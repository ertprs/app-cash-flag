<?php
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxNTk3NzU0MS1lZTRkLTRkOTEtYTc2NC0xZmIyMzg5ZWQ2ZTgiLCJpYXQiOjE2MDYxODU5NzAsImV4cCI6MTYwNzM5NTU3MH0.0ZyLSd9GHYZkJODh_q5oIVGhDlaJKtrO_lmnDpTTMQY";

$url = "https://ap2.cash-flag.com/api/user/profile?Authorization=".$token;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);


$result=curl_exec($ch);

$x = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);

echo curl_error($ch);

curl_close($ch);

$info = json_decode($result,true);

echo $result;

?>