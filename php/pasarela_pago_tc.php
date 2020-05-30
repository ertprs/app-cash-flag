<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$fecha = new DateTime();
$timestamp =  $fecha->getTimestamp();

$apikey = hash('sha256','identificador_comercio=47.60244365&tipo_transaccion=0200&monto_transaccion=1000&numero_factura=1&identificacion_tarjetahabiente=7132358&nombre_tarjetahabiente=Luis Rodriguez&numero_tarjeta=5449096912070410&fecha_vencimiento_tarjeta=223&codigo_seguridad_tarjeta=226&numero_lote=1&timeStamp='.$timestamp.'&secret=pL6cG7cX4pY2xN0pQ8kI1vM7vB3hE5tU4rP4iT4kW5aS6sY6mM');

$curl = curl_init();
// "https://apimbu.mercantilbanco.com/mercantil-banco/prod/api-pagos-b2c/REALIZAR_PAGO_CREDITO"
curl_setopt_array($curl, array(
	CURLOPT_URL => "https://apimbu.mercantilbanco.com:9443/mercantil-banco/desarrollo/api-pagos-b2c/REALIZAR_PAGO_CREDITO",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	// CURLOPT_POSTFIELDS => "{\"HEADER_PAGO_REQUEST\":{\"IDENTIFICADOR_UNICO_GLOBAL\":\"1228179914096640\",\"IDENTIFICACION_CANAL\":\"1392758933684224\",\"SIGLA_APLICACION\":\"wiidiuk\",\"IDENTIFICACION_USUARIO\":\"5704598991929344\",\"DIRECCION_IP_CONSUMIDOR\":\"3528552226812144\",\"DIRECCION_IP_CLIENTE\":\"4410156678135548\",\"FECHA_ENVIO_MENSAJE\":\"mupurse\",\"HORA_ENVIO_MENSAJE\":\"kaperagr\",\"ATRIBUTO_PAGINEO\":\"matoikt\",\"CLAVE_BUSQUEDA\":\"unlu\",\"CANTIDAD_REGISTROS\":91275892},\"BODY_PAGO_REQUEST\":{\"IDENTIFICADOR_COMERCIO\":91.64837469,\"TIPO_TRANSACCION\":\"5610209810624930\",\"MONTO_TRANSACCION\":57.87007958,\"NUMERO_FACTURA\":67.97109335,\"IDENTIFICACION_TARJETAHABIENTE\":\"3665033043116032\",\"NOMBRE_TARJETAHABIENTE\":\"fize\",\"NUMERO_TARJETA\":\"nazrajn\",\"FECHA_VENCIMIENTO_TARJETA\":22.62049252,\"CODIGO_SEGURIDAD_TARJETA\":55.11018578,\"NUMERO_LOTE\":\"cebtuce\"}}",
	CURLOPT_POSTFIELDS => 
		'{
		  "HEADER_PAGO_REQUEST": {
		    "IDENTIFICADOR_UNICO_GLOBAL": "5699980767526912",
		    "IDENTIFICACION_CANAL": "7728690177769472",
		    "SIGLA_APLICACION": "fadtofad",
		    "IDENTIFICACION_USUARIO": "3715299920576512",
		    "DIRECCION_IP_CONSUMIDOR": "6304105272184232",
		    "DIRECCION_IP_CLIENTE": "5138181667717682",
		    "FECHA_ENVIO_MENSAJE": "iroad",
		    "HORA_ENVIO_MENSAJE": "nakfawsu",
		    "ATRIBUTO_PAGINEO": "ojfalupo",
		    "CLAVE_BUSQUEDA": "nukt",
		    "CANTIDAD_REGISTROS": 27553107
		  },
		  "BODY_PAGO_REQUEST": {
		    "IDENTIFICADOR_COMERCIO": 47.60244365,
		    "TIPO_TRANSACCION": "4903058219041223",
		    "MONTO_TRANSACCION": 91.8387726,
		    "NUMERO_FACTURA": 46.37268095,
		    "IDENTIFICACION_TARJETAHABIENTE": "4742692009410560",
		    "NOMBRE_TARJETAHABIENTE": "usuiz",
		    "NUMERO_TARJETA": "nich",
		    "FECHA_VENCIMIENTO_TARJETA": 76.89428081,
		    "CODIGO_SEGURIDAD_TARJETA": 46.50398565,
		    "NUMERO_LOTE": "sanv"
		  }
		}',
	CURLOPT_HTTPHEADER => array(
	    "accept: application/json",
	    "content-type: application/json",
	    "x-ibm-client-id: 48304076-fff7-4fc4-9399-237f3ffad519",
	    "apikey: ".$apikey
		),
	)
);
	    // "x-ibm-client-id: 48304076-fff7-4fc4-9399-237f3ffad519"

	    // "x-ibm-client-id: 48304076-fff7-4fc4-9399-237f3ffad519",
	    // "apikey: ".$apikey

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
	/*
	IDENTIFICADOR_UNICO_GLOBAL = Identificador único generado por CLIENTE. Debe enviar una constante con el valor “900”.
	IDENTIFICACION_CANAL = Identificación del canal correspondiente a la transacción, Debe enviar una constante con el valor “06”.
	SIGLA_APLICACION = Debe enviar una constante con el valor “APIC”
	IDENTIFICACION_USUARIO = Identificador del usuario dentro del portal de CLIENTE.
	DIRECCION_IP_CONSUMIDOR = Dirección Ip de la máquina que realiza el consumo del servicio. 
	DIRECCION_IP_CLIENTE = Dirección Ip del cliente que realiza la transacción a través del portal de CLIENTE.
	FECHA_ENVIO_MENSAJE = Fecha del envío de la transacción en formato YYYYMMDD.
	HORA_ENVIO_MENSAJE = Hora de envío de la transacción en formato hhmmss.
	ATRIBUTO_PAGINEO = Debe enviar una constante con el valor “N”
	CLAVE_BUSQUEDA = Debe enviar una constante con el valor “ ”
	CANTIDAD_REGISTROS = Debe enviar una constante con el valor 0
	*/
	/*
	IDENTIFICADOR_COMERCIO = Código de comercio, este código es entregado por Mercantil Banco y es parte de la afiliación realizada por el comercio
	TIPO_TRANSACCION = Se refiere al código de la transacción que se está realizando, para el caso del pago el código debe ser 0200 y para el reverso el código debe ser 0420.
	MONTO_TRANSACCION = Monto correspondiente a la transacción. Monto de la transacción, en caso de contener decimales los mismos deben ser separados por una coma (,) ejemplo "MONTO_TRANSACCION": 2566,33 
	NUMERO_FACTURA = Número de factura, este datos es para el control de la empresa y el servicio valida que no esté duplicada y que sea mayor a cero
	IDENTIFICACION_TARJETAHABIENTE = Identificación del TH, en este campo debe colocarse el número de cedula de identidad en caso ce VE o la identificación del cliente correspondiente a su país de origen
	NOMBRE_TARJETAHABIENTE = Nombre del cliente que aparece en la tarjeta utilizada.
	NUMERO_TARJETA = Número de tarjeta sin ningún tipo de separación, campo numérico
	FECHA_VENCIMIENTO_TARJETA = Fecha de vencimiento de la tarjeta en formato
	MMAA, para los meses comprendidos de enero a septiembre no deben incluir cero a la izquierda, ejemplo 
	FECHA_VENCIMIENTO_TARJETA": 720 = Julio del 2020
	CODIGO_SEGURIDAD_TARJETA = Código de seguridad o CVV numero de tres dígitos que está en la parte posterior de la tarjeta
	NUMERO_LOTE = Numero de control utilizado por la empresa que consume el servicio para su uso interno, el valor debe ser numérico y debe ser mayor a cero.
	*/
	/*
		{
			"HEADER_PAGO_REQUEST":
				{
					"IDENTIFICADOR_UNICO_GLOBAL": "5699980767526912",
					"IDENTIFICACION_CANAL": "7728690177769472",
					"SIGLA_APLICACION": "fadtofad",
					"IDENTIFICACION_USUARIO": "3715299920576512",
					"DIRECCION_IP_CONSUMIDOR": "200.90.94.9",
					"DIRECCION_IP_CLIENTE": "200.90.94.9",
					"FECHA_ENVIO_MENSAJE": "20200515",
					"HORA_ENVIO_MENSAJE": "222500",
					"ATRIBUTO_PAGINEO": "N",
					"CLAVE_BUSQUEDA": " ",
					"CANTIDAD_REGISTROS": 0
				},
			"BODY_PAGO_REQUEST":
				{
					"IDENTIFICADOR_COMERCIO": 47.60244365,
					"TIPO_TRANSACCION": "0200",
					"MONTO_TRANSACCION": 1000,
					"NUMERO_FACTURA": 1,
					"IDENTIFICACION_TARJETAHABIENTE": "7132358",
					"NOMBRE_TARJETAHABIENTE": "Luis Rodriguez",
					"NUMERO_TARJETA": "5449096912070410",
					"FECHA_VENCIMIENTO_TARJETA": 223,
					"CODIGO_SEGURIDAD_TARJETA": 226,
					"NUMERO_LOTE": "1"
				}
			}	*/
			/*
		'{"HEADER_PAGO_REQUEST":{"IDENTIFICADOR_UNICO_GLOBAL":"5699980767526912","IDENTIFICACION_CANAL":"778690177769473","SIGLA_APLICACION":"fadtofad","IDENTIFICACION_USUARIO":"3715299920576512","DIRECCION_IP_CONSUMIDOR": "6304105272184232","DIRECCION_IP_CLIENTE": "5138181667717682","FECHA_ENVIO_MENSAJE": "iroad","HORA_ENVIO_MENSAJE":"nakfawsu","ATRIBUTO_PAGINEO":"ojfalupo","CLAVE_BUSQUEDA":"nukt","CANTIDAD_REGISTROS":27553107},"BODY_PAGO_REQUEST":{"IDENTIFICADOR_COMERCIO":47.60244365,"TIPO_TRANSACCION":"4903058219041223","MONTO_TRANSACCION":91.8387726,"NUMERO_FACTURA":46.37268095,"IDENTIFICACION_TARJETAHABIENTE":"4742692009410560","NOMBRE_TARJETAHABIENTE":"usuiz","NUMERO_TARJETA":"nich","FECHA_VENCIMIENTO_TARJETA":76.89428081,"CODIGO_SEGURIDAD_TARJETA":46.50398565,"NUMERO_LOTE":"sanv"}}			*/

/*
		'{
			"HEADER_PAGO_REQUEST":
			{
				"IDENTIFICADOR_UNICO_GLOBAL": "5699980767526912",
				"IDENTIFICACION_CANAL": "7728690177769472",
				"SIGLA_APLICACION": "fadtofad",
				"IDENTIFICACION_USUARIO": "3715299920576512",
				"DIRECCION_IP_CONSUMIDOR": "200.90.94.9",
				"DIRECCION_IP_CLIENTE": "200.90.94.9",
				"FECHA_ENVIO_MENSAJE": "20200515",
				"HORA_ENVIO_MENSAJE": "222500",
				"ATRIBUTO_PAGINEO": "N",
				"CLAVE_BUSQUEDA": " ",
				"CANTIDAD_REGISTROS": 0
			},
			"BODY_PAGO_REQUEST":
			{
				"IDENTIFICADOR_COMERCIO": 47.60244365,
				"TIPO_TRANSACCION": "0200",
				"MONTO_TRANSACCION": 1000,
				"NUMERO_FACTURA": 1,
				"IDENTIFICACION_TARJETAHABIENTE": "7132358",
				"NOMBRE_TARJETAHABIENTE": "Luis Rodriguez",
				"NUMERO_TARJETA": "5449096912070410",
				"FECHA_VENCIMIENTO_TARJETA": 223,
				"CODIGO_SEGURIDAD_TARJETA": 226,
				"NUMERO_LOTE": "1"
			}
		}',
*/
?>
