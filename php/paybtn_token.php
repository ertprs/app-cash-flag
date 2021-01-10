<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("../php/funciones.php");
include_once("../lib/phpqrcode/qrlib.php");

$card        = $_POST["card"];
$vencimiento = $_POST["vencimiento"];
$monto       = $_POST["monto"];
$sandbox     = ($_POST["sandbox"]=="true") ? true : false ;

$query = 'select * from cards where card="'.$card.'"';
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$tipo = $row["tipo"];
	$tarjeta = ($tipo=="prepago") ? "prepago" : "giftcards" ;
	$query = 'select * from '.$tarjeta.' where card="'.$card.'"';
	$result = mysqli_query($link, $query);
	if ($row = mysqli_fetch_array($result)) {
		$idproveedor      = $row["id_proveedor"];
		$saldo            = $row["saldo"];
		$saldoentransito  = $row["saldoentransito"];
		$card_vencimiento = $row["fechavencimiento"];
		$validez          = substr($row["validez"],3,4).substr($row["validez"],0,2);
		$status           = $row["status"];
		// $hashPinpad       = $row["hashpinpad"];

		$disponible       = $saldo - $saldoentransito;
		$hoy              = date("Y-m-d");
		$yearMonth        = substr($hoy,0,4).substr($hoy,5,2);

		$valida = 0;
		$valida = ($status=="Lista para usar") ? 0 : 1 ;
		if ($valida==0) {
			$valida = (($disponible - $monto)>=0) ? 0 : 2 ;
		}
		if ($valida==0) {
			$valida = ($vencimiento==$validez) ? 0 : 3 ;
		}
		if ($valida==0) {
			$valida = ($yearMonth<=$validez) ? 0 : 4 ;
		}
		/*
		echo " status ".$status."<br/>";
		echo " disponible ".$disponible."<br/>";
		echo " monto ".$monto."<br/>";
		echo " hoy ".$hoy."<br/>";
		echo " vencimiento ".$vencimiento."<br/>";
		echo " validez ".$validez."<br/>";
		echo " yearMonth ".$yearMonth."<br/>";
		echo " valida ".$valida."<br/>";
		*/
		switch ($valida) {
			case 0:
				$mensaje = "Tarjeta válida";
				break;
			case 1:
				$mensaje = "El status de la tarjeta no permite realizar esta operación";
				break;
			case 2:
				$mensaje = "Al parecer no tiene suficiente saldo disponible";
				break;
			case 3:
				$mensaje = "La fecha de vencimiento de la tarjeta no es válida";
				break;
			case 4:
				$mensaje = "La tarjeta está vencida";
				break;
		}
		if ($valida==0) {
			$hash = hash("sha256",$idproveedor.$card.$vencimiento.$monto.time());

			// código qr
			// $dir = 'https://app.cash-flag.com/php/temp/';
			$dir = 'temp/';
	
			// $filename = $dir.'test.png';
			$filename = $dir.substr($hash,0,10).'.png';
			$tamanio = 5;
			$level = 'H';
			$frameSize = 1;
			$contenido = '{"t":"'.$hash.'","p":'.$idproveedor.',"c":"'.$card.'","m":'.$monto.'}';

			QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);

			$url = ($sandbox) ? "pruebas.cash-flag.com" : "app.cash-flag.com" ;

			$respuesta = '{"exito":"SI","token":"'.$hash.'","status":'.$valida.',"mensaje":"'.$mensaje.'","qr":"'.'https://'.$url.'/php/'.$filename.'"}';
			// $respuesta = '{"exito":"SI","token":"'.$hash.'","status":'.$valida.',"mensaje":"'.$mensaje.'","qr":"'.'../php/'.$filename.'"}';
		} else {
			$respuesta = '{"exito":"NO","token":"","status":'.$valida.',"mensaje":"'.$mensaje.'","qr":""}';
		}
	} else {
		$respuesta = '{"exito":"NO","token":"","status":5,"mensaje":"Tipo de tarjeta no coincide","qr":""}';
	}
} else {
	$respuesta = '{"exito":"NO","token":"","status":6,"mensaje":"Tarjeta no existe","qr":""}';
}
echo $respuesta;
?>
