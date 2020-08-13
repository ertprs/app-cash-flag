<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("funciones.php");
require("generapdf.php");

$file = 'CONTRATO DE ADHESIÓN PARA EL USO DE LA PLATAFORMA CASH-FLAG versión 1.0

Entre SGC CONSULTORES C.A., sociedad mercantil identificada con el número de Registro de Identificación Fiscal (RIF) No. J-40242441-8, de domicilio Parroquia San Blas, avenida Uslar, casa No. 99-13, debidamente registrada en el Registro Mercantil Segundo del Estado Carabobo, bajo el Tomo 52-A, número 33 del año 2013, denominada para los efectos de este contrato EL PROVEEDOR, y ';
$file .= $_POST["razonsocial"];

$file .= ', denominado comercialmente como ';
$file .= $_POST["nombre"];

$file .= ' número de identificación (RIF) No. ';
$file .= $_POST["rif"];

$file .= ', de domicilio fiscal ';
$file .= $_POST["direccion"];

$file .= ' quien a los efectos del presente contrato se denomina EL ALIADO, se ha convenido celebrar, como en efecto se celebra, el siguiente contrato contenido en las siguientes cláusulas:


I. Definición de términos:

CASH-FLAG: Plataforma tecnológica sobre la cual se ejecutan los productos y/o servicios objeto de este contrato.
PROVEEDOR: La empresa encargada de la producción, entrega y mantenimiento de los productos y/o servicios objeto de este contrato, es la empresa propietaria de CASH-FLAG.
ALIADO: Persona Natural o Jurídica que utiliza CASH-FLAG generando beneficios a sus clientes y aprovechando los servicios para mejorar su negocio.
CLIENTE: Persona Natural o Jurídica que consume en el ALIADO y obtiene beneficios de CASH-FLAG.
TOKEN: Unidad de Valor que puede usarse dentro de CASH-FLAG para realizar transacciones.
TRANSACCIÓN: Cualquier interacción que ocurra dentro de CASH-FLAG, puede ser de valor (monto en dinero o tokens) o de datos (registro, mensaje, notificación, etc.).
CONSUMO: Canje de un TOKEN por productos y/o servicios en un ALIADO.
BENEFICIO: Producto, servicio o monto que de manera estratégica entrega un ALIADO a un CLIENTE.
CUPÓN: TOKEN de un sólo uso que un ALIADO entrega a un CLIENTE como BENEFICIO por medio de CASH-FLAG, estos se generan al registrar en la plataforma la factura de una compra en el ALIADO.
TARJETA: TOKEN que un cliente puede obtener de manera voluntaria para realizar CONSUMOS en un ALIADO.
TARJETA PREPAGADA: TOKEN recargable personalizado para realizar CONSUMOS en un ALIADO.
TARJETA DE REGALO: TOKEN de un solo uso que un CLIENTE puede regalar a un tercero para que realice CONSUMOS en un ALIADO.


II. Alcance:
El PROVEEDOR ofrece al ALIADO una plataforma para ejecutar productos y/o servicios digitales orientados a generar la fidelidad de sus CLIENTES denominada CASH-FLAG, esta plataforma permitirá al ALIADO usar los productos de CASH-FLAG a su entera libertad, siempre que sea lícito y cumpla con sus responsabilidades listadas más adelante, en la versión 1.0 el PROVEEDOR ofrece los siguientes productos:
A) CUPONES de BENEFICIOS
B) TARJETAS PREPAGADAS
C) TARJETAS DE REGALO


III. Responsabilidades:
El PROVEEDOR tiene las siguientes responsabilidades:
A) Mantener operativa la plataforma
B) Entregar información estadística para los ALIADOS
C) Velar por la privacidad de ALIADOS y CLIENTES
D) Moderar la actividad de la plataforma para evitar acciones cuestionables
E) Cumplir con el acuerdo de nivel de servicio anexo a este contrato
F) Colaborar con el ALIADO en las iniciativas de promoción para captar más clientes o retener clientes actuales
G) Realizar labores de investigación y desarrollo para generar nuevos productos y/o servicios

El ALIADO tiene las siguientes responsabilidades:
A) Otorgar BENEFICIOS atractivos a los clientes para hacer atractiva la plataforma
B) Colaborar con el mantenimiento de CASH-FLAG honrando sus compromisos, aportando información cuando sea requerida, pagando puntualmente sus comisiones, reportando el desempeño de su cartera de CLIENTES o informando acerca de posibles oportunidades de mejora.
C) Velar por la imagen de CASH-FLAG haciendo un uso adecuado de logos, material POP, etc.
D) Velar por la privacidad de sus CLIENTES
E) Actuar con ética, cumplir con sus ofertas y promociones, no hacer publicidad engañosa
F) Cumplir con el acuerdo de nivel de servicio anexo a este contrato
G) Mostrar receptividad y colaboración con las iniciativas de promoción que proponga el PROVEEDOR


IV. Derechos:
El ALIADO tiene los siguientes derechos:
A) Solicitar reportes e información estadística sobre el comportamiento de su cuenta (sin incluir detalles privados de los CLIENTES)
B) Recibir soporte comercial y técnico para aprovechar al máximo la plataforma.
C) Recibir información detallada sobre sus transacciones y facturas.
D) Percibir beneficios en función del comportamiento de su cartera de clientes, estos beneficios se explican más adelante en el apartado VII.

El PROVEEDOR tiene los siguientes derechos:
A) Recibir retroalimentación oportuna y adecuada para garantizar el servicio
B) Recibir el pago de sus servicios de manera oportuna y completa
C) Solicitar a los ALIADOS la información necesaria y suficiente para evaluar el desempeño y ejecutar iniciativas de promoción o mejoras técnicas a la plataforma.
D) Captar a nuevos CLIENTES para la plataforma.


V. Propiedad de la información:
Toda la información que se genere en la plataforma será propiedad de CASH-FLAG, a excepción de los datos privados de ALIADOS y CLIENTES.


VI. Beneficios para los CLIENTES:
El ALIADO se compromete a entregar a los CLIENTES los siguientes BENEFICIOS:
A) Premio por CONSUMO.
B) Premio de Bienvenida.
C) Premio por ocasiones especiales.
D) Premio especial para promociones: Se define en cada promoción.


VII. Comisiones:
El ALIADO se compromete a pagar al PROVEEDOR las siguientes comisiones:
A) 3% del monto promedio entre la factura que se use para generar un CUPÓN y el monto de la factura con la que se canjee, esta comisión se calcula cuando el CUPÓN es canjeado.
B) 3% del monto que se recargue en una TARJETA PREPAGADA, este monto se calcula al momento de la recarga.
C) 3% del monto por el que se compre una TARJETA DE REGALO, este monto se calcula al momento de la compra.

Los consumos con TARJETAS PREPAGADAS o TARJETAS DE REGALO no generan comisiones.


VIII. Beneficio especial para el ALIADO, comisión por consumo de sus clientes:
El ALIADO recibirá de CASH-FLAG, el 15% de las comisiones generadas por los clientes que haya captado directamente y estén vinculados a su cuenta, indistintamente de donde ejecuten sus consumos, siempre  cuando el ALIADO esté solvente con sus compromisos administrativos y activo en la plataforma. Se considera que un ALIADO se encuentra activo en la plataforma cuando genera al menos una transacción a la semana.


IX. Facturación y liquidación de comisiones:
El PROVEEDOR generará una relación de transacciones a cobrar de forma semanal los días lunes junto con la factura correspondiente la cual deberá ser cancelada en un lapso de 5 días hábiles, si el ALIADO no cumpliera con el pago de manera oportuna y completa, este se expone a ser inactivado en la plataforma y de ser recurrente en 3 oportunidades, el PROVEEDOR podrá, a su discreción, darlo de baja definitivamente.


X. Nuevos productos y nuevas versiones:
El PROVEEDOR realizará permanentemente acciones para generar nuevos productos a incluir en la plataforma y es privilegio de los ALIADOS participar del proceso de diseño, desarrollo y pruebas.
Este contrato abarca la versión 1.0, los nuevos productos serán incorporados en este contrato mediante la liberación de una nueva versión, si se trata de una modificación menor se generará un addendum y se hará una actualización a la versión actual, estos cambios se notificarán a los ALIADOS mediante una comunicación vía email.


XI. Cadena de bloques (Blockchain):
Las transacciones finales de cada ciclo (generación y canje de cupones o recarga y consumos) serán registradas en la Blockchain de Aeternity.
Todos los ALIADOS tendrán una cuenta en la Blockchain de Aeternity y podrán usar su llave privada para firmar electrónicamente cualquier comunicación o transacción.
Este contrato será firmado de manera electrónica por ambas partes (PROVEEDOR y ALIADO), encriptado y registrado en la cadena de bloques de Aeternity como sello del acuerdo.

El presente Contrato se firma en la Ciudad de Valencia, estado Carabobo en la Republica Bolivariana de Venezuela, el día '.$_POST["fecha"].'.

Aceptación:
SGC Consultores:
';
$file .= $_POST["firmasgc"].'

';

$file .= $_POST["razonsocial"].':
';
$file .= $_POST["firmacliente"].'


XII. Anexo - Acuerdo de nivel de servicio (A continuación)

==============================================================================================

ANEXO

ACUERDO DE NIVEL DE SERVICIO PARA PRODUCTOS SGC
POR FAVOR LEA CUIDADOSAMENTE


El siguiente acuerdo de nivel de servicio establece los términos y condiciones para asegurar el servicio/soporte técnico (en lo sucesivo EL SERVICIO) al usuario de los productos y servicios comercializados por SGC Consultores C.A. (en lo sucesivo SGC)


DERECHO A SERVICIO: Tienen acceso y derecho a EL SERVICIO los usuarios solventes con sus licencias y pago de servicios.


COMPROMISO DE CONTINUIDAD DEL SERVICIO: SGC Hará todas las acciones necesarias para garantizar la continuidad del servicio, contratando personal de soporte, brindando capacitación en el uso de las herramientas e incorporando mejoras a los distintos productos y servicios.


CLIENTE APTO PARA SERVICIO: Para los efectos de este acuerdo se considera cliente apto para servicio al usuario que reporta un incidente siempre y cuando posea una licencia válida y registrada.


RESPONSABLE DEL SERVICIO: La responsabilidad del servicio depende de la correcta integración de SGC, el cliente y los usuarios, esta responsabilidad se describe de la siguiente manera:
	
	SGC: Es responsable de garantizar la continuidad del servicio realizando las acciones necesarias para que los productos y servicios funciones en los términos, condiciones y con todas las características que hayan sido negociadas al momento de la venta.
	El Cliente: Es la persona natural o jurídica que compra o contrata los productos y/o servicios de SGC. A su vez, está autorizado para usar la licencia y tiene la responsabilidad de dar el uso correcto a estos productos y/o servicios para tener derecho al soporte. Si por algún motivo SGC percibe que un incidente reportado por el cliente se debe a un uso indebido, SGC podrá decidir y establecer a su único criterio las condiciones para dar soporte.
	Los usuarios: Los usuarios son las personas que utilizan los productos y/o servicios en nombre de El Cliente y son corresponsables por el uso correcto, según lo estipulado en el presente contrato.

	
SEGURIDAD DE LAS TECNOLOGÍAS DE INFORMACIÓN (TI): Los usuarios deberán ingresar a la base de datos únicamente por medio de las aplicaciones diseñadas para tal fin, manteniendo los perfiles asignados para cada nivel de usuario dentro de las aplicaciones. De igual manera el cliente deberá tomar todas las medidas necesarias para garantizar la seguridad e integridad de la información contenida en sus servidores.


VIGENCIA DEL ACUERDO: Este acuerdo posee vigencia mientras existan dos condiciones:
1.)	Vida útil del producto y/o servicio prestado por SGC; y
2.)	Siempre y cuando El Cliente cumpla con estos requisitos: esté solvente, tenga una licencia válida y registrada. 
Esta vigencia inicia a partir del momento en que se realiza el registro de la licencia por parte de EL CLIENTE y expirará al finalizar la relación entre EL CLIENTE y SGC.


TERMINACIÓN DEL ACUERDO: Este acuerdo se terminará por alguna de las siguientes causas:
1.	Incumplimiento de los términos y condiciones de la licencia por parte de EL CLIENTE
2.	Incumplimiento de los términos y condiciones de este acuerdo
3.	Previo acuerdo entre el usuario y SGC
4.	Previo acuerdo entre EL CLIENTE y SGC
5.	Motivos de fuerza mayor que obliguen el cese de este acuerdo.


RESULTADOS A ESPERAR DEL SERVICIO: SGC sólo brindará soporte a los productos y servicios implementados en el cliente en los términos y condiciones establecidos en el contrato de licencia


HORARIO DEL SERVICIO: SGC prestará EL SERVICIO de lunes a viernes entre las 7:00am y las 7:00pm (hora de Venezuela), salvo casos de alta severidad los cuales recibirán tratamientos especiales.


TIPOLOGÍA PARA SOPORTE Y SERVICIO: SGC cuenta con las siguientes figuras para brindar asistencia técnica a los incidentes reportados por EL CLIENTE:

a) Consultor Técnico Calificado: Personal con perfil profesional capacitado y entrenado por SGC que está facultado para brindar soporte técnico y resolver el incidente reportado por EL CLIENTE. Este Consultor Técnico Calificado actúa como Representante SGC Calificado.
b) Representante SGC Calificado: Es un ente jurídico con actividad comercial legal vinculada al área técnica y cuyo personal posee un perfil profesional y que está capacitado y entrenado por SGC que está facultado para brindar soporte técnico y resolver el incidente reportado por EL CLIENTE.


APOYO IN SITU: Cuando sea necesaria la intervención de SGC en forma física esta intervención se prestará en la localidad donde fue instalada la licencia o donde haya sido mudada, previo acuerdo entre El CLIENTE y SGC. Para activar este apoyo in situ EL CLIENTE debe solicitar cotización a SGC.


APOYO A DISTANCIA: Cuando la intervención sea de forma remota, se podrá realizar en cualquier localidad, utilizando herramientas de conexión remota. Para tal efecto, SGC hace llegar un formato de autorización que EL CLIENTE debe firmar y sellar y enviar escaneado vía correo electrónico. EL CLIENTE recibirá cotización una vez evaluada la situación.


REQUISITOS DEL SERVICIO: Para poder prestar un buen servicio SGC necesitará que el cliente cumpla con los siguientes requisitos:
1.	Estar solvente con sus licencias y pago de servicios
2.	Brindar toda la información necesaria para clarificar el incidente o requerimiento
3.	Contar con los recursos disponibles para atender a los Representantes SGC Calificados el tiempo que sea requerido para solventar el incidente reportado.


MODALIDADES DE SOPORTE: El soporte básico incluye:
1.	Atención y asesoría telefónica
2.	Atención y asesoría ON-LINE
3.	Mantenimiento y actualizaciones de los sistemas
4.	Actualización de los sistemas a las últimas versiones liberadas y certificadas
5.	Resolución de emergencias


CLASIFICACIÓN DE LOS INCIDENTES: Los incidentes se clasifican dependiendo del impacto o riesgo para el negocio o la infraestructura del cliente, esta clasificación es la siguiente:
	
	SEVERIDAD 3: No representa riesgo o no tiene impacto significativo sobre el negocio
	Plazo para la solución: 5 días hábiles a partir de la notificación del incidente
	Primera respuesta al usuario para informar estado de la solución: dos días hábiles después de recibido el reporte del incidente
	Contactos con el usuario para certificar la solución del problema: 5 días hábiles a partir de la notificación del incidente y a partir de ahí cada 3 días hábiles si fuera necesario
	
	SEVERIDAD 2: Riesgo o impacto sobre componentes de la aplicación o base de datos, puede afectar la operación interna del negocio sin perjuicios a terceros
	Plazo para la solución: 3 días hábiles a partir de la notificación del incidente
	Primera respuesta al usuario para informar estado de la solución: un día hábil después de recibido el reporte del incidente
	Contactos con el usuario para certificar la solución del problema: 3 días hábiles a partir de la notificación del incidente y a partir de ahí cada 24 horas si fuera necesario
	
	SEVERIDAD 1: Riesgo o impacto significativo sobre la infraestructura o el negocio, afecta la operación, la imagen de la empresa y/o los compromisos con terceros, puede acarrear sanciones, multas o cierre.
	Plazo para la solución: un día hábil a partir de la notificación del incidente
	Primera respuesta al usuario para informar estado de la solución: una hora (hábil) después de recibido el reporte del incidente
	Contactos con el usuario para certificar la solución del problema: cada 2 horas a partir de la notificación del incidente


OBSERVACIÓN AL TIPO DE SEVERIDAD: SGC está consciente que cualquier tipo de severidad reportada por EL CLIENTE afecta la calidad de sus procesos en alguna etapa vital. Por ello, SGC siempre estimará ofrecer soluciones óptimas y efectivas lo antes posible.
';

$a = fopen('../contrato/ultimocontrato.contrato','w+');
fwrite($a,$file);
fclose($a);

// $p1 = '
// CASH-FLAG: Plataforma tecnológica sobre la cual se ejecutan los productos y/o servicios objeto de este contrato.
// PROVEEDOR: La empresa encargada de la producción, entrega y mantenimiento de los productos y/o servicios objeto de este contrato, es la empresa propietaria de CASH-FLAG.
// ALIADO: Persona Natural o Jurídica que utiliza CASH-FLAG generando beneficios a sus clientes y aprovechando los servicios para mejorar su negocio.
// CLIENTE: Persona Natural o Jurídica que consume en el ALIADO y obtiene beneficios de CASH-FLAG.
// TOKEN: Unidad de Valor que puede usarse dentro de CASH-FLAG para realizar transacciones.
// TRANSACCIÓN: Cualquier interacción que ocurra dentro de CASH-FLAG, puede ser de valor (monto en dinero o tokens) o de datos (registro, mensaje, notificación, etc.).
// CONSUMO: Canje de un TOKEN por productos y/o servicios en un ALIADO.
// BENEFICIO: Producto, servicio o monto que de manera estratégica entrega un ALIADO a un CLIENTE.
// CUPÓN: TOKEN de un sólo uso que un ALIADO entrega a un CLIENTE como BENEFICIO por medio de CASH-FLAG, estos se generan al registrar en la plataforma la factura de una compra en el ALIADO.
// TARJETA: TOKEN que un cliente puede obtener de manera voluntaria para realizar CONSUMOS en un ALIADO.
// TARJETA PREPAGADA: TOKEN recargable personalizado para realizar CONSUMOS en un ALIADO.
// TARJETA DE REGALO: TOKEN de un solo uso que un CLIENTE puede regalar a un tercero para que realice CONSUMOS en un ALIADO.
// ';

// $p2 = '
// El PROVEEDOR ofrece al ALIADO una plataforma para ejecutar productos y/o servicios digitales orientados a generar la fidelidad de sus CLIENTES denominada CASH-FLAG, esta plataforma permitirá al ALIADO usar los productos de CASH-FLAG a su entera libertad, siempre que sea lícito y cumpla con sus responsabilidades listadas más adelante, en la versión 1.0 el PROVEEDOR ofrece los siguientes productos:
// 	A) CUPONES de BENEFICIOS
// 	B) TARJETAS PREPAGADAS
// 	C) TARJETAS DE REGALO
// ';

$hash = hash("sha256",$file);
$hoy = date("Y-m-d");

$query  = 'INSERT INTO contratos (razonsocial, nombre, rif, direccion, ';
$query .= 'email, firmasgc, firmacliente, fecha, fecharegistro, hash) VALUES (';
$query .= '"'.$_POST["razonsocial"].'", "'.$_POST["nombre"].'", "'.$_POST["rif"].'", ';
$query .= '"'.$_POST["direccion"].'", "'.$_POST["email"].'", "'.$_POST["firmasgc"].'", ';
$query .= '"'.$_POST["firmacliente"].'", "'.$_POST["fecha"].'","'.$hoy.'", "'.$hash.'")';
if($result = mysqli_query($link, $query)) {
	$respuesta = '{"exito":"SI",';
    $respuesta .= '"mensaje":"Registro exitoso"}';
} else {
	// echo mysqli_error($link);
	$respuesta = '{"exito":"NO"}';
}

////////////////////////////////////////////////////////////////////////////////////////////////////
$pdf = new PDF();
$title = utf8_decode('CASH-FLAG - Contrato de Adhesión');
$pdf->SetTitle($title);
$pdf->SetAuthor('SGC Consultores C.A.');
$pdf->PrintChapter(1,utf8_decode('Definición de términos'),'../contrato/ultimocontrato.contrato');
// $pdf->PrintChapter(1,'Definición de términos',utf8_decode($p1));
// $pdf->PrintChapter(2,'Alcance',$p2);
$pdf->Output('F','../contrato/documentos/'.$_POST["firmacliente"].'.pdf',true);

////////////////////////////////////////////////////////////////////////////////////////////////////
$cabeceras  = "MIME-version: 1.0\n";

$file2attach = '../contrato/documentos/'.$_POST["firmacliente"].'.pdf';

// $fileTmpPath = $vAdjunto['tmp_name'];
$fileName = $file2attach;
$fileSize = filesize($file2attach);
$fileType = filetype($file2attach);
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

$cabeceras .= "Content-type: multipart/mixed;";
$cabeceras .= "boundary=\"--_Separador-de-mensajes_--\"\n";

$sCabeceraTexto = "----_Separador-de-mensajes_--\n";
$sCabeceraTexto .= "Content-type: text/html;charset=iso-8859-1\n";
$sCabeceraTexto .= "Content-transfer-encoding: 7BIT\n";
$mensaje = $sCabeceraTexto;


$sAdjuntos  = "\n\n----_Separador-de-mensajes_--\n";
$sAdjuntos .= "Content-type: ".$fileType.";name=\"".$_POST["firmacliente"].'.pdf'."\"\n";
$sAdjuntos .= "Content-Transfer-Encoding: BASE64\n";
$sAdjuntos .= "Content-disposition: attachment;filename=\"".$_POST["firmacliente"].'.pdf'."\"\n\n";

$oFichero = fopen($file2attach, 'r');
$sContenido = fread($oFichero, filesize($file2attach));
$sAdjuntos .= chunk_split(base64_encode($sContenido));
fclose($oFichero);


$correo = $_POST["email"];

$mensaje .= utf8_decode('<div style="width: 10em; height: auto"><a href="app.cash-flag.com"><img src="https://app.cash-flag.com/img/logoclub.png" title="ingrese al portal haciendo click en el logo" /></a></div>');
$mensaje .= utf8_decode('Hola '.trim($_POST["razonsocial"]).',<br/><br/>');
$mensaje .= utf8_decode('¡Gracias por querer formar parte de nuestra comunidad!<br/><br/>');
$mensaje .= utf8_decode('Adjunto encontrarás una copia del contrato de adhesión para tus archivos.<br/><br/>');
$mensaje .= utf8_decode('<b>Te garantizamos que tu información será guardada celosamente y nunca será compartida con ningún tercero sin tu consentimiento y te aseguramos que siempre cumpliremos con las Leyes vigentes en lo relacionado al tratamiento de tus datos personales.</b><br/><br/>');
$mensaje .= utf8_decode('Nuestra comunidad está en permanente evolución y tú como un miembro muy importante puedes aportarnos ideas o sugerencias que la harán crecer, ten la certeza que serás escuchado(a) y tus sugerencias o comentarios serán repondidos en un lapso de tiempo razonable con mucho entusiasmo por resolver tus inquietudes, para nosotros será un placer atenderte por medio del email: <a href="mailto:info@cash-flag.com">info@cash-flag.com</a>.<br/><br/>');
$mensaje .= utf8_decode('Bienvenido!!!'.'<br/><br/>');
$mensaje .= utf8_decode('Equipo de Cash-Flag'.'<br/><a href="https://www.cash-flag.com">www.cash-flag.com</a><br/><br/>');
$mensaje .= utf8_decode('<b>Nota:</b> Esta cuenta no es monitoreada, por favor no respondas este email, si deseas comunicarte con tu club escribe a: <b><a href="mailto:info@cash-flag.com">info@cash-flag.com</a></b>'.'<br/><br/>');

$mensaje .= $sAdjuntos."\n\n----_Separador-de-mensajes_----\n";

$asunto = utf8_decode(trim($_POST["razonsocial"]).', bienvenido a Cash-Flag, puente digital entre comercio y consumidores!!!');

// $cabeceras = 'Content-type: text/html;';

if ($_SERVER["HTTP_HOST"]!='localhost') {
	mail($correo,$asunto,$mensaje,$cabeceras);
}

// $respuesta = '{"exito":"SI","archivo":"ultimocontrato.contrato","contenido":'.json_encode($file).',';
// $respuesta .= '"razonsocial":"'.$_POST["razonsocial"].'",';
// $respuesta .= '"nombre":"'.$_POST["nombre"].'",';
// $respuesta .= '"rif":"'.$_POST["rif"].'",';
// $respuesta .= '"direccion":"'.$_POST["direccion"].'",';
// $respuesta .= '"email":"'.$_POST["email"].'",';
// $respuesta .= '"firmasgc":"'.$_POST["firmasgc"].'",';
// $respuesta .= '"firmacliente":"'.$_POST["firmacliente"].'",';
// $respuesta .= '"fecha":"'.$_POST["fecha"].'"}';

echo $respuesta;
?>
