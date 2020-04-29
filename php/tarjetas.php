<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

// Buscar giftcards
$query = "SELECT * from socios where id=".$_GET["idsocio"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$email = $row["email"];

	$respuesta = '{"exito": "SI", "tarjetas":[';
	$coma = '';
	$inicio = true;

	// Buscar prepagos
	$query = "SELECT proveedores.nombre, 'prepago' as tipo, simbolo, card, saldo, saldoentransito, validez, prepago.status from prepago left outer join proveedores on prepago.id_proveedor=proveedores.id left outer join _monedas on prepago.moneda=_monedas.moneda where prepago.email='".$email."' union SELECT proveedores.nombre, 'giftcard' as tipo, simbolo, card, saldo, 0 as saldoentransito, validez, giftcards.status from giftcards left outer join proveedores on giftcards.id_proveedor=proveedores.id left outer join _monedas on giftcards.moneda=_monedas.moneda where giftcards.email='".$email."' order by status, nombre, tipo desc, validez, card";
	$result = mysqli_query($link, $query);
	while ($row = mysqli_fetch_array($result)) {
		$monto = $row["saldo"]-$row["saldoentransito"];
		if($monto>0) {
			if ($inicio) {
				$coma = '';
				$inicio = false;
			} else {
				$coma = ',';
			}
			$respuesta .= $coma.'{';
			$respuesta .= '"nombre":"'.$row["nombre"].'",';
			$respuesta .= '"tipo":"'.$row["tipo"].'",';
			$respuesta .= '"simbolo":"'.$row["simbolo"].'",';
			$respuesta .= '"tarjeta":"'.$row["card"].'",';
			$respuesta .= '"saldo":'.$monto.',';
			$respuesta .= '"validez":"'.$row["validez"].'",';
			$respuesta .= '"status":"'.$row["status"].'"';
			$respuesta .= '}';
		}
	}
	$respuesta .= ']}';
} else {
	$respuesta = '{"exito": "NO"}';
}

// // Buscar giftcards
// $query = "SELECT moneda, card, saldo, validez, status from giftcards where id_socio=".$_GET["idsocio"];
// $result = mysqli_query($link, $query);
// while ($row = mysqli_fetch_array($result)) {
// 	if ($inicio) {
// 		$coma = '';
// 		$inicio = false;
// 	} else {
// 		$coma = ',';
// 	}
// 	$respuesta .= $coma.'{';
// 	$respuesta .= '"tipo":"prepago",';
// 	$respuesta .= '"moneda":"'.$row["moneda"].'",';
// 	$respuesta .= '"saldo":'.$row["saldo"].',';
// 	$respuesta .= '"validez":"'.$row["validez"].'",';
// 	$respuesta .= '"status":"'.$row["status"].'"';
// 	$respuesta .= '}';
// }

echo $respuesta;
?>
