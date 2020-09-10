<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

// Buscar tarjetas
$query  = "SELECT * from socios where id=".$_GET["idsocio"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$respuesta  = '{';
   $respuesta .= '"exito":"SI",';
   $respuesta .= '"secretkey":"'.$row["secretkey"].'",';
	$respuesta .= '"publickey":"'.$row["account"].'"';
	$respuesta .= '}';
} else {
	$respuesta  = '{';
	$respuesta .= '"exito":"NO",';
	$respuesta .= '"secretkey":"",';
	$respuesta .= '"publickey":""';
	$respuesta .= '}';
}
echo $respuesta;
?>
