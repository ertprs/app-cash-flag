<?php
$simpleswapkey = '4a000952-c549-496f-a9e0-0ca0cb3760cb';
$fixed = 'false';
$currencyfrom = 'ae';
$currencydest = 'btc';
$exchangeid = 'mwkwGFXmnJC';

// $url = 'https://api.simpleswap.io/v1/get_ranges?api_key='.$simpleswapkey.'&fixed='.$fixed.'&currency_from='.$currencyfrom.'&currency_to='.$currencydest;
// $url = 'https://api.simpleswap.io/v1/get_exchanges?api_key='.$simpleswapkey;
$url = 'https://api.simpleswap.io/v1/get_exchange?api_key='.$simpleswapkey.'&id='.$exchangeid;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);
curl_close($ch);

echo $result;
?>