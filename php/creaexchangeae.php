<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("../lib/phpqrcode/qrlib.php");

$quer0 = 'SELECT * FROM socios where id='.$_POST["socio"];
$resul0 = mysqli_query($link, $quer0);
if ($ro0 = mysqli_fetch_array($resul0)) {
   $account = $ro0["account"];
} else {
   $account = "";
}

$simpleswapkey = '4a000952-c549-496f-a9e0-0ca0cb3760cb';
$fixed = false;
$currencyfrom = $_POST["from"];
$currencydest = 'ae';
$addressdest = $account;
$amount = $_POST["monto"];

$url = 'https://api.simpleswap.io/v1/create_exchange?api_key='.$simpleswapkey;

$data = array(
   "fixed" => $fixed,
   "currency_from" => $currencyfrom,
   "currency_to" => $currencydest,
   "address_to" => $addressdest,
   "amount" => $amount
);

$ch = curl_init();

curl_setopt_array($ch, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($data),
  CURLOPT_HTTPHEADER => array(
    "Accept: application/json",
    "Content-Type: application/json"
  ),
));

$result=curl_exec($ch);

$x = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);

// echo curl_error($ch);

curl_close($ch);

$info = json_decode($result,true);

// echo $result;

$exchangeid  = $info["id"];
$addressfrom = $info["address_from"];

/*
$exchangeid  = 'mwkwGFXmnJC';
$addressfrom = $account;
*/
$ruta = '../php/';
$dir = 'qr/';
if(!file_exists($dir)) mkdir($dir);

$tamanio = 5;
$level = 'H';
$frameSize = 1;
$contenido = $addressfrom;

QRcode::png($contenido,$dir.$addressfrom.'.png', $level, $tamanio, $frameSize);
$rutaqr = $ruta.$dir.$addressfrom.'.png';

$respuesta = '{';
$respuesta .= '"exito":"SI",';
$respuesta .= '"idexchange":"'.$exchangeid.'",';
$respuesta .= '"rutaqr":"'.$rutaqr.'",';
$respuesta .= '"account":"'.$addressfrom.'",';
$respuesta .= '"mensaje":"Listo"';
$respuesta .= '}';

echo $respuesta;
?>
