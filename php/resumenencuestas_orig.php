<?php 
header('Access-Control-Allow-Origin: *');
include_once("../_config/conexion.php");
include_once("../_config/configShopify.php");
include_once("funciones.php");
set_time_limit(3600);
/*
$fech1 = date('Y-m-d');
$fecha = strtotime('-1 day', strtotime ($fech1));
$fecha = date ('Y-m-d', $fecha);

$quer0 = "select * from encuesta where idp=1 and status=1 and desde<'".date('Y-m-d'). "' and hasta>='".date('Y-m-d')."'";
$resul0 = mysqli_query($link,$quer0);
if ($ro0 = mysqli_fetch_array($resul0)) {
    $ide = $ro0["id"];
    $desde = $ro0["desde"];
    $hasta = $ro0["hasta"];
    $descripcion = $ro0["descripcion"];
}

$quer1 = "SELECT * FROM detalle_encuesta where ide=" . $ide. " order by orden";
$resul1 = mysqli_query($link, $quer1);

$asunto = utf8_decode("Resumen de encuestas del período entre el ");
$asunto .= substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4);
$asunto .= " y el ";
$asunto .= substr($hasta,8,2).'/'.substr($hasta,5,2).'/'.substr($hasta,0,4);

$mensaje = utf8_decode("<u><b>Resumen de encuestas del período entre el ");
$mensaje .= substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4);
$mensaje .= " y el ";
$mensaje .= substr($hasta,8,2).'/'.substr($hasta,5,2).'/'.substr($hasta,0,4).":</b></u><br/><br/>";

$query = "select email from resultado_encuesta where ide=".$ide." group by email";
$result = mysqli_query($link,$query);
while ($row = mysqli_fetch_array($result)) { $totalencuestas++; }
$mensaje .= utf8_decode("Total encuestas aplicadas en el período: ".number_format($totalencuestas,0,',','.')."<br/><br/>");

while ($ro1 = mysqli_fetch_array($resul1)) {
    $orden = $ro1["orden"];
    $pregunta = $ro1["pregunta"];
    $tiporespuesta = $ro1["tiporespuesta"];
    switch ($tiporespuesta) {
        case 'si o no':
            $query = "select count(valorrespuesta1) as si from resultado_encuesta where idd=".$orden." and valorrespuesta1=1";
            $result = mysqli_query($link,$query);
            if ($row = mysqli_fetch_array($result)) {
                $si = $row["si"];
            }
            $query = "select count(valorrespuesta1) as no from resultado_encuesta where idd=".$orden." and valorrespuesta1=0";
            $result = mysqli_query($link,$query);
            if ($row = mysqli_fetch_array($result)) {
                $no = $row["no"];
            }
            $siono = $si+$no;
            $psi = $si/$siono*100;
            $pno = $no/$siono*100;
            if ($siono<>0) {
                $mensaje .= "Pregunta ".$orden." - ".trim($pregunta).": ";
            }
            if ($psi<>0) {
                $mensaje .= "SI ".number_format($psi,2,',','.')."%";
                if ($pno<>0) {
                    $mensaje .= " - ";
                } else {
                    $mensaje .= "<br/><br/>";
                }
            }
            if ($pno<>0) {
                $mensaje .= "NO ".number_format($pno,2,',','.')."%"."<br/><br/>";
            }
            break;
        case 'rango':
            $query = "select count(valorrespuesta2) as excelente from resultado_encuesta where idd=".$orden." and valorrespuesta2='excelente'";
            $result = mysqli_query($link,$query);
            if ($row = mysqli_fetch_array($result)) {
                $excelente = $row["excelente"];
            }
            $query = "select count(valorrespuesta2) as bueno from resultado_encuesta where idd=".$orden." and valorrespuesta2='bueno'";
            $result = mysqli_query($link,$query);
            if ($row = mysqli_fetch_array($result)) {
                $bueno = $row["bueno"];
            }
            $query = "select count(valorrespuesta2) as regular from resultado_encuesta where idd=".$orden." and valorrespuesta2='regular'";
            $result = mysqli_query($link,$query);
            if ($row = mysqli_fetch_array($result)) {
                $regular = $row["regular"];
            }
            $query = "select count(valorrespuesta2) as deficiente from resultado_encuesta where idd=".$orden." and valorrespuesta2='deficiente'";
            $result = mysqli_query($link,$query);
            if ($row = mysqli_fetch_array($result)) {
                $deficiente = $row["deficiente"];
            }
            $query = "select count(valorrespuesta2) as noopina from resultado_encuesta where idd=".$orden." and valorrespuesta2='no opina'";
            $result = mysqli_query($link,$query);
            if ($row = mysqli_fetch_array($result)) {
                $noopina = $row["noopina"];
            }
            $todos = $excelente+$bueno+$regular+$deficiente+$noopina;
            $pexcelente = $excelente/$todos*100;
            $pbueno = $bueno/$todos*100;
            $pregular = $regular/$todos*100;
            $pdeficiente = $deficiente/$todos*100;
            $pnoopina = $noopina/$todos*100;
            if ($todos<>0) {
                $mensaje .= "Pregunta ".$orden." - ".trim($pregunta).": ";
            }
            if ($pexcelente<>0) {
                $mensaje .= "Excelente ".number_format($pexcelente,2,',','.')."%";
                if (($pbueno+$pregular+$pdeficiente+$pnoopina)<>0) {
                    $mensaje .= " - ";
                } else {
                    $mensaje .= "<br/><br/>";
                }
            }
            if ($pbueno<>0) {
                $mensaje .= "Bueno ".number_format($pbueno,2,',','.')."%";
                if (($pregular+$pdeficiente+$pnoopina)<>0) {
                    $mensaje .= " - ";
                } else {
                    $mensaje .= "<br/><br/>";
                }
            }
            if ($regular<>0) {
                $mensaje .= "Regular ".number_format($pregular,2,',','.')."%";
                if (($pdeficiente+$pnoopina)<>0) {
                    $mensaje .= " - ";
                } else {
                    $mensaje .= "<br/><br/>";
                }
            }
            if ($deficiente<>0) {
                $mensaje .= "Deficiente ".number_format($pdeficiente,2,',','.')."%";
                if (($pnoopina)<>0) {
                    $mensaje .= " - ";
                } else {
                    $mensaje .= "<br/><br/>";
                }
            }
            if ($noopina<>0) {
                $mensaje .= " No opina ".number_format($pnoopina,2,',','.')."%"."<br/><br/>";
            }
            break;
    }
}
$quer1 = "SELECT * FROM detalle_encuesta where ide=" . $ide. " and tiporespuesta='desarrollo' order by orden";
$resul1 = mysqli_query($link, $quer1);
while ($ro1 = mysqli_fetch_array($resul1)) {
    $orden = $ro1["orden"];
    $pregunta = $ro1["pregunta"];
    $mensaje .= "Pregunta ".$orden." - ".trim($pregunta).":<br/>";
    $query = "select descrespuesta3 from resultado_encuesta where idd=".$orden;
    $result = mysqli_query($link,$query);
    while ($row = mysqli_fetch_array($result)) {
        $descrespuesta3 = $row["descrespuesta3"];
        if ($descrespuesta3!='') {
            $mensaje .= "- ".utf8_decode($descrespuesta3)."<br/>";
        }
    }
}

echo $mensaje;
$cabeceras = 'Content-type: text/html;';
mail("soluciones2000@gmail.com",$asunto,$mensaje,$cabeceras);
*/
////////////////////////////////////////////////////////////////////////////////////////////
// A partir de acá es el código para anular ordenes de shopify que tengan más de 48 horas //
////////////////////////////////////////////////////////////////////////////////////////////
$url = $urlOrdenesPendientes;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

if (isset($result)) {
    $ordenes=json_decode($result,true);

    // Fecha borrar
    $dia = date('Y-m-d');
    $fechaborrar = strtotime('-3 days', strtotime($dia));
    $fechaborrar = date('Y-m-d', $fechaborrar);

    foreach ($ordenes["orders"] as $lista => $orden) {
        // Fecha de orden
        $fechaorden = substr($orden["created_at"],0,10);
        if ($fechaorden <= $fechaborrar && $orden["cancelled_at"]==null) {
            $ordenid = $orden["id"];

            echo '$ordenid '.$ordenid.'<br/>'.'<br/>';

            $ur2 = $urlUnaOrden.$ordenid.'/cancel.json';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$ur2 );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            curl_close($ch);
        } else {
            echo '<br/>';
        }
    }
}

/////////////////////////////////////////////////////////////////////
// A partir de acá es el código para actualizar las tablas de zoom //
/////////////////////////////////////////////////////////////////////
$url = "http://sandbox.grupozoom.com/baaszoom/public/canguroazul/getCiudades?filtro=nacional";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
curl_setopt($ch,CURLOPT_HEADER, false);

$result=curl_exec($ch);

curl_close($ch);

$aux=json_decode($result,true);
foreach ($aux["entidadRespuesta"] as $key => $value) {
    $codciudad = $value["codciudad"];
    $nombre_ciudad = $value["nombre_ciudad"];
    $nombre_estado = $value["nombre_estado"];
    $query = 'select * from zoom_ciudades where codciudad='.$value["codciudad"];
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        $codciudad2 = $row["codciudad"];
        $nombre_ciudad2 = $row["nombre_ciudad"];
        $nombre_estado2 = $row["nombre_estado"];
    } else {
        $quer11 = 'INSERT INTO zoom_ciudades (codciudad, nombre_ciudad, nombre_estado) VALUES ('.$codciudad.',"'.$nombre_ciudad.'","'.$nombre_estado.'")';
        echo 'ciudad '.$codciudad.'<br/>';
        $resul11 = mysqli_query($link, $quer11);
    }    
    /////////////////////
    $ur2 = "http://sandbox.grupozoom.com/baaszoom/public/canguroazul/getMunicipios?codciudad=".$codciudad;

    $c2 = curl_init();
    curl_setopt($c2, CURLOPT_URL,$ur2 );
    curl_setopt($c2, CURLOPT_POST, false);
    curl_setopt($c2,CURLOPT_RETURNTRANSFER,true);// set optional params
    curl_setopt($c2,CURLOPT_HEADER, false);

    $resul2=curl_exec($c2);

    curl_close($c2);

    $au2=json_decode($resul2,true);
    foreach ($au2["entidadRespuesta"] as $key => $value) {
        $codigo_municipio = $value["codigo_municipio"];
        $nombre_municipio = $value["nombre_municipio"];
        $quer2 = 'select * from zoom_municipios where codigo_municipio="'.$value["codigo_municipio"].'"';
        $resul2 = mysqli_query($link, $quer2);
        if ($ro2 = mysqli_fetch_array($resul2)) {
            $codigo_municipio2 = $ro2["codigo_municipio"];
            $nombre_municipio2 = $ro2["nombre_municipio"];
        } else {
            $quer21 = 'INSERT INTO zoom_municipios (codciudad, codigo_municipio, nombre_municipio) VALUES ('.$codciudad.',"'.$codigo_municipio.'","'.$nombre_municipio.'")';
            echo 'ciudad '.$codciudad.' municipio '.$codigo_municipio.'<br/>';
            $resul21 = mysqli_query($link, $quer21);
        }
        /////////////////////
        $ur3 = "http://sandbox.grupozoom.com/baaszoom/public/canguroazul/getParroquias?codciudad=".$codciudad."&codmunicipio=".$codigo_municipio;

        $c3 = curl_init();
        curl_setopt($c3, CURLOPT_URL,$ur3 );
        curl_setopt($c3, CURLOPT_POST, false);
        curl_setopt($c3,CURLOPT_RETURNTRANSFER,true);// set optional params
        curl_setopt($c3,CURLOPT_HEADER, false);

        $resul3=curl_exec($c3);

        curl_close($c3);

        $au3=json_decode($resul3,true);
        foreach ($au3["entidadRespuesta"] as $key => $value) {
            $codigo_parroquia = $value["codigo_parroquia"];
            $nombre_parroquia = $value["nombre_parroquia"];
            $codigo_postal    = $value["codigo_postal"];
            $quer3 = 'select * from zoom_parroquias where codigo_parroquia="'.$codigo_parroquia.'"';
            $resul3 = mysqli_query($link, $quer3);
            if ($ro3 = mysqli_fetch_array($resul3)) {
                $nombre_parroquia2 = $ro3["nombre_parroquia"];
                $codigo_postal2    = $ro3["codigo_postal"];
            } else {
                $quer31 = 'INSERT INTO zoom_parroquias (codciudad, codmunicipio, codigo_parroquia, nombre_parroquia, codigo_postal) VALUES ('.$codciudad.',"'.$codigo_municipio.'","'.$codigo_parroquia.'","'.$nombre_parroquia.'","'.$codigo_postal.'")';
                echo 'ciudad '.$codciudad.' municipio '.$codigo_municipio.' parroquia '.$codigo_parroquia.'<br/><br/>';
                $resul31 = mysqli_query($link, $quer31);
            }
        }
    }
}
?>
