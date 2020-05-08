<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

if ($_POST["tipocard"]=="prepago") {
  $tipocard = 'prepago';
} else {
  $tipocard = 'giftcards';
}

$query = 'SELECT * FROM '.$tipocard.'_transacciones where id='.$_POST["transaccion"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
  $monto = $row['montobs']+$row['montodolares'];
  $card  = $row['card'];
} else {
  $monto = 0;
  $card  = '';
}

switch ($_POST["accion"]) {
  case 'confirmar':
    $query = 'UPDATE '.$tipocard.'_transacciones SET status="Lista para usar" WHERE id='.$_POST["transaccion"];
    $result = mysqli_query($link, $query);

    $query = 'SELECT * FROM '.$tipocard.' where card="'.trim($card).'"';
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
      $saldo = $monto+$row['saldo'];
    } else {
      $saldo = $monto;
    }

    $query = 'UPDATE '.$tipocard.' SET saldo='.$saldo.', status="Lista para usar" WHERE card="'.trim($card).'"';
    $result = mysqli_query($link, $query);

    break;
  case 'rechazar':
    $query = 'UPDATE '.$tipocard.'_transacciones SET status="Rechazada" WHERE id='.$_POST["transaccion"];
    $result = mysqli_query($link, $query);
    break;
}

$respuesta = '{';
$respuesta .= '"exito":"SI",';
$respuesta .= '"mensaje":"' . utf8_encode('Proceso exitoso.') . '"';
$respuesta .= '}';
echo $respuesta;
?>
