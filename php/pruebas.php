<?php
include_once("../_config/configShopify.php");

$url = $urlUnCustomer.'3053995622474'.'.json';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

$registros=json_decode($result,true);
$cliente = $registros["customer"];

$phone = $cliente["phone"];

$prefijos = array('414','424','416','426','412');

$prefijoValido = 0;
foreach ($prefijos as $key) {
	$prefijoValido += substr_count($phone, $key, 0, 7);
}
echo $prefijoValido;
?>
