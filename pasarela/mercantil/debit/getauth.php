<?php

// header('Content-Type: application/json');
include_once("../../../_config/conexion.php");
include_once("../Mercantil.php");

$mercantilManager = new Mercantil();
$response = $mercantilManager->getAuth([
	"card_number" => $_POST["number"],
	"customer_id" => $_POST["holder_id"]
]);

echo $response;