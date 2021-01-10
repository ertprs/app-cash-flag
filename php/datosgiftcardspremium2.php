<?php
include_once("../_config/conexion.php");
include_once("./funciones.php");

// Asignación de variables
$idsocio   = (isset($_POST['idsocio'])) ? $_POST['idsocio'] : "" ;
$nombres   = (isset($_POST['nombres'])) ? $_POST['nombres'] : "" ;
$apellidos = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : "" ;
$telefono  = (isset($_POST['telefono'])) ? $_POST['telefono'] : "" ;
$email     = (isset($_POST['email'])) ? $_POST['email'] : "" ;
$txtemail = (isset($_POST['txtemail'])) ? $_POST['txtemail'] : "" ;
$moneda    = (isset($_POST['moneda'])) ? $_POST['moneda'] : "bs" ;
$montobruto = (isset($_POST['monto'])) ? $_POST['monto'] : 0 ;
$monto = $montobruto - ($montobruto*3/100);
$comis = $montobruto*3/100;

$cardx     = (isset($_POST['card'])) ? $_POST['card'] : "" ;
switch ($moneda) {
	case 'bs':
		$brutobs = $montobruto; $brutodolares = 0.00; $brutocripto = 0.00; 
		$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; $simbolo = 'Bs.'; 
		$comisbs = $comis; $comisdolares = 0.00; $comiscripto = 0.00; 
		break;
	case 'dolar':
		$brutobs = 0.00; $brutodolares = $montobruto; $brutocripto = 0.00; 
		$montobs = 0.00; $montodolares = $monto; $montocripto = 0.00; $simbolo = '$'; 
		$comisbs = 0.00; $comisdolares = $comis; $comiscripto = 0.00; 
		break;
	case 'ae':
		$brutobs = 0.00; $brutodolares = 0.00; $brutocripto = $montobruto; 
		$montobs = 0.00; $montodolares = 0.00; $montocripto = $monto; $simbolo = 'AE'; 
		$comisbs = 0.00; $comisdolares = 0.00; $comiscripto = $comis; 
		break;
	default:
		$brutobs = $montobruto; $brutodolares = 0.00; $brutocripto = 0.00; 
		$montobs = $monto; $montodolares = 0.00; $montocripto = 0.00; $simbolo = 'Bs.'; 
		$comisbs = $comis; $comisdolares = 0.00; $comiscripto = 0.00; 
		break;
}
$tipotransaccion = '03';
$tipotrxcomision = '53';
$tasadolarbs = 1.00;
$tasadolarcripto = 1.00;
$idproveedor = (isset($_POST['idproveedor'])) ? $_POST['idproveedor'] : 0 ;
$comercio = (isset($_POST['comercio'])) ? $_POST['comercio'] : $idproveedor ;
$tipopago = (isset($_POST['tipopago'])) ? $_POST['tipopago'] : 'efectivo' ;
$menu = $_POST["menu"];
$origen = (isset($_POST['origen'])) ? $_POST['origen'] : '' ;
$referencia = (isset($_POST['referencia'])) ? $_POST['referencia'] : '' ;
if ($tipopago=="cashflag") {
	$origen = 'cashflag' ;
	$referencia = $cardx ;
}

// Buscar el nombre del proveedor para generar la giftcard
$query = "select * from proveedores where id=".$idproveedor;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $nombreproveedor = $row["nombre"];
} else {
    $nombreproveedor = '';
}

// Buscar el id del socio (si existe)
$query = "select * from socios where id='".trim($idsocio)."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $idsocio = $row["id"];
	$remitente = trim($row["nombres"])." ".trim($row["apellidos"]);
} else {
    $idsocio = 0;
	$remitente = "";
}

// Generar numero de tarjeta partiendo de los datos enviados
$cardnew = generagiftcard($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);
/*
   El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:
   AABBCCDDEEFFGGGG -> AABB CCDD EEFF GGGG
   0123456789012345
	            xxxx
*/

$dcg = intval(substr($cardnew,12,4));

// Buscaar datos de la tarjeta
$tarjetaexiste = false;
$query = "select * from giftcards where nombres='".trim($nombres)."' and apellidos='".trim($apellidos)."' and telefono='".trim($telefono)."' and email='".trim($email)."' and id_proveedor=".$idproveedor." and moneda='".trim($moneda)."'";
// $query = "select * from giftcards where card='".trim($card)."'";
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
	// Busca la tarjeta existente
	$tarjetaexiste = true;
   $card = $row["card"];
   $saldoant = $row["saldo"];
   $saldo = ($tipopago == 'efectivo' || $tipopago == 'cashflag' || $tipopago == 'tarjeta') ? $row["saldo"]+$monto : $row["saldo"] ;
} else {
	// Generar la tarjeta
	$tarjetaexiste = false;
	$card = $cardnew;
   $saldoant = 0.00;
   $saldo = ($tipopago == 'efectivo' || $tipopago == 'cashflag' || $tipopago == 'tarjeta') ? $monto : 0.00 ;
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
$status = ( $tipopago == 'efectivo' || $tipopago == 'cashflag' || $tipopago == 'tarjeta') ? 'Lista para usar' : 'Por confirmar pago' ;
$fechaconfirmacion = ( $tipopago == 'efectivo' || $tipopago == 'cashflag' || $tipopago == 'tarjeta') ? $fecha : '0000-00-00' ;

// Encripta la giftcard
$hash = hash("sha256",$card.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);
$aux  =  rand(10000, 99999);
$pwd  = hash("sha256",$card.$aux);

$query = "INSERT INTO giftcards_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio, menu, formapago) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."',".$comercio.", '".$menu."', '".$tipopago."')";
if ($result = mysqli_query($link,$query)) {
	if ($tipopago=="cashflag") {
		$querz = "select * from prepago where card='".$cardx."'";
		$resulz = mysqli_query($link,$querz);
		if ($roz = mysqli_fetch_array($resulz)) {
			$provx    = $roz["id_proveedor"];
			$saldx    = $roz["saldo"]-$montobruto;
		}
		$tipotrx = '51';
		$querz = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio, menu, formapago) VALUES (".$idsocio.",".$provx.",'".$fecha."','".$tipotrx."','".$moneda."',".$brutobs.",".$brutodolares.",".$brutocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','Confirmada','".$cardx."',".$provx.", '".$menu."', '".$tipopago."')";
		if ($resulz = mysqli_query($link,$querz)) {
			$querz = "UPDATE prepago SET saldo=".$saldx." WHERE card='".trim($cardx)."'";
			$resulz = mysqli_query($link,$querz);
		}
	}
	if ($tarjetaexiste) {
		$query = "UPDATE giftcards SET saldo=".$saldo." WHERE card='".trim($card)."'";
		if ($result = mysqli_query($link,$query)) {
			// Punto de venta
			$tipo2 = '53'; 
			// Insertar transacción para confirmar
			$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision,';
			$quer2 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
			$quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'","'.$moneda.'",';
			$quer2 .= $monto.', '.$comis.',"giftcard","'.$card.'","'.$referencia.'","'.$status.'","","",0,"")';
			$resul2 = mysqli_query($link,$quer2);

			// Comision por pagar (del punto de venta a Cash-Flag)
			$tipo3 = '54'; 
			// Insertar transacción para confirmar
			$quer3  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision, ';
			$quer3 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
			$quer3 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo3.'","'.$moneda.'",';
			$quer3 .= $comis.', 0,"giftcard","'.$card.'","'.$referencia.'","Por enterar","","",0,"")';
			$resul3 = mysqli_query($link,$quer3);
			// 
			//       Registro de la comisión
			// 
			$query = "INSERT INTO giftcards_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio, menu, formapago) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotrxcomision."','".$moneda."',".$comisbs.",".$comisdolares.",".$comiscripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','Confirmada','".$card."',".$comercio.", '".$menu."', '".$tipopago."')";
			$result = mysqli_query($link,$query);

			$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);
			if ($tipopago == 'efectivo' || $tipopago == 'cashflag' || $tipopago == 'tarjeta') {
				$mensaje = '["Tarjeta de regalo generada exitosamente:","",';
				$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
				$mensaje .= '"Número de tarjeta: '.$txtcard.'",';
				$mensaje .= '"Su nuevo saldo es de: ';
				switch ($moneda) {
					case 'bs':     $mensaje .= "Bs. ";     break;
					case 'dolar':  $mensaje .= "US$ ";     break;
					case 'ae': $mensaje .= "AE "; break;
				}
				$mensaje .= number_format($saldo,2,',','.').'","",';
				$mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
				$mensaje .= '"Te quedan ';
				$mensaje .= $diferencia->format('%a').' días';
				$mensaje .= ' para usarla."]';
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
				$mensaje .= number_format($saldoant+$monto,2,',','.').'","",';
				$mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
				$mensaje .= '"Te quedan ';
				$mensaje .= $diferencia->format('%a').' días';
				$mensaje .= ' para usarla."]';
			}
		    $respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"card":"'.$card.'","hash":"'.$hash.'"}';	
		} else {
		    $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico [4]","card":"'.$card.'","hash":"'.$hash.'"}';	
		}
	} else {
		$quer0 = "INSERT INTO cards (card, tipo) VALUES ('".$card."','giftcard')";
		if ($resul0 = mysqli_query($link,$quer0)) {
			$query = "INSERT INTO giftcards (card, remitente, nombres, apellidos, telefono, email, saldo, saldoentransito, moneda, fechacompra, fechavencimiento, validez, status, id_socio, id_proveedor, hash, tipopago, origen, referencia, premium, pwd) VALUES ('".$card."','".$remitente."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",0,'".$moneda."','".$fecha."','".$fechavencimiento."','".$validez."','".$status."',".$idsocio.",".$idproveedor.",'".$hash."','".$tipopago."','".$origen."','".$referencia."',1, '".$pwd."')";
			if ($result = mysqli_query($link,$query)) {
				// Punto de venta
				$tipo2 = '53'; 
				// Insertar transacción para confirmar
				$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision,';
				$quer2 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
				$quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'","'.$moneda.'",';
				$quer2 .= $monto.', '.$comis.',"giftcard","'.$card.'","'.$referencia.'","'.$status.'","","",0,"")';
				$resul2 = mysqli_query($link,$quer2);

				// Comision por pagar (del punto de venta a Cash-Flag)
				$tipo3 = '54'; 
				// Insertar transacción para confirmar
				$quer3  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, moneda, monto, comision, ';
				$quer3 .= 'instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
				$quer3 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo3.'","'.$moneda.'",';
				$quer3 .= $comis.', 0,"giftcard","'.$card.'","'.$referencia.'","Por enterar","","",0,"")';
				$resul3 = mysqli_query($link,$quer3);
				// 
				//       Registro de la comisión
				// 
				$query = "INSERT INTO giftcards_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio, menu, formapago) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotrxcomision."','".$moneda."',".$comisbs.",".$comisdolares.",".$comiscripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','Confirmada','".$card."',".$comercio.", '".$menu."', '".$tipopago."')";
				$result = mysqli_query($link,$query);

				$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);
				if ($tipopago == 'efectivo' || $tipopago == 'cashflag' || $tipopago == 'tarjeta') {
					$mensaje = '["Tarjeta de regalo generada exitosamente:","",';
					$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
					$mensaje .= '"Número de tarjeta: '.$txtcard.'",';
					$mensaje .= '"Con un saldo de: ';
					switch ($moneda) {
						case 'bs':     $mensaje .= "Bs. ";     break;
						case 'dolar':  $mensaje .= "US$ ";     break;
						case 'ae': $mensaje .= "AE "; break;
					}
					$mensaje .= number_format($monto,2,',','.').'","",';
					$mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
					$mensaje .= '"Te quedan ';
					$mensaje .= $diferencia->format('%a').' días';
					$mensaje .= ' para usarla."]';
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
					$mensaje .= number_format($saldoant+$monto,2,',','.').'"]';
				}
				$querx = 'UPDATE _parametros SET dcg='.$dcg;
				$resulx = mysqli_query($link,$querx);
				correogiftcard($card, $nombres, $remitente, $email, $txtemail, $nombreproveedor, $monto, $simbolo, $validez, $aux, $urlapp);
			   $respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"card":"'.$card.'","hash":"'.$hash.'"}';	
			} else {
			   $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico [1]","card":"'.$card.'","hash":"'.$hash.'"}';	
			}
		} else {
		   $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico [2]","card":"'.$card.'","hash":"'.$hash.'"}';	
		}
	}
} else {
   $respuesta = '{"exito":"NO","mensaje":"La transacción no pudo completarse por favor comuniquese con soporte técnico [3]","card":"'.$card.'","hash":"'.$hash.'"}';	
}
echo $respuesta;


function correogiftcard($card, $nombres, $remitente, $correo, $mensaje, $comercio, $monto, $simbolo, $validez, $pwd, $ruta) {
	$mensaje = 
	'<!DOCTYPE html>
	<html>
	<head>
	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <title>Tarjeta de regalo</title>
	  <link rel="stylesheet" href="">
	  <script type="text/javascript" src="'.$ruta.'/js/funciones.js"></script>
	  <script type="text/javascript" src="'.$ruta.'/card/classes.js"></script>
	  <script type="text/javascript" src="'.$ruta.'/lib/html2canvas.min.js"></script>
     <script>
		var xmlhttp = new XMLHttpRequest();
	   xmlhttp.onreadystatechange = function() {
		  if (this.readyState == 4 && this.status == 200) {
			  respuesta = JSON.parse(this.responseText);

			  card1 = new Card(
				  "'.$card.'",
				  respuesta.logocard,
				  respuesta.tipo,
				  "'.$card.'",
				  respuesta.nombres,
				  respuesta.vencimiento,
				  respuesta.qr
			  );

			  saldo = respuesta.saldo;
			  idproveedor = respuesta.idproveedor;

			  card1.dibuja("tarjetero");

			  html2canvas(document.getElementById("tarjetero")) // Llamar a html2canvas y pasarle el elemento
			  .then(function(canvas) {
				 document.getElementById("card_img").appendChild(canvas); // Lo agregamos como hijo del div
				 document.getElementById("tarjetero").style.display = "none";
			  });
			}
	   };
	   xmlhttp.open("GET", "'.$ruta.'/php/buscatarjeta.php?t="+'.$card.', true);
	   xmlhttp.send();
	 </script>
	</head>
	<body>
	 <div>
		<p><img src="'.$ruta.'/img/logoclub.png" width="120" height="auto" /></p>
		<p>
		  <span style="font-size: 150%; color: blue;">
			 <b>¡Felicidades '.$nombres.', has recibido un regalo de '.$remitente.'!</b>
		  </span>
		</p>

		<p style="text-align: justify;"><u>Mensaje:</u><br/><b><i>'.$mensaje.'</i></b></p>

		<div id="tarjetero"></div>			
		<div id="card_img"></div>			

		<p>Has recibido una tarjeta de regalo para consumir en <b>'.$comercio.'</b> con un saldo prepagado de <b>'.$monto.' '.$simbolo.'</b> para ser usada hasta <b>'.$validez.'</b></p>

		<p>Para usar esta tarjeta debes ingresar en <a href="'.$ruta.'/giftcards/tarjeta.html", target="_blank">este enlace</a> e introducir el número de tarjeta <span style="font-size: 110%;"><b>'.$card.'</b></span> y la siguiente contraseña: <span style="font-size: 110%;"><b>'.$pwd.'</b></span></p>

		<br/>

		<p><i>¡Disfruta de tu regalo y gana con Cash-Flag!</i></p>
 
		<p style="font-size: 80%;"><b>Si tienes alguna pregunta o comentario, contáctanos a través de <a href="mailto:info@cash-flag.com">este enlace</a></b></p>
	 </div>
	 </body>
	</html>';
 
	$asunto = '¡Felicidades '.$nombres.', has recibido un regalo de '.$remitente.'!';
	
	$cabeceras = 'Content-type: text/html; charset=utf-8'."\r\n";
	$cabeceras .= 'From: Cash-Flag <info@cash-flag.com>';
	
	$a = fopen('log.html','w+');
	fwrite($a,$asunto);
	fwrite($a,'-');
	fwrite($a,$mensaje);
	
	// mail($correo,$asunto,$mensaje,$cabeceras);
}





/*
// Buscar el nombre del proveedor para generar la giftcard
$query = "select * from proveedores where id=".$idproveedor;
$result = mysqli_query($link, $query);
if ($row = mysqli_fetch_array($result)) {
    $nombreproveedor = $row["nombre"];
} else {
    $nombreproveedor = '';
}

// Generar la giftcard
$card = generagiftcard($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link);

// Fecha de compra
$fecha = date('Y-m-d');

// Fecha de vecnimiento (1 año)
$fechavencimiento = strtotime('+1 year', strtotime ($fecha));
$fechavencimiento = date('Y-m-d', $fechavencimiento);

$datetime1 = date_create($fecha);
$datetime2 = date_create($fechavencimiento);
$diferencia = date_diff($datetime1, $datetime2);

// Status, si es en efectivo queda lista para usar de inmediato, si no queda por conciliar
$status = ( $tipopago == 'efectivo') ? 'Lista para usar' : 'Por confirmar pago' ;

// Encripta la giftcard
$hash = hash("sha256",$card.$remitente.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

$query = "INSERT INTO giftcards (card,remitente, nombres, apellidos, telefono, email, saldo, moneda, fechacompra, fechavencimiento, status, id_proveedor, hash, tipopago, origen, referencia) VALUES ('".$card."','".$remitente."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",'".$moneda."','".$fecha."','".$fechavencimiento."','".$status."',".$idproveedor.",'".$hash."','".$tipopago."','".$origen."','".$referencia."')";
if ($result = mysqli_query($link,$query)) {
	$txtcard = substr($card,0,4).'-'.substr($card,4,4).'-'.substr($card,8,4).'-'.substr($card,12,4);
	$mensaje = '["Tarjeta de regalo generada exitosamente:","",';
	$mensaje .= '"A nombre de: '.trim($nombres).' '.trim($apellidos).'",';
	$mensaje .= '"Número de tarjeta: '.$txtcard.'",';
	$mensaje .= '"Con un sado de: ';
	switch ($moneda) {
		case 'bs':     $mensaje .= "Bs. ";     break;
		case 'dolar':  $mensaje .= "US$ ";     break;
		case 'ae': $mensaje .= "AE "; break;
	}
	$mensaje .= number_format($monto,2,',','.').'",';
	$mensaje .= '"Fecha de vencimiento: '.substr($fechavencimiento,8,2)."/".substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4).'",';
	$mensaje .= '"Te quedan ';
	$mensaje .= $diferencia->format('%a').' días';
	$mensaje .= ' para usarla."]';

    $respuesta = '{"exito":"SI","mensaje":'.$mensaje.',"card":"'.$card.'","hash":"'.$hash.'"}';	
} else {
    $respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo generarse por favor comuniquese con soporte técnico","card":"'.$card.'","hash":"'.$hash.'"}';	
}
echo $respuesta;
?>
*/
?>
