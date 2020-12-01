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
      $exito = "SI";
		$saldo = $ro2["saldo"];
      $skey = $row["secretkey"];
      $pkey = $row["account"];
      $existe = true;
	} else {
      $exito = "NO";
		$saldo = 0;
      $skey = "";
      $pkey = "";
		$existe = false;
	}
} else {
   $exito = "NO";
   $saldo = 0;
   $skey = "";
   $pkey = "";
   $existe = false;
}
$respuesta  = '{';
$respuesta .= '"exito":"'.$exito.'",';
$respuesta .= '"secretkey":"'.$row["secretkey"].'",';
$respuesta .= '"publickey":"'.$row["account"].'",';
$respuesta .= '"saldo":'.$saldo.',';
$respuesta .= '"existe":'.$existe;
$respuesta .= '}';

echo $respuesta;
?>
