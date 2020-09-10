<?php
include_once("./_config/configShopify.php");

$url = $urlUnaOrden.'2403557441610.json';

$ch = curl_init();

$variant = '{"order":{"id":'.'2403557441610'.',"shipping_address":{"address1":"'.'Valencia2'.'"}}}';

curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
// curl_setopt($ch, CURLOPT_PUT, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $variant);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch,CURLOPT_HEADER, false); 
$response = curl_exec($ch);
echo curl_error($ch);
echo '<pre>';
var_dump(curl_getinfo($ch));
echo '</pre>';
curl_close($ch);
print_r($response);
?>
