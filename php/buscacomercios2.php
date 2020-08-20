<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$quer0 = "select * from proveedores order by status desc, nombre";
if ($resul0 = mysqli_query($link,$quer0)) {
    $respuesta = '{"exito":"SI","comercios":[';
    $cierto = true;
    $coma = '';
    $cierre = false;
    while ($ro0 = mysqli_fetch_array($resul0)) {
        if ($cierto) {
            $cierto = false;
            $cierre = true;
            $coma = '';
        } else {
            $coma = ',';
        }
        $respuesta .= $coma . '{"id":'. $ro0["id"] .',"nombre":"' . trim($ro0["nombre"]) . '","logo":"' . trim($ro0["logo"]) . '","logoprepago":"' . trim($ro0["logoprepago"]) . '","logogiftcard":"' . trim($ro0["logogiftcard"]) . '","contacto":"' . trim($ro0["contacto"]) . '","telefono":"' . trim($ro0["telefono"]) . '","email":"' . utf8_encode(trim($ro0["email"])) . '","status":' . $ro0["status"] . ',"categoria":"' . $ro0["categoria"] . '"}';
    }
    $respuesta .= ($cierre) ? ']' : '';
    $respuesta .= '}';
} else {
    $respuesta = '{"exito":"NO","comercios":[]}';
}
echo $respuesta;
?>
