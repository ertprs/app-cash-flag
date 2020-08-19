<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

// 
// Cupones
// 
$query  = 'SELECT * FROM cupones ';
$query .= 'WHERE id_proveedor='.$_GET["idproveedor"].' AND (';
$query .= '(fechacupon>="'.$_GET["fechadesde"].'" AND fechacupon<="'.$_GET["fechahasta"].'") or ';
$query .= '(fechavencimiento>="'.$_GET["fechadesde"].'" AND fechavencimiento<="'.$_GET["fechahasta"].'") or ';
$query .= '(fechacanje>="'.$_GET["fechadesde"].'" AND fechacanje<="'.$_GET["fechahasta"].'"))';

$generadosdia = 0;
$canjeadosdia = 0;
$vencidosdia = 0;
if ($result = mysqli_query($link, $query)) {
   while($row = mysqli_fetch_array($result)) {
      switch ($row["status"]) {
         case 'Generado':
            $generadosdia++;
            break;
         case 'Usado':
            $canjeadosdia++;
            break;
         case 'Vencido':
            $vencidosdia++;
            break;
      }
   }
}

$respuesta = '{';
$respuesta .= '"exito":"SI",';
$respuesta .= '"cupones":{';
$respuesta .= '"generadosdia":'.$generadosdia.',';
$respuesta .= '"canjeadosdia":'.$canjeadosdia.',';
$respuesta .= '"vencidosdia":'.$vencidosdia;
$respuesta .= '}';

// 
// Tarjetas
// 
$query  = 'SELECT * FROM pdv_transacciones ';
$query .= 'WHERE id_proveedor='.$_GET["idproveedor"].' AND ';
$query .= '(fecha>="'.$_GET["fechadesde"].'" AND fecha<="'.$_GET["fechahasta"].'") AND moneda="'.$_GET["moneda"].'" AND ';
$query .= '(status="Confirmada" OR status="Lista para usar") AND (tipo="01" OR tipo="51")';

$cantrecaprep = 0;
$cantrecagift = 0;
$montrecaprep = 0.00;
$montrecagift = 0.00;

$cantconsprep = 0;
$cantconsgift = 0;
$montconsprep = 0.00;
$montconsgift = 0.00;
if ($result = mysqli_query($link, $query)) {
   while($row = mysqli_fetch_array($result)) {
      if ($row["tipo"]=="01") {
         switch ($row["instrumento"]) {
            case 'prepago':
               $cantconsprep++;
               $montconsprep += $row["monto"];
               break;
            case 'giftcard':
               $cantconsgift++;
               $montconsgift += $row["monto"];
               break;
         }
      } else {
         switch ($row["instrumento"]) {
            case 'prepago':
               $cantrecaprep++;
               $montrecaprep += $row["monto"];
               break;
            case 'giftcard':
               $cantrecagift++;
               $montrecagift += $row["monto"];
               break;
         }
      }      
   }
}
$respuesta .= ',"recargas":{';
$respuesta .= '"cantrecaprep":'.$cantrecaprep.',';
$respuesta .= '"montrecaprep":'.$montrecaprep.',';
$respuesta .= '"cantrecagift":'.$cantrecagift.',';
$respuesta .= '"montrecagift":'.$montrecagift;
$respuesta .= '}';
   
$respuesta .= ',"consumos":{';
$respuesta .= '"cantconsprep":'.$cantconsprep.',';
$respuesta .= '"montconsprep":'.$montconsprep.',';
$respuesta .= '"cantconsgift":'.$cantconsgift.',';
$respuesta .= '"montconsgift":'.$montconsgift;
$respuesta .= '}';

$respuesta .= '}';
echo $respuesta;
?>