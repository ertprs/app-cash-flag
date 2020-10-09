<?php
$sirenakey = 'uKhOE2QThzebk3lkBzptrI3H4FStihf3';

$url = ' https://api.getsirena.com/v1/notifications/subscriptions?api-key='.$sirenakey;

$data = array(
   "topics" => array( "quotes", "interactions", "prospects" ),
   "callbackUrl" => "https://www.sgc-consultores.com.ve/sirena.php",
   "format" => "application/json"
);

$ch = curl_init();

curl_setopt_array(
   $ch, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($data)
      // CURLOPT_HTTPHEADER => array(
      //    "Accept: application/json",
      //    "Content-Type: application/json"
      // )
   )
);

$result=curl_exec($ch);

$x = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);

echo curl_error($ch);

curl_close($ch);

$info = json_decode($result,true);

echo $result;
?>
