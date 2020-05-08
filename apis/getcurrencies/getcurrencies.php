<?php
include_once("../../_config/conexion.php");

$url           = 'https://openexchangerates.org/api/latest.json?app_id='.$app_id_openexchange;
$monedas       = '&symbols="BTC","COP","ETH","EUR","VEF_BLKMKT","VES"';
$alternativas  = '&show_alternative=true';
$url          .= $monedas.$alternativas;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

$response = curl_exec($ch);

$currencies = json_decode($response);

echo $response;
?>
