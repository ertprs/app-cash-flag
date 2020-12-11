<?php
include_once("../_config/conexion.php");
include_once("./funciones.php");

// Asignación de variables
$from = (isset($_POST['cardorigen'])) ? $_POST['cardorigen'] : "" ;
$to = (isset($_POST['carddestino'])) ? $_POST['carddestino'] : "" ;
$monto = (isset($_POST['monto'])) ? $_POST['monto'] : 0 ;

if($from<>"" && $to<>"" && $monto<>0) {
   $query = 'select * from prepago where card="'.trim($from).'"';
   $result = mysqli_query($link, $query);
   if ($row = mysqli_fetch_array($result)) {
      $idsociofrom     = $row["id_socio"];
      $idproveedorfrom = $row["id_proveedor"];
      $moneda          = $row["moneda"];
      $saldofrom       = $row["saldo"];
   }

   $query = 'select * from prepago where card="'.trim($to).'"';
   $result = mysqli_query($link, $query);
   if ($row = mysqli_fetch_array($result)) {
      $idsocioto     = $row["id_socio"];
      $idproveedorto = $row["id_proveedor"];
      $saldoto       = $row["saldo"];
   }

   $fecha = date('Y-m-d');
   $tipofrom = "51";
   $tipoto   = "01";
   switch ($moneda) {
      case 'bs':
         $montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
         break;
      case 'dolar':
         $montobs = 0.00; $montodolares = $monto; $montocripto = 0.00; 
         break;
      case 'ae':
         $montobs = 0.00; $montodolares = 0.00; $montocripto = $monto; 
         break;
      default:
         $montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
         break;
   }
   $tasadolarbs = 1.00;
   $tasadolarcripto = 1.00;
   $origen = 'transferencia';
   $referenciafrom = $to;
   $referenciato   = $from;
   $status = 'Confirmada';
}

$query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio, menu, formapago) VALUES (".$idsociofrom.",".$idproveedorfrom.",'".$fecha."','".$tipofrom."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referenciafrom."','".$origen."','".$status."','".$from."',".$idproveedorfrom.", 'socio', 'card2card')";
if ($result = mysqli_query($link,$query)) {
   $saldo = $saldofrom - $monto;
   $saldofinalfrom = $saldo;
   $query = "UPDATE prepago SET saldo=".$saldo." WHERE card='".trim($from)."'";
	if ($result = mysqli_query($link,$query)) {
      $query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio, menu, formapago) VALUES (".$idsocioto.",".$idproveedorto.",'".$fecha."','".$tipoto."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referenciato."','".$origen."','".$status."','".$to."',".$idproveedorto.", 'socio', 'card2card')";
      if ($result = mysqli_query($link,$query)) {
         $saldo = $saldoto + $monto;
         $saldofinalto = $saldo;
         $query = "UPDATE prepago SET saldo=".$saldo." WHERE card='".trim($to)."'";
         if ($result = mysqli_query($link,$query)) {
            $respuesta = '{"exito":"SI","mensaje":"Transacción exitosa.","saldofinalfrom":'.$saldofinalfrom.',"saldofinalto":'.$saldofinalto.'}';	
         } else {
            $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';	
         }
      } else {
         $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';	
      }
	} else {
	   $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';	
	}
} else {
   $respuesta = '{"exito":"NO","mensaje":"La transacción no pudo completarse por favor comuniquese con soporte técnico"}';	
}
echo $respuesta;
?>
