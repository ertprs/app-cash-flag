<?php
include_once("../_config/conexion.php");
include_once("funciones.php");
include_once("../lib/phpqrcode/qrlib.php");

$proveedor = $_GET["p"];

$query = "select * from proveedores where id=".$proveedor;
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);

$nombreproveedor=$row["nombre"];
$logo=$row["logo"];

// código qr
// $dir = 'https://www.cash-flag.com/php/temp/';
$dir = 'temp/';
	
// $filename = $dir.'test.png';
$filename = $dir.'pdv-'.$proveedor.'.png';
$tamanio = 5;
$level = 'H';
$frameSize = 1;
$contenido = '{"idp":'.$proveedor.'}';

QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);

echo $filename;
/*
if ($logo!="") {
	$filename = $dir.'pdv-'.$proveedor.'.png';

	$original = imagecreatefrompng($filename);
	$logotipo = imagecreatefromjpeg('../img/'.$logo);

	$dataorig = getimagesize($filename);
	$datalogo = getimagesize('../img/'.$logo);

	list($wsour, $hsour) = getimagesize($filename);
	list($wtarg, $htarg) = getimagesize('../img/'.$logo);

	$newqr = imagecreatetruecolor($wsour, $hsour);

	imagecopy($newqr, $original, 0, 0, 0, 0, $wsour, $hsour);

	$porcentaje = 50;

	$a = ($wsour/$wtarg);
	$b = $a*100/$wsour;
	$ancho = $a/$b * $porcentaje;

	$a = ($htarg/$wtarg);
	$alto = $ancho * $a;

	$x = ($wsour - $ancho) / 2;
	$y = ($hsour - $alto) / 2;

	imagecopyresized($newqr, $logotipo, $x, $y, 0, 0, $ancho, $alto, $wtarg, $htarg);

	imagepng($newqr, $dir.'pdv-'.$proveedor."_logo.png", 0);
	$codigo = $dir.'pdv-'.$proveedor."_logo.png";
} else {
	$codigo = $dir.'pdv-'.$proveedor.".png";
}
echo $codigo;
// Hasta aqui
*/
?>
