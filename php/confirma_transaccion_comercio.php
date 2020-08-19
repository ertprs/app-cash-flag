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
  $documento = $row['documento'];
  $fecha = $row['fecha'];
} else {
  $monto = 0;
  $card  = '';
  $documento = '';
  $fecha = '';
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

    $fechaconfirmacion = date("Y-m-d");
    $query = 'UPDATE pdv_transacciones SET status="Lista para usar", fechaconfirmacion="'.$fechaconfirmacion.'" WHERE id_instrumento="'.trim($card).'" and documento="'.$documento.'"';
    $result = mysqli_query($link, $query);

    break;
  case 'rechazar':
    $query = 'UPDATE '.$tipocard.'_transacciones SET status="Rechazada" WHERE id='.$_POST["transaccion"];
    $result = mysqli_query($link, $query);

    $fechaconfirmacion = date("Y-m-d");
    $query = 'UPDATE pdv_transacciones SET status="Rechazada", fechaconfirmacion="'.$fechaconfirmacion.'" WHERE id_instrumento="'.trim($card).'" and documento="'.$documento.'"';
    $result = mysqli_query($link, $query);
    break;
}

$respuesta = '{';
$respuesta .= '"exito":"SI",';
$respuesta .= '"mensaje":"' . utf8_encode('Proceso exitoso.') . '"';
$respuesta .= '}';
echo $respuesta;
?>
