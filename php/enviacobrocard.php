<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("funciones.php");
include_once("../lib/phpqrcode/qrlib.php");

// Buscar tipo de instrumento
$instrumento = "";
$query = "select * from cards where card='".trim($_POST["tarjeta"])."'";
$token = isset($_POST["token"]) ? $_POST["token"] : "" ;
$origen = isset($_POST["origen"]) ? $_POST["origen"] : "" ;
$result = mysqli_query($link, $query);
$instrumento = ($row = mysqli_fetch_array($result)) ? $row["tipo"] : "" ;
// if ($row = mysqli_fetch_array($result)) {
// 	$instrumento = $row["tipo"];
// } else {
// 	$instrumento = "";
// }

if ($instrumento<>"") {
	// Buscar tipo de instrumento
	$id_socio = 0;
	$saldo = 0.00;
	$saldoentransito = 0.00;
	if ($instrumento=='prepago') {
		$tpcard = "prepago_transacciones";
		$query = "select * from prepago where card='".trim($_POST["tarjeta"])."'";
		$result = mysqli_query($link, $query);
		if ($row = mysqli_fetch_array($result)) {
			$id_socio = $row["id_socio"];
			$saldo = $row["saldo"];
	    	$saldoentransito = $row["saldoentransito"];
			$cardProveedor = $row["id_proveedor"];
			$cardMoneda    = $row["moneda"];
		}
	} else {
		$tpcard = "giftcards_transacciones";
		$query = "select * from giftcards where card='".trim($_POST["tarjeta"])."'";
		$result = mysqli_query($link, $query);
		if ($row = mysqli_fetch_array($result)) {
			$id_socio = $row["id_socio"];
		    $saldo = $row["saldo"];
	    	$saldoentransito = 0.00;
			$cardProveedor = $row["id_proveedor"];
			$cardMoneda    = $row["moneda"];
		}
	}

	// Asignar parámetros a variables
	$fecha = date("Y-m-d");
	$id_proveedor = $_POST["idproveedor"];
	$tipo = '01'; // Cobro
	$monto = $_POST["monto"];
	$id_instrumento = $_POST["tarjeta"];
	$documento = generatransaccion_pdv($link, $database);
	$status = 'Confirmada'; // Status pendiente por confirmación
	$tipotransaccion = '51'; // Consumo
	$moneda = $cardMoneda;
	switch ($moneda) {
		case 'bs':
			$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
			break;
		case 'dolar':
			$montobs = 0.00; $montodolares = $monto; $montocripto = 0.00; 
			break;
		case 'ae':
			$montobs = 0.00; $montodolares = 0.00; $montocripto = $monto; 
			break;
		default:
			$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
			break;
	}
	$tasadolarbs = 1.00;
	$tasadolarcripto = 1.00;

	if ($id_proveedor==$cardProveedor) {
		// Calcular disponibilidad
		$disponible = $saldo - $saldoentransito;
		if ($disponible - $monto >= 0.00) {
			$query = "select count(id) as increment from pdv_transacciones";
			$result = mysqli_query($link,$query);
			if($row = mysqli_fetch_array($result)) {
					  $pdv_id = $row["increment"];
			} else {
					  $pdv_id = 0;
			}
			// $querx = "select auto_increment from information_schema.tables where table_schema='clubdeconsumidores' and table_name='pdv_transacciones'";
			// $resulx = mysqli_query($link,$querx);
			// if($rox = mysqli_fetch_array($resulx)) {
			// 	$pdv_id = $rox["auto_increment"];
			// } else {
			// 	$pdv_id = 0;
			// }
			/////////////////////////////////////////////////////////////////////////////////////
			$query = "INSERT INTO ".$tpcard." (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio, menu, formapago) VALUES (".$id_socio.",".$id_proveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$documento."','','".$status."','".$id_instrumento."',".$id_proveedor.", 'socio', '".$instrumento."')";
			$result = mysqli_query($link, $query);
			/////////////////////////////////////////////////////////////////////////////////////
			// Insertar transacción para confirmar
			$query  = "INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, ";
			$query .= "instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ";
			$query .= "VALUES ('".$fecha."','".$fecha."',".$id_proveedor.",".$id_socio.",'".$tipo."','".$moneda."',".$monto;
			$query .= ",'".$instrumento."','".$id_instrumento."','".$documento."','".$status."','".$origen."','".$token."',0,'')";
			if ($result = mysqli_query($link, $query)) {
				$saldo -= $monto;
				$nuevodisponible = $saldo - $saldoentransito;
				if ($instrumento=='prepago') {
					$query = 'UPDATE prepago SET saldo='.$saldo.' WHERE card="'.trim($id_instrumento).'"';
				} else {
					$query = 'UPDATE giftcards SET saldo='.$saldo.' WHERE card="'.trim($id_instrumento).'"';
				}
				if ($result = mysqli_query($link, $query)) {
					$mensaje = '["Registro exitoso."]';
					$respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"fecha":"'.$fecha.'","transaccion":"'.$documento.'","pdv_id":'.$pdv_id.',"nuevosaldo":'.$nuevodisponible;
					$respuesta .= '}';
				} else {
					$mensaje = '["Fallo el registro, por favor comuniquese con soporte técnico."]';
					$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'","pdv_id":0}';
				}
			} else {
				$mensaje = '["Fallo el registro, por favor comuniquese con soporte técnico."]';
				$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'","pdv_id":0}';
			}
		} else {
			$mensaje = '["Ups! Ocurrió un problema."';
			$mensaje .= ',"Aparentemente el comprador no tiene suficiente saldo disponible."';
			if ($saldo - $_POST["monto"] > 0.00) {
				$mensaje .= ',"Tiene transacciones pendientes por confirmar o rechazar."';
			} else {
				$mensaje .= ',"Puede recargar saldo a esta tarjeta para poder usarla."]';
			}
			$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'","pdv_id":0}';
		}
	} else {
		$respuesta = '{"exito":"NO","mensaje":"No coinciden tarjeta y comercio","transaccion":"'.$documento.'","pdv_id":0}';
	}	
} else {
	$respuesta = '{"exito":"NO","mensaje":"Número de tarjeta no existe.","transaccion":"","pdv_id":0}';
}
echo $respuesta;
?>
