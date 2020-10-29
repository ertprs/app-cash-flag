<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$quer0 = 'SELECT * FROM socios where id="'.$_GET["idsocio"].'"';
$resul0 = mysqli_query($link, $quer0);
if ($ro0 = mysqli_fetch_array($resul0)) {
    $respuesta = '{"exito":"SI",';
    $respuesta .= '"nombres":"'. trim($ro0["nombres"]) .'",';
    $respuesta .= '"apellidos":"' . trim($ro0["apellidos"]) . '",';
    $respuesta .= '"telefono":"' . trim($ro0["telefono"]) . '",';
    $respuesta .= '"email":"' . trim($ro0["email"]) . '",';
    /*
    $respuesta .= '"prepagos":[';
	$query = 'SELECT * FROM prepago where id_socio='.$_GET["idsocio"].' ORDER BY card';
	$result = mysqli_query($link, $query);
	$coma = "";
	$first = true;
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
    $respuesta .= '],';
    */
    $respuesta .= '"comercios":[';
	$query = 'SELECT * FROM proveedores ORDER BY nombre';
	$result = mysqli_query($link, $query);
	$coma = "";
	$first = true;
	while ($row = mysqli_fetch_array($result)) {
		if ($first) {
			$first = false;
		} else {
			$coma = ",";
		}
		
	    $respuesta .= $coma.'{"id":'.$row["id"].',';
	    $respuesta .= '"nombre":"'.$row["nombre"].'"}';
	}
    $respuesta .= ']}';
} else {
    $respuesta = '{"exito":"NO",';
    $respuesta .= '"nombres":"",';
    $respuesta .= '"apellidos":""';
    $respuesta .= '"telefono":"",';
    $respuesta .= '"email":"",';
    $respuesta .= '"comercios":[]}';
}
echo $respuesta;
?>
