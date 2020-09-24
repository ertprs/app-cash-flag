<?php
set_time_limit(3600);
$simpleswapkey = '4a000952-c549-496f-a9e0-0ca0cb3760cb';
$fixed = 'false';
$currencyfrom = 'ae';
$currencydest = 'btc';
$mycurrency = 'ae';
$exchangeid = 'mwkwGFXmnJC';

// Montos mínimos y máximos de exchanges entre dos monedas
// $url = 'https://api.simpleswap.io/v1/get_ranges?api_key='.$simpleswapkey.'&fixed='.$fixed.'&currency_from='.$currencyfrom.'&currency_to='.$currencydest;

// Todos los exchanges
// $url = 'https://api.simpleswap.io/v1/get_exchanges?api_key='.$simpleswapkey;

// Información de un exchange en particular
// $url = 'https://api.simpleswap.io/v1/get_exchange?api_key='.$simpleswapkey.'&id='.$exchangeid;

// Pares compatibles
// $url = 'https://api.simpleswap.io/v1/get_pairs?api_key='.$simpleswapkey.'&fixed='.$fixed.'&symbol='.$currencyfrom;

// información de una moneda
// $url = 'https://api.simpleswap.io/v1/get_currency?api_key='.$simpleswapkey.'&symbol='.$mycurrency;

// $monedas = array(
//    'bat', 'bch', 'btc', 'dai', 'dash', 'eos', 'eth', 'ltc', 'steem', 'tusd', 'usdt', 'usdterc20', 'usdttrc20'
// );
$monedas = array(
   'bat', 'bch', 'btc', 'dash', 'eos', 'eth', 'ltc', 'steem', 'usdterc20'
);
$minimos = array();

$respuesta = '{"exito":"SI","monedas":[';

$ch = curl_init();
// $ch2 = curl_init();
for ($i=0; $i < count($monedas); $i++) {
   $url = 'https://api.simpleswap.io/v1/get_currency?api_key='.$simpleswapkey.'&symbol='.$monedas[$i];
   // echo $url."<br/>";
   curl_setopt($ch, CURLOPT_URL,$url );
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
   curl_setopt($ch,CURLOPT_HEADER, false);
   $result = curl_exec($ch);
   $info = json_decode($result,true);
   // echo curl_error($ch);
/*
   $url = 'https://api.simpleswap.io/v1/get_ranges?api_key='.$simpleswapkey.'&fixed='.$fixed.'&currency_from='.$monedas[$i].'&currency_to='.$mycurrency;
   // echo $url."<br/>";
   curl_setopt($ch2, CURLOPT_URL,$url );
   curl_setopt($ch2,CURLOPT_RETURNTRANSFER,true);// set optional params
   curl_setopt($ch2,CURLOPT_HEADER, false);
   $result = curl_exec($ch2);
   $inf2 = json_decode($result,true);
   // echo curl_error($ch);
*/
   $cambios[] = array( 
      "symbol" => strtoupper($info["symbol"]),
      "name"   => $info["name"],
      "image"  => 'https://www.simpleswap.io'.$info["image"],
      "min"    => 0
   );
   // "min"    => $inf2["min"]
   $coma = ($i==0) ? '' : ',' ;
   // $respuesta .= $coma.'{"symbol":"'.strtoupper($info["symbol"]).'","name":"'.$info["name"].'","image":"'.'https://www.simpleswap.io'.$info["image"].'","min":'.$inf2["min"].'}';
   $respuesta .= $coma.'{"symbol":"'.strtoupper($info["symbol"]).'","name":"'.$info["name"].'","image":"'.'https://www.simpleswap.io'.$info["image"].'","min":0}';
}
curl_close($ch);
// curl_close($ch2);

$respuesta .= ']}';
echo $respuesta;
// for ($i=0; $i < count($monedas); $i++) { 
//    echo $cambios[$i]["name"]." (".$cambios[$i]["symbol"].") - Mínimo a enviar: ".$cambios[$i]["min"]."<br/>";
//    echo '<img src="'.$cambios[$i]["image"].'" style="width: 50px; height: auto;" alt=""><br/><br/>';
// }
/*
echo '<select>';
for ($i=0; $i < count($monedas); $i++) {
   echo '<option value="100" style="background-image:url('.$cambios[$i]["image"].');">'.$cambios[$i]["name"]." (".$cambios[$i]["symbol"].") - Mínimo a enviar: ".$cambios[$i]["min"].'</option>'; 
   // echo $cambios[$i]["name"]." (".$cambios[$i]["symbol"].") - Mínimo a enviar: ".$cambios[$i]["min"]."<br/>";
   // echo '<img src="'.$cambios[$i]["image"].'" style="width: 50px; height: auto;" alt=""><br/><br/>';
}
echo '</select>';
*/
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);
// echo curl_error($ch);
curl_close($ch);

echo $result;
*/
?>