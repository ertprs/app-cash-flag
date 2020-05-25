<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/getauth",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"merchant_identify\":{\"integratorId\":89694855,\"merchantId\":73127147,\"terminalId\":\"7673270170025984\"},\"client_identify\":{\"ipaddress\":\"70.134.207.191\",\"browser_agent\":\"52\",\"mobile\":{\"manufacturer\":\"korafacj\",\"model\":\"higi\",\"os_version\":\"kareh\",\"location\":{\"lat\":0.09263914,\"lng\":88.07317105}}},\"transaction_authInfo\":{\"trx_type\":\"ibos\",\"payment_method\":\"wazamcis\",\"card_number\":\"4103278631583744\",\"customer_id\":\"2445663644155904\"}}",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "content-type: application/json",
    "x-ibm-client-id: REPLACE_THIS_KEY"
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

Respuesta de ejemplo
Definición
POST https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/getauth
Respuesta
{
  "merchant_identify": {
    "integratorId": 76985751,
    "merchantId": 10432865,
    "terminalId": "7643685365743616",
    "masterkey": "ovdara",
    "accountnumber": "30081047382530"
  },
  "authentication_info": {
    "procesing_date": "11/13/2085",
    "trx_status": "baawakoh",
    "trx_type": "hufetd",
    "payment_method": "mezo",
    "twofactor_type": "imrekla"
  }
}
Intentar esta operación
https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/getauth
Identificación
ID de cliente

Cuerpo
{
  "merchant_identify": {
    "integratorId": 51311798,
    "merchantId": 51415981,
    "terminalId": "1241752379850752"
  },
  "client_identify": {
    "ipaddress": "106.165.92.33",
    "browser_agent": "30",
    "mobile": {
      "manufacturer": "nutew",
      "model": "acmotez",
      "os_version": "reijio",
      "location": {
        "lat": 68.39834838,
        "lng": 37.84775198
      }
    }
  },
  "transaction_authInfo": {
    "trx_type": "pucfanid",
    "payment_method": "ewgipdah",
    "card_number": "6630587100037120",
    "customer_id": "4349353332310016"
  }
}
Cabeceras
content-type application/json
accept       application/json

Solicitud
POST https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/getauth
X-IBM-Client-Id: b87bdb04-8564-49a1-888f-127a28f16c80
content-type: application/json
accept: application/json

                        
Respuesta
401 Unauthorized
content-type: application/json
{
    "httpCode": "401",
    "httpMessage": "Unauthorized",
    "moreInformation": "Unauthorized request"
}
