<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$respuesta = '{"exito":"SI","monedas":[';

$query = "SELECT * FROM _criptomonedas order by nombremoneda";

if ($result = mysqli_query($link, $query)) {
   $first = true;
   while ($row = mysqli_fetch_array($result)) {
      if ($first) {
         $first = false;
         $coma = '';
      } else {
         $coma = ',';
      }
      $respuesta .= $coma.'{"symbol":"'.strtoupper($row["symbol"]).'","name":"'.$row["nombremoneda"].'"}';
      // $respuesta .= $coma.'{"symbol":"'.strtoupper($info["symbol"]).'","name":"'.$info["name"].'","image":"'.'https://www.simpleswap.io'.$info["image"].'","min":0}';
   }
}

$respuesta .= ']}';

echo $respuesta;
?>