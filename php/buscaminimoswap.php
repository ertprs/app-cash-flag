<?php
set_time_limit(3600);
$simpleswapkey = '4a000952-c549-496f-a9e0-0ca0cb3760cb';
$fixed = 'false';
$mycurrency = 'ae';

$ch2 = curl_init();
$url = 'https://api.simpleswap.io/v1/get_ranges?api_key='.$simpleswapkey.'&fixed='.$fixed.'&currency_from='.$_GET["symbol"].'&currency_to='.$mycurrency;
// echo $url."<br/>";
curl_setopt($ch2, CURLOPT_URL,$url );
curl_setopt($ch2,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch2,CURLOPT_HEADER, false);
$result = curl_exec($ch2);
$inf2 = json_decode($result,true);
// echo curl_error($ch);
$respuesta = '{"exito":"SI","min":'.$inf2["min"].'}';
curl_close($ch2);
echo $respuesta;
?>