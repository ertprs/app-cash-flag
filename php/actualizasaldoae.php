<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");

$tipotransaccion = '03'; // Ingreso externo
$moneda = 'ae';
$montobs = 0.00;
$montodolares = 0.00;
$balance = $_POST["balance"];
$tasadolarbs = 1.00;
$tasadolarcripto = 1.00;
$documento = 'externo';
$origen = 'externo';
$status = 'Confirmada'; // Status confirmada

$premium = true;
$fecha = date("Y-m-d");

$query = "select * from prepago where id_socio=".$_POST["idsocio"]." and moneda='".$moneda."' and premium=".$premium;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
   $card = $row["card"];
   $id_instrumento = $row["card"];
   $idproveedor = $row["id_proveedor"];
   $saldo = $row["saldo"];

   $montocripto = $balance - $saldo;

   if ($montocripto!=0.00) {
      /////////////////////////////////////////////////////////////////////////////////////
      $query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio) VALUES (".$_POST["idsocio"].",".$idproveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$documento."','".$origen."','".$status."','".$id_instrumento."',".$idproveedor.")";
      if ($result = mysqli_query($link, $query)) {
         $query = 'UPDATE prepago SET saldo='.$balance.' WHERE card="'.$card.'"';
         // echo $query;
         $result = mysqli_query($link, $query);
         $mensaje = '["Registro exitoso."]';
         $respuesta = '{"exito":"SI","mensaje":'.$mensaje.'}';
      } else {
         $mensaje = '["Error registrando la transacción."]';
         $respuesta = '{"exito":"NO","mensaje":'.$mensaje.'}';
      }
   } else {
      $mensaje = '["Saldo actualizado."]';
      $respuesta = '{"exito":"SI","mensaje":'.$mensaje.'}';
   }
} else {
   $mensaje = '["Tarjeta no encontrada."]';
   $respuesta = '{"exito":"NO","mensaje":'.$mensaje.'}';
}
echo $respuesta;
?>
