<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"merchant_identify\":{\"integratorId\":36030273,\"merchantId\":32977325,\"terminalId\":\"4525646636122112\"},\"client_identify\":{\"ipaddress\":\"199.8.180.240\",\"browser_agen\":\"40\",\"mobile\":{\"manufacturer\":\"fecj\",\"model\":\"jujcav\",\"os_version\":\"gekuz\",\"location\":{\"lat\":37.4224764,\"lng\":64.07951191}}},\"transaction\":{\"trx_type\":\"lovhi\",\"payment_method\":\"epap\",\"card_number\":\"4810498082078720\",\"customer_id\":\"4892515673047040\",\"invoice_number\":\"2152468595081216\",\"account_type\":\"5107051936990716\",\"twofactor_auth\":\"kazpifoo\",\"expiration_date\":\"2/15/2084\",\"cvv\":\"lutpuim\",\"currency\":\"CNY\",\"amount\":29.61155844}}",
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
POST https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/pay
Respuesta
{
  "merchant_identify": {
    "integratorId": 82298863,
    "merchantId": 99474299,
    "terminalId": "394883942580224"
  },
  "transaction_response": {
    "processing_date": "1/23/2060",
    "trx_status": "liwiz",
    "trx_type": "jogomawi",
    "payment_method": "gogem",
    "payment_reference": "fazorm",
    "invoice_number": "6005363788742656",
    "amount": 90.4894576,
    "currency": "AZN"
  }
}
Intentar esta operación
https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/pay
Identificación
ID de cliente

Cuerpo
{
  "merchant_identify": {
    "integratorId": 26108660,
    "merchantId": 55775570,
    "terminalId": "2776876030361600"
  },
  "client_identify": {
    "ipaddress": "65.45.139.121",
    "browser_agen": "20",
    "mobile": {
      "manufacturer": "nezd",
      "model": "motamod",
      "os_version": "zuzku",
      "location": {
        "lat": 37.4224764,
        "lng": 57.10159673
      }
    }
  },
  "transaction": {
    "trx_type": "raskeu",
    "payment_method": "roicoa",
    "card_number": "1383296931987456",
    "customer_id": "5215017332899840",
    "invoice_number": "5156553730555904",
    "account_type": "4454966692974715",
    "twofactor_auth": "urecizp",
    "expiration_date": "11/16/2086",
    "cvv": "ajav",
    "currency": "BBD",
    "amount": 27.45438674
  }
}
Cabeceras
content-type application/json
accept       application/json

Solicitud
POST https://apimbu.mercantilbanco.com/mercantil-banco/prod/v1/payment/pay
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

