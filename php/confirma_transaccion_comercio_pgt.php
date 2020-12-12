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
  $idproveedor = $row['idproveedor'];
  $idsocio = $row['idsocio'];
  $tipomoneda = $row['tipomoneda'];
  if ($idproveedor==3) {
    $montobruto = $monto;
    $montoneto = $monto * 100 / (100-(100*3/100));
    $comis = $montoneto - $monto; 
  } else {
    $comis = $monto * 3 / 100; 
    $montobruto = $monto - $comis;
  }
} else {
  $monto = 0;
  $card  = '';
  $documento = '';
  $fecha = '';
  $idproveedor = 0;
  $idsocio = 0;
  $tipomoneda = '';
  $comis = 0;
  $montoneto = 0;  
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
	  // Punto de venta
	  $tipo2 = '51'; 
		// Insertar transacción para confirmar
		$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision, ';
		$quer2 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
		$quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'","'.$tipomoneda.'",';
		$quer2 .= $montobruto.','.$comis.',"'.$tipocard.'","'.$card.'","'.$documento.'","Lista para usar","","",0,"")';
		$resul2 = mysqli_query($link,$quer2);

		// Comision Punto de venta
		$tipo3 = '03'; 
		// Insertar transacción para confirmar
		$quer3  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision,';
		$quer3 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
		$quer3 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo3.'","'.$tipomoneda.'",';
		$quer3 .= $comis.', 0,"'.$tipocard.'","'.$card.'","'.$documento.'","Confirmada","","",0,"")';
		$resul3 = mysqli_query($link,$quer3);

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
