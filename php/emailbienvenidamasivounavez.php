<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("./funciones.php");

$query = "select * from socios where id=1";
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_array($result)) {
   $telefono=$row["telefono"];
   enviasms($telefono,"¿Quieres incrementar el saldo de tu tarjeta prepagada Cash-Flag? revisa tu correo, haz recibido un email de bienvenida  con una invitación ¡acéptala!");
}
?>
