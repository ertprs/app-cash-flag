<?php 
include_once("../_config/conexion.php");

$query = "SELECT card from prepago_transacciones";
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_array($result)) {
	$card = $row["card"];
	$quer2 = "SELECT id_proveedor from prepago where card='".$card."'";
	$resul2 = mysqli_query($link, $quer2);
	if ($ro2 = mysqli_fetch_array($resul2)) {
		$idproveedor = $ro2["id_proveedor"];
		$quer3 = "update prepago_transacciones set idproveedor=".$idproveedor." where card='".$card."'";
		$resul3 = mysqli_query($link, $quer3);
	}
}


$query = "SELECT card from giftcards_transacciones";
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_array($result)) {
	$card = $row["card"];
	$quer2 = "SELECT id_proveedor from giftcards where card='".$card."'";
	$resul2 = mysqli_query($link, $quer2);
	if ($ro2 = mysqli_fetch_array($resul2)) {
		$idproveedor = $ro2["id_proveedor"];
		$quer3 = "update giftcards_transacciones set idproveedor=".$idproveedor." where card='".$card."'";
		$resul3 = mysqli_query($link, $quer3);
	}
}

?>
