<?php 

session_start();

// Llaves Pago Flash
$key_public_pf = "";
$key_secret_pf = "";

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