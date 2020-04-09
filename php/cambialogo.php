<?php
include_once("../_config/conexion.php");

$dest_path = '../img/sin_imagen.jpg';

if(isset($_FILES)) {
    foreach ($_FILES as $Campo => $vAdjunto) {
        if($vAdjunto['error'] === UPLOAD_ERR_OK) {
            // obtener detalles del archivo
            $fileTmpPath = $vAdjunto['tmp_name'];
            $fileName = $vAdjunto['name'];
            $fileSize = $vAdjunto['size'];
            $fileType = $vAdjunto['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $newFileName = $_POST["comercio"].'.'.$fileExtension;

            // // Sólo acepta los archivos con las siguientes extensiones
            // $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');
            // if(in_array($fileExtension, $allowedfileExtensions)) {
            //     // ...
            // }
            // // Directorio al cual será copiado el archivo
            $uploadFileDir = '../img/';
            $dest_path = $uploadFileDir . trim($newFileName);

            if (is_uploaded_file($fileTmpPath)) {
                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    $respuesta ='File is successfully uploaded.';
                    $query = 'update proveedores set logo="'.trim($newFileName).'", logocard="'.trim($newFileName).'" where id='.$_POST["comercio"];
                    $result = mysqli_query($link, $query);
                } else {
                    $respuesta = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                }
            } else {
                $respuesta = 'error2';
            }
        }
    }
} else {
    $respuesta = 'error files';
}

echo '
    <script>
        window.location.replace("'.$_POST["ruta"].'&exito=S&logo='.trim($dest_path).'");
    </script>
    ';

?>
