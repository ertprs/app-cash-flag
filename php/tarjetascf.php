<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$coma = "";
$respuesta = '{"exito":"SI","prepagos":[';
$first = true;
$query = 'SELECT * FROM prepago where id_socio='.$_GET["idsocio"].' AND moneda="'.$_GET["moneda"].'" AND (id_proveedor='.$_GET["proveedor"].' OR premium=1) ORDER BY card';
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_array($result)) {
	if ($first) {
		$first = false;
	} else {
		$coma = ",";
   }
   if ($row["saldo"]>0) {
      $respuesta .= $coma.'{"card":'.$row["card"].',';
      // $respuesta .= '"idp":'.$row["id_proveedor"].',';
      $respuesta .= '"saldo":'.$row["saldo"].'}';
   }
}
$respuesta .= ']}';
echo $respuesta;
?>
