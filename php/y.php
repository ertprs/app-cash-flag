<?php
function calculaedad($fechanacimiento){
      list($ano,$mes,$dia) = explode("-",$fechanacimiento);
   $ano_diferencia  = date("Y") - $ano;
   $mes_diferencia = date("m") - $mes;
   $dia_diferencia   = date("d") - $dia;
   if ($dia_diferencia < 0 || $mes_diferencia < 0)
      $ano_diferencia--;
   return $ano_diferencia;
}

echo calculaedad ('1961-01-07'); 

$fecha = date('Y-m-d');

$x0120 = strtotime('-20 year', strtotime($fecha));
$x0120 = date('Y-m-d', $x0120);

$x2130 = strtotime('-30 year', strtotime($fecha));
$x2130 = date('Y-m-d', $x2130);

$x3140 = strtotime('-40 year', strtotime($fecha));
$x3140 = date('Y-m-d', $x3140);

$x4150 = strtotime('-50 year', strtotime($fecha));
$x4150 = date('Y-m-d', $x4150);

$x5160 = strtotime('-60 year', strtotime($fecha));
$x5160 = date('Y-m-d', $x5160);


echo " <=> ";
echo $fecha;
echo " <=> ";
echo $x0120;
echo " <=> ";
echo $x2130;
echo " <=> ";
echo $x3140;
echo " <=> ";
echo $x4150;
echo " <=> ";
echo $x5160;
echo " <=> ";
echo '2001-01-07' >= $x0120;
echo " <=> ";
echo '2001-01-06' >= $x0120;
echo " <=> ";
echo '2001-01-08' >= $x0120;
echo " <=> ";
echo '1961-01-06' < $x5160;
echo " <=> ";

01-20 // fechanacimiento >= $x0120
21-30 
?>