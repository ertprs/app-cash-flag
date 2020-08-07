<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$monto = 0.00;
$query = 'SELECT * FROM pdv_transacciones where id='.$_POST["transaccion"];
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
  $monto = $row['monto'];
  $card = $row['id_instrumento'];
  $referencia = $row['documento'];
  $fecha = $row['fecha'];
  $instrumento = $row['instrumento'];

  if ($instrumento=='prepago') {
    $query  = 'SELECT * FROM prepago where card="'.trim($card).'"';
    $quer2  = 'SELECT * FROM prepago_transacciones where card="'.trim($card).'"';
    $quer2 .= ' and documento="'.$referencia.'"';
  } else {
    $query  = 'SELECT * FROM giftcards where card="'.trim($card).'"';
    $quer2  = 'SELECT * FROM giftcards_transacciones where card="'.trim($card).'"';
    $quer2 .= ' and documento="'.$referencia.'"';
  }
  $result = mysqli_query($link, $query);
  if ($row = mysqli_fetch_array($result)) {
    $saldo = $row["saldo"];
    $saldoentransito = $row["saldoentransito"];
  }
  $result = mysqli_query($link, $quer2);
  if ($row = mysqli_fetch_array($result)) {
    $idcardtransaccion = $row["id"];
  }
}

$respuesta = '{';
$respuesta .= '"exito":"NO",';
$respuesta .= '"mensaje":"'.utf8_encode('Ocurrió un error, comuniquese con soporte técnico al +584244071820.').'"';
$respuesta .= '}';

switch ($_POST["accion"]) {
  case 'confirmar':
    $query = 'UPDATE pdv_transacciones SET status="Confirmada" WHERE id='.$_POST["transaccion"];
    $saldo -= $monto;
    $saldoentransito -= $monto;
    break;
  case 'rechazar':
    $query = 'UPDATE pdv_transacciones SET status="Rechazada" WHERE id='.$_POST["transaccion"];
    $saldoentransito -= $monto;
    break;
}
if ($result = mysqli_query($link, $query)) {
  if ($instrumento=='prepago') {
    $query = 'UPDATE prepago SET saldo='.$saldo.', saldoentransito='.$saldoentransito.' WHERE card="'.trim($card).'"';
    $quer2 = 'UPDATE prepago_transacciones SET status="Confirmada" WHERE id='.$idcardtransaccion;
  } else {
    $query = 'UPDATE giftcards SET saldo='.$saldo.', saldoentransito='.$saldoentransito.' WHERE card="'.trim($card).'"';
    $quer2 = 'UPDATE giftcards_transacciones SET status="Rechazada" WHERE id='.$idcardtransaccion;
  }
  $resul2 = mysqli_query($link, $quer2);
  if ($result = mysqli_query($link, $query)) {
    $respuesta = '{';
    $respuesta .= '"exito":"SI",';
    $respuesta .= '"mensaje":"' . utf8_encode('Proceso exitoso.') . '",';
    $respuesta .= '"pdv_id":' . $_POST["transaccion"];
    $respuesta .= '}';
  }
}
echo $respuesta;
?>
