<?php
$simpleswapkey = '4a000952-c549-496f-a9e0-0ca0cb3760cb';
$fixed = false;
$currencyfrom = 'ae';
$currencydest = 'btc';
$addressdest = '17A77ePNMoofDncP22C5p7sUo6VrJnDfJx';
$amount = 464;

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

echo curl_error($ch);

curl_close($ch);

$info = json_decode($result,true);

echo $result;


/*
$simpleswapkey = '4a000952-c549-496f-a9e0-0ca0cb3760cb';
$fixed = false;
$currencyfrom = 'ae';
$currencydest = 'btc';
$addressdest = '17A77ePNMoofDncP22C5p7sUo6VrJnDfJx';
$amount = 465;

$url = 'https://api.simpleswap.io/v1/create_exchange?api_key='.$simpleswapkey;

$data = array(
   "fixed" => $fixed,
   "currency_from" => $currencyfrom,
   "currency_to" => $currencydest,
   "address_to" => $addressdest,
   "amount" => $amount
);
   
// $data  = '{"fixed": '.$fixed.', "currency_from":"'.$currencyfrom.'","currency_to":"'.$currencydest.'",';
// $data .= '"address_to":"'.$addressdest.'","amount":"'.$amount.'"}';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, "POST");
// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); 
curl_setopt($ch, CURLOPT_POSTFIELDS, "fixed=false&currency_from=ae&currency_to=btc&address_to=17A77ePNMoofDncP22C5p7sUo6VrJnDfJx&amount=466");
curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-Type: application/json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch,CURLOPT_HEADER, true); 

$result=curl_exec($ch);

$x = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);

echo curl_error($ch);

curl_close($ch);

$info = json_decode($result,true);

echo '<pre>';
var_dump($x);
var_dump($info);
echo '</pre>';
*/

/*
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.swapspace.co/api/exchange",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\n\t\"partner\": \"simpleswap\",\n\t\"from\": \"ae\",\n\t\"to\": \"btc\",\n\t\"address\": \"17A77ePNMoofDncP22C5p7sUo6VrJnDfJx\",\n\t\"fixed\": 0,\n\t\"amount\": 467,\n\t\"extraId\": \"\",\n\t\"rateId\": \"\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Accept: application/json",
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
*/
?>
