<?php
$coinmarketcapkey = 'f82b18ca-28cd-4fbd-8197-a2c5c1d232b4';
$symbol = 'AE';

$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol='.$symbol.'&CMC_PRO_API_KEY='.$coinmarketcapkey;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);
$result = curl_exec($ch);
$info = json_decode($result,true);

curl_close($ch);

echo $info["data"]["AE"]["quote"]["USD"]["price"];
// echo '<pre>';
// var_dump($info["data"]["AE"]);
// echo '</pre>';

// echo $result;
?>