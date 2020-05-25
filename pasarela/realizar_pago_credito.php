<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://apimbu.mercantilbanco.com/mercantil-banco/prod/api-pagos-b2c/REALIZAR_PAGO_CREDITO",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"HEADER_PAGO_REQUEST\":{\"IDENTIFICADOR_UNICO_GLOBAL\":\"6445093659607040\",\"IDENTIFICACION_CANAL\":\"1141212343959552\",\"SIGLA_APLICACION\":\"asbiz\",\"IDENTIFICACION_USUARIO\":\"6257432055513088\",\"DIRECCION_IP_CONSUMIDOR\":\"345315403490127\",\"DIRECCION_IP_CLIENTE\":\"3528999920934148\",\"FECHA_ENVIO_MENSAJE\":\"hidwejw\",\"HORA_ENVIO_MENSAJE\":\"omukesv\",\"ATRIBUTO_PAGINEO\":\"sohpupiz\",\"CLAVE_BUSQUEDA\":\"kikonutk\",\"CANTIDAD_REGISTROS\":60845610},\"BODY_PAGO_REQUEST\":{\"IDENTIFICADOR_COMERCIO\":3.50777674,\"TIPO_TRANSACCION\":\"3528134474753625\",\"MONTO_TRANSACCION\":57.34025994,\"NUMERO_FACTURA\":67.05593779,\"IDENTIFICACION_TARJETAHABIENTE\":\"5325278169530368\",\"NOMBRE_TARJETAHABIENTE\":\"eduah\",\"NUMERO_TARJETA\":\"dufdi\",\"FECHA_VENCIMIENTO_TARJETA\":70.76228126,\"CODIGO_SEGURIDAD_TARJETA\":75.92841795,\"NUMERO_LOTE\":\"bonda\"}}",
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
POST https://apimbu.mercantilbanco.com/mercantil-banco/prod/api-pagos-b2c/REALIZAR_PAGO_CREDITO
Respuesta
{
  "HEADER_PAGO_RESPONSE": {
    "TIPO_MENSAJE": "187.139.167.245",
    "MENSAJE_PROGRAMADOR_SISTEMA": "mapw",
    "CODIGO_MENSAJE_PROGRAMADOR": "wukgoro",
    "MENSAJE_USUARIO": "weju",
    "CODIGO_MENSAJE_USUARIO": "detwek",
    "FECHA_SALIDA_MENSAJE": "6653875278839808",
    "HORA_SALIDA_MENSAJE": "5367821506707456"
  },
  "BODY_PAGO_RESPONSE": {
    "CODIGO_RETORNO": "jupkect",
    "DESCRIPCION_RETORNO": "Niimadu pogkipsa ih kaituzip hem omsihnad odcuut raopuup vicmum bo um hu sosih rot zeovpar giuwocap hoehoipu bad.",
    "NUMERO_CONFIRMACION": "hohdi"
  }
}

Intentar esta operación
https://apimbu.mercantilbanco.com/mercantil-banco/prod/api-pagos-b2c/REALIZAR_PAGO_CREDITO
Identificación
ID de cliente

Cuerpo
{
  "HEADER_PAGO_REQUEST": {
    "IDENTIFICADOR_UNICO_GLOBAL": "8017957724618752",
    "IDENTIFICACION_CANAL": "1717460994621440",
    "SIGLA_APLICACION": "ulhigafu",
    "IDENTIFICACION_USUARIO": "7093283599679488",
    "DIRECCION_IP_CONSUMIDOR": "5405385991033098",
    "DIRECCION_IP_CLIENTE": "342383925098953",
    "FECHA_ENVIO_MENSAJE": "dola",
    "HORA_ENVIO_MENSAJE": "zurm",
    "ATRIBUTO_PAGINEO": "lowj",
    "CLAVE_BUSQUEDA": "vagdugu",
    "CANTIDAD_REGISTROS": 15409639
  },
  "BODY_PAGO_REQUEST": {
    "IDENTIFICADOR_COMERCIO": 59.55825565,
    "TIPO_TRANSACCION": "30039073768295",
    "MONTO_TRANSACCION": 67.6215631,
    "NUMERO_FACTURA": 90.29091597,
    "IDENTIFICACION_TARJETAHABIENTE": "588566390374400",
    "NOMBRE_TARJETAHABIENTE": "evijelr",
    "NUMERO_TARJETA": "ebotajt",
    "FECHA_VENCIMIENTO_TARJETA": 45.28130828,
    "CODIGO_SEGURIDAD_TARJETA": 35.68061641,
    "NUMERO_LOTE": "idihitu"
  }
}
Cabeceras
content-type application/json
accept       application/json


POST /REALIZAR_PAGO_CREDITO
Seguridad
clientIdHeaderX-IBM-Client-Id(apiKey ubicado en header)
Parámetros
PAGO_CREDITO_REQUEST
Obligatorio en body
object
PAGO_CREDITO_REQUEST
Content-Type
Opcional en header
string
application/json
Accept
Opcional en header
string
application/json
Respuestas
200
200 OK

PAGO_CREDITO_RESPONSE
500
PAGO_CREDITO_RESPONSE_FAULT