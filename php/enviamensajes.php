<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("funciones.php");

$mensaje = utf8_decode($_POST["contenidosms"]);
$asunto = utf8_decode($_POST["asuntoemail"]);
$mensaje2 = utf8_decode($_POST["contenidoemail"]);

$total = 0;
// $lote = 3;
// Buscar en base de datos
if ($_POST["socios"]=="true") {
   $query = $_POST["query"];
   $result = mysqli_query($link, $query);
   // $filas = mysqli_num_rows($result);
   $msgs = 0;
   $lista = "";
   $first = true;
   $coma = "";
   while ($row = mysqli_fetch_array($result)) {
      if ($_POST["sms"]=="true") {
         $respuesta1 = enviasms(trim($row["telefono"]),$mensaje);
         $total++;
         /*
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = ";";
         }
         $lista .= $coma.trim($row["telefono"]);
         $msgs++;
         if($msgs==$lote) {
            $respuesta1 = enviasms($lista,$mensaje.$lista);
            $msgs = 0;
            $lista = "";
            $first = true;
         }
         $total++;
         */
      }
      if ($_POST["email"]=="true") {
         $email = trim($row["email"]);
         $nombres = trim($row["nombres"])." ".trim($row["apellidos"]);
         // $asunto = "Información interesante de Cash-Flag.";
         cashflagemail($email, $nombres, $asunto, $mensaje2);
         $total++;
      }
   }
   /*
   if ($_POST["sms"]=="true") {
      if($msgs>0) {
         $respuesta1 = enviasms($lista,$mensaje.$lista);
      }
   }
   */
}

if ($_POST["prospectos"]=="true") {
   if ($_POST["sms"]=="true") {
      if (strlen($_POST["otrossms"])>0) {
         $aSMS = explode(";",$_POST["otrossms"]);
         /*
         $msgs = 0;
         $lista = "";
         $first = true;
         $coma = "";
         */
         for ($i=0; $i < count($aSMS); $i++) {
            $respuesta1 = enviasms(trim($aSMS[$i]),$mensaje);
            /*
            if ($first) {
               $coma = "";
               $first = false;
            } else {
               $coma = ";";
            }
            $lista .= $coma.trim($aSMS[$i]);
            $msgs++;
            if($msgs==$lote) {
               $respuesta1 = enviasms($lista,$mensaje.$lista);
               $msgs = 0;
               $lista = "";
               $first = true;
            }
            */
            $total++;
         }
         /*
         if($msgs>0) {
            $respuesta1 = enviasms($lista,$mensaje.$lista);
         }
         */
      }
   }
   if ($_POST["email"]=="true") {
      if (strlen($_POST["otrosemails"])>0) {
         $aEmails = explode(";",$_POST["otrosemails"]);
         for ($i=0; $i < count($aEmails); $i++) {
            $email = trim($aEmails[$i]);
            $nombres = "<".trim($aEmails[$i]).">";
            // $asunto = "Información interesante de Cash-Flag.";
            cashflagemail($email, $nombres, $asunto, $mensaje2);
            $total++;
         }
      }
   }
}
$respuesta = '{"exito":"SI","mensaje":"'.$total.' Mensaje(s) enviado(s)."}';

echo $respuesta;
?>
