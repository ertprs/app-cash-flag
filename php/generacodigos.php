<?php
include_once("../_config/conexion.php");
include_once("funciones.php");

$nombres         = 'Luis';
$apellidos       = 'Rodríguez';
$telefono        = '+58424071820';
$email           = 'soluciones2000@gmail.com';
$nombreproveedor = 'Mr. Falafel';
$moneda          = 'Dolar';

// Generar numero de tarjeta partiendo de los datos enviados
$card = generaprepago($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);

echo $card;

?>
