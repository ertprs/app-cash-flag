<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("./funciones.php");

$query = "select * from socios where id=2";
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_array($result)) {
   $telefono=$row["telefono"];
   // mensajebienvenida($row);
   // enviasms($telefono,"Quieres incrementar el saldo de tu tarjeta prepagada PREMIUM, has recibido un email de bienvenida  con una invitacion, aceptala y gana con Cash-Flag!");
}
?>

