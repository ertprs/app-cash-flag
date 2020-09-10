<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("../lib/phpqrcode/qrlib.php");

$email = $_GET["email"];

$query = "SELECT * FROM proveedores WHERE email='".$email."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
   $contenido = $row["account"];

	$ruta = '../php/';
	// $ruta = 'https://app.cash-flag.com/php/';
	$dir = 'qr/';
	if(!file_exists($dir)) mkdir($dir);

	$tamanio = 5;
	$level = 'H';
	$frameSize = 1;

	QRcode::png($contenido,$dir.$row["account"].'.png', $level, $tamanio, $frameSize);
	$rutaqr = $ruta.$dir.$row["account"].'.png';

   $respuesta = '{';
   $respuesta .= '"rutaqr":"'.$rutaqr.'",';
   $respuesta .= '"account":"'.$row["account"].'"';
   $respuesta .= '}';
} else {
	$respuesta = '{"exito": "NO"}';
}
echo $respuesta;
?>
