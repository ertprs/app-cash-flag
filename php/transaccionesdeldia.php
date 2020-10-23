<?php 
include_once("../_config/conexion.php");
include_once("funciones.php");

$url           = 'https://openexchangerates.org/api/latest.json?app_id='.$app_id_openexchange;
$monedas       = '&symbols="VEF_BLKMKT"';
$alternativas  = '&show_alternative=true';
$url          .= $monedas.$alternativas;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

$response = curl_exec($ch);
$currencj = json_encode($response);
$currency = get_object_vars(json_decode(json_decode($currencj)));
$cambio = get_object_vars($currency["rates"]);

$fecha = date('Y-m-d');

$query = 'update _parametros set dolar='.$cambio["VEF_BLKMKT"].', fechadolar="'.$fecha.'"';
$result = mysqli_query($link,$query);
?>
