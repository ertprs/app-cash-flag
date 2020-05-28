var ruta = s_paybtn.src.split("?");
var params = ruta[1].split("&");

var ok = params[0].substr(3,1000), nok = params[1].substr(4,1000);

// Función para mostrar los decimales que se quieran
var formatNumber = {
	separador: ".", // separador para los miles
	sepDecimal: ',', // separador para los decimales
	formatear: function (num) {
		num += '';
		var splitStr = num.split('.');
		var splitLeft = splitStr[0];
		var xright = splitStr[1] < 10 ? splitStr[1] * 10 : splitStr[1];
		var splitRight = splitStr.length > 1 ? this.sepDecimal + xright : this.sepDecimal + '00';
		var regx = /(\d+)(\d{3})/;
		while (regx.test(splitLeft)) {
			splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
		}
		return this.simbol + splitLeft + splitRight;
	},
	new: function (num, simbol) {
		this.simbol = simbol || '';
		return this.formatear(num);
	}
}

pasarela.addEventListener("click", function () { fpasarela(event) } );

function fpasarela(event) {
	event.preventDefault();

	// Contenedor
	dcon = document.createElement("div");
	dcon.style.position = "absolute";
	dcon.style.zIndex   = "50000";
	dcon.style.width    = "100%";
	dcon.style.height   = "100%";
	dcon.style.top      = "5em";

	// Ventana Modal
	dmod = document.createElement("div");
	dmod.style.textAlign     = "center";
	dmod.style.top           = "4.5em";
	// dmod.style.width         = "50%";
	// dmod.style.height        = "65%";

	dmod.style.width         = "500px";
	dmod.style.height        = "412px";

	dmod.style.border        = "solid 1px black";
	dmod.style.borderRadius  = "5%";
	dmod.style.zIndex        = "100000";
	dmod.style.padding       = "5px 5px 20px";
	dmod.style.margin        = "auto";
	dmod.style.background    = "white";
	dmod.style.display       = "flex";
	dmod.style.flexDirection = "column";
	dmod.style.flexWrap      = "wrap";

	// Logo
	// Marco
	dlog = document.createElement("div");
	dlog.style.margin = "0 auto";
	dlog.style.height = "auto";
	dlog.style.width = "100%";
	dlog.style.textAlign = "center";
	// Imagen
	ilog = document.createElement("img");
	ilog.src = "https://app.cash-flag.com/img/logoclub.png";
	// ilog.src = "../img/logoclub.png";
	ilog.style.width = "10em";
	ilog.style.height = "auto";
	ilog.style.objectFit = "cover";

	// Título
	ttit = document.createTextNode("Datos de la tarjeta de crédito");
	htit = document.createElement("h3");
	htit.style.margin = "0 auto 0.5em";
	htit.style.width  = "100%";
	htit.appendChild(ttit);
	htit.style.textAlign = "center";

	// formulario
	div1 = document.createElement("div");
	div1.style.width  = "100%";
	div1.style.height = "57%";
	div1.style.margin = "0 auto";
	div1.style.display = "flex";
	div1.style.flexFlow = "column";
	div1.style.justifyContent = "center";
	// Tarjeta 
	// Div
	dcrd = document.createElement("div");
	dcrd.style.padding       = "0.5em 0";
	dcrd.style.display       = "flex";
	dcrd.style.flexDirection = "row";
	// Etiqueta
	tet1 = document.createTextNode("Número de tarjeta");
	set1 = document.createElement("span");
	set1.appendChild(tet1);
    set1.style.width      = "50%";
    set1.style.height     = "20px";
    set1.style.textAlign  = "left";
    set1.style.marginLeft = "45px";
    // Input
	icrd                  = document.createElement("input");
	icrd.id               = "crd";
	icrd.type             = "text";
	icrd.size             = "50";
	icrd.maxLength        = "16";
	icrd.style.width      = "11em";
	icrd.style.textAlign  = "center";
	icrd.style.background = "lightgray";
	icrd.addEventListener("keyup", function(){ xNumero(this.id, this.value); });

	// Nombre 
	// Div
	dnom = document.createElement("div");
	dnom.style.padding       = "0.5em 0";
	dnom.style.display       = "flex";
	dnom.style.flexDirection = "row";
	// Etiqueta
	tet2 = document.createTextNode("Nombre tarjetahabiente");
	set2 = document.createElement("span");
	set2.appendChild(tet2);
    set2.style.width      = "50%";
    set2.style.height     = "20px";
    set2.style.textAlign  = "left";
    set2.style.marginLeft = "45px";
    // Input
	inom                  = document.createElement("input");
	inom.id               = "nom";
	inom.type             = "text";
	inom.size             = "50";
	inom.maxLength        = "16";
	inom.style.width      = "11em";
	inom.style.textAlign  = "center";
	inom.style.background = "lightgray";
	// inom.addEventListener("keyup", function(){ xNumero(this.id, this.value); });

	// Cédula de identidad 
	// Div
	dced                     = document.createElement("div");
	dced.style.padding       = "0.5em 0";
	dced.style.display       = "flex";
	dced.style.flexDirection = "row";
	// Etiqueta
	tet3 = document.createTextNode("Cédula de identidad");
	set3 = document.createElement("span");
	set3.appendChild(tet3);
    set3.style.width      = "50%";
    set3.style.height     = "20px";
    set3.style.textAlign  = "left";
    set3.style.marginLeft = "45px";
    // cedula
	iced                  = document.createElement("input");
	iced.id               = "ced";
	iced.type             = "text";
	iced.size             = "50";
	iced.maxLength        = "8";
	iced.style.width      = "7em";
	iced.style.textAlign  = "center";
	iced.style.background = "lightgray";
	iced.addEventListener("keyup", function(){ xNumero(this.id, this.value); });

	// Fecha de vencimiento 
	// Div
	dven                     = document.createElement("div");
	dven.style.padding       = "0.5em 0";
	dven.style.display       = "flex";
	dven.style.flexDirection = "row";
	// Etiqueta
	tet4 = document.createTextNode("Vencimiento (MM/YYYY)");
	set4 = document.createElement("span");
	set4.appendChild(tet4);
    set4.style.width      = "50%";
    set4.style.height     = "20px";
    set4.style.textAlign  = "left";
    set4.style.marginLeft = "45px";
    // Input mes
	imes                  = document.createElement("input");
	imes.id               = "mes";
	imes.type             = "text";
	imes.size             = "50";
	imes.maxLength        = "2";
	imes.style.width      = "3em";
	imes.style.textAlign  = "center";
	imes.style.background = "lightgray";
	imes.addEventListener("keyup", function(){ xNumero(this.id, this.value); });
    // Input año
	iyea                  = document.createElement("input");
	iyea.id               = "yea";
	iyea.type             = "text";
	iyea.size             = "50";
	iyea.maxLength        = "4";
	iyea.style.width      = "5em";
	iyea.style.textAlign  = "center";
	iyea.style.background = "lightgray";
	iyea.addEventListener("keyup", function(){ xNumero(this.id, this.value); });

	// Código de seguridad 
	// Div
	dcvv                     = document.createElement("div");
	dcvv.style.padding       = "0.5em 0";
	dcvv.style.display       = "flex";
	dcvv.style.flexDirection = "row";
	// Etiqueta
	tet5 = document.createTextNode("Código de seguridad (CVV)");
	set5 = document.createElement("span");
	set5.appendChild(tet5);
    set5.style.width      = "50%";
    set5.style.height     = "20px";
    set5.style.textAlign  = "left";
    set5.style.marginLeft = "45px";
    // cvv
	icvv                  = document.createElement("input");
	icvv.id               = "cvv";
	icvv.type             = "text";
	icvv.size             = "50";
	icvv.maxLength        = "3";
	icvv.style.width      = "4em";
	icvv.style.textAlign  = "center";
	icvv.style.background = "lightgray";
	icvv.addEventListener("keyup", function(){ xNumero(this.id, this.value); });

	// Monto 
	// Div
	dmon                     = document.createElement("div");
	dmon.style.padding       = "0.5em 0";
	dmon.style.display       = "flex";
	dmon.style.flexDirection = "row";
	// Etiqueta
	tet6 = document.createTextNode("Monto");
	set6 = document.createElement("span");
	set6.appendChild(tet6);
    set6.style.width      = "50%";
    set6.style.height     = "20px";
    set6.style.textAlign  = "left";
    set6.style.marginLeft = "45px";
    // Input
	imon                  = document.createElement("input");
	imon.id               = "mon";
	imon.type             = "text";
	imon.size             = "50";
	imon.maxLength        = "15";
	imon.style.width      = "11em";
	imon.style.textAlign  = "right";
	imon.style.background = "lightgray";
	imon.addEventListener("keyup", function(){ xMonto(this.id, this.value); });

    // Botones
	// Div
	dpag                     = document.createElement("div");
	// dpag.style.paddingTop    = "1em";
	dpag.style.margin        = "0.5em auto 0";
	dpag.style.width         = "100%";
	dpag.style.textAlign     = "center"
	// Boton enviar
	tete = document.createTextNode("Enviar");
	bpag = document.createElement("button");
	bpag.appendChild(tete);
	bpag.id           = "bpag";
	bpag.style.width  = "7em";
	bpag.style.margin = "0 0.25em";
	bpag.addEventListener("click", function(){ enviar() });
	// Boton cerrar
	tetc = document.createTextNode("Cerrar");
	bcer = document.createElement("button");
	bcer.appendChild(tetc);
	bcer.style.width  = "7em";
	bcer.style.margin = "0 0.25em";
	bcer.addEventListener("click", function(){
		document.getElementsByTagName("html")[0].style.overflow = "auto";
		document.getElementsByTagName("html")[0].removeChild(dcon);
		// d_paybtn.removeChild(dcon);
	});
	/*
	// Div del QR
	div2 = document.createElement("div");
	div2.style.margin  = '0 auto';
	div2.style.width   = '100%';
	div2.style.height   = '57%';
	div2.style.display = 'none';
	// div2.style.display = "flex";
	div2.style.flexFlow = "column";
	div2.style.justifyContent = "center";
	// Marco QR
	dcqr = document.createElement("div");
	dcqr.style.margin = "0 auto 0";
	dcqr.style.height = "auto";
	dcqr.style.width = "100%";
	dcqr.style.textAlign = "center";
	// Imagen
	icqr = document.createElement("img");
	icqr.src = "";
	icqr.style.width = "10em";
	icqr.style.height = "auto";
	icqr.style.objectFit = "cover";
	// Texto de la transacción
	tcqr = document.createTextNode("Transacción");
	pcqr = document.createElement("p");
	pcqr.id              = "pcqr";
	pcqr.style.margin    = "0 auto";
	pcqr.style.width     = "80%";
	pcqr.style.textAlign = "center";
	pcqr.appendChild(tcqr);
	*/
	// Agregar objetos
	// Agrega logo
	dlog.appendChild(ilog);
	dmod.appendChild(dlog);
	dmod.appendChild(htit);

	// Agrega tarjeta
	dcrd.appendChild(set1);
	dcrd.appendChild(icrd);
	div1.appendChild(dcrd);
	// Agrega nombre
	dnom.appendChild(set2);
	dnom.appendChild(inom);
	div1.appendChild(dnom);
	// Agrega cedula
	dced.appendChild(set3);
	dced.appendChild(iced);
	div1.appendChild(dced);
	// Agrega vencimiento
	dven.appendChild(set4);
	dven.appendChild(imes);
	dven.appendChild(iyea);
	div1.appendChild(dven);
	// Agrega cvv
	dcvv.appendChild(set5);
	dcvv.appendChild(icvv);
	div1.appendChild(dcvv);
	// Agrega monto
	dmon.appendChild(set6);
	dmon.appendChild(imon);
	div1.appendChild(dmon);
	/*
	// Agrega qr al div 2
	dcqr.appendChild(icqr);
	div2.appendChild(dcqr);
	div2.appendChild(pcqr);
	*/
	dmod.appendChild(div1);
	/*
	dmod.appendChild(div2);
	*/
	// Agrega botones
	dpag.appendChild(bpag);
	dpag.appendChild(bcer);
	dmod.appendChild(dpag);
	
	dcon.appendChild(dmod);
	document.getElementsByTagName("html")[0].style.overflow = "hidden";
	document.getElementsByTagName("html")[0].appendChild(dcon);

	document.getElementById("crd").focus();
}

function xNumero(id, valor) {
	if(!validaNumero(valor)) {
		xValor = document.getElementById(id).value;
		document.getElementById(id).value = xValor.substr(0,xValor.length-1);
		document.getElementById(id).focus();
	}
}

function xMonto(id, monto) {
	if(!validaMonto(monto)) {
		xValor = document.getElementById(id).value;
		document.getElementById(id).value = xValor.substr(0,xValor.length-1);
		document.getElementById(id).focus();
	}
}

function validaNumero(numero) {
	cadena = "0123456789";
	valido = 0;
	for (var i = 0; i < numero.length; i++) { if (cadena.search(numero.charAt(i))>=0) { valido++ } }
	if (valido==numero.length) {
		return true;
	} else {
		alert("Sólo debe introducir números.");
		return false;
	}
}

function validaMonto(monto) {
	cadena = "0123456789.";
	valido = 0;
	punto = 0;
	for (var i = 0; i < monto.length; i++) {
		if (cadena.search(monto.charAt(i))>=0) { valido++ }
		if (monto.charAt(i)==".") { punto++ }
	}
	if (valido==monto.length) {
		if (punto>1) { 
			alert("Sólo puede colocar un sólo punto decimal");
			return false;
		} else {
			return true;
		}
	} else {
		alert("Sólo debe introducir números.");
		return false;
	}
}

// valida la entrada en los campos
function validaciones() {
	let continuar = true, nocontinuar = false, vacios = 0, campo = "";
	if (document.getElementById("crd").value=="" || document.getElementById("crd").value==undefined) {
		alert("El campo tarjeta no puede quedar en blanco");
		vacios++;
		campo = "crd";
		continuar = false;
		nocontinuar = true;
	}
	if ((document.getElementByIdnom("nom").value=="" || document.getElementById("nom").value==undefined) && vacios == 0) {
		alert("El campo nombre no puede quedar en blanco");
		vacios++;
		campo = "nom";
	}
	if ((document.getElementById("ced").value=="" || document.getElementById("ced").value==undefined) && vacios == 0) {
		alert("El campo cédula no puede quedar en blanco");
		vacios++;
		campo = "ced";
	}
	if ((document.getElementById("mes").value=="" || document.getElementById("mes").value==undefined) && vacios == 0) {
		alert("El campo mes no puede quedar en blanco");
		vacios++;
		campo = "mes";
	}
	if ((document.getElementById("yea").value=="" || document.getElementById("yea").value==undefined) && vacios == 0) {
		alert("El campo año no puede quedar en blanco");
		vacios++;
		campo = "yea";
	}
	if ((document.getElementById("cvv").value=="" || document.getElementById("cvv").value==undefined) && vacios == 0) {
		alert("El campo código de seguridad no puede quedar en blanco");
		vacios++;
		campo = "cvv";
	}
	if ((document.getElementById("mon").value=="" || document.getElementById("mon").value==undefined) && vacios == 0) {
		alert("El campo monto no puede quedar en blanco");
		vacios++;
		campo = "mon";
	}
	if (vacios>0) {
		continuar = false;
		nocontinuar = true;
	}
	if (nocontinuar) {
		document.getElementById(campo).focus();
	}
	return continuar;
}

function enviar() {
	if (validaciones()) {
		card  = document.getElementById("crd").value;
		nomb  = document.getElementById("nom").value;
		cedu  = document.getElementById("ced").value;
		// venc  = document.getElementById("mes").value+"/"+document.getElementById("yea").value;
		mven  = document.getElementById("mes").value;
		yven  = document.getElementById("yea").value;
		venc  = document.getElementById("yea").value+document.getElementById("mes").value;
		ccvv  = document.getElementById("cvv").value;
		mont  = document.getElementById("mon").value;
		datos = new FormData();
		datos.append("card",        card);
		datos.append("nombre",      nomb);
		datos.append("cedula",      cedu);
		datos.append("mesven",      mven);
		datos.append("yeaven",      yven);
		datos.append("vencimiento", venc);
		datos.append("codcvv",      ccvv);
		datos.append("monto",       mont);

		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				respuesta = JSON.parse(this.responseText);
				console.log(respuesta);
				if (respuesta.exito == 'SI') {
					console.log(respuesta);
				} else {
					alert(respuesta.mensaje);
				}
			}
		};
		// xmlhttp.open("POST", "https://app.cash-flag.com/php/pasarela_pago_tc.php", true);
		xmlhttp.open("POST", "../php/pasarela_pago_tc.php", true);
		xmlhttp.send(datos);
	} else {

	}
}
/*
function callback(card, token, monto) {
	datos.append("card",  card);
	datos.append("token", token);
	datos.append("monto", monto);
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			console.log(respuesta);
			console.log(ok);
			console.log(nok);
			if (respuesta.exito == 'SI') {
				alert(respuesta.mensaje);
				window.open(ok, "_self");
			} else {
				alert(respuesta.mensaje);
			}
		}
	};
	xmlhttp.open("POST", "https://app.cash-flag.com/php/paybtn_verify.php", true);
	// xmlhttp.open("POST", "../php/paybtn_verify.php", true);
	xmlhttp.send(datos);
}
*/