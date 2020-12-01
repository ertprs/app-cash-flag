<?php

$host = "https://ap2.cash-flag.com/api";

// Inicio de sesion
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => sprintf("%s/login",$host),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\n    \"username\": \"customer@gmail.com\",\n    \"password\": \"abc.12345\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$decodeResponse = json_decode($response,true);

// Consulta de perfil de usuario
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => sprintf("%s/user/profile",$host),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    sprintf("Authorization: Bearer %s",$decodeResponse["token"])
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
