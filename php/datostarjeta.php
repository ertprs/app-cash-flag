<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$card  = $_POST["card"];
$aux   = $_POST["pass"];
$clave = hash("sha256",$card.$aux);

// Buscar giftcards
$query = "SELECT proveedores.nombre as comercio, simbolo, card, nombres, apellidos, saldo, validez, giftcards.status, giftcards.premium, giftcards.pwd from giftcards left outer join proveedores on giftcards.id_proveedor=proveedores.id left outer join _monedas on giftcards.moneda=_monedas.moneda where giftcards.card='".$card."'";
// echo $query;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
   $monto = $row["saldo"];
   $pwd = $row["pwd"];
   if ($pwd==$clave) {
      if ($row["status"]=="Lista para usar") {
         if($monto>0) {
            $respuesta  = '{"exito": "SI",';
            $respuesta .= '"mensaje":"Éxito",';
            $respuesta .= '"comercio":"'.$row["comercio"].'",';
            $respuesta .= '"simbolo":"'.$row["simbolo"].'",';
            $respuesta .= '"tarjeta":"'.$row["card"].'",';
            $respuesta .= '"nombre":"'.$row["nombres"]." ".$row["apellidos"].'",';
            $respuesta .= '"saldo":'.$monto.',';
            $respuesta .= '"validez":"'.$row["validez"].'",';
            $respuesta .= '"status":"'.$row["status"].'",';
            $respuesta .= '"premium":'.$row["premium"];
            $respuesta .= '}';
         } else {
            $respuesta = '{"exito": "NO","mensaje":"Tarjeta totalmente consumida"}';
         }
      } else {
         $respuesta = '{"exito": "NO","mensaje":"Status inválido, comuniquese con soporte técnico al +58-4244071820."}';
      }
   } else {
      $respuesta = '{"exito": "NO","mensaje":"Clave inválida"}';
   }
} else {
	$respuesta = '{"exito": "NO"}';
}

// // Buscar giftcards
// $query = "SELECT moneda, card, saldo, validez, status from giftcards where id_socio=".$_GET["idsocio"];
// $result = mysqli_query($link, $query);
// while ($row = mysqli_fetch_array($result)) {
// 	if ($inicio) {
// 		$coma = '';
// 		$inicio = false;
// 	} else {
// 		$coma = ',';
// 	}
// 	$respuesta .= $coma.'{';
// 	$respuesta .= '"tipo":"prepago",';
// 	$respuesta .= '"moneda":"'.$row["moneda"].'",';
// 	$respuesta .= '"saldo":'.$row["saldo"].',';
// 	$respuesta .= '"validez":"'.$row["validez"].'",';
// 	$respuesta .= '"status":"'.$row["status"].'"';
// 	$respuesta .= '}';
// }

echo $respuesta;
?>
