<?php
include_once("./cash-flag-email.php");
include_once("../lib/phpqrcode/qrlib.php");

function mensajes($archivojson,$texto){
    $parametros = json_decode(file_get_contents($archivojson),true);
    $mensaje = '[';
    for ($i = 0; $i < count($parametros["mensajes"][$texto]); $i++) {
        $mensaje .= '"' . $parametros["mensajes"][$texto][$i] . '"';
        if (count($parametros["mensajes"][$texto]) > 1 && $i + 1 < count($parametros["mensajes"][$texto])) {
            $mensaje .= ',';
        }
    }
    $mensaje .= ']';
    return $mensaje;
}

function asignacodigo($ultcupon){
    $valores = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $a = strlen($valores)-1;
    $base = 36;
    $codigo = '';
    $arriba = 1;
    $newcodigo = '';
    $numero = $ultcupon;
    // echo $numero.'<br>';
    for ($i=strlen($ultcupon)-1 ; $i>=0 ; $i--) { 
        $pos = strpos($valores, substr($numero,$i,1));
        if ($arriba==1) {
            if ($pos==$a) {
                $codigo = substr($valores,0,1);
            } else {
                $codigo = substr($valores,$pos+1,1);
                $arriba = 0;
            }
        } else {
            $codigo = substr($numero,$i,1);
        }
        $newcodigo = $codigo.$newcodigo;
    }
    // switch (strlen($newcodigo)) {
    //  case '1':
    //      $newcodigo = '0000'.$newcodigo;
    //      break;
    //  case '2':
    //      $newcodigo = '000'.$newcodigo;
    //      break;
    //  case '3':
    //      $newcodigo = '00'.$newcodigo;
    //      break;
    //  case '4':
    //      $newcodigo = '0'.$newcodigo;
    //      break;
    // }
    for ($i=0 ; $i< strlen($newcodigo); $i++) { 
        // echo substr($newcodigo,$i,1).'<br>';
    }

    return $newcodigo;
}

function asignacodigolargo($ultcupon){
    $newcodigo = $ultcupon;

    $cuponlargo = substr($newcodigo,0,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["email"],-1)));
    $cuponlargo .= substr($newcodigo,2,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["nombres"],-1)));
    $cuponlargo .= substr($newcodigo,4,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["apellidos"],-1)));
    $cuponlargo .= substr($newcodigo,6,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($_POST["telefono"],-1)));
    $cuponlargo .= substr($newcodigo,8,2);

    return $cuponlargo;
}

function asignacodigolargo2($ultcupon,$email,$nombres,$apellidos,$telefono){
    $newcodigo = $ultcupon;

    $cuponlargo = substr($newcodigo,0,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($email,-1)));
    $cuponlargo .= substr($newcodigo,2,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($nombres,-1)));
    $cuponlargo .= substr($newcodigo,4,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($apellidos,-1)));
    $cuponlargo .= substr($newcodigo,6,2);
    $cuponlargo .= codigocaracter(strtoupper(substr($telefono,-1)));
    $cuponlargo .= substr($newcodigo,8,2);

    return $cuponlargo;
}

function codigocaracter($valor){
    $llaves = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $codigos =  '111213141A1B1C1D212223242A2B2C2D3132';
    $codigos .= '33343A3B3C3D414243444A4B4C4DA1A2A3A4';

    $posicion = strpos($llaves, $valor);
    $pos2 = $posicion*2;
    $newvalor = substr($codigos,$pos2,2);

    return $newvalor;
}

function generagiftcard($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link){
    // Busca el próximo número correlativo (único)
    $query = "select dcg from _parametros";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
        $numgiftcard = $row["dcg"];
    } else {
        $numgiftcard = 0;
    }
    $numgiftcard++;

    // Si el número del correlativo supera los cuatro dígitos se trunca a cuatro
    if ($numgiftcard > 9999) { $numgiftcard = 1; }

    // Rellena con ceros los caracteres faltantes hasta 4
    if ($numgiftcard < 10) {
        $txtgiftcard = "000".trim($numgiftcard);
    } elseif ($numgiftcard < 100) {
        $txtgiftcard = "00".trim($numgiftcard);
    } elseif ($numgiftcard < 1000) {
        $txtgiftcard = "0".trim($numgiftcard);
    } else {
        $txtgiftcard = trim($numgiftcard);
    }
    /*
        El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:

        AABBCCDDEEFFGGGG -> AABB CCDD EEFF GGGG

        AA   = Código de dos dígitos correspondiente a la primera letra del nombre
        BB   = Código de dos dígitos correspondiente a la primera letra del apellido
        CC   = Código de dos dígitos correspondiente al último dígito del teléfono
        DD   = Código de dos dígitos correspondiente a la primera letra del email
        EE   = Código de dos dígitos correspondiente a la primera letra del nombre del proveedor
        FF   = Código de dos dígitos correspondiente a la primera letra de la moneda
        GGGG = Número correlativo de 4 dígitos
    */
    $card = "";
    $card .= generacodigo(substr($nombres,0,1),$link);
    $card .= generacodigo(substr($apellidos,0,1),$link);
    $card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
    $card .= generacodigo(substr($email,0,1),$link);
    $card .= generacodigo(substr($nombreproveedor,0,1),$link);
    $card .= generacodigo(substr($moneda,0,1),$link);
    $card .= substr($txtgiftcard,0,1);
    $card .= substr($txtgiftcard,1,1);
    $card .= substr($txtgiftcard,2,1);
    $card .= substr($txtgiftcard,3,1);

    return $card;
}

function generaprepago($nombres,$apellidos,$telefono,$email,$nombreproveedor,$moneda,$link){
    // Busca el próximo número correlativo (único)
    $query = "select dcp from _parametros";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
        $numprepago = $row["dcp"];
    } else {
        $numprepago = 0;
    }
    $numprepago++;

    // Si el número del correlativo supera los cuatro dígitos se trunca a cuatro
    if ($numprepago > 9999) { $numprepago = 1; }

    // Rellena con ceros los caracteres faltantes hasta 4
    if ($numprepago < 10) {
        $txtprepago = "000".trim($numprepago);
    } elseif ($numprepago < 100) {
        $txtprepago = "00".trim($numprepago);
    } elseif ($numprepago < 1000) {
        $txtprepago = "0".trim($numprepago);
    } else {
        $txtprepago = trim($numprepago);
    }
    /*
        El número de la tarjeta está compuesto por 10 caracteres en el orden que sigue:

        AAGBBGCCDDGEEGFF -> AAGB BGCC DDGE EGFF

        AA = Código de dos dígitos correspondiente a la primera letra del nombre
        G  = Primer dígito del número correlativo de 4 dígitos
        BB = Código de dos dígitos correspondiente a la primera letra del apellido
        G  = Segundo dígito del número correlativo de 4 dígitos
        CC = Código de dos dígitos correspondiente al último dígito del teléfono
        DD = Código de dos dígitos correspondiente a la primera letra del email
        G  = Tercer dígito del número correlativo de 4 dígitos
        EE = Código de dos dígitos correspondiente a la primera letra del nombre del proveedor
        G  = Cuarto dígito del número correlativo de 4 dígitos
        FF = Código de dos dígitos correspondiente a la primera letra de la moneda
    */
    $card = "";
    $card .= generacodigo(substr($nombres,0,1),$link);
    $card .= substr($txtprepago,0,1);
    $card .= generacodigo(substr($apellidos,0,1),$link);
    $card .= substr($txtprepago,1,1);
    $card .= generacodigo(substr($telefono,strlen($telefono)-1,1),$link);
    $card .= generacodigo(substr($email,0,1),$link);
    $card .= substr($txtprepago,2,1);
    $card .= generacodigo(substr($nombreproveedor,0,1),$link);
    $card .= substr($txtprepago,3,1);
    $card .= generacodigo(substr($moneda,0,1),$link);

    return $card;
}

function generacodigo($letra,$link){
    $query = "select codigo from _codigo where valor='".$letra."'";
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        $codigo = $row["codigo"];
    } else {
        $query = "select codigo from _codigo where valor='?'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $codigo = $row["codigo"];
    }
    return $codigo;
}

// Generar el próximo número de transacción en el pdv
function generatransaccion_pdv($link, $database){
    // Busca el próximo número correlativo (único)
    $query = "select count(id) as increment from pdv_transacciones";
    $result = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($result)) {
            $numero = $row["increment"];
    } else {
            $numero = 0;
    }
    return $numero;
}

function generatarjetaAE($post, $link){
    // Asignación de variables
    $nombres   = $post['nombres'];
    $apellidos = $post['apellidos'];
    $telefono  = $post['telefono'];
    $email     = $post['email'];

    $moneda = "ae" ;
    $monto = 0.00 ;

    $montobs = 0.00;
    $montodolares = 0.00;
    $montocripto = $monto; 

    $tipotransaccion = '01';
    $tasadolarbs = 1.00;
    $tasadolarcripto = 1.00;
    $idproveedor = 3;  // Cash-Flag
    $tipopago = 'efectivo' ;
    $origen = 'afiliacion' ;
    $referencia = 'nuevosocio' ;

    $nombreproveedor = "Cash-Flag";

    // Buscar el id del socio (si existe)
    $query = "select * from socios where email='".trim($post['email'])."'";
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

    $card = $cardnew;
    $saldoant = 0.00;
    $saldo = ($tipopago == 'efectivo') ? $monto : 0.00 ;

    // Fecha de compra
    $fecha = date('Y-m-d');

    // Fecha de vecnimiento (1 año)
    $fechavencimiento = strtotime('+1 year', strtotime ($fecha));
    $fechavencimiento = date('Y-m-d', $fechavencimiento);

    $datetime1 = date_create($fecha);
    $datetime2 = date_create($fechavencimiento);
    $diferencia = date_diff($datetime1, $datetime2);

    $validez = substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4);

    $status = 'Lista para usar';
    $fechaconfirmacion = $fecha;
    
    // Encripta la card
    $hash = hash("sha256",$card.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

    $query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."',".$idproveedor.")";
    if ($result = mysqli_query($link,$query)) {
		$quer0 = "INSERT INTO cards (card, tipo) VALUES ('".$card."','prepago')";
		if ($resul0 = mysqli_query($link,$quer0)) {
			$query = "INSERT INTO prepago (card, nombres, apellidos, telefono, email, saldo, saldoentransito, moneda, fechacompra, fechavencimiento, validez, status, id_socio, id_proveedor, hash, premium) VALUES ('".$card."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",0.00,'".$moneda."','".$fecha."','".$fechavencimiento."','".$validez."','".$status."',".$idsocio.",".$idproveedor.",'".$hash."',1)";
			if ($result = mysqli_query($link,$query)) {
				// Punto de venta
				$tipo2 = '51'; 
				// Insertar transacción confirmada
				$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, ';
				$quer2 .= 'moneda, monto, instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
                $quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'", ';
                $quer2 .= '"'.$moneda.'",'.$monto.',"prepago","'.$card.'","'.$referencia.'","'.$status.'", ';
				$quer2 .= '"'.$origen.'","",0,"")';
				$resul2 = mysqli_query($link,$quer2);
				$querx = 'UPDATE _parametros SET dcp='.$dcp;
				$resulx = mysqli_query($link,$querx);
            }
        }
    }
}

function generatarjetadolar($post, $link){
    // Asignación de variables
    $nombres   = $post['nombres'];
    $apellidos = $post['apellidos'];
    $telefono  = $post['telefono'];
    $email     = $post['email'];

    $moneda = "dolar" ;
    $monto = 0.00 ;

    $montobs = 0.00;
    $montodolares = $monto;
    $montocripto = 0.00;

    $tipotransaccion = '01';
    $tasadolarbs = 1.00;
    $tasadolarcripto = 1.00;
    $idproveedor = 3;  // Cash-Flag
    $tipopago = 'efectivo' ;
    $origen = 'afiliacion' ;
    $referencia = 'nuevosocio' ;

    $nombreproveedor = "Cash-Flag";

    // Buscar el id del socio (si existe)
    $query = "select * from socios where email='".trim($post['email'])."'";
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

    $card = $cardnew;
    $saldoant = 0.00;
    $saldo = ($tipopago == 'efectivo') ? $monto : 0.00 ;

    // Fecha de compra
    $fecha = date('Y-m-d');

    // Fecha de vecnimiento (1 año)
    $fechavencimiento = strtotime('+1 year', strtotime ($fecha));
    $fechavencimiento = date('Y-m-d', $fechavencimiento);

    $datetime1 = date_create($fecha);
    $datetime2 = date_create($fechavencimiento);
    $diferencia = date_diff($datetime1, $datetime2);

    $validez = substr($fechavencimiento,5,2)."/".substr($fechavencimiento,0,4);

    $status = 'Lista para usar';
    $fechaconfirmacion = $fecha;
    
    // Encripta la card
    $hash = hash("sha256",$card.$nombres.$apellidos.$telefono.$email.$monto.$idproveedor.$moneda);

    $query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio) VALUES (".$idsocio.",".$idproveedor.",'".$fecha."','".$tipotransaccion."','".$moneda."',".$montobs.",".$montodolares.",".$montocripto.",".$tasadolarbs.",".$tasadolarcripto.",'".$referencia."','".$origen."','".$status."','".$card."',".$idproveedor.")";
    if ($result = mysqli_query($link,$query)) {
		$quer0 = "INSERT INTO cards (card, tipo) VALUES ('".$card."','prepago')";
		if ($resul0 = mysqli_query($link,$quer0)) {
			$query = "INSERT INTO prepago (card, nombres, apellidos, telefono, email, saldo, saldoentransito, moneda, fechacompra, fechavencimiento, validez, status, id_socio, id_proveedor, hash, premium) VALUES ('".$card."','".$nombres."','".$apellidos."','".$telefono."','".$email."',".$monto.",0.00,'".$moneda."','".$fecha."','".$fechavencimiento."','".$validez."','".$status."',".$idsocio.",".$idproveedor.",'".$hash."',1)";
			if ($result = mysqli_query($link,$query)) {
				// Punto de venta
				$tipo2 = '51'; 
				// Insertar transacción confirmada
				$quer2  = 'INSERT INTO pdv_transacciones (fecha, fechaconfirmacion, id_proveedor, id_socio, tipo, ';
				$quer2 .= 'moneda, monto, instrumento, id_instrumento, documento, status, origen, token, pin, hashpin) ';
                $quer2 .= 'VALUES ("'.$fecha.'","'.$fechaconfirmacion.'",'.$idproveedor.','.$idsocio.',"'.$tipo2.'", ';
                $quer2 .= '"'.$moneda.'",'.$monto.',"prepago","'.$card.'","'.$referencia.'","'.$status.'", ';
				$quer2 .= '"'.$origen.'","",0,"")';
				$resul2 = mysqli_query($link,$quer2);
				$querx = 'UPDATE _parametros SET dcp='.$dcp;
				$resulx = mysqli_query($link,$querx);
            }
        }
    }
}

function enviasms($telefono,$mensaje){
    //parámetros de envío
    $usuario="sgcvzla@gmail.com";
    $clave="Ma24032008.";

    $parametros="usuario=$usuario&clave=$clave&texto=$mensaje&telefonos=$telefono";

    $url = "http://www.sistema.massivamovil.com/webservices/SendSms";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST,true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
    $response = curl_exec($ch);

    curl_close($ch);
    // echo $response;
}
/*
function mensajebienvenida($reg) {
    $telefono = $reg["telefono"];
    
    $servidor = $_SERVER['HTTP_HOST'];
    *//*
    $mensaje = utf8_decode('Gracias por formar parte de Cash-Flag, completa el registro y recibe mas recompensas, ingresa en https://'.$servidor.'/registro/index.html?id='.$reg["idproveedor"].'-'.$reg["id"]);
    *//*
    $mensaje = utf8_decode('Gracias por formar parte de Cash-Flag, completa el registro y recibe mas recompensas, ingresa en https://'.$servidor.'/registro/id/'.$reg["idproveedor"].'-'.$reg["id"]);

    $respuesta1 = enviasms($telefono,$mensaje);
    // echo $mensaje;
    // echo $respuesta1;
}
*/

function mensajebienvenida($reg) {
	$correo = $reg["email"];

	$mensaje = 'Hola '.trim($reg["nombres"]).',<br/><br/>';
	$mensaje .= '¡Gracias por querer formar parte de nuestra comunidad!<br/><br/>';
    
    $servidor = $_SERVER['HTTP_HOST'];

	$mensaje .= 'Queremos conocerte un poco más y ofrecerte premios, promociones o productos/servicios especialmente diseñados para ti, pero necesitamos que nos brindes alguna información que nos ayudará a prestarte un mejor servicio, innovar en nuestros premios y hacerte la vida mucho más fácil y gratificante, además desde ya comenzaras a ganar, luego de completar <a href="https://'.$servidor.'/registro/index.html?idp='.$reg["idproveedor"].'&ids='.$reg["id"].'">este formulario</a> recibirás un premio de bienvenida.<br/><br/>';

	$mensaje .= '<i>El próximo paso es crear tu contraseña para acceder a tu menú de socio, puedes hacerlo desde tu computadora o teléfono celular ingresando en <a href="https://'.$servidor.'/socio">este enlace</a></i><br/><br/>';

	$mensaje .= 'Puedes generar un enlace directo copiando <a href="https://'.$servidor.'">este link</a><br/><br/>';

	$mensaje .= '<b>Te garantizamos que tu información será guardada celosamente y nunca será compartida con ningún tercero sin tu consentimiento y te aseguramos que siempre cumpliremos con las Leyes vigentes en lo relacionado al tratamiento de tus datos personales.</b><br/><br/>';

	$mensaje .= 'Nuestra comunidad está en permanente evolución y tú como un miembro muy importante puedes aportarnos ideas o sugerencias que la harán crecer, ten la certeza que serás escuchado(a) y tus sugerencias o comentarios serán repondidos en un lapso de tiempo razonable con mucho entusiasmo por resolver tus inquietudes, para nosotros será un placer atenderte por medio del email: <a href="mailto:info@cash-flag.com">info@cash-flag.com</a>.<br/><br/>';

	$mensaje .= 'Bienvenido!!!'.'<br/><br/>';
	$mensaje .= 'Cash-Flag'.'<br/><br/>';

	$mensaje .= '<b>Nota:</b> Esta cuenta no es monitoreada, por favor no respondas este email, si deseas comunicarte con tu club escribe a: <b><a href="mailto:info@cash-flag.com">info@cash-flag.com</a></b>'.'<br/><br/>';

	$asunto = trim($reg["nombres"]).', Bienvenido a Cash-Flag, tu comunidad de beneficios!!!';
	$cabeceras = 'Content-type: text/html;';

    // $cabeceras = 'Content-type: text/html'."\r\n";
    // $cabeceras .= 'From: Cash-Flag <info@cash-flag.com>';
    
    // if ($_SERVER["HTTP_HOST"]!='localhost') {
		// $ret = mail($correo,$asunto,$mensaje,$cabeceras);
        cashflagemail($correo, trim($reg["nombres"]), $asunto, $mensaje);
            // }

    $a = fopen('log.html','w+');
    fwrite($a,$asunto);
    fwrite($a,'-');
    fwrite($a,$mensaje);
    // if($ret) { fwrite($a,'-true'); } else { fwrite($a,'-false'); }
}

function recargapremiumdolar($link,$idsocio,$email,$telefono,$nombres,$apellidos) {
	$query = "select * from prepago where nombres='".trim($nombres)."' and apellidos='".trim($apellidos)."' and telefono='".trim($telefono)."' and email='".trim($email)."' and id_proveedor=3 and moneda='dolar'";
	$result = mysqli_query($link, $query);
	if ($row = mysqli_fetch_array($result)) {
		$tarjetaexiste = true;
		$card = $row["card"];
		$saldoant = $row["saldo"];

		$fecha = date("Y-m-d");
		$tipotransaccion = '01';
		$tasadolarbs = 1.00;
		$tasadolarcripto = 1.00;
		
		$query = "INSERT INTO prepago_transacciones (idsocio, idproveedor, fecha, tipotransaccion, tipomoneda, montobs, montodolares, montocripto, tasadolarbs, tasadolarcripto, documento, origen, status, card, comercio) VALUES (".$idsocio.",3,'".$fecha."','".$tipotransaccion."','dolar',0 ,1 ,0 ,".$tasadolarbs.",".$tasadolarcripto.",'0000','registro','Confirmada','".$card."',3)";
		if ($result = mysqli_query($link,$query)) {
			$saldo = $saldoant + 1;
			$query = "UPDATE prepago SET saldo=".$saldo." WHERE card='".trim($card)."'";
			if ($result = mysqli_query($link,$query)) {
				$respuesta = '{"exito":"SI","mensaje":"Transacción exitosa."}';	
			} else {
				$respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';
			}
		} else {
			$respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';
		}
	} else {
		$respuesta = '{"exito":"NO","mensaje":"La tarjeta no pudo recargarse por favor comuniquese con soporte técnico"}';
	}
}

function cupondebienvenida($link,$socio,$email,$telefono,$nombres,$apellidos,$archivojson,$idproveedor,$idsocio,$idcomercio) {
	// Buscar datos de proveedor
	$query = "select * from proveedores where id=".$idcomercio;
	// $query = "select * from proveedores where id=1";
	$result = mysqli_query($link, $query);
	if ($row = mysqli_fetch_array($result)) {
		$nombreproveedor=$row["nombre"];
	}

	// Buscar premio activo
	$query = "select * from premios where id_proveedor=".$idproveedor." and clasepremio='bienvenida' and activo=1";
	// $query = "select * from premios where id_proveedor=1 and activo=1";
	$result = mysqli_query($link, $query);
	if ($row = mysqli_fetch_array($result)) {
		$id_premio=$row["id"];
		$tipopremio=$row["tipopremio"];
		$montopremio=$row["montopremio"];
		$descpremio=$row["descpremio"];
		$diasvalidez=$row["diasvalidez"];
	}

	// Asignar el número de cupón
	$query = "select max(cupon) as ultcupon from cupones";
	$result = mysqli_query($link, $query);
	if ($row = mysqli_fetch_array($result)) {
		if (strlen($row["ultcupon"])==0) {
			$numcupon = asignacodigo('0000000000');
			$cuponlargo = asignacodigolargo2($numcupon,$email,$nombres,$apellidos,$telefono);
		} else {
			$numcupon = asignacodigo($row["ultcupon"]);
			$cuponlargo = asignacodigolargo2($numcupon,$email,$nombres,$apellidos,$telefono);
		}
	}

	// Verificar si ya existe el cupón, si existe responder, si no, agregar y responder 
	$query = "select * from cupones where id_socio=".$idsocio." and factura='00000'";
	// $query = "select * from cupones where id_proveedor=1 and factura='8888888'";
	$result = mysqli_query($link, $query);
	if ($row = mysqli_fetch_array($result)) {
		$respuesta = '{"exito":"NO","mensaje":'. mensajes($archivojson,"cuponyaregistrado") .',"cupon":"0"}';
	} else {
		$fechacupon = date ('Y-m-d');
		$fechavencimiento = strtotime('+'.$diasvalidez.' days', strtotime ($fechacupon));
		$fechavencimiento = date ('Y-m-d' , $fechavencimiento);
		$fechavencstr = substr($fechavencimiento,8,2).'/'.substr($fechavencimiento,5,2).'/'.substr($fechavencimiento,0,4);

		/*
		Hash para insertar en el blockchain
		-----------------------------------
		El hash se va a armar con los siguientes datos:
		- Cupon
		- Proveedor
		- Socio
		- Tipo premio
		- Monto premio
		- Descripción premio
		- Status cupón
		*/
		$hash = hash("sha256",$numcupon.$idcomercio.$idsocio.$tipopremio.$montopremio.$descpremio."Generado");

		$query = "INSERT INTO cupones (cupon,cuponlargo,id_proveedor,id_comercio,id_socio,status,factura,monto,id_premio,tipopremio,montopremio,descpremio,socio,email,telefono,nombres,apellidos,fechacupon,fechavencimiento,fechacanje,facturacanje,montocanje,hash) VALUES ('".$numcupon."','".$cuponlargo."'," . $idcomercio . "," . $idproveedor. "," . $idsocio . ",'Generado','00000',0,".$id_premio.",'".$tipopremio."',".$montopremio.",'Bienvenida',".$socio.",'" . $email . "','" . $telefono . "','" . $nombres . "','" . $apellidos . "','".$fechacupon."','".$fechavencimiento."','0000-00-00','',0,'".$hash."')";
		// echo $query;

		if ($result = mysqli_query($link, $query)) {

			$correo = $email;

			$mensaje = 'Hola '.trim($nombres).',<br/><br/>';
			$mensaje .= '¡Bienvenido a Cash-Flag, tu comunidad de beneficios!<br/><br/>';

			$mensaje .= 'Queremos darte un obsequio de bienvenida, ';
			$mensaje .= 'la próxima que visites <b>'.trim($nombreproveedor).'</b> podrás reclamar el siguiente premio:'.'<br/><br/>';
			switch ($tipopremio) {
				case 'porcentaje':
					$mensaje .= '<h3 style="text-align:center;"><b>'.number_format($montopremio,2,',','.').'% de descuento sobre el monto total de tu factura.</b></h3>';
					break;
				case 'monto':
					$mensaje .= '<h3 style="text-align:center;"><b>'.number_format($montopremio,2,',','.').' Bs. de descuento en sobre el monto total de tu factura.</b></h3>';
					break;
				case 'producto':
					$mensaje .= '<h3 style="text-align:center;"><b>'.trim($descpremio).'.</b></h3>';
					break;
				default:
					$mensaje .= '<h3 style="text-align:center;"><b>Premio especial sorpresa.</b></h3>';
					break;
			}

			$mensaje .= 'Este premio podrás reclamarlo cualquier día, siempre que sea antes del <b>'.$fechavencstr.'</b>.<br/><br/>';
			$mensaje .= 'Sólo debes presentar este correo electrónico o indicar el siguiente código:'.'<br/>';
			$mensaje .= '<h2 style="text-align:center"><b>'.$cuponlargo.'</b></h2>';

			// codigo de barras
			$mensaje .= '<p style="text-align:center;">';
				$mensaje .= '<img src="https://app.cash-flag.com/php/barcode.php?';
				$mensaje .= 'text='.$cuponlargo;
				$mensaje .= '&size=50';
				$mensaje .= '&orientation=horizontal';
				$mensaje .= '&codetype=Code39';
				$mensaje .= '&print=true';
				$mensaje .= '&sizefactor=1" />';
			$mensaje .= '</p>';

			// código qr
			$mensaje .= '<p style="text-align:center;">Para canjear desde el móvil:</p>';

	//		$dir = 'https://app.cash-flag.com/php/temp/';
	//		if(!file_exists($dir)) mkdir($dir);
			$ruta = 'https://app.cash-flag.com/php/';
			$dir = 'qr/';
			if(!file_exists($dir)) mkdir($dir);

	//		$filename = $dir.'test.png';
			$tamanio = 5;
			$level = 'H';
			$frameSize = 1;
	//		$contenido = $cuponlargo;
	//		$contenido = '{"id_proveedor":'.$_POST['id_proveedor'].',"cupon":"'.$cuponlargo.'"}';
			$contenido = 'https://app.cash-flag.com/canje/canje.html?cJson={"id_proveedor":'.$idcomercio.',"cupon":"'.$cuponlargo.'"}';

	//		QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);
			QRcode::png($contenido,$dir.$numcupon.'.png', $level, $tamanio, $frameSize);
			$mensaje .= '<p style="text-align:center;">';
				$mensaje .= '<img src="'.$ruta.$dir.$numcupon.'.png" height="200" width="200" />';
			$mensaje .= '</p>';
			// Hasta aqui
			$mensaje .= '<p style="text-align:center;">'.$hash.'</p>';

			$mensaje .= '¡Te esperamos!'.'<br/><br/>';

			$mensaje .= 'Atentamente'.'<br/><br/>';
			$mensaje .= 'Cash-Flag'.'<br/><br/>';

			$mensaje .= '<b>Nota:</b> Esta cuenta no es monitoreada, por favor no respondas este email, si deseas comunicarte con tu club escribe a: <b><a href="mailto:info@cash-flag.com">info@cash-flag.com</a></b>'.'<br/><br/>';

			// $mensaje .= $numcupon;

			$asunto = 'Hola '.trim($nombres).', recibe este obsequio de bienvenida a Cash-Flag, tu comunidad de beneficios.';
			// $cabeceras = 'Content-type: text/html;';

			$cabeceras = 'Content-type: text/html'."\r\n";
			$cabeceras .= 'From: Cash-Flag <info@cash-flag.com>';
		  // if ($_SERVER["HTTP_HOST"]!='localhost') {
				// mail($correo,$asunto,$mensaje,$cabeceras);
				cashflagemail($correo, trim($nombres), $asunto, $mensaje);
				// }

			$a = fopen('log.html','w+');
			fwrite($a,$asunto);
			fwrite($a,'-');
			fwrite($a,$mensaje);

			// $respuesta = '{"exito":"SI","mensaje":' . mensajes($archivojson,"exitoregistrocupon") . ',"cupon":"'.$numcupon.'"}';
	//		$respuesta = '{"exito":"SI","mensaje":' . mensajes($archivojson,"exitoregistrocupon") . ',"cupon":"'.$numcupon.'",';
	//		$respuesta .= '"contenido":'.$contenido.',"filename":"'.$filename.'"}';

		// } else {
			// $respuesta = '{"exito":"NO","mensaje":' . mensajes($archivojson,"fallaregistrocupon") . ',"cupon":"0"}';
		}
	}
	// echo $respuesta;
}
?>