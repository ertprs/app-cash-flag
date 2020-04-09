<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query = "SELECT logo from proveedores where id = " . $_GET["id"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$logo = ($row["logo"]=="") ? "sin_imagen.jpg" : $row["logo"] ;
	$respuesta = '{"exito":"SI","logo":"'.$logo . '"}';
} else {
	$respuesta = '{"exito":"NO","logo":"sin_imagen.jpg"}';
}

echo $respuesta;
?>
