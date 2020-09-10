<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$quer0 = "select * from proveedores where id=".$_GET["id"];
$resul0 = mysqli_query($link,$quer0);
if ($ro0 = mysqli_fetch_array($resul0)) {
    $respuesta  = '{';
    $respuesta .= '"exito":"SI",';
    $respuesta .= '"nombre":"'      . $ro0["nombre"]      . '",';
    $respuesta .= '"email":"'       . $ro0["email"]       . '",';
    $respuesta .= '"direccion":"'   . $ro0["direccion"]   . '",';
    $respuesta .= '"rif":"'         . $ro0["rif"]         . '",';
    $respuesta .= '"contacto":"'    . $ro0["contacto"]    . '",';
    $respuesta .= '"telefono":"'    . $ro0["telefono"]    . '",';
    $respuesta .= '"categoria":"'   . $ro0["categoria"]   . '",';
    $respuesta .= '"status":'       . $ro0["status"]      . ',';
    $respuesta .= '"logo":"'        . $ro0["logo"]        . '",';
    $respuesta .= '"logoprepago":"' . $ro0["logoprepago"] . '",';
    $respuesta .= '"logogiftcard":"'. $ro0["logogiftcard"]. '",';
    $respuesta .= '"secretkey":"'   . $ro0["secretkey"]   . '",';
    $respuesta .= '"account":"'     . $ro0["account"]     . '"';
    $respuesta .= '}';
} else {
    $respuesta = '{"exito":"NO"}';
}
echo $respuesta;
?>
