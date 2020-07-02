<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("funciones.php");

$firmasgc     = hash("sha256","SGC Consultores C.A."."Cash-Flag"."J-40242441-8"."info@cash-flag.com".date("Y-m-d"));
$firmacliente = hash("sha256",$_POST['razonsocial'].$_POST['nombre'].$_POST['rif'].$_POST['email'].date("Y-m-d"));

$respuesta = '{"exito":"SI","firmasgc":"'.$firmasgc.'","firmacliente":"'.$firmacliente.'"}';

echo $respuesta;
?>
