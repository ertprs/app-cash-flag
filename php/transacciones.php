<?php 
include_once("../_config/conexion.php");
include_once("funciones.php");

$fecha = date("Y")."-".date("m")."-".sprintf("%'02d",(date("d")-1));
// $fecha = date("Y")."-".date("m")."-".sprintf("%'02d",date("d"));

$fech1 = date('Y-m-d');
$fech2 = strtotime('-1 day', strtotime ($fech1));
$fech2 = date ('Y-m-d', $fech2);

$fech3 = strtotime('-3 day', strtotime ($fech1));
$fech3 = date ('Y-m-d', $fech3);

///////////////////////////////////////////////////////////////////////////////////
$querx = "SELECT count(id) as socios FROM socios";
$resulx = mysqli_query($link, $querx);
$rox = mysqli_fetch_array($resulx);
echo 'Fecha '.$fech1.'<br/>';
echo 'Cantidad de socios a la fecha '.$rox["socios"].'<br/>';

$querx = "SELECT count(id) as cantidad FROM prepago_transacciones";
$resulx = mysqli_query($link, $querx);
$rox = mysqli_fetch_array($resulx);
echo 'Cantidad de transacciones de prepago a la fecha '.$rox["cantidad"].'<br/>';

$querx = "SELECT count(id) as cantidad FROM giftcards_transacciones";
$resulx = mysqli_query($link, $querx);
$rox = mysqli_fetch_array($resulx);
echo 'Cantidad de transacciones de giftcards a la fecha '.$rox["cantidad"].'<br/>';

$querx = "SELECT count(id) as cantidad FROM cupones";
$resulx = mysqli_query($link, $querx);
$rox = mysqli_fetch_array($resulx);
echo 'Cantidad de cupones a la fecha '.$rox["cantidad"].'<br/>';
///////////////////////////////////////////////////////////////////////////////////
echo '<br/><br/>';
///////////////////////////////////////////////////////////////////////////////////
$tabla = 'socios';
$campos = array();
$tipos = array();
$quer2 = "select * from information_schema.columns where table_schema='".$database."' and table_name='".$tabla."'";
$resul2 = mysqli_query($link,$quer2);
while($row = mysqli_fetch_array($resul2)) {
    $indice = $row["COLUMN_NAME"];
    $campos[] = $indice;
    $x = $row["DATA_TYPE"];
    $tipos[] = $x;
}

$query = "SELECT * FROM ".$tabla;
$result = mysqli_query($link, $query);
$cuerpo = '';
while ($row = mysqli_fetch_array($result)) {
    $cuerpo .= '<tr>';
        foreach ($campos as $key => $value) {
            $cuerpo .= '<td>';
                $cuerpo .= utf8_decode($row[$key]);
            $cuerpo .= '</td>';
        }
    $cuerpo .= '</tr>';
}

$asunto = 'Tabla: '.$tabla.', datos del ';
$asunto .= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);

$texto = '<p><u>'.$asunto.'</u></p>';
$texto .= '<table border="1">';
    $texto .= '<thead>';
        $texto .= '<tr>';
            foreach ($campos as $key => $value) {
                $texto .= '<th>';
                    $texto .= $value;
                $texto .= '</th>';
            }
        $texto .= '</tr>';
    $texto .= '</thead>';
    $texto .= '<tbody>';
        $texto .= $cuerpo;
    $texto .= '</tbody>';
$texto .= '</table>';

echo $texto;
///////////////////////////////////////////////////////////////////////////////////
echo '<br/><br/>';
///////////////////////////////////////////////////////////////////////////////////
$tabla = 'prepago_transacciones';
$campos = array();
$tipos = array();
$quer2 = "select * from information_schema.columns where table_schema='".$database."' and table_name='".$tabla."'";
$resul2 = mysqli_query($link,$quer2);
while($row = mysqli_fetch_array($resul2)) {
    $indice = $row["COLUMN_NAME"];
    $campos[] = $indice;
    $x = $row["DATA_TYPE"];
    $tipos[] = $x;
}

$query = "SELECT * FROM ".$tabla." where fecha >= '".$fech2."' and fecha <= '".$fech1."'";
$result = mysqli_query($link, $query);
$cuerpo = '';
while ($row = mysqli_fetch_array($result)) {
    $cuerpo .= '<tr>';
        foreach ($campos as $key => $value) {
            $cuerpo .= '<td>';
                $cuerpo .= utf8_decode($row[$key]);
            $cuerpo .= '</td>';
        }
    $cuerpo .= '</tr>';
}

$asunto = 'Tabla: '.$tabla.', datos desde el ';
// $asunto .= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
$asunto .= substr($fech2,8,2).'/'.substr($fech2,5,2).'/'.substr($fech2,0,4);
$asunto .= ' hasta el ';
$asunto .= substr($fech1,8,2).'/'.substr($fech1,5,2).'/'.substr($fech1,0,4);

$texto = '<p><u>'.$asunto.'</u></p>';
$texto .= '<table border="1">';
    $texto .= '<thead>';
        $texto .= '<tr>';
            foreach ($campos as $key => $value) {
                $texto .= '<th>';
                    $texto .= $value;
                $texto .= '</th>';
            }
        $texto .= '</tr>';
    $texto .= '</thead>';
    $texto .= '<tbody>';
        $texto .= $cuerpo;
    $texto .= '</tbody>';
$texto .= '</table>';

echo $texto;
///////////////////////////////////////////////////////////////////////////////////
echo '<br/><br/>';
///////////////////////////////////////////////////////////////////////////////////
$tabla = 'giftcards_transacciones';
$campos = array();
$tipos = array();
$quer2 = "select * from information_schema.columns where table_schema='".$database."' and table_name='".$tabla."'";
$resul2 = mysqli_query($link,$quer2);
while($row = mysqli_fetch_array($resul2)) {
    $indice = $row["COLUMN_NAME"];
    $campos[] = $indice;
    $x = $row["DATA_TYPE"];
    $tipos[] = $x;
}

$query = "SELECT * FROM ".$tabla." where fecha >= '".$fech2."' and fecha <= '".$fech1."'";
$result = mysqli_query($link, $query);
$cuerpo = '';
while ($row = mysqli_fetch_array($result)) {
    $cuerpo .= '<tr>';
        foreach ($campos as $key => $value) {
            $cuerpo .= '<td>';
                $cuerpo .= utf8_decode($row[$key]);
            $cuerpo .= '</td>';
        }
    $cuerpo .= '</tr>';
}

$asunto = 'Tabla: '.$tabla.', datos desde el ';
// $asunto .= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
$asunto .= substr($fech2,8,2).'/'.substr($fech2,5,2).'/'.substr($fech2,0,4);
$asunto .= ' hasta el ';
$asunto .= substr($fech1,8,2).'/'.substr($fech1,5,2).'/'.substr($fech1,0,4);

$texto = '<p><u>'.$asunto.'</u></p>';
$texto .= '<table border="1">';
    $texto .= '<thead>';
        $texto .= '<tr>';
            foreach ($campos as $key => $value) {
                $texto .= '<th>';
                    $texto .= $value;
                $texto .= '</th>';
            }
        $texto .= '</tr>';
    $texto .= '</thead>';
    $texto .= '<tbody>';
        $texto .= $cuerpo;
    $texto .= '</tbody>';
$texto .= '</table>';

echo $texto;
///////////////////////////////////////////////////////////////////////////////////
echo '<br/><br/>';
///////////////////////////////////////////////////////////////////////////////////
$tabla = 'cupones';
$campos = array();
$tipos = array();
$quer2 = "select * from information_schema.columns where table_schema='".$database."' and table_name='".$tabla."'";
$resul2 = mysqli_query($link,$quer2);
while($row = mysqli_fetch_array($resul2)) {
    $indice = $row["COLUMN_NAME"];
    $campos[] = $indice;
    $x = $row["DATA_TYPE"];
    $tipos[] = $x;
}

$query = "SELECT * FROM ".$tabla." where fechacupon >= '".$fech2."' and fechacupon <= '".$fech1."'";
$result = mysqli_query($link, $query);
$cuerpo = '';
while ($row = mysqli_fetch_array($result)) {
    $cuerpo .= '<tr>';
        foreach ($campos as $key => $value) {
            $cuerpo .= '<td>';
                $cuerpo .= utf8_decode($row[$key]);
            $cuerpo .= '</td>';
        }
    $cuerpo .= '</tr>';
}

$asunto = 'Tabla: '.$tabla.', datos desde el ';
// $asunto .= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
$asunto .= substr($fech2,8,2).'/'.substr($fech2,5,2).'/'.substr($fech2,0,4);
$asunto .= ' hasta el ';
$asunto .= substr($fech1,8,2).'/'.substr($fech1,5,2).'/'.substr($fech1,0,4);

$texto = '<p><u>'.$asunto.'</u></p>';
$texto .= '<table border="1">';
    $texto .= '<thead>';
        $texto .= '<tr>';
            foreach ($campos as $key => $value) {
                $texto .= '<th>';
                    $texto .= $value;
                $texto .= '</th>';
            }
        $texto .= '</tr>';
    $texto .= '</thead>';
    $texto .= '<tbody>';
        $texto .= $cuerpo;
    $texto .= '</tbody>';
$texto .= '</table>';

echo $texto;

?>
