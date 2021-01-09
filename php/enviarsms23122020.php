<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("./funciones.php");

$query = "SELECT * from telefonos_promocion where status='Pendiente'";
$result = mysqli_query($link, $query);
Echo 'Inicio -> ';
while ($row = mysqli_fetch_array($result)) {
   $telefono = trim($row["telefono"]);
   $id       = $row["id"];

   $mensaje = utf8_decode('En Mr. Falafel tu compra te premia, afiliate a Cash-Flag y recibe unos mini falafel en tu proxima compra, ingresa antes del 31/12 en http://bit.ly/37JrYKE');
   $respuesta1 = enviasms($telefono,$mensaje);

   $quer2 = "UPDATE telefonos_promocion SET status='Enviado' where id=".$id;
   echo '.';
   // $resul2 = mysqli_query($link, $quer2);   
}
Echo ' -> Fin!';
?>