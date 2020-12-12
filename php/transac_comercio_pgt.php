<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query  = 'SELECT prepago_transacciones.id, prepago_transacciones.idproveedor, proveedores.nombre, "prepago" as tipocard, prepago_transacciones.tipomoneda, prepago_transacciones.fecha, prepago_transacciones.montobs, prepago_transacciones.montodolares, prepago_transacciones.origen, prepago_transacciones.documento, prepago_transacciones.card from prepago_transacciones inner join proveedores on prepago_transacciones.idproveedor=proveedores.id where prepago_transacciones.menu="'.$_GET["menu"].'" and prepago_transacciones.status="Por confirmar pago" union SELECT giftcards_transacciones.id, giftcards_transacciones.idproveedor, proveedores.nombre, "giftcard" as tipocard, giftcards_transacciones.tipomoneda, giftcards_transacciones.fecha, giftcards_transacciones.montobs, giftcards_transacciones.montodolares, giftcards_transacciones.origen, giftcards_transacciones.documento, giftcards_transacciones.card from giftcards_transacciones inner join proveedores on giftcards_transacciones.idproveedor=proveedores.id where giftcards_transacciones.menu="'.$_GET["menu"].'" and giftcards_transacciones.status="Por confirmar pago"';
// echo $query;
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

	$tipocard = ($row["idproveedor"]==3) ? trim($row["tipocard"])." PREMIUM" : trim($row["tipocard"])." ".$row["nombre"];

	$respuesta .= $coma.'{'; 
	$respuesta .= '"id":'          .$row["id"]          .',';
	$respuesta .= '"txttipocard":"'.$tipocard           .'",';
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
