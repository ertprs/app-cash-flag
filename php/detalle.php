<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

// 
// Cupones
// 
$query  = 'SELECT * FROM cupones ';
$query .= 'WHERE id_proveedor='.$_GET["idproveedor"].' AND (';
$query .= '(fechacupon>="'.$_GET["fechadesde"].'" and fechacupon<="'.$_GET["fechahasta"].'") or ';
$query .= '(fechavencimiento>="'.$_GET["fechadesde"].'" and fechavencimiento<="'.$_GET["fechahasta"].'") or ';
$query .= '(fechacanje>="'.$_GET["fechadesde"].'" and fechacanje<="'.$_GET["fechahasta"].'")';
$query .= ') order by status, cuponlargo';

$respuesta = '{';
$respuesta .= '"exito":"SI",';
$respuesta .= '"cupones":[';
if ($result = mysqli_query($link, $query)) {
   $first = true;
   while($row = mysqli_fetch_array($result)) {
      if ($first) {
         $first = false;
         $coma = "";
      } else {
         $coma = ",";
      }
      $respuesta .= $coma.'{';
      $respuesta .= '"status":"'.$row["status"].'",';
      $respuesta .= '"cuponlargo":"'.$row["cuponlargo"].'",';
      $premio = "";
      switch ($row["tipopremio"]) {
         case 'monto': 
            $premio = 'Bs. '.number_format($row["montopremio"],2,',','.').' de descuento';
            break;
         case 'porcentaje':
            $premio = number_format($row["montopremio"],0,',','.').'% de descuento';
            break;
         case 'producto':
            $premio = $row["descpremio"];
            break;
   
      }
      $respuesta .= '"premio":"'.$premio.'",';
      $respuesta .= '"fechacupon":"'.$row["fechacupon"].'",';
      $respuesta .= '"facturacupon":"'.$row["factura"].'",';
      $respuesta .= '"montocupon":'.$row["monto"].',';
      $respuesta .= '"fechacanje":"'.$row["fechacanje"].'",';
      $respuesta .= '"fechavencimiento":"'.$row["fechavencimiento"].'",';
      $respuesta .= '"facturacanje":"'.$row["facturacanje"].'",';
      $respuesta .= '"montocanje":'.$row["montocanje"];
      $respuesta .= '}';
   }
}
$respuesta .= ']';

// 
// Tarjetas
// 
$query  = 'SELECT * FROM pdv_transacciones ';
$query .= 'WHERE id_proveedor='.$_GET["idproveedor"].' AND ';
$query .= '(fecha>="'.$_GET["fechadesde"].'" AND fecha<="'.$_GET["fechahasta"].'") AND moneda="'.$_GET["moneda"].'" AND ';
$query .= '(status="Confirmada" OR status="Lista para usar") AND (tipo="01" OR tipo="51") order by tipo desc, instrumento desc, fecha, id_instrumento';
// echo $query;
$respuesta .= ',"tarjetas":[';
if ($result = mysqli_query($link, $query)) {
   $first = true;
   while($row = mysqli_fetch_array($result)) {
      if ($first) {
         $first = false;
         $coma = "";
      } else {
         $coma = ",";
      }
      $respuesta .= $coma.'{';
      if ($row["tipo"]=="51") {
         $respuesta .= '"tipo":"Recargas y venta de tarjetas",';
      } else {
         $respuesta .= '"tipo":"Consumos",';
      }
      // $respuesta .= '"tipo2":"'.$row["tipo"].'",';
      $respuesta .= '"instrumento":"'.$row["instrumento"].'",';
      $respuesta .= '"fecha":"'.$row["fecha"].'",';
      $respuesta .= '"fechaconfirmacion":"'.$row["fechaconfirmacion"].'",';
      $respuesta .= '"monto":'.$row["monto"].',';
      $respuesta .= '"id_instrumento":"'.$row["id_instrumento"].'",';
      $respuesta .= '"documento":"'.$row["documento"].'",';
      $respuesta .= '"status":"'.$row["status"].'"';
      $respuesta .= '}';
   }
}
$respuesta .= ']';

$respuesta .= '}';
echo $respuesta;
?>