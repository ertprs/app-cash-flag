<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query  = 'INSERT INTO contratos (razonsocial, nombre, rif, direccion, ';
$query .= 'email, firmasgc, firmacliente, fecha, hash) VALUES (';
$query .= '"'.$_POST["razonsocial"].'", "'.$_POST["nombre"].'", "'.$_POST["rif"].'", ';
$query .= '"'.$_POST["direccion"].'", "'.$_POST["email"].'", "'.$_POST["firmasgc"].'", ';
$query .= '"'.$_POST["firmacliente"].'", "'.$_POST["fecha"].'", "'.$_POST["hash"].'")';
if($result = mysqli_query($link, $query)) {
	$respuesta = '{"exito":"SI",';
    $respuesta .= '"mensaje":"Registro exitoso"}';
} else {
	$respuesta = '{"exito":"NO"}';
}
echo $respuesta;
?>
