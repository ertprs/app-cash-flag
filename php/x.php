<?php
include_once("./funciones.php");
$aux  =  rand(10000, 99999);
$pwd  = hash("sha256","4445515525230001".$aux);
echo $aux;
echo "<br/>";
echo $pwd
?>
