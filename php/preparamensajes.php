<?php
header('Content-Type: application/json');
include_once("../_config/conexion.php");
include_once("funciones.php");

$fecha = date('Y-m-d');

$x0120 = strtotime('-20 year', strtotime($fecha));
$x0120 = date('Y-m-d', $x0120);

$x2130 = strtotime('-30 year', strtotime($fecha));
$x2130 = date('Y-m-d', $x2130);

$x3140 = strtotime('-40 year', strtotime($fecha));
$x3140 = date('Y-m-d', $x3140);

$x4150 = strtotime('-50 year', strtotime($fecha));
$x4150 = date('Y-m-d', $x4150);

$x5160 = strtotime('-60 year', strtotime($fecha));
$x5160 = date('Y-m-d', $x5160);

$filas = 0;
// Buscar en base de datos
if ($_POST["socios"]=="true") {
   $query = 'select * from socios';
   $edades = "";
   $edocivil="";
   $hijos="";
   // Rangos de Edad
   if ($_POST["edad-todos"]=="true") {
      $edades .= "";
   } else {
      $first = true;
      if ($_POST["01-20"]=="true") {
         $edades .= "fechanacimiento>='".$x0120."'";
         $first = false;
      }
      if ($_POST["21-30"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edades .= $coma."(fechanacimiento<'".$x0120."' and fechanacimiento>='".$x2130."')";
      }
      if ($_POST["31-40"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edades .= $coma."(fechanacimiento<'".$x2130."' and fechanacimiento>='".$x3140."')";
      }
      if ($_POST["41-50"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edades .= $coma."(fechanacimiento<'".$x3140."' and fechanacimiento>='".$x4150."')";
      }
      if ($_POST["51-60"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edades .= $coma."(fechanacimiento<'".$x4150."' and fechanacimiento>='".$x5160."')";
      }
      if ($_POST["61-99"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edades .= $coma."fechanacimiento<'".$x5160."'";
      }
   }
   // Sexo
   if ($_POST["sexo-todos"]=="true") {
      $sexo = "";
   } else {
      if ($_POST["femenino"]=="true") {
         $sexo = "sexo='femenino'";
      }
      if ($_POST["masculino"]=="true") {
         $sexo = "sexo='masculino'";
      }
   }
   // País
   if ($_POST["pais"]=="todos") {
      $pais = "";
   } else {
      $aPais = explode("-", $_POST["pais"]);
      $pais = "pais=".$aPais[0];
   }
   // Estado
   if ($_POST["estado"]=="todos") {
      $estado = "";
   } else {
      $aEstado = explode("-", $_POST["estado"]);
      $estado = "estado=".$aEstado[0];
   }
   // Ciudad
   if ($_POST["ciudad"]=="todos") {
      $ciudad = "";
   } else {
      $aCiudad = explode("-", $_POST["ciudad"]);
      $ciudad = "ciudad=".$aCiudad[0];
   }
   // Sector
   if (strlen($_POST["sector"])==0) {
      $sector = "";
   } else {
      $sector = "upper(sector) like %".trim($_POST["sector"])."%";
   }
   // Vehículo
   if ($_POST["vehiculo"]=="todos") {
      $vehiculo = "";
   } else {
      if ($_POST["vehiculo"]=="si") {
         $vehiculo = "vehiculo=1";
      }
      if ($_POST["vehiculo"]=="no") {
         $vehiculo = "vehiculo=0";
      }
   }
   // Profesion
   if (strlen($_POST["profesion"])==0) {
      $profesion = "";
   } else {
      $profesion = "upper(profesion) like %".trim($_POST["profesion"])."%";
   }
   // Ocupación
   if (strlen($_POST["ocupacion"])==0) {
      $ocupacion = "";
   } else {
      $ocupacion = "upper(ocupacion) like %".trim($_POST["ocupacion"])."%";
   }
   // Estado civil
   if ($_POST["edocivil-todos"]=="true") {
      $edocivil .= "";
   } else {
      $first = true;
      if ($_POST["soltero"]=="true") {
         $edocivil .= "edocivil='soltero'";
         $first = false;
      }
      if ($_POST["casado"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edocivil .= $coma."edocivil='casado'";
      }
      if ($_POST["divorciado"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edocivil .= $coma."edocivil='divorciado'";
      }
      if ($_POST["viudo"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edocivil .= $coma."edocivil='viudo'";
      }
      if ($_POST["complicado"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $edocivil .= $coma."edocivil='complicado'";
      }
   }
   // Padre vivo
   if ($_POST["padrevivo"]=="todos") {
      $padrevivo = "";
   } else {
      if ($_POST["padrevivo"]=="si") {
         $padrevivo = "padre=1";
      }
      if ($_POST["padrevivo"]=="no") {
         $padrevivo = "padre=0";
      }
   }
   // Madre viva
   if ($_POST["madreviva"]=="todos") {
      $madreviva = "";
   } else {
      if ($_POST["madreviva"]=="si") {
         $madreviva = "madre=1";
      }
      if ($_POST["madreviva"]=="no") {
         $madreviva = "madre=0";
      }
   }
   // Edades de los hijos
   if ($_POST["hijos-todos"]=="true") {
      $hijos .= "";
   } else {
      $first = true;
      if ($_POST["00-05"]=="true") {
         $hijos .= "hijos>0 or menores5>0";
         $first = false;
      }
      if ($_POST["05-10"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $hijos .= $coma."menores10>0";
      }
      if ($_POST["11-20"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $hijos .= $coma."menores20>0";
      }
      if ($_POST["21-99"]=="true") {
         if ($first) {
            $coma = "";
            $first = false;
         } else {
            $coma = " or ";
         }
         $hijos .= $coma."mayores>0";
      }
   }
   // Armar la consulta
   $first = true;
   if (strlen($edades)>0) {
      $query .= " where (".$edades.")";
      $first = false;
   }
   if (strlen($sexo)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$sexo."";
   }
   if (strlen($pais)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$pais."";
   }
   if (strlen($estado)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$estado."";
   }
   if (strlen($ciudad)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$ciudad."";
   }
   if (strlen($sector)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$sector."";
   }
   if (strlen($vehiculo)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$vehiculo."";
   }
   if (strlen($profesion)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$profesion."";
   }
   if (strlen($ocupacion)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$ocupacion."";
   }
   if (strlen($edocivil)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma."(".$edocivil.")";
   }
   if (strlen($padrevivo)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$padrevivo."";
   }
   if (strlen($madreviva)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma.$madreviva."";
   }
   if (strlen($hijos)>0) {
      if ($first) {
         $coma = " where ";
         $first = false;
      } else {
         $coma = " and ";
      }
      $query .= $coma."(".$hijos.")";
   }
   $result = mysqli_query($link, $query);
   $filas += mysqli_num_rows($result);
   if ($_POST["sms"]=="true" && $_POST["email"]=="true") {
      $filas = $filas * 2;
   }
}

if ($_POST["prospectos"]=="true") {
   if ($_POST["sms"]=="true") {
      if (strlen($_POST["otrossms"])>0) {
         $aSMS = explode(";",$_POST["otrossms"]);
         $filas += count($aSMS);
      }
   }
   if ($_POST["email"]=="true") {
      if (strlen($_POST["otrosemails"])>0) {
         $aEmails = explode(";",$_POST["otrosemails"]);
         $filas += count($aEmails);
      }
   }
}
$respuesta = '{"exito":"SI","query":"'.$query.'","filas":'.$filas.'}';

echo $respuesta;

function calculaedad($fechanacimiento){
   list($ano,$mes,$dia) = explode("-",$fechanacimiento);
   $ano_diferencia  = date("Y") - $ano;
   $mes_diferencia = date("m") - $mes;
   $dia_diferencia   = date("d") - $dia;
   if ($dia_diferencia < 0 || $mes_diferencia < 0)
      $ano_diferencia--;
   return $ano_diferencia;
}
?>
