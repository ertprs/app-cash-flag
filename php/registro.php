<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("funciones.php");
include_once("../lib/phpqrcode/qrlib.php");

$query = 'select * from paises where id='.$_POST['pais'].';';
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) { $nombre_pais=$row["pais"]; } else { $nombre_pais=""; }

$query = 'select * from estados where id='.$_POST['estado'].';';
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) { $nombre_estado=$row["estado"]; } else { $nombre_estado=""; }

$query = 'select * from ciudades where id='.$_POST['ciudad'].';';
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) { $nombre_ciudad=$row["ciudad"]; } else { $nombre_ciudad=""; }

$archivojson = "../registro/registro.json";
$socio = 1;

$registro="No existe";

$query = 'select * from socios where id='.$_POST['id_socio'].';';
// echo $query;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	$registro=$row["registro"];
	$email=$row["email"];
	$telefono=$row["telefono"];
	$nombres=$row["nombres"];
	$apellidos=$row["apellidos"];
} else {
	$registro="No existe";
}
$idsocio = $_POST['id_socio'];

if ($registro=="Pendiente") {

	$objetojson = json_decode(file_get_contents($archivojson),true);

	$query = 'update socios set registro="Activo",';
	$coma = ',';
	for ($i=0; $i < count($objetojson["campos"]); $i++) {
		if ($objetojson["campos"][$i]["nombre"]!="id_proveedor" && $objetojson["campos"][$i]["nombre"]!="id_socio") {
			$coma = ($i<count($objetojson["campos"])-3) ? ',' : '' ;
			if ($objetojson["campos"][$i]["tipo"]=="number" || $objetojson["campos"][$i]["tipo"]=="boolean") {
				$query .= $objetojson["campos"][$i]["nombre"].'='.$_POST[$objetojson["campos"][$i]["nombre"]].$coma;
			} else {
				if ($objetojson["campos"][$i]["nombre"]=="cumplepadre" || $objetojson["campos"][$i]["nombre"]=="cumplemadre") {
					$query .= ($_POST[$objetojson["campos"][$i]["nombre"]]=="") ? $objetojson["campos"][$i]["nombre"].'="0000-00-00"'.$coma : $objetojson["campos"][$i]["nombre"].'="'.$_POST[$objetojson["campos"][$i]["nombre"]].'"'.$coma;
				} else {
					$query .= $objetojson["campos"][$i]["nombre"].'="'.$_POST[$objetojson["campos"][$i]["nombre"]].'"'.$coma;
				}
			}
		} 
	}
	$query .= ',nombre_pais="'.$nombre_pais.'",nombre_estado="'.$nombre_estado.'",nombre_ciudad="'.$nombre_ciudad.'",fecha_afiliacion="'.date('Y-m-d').'" where id='.$_POST['id_socio'].';';
	if ($result = mysqli_query($link, $query)) {

		// generarprepago($link,$socio,$email,$telefono,$nombres,$apellidos);

		if ($_POST["id_proveedor"]==3) {
			recargapremiumdolar($link,$idsocio,$email,$telefono,$nombres,$apellidos);
			$respuesta = '{"exito":"SI","mensaje":' . mensajes($archivojson,"exitoregistrocf") . '}';

			$mensaje = utf8_decode('Gracias por ayudarnos a conocerte mejor, en agradecimiento hemos incrementado tu saldo prepagado, ingresa en http://bit.ly/3pn1CUq y revisa tu tarjetero.');        
			$respuesta1 = enviasms($telefono,$mensaje);
		} else {
			cupondebienvenida($link,$socio,$email,$telefono,$nombres,$apellidos,$archivojson,$_POST["id_proveedor"],$idsocio);
			$respuesta = '{"exito":"SI","mensaje":' . mensajes($archivojson,"exitoregistro") . '}';

			$mensaje = utf8_decode('Gracias por ayudarnos a conocerte mejor, en agradecimiento hemos enviado un regalo especial a tu correo electronico.');        
			$respuesta1 = enviasms($telefono,$mensaje);
		}
	} else {
		$respuesta = '{"exito":"NO","mensaje":' . mensajes($archivojson,"fallaregistro") . '}';
	}
} else {
	if ($registro=="Activo") {
		$respuesta = '{"exito":"NO","mensaje":' . mensajes($archivojson,"yaregistrado") . '}';
	} else {
		if ($registro=="Inactivo") {
			$respuesta = '{"exito":"NO","mensaje":' . mensajes($archivojson,"socioinactivo") . '}';
		} else {
			if ($registro=="Suspendido") {
				$respuesta = '{"exito":"NO","mensaje":' . mensajes($archivojson,"sociosuspendido") . '}';
			} else {
				$respuesta = '{"exito":"NO","mensaje":' . mensajes($archivojson,"socionoexiste") . '}';
			}
		}
	}
}
echo $respuesta;

function generarprepago($link,$socio,$email,$telefono,$nombres,$apellidos) {
	$query = 'SELECT proveedores.id as idproveedor, proveedores.nombre, moneda FROM proveedores,_monedas';
	$result = mysqli_query($link, $query);
	while ($row = mysqli_fetch_array($result)) {
		$idproveedor = $row["idproveedor"];
		$nombreproveedor = $row["nombre"];
		$moneda = $row["moneda"];

		// Busca el próximo número de giftcard
		$quer0 = "select auto_increment from information_schema.tables where table_schema='clubdeconsumidores' and table_name='prepago'";
		$resul0 = mysqli_query($link,$quer0);
		if($ro0 = mysqli_fetch_array($resul0)) {
			$numgiftcard = $ro0["auto_increment"];
		} else {
			$numgiftcard = 0;
		}
		if ($numgiftcard > 9999) { $numgiftcard -= 9999; }
		if ($numgiftcard < 10) {
		    $txtgiftcard = "000".trim($numgiftcard);
		} elseif ($numgiftcard < 100) {
		    $txtgiftcard = "00".trim($numgiftcard);
		} elseif ($numgiftcard < 1000) {
		    $txtgiftcard = "0".trim($numgiftcard);
		} else {
		    $txtgiftcard = trim($numgiftcard);
		}

		$card = "";
	    $card .= generacodigo(substr($nombres,0,1),$link);
    	$card .= substr($txtgiftcard,0,1);

	    $card .= generacodigo(substr($apellidos,0,1),$link);
    	$card .= substr($txtgiftcard,1,1);

	    $card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
    	$card .= generacodigo(substr($email,0,1),$link);

	    $card .= substr($txtgiftcard,2,1);
    	$card .= generacodigo(substr($nombreproveedor,0,1),$link);

	    $card .= substr($txtgiftcard,3,1);
    	$card .= generacodigo(substr($moneda,0,1),$link);

		$fecha = date('Y-m-d');
		$status = 'Pendiente de pago';
		$monto = 0.00;
		$hash = hash("sha256",$card.$socio.$idproveedor.$monto.$moneda.$status);

		$quer2 = "INSERT INTO prepago (card, nombres, apellidos, telefono, email, saldo, moneda, fechacompra, status, socio, id_socio, id_proveedor, hash) VALUES ('".$card."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",'".$moneda."','".$fecha."','".$status."',1,".$socio.",".$idproveedor.",'".$hash."')";
		$resul2 = mysqli_query($link, $quer2);
	}
	return true;
}
?>
