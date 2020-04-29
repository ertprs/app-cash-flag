<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("../php/funciones.php");

$card  = $_POST["card"];
$token = $_POST["token"];
$monto = $_POST["monto"];

$query = 'select * from pdv_transacciones where token="'.$token.'"';
if ($result = mysqli_query($link, $query)) {
	if ($row = mysqli_fetch_array($result)) {
		$cardtx = $row["id_instrumento"];
		$status = $row["status"];
		$mntotx = $row["monto"];
		$dctotx = $row["documento"];
		if ($card==$cardtx && $monto==$mntotx) {
			if ($status=="Confirmada") {
				$respuesta = '{"exito":"SI","documento":"'.$dctotx.'","status":0,"mensaje":"Transacción exitosa"}';
			} else {
				$respuesta = '{"exito":"NO","documento":"","status":1,"mensaje":"Transacción no confirmada por el usuario"}';
			}
		} else {
			if ($card==$cardtx) {
				$respuesta = '{"exito":"NO","documento":"","status":2,"mensaje":"Monto incorrecto"}';
			} else {
				if ($monto==$mntotx) {
					$respuesta = '{"exito":"NO","documento":"","status":2,"mensaje":"Tarjeta incorrecta"}';
				} else {
					$respuesta = '{"exito":"NO","documento":"","status":2,"mensaje":"Tarjeta y monto incorrectos"}';
				}
			}		
		}
	} else {
		$respuesta = '{"exito":"NO","documento":"","status":3,"mensaje":"Transacción no existe"}';
	}
} else {
	$respuesta = '{"exito":"NO","documento":"","status":3,"mensaje":"Transacción no existe"}';
}
echo $respuesta;
?>
