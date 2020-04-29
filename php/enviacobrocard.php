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
	$documento = generatransaccion_pdv($link);
	$status = 'Confirmada'; // Status pendiente por confirmación

	if ($id_proveedor==$cardProveedor) {
		// Calcular disponibilidad
		$disponible = $saldo - $saldoentransito;
		if ($disponible - $monto > 0.00) {
			// Insertar transacción para confirmar
			$query  = "INSERT INTO pdv_transacciones (fecha, id_proveedor, id_socio, tipo, moneda, monto, ";
			$query .= "instrumento, id_instrumento, documento, status, origen, token) ";
			$query .= "VALUES ('".$fecha."',".$id_proveedor.",".$id_socio.",'".$tipo."','".$cardmoneda."',".$monto;
			$query .= ",'".$instrumento."','".$id_instrumento."','".$documento."','".$status."','".$origen."','".$token."')";
			if ($result = mysqli_query($link, $query)) {
				$saldo -= $monto;
				if ($instrumento=='prepago') {
					$query = 'UPDATE prepago SET saldo='.$saldo.' WHERE card="'.trim($id_instrumento).'"';
				} else {
					$query = 'UPDATE giftcards SET saldo='.$saldo.' WHERE card="'.trim($id_instrumento).'"';
				}
				if ($result = mysqli_query($link, $query)) {
					$mensaje = '["Registro exitoso."]';
					$respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"transaccion":"'.$documento.'"';
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
		$respuesta = '{"exito":"NO","mensaje":"No coinciden tarjeta y comercio","transaccion":"'.$documento.'"}';
	}	
} else {
	$respuesta = '{"exito":"NO","mensaje":"Número de tarjeta no existe.","transaccion":""}';
}
echo $respuesta;
?>
