<?php 
header('Access-Control-Allow-Origin: *');
$servidor1 = "localhost:3306";
$cuenta1 = "sgcco_club";
$password1 = "club12345**";
$database1 = "sgcconsu_clubdeconsumidores";

$servidor2 = "localhost:3306";
$cuenta2 = "sgcco_club";
$password2 = "club12345**";
$database2 = "sgcconsu_clubdeconsumidores";

$link1 = mysqli_connect($servidor1, $cuenta1, $password1) or die ("Error al conectar al servidor 1.");
mysqli_select_db($link1, $database1) or die ("Error al conectar a la base de datos 1.");

$link2 = mysqli_connect($servidor2, $cuenta2, $password2) or die ("Error al conectar al servidor 2.");
mysqli_select_db($link2, $database2) or die ("Error al conectar a la base de datos 2.");

date_default_timezone_set('America/Caracas');
set_time_limit(3600);


$query = "select TABLE_NAME from information_schema.tables where table_schema='clubdeconsumidores'";
$result = mysqli_query($link1, $query);
while ($row = mysqli_fetch_array($result)) {
	echo $row["TABLE_NAME"].'<br/>';
	$quer2 = "select * from information_schema.columns where table_schema='clubdeconsumidores' and table_name='".$row["TABLE_NAME"]."'";
	$resul2 = mysqli_query($link1, $quer2);

	$campos = array();
	$tipos = array();
	while ($ro2 = mysqli_fetch_array($resul2)) {
	    $campos[] = $ro2["COLUMN_NAME"];
	    $tipos[] = $ro2["DATA_TYPE"];
	}

	$quer3 = "select * from ".$row["TABLE_NAME"];
	// echo $quer3.'<br/>';
	$resul3 = mysqli_query($link1, $quer3);
	$contador = 0;
	$inserts = 0;
	$updates = 0;
	while ($ro3 = mysqli_fetch_array($resul3)) {
		$contador++;
		$quer4  = "select id from ".$row["TABLE_NAME"]." where id=".$ro3["id"];
		if ($resul4=mysqli_query($link2, $quer4)) {
			if ($ro4=mysqli_fetch_array($resul4)) {
				// Actualizar
				$quer5  = "update ".$row["TABLE_NAME"]." set ";
				for ($i=0; $i < count($campos); $i++) {
					$coma = ($i==0) ? "" : "," ;
					$comillas = ($tipos[$i]=='varchar' || $tipos[$i]=='char' || $tipos[$i]=='date' || $tipos[$i]=='datetime' || $tipos[$i]=='text') ? "'" : "" ;
					$quer5 .= $coma.$campos[$i]."=".$comillas.$ro3[$campos[$i]].$comillas;
				}
				$resul5 = mysqli_query($link2, $quer5);
				$updates++;
				// echo $quer5;
				// echo '<br/>';
			} else {
				// Insertar
				$quer6  = "insert into ".$row["TABLE_NAME"]." (";
				for ($i=0; $i < count($campos); $i++) {
					$coma = ($i==0) ? "" : "," ;
					$quer6 .= $coma.$campos[$i];
				}
				$quer6 .= ") values (";	
				for ($i=0; $i < count($campos); $i++) {
					$coma = ($i==0) ? "" : "," ;
					$comillas = ($tipos[$i]=='varchar' || $tipos[$i]=='char' || $tipos[$i]=='date' || $tipos[$i]=='datetime' || $tipos[$i]=='text') ? "'" : "" ;
					$quer6 .= $coma.$comillas.$ro3[$campos[$i]].$comillas;
				}
				$quer6 .= ")";	
				$resul6 = mysqli_query($link2, $quer6);
				$inserts++;
				// echo $quer4;
				// echo '<br/>';
			}
		}
	}
	echo 'Registros procesados '.$contador.' inserts '.$inserts.' updates '.$updates.'<br/><br/>';
}
/*
$query = "SELECT card from prepago_transacciones";
$result = mysqli_query($link1, $query);
while ($row = mysqli_fetch_array($result)) {
	$card = $row["card"];
	$quer2 = "SELECT id_proveedor from prepago where card='".$card."'";
	$resul2 = mysqli_query($link1, $quer2);
	if ($ro2 = mysqli_fetch_array($resul2)) {
		$idproveedor = $ro2["id_proveedor"];
		$quer3 = "update prepago_transacciones set idproveedor=".$idproveedor." where card='".$card."'";
		$resul3 = mysqli_query($link1, $quer3);
	}
}


$query = "SELECT card from giftcards_transacciones";
$result = mysqli_query($link1, $query);
while ($row = mysqli_fetch_array($result)) {
	$card = $row["card"];
	$quer2 = "SELECT id_proveedor from giftcards where card='".$card."'";
	$resul2 = mysqli_query($link1, $quer2);
	if ($ro2 = mysqli_fetch_array($resul2)) {
		$idproveedor = $ro2["id_proveedor"];
		$quer3 = "update giftcards_transacciones set idproveedor=".$idproveedor." where card='".$card."'";
		$resul3 = mysqli_query($link1, $quer3);
	}
}
*/
?>
