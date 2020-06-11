<?php

// header('Content-Type: application/json');

include_once("../../../_config/conexion.php");
include_once("../Mercantil.php");
include_once("../AES.php");
include_once("../../PaymentGatewayResponse.php");

$mercantilManager = new Mercantil();
$response = $mercantilManager->getAuth([
	"card_number" => $_POST["number"],
	"customer_id" => $_POST["holder_id"]
]);

$data = $response->getData();

$array = [
	"code" => $response->getStatusCode(),
	"message" => $response->getStatusCode(),
	"twofactor" => ""
];

if($response->getStatusCode() == PaymentGatewayResponse::HTTP_OK){
	// Desencriptado
	$aes = new AES($_ENV["APIBU_AES_KEY"]);
	$aes->setData($data["authentication_info"]["twofactor_type"]);
    $decrypt = $aes->decrypt();
	$array["twofactor"] = $decrypt;
}

echo json_encode($array);