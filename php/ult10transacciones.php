<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

// Buscar tipo de instrumento
$instrumento = "";
$query = "select * from cards where card='".trim($_GET["card"])."'";
$result = mysqli_query($link, $query);
$instrumento = ($row = mysqli_fetch_array($result)) ? $row["tipo"] : "" ;

if ($instrumento<>"") {
   // Buscar tipo de instrumento
   $tpcard = ($instrumento=='prepago') ? 'prepago_transacciones' : 'giftcards_transacciones' ;
   $query  = "select * from ".$tpcard." where card='".trim($_GET["card"])."'";
	$query .= " and (status='Lista para usar' or status='Confirmada')";
	$query .= " order by fecha desc, documento desc";
   $result = mysqli_query($link, $query);
	$respuesta = '{"exito":"SI","transacciones":';
	$respuesta .= '[';
   $first = true;
   $cant = 0;
   while ($row = mysqli_fetch_array($result)) {
      if ($cant >= 10) { break; }
      if ($first) {
         $coma = "";
         $first = false;
      } else {
         $coma = ",";
      }
      $respuesta .= $coma.'{'; 
      $respuesta .= '"id":'.$row["id"];
      $respuesta .= ','.'"fecha":"'.trim($row["fecha"]).'"';
      $respuesta .= ','.'"referencia":"'.trim($row["documento"]).'"';
      $monto = $row["montobs"]+$row["montodolares"]+$row["montocripto"];
      if ($row["tipotransaccion"]<'50') {
         $respuesta .= ','.'"recarga":'.$monto;
         $respuesta .= ','.'"consumo": 0.00';
      } else {
         $respuesta .= ','.'"recarga": 0.00';
         $respuesta .= ','.'"consumo":'.$monto;
      }
      $respuesta .= '}';
      $cant++;
	}
	$respuesta .= ']';
	$respuesta .= '}';
} else {
	$respuesta = '{"exito":"NO","proveedor":{},"transaciones":[]}';
}
echo $respuesta;
?>
