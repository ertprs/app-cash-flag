<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/search",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"merchant_identify\":{\"integratorId\":\"31\",\"merchantId\":\"150332\",\"terminalId\":\"abcde\"},\"client_identify\":{\"ipaddress\":\"10.0.0.1\",\"browser_agent\":\"Chrome 18.1.3\",\"mobile\":{\"manufacturer\":\"Samsung\",\"model\":\"S9\",\"os_version\":\"Oreo 9.1\",\"location\":{\"lat\":37.4224764,\"lng\":56.39339688}}},\"search_by\":{\"search_criteria\":\"all\",\"procesing_date\":\"2019/01/23\"}}",
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


Example Response
Definition
POST https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/search
Response
{
  "merchant_identify": {
    "integratorId": "31",
    "merchantId": "150332",
    "terminalId": "abcde"
  },
  "transaction_list": [
    {
      "processing_date": "2019-09-12 03:07:53 VET",
      "trx_status": "approved",
      "trx_internal_status": "null",
      "trx_type": "compra",
      "payment_method": "tdd",
      "payment_reference": "0057718281656",
      "invoice_number": "113466",
      "amount": 30.11,
      "currency": "ves"
    }
  ]
}

Intentar esta operación
https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/search
Identificación
ID de cliente

Cuerpo
{
  "merchant_identify": {
    "integratorId": "31",
    "merchantId": "150332",
    "terminalId": "abcde"
  },
  "client_identify": {
    "ipaddress": "10.0.0.1",
    "browser_agent": "Chrome 18.1.3",
    "mobile": {
      "manufacturer": "Samsung",
      "model": "S9",
      "os_version": "Oreo 9.1",
      "location": {
        "lat": 37.4224764,
        "lng": 40.9129122
      }
    }
  },
  "search_by": {
    "search_criteria": "all",
    "procesing_date": "2019/01/23"
  }
}
Cabeceras
content-type application/json
accept       application/json

Solicitud
POST https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/search
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
