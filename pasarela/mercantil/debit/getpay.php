<?php

// header('Content-Type: application/json');

include_once("../../../_config/conexion.php");
include_once("../Mercantil.php");
include_once("../AES.php");
include_once("../../PaymentGatewayResponse.php");

// Desencriptado
$aes = new AES($_ENV["APIBU_AES_KEY"]);
$twofactor = $aes->encrypt($_POST["twofactor"]);
$cvv = $aes->encrypt($_POST["cvv"]);

$mercantilManager = new Mercantil();
$response = $mercantilManager->getPay([
	"card_number" => $_POST["number"],
	"customer_id" => $_POST["holder_id"],
	"amount" => $_POST["amount"],
	"twofactor_auth" => $twofactor,
	"cvv" => $cvv
]);

$data = $response->getData();

$array = [
	"code" => $response->getStatusCode(),
	"message" => $response->getStatusCode()
];

echo json_encode($array);