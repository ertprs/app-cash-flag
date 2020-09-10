<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("../lib/phpqrcode/qrlib.php");

$email = $_GET["email"];
$premium = true;
$moneda = "ae";

$query = "SELECT * from prepago where email='".$email."' and premium=".$premium." and moneda='".$moneda."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
   $quer2 = "SELECT * from socios where email='".$email."'";
   $resul2 = mysqli_query($link, $quer2);
   if ($ro2 = mysqli_fetch_array($resul2)) {
		$ruta = '../php/';
		// $ruta = 'https://app.cash-flag.com/php/';
		$dir = 'qr/';
		if(!file_exists($dir)) mkdir($dir);

		$tamanio = 5;
		$level = 'H';
		$frameSize = 1;
		$contenido = $ro2["account"];

		QRcode::png($contenido,$dir.$ro2["account"].'.png', $level, $tamanio, $frameSize);
		$rutaqr = $ruta.$dir.$ro2["account"].'.png';

      $respuesta = '{';
      $respuesta .= '"nombre":"'.trim($row["nombres"])." ".trim($row["apellidos"]).'",';
      $respuesta .= '"tarjeta":"'.$row["card"].'",';
      $respuesta .= '"saldo":'.$row["saldo"].',';
      $respuesta .= '"validez":"'.$row["validez"].'",';
      $respuesta .= '"status":"'.$row["status"].'",';
      $respuesta .= '"rutaqr":"'.$rutaqr.'",';
      $respuesta .= '"account":"'.$ro2["account"].'"';
      $respuesta .= '}';
   } else {
      $respuesta = '{"exito": "NO"}';
   }
} else {
	$respuesta = '{"exito": "NO"}';
}
echo $respuesta;
?>
