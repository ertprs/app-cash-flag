<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("../php/funciones.php");


$hash = hash("sha256",$_POST['id_proveedor'].$_POST["clavecanje"]);

// Buscar datos de proveedor
$query = 'UPDATE proveedores SET clavecanje="'.$hash.'" WHERE id='.$_POST['id_proveedor'];
$result = mysqli_query($link, $query);
$respuesta = '{"exito":"SI","mensaje":"Clave cambiada exitosamente"}';

echo $respuesta;
?>
