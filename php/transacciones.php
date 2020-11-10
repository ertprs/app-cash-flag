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
echo 'Fecha '.substr($fech1,8,2).'/'.substr($fech1,5,2).'/'.substr($fech1,0,4).'<br/>';
echo 'Cantidad de socios a la fecha '.$rox["socios"].'<br/>';

$querx = "SELECT count(id) as cantidad FROM pdv_transacciones";
$resulx = mysqli_query($link, $querx);
$rox = mysqli_fetch_array($resulx);
echo 'Cantidad de transacciones en punto de ventas a la fecha '.$rox["cantidad"].'<br/>';

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
$query = "SELECT moneda,id_proveedor,proveedores.nombre,sum(pdv_transacciones.monto) as total FROM pdv_transacciones inner join proveedores on pdv_transacciones.id_proveedor=proveedores.id where fecha = '".$fech2."' and tipo='01' order by moneda,nombre";
$result = mysqli_query($link, $query);
$cuerpo = '';
while ($row = mysqli_fetch_array($result)) {
    $cuerpo .= '<tr>';
        $cuerpo .= '<td>';
            $cuerpo .= $row["moneda"];
        $cuerpo .= '</td>';
        $cuerpo .= '<td>';
            $cuerpo .= $row["id_proveedor"]." - ".$row["nombre"];
        $cuerpo .= '</td>';
        $cuerpo .= '<td>';
            $cuerpo .= $row["total"];
        $cuerpo .= '</td>';
    $cuerpo .= '</tr>';
}

$asunto = 'Resumen de consumos en pdv para liquidaciones, datos del ';
// $asunto .= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
$asunto .= substr($fech2,8,2).'/'.substr($fech2,5,2).'/'.substr($fech2,0,4);
$asunto .= ' (sólo transacción 01 - Consumo)';
// $asunto .= ' hasta el ';
// $asunto .= substr($fech1,8,2).'/'.substr($fech1,5,2).'/'.substr($fech1,0,4);

$texto = '<p><u>'.$asunto.'</u></p>';
$texto .= '<table border="1">';
    $texto .= '<thead>';
        $texto .= '<tr>';
            $texto .= '<th>';
                $texto .= 'Moneda';
            $texto .= '</th>';
            $texto .= '<th>';
                $texto .= 'Proveedor';
            $texto .= '</th>';
            $texto .= '<th>';
                $texto .= 'Total';
            $texto .= '</th>';
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
$tabla = 'pdv_transacciones';
$campos = array();
$tipos = array();
$quer2 = "select * from information_schema.columns where table_schema='".$database."' and table_name='".$tabla."'";
$resul2 = mysqli_query($link,$quer2);
while($row = mysqli_fetch_array($resul2)) {
    if($row["COLUMN_NAME"]<>"secretkey") {
        $indice = $row["COLUMN_NAME"];
        $campos[] = $indice;
        $x = $row["DATA_TYPE"];
        $tipos[] = $x;
    }
}

// $query = "SELECT * FROM ".$tabla." where fecha >= '".$fech2."' and fecha <= '".$fech1."' order by tipo,moneda,id_proveedor";
$query = "SELECT * FROM ".$tabla." where fecha = '".$fech2."' order by tipo,moneda,id_proveedor";
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
// $asunto .= substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
$asunto .= substr($fech2,8,2).'/'.substr($fech2,5,2).'/'.substr($fech2,0,4);
// $asunto .= ' hasta el ';
// $asunto .= substr($fech1,8,2).'/'.substr($fech1,5,2).'/'.substr($fech1,0,4);

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
$tabla = 'socios';
$campos = array();
$tipos = array();
$quer2 = "select * from information_schema.columns where table_schema='".$database."' and table_name='".$tabla."'";
$resul2 = mysqli_query($link,$quer2);
while($row = mysqli_fetch_array($resul2)) {
    if($row["COLUMN_NAME"]<>"secretkey") {
        $indice = $row["COLUMN_NAME"];
        $campos[] = $indice;
        $x = $row["DATA_TYPE"];
        $tipos[] = $x;
    }
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

$asunto = 'Tabla: '.$tabla.', datos al ';
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
