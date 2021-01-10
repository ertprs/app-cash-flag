var ruta = s_paybtn.src.split("?");
var params = ruta[1].split("&");

var ok = params[0].substr(3,1000), nok = params[1].substr(4,1000), sandbox = "", url = "";

if(params[2].substr(8,1000)=="true") { 
	sandbox = true;
	url = "pruebas.cash-flag.com";
} else {
	sandbox = false;
	url = "app.cash-flag.com";
}

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

paybtn.addEventListener("click", function () { PayButton(event) } );

function PayButton(event) {
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
	ilog.src = "https://"+url+"/img/logoclub.png";
	// ilog.src = "../img/logoclub.png";
	ilog.style.width = "10em";
	ilog.style.height = "auto";
	ilog.style.objectFit = "cover";

	// Título
	ttit = document.createTextNode("Procesador de pagos");
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

	// Fecha de vencimiento 
	// Div
	dven                     = document.createElement("div");
	dven.style.padding       = "1em 0";
	dven.style.display       = "flex";
	dven.style.flexDirection = "row";
	// Etiqueta
	tet2 = document.createTextNode("Vencimiento (MM/YYYY)");
	set2 = document.createElement("span");
	set2.appendChild(tet2);
    set2.style.width      = "50%";
    set2.style.height     = "20px";
    set2.style.textAlign  = "left";
    set2.style.marginLeft = "45px";
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

	// Monto 
	// Div
	dmon                     = document.createElement("div");
	dmon.style.padding       = "0.5em 0";
	dmon.style.display       = "flex";
	dmon.style.flexDirection = "row";
	// Etiqueta
	tet3 = document.createTextNode("Monto");
	set3 = document.createElement("span");
	set3.appendChild(tet3);
    set3.style.width      = "50%";
    set3.style.height     = "20px";
    set3.style.textAlign  = "left";
    set3.style.marginLeft = "45px";
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
	// bpag.addEventListener("click", function(){ alert('enviar') });
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

	// Agregar objetos
	// Agrega logo
	dlog.appendChild(ilog);
	dmod.appendChild(dlog);
	dmod.appendChild(htit);

	// Agrega tarjeta
	dcrd.appendChild(set1);
	dcrd.appendChild(icrd);
	div1.appendChild(dcrd);
	// Agrega vencimiento
	dven.appendChild(set2);
	dven.appendChild(imes);
	dven.appendChild(iyea);
	div1.appendChild(dven);
	// Agrega monto
	dmon.appendChild(set3);
	dmon.appendChild(imon);
	div1.appendChild(dmon);

	// Agrega qr al div 2
	dcqr.appendChild(icqr);
	div2.appendChild(dcqr);
	div2.appendChild(pcqr);

	dmod.appendChild(div1);
	dmod.appendChild(div2);
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
		// venc  = document.getElementById("mes").value+"/"+document.getElementById("yea").value;
		venc  = document.getElementById("yea").value+document.getElementById("mes").value;
		mont  = document.getElementById("mon").value;
		datos = new FormData();
		datos.append("card",        card);
		datos.append("vencimiento", venc);
		datos.append("monto",       mont);
		datos.append("sandbox",     sandbox);

		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				respuesta = JSON.parse(this.responseText);
				console.log(respuesta);
				if (respuesta.exito == 'SI') {
					icqr.src = respuesta.qr;
					tcrd = card.substr(0,4)+" "+card.substr(4,4)+" "+card.substr(8,4)+" "+card.substr(12,4);
					txt = "Escanee este código con el lector de su tarjeta<br/>";
					txt += "Número: <b>"+tcrd+"</b> - ";
					txt += "Monto: <b>"+formatNumber.new(mont)+"<br/>";
					txt += "<b><i>(Esta ventana se cerrará automáticamente en 60 segundos)</i></b>";
					pcqr.innerHTML = txt;
					div1.style.display = 'none';
					div2.style.display = 'block';
					bpag.style.display = 'none';
					setTimeout(function() {
						document.getElementsByTagName("html")[0].style.overflow = "auto";
						document.getElementsByTagName("html")[0].removeChild(dcon);
						callback(card, respuesta.token, mont);
					}, 60000);
				} else {
					alert(respuesta.mensaje);
				}
			}
		};
		// xmlhttp.open("POST", "https://"+url+"/php/paybtn_token.php", true);
		xmlhttp.open("POST", "../php/paybtn_token.php", true);
		xmlhttp.send(datos);
	} else {

	}
}

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
	// xmlhttp.open("POST", "https://"+url+"/php/paybtn_verify.php", true);
	xmlhttp.open("POST", "../php/paybtn_verify.php", true);
	xmlhttp.send(datos);
}