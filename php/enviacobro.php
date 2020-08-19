<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("funciones.php");
include_once("../lib/phpqrcode/qrlib.php");

// Buscar tipo de instrumento
$instrumento = "";
$query = "select * from cards where card='".trim($_POST["tarjeta"])."'";
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
	$moneda = $_POST["moneda"];
	$monto = $_POST["monto"];
	$id_instrumento = $_POST["tarjeta"];
	$documento = generatransaccion_pdv($link, $database);
	$status = 'Por confirmar'; // Estatus pendiente por confirmación
	$tipotransaccion = '51'; // Consumo
	switch ($moneda) {
		case 'bs':
			$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
			break;
		case 'dolar':
			$montobs = 0.00; $montodolares = $monto; $montocripto = 0.00; 
			break;
		case 'cripto':
			$montobs = 0.00; $montodolares = 0.00; $montocripto = $monto; 
			break;
		default:
			$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
			break;
	}
	$tasadolarbs = 1.00;
	$tasadolarcripto = 1.00;

	if ($id_proveedor==$cardProveedor && $moneda==$cardMoneda) {
		// Calcular disponibilidad
		$disponible = $saldo - $saldoentransito;
		if ($disponible - $monto > 0.00) {
			/////////////////////////////////////////////////////////////////////////////////////
			$query = "INSERT INTO ".$tpcard." (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$id_socio.",".$id_proveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$documento."','','".$status."','".$id_instrumento."')";
			$result = mysqli_query($link, $query);
			/////////////////////////////////////////////////////////////////////////////////////
			// Insertar transacción para confirmar
			$query  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, ';
			$query .= 'instrumento, id_instrumento, documento, status, origen, token) ';
			$query .= 'VALUES ("'.$fecha.'","0000-00-00",'.$id_proveedor.','.$id_socio.',"'.$tipo.'","'.$moneda.'",';
			$query .= $monto.',"'.$instrumento.'","'.$id_instrumento.'","'.$documento.'","'.$status;
			$query .= '","","")';
			if ($result = mysqli_query($link, $query)) {
				$saldoentransito += $monto;
				if ($instrumento=='prepago') {
					$query = 'UPDATE prepago SET saldoentransito='.$saldoentransito.' WHERE card="'.trim($id_instrumento).'"';
				} else {
					$query = 'UPDATE giftcards SET saldoentransito='.$saldoentransito.' WHERE card="'.trim($id_instrumento).'"';
				}
				if ($result = mysqli_query($link, $query)) {
					$mensaje = '["Registro exitoso."]';
					$respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"';

					$quer2   = "SELECT * from pdv_transacciones where id_proveedor=".$id_proveedor;
					$quer2  .= " and (status='Por confirmar' or status='Confirmada')";
					$quer2  .= " order by status desc,id_instrumento";
					$resul2 = mysqli_query($link, $quer2);
					$respuesta .= ',"transacciones":';
						$respuesta .= '[';
						$first = true;
						while ($row = mysqli_fetch_array($resul2)) {
							if ($row["fecha"]==date("Y-m-d")) {
								if ($first) {
									$coma = "";
									$first = false;
								} else {
									$coma = ",";
								}
								$respuesta .= $coma.'{'; 
								$respuesta .= '"id":'.$row["id"];
								$respuesta .= ','.'"tarjeta":"'.trim($row["id_instrumento"]).'"';
								$respuesta .= ','.'"referencia":"'.trim($row["documento"]).'"';
								$respuesta .= ','.'"monto":'.$row["monto"];
								$respuesta .= ','.'"status":"'.$row["status"].'"';
								$respuesta .= '}';
							}
						}
						$respuesta .= ']';
					$respuesta .= '}';
				} else {
					$mensaje = '["Fallo el registro, por favor comuniquese con soporte técnico."]';
					$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
				}
			} else {
				$mensaje = '["Fallo el registro, por favor comuniquese con soporte técnico."]';
				$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
			}
		} else {
			$mensaje = '["Ups! Ocurrió un problema."';
			$mensaje .= ',"Aparentemente el comprador no tiene suficiente saldo disponible."';
			if ($saldo - $_POST["monto"] > 0.00) {
				$mensaje .= ',"Tiene transacciones pendientes por confirmar o rechazar."';
			} else {
				$mensaje .= ',"Puede recargar saldo a esta tarjeta para poder usarla."]';
			}
			$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
		}
	} else {
		if ($id_proveedor<>$cardProveedor) {
			if ($moneda<>$cardMoneda) {
				$respuesta = '{"exito":"NO","mensaje":"No coinciden tarjeta, comercio y tipo de moneda","transaccion":"'.$documento.'"}';
			} else {
				$respuesta = '{"exito":"NO","mensaje":"No coinciden tarjeta y comercio","transaccion":"'.$documento.'"}';
			}
		} else {
			if ($moneda<>$cardMoneda) {
				$respuesta = '{"exito":"NO","mensaje":"No coinciden tarjeta y tipo de moneda","transaccion":"'.$documento.'"}';
			}
		}
	}	
} else {
	$respuesta = '{"exito":"NO","mensaje":"Número de tarjeta no existe.","transaccion":""}';
}
echo $respuesta;
?>
