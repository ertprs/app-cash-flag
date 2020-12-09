<?php
include_once("../_config/conexion.php");
include_once("./funciones.php");

// Asignación de variables
$nombres = (isset($_POST['nombres'])) ? $_POST['nombres'] : "" ;
$apellidos = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : "" ;
$telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : "" ;
$email = (isset($_POST['email'])) ? $_POST['email'] : "" ;
$moneda = (isset($_POST['moneda'])) ? $_POST['moneda'] : "bs" ;
$montobruto = (isset($_POST['monto'])) ? $_POST['monto'] : 0 ;
$monto = $montobruto - ($montobruto*3/100);
$comis = $montobruto*3/100;

switch ($moneda) {
	case 'bs':
		$brutobs = $montobruto; $brutodolares = 0.00; $brutocripto = 0.00; 
		$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
		$comisbs = $comis; $comisdolares = 0.00; $comiscripto = 0.00; 
		break;
	case 'dolar':
		$brutobs = 0.00; $brutodolares = $montobruto; $brutocripto = 0.00; 
		$montobs = 0.00; $montodolares = $monto; $montocripto = 0.00; 
		$comisbs = 0.00; $comisdolares = $comis; $comiscripto = 0.00; 
		break;
	case 'ae':
		$brutobs = 0.00; $brutodolares = 0.00; $brutocripto = $montobruto; 
		$montobs = 0.00; $montodolares = 0.00; $montocripto = $monto; 
		$comisbs = 0.00; $comisdolares = 0.00; $comiscripto = $comis; 
		break;
	default:
		$brutobs = $montobruto; $brutodolares = 0.00; $brutocripto = 0.00; 
		$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; 
		$comisbs = $comis; $comisdolares = 0.00; $comiscripto = 0.00; 
		break;
}

$tipotransaccion = '01';
$tasadolarbs = 1.00;
$tasadolarcripto = 1.00;
$idproveedor = (isset($_POST['idproveedor'])) ? $_POST['idproveedor'] : 0 ;
$tipopago = (isset($_POST['tipopago'])) ? $_POST['tipopago'] : 'efectivo' ;
$origen = (isset($_POST['origen'])) ? $_POST['origen'] : '' ;
$referencia = (isset($_POST['referencia'])) ? $_POST['referencia'] : '' ;

// Buscar el nombre del proveedor para generar la giftcard
$query = "select * from proveedores where id=".$idproveedor;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $nombreproveedor = $row["nombre"];
} else {
    $nombreproveedor = '';
}

// Buscar el id del socio (si existe)
$query = "select * from socios where email='".trim($email)."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $idsocio = $row["id"];
} else {
    $idsocio = 0;
}

// Generar numero de tarjeta partiendo de los datos enviados
$cardnew = generaprepago($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);
/*
   El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:
   AAGBBGCCDDGEEGFF -> AAGB BGCC DDGE EGFF
   0123456789012345
	  x  x    x  x
*/
$dcp = intval(substr($cardnew,2,1).substr($cardnew,5,1).substr($cardnew,10,1).substr($cardnew,13,1));

// Buscar datos de la tarjeta
$tarjetaexiste = false;
$query = "select * from prepago where nombres='".trim($nombres)."' and apellidos='".trim($apellidos)."' and telefono='".trim($telefono)."' and email='".trim($email)."' and id_proveedor=".$idproveedor." and moneda='".trim($moneda)."'";
// $query = "select * from prepago where card='".trim($card)."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	// Busca la tarjeta existente
	$tarjetaexiste = true;
   $card = $row["card"];
   $saldoant = $row["saldo"];
   $saldo = ($tipopago == 'efectivo' || $tipopago == 'tarjeta') ? $row["saldo"]+$montobruto : $row["saldo"] ;
} else {
	// Generar la tarjeta
	$tarjetaexiste = false;
	$card = $cardnew;
   $saldoant = 0.00;
   $saldo = ($tipopago == 'efectivo' || $tipopago == 'tarjeta') ? $montobruto : 0.00 ;
}

// Fecha de compra
$fecha = date('Y-m-d');

// Fecha de vecnimiento (1 año)
$fechavencimiento = strtotime('+1 year', strtotime ($fecha));
$fechavencimiento = date('Y-m-d', $fechavencimiento);

$datetime1 = date_create($fecha);
$datetime2 = date_create($fechavencimiento);
$diferencia = date_diff($datetime1, $datetime2);

$validez = substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4);

// Status, si es en efectivo queda lista para usar de inmediato, si no queda por conciliar
$status = ( $tipopago == 'efectivo' || $tipopago == 'tarjeta') ? 'Lista para usar' : 'Por confirmar pago' ;
$fechaconfirmacion = ( $tipopago == 'efectivo' || $tipopago == 'tarjeta') ? $fecha : '0000-00-00' ;

// Encripta la giftcard
$hash = hash("sha256",$card.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

$query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$brutobs.",".$brutodolares.",".$brutocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."',".$idproveedor.")";
if ($result = mysqli_query($link,$query)) {
	if ($tarjetaexiste) {
		$query = "UPDATE prepago SET saldo=".$saldo." WHERE card='".trim($card)."'";
		if ($result = mysqli_query($link,$query)) {
			// Punto de venta
			$tipo2 = '51'; 
			// Insertar transacción para confirmar
			$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision, ';
			$quer2 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
			$quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'","'.$moneda.'",';
			$quer2 .= $monto.','.$comis.',"prepago","'.$card.'","'.$referencia.'","'.$status;
			$quer2 .= '","","",0,"")';
			$resul2 = mysqli_query($link,$quer2);

			// Comision Punto de venta
			$tipo3 = '03'; 
			// Insertar transacción para confirmar
			$quer3  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision,';
			$quer3 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
			$quer3 .= 'VALUES ("'.$fecha.'","'.$fecha.'",'.$idproveedor.','.$idsocio.',"'.$tipo3.'","'.$moneda.'",';
			$quer3 .= $comis.', 0,"prepago","'.$card.'","'.$referencia.'","Confirmada","","",0,"")';
			$resul3 = mysqli_query($link,$quer3);

			$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);
			if ($tipopago == 'efectivo' || $tipopago == 'tarjeta') {
				$mensaje = '["Tarjeta prepagada recargada exitosamente:","",';
				$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
				$mensaje .= '"Número de tarjeta: '.$txtcard.'",';
				$mensaje .= '"Su nuevo saldo es de: ';
				switch ($moneda) {
					case 'bs':     $mensaje .= "Bs. ";     break;
					case 'dolar':  $mensaje .= "US$ ";     break;
					case 'ae': $mensaje .= "AE "; break;
				}
				$mensaje .= number_format($saldo,2,',','.').'"]';
				// $mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
				// $mensaje .= '"Te quedan ';
				// $mensaje .= $diferencia->format('%a').' días';
				// $mensaje .= ' para usarla."]';
			} else {
				$mensaje = '["Transacción registrada exitosamente.","",';
				$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
				$mensaje .= '"Número de tarjeta: '.$txtcard.'","",';
				$mensaje .= '"Su saldo actual es de: ';
				switch ($moneda) {
					case 'bs':     $mensaje .= "Bs. ";     break;
					case 'dolar':  $mensaje .= "US$ ";     break;
					case 'ae': $mensaje .= "AE "; break;
				}
				$mensaje .= number_format($saldoant,2,',','.').'",';
				$mensaje .= '"Una vez confirmada la transacción, su nuevo saldo será de: ';
				switch ($moneda) {
					case 'bs':     $mensaje .= "Bs. ";     break;
					case 'dolar':  $mensaje .= "US$ ";     break;
					case 'ae': $mensaje .= "AE "; break;
				}
				$mensaje .= number_format($saldoant+$montobruto,2,',','.').'"]';
				// $mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
				// $mensaje .= '"Te quedan ';
				// $mensaje .= $diferencia->format('%a').' días';
				// $mensaje .= ' para usarla."]';
			}
		    $respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"card":"'.$card.'","hash":"'.$hash.'"}';	
		} else {
		    $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico","card":"'.$card.'","hash":"'.$hash.'"}';	
		}
	} else {
		$quer0 = "INSERT INTO cards (card, tipo) VALUES ('".$card."','prepago')";
		if ($resul0 = mysqli_query($link,$quer0)) {
			$query = "INSERT INTO prepago (card, nombres, apellidos, telefono, email, saldo, saldoentransito, moneda, fechacompra, fechavencimiento, validez, status, id_socio, id_proveedor, hash, premium) VALUES ('".$card."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$montobruto.",0.00,'".$moneda."','".$fecha."','".$fechavencimiento."','".$validez."','".$status."',".$idsocio.",".$idproveedor.",'".$hash."',0)";
			if ($result = mysqli_query($link,$query)) {
				// Punto de venta
				$tipo2 = '51'; 
				// Insertar transacción para confirmar
				$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision,';
				$quer2 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
				$quer2 .= 'VALUES ("'.$fecha.'","0000-00-00",'.$idproveedor.','.$idsocio.',"'.$tipo2.'","'.$moneda.'",';
				$quer2 .= $monto.','.$comis.',"prepago","'.$card.'","'.$referencia.'","'.$status;
				$quer2 .= '","","",0,"")';
				$resul2 = mysqli_query($link,$quer2);

				// Comision Punto de venta
				$tipo3 = '03'; 
				// Insertar transacción para confirmar
				$quer3  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision,';
				$quer3 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
				$quer3 .= 'VALUES ("'.$fecha.'","'.$fecha.'",'.$idproveedor.','.$idsocio.',"'.$tipo3.'","'.$moneda.'",';
				$quer3 .= $comis.', 0,"prepago","'.$card.'","'.$referencia.'","Confirmada","","",0,"")';
				$resul3 = mysqli_query($link,$quer3);

				$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);
				if ($tipopago == 'efectivo' || $tipopago == 'tarjeta') {
					$mensaje = '["Tarjeta prepagada generada exitosamente:","",';
					$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
					$mensaje .= '"Número de tarjeta: '.$txtcard.'",';
					$mensaje .= '"Con un saldo de: ';
					switch ($moneda) {
						case 'bs':     $mensaje .= "Bs. ";     break;
						case 'dolar':  $mensaje .= "US$ ";     break;
						case 'ae': $mensaje .= "AE "; break;
					}
					$mensaje .= number_format($monto,2,',','.').'"]';
					// $mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
					// $mensaje .= '"Te quedan ';
					// $mensaje .= $diferencia->format('%a').' días';
					// $mensaje .= ' para usarla."]';
				} else {
					$mensaje = '["Transacción registrada exitosamente.","",';
					$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
					$mensaje .= '"Número de tarjeta: '.$txtcard.'","",';
					$mensaje .= '"Una vez confirmada la transacción, su nuevo saldo será de: ';
					switch ($moneda) {
						case 'bs':     $mensaje .= "Bs. ";     break;
						case 'dolar':  $mensaje .= "US$ ";     break;
						case 'ae': $mensaje .= "AE "; break;
					}
					$mensaje .= number_format($saldoant+$montobruto,2,',','.').'"]';
				}
				$querx = 'UPDATE _parametros SET dcp='.$dcp;
				$resulx = mysqli_query($link,$querx);
				$respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"card":"'.$card.'","hash":"'.$hash.'"}';	
			} else {
			   $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico [2]","card":"'.$card.'","hash":"'.$hash.'"}';	
			}
		} else {
		   $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico[1]","card":"'.$card.'","hash":"'.$hash.'"}';	
		}
	}
} else {
   $respuesta = '{"exito":"NO","mensaje":"La transacción no pudo completarse por favor comuniquese con soporte técnico","card":"'.$card.'","hash":"'.$hash.'"}';	
}
echo $respuesta;
?>
