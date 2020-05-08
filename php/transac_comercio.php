<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query  = 'SELECT prepago_transacciones.id, "prepago" as tipocard, prepago_transacciones.tipomoneda, prepago_transacciones.fecha, prepago_transacciones.montobs, prepago_transacciones.montodolares, prepago_transacciones.origen, prepago_transacciones.documento, prepago_transacciones.card from prepago_transacciones where prepago_transacciones.idproveedor='.$_GET["idproveedor"].' and prepago_transacciones.status="Por confirmar pago" union SELECT giftcards_transacciones.id, "giftcard" as tipocard, giftcards_transacciones.tipomoneda, giftcards_transacciones.fecha, giftcards_transacciones.montobs, giftcards_transacciones.montodolares, giftcards_transacciones.origen, giftcards_transacciones.documento, giftcards_transacciones.card from giftcards_transacciones where giftcards_transacciones.idproveedor='.$_GET["idproveedor"].' and giftcards_transacciones.status="Por confirmar pago"';
$result = mysqli_query($link, $query);
$respuesta = '{"transacciones":';
$respuesta .= '[';
$first = true;
while ($row = mysqli_fetch_array($result)) {
	if ($first) {
		$coma = "";
		$first = false;
	} else {
		$coma = ",";
	}
	$respuesta .= $coma.'{'; 
	$respuesta .= '"id":'          .$row["id"]          .',';
	$respuesta .= '"tipocard":"'   .$row["tipocard"]    .'",';
	$respuesta .= '"tipomoneda":"' .$row["tipomoneda"]  .'",';
	$respuesta .= '"fecha":"'      .$row["fecha"]       .'",';
	$respuesta .= '"montobs":'     .$row["montobs"]     .',';
	$respuesta .= '"montodolares":'.$row["montodolares"].',';
	$respuesta .= '"origen":"'     .$row["origen"]      .'",';
	$respuesta .= '"documento":"'  .$row["documento"]   .'",';
	$respuesta .= '"card":"'       .$row["card"]        .'"';
	$respuesta .= '}';
}
$respuesta .= ']';
$respuesta .= '}';

echo $respuesta;
?>
