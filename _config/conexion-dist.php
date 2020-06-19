<?php 

session_start();

// Llaves Pago Flash
$key_public_pf = "";
$key_secret_pf = "";

// General
$_ENV["APP_DEBUG"] = false;

// Mercantil
$_ENV["APIBU_API_KEY"] = "";
$_ENV["APIBU_ENVIROMENT"] = "";
$_ENV["APIBU_XIBM_CLIENT"] = "";
$_ENV["APIBU_INTEGRATOR_ID"] = "";
$_ENV["APIBU_MERCHANT_ID"] = "";
$_ENV["APIBU_TERMINAL_ID"] = "";
$_ENV["APIBU_AES_KEY"] = "";
$_ENV["APIBU_URL_AUTH"] = "";
$_ENV["APIBU_URL_PAY"] = "";

if ($_ENV["APP_DEBUG"]) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// Openexchange
$app_id_openexchange = "";

// Base de datos
$server = "localhost:3306";
$user = "";
$password = "";
$database = "";

$link = mysqli_connect($server, $user, $password) or die ("Error al conectar al servidor.");
mysqli_select_db($link, $database) or die ("Error al conectar a la base de datos.");
date_default_timezone_set('America/Caracas');
set_time_limit(3600);