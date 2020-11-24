<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$moneda = 'ae';
$premium = true;
$existe = false;

// Buscar tarjetas
$query  = "SELECT * from socios where id=".$_GET["idsocio"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {

	$quer2 = "select * from prepago where id_socio=".$_GET["idsocio"]." and moneda='".$moneda."' and premium=".$premium;
	$resul2 = mysqli_query($link, $quer2);
	if ($ro2 = mysqli_fetch_array($resul2)) {
		$saldo = $ro2["saldo"];
		$existe = true;
	} else {
		$saldo = 0;
		$existe = false;
	}

	$respuesta  = '{';
   $respuesta .= '"exito":"SI",';
   $respuesta .= '"secretkey":"'.$row["secretkey"].'",';
	$respuesta .= '"publickey":"'.$row["account"].'",';
	$respuesta .= '"saldo":'.$saldo.',';
	$respuesta .= '"existe":'.$existe;
	$respuesta .= '}';
} else {
	$respuesta  = '{';
	$respuesta .= '"exito":"NO",';
	$respuesta .= '"secretkey":"",';
	$respuesta .= '"publickey":"",';
	$respuesta .= '"saldo":0,';
	$respuesta .= '"existe":'.$existe;
	$respuesta .= '}';
}
echo $respuesta;
?>
