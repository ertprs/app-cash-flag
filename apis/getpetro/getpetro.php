<?php

$url = 'https://petroapp-price.petro.gob.ve/price/';

$coins = array(
	'coins' => array("BTC","DASH","ETH","PTR"),
	'fiats' => array("USD","EUR","COP")
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($coins)); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

$response = curl_exec($ch);

echo $response;
?>
