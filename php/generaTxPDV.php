<?php
include_once("../_config/conexion.php");
include_once("funciones.php");
include_once("../lib/phpqrcode/qrlib.php");

$proveedor = $_GET["p"];
$divisa = $_GET["d"];
$monto = $_GET["m"];
// $transaccion = random_int(0, $monto);
$transaccion = '11223344';


// código qr
// $dir = 'https://www.cash-flag.com/php/temp/';
$dir = 'temp/';
	
// $filename = $dir.'test.png';
$filename = $dir.'trx-'.$transaccion.'.png';
$tamanio = 5;
$level = 'H';
$frameSize = 1;
$contenido = 'https://www.cash-flag.com/php/preparaTxPDV.php?j={"p":'.$proveedor.',"d":"'.$divisa.'","m":'.$monto.'}';

QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);

echo $filename;
// Hasta aqui
?>
