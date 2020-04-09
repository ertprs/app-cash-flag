<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query = "SELECT * from proveedores where id = " . $_GET["prov"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$sinclave = ($row["clavecanje"]=="") ? 1 : 0 ;
	$respuesta = '{"exito":"SI","proveedor":{"nombre":"' . utf8_encode($row["nombre"]) . '","logo":"' . $row["logo"] . '","sinclave":'.$sinclave.'}}';
} else {
	$respuesta = '{"exito":"NO","proveedor":{}}';
}
echo $respuesta;
?>
