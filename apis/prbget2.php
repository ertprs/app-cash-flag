<?php
$simpleswapkey = '4a000952-c549-496f-a9e0-0ca0cb3760cb';
$fixed = 'false';
$currencyfrom = 'ae';
$currencydest = 'btc';
$mycurrency = 'ae';
$exchangeid = 'mwkwGFXmnJC';

// Montos mínimos y máximos de exchanges entre dos monedas
// $url = 'https://api.simpleswap.io/v1/get_ranges?api_key='.$simpleswapkey.'&fixed='.$fixed.'&currency_from='.$currencyfrom.'&currency_to='.$currencydest;

// Todos los exchanges
// $url = 'https://api.simpleswap.io/v1/get_exchanges?api_key='.$simpleswapkey;

// Información de un exchange en particular
$url = 'https://api.simpleswap.io/v1/get_exchange?api_key='.$simpleswapkey.'&id='.$exchangeid;

// Pares compatibles
// $url = 'https://api.simpleswap.io/v1/get_pairs?api_key='.$simpleswapkey.'&fixed='.$fixed.'&symbol='.$currencyfrom;

// información de una moneda
// $url = 'https://api.simpleswap.io/v1/get_currency?api_key='.$simpleswapkey.'&symbol='.$mycurrency;


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);
$result = curl_exec($ch);
$info = json_decode($result,true);
// echo curl_error($ch);

curl_close($ch);

echo $result;
?>