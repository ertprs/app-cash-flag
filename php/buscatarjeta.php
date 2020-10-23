<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$saldo = 0.00;
$vencimiento = '12/2020';
// Buscar prepago
$query = "SELECT * from prepago where card=".$_GET["t"];
if($result = mysqli_query($link, $query)){
	if ($row = mysqli_fetch_array($result)) {
		$idproveedor = $row["id_proveedor"];
		$idsocio = $row["id_socio"];
		$tipo = 'prepago';
		$nombres = trim($row["nombres"])." ".trim($row["apellidos"]);
		$saldo = $row["saldo"]-$row["saldoentransito"];
		$vencimiento = $row["validez"];
		$qr = '';
		$moneda = $row["moneda"];
	} else {
		$query = "SELECT * from giftcards where card=".$_GET["t"];
		$result = mysqli_query($link, $query);
		if ($row = mysqli_fetch_array($result)) {
			$idproveedor = $row["id_proveedor"];
		 	$idsocio = $row["id_socio"];
			$tipo = 'giftcard';
			$nombres = trim($row["nombres"])." ".trim($row["apellidos"]);
			$saldo = $row["saldo"];
			$vencimiento = $row["validez"];
			$qr = '';
			$moneda = $row["moneda"];
		}
	}
} else {
	alert("Tarjeta no existe.");
}

// Buscar proveedor
$query = "SELECT * from proveedores where id=".$idproveedor;
// $query = "SELECT * from proveedores where id=3";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$logo = $row["logo"];
	$logo = ($tipo=='prepago') ? $row["logoprepago"] : $row["logogiftcard"] ;
}

// Buscar datos cuenta AE
$query = "SELECT * from socios where id=".$idsocio;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$secretkey = $row["secretkey"];
	$account = $row["account"];
} else {
	$secretkey = "";
	$account = "";
}

// Buscar moneda
$dibujomoneda = "";
$query = "SELECT * from _monedas where moneda='".$moneda."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$dibujomoneda       = $row["dibujo"];
	$dibujomonedablanco = $row["dibujoblanco"];
	$simbolomoneda      = $row["simbolo"];
}

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

$respuesta = '{"logocard":"'.$logo.'","tipo":"'.$tipo.'","nombres":"'.$nombres.'","vencimiento":"'.$vencimiento.'","saldo":'.$saldo.',"qr":"'.$qr.'","idproveedor":'.$idproveedor.',"moneda":"'.$moneda.'","dibujomoneda":"'.$dibujomoneda.'","dibujomonedablanco":"'.$dibujomonedablanco.'","secretkey":"'.$secretkey.'","publickey":"'.$account.'","simbolomoneda":"'.$simbolomoneda.'","bsdolar":'.$bsdolar.',"usdae":'.$usdae.'}';

echo $respuesta;
?>
