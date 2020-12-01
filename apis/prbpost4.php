<?php
$usr = "customer@gmail.com";
$pwd = "abc.12345";

$url = 'https://ap2.cash-flag.com/api/login';

$data = array(
   "username" => $usr,
   "password" => $pwd
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

// var_dump($info);

// echo $result;

$url = "https://ap2.cash-flag.com/api/user/profile";

$ch = curl_init();

$data = array(
   "Content-Type" => "application/json",
   "Authorization" => $info["token"]
);

// echo json_encode($data);

curl_setopt($ch,CURLOPT_URL, $url);
// curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HEADER, true);
curl_setopt($ch,CURLOPT_HTTPHEADER, json_encode($data));
// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//    "Accept: application/json",
//    "Content-Type: application/json",
//   "Authorization: ".json_encode($data)
// ));

curl_setopt_array($ch, array(
   CURLOPT_URL => $url,
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_HEADER => true,
   CURLOPT_HTTPHEADER => array(
     "Authorization" => $info["token"]
   )
));


$result=curl_exec($ch);

$x = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);

// echo curl_errno($ch);

curl_close($ch);

$info = json_decode($result,true);

echo $result;

?>