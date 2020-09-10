<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

if ($_POST["id"]=="New") {

    $query  = 'INSERT INTO proveedores (nombre, email, logo, logoprepago, logogiftcard, ';
    $query .= 'direccion, rif, contacto, telefono, categoria, claveadmin, clavecanje, color, status, secretkey, account) ';
    $query .= 'VALUES ("'.$_POST["nombre"].'","'.$_POST["email"].'","","","","'.$_POST["direccion"].'",';
    $query .= '"'.$_POST["rif"].'","'.$_POST["contacto"].'","'.$_POST["telefono"].'",';
    $query .= '"'.$_POST["categoria"].'","","","",'.$_POST["status"].', "'.$_POST["secretkey"].'", "'.$_POST["account"].'")';
} else {
    $query  = 'UPDATE proveedores SET nombre="'.$_POST["nombre"].'", email="'.$_POST["email"].'", ';
    $query .= 'direccion="'.$_POST["direccion"].'", rif="'.$_POST["rif"].'", ';
    $query .= 'contacto="'.$_POST["contacto"].'", telefono="'.$_POST["telefono"].'", ';
    $query .= 'categoria="'.$_POST["categoria"].'", status='.$_POST["status"].' ';
    $query .= 'WHERE id='.$_POST["id"];
}
if($result = mysqli_query($link, $query)) {
	$respuesta = '{"exito":"SI",';
    $respuesta .= '"mensaje":"Registro exitoso"}';
} else {
	$respuesta = '{"exito":"NO"}';
}
echo $respuesta;
?>
