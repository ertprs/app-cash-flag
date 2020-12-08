<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

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
// $usdae = 0.1; //   O J O Borrar o comentar
////////////////////////////////////////////////////////////////////////////
// Buscar parametros
$query  = "SELECT * from _parametros";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$acctcf = $row["account"];
	$bsdolar = $row["dolar"];
} else {
	$acctcf = "";
	$bsdolar = 0.00;
}

$query = "SELECT * from proveedores where id = " . $_GET["prov"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$sinclave = ($row["clavecanje"]=="") ? 1 : 0 ;
	$respuesta = '{"exito":"SI","proveedor":{"nombre":"' . utf8_encode($row["nombre"]) . '","logo":"' . $row["logo"] . '","sinclave":'.$sinclave.',"skey":"'.$row["secretkey"].'","acct":"'.$row["account"].'"},"acctcf":"'.$acctcf.'","bsdolar":'.$bsdolar.',"usdae":'.$usdae.'}';
} else {
	$respuesta = '{"exito":"NO","proveedor":{},"acctcf":"","bsdolar":0.00,"usdae":0.00}';
}
echo $respuesta;
?>
