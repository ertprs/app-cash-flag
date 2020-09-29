<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("./funciones.php");

// Asignación de variables
$nombres   = $_GET['n'];
$apellidos = $_GET['a'];
$telefono  = $_GET['t'];
$email     = $_GET['e'];

// $nombres   = 'Luis';
// $apellidos = 'RodrÃ­guez';
// $telefono  = '+584244178584';
// $email     = 'soluciones2000@gmail.com';

$moneda = "ae" ;
$monto = 0.00 ;

$montobs = 0.00;
$montodolares = 0.00;
$montocripto = $monto; 

$tipotransaccion = '01';
$tasadolarbs = 1.00;
$tasadolarcripto = 1.00;
$idproveedor = 3;  // Cash-Flag
$tipopago = 'efectivo' ;
$origen = 'afiliacion' ;
$referencia = 'nuevosocio' ;

$nombreproveedor = "Cash-Flag";

// Buscar el id del socio (si existe)
$query = "select * from socios where email='".$email."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
   $idsocio = $row["id"];
} else {
   $idsocio = 0;
}

// Generar numero de tarjeta partiendo de los datos enviados
$cardnew = generaprepago($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);
/*
El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:
AAGBBGCCDDGEEGFF -> AAGB BGCC DDGE EGFF
0123456789012345
    x  x    x  x
*/
$dcp = intval(substr($cardnew,2,1).substr($cardnew,5,1).substr($cardnew,10,1).substr($cardnew,13,1));

$card = $cardnew;
$saldoant = 0.00;
$saldo = ($tipopago == 'efectivo') ? $monto : 0.00 ;

// Fecha de compra
$fecha = date('Y-m-d');

// Fecha de vecnimiento (1 año)
$fechavencimiento = strtotime('+1 year', strtotime ($fecha));
$fechavencimiento = date('Y-m-d', $fechavencimiento);

$datetime1 = date_create($fecha);
$datetime2 = date_create($fechavencimiento);
$diferencia = date_diff($datetime1, $datetime2);

$validez = substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4);

$status = 'Lista para usar';
$fechaconfirmacion = $fecha;
    
// Encripta la card
$hash = hash("sha256",$card.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

$query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."')";
if ($result = mysqli_query($link,$query)) {
   $quer0 = "INSERT INTO cards (card, tipo) VALUES ('".$card."','prepago')";
	if ($resul0 = mysqli_query($link,$quer0)) {
		$query = "INSERT INTO prepago (card, nombres, apellidos, telefono, email, saldo, saldoentransito, moneda, fechacompra, fechavencimiento, validez, status, id_socio, id_proveedor, hash, premium) VALUES ('".$card."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",0.00,'".$moneda."','".$fecha."','".$fechavencimiento."','".$validez."','".$status."',".$idsocio.",".$idproveedor.",'".$hash."',1)";
		if ($result = mysqli_query($link,$query)) {
			// Punto de venta
			$tipo2 = '51'; 
			// Insertar transacción confirmada
			$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, ';
			$quer2 .= 'moneda, monto, instrumento, id_instrumento, documento, status, origen, token) ';
         $quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'", ';
         $quer2 .= '"'.$moneda.'",'.$monto.',"prepago","'.$card.'","'.$referencia.'","'.$status.'", ';
			$quer2 .= '"'.$origen.'","")';
			$resul2 = mysqli_query($link,$quer2);
			$querx = 'UPDATE _parametros SET dcp='.$dcp;
			$resulx = mysqli_query($link,$querx);
      }
   }
}

?>