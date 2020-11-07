// Cargar los datos iniciales de la forma y la etiquetas
function cargaforma() {
	buscatitulo();
	var logo;

	var params = fparamurl(window.location.search.substr(1));

	localStorage.setItem("id_proveedor",params.idp);
	localStorage.setItem("id_socio",params.ids);
	var prov = localStorage.getItem("id_proveedor");
	var socio = localStorage.getItem("id_socio");

	var titulo = localStorage.getItem("nombresistema");
	// cargar parámetros de la tabla
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			if (respuesta.exito == 3) {
				document.title = titulo;
				logo = respuesta.proveedor.logo;
				if (logo!="") {
					document.getElementById("logo").src = "../img/" + logo;
				} else {
					document.getElementById("logo").src = "../img/" + 'sin_imagen.jpg';
				}
				document.getElementById("logo").title = respuesta.proveedor.nombre;
				document.getElementById("tituloformulario").innerHTML = '¡'+respuesta.socio.nombres+', bienvenido a tu comunidad!';
			}
		}
	};
	xmlhttp.open("GET", "../php/buscadatos.php?prov=" + prov+"&socio="+socio, false);
	xmlhttp.send();

	// cargar parámetros del json: etiquetas y elementos de forma
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			modulo = respuesta.titulo;
			document.title = document.title + ' - ' + modulo;
			console.log(respuesta);
			for (campo = 0; campo < document.getElementsByClassName("campo").length; campo++) {
				if (campo<document.getElementsByClassName("campo").length-2) {
					document.getElementsByClassName("etiq")[campo].innerHTML = respuesta.etiquetas[campo];
				}
				document.getElementsByClassName("campo")[campo].id = respuesta.campos[campo].nombre;
			}
		}
	};
	xmlhttp.open("GET", "registro.json", false);
	xmlhttp.send();
}

// limpia el formulario
function limpiar() {
	for (campo = 0; campo < document.getElementsByClassName("campo").length; campo++) {
		document.getElementsByClassName("campo")[campo].value = "";
	}
	document.getElementById("fechanacimiento").focus();
}

// Enviar los datos del formulario para procesar en el servidor
function enviar() {
	var continuar = true;
	if (document.getElementById("estado").value=="nodefinido") {
		alert("Debes seleccionar un estado.");
		continuar = false;
	}
	if (document.getElementById("ciudad").value=="nodefinido") {
		alert("Debes seleccionar una ciudad.");
		continuar = false;
	}
	if (continuar) {
		if (document.getElementById("promociones").checked && document.getElementById("premium").checked && document.getElementById("cargos").checked && document.getElementById("comision").checked) {
			var id_proveedor = localStorage.getItem("id_proveedor");
			document.getElementById("id_proveedor").value = id_proveedor;
			var id_socio = localStorage.getItem("id_socio");
			document.getElementById("id_socio").value = id_socio;
		
			var datos = new FormData();
			for (campo = 0; campo < document.getElementsByClassName("campo").length; campo++) {
				if (document.getElementsByClassName("campo")[campo].type=='checkbox'){
					datos.append(document.getElementsByClassName("campo")[campo].id, document.getElementsByClassName("campo")[campo].checked);
				} else {
					datos.append(document.getElementsByClassName("campo")[campo].id, document.getElementsByClassName("campo")[campo].value);
				}
			}
		
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					respuesta = JSON.parse(this.responseText);
					console.log(respuesta);
					if (respuesta.exito == 'SI') {
						alert(fmensaje(respuesta.mensaje));
						limpiar();
						// window.location.replace("app.cash-flag.com/index.html");
						window.location.replace("../");
					} else {
						alert(fmensaje(respuesta.mensaje));
					}
				}
			};
			xmlhttp.open("POST", "../php/registro.php", false);
			xmlhttp.send(datos);
		} else {
			alert("Debes aceptar todos los términos y condiciones del servicio para completar el registro.")
		}
	}
}

function buscatitulo() {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			if (respuesta.exito == 'SI') {
				document.title = respuesta.parametros.nombresistema;
				localStorage.setItem("nombresistema", respuesta.parametros.nombresistema);
			}
		}
	};
	xmlhttp.open("GET", "../php/parametros.php", false);
	xmlhttp.send();
}

function atras(tab1,tab2) {
	document.getElementById("tab"+tab1).style.display = 'block';
	document.getElementById("tab"+tab2).style.display = 'none';
	if (tab2==5) {
		document.getElementById("enviar").style.display = 'none';
	}
}

function adelante(tab1,tab2) {
	document.getElementById("tab"+tab1).style.display = 'none';
	document.getElementById("tab"+tab2).style.display = 'block';
	if (tab2==5) {
		document.getElementById("enviar").style.display = 'inline-block';
	}
}
