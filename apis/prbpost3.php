<?php
//parámetros de envío
$usuario="sgcvzla@gmail.com";
$clave="Ma24032008.";

//cantidad de caracteres para teléfono 12
  //los 2 primeros caracteres es del código del país (58 Venezuela)
  //los 3 siguientes caracteres es de la telefonía (416,426,414,424,412)
  //seguido de los 7 últimos dígitos del número telefónico

$telefonos="584244071820";
// Para el envío a varios teléfonos se concatena cada número telefónico con punto y coma (;)
// $telefonos="584265207594;584242195147;584164248750”;

// la cantidad máxima para el texto por SMS es de 160 caracteres
$texto="prueba de envío 4";

//para el envío de múltiples mensajes se concatena cada texto con una barra vertical (|)
// $texto="prueba de envio 1|prueba de envio 2 ";
// *-- Para el envío múltiple de SMS, la cantidad de números telefónicos debe ser igual a la cantidad de
// mensajes a enviar --*
// $telefonos="584265207594;584242195147”;
// $texto="prueba de envio 1|prueba de envio 2 ";

//los parámetros a usar por el webservices:
$parametros="usuario=$usuario&clave=$clave&texto=$texto&telefonos=$telefonos";

//URL para capturar los datos de envio con webservices
//envio de parametros con metodo post a traves de CURL
$url = "http://www.sistema.massivamovil.com/webservices/SendSms";
$handler = curl_init();
curl_setopt($handler, CURLOPT_URL, $url);
curl_setopt($handler, CURLOPT_POST,true);
curl_setopt($handler, CURLOPT_POSTFIELDS, $parametros);
$response = curl_exec($handler);

echo "response ";
echo $response;
echo "<br/>curl_error ";
echo curl_error($handler);

curl_close($handler);

//Ejemplo De Recepción De Data XML Con PHP
//se carga el documento xml obtenido por el api
$xml = simplexml_load_string($response);
//con la data suministrada creamos una tabla html como ejemplo
?>
<table width="800" border="1" align="center">
  <tr>
    <td width="70"><b>Mensaje</b></td>
    <td width="318" colspan="3"><?=$xml->mensaje[0];?></td>
  </tr>
  <tr>
    <td><b>Estatus</b></td>
    <td colspan="3"><?=$xml->status[0];?></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><b>Telefonos enviados</b></td>
  </tr>
  <tr align="center">
    <td><b>ID</b></td>
    <td><b>SID</b></td>
    <td><b>Estatus</b></td>
    <td><b>Celular</b></td>
    <td><b>Texto</b></td>
  </tr>
  <?php
    //recorre todos los teléfonos que fueron enviados por el api
    //muestra cada uno de los atributos del teléfono como el sid (id único), status del mensaje, num celular
    // y texto enviado
    foreach ($xml->telefonos[0]->celular as $celular) {
  ?>
    <tr>
      <td><?=$celular['id']?></td>
      <td><?=$celular['sid']?></td>
      <td><?=$celular['status']?></td>
      <td><?=$celular['num_celular']?></td>
      <td><?=$celular['texto']?></td>
    </tr>
  <?php
    }
  ?>
</table>