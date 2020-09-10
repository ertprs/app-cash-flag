<?php 
header('Content-Type: application/json');
include_once("../_config/conexion.php");

if ($_GET["tipo"]=='comercio') {
    $query = 'SELECT * FROM proveedores where email="'.$_GET["email"].'"';
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        if ($row["status"]==1) {
            $valido = true;
            $logo = ($row['logo']<>'') ? $row['logo'] : 'sin_imagen.jpg' ;
            $nombreprov = utf8_encode($row["nombre"]);
            $id = $row['id'];
        } else {
            $valido = false;
            $logo = 'sin_imagen.jpg';
        }
        if ($valido) {
            $quer2 = 'SELECT * FROM usuarios where email="'.$_GET["email"].'"';
            $resul2 = mysqli_query($link, $quer2);
            if ($ro2 = mysqli_fetch_array($resul2)) {
                if ($ro2["tipo"]==$_GET["tipo"] or $ro2["tipo"]=="ambos") {
                    $respuesta = '{"exito":"SI",';
                    $respuesta .= '"id":'.$id.',';
                    $respuesta .= '"logo":"'.$logo.'",';
                    $respuesta .= '"nombreprov":"'.$nombreprov.'",';
                    $respuesta .= '"hashp":"'. $ro2["hashp"] .'",';
                    $respuesta .= '"pregunta":"' . utf8_encode($ro2["pregunta"]) . '",';
                    $respuesta .= '"hashr":"' . $ro2["hashr"] . '",';
                    $respuesta .= '"mensaje":"exito"}';
                } else {
                    $respuesta = '{"exito":"NO",';
                    $respuesta .= '"id":'.$id.',';
                    $respuesta .= '"logo":"'.$logo.'",';
                    $respuesta .= '"nombreprov":"'.$nombreprov.'",';
                    $respuesta .= '"hashp":"",';
                    $respuesta .= '"pregunta":"",';
                    $respuesta .= '"hashr":"",';
                    $respuesta .= '"mensaje":"error de tipo"}';
                }
            } else {
                $respuesta = '{"exito":"NO",';
                $respuesta .= '"id":'.$id.',';
                $respuesta .= '"logo":"'.$logo.'",';
                $respuesta .= '"nombreprov":"'.$nombreprov.'",';
                $respuesta .= '"hashp":"",';
                $respuesta .= '"pregunta":"",';
                $respuesta .= '"hashr":"",';
                $respuesta .= '"mensaje":"correo no registrado"}';                
            }
        } else {
            $respuesta = '{"exito":"NO",';
            $respuesta .= '"id":'.$id.',';
            $respuesta .= '"logo":"'.$logo.'",';
            $respuesta .= '"nombreprov":"'.$nombreprov.'",';
            $respuesta .= '"hashp":"",';
            $respuesta .= '"pregunta":"",';
            $respuesta .= '"hashr":"",';
            $respuesta .= '"mensaje":"error de status"}';       
        }
    } else {
        $quer2 = 'SELECT * FROM socios where email="'.$_GET["email"].'"';
        $resul2 = mysqli_query($link, $quer2);
        if ($ro2 = mysqli_fetch_array($resul2)) {
            $respuesta = '{"exito":"NO",';
            $respuesta .= '"id":'.$ro2["id"].',';
            $respuesta .= '"logo":"",';
            $respuesta .= '"nombreprov":"",';
            $respuesta .= '"hashp":"",';
            $respuesta .= '"pregunta":"",';
            $respuesta .= '"hashr":"",';
            $respuesta .= '"mensaje":"error de tipo"}';
        } else {
            $respuesta = '{"exito":"NO",';
            $respuesta .= '"id":0,';
            $respuesta .= '"logo":"",';
            $respuesta .= '"nombreprov":"",';
            $respuesta .= '"hashp":"",';
            $respuesta .= '"pregunta":"",';
            $respuesta .= '"hashr":"",';
            $respuesta .= '"mensaje":"usuario no registrado"}';
        }
    }
} else {
    $query = 'SELECT * FROM socios where email="'.$_GET["email"].'"';
    $result = mysqli_query($link, $query);
    if ($row = mysqli_fetch_array($result)) {
        // if ($row["status"]=="Activo") {
            $valido = true;
            $id = $row['id'];
        // } else {
        //     $valido = false;
        // }
        if ($valido) {
            $quer2 = 'SELECT * FROM usuarios where email="'.$_GET["email"].'"';
            $resul2 = mysqli_query($link, $quer2);
            if ($ro2 = mysqli_fetch_array($resul2)) {
                if ($ro2["tipo"]==$_GET["tipo"] or $ro2["tipo"]=="ambos") {
                    $respuesta = '{"exito":"SI",';
                    $respuesta .= '"id":'.$id.',';
                    $respuesta .= '"nombre":"'.$row["nombres"]." ".$row["apellidos"].'",';
                    $respuesta .= '"hashp":"'. $ro2["hashp"] .'",';
                    $respuesta .= '"pregunta":"' . utf8_encode($ro2["pregunta"]) . '",';
                    $respuesta .= '"hashr":"' . $ro2["hashr"] . '",';
                    $respuesta .= '"mensaje":"exito"}';
                } else {
                    $respuesta = '{"exito":"NO",';
                    $respuesta .= '"id":'.$id.',';
                    $respuesta .= '"nombre":"'.$row["nombres"]." ".$row["apellidos"].'",';
                    $respuesta .= '"hashp":"",';
                    $respuesta .= '"pregunta":"",';
                    $respuesta .= '"hashr":"",';
                    $respuesta .= '"mensaje":"error de tipo"}';
                }
            } else {
                $respuesta = '{"exito":"NO",';
                $respuesta .= '"id":'.$id.',';
                $respuesta .= '"nombre":"'.$row["nombres"]." ".$row["apellidos"].'",';
                $respuesta .= '"hashp":"",';
                $respuesta .= '"pregunta":"",';
                $respuesta .= '"hashr":"",';
                $respuesta .= '"mensaje":"correo no registrado"}';                
            }
        } else {
            $respuesta = '{"exito":"NO",';
            $respuesta .= '"id":'.$id.',';
            $respuesta .= '"nombre":"'.$row["nombres"]." ".$row["apellidos"].'",';
            $respuesta .= '"hashp":"",';
            $respuesta .= '"pregunta":"",';
            $respuesta .= '"hashr":"",';
            $respuesta .= '"mensaje":"error de status"}';       
        }
    } else {
        $quer2 = 'SELECT * FROM proveedores where email="'.$_GET["email"].'"';
        $resul2 = mysqli_query($link, $quer2);
        if ($ro2 = mysqli_fetch_array($resul2)) {
            $respuesta = '{"exito":"NO",';
            $respuesta .= '"id":'.$ro2["id"].',';
            $respuesta .= '"nombre":"",';
            $respuesta .= '"hashp":"",';
            $respuesta .= '"pregunta":"",';
            $respuesta .= '"hashr":"",';
            $respuesta .= '"mensaje":"error de tipo"}';
        } else {
            $respuesta = '{"exito":"NO",';
            $respuesta .= '"id":0,';
            $respuesta .= '"logo":"",';
            $respuesta .= '"nombre":"",';
            $respuesta .= '"hashp":"",';
            $respuesta .= '"pregunta":"",';
            $respuesta .= '"hashr":"",';
            $respuesta .= '"mensaje":"usuario no registrado"}';
        }
    }
}
echo $respuesta;
?>
