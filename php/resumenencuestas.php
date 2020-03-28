<?php 
header('Access-Control-Allow-Origin: *');
include_once("../_config/conexion.php");
include_once("../_config/configShopify.php");
include_once("funciones.php");
set_time_limit(3600);

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

// /////////////////////////////////////////////////////////////////////
// // A partir de acá es el código para actualizar las tablas de zoom //
// /////////////////////////////////////////////////////////////////////
// $url = "http://sandbox.grupozoom.com/baaszoom/public/canguroazul/getCiudades?filtro=nacional";

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,$url );
// curl_setopt($ch, CURLOPT_POST, false);
// curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);// set optional params
// curl_setopt($ch,CURLOPT_HEADER, false);

// $result=curl_exec($ch);

// curl_close($ch);

// $aCiudades = array();
// $aMunicipios = array();
// $aParroquias = array();

// $aux=json_decode($result,true);
// foreach ($aux["entidadRespuesta"] as $key => $value) {
//     $codciudad = $value["codciudad"];
//     $nombre_ciudad = $value["nombre_ciudad"];
//     $nombre_estado = $value["nombre_estado"];
//     $query = 'select * from zoom_ciudades where codciudad='.$value["codciudad"];
//     $result = mysqli_query($link, $query);
//     if ($row = mysqli_fetch_array($result)) {
//         $codciudad2 = $row["codciudad"];
//         $nombre_ciudad2 = $row["nombre_ciudad"];
//         $nombre_estado2 = $row["nombre_estado"];
//     } else {
//         $aCiudades[] = array(
//             'codciudad' => $codciudad,
//             'nombre_ciudad' => $nombre_ciudad,
//             'nombre_estado' => $nombre_estado
//         );
//         echo 'ciudad '.$codciudad.'<br/>';
//     }    
//     /////////////////////
//     $ur2 = "http://sandbox.grupozoom.com/baaszoom/public/canguroazul/getMunicipios?codciudad=".$codciudad;

//     $c2 = curl_init();
//     curl_setopt($c2, CURLOPT_URL,$ur2 );
//     curl_setopt($c2, CURLOPT_POST, false);
//     curl_setopt($c2,CURLOPT_RETURNTRANSFER,true);// set optional params
//     curl_setopt($c2,CURLOPT_HEADER, false);

//     $resul2=curl_exec($c2);

//     curl_close($c2);

//     $au2=json_decode($resul2,true);
//     foreach ($au2["entidadRespuesta"] as $key => $value) {
//         $codigo_municipio = $value["codigo_municipio"];
//         $nombre_municipio = $value["nombre_municipio"];
//         $quer2 = 'select * from zoom_municipios where codigo_municipio="'.$value["codigo_municipio"].'"';
//         $resul2 = mysqli_query($link, $quer2);
//         if ($ro2 = mysqli_fetch_array($resul2)) {
//             $codigo_municipio2 = $ro2["codigo_municipio"];
//             $nombre_municipio2 = $ro2["nombre_municipio"];
//         } else {
//             $aMunicipios[] = array(
//                 'codciudad' => $codciudad,
//                 'codigo_municipio' => $codigo_municipio,
//                 'nombre_municipio' => $nombre_municipio
//             );
//             echo 'ciudad '.$codciudad.' municipio '.$codigo_municipio.'<br/>';
//         }
//         /////////////////////
//         $ur3 = "http://sandbox.grupozoom.com/baaszoom/public/canguroazul/getParroquias?codciudad=".$codciudad."&codmunicipio=".$codigo_municipio;

//         $c3 = curl_init();
//         curl_setopt($c3, CURLOPT_URL,$ur3 );
//         curl_setopt($c3, CURLOPT_POST, false);
//         curl_setopt($c3,CURLOPT_RETURNTRANSFER,true);// set optional params
//         curl_setopt($c3,CURLOPT_HEADER, false);

//         $resul3=curl_exec($c3);

//         curl_close($c3);

//         $au3=json_decode($resul3,true);
//         foreach ($au3["entidadRespuesta"] as $key => $value) {
//             $codigo_parroquia = $value["codigo_parroquia"];
//             $nombre_parroquia = $value["nombre_parroquia"];
//             $codigo_postal    = $value["codigo_postal"];
//             $quer3 = 'select * from zoom_parroquias where codigo_parroquia="'.$codigo_parroquia.'"';
//             $resul3 = mysqli_query($link, $quer3);
//             if ($ro3 = mysqli_fetch_array($resul3)) {
//                 $nombre_parroquia2 = $ro3["nombre_parroquia"];
//                 $codigo_postal2    = $ro3["codigo_postal"];
//             } else {
//                 $aParroquias[] = array(
//                     'codciudad' => $codciudad,
//                     'codmunicipio' => $codigo_municipio,
//                     'codigo_parroquia' => $codigo_parroquia,
//                     'nombre_parroquia' => $nombre_parroquia,
//                     'codigo_postal' => $codigo_postal
//                 );
//                 echo 'ciudad '.$codciudad.' municipio '.$codigo_municipio.' parroquia '.$codigo_parroquia.'<br/><br/>';
//             }
//         }
//     }
// }

// echo '-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-<br/>';
// foreach ($aCiudades as $key => $value) {
//     echo $key.' - '.$value.'<br/>';
// }
// echo '<br/>';
// foreach ($aMunicipios as $key => $value) {
//     echo $key.' - '.$value.'<br/>';
// }
// echo '<br/>';
// foreach ($aParroquias as $key => $value) {
//     echo $key.' - '.$value.'<br/>';
// }
?>
