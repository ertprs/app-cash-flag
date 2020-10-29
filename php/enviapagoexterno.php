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
	$moneda = $cardMoneda;
	$monto = $_POST["monto"];
	$id_instrumento = $_POST["tarjeta"];
	$documento = $_POST["token"];
	$status = 'Confirmada'; // Status confirmada
	$tipotransaccion = '53'; // Consumo
	switch ($moneda) {
		case 'bs':
			$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
			break;
		case 'dolar':
			$montobs = 0.00; $montodolares = $monto; $montocripto = 0.00; 
			break;
		// case 'cripto':
		// 	$montobs = 0.00; $montodolares = 0.00; $montocripto = $monto; 
		// 	break;
      case 'ae':
         $montobs = 0.00; $montodolares = 0.00; $montocripto = $monto; 
         break;
      default:
			$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
			break;
	}
	$tasadolarbs = 1.00;
	$tasadolarcripto = 1.00;

	// Calcular disponibilidad
	$disponible = $saldo - $saldoentransito;
	if ($disponible - $monto > 0.00) {
		/////////////////////////////////////////////////////////////////////////////////////
		$query = "INSERT INTO ".$tpcard." (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$id_socio.",".$id_proveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$documento."','','".$status."','".$id_instrumento."')";
      if ($result = mysqli_query($link, $query)) {
			$saldo -= $monto;
			if ($instrumento=='prepago') {
				$query = 'UPDATE prepago SET saldo='.$saldo.' WHERE card="'.trim($id_instrumento).'"';
			} else {
				$query = 'UPDATE giftcards SET saldo='.$saldo.' WHERE card="'.trim($id_instrumento).'"';
			}
			if ($result = mysqli_query($link, $query)) {
				$mensaje = '["Registro exitoso."]';
				$respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
			} else {
				$mensaje = '["Fallo el registro, por favor comuniquese con soporte técnico."]';
				$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"}';
			}
		} else {
			$mensaje = '["Fallo el registro, por favor comuniquese con soporte técnico."]';
			$respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'","pdv_id":0}';
		}
	} else {
      $mensaje = '["Ups! Ocurrió un problema."';
      $mensaje .= ',"Aparentemente el comprador no tiene suficiente saldo disponible."';
      $mensaje .= ',"Puede recargar saldo a esta tarjeta para poder usarla."]';
      $respuesta = '{"exito":"NO","mensaje":'.$mensaje.',"transaccion":"'.$documento.'","pdv_id":0}';
	}	
} else {
	$respuesta = '{"exito":"NO","mensaje":"Número de tarjeta no existe.","transaccion":"","pdv_id":0}';
}
echo $respuesta;
?>
