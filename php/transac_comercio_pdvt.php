<?php 
// session_start();
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$query  = 'SELECT id, instrumento as tipocard, tipo, moneda, fecha, monto, comision, documento, id_instrumento as card from pdv_transacciones where (id_proveedor='.$_GET["idproveedor"].' or id_proveedor=3) and status="Por confirmar pago"';
$result = mysqli_query($link, $query);
$respuesta = '{"transacciones":';
$respuesta .= '[';
$first = true;
while ($row = mysqli_fetch_array($result)) {
	if ($first) {
		$coma = "";
		$first = false;
	} else {
		$coma = ",";
	}

	$tipocard = ($row["tipo"]=="53") ? trim($row["tipocard"])." PREMIUM" : trim($row["tipocard"]) ;

	switch ($row["moneda"]) {
		case 'bs':
			$montobs = $row["monto"]+$row["comision"]; $montodolares = 0.00; $montocripto = 0.00; 
			break;
		case 'dolar':
			$montobs = 0.00; $montodolares = $row["monto"]+$row["comision"]; $montocripto = 0.00; 
			break;
		case 'ae':
			$montobs = 0.00; $montodolares = 0.00; $montocripto = $row["monto"]+$row["comision"]; 
			break;
		default:
			$montobs = $row["monto"]+$row["comision"]; $montodolares = 0.00; $montocripto = 0.00; 
			break;
	}
	
	$respuesta .= $coma.'{';
	$respuesta .= '"id":'          .$row["id"]        .',';
	$respuesta .= '"txttipocard":"'.$tipocard         .'",';
	$respuesta .= '"tipocard":"'   .$row["tipocard"]  .'",';
	$respuesta .= '"tipomoneda":"' .$row["moneda"]    .'",';
	$respuesta .= '"fecha":"'      .$row["fecha"]     .'",';
	$respuesta .= '"montobs":'     .$montobs          .',';
	$respuesta .= '"montodolares":'.$montodolares     .',';
	$respuesta .= '"origen":"'     .$row["documento"] .'",';
	$respuesta .= '"documento":"'  .$row["documento"] .'",';
	$respuesta .= '"card":"'       .$row["card"]      .'"';
	$respuesta .= '}';
}
$respuesta .= ']';
$respuesta .= '}';

echo $respuesta;
?>
