<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

// Buscar cambiodolar
$bsdolar = 0;
$query = "SELECT dolar from _parametros";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$bsdolar = $row["dolar"];
}
////////////////////////////////////////////////////////////////////////////
$coinmarketcapkey = 'f82b18ca-28cd-4fbd-8197-a2c5c1d232b4';
$symbol = 'AE';

$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol='.$symbol.'&CMC_PRO_API_KEY='.$coinmarketcapkey;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);
$result = curl_exec($ch);
$info = json_decode($result,true);

curl_close($ch);

$usdae = $info["data"]["AE"]["quote"]["USD"]["price"];
////////////////////////////////////////////////////////////////////////////
// Buscar giftcards
$query = "SELECT * from socios where id=".$_GET["idsocio"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$email = $row["email"];

	$respuesta = '{"exito": "SI", "tarjetas":[';
	$coma = '';
	$inicio = true;

	// Buscar prepagos
	$query = "SELECT proveedores.nombre, 'prepago' as tipo, prepago.moneda, simbolo, card, saldo, saldoentransito, validez, prepago.status, prepago.premium from prepago left outer join proveedores on prepago.id_proveedor=proveedores.id left outer join _monedas on prepago.moneda=_monedas.moneda where prepago.email='".$email."' union SELECT proveedores.nombre, 'giftcard' as tipo, giftcards.moneda, simbolo, card, saldo, 0 as saldoentransito, validez, giftcards.status, giftcards.premium from giftcards left outer join proveedores on giftcards.id_proveedor=proveedores.id left outer join _monedas on giftcards.moneda=_monedas.moneda where giftcards.email='".$email."' order by premium desc, status, nombre, tipo desc, validez, card";
	$result = mysqli_query($link, $query);
	while ($row = mysqli_fetch_array($result)) {
		if ($row["tipo"]==$_GET["tipo"]) {
			$monto = $row["saldo"]-$row["saldoentransito"];
			if($monto>0 || $row["premium"]) {
				if ($inicio) {
					$coma = '';
					$inicio = false;
				} else {
					$coma = ',';
				}
				$respuesta .= $coma.'{';
				$respuesta .= '"nombre":"'.$row["nombre"].'",';
				$respuesta .= '"tipo":"'.$row["tipo"].'",';
				$respuesta .= '"moneda":"'.$row["moneda"].'",';
				$respuesta .= '"simbolo":"'.$row["simbolo"].'",';
				$respuesta .= '"tarjeta":"'.$row["card"].'",';
				$respuesta .= '"saldo":'.$monto.',';
				$respuesta .= '"validez":"'.$row["validez"].'",';
				$respuesta .= '"status":"'.$row["status"].'",';
				$respuesta .= '"premium":'.$row["premium"];
				$respuesta .= '}';
			}
		}
	}
	$respuesta .= '],"bsdolar":'.$bsdolar.',"usdae":'.$usdae.'}';
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
