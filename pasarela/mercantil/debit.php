<?php

$amount = $_GET["monto"];

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, scalable=yes"> -->
    <title>Cash-Flag</title>
    <link rel="stylesheet" href="../../prepago/compra.css">
  </head>
<body>
  <div id="container">
      <div class="logo" align="center">
          <img class="img-logo" id="logo" src="../img/logoclub.png" alt="">
      </div>
      <h2 style="text-align: center; color: black;">Cash-Flag</h2>
      <h3 id="nombre" align="center"></h3>
      <h3 align="center">Recargar tarjetas prepagadas - Llave Mercantil</h3>
      <div id="div1" align="center">
          <p style="text-align: left; padding: 0.5em 0 0.5em 0.5em;"><b><u>Datos de la tarjeta</u></b></p>
          <!-- Número de tarjeta -->
          <div id="div1" class="cmps">
              <span class="etiq">Número de tarjeta:</span>
              <input id="number" class="campo" type="text" size="20" maxlength="18" title="Debe introducir sólo números" style="text-align: left;"/>
          </div>
          <!-- Nombre -->
          <div id="div2" class="cmps">
              <span class="etiq">Nombre de Titular:</span>
              <input id="holder_name" class="campo" type="text" size="20" maxlength="18" title="Debe introducir sólo números" style="text-align: left;"/>
          </div>
          <!-- Identificación de Titular -->
          <div id="div2" class="cmps">
              <span class="etiq">Identificación de Titular:</span>
              <input id="holder_id" class="campo" type="text" size="20" maxlength="18" title="Debe introducir Identificación de Titular" style="text-align: left;"/>
          </div>
          <!-- Código de validación -->
          <div id="div3" class="cmps">
              <span class="etiq">Código de validación - CVV:</span>
              <input id="cvv" class="campo" type="password" size="3" maxlength="3" title="Debe introducir sólo números" style="text-align: left;"/>
          </div>
          <!-- Monto -->
          <div id="div7" class="cmps">
              <span class="etiq">Monto:</span>
              <input id="amount" class="campo" type="text" size="30" maxlength="12" title="Debe introducir sólo números o el . como separador decimal" style="text-align: right;" value="<?php echo $amount; ?>" />
          </div>
          <!-- Factor de autenticación -->
          <div id="div3" class="cmps" style="display: none;">
              <span class="etiq">Clave de operaciones internet:</span>
              <input id="twofactor" class="campo" type="password" size="20" maxlength="20" title="Debe introducir clave de operaciones internet" style="text-align: left;"/>
          </div>
          <!-- Linea de botones -->
          <div class="btns">
              <button id="btn_auth" onclick="getAuth()" style="width: 8em;">Solicitar Validación</button>
              <button id="btn_proccess" onclick="getPay()" style="width: 8em;display: none;">Procesar Pago</button>
              <button id="limpiar" onclick="limpiar()" style="width: 8em;">Limpiar</button>
          </div>
          <div class="btns">
              <button id="btnvolver" style="width: 10em; margin: 0.5em 0 0 0;">Volver</button>
          </div>
      </div>
  </div>
  <script type="text/javascript">
    var number = "";
    var holder_name = "";
    var holder_id = "";
    var cvv = "";
    var twofactor = "";
    var amount = "";
    var datos = new FormData();
    
    // limpia el formulario
      function limpiar() {
        monto = "";
        document.getElementById("number").value = "";
        document.getElementById("holder_name").value = "";
        document.getElementById("holder_id").value = "";
        document.getElementById("cvv").value = "";
        document.getElementById("twofactor").value = "";
        document.getElementById("amount").value = "";
        document.getElementById("btn_proccess").style.display = 'none';
      }

      // valida la entrada en los campos
      function isValid() {
        var continuar = true, vacios = 0, campo = "";
        if ((document.getElementById("number").value=="" || document.getElementById("number").value==undefined) && vacios == 0) {
          alert("El campo número de tarjeta no puede quedar en blanco");
          vacios++;
          campo = "number";
        }
        if ((document.getElementById("holder_name").value=="" || document.getElementById("holder_name").value==undefined) && vacios == 0) {
          alert("El campo Nombre de Titular no puede quedar en blanco");
          vacios++;
          campo = "holder_name";
        }
        if ((document.getElementById("holder_id").value=="" || document.getElementById("holder_id").value==undefined) && vacios == 0) {
          alert("El campo Identificación de Titular no puede quedar en blanco");
          vacios++;
          campo = "holder_id";
        }
        if ((document.getElementById("cvv").value=="" || document.getElementById("cvv").value==undefined) && vacios == 0) {
          alert("El campo Código de validación - CVV no puede quedar en blanco");
          vacios++;
          campo = "cvv";
        }
        // if ((document.getElementById("twofactor").value=="" || document.getElementById("twofactor").value==undefined) && vacios == 0) {
        //   alert("El campo Clave de operaciones no puede quedar en blanco");
        //   vacios++;
        //   campo = "twofactor";
        // }
        if ((document.getElementById("amount").value=="" || document.getElementById("amount").value==undefined) && vacios == 0) {
          alert("El campo monto no puede quedar en blanco");
          vacios++;
          campo = "amount";
        }

        if (vacios>0) { continuar = false; }
        if (continuar) { 
          number = document.getElementById("number").value;
          holder_name = document.getElementById("holder_name").value;
          holder_id = document.getElementById("holder_id").value;
          cvv = document.getElementById("cvv").value;
          twofactor = document.getElementById("twofactor").value;
          amount = document.getElementById("amount").value;
        } else {
          document.getElementById(campo).focus();
        }

        return continuar;
      }

      function getAuth() {
        var validation = isValid();
        if (!validation) {
          return;
        }
        console.log(number);

        datos.append("number", document.getElementById("number").value);
        datos.append("holder_name", document.getElementById("holder_name").value);
        datos.append("holder_id", document.getElementById("holder_id").value);
        datos.append("cvv", document.getElementById("cvv").value);
        datos.append("amount", document.getElementById("amount").value);

        console.log(datos);

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            respuesta = JSON.parse(this.responseText);
            if (respuesta.exito == 'SI') {
              alert(fmensaje(respuesta));
              limpiar()
            } else {
              alert(respuesta);
            }
          }
        };
        xmlhttp.open("POST", "./debit/getauth.php", false);
        xmlhttp.send(datos);
        console.log(respuesta);
        return respuesta;
      }
  </script>
</body>
</html>