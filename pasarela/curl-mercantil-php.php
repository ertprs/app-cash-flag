<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "9443",
  CURLOPT_URL => "https://apimbu.mercantilbanco.com:9443/mercantil-banco/desarrollo/v1/payment/getauth",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n\t\"merchant_identify\": {\n\t\t\"integratorId\": 31,\n\t\t\"merchantId\": 150332,\n\t\t\"terminalId\": \"abcde\"\n\t},\n\t\"client_identify\": {\n\t\t\"ipaddress\": \"10.0.0.1\",\n\t\t\"browser_agent\": \"Chrome 18.1.3\",\n\t\t\"mobile\": {\n\t\t\t\"manufacturer\": \"Samsung\",\n\t\t\t\"model\": \"S9\",\n\t\t\t\"os_version\": \"Oreo 9.1\",\n\t\t\t\"location\": {\n\t\t\t\t\"lat\": 37.4224764,\n\t\t\t\t\"lng\": -122.0842499\n\t\t\t}\n\t\t}\n\t},\n\t\"transaction_authInfo\" : {\n\t\t\"trx_type\": \"solaut\",\n\t\t\"payment_method\": \"tdd\",\n\t\t\"card_number\": \"501878200066287386\",\n\t\t\"customer_id\": \"V18366876\"\n\t}\n}",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "apikey: mbu1",
    "cache-control: no-cache",
    "content-type: application/json",
    "environment: dev",
    "postman-token: 9cfc95b1-4461-b877-a3cc-e7f1fbfec8de",
    "x-ibm-client-id: 9860e0f2-ed46-495e-a25f-ef377ea645f6"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}