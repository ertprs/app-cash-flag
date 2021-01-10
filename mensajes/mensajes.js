let idproveedor=localStorage.getItem("idproveedor");
let ub=localStorage.getItem("url_bck2");

idproveedor = (idproveedor==undefined) ? 3 : idproveedor;
ub = (ub==undefined) ? window.location() : ub;

function inicio() {
   document.getElementById("volver").addEventListener('click', function(){
      // window.open(localStorage.getItem("url_bck2"), "_self") });
      window.open(ub, "_self") });

   var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
         respuesta = JSON.parse(this.responseText);
         console.log(respuesta);
			if (respuesta.exito == 'SI') {
				logo = respuesta.proveedor.logo;
				if (logo!="") {
					document.getElementById("logo").src = "../img/" + logo;
				} else {
					document.getElementById("logo").src = "../img/" + 'sin_imagen.jpg';
				}
				document.getElementById("logo").title = respuesta.proveedor.nombre;
				datossms(false);
				datosemail(false);
			}
		}
   };
	xmlhttp.open("GET", "../php/proveedores4.php?prov="+idproveedor, true);
	xmlhttp.send();
}

// limpia el formulario
function limpiar() {
	/*
	for (campo = 0; campo < document.getElementsByClassName("campo").length; campo++) {
		document.getElementsByClassName("campo")[campo].value = "";
		if (document.getElementsByClassName("campo")[campo].type=="checkbox") {
			document.getElementsByClassName("campo")[campo].checked = false;
		}
	}
	for (campo = 0; campo < document.getElementsByClassName("campo2").length; campo++) {
		document.getElementsByClassName("campo2")[campo].value = "";
		if (document.getElementsByClassName("campo2")[campo].type=="checkbox") {
			document.getElementsByClassName("campo2")[campo].checked = false;
		}
	}
	*/
	/////////////////////////////////////////////////////////////////////////////////////////
	document.getElementById("sms").checked = false;
	document.getElementById("email").checked = false;
	document.getElementById("contenidosms").value = "";
	document.getElementById("contenidoemail").value = "";
	document.getElementById("socios").checked = true;
	document.getElementById("prospectos").checked = true;
	document.getElementById("edad-todos").checked = true;
	document.getElementById("01-20").checked = true;
	document.getElementById("21-30").checked = true;
	document.getElementById("31-40").checked = true;
	document.getElementById("41-50").checked = true;
	document.getElementById("51-60").checked = true;
	document.getElementById("61-99").checked = true;
	document.getElementById("sexo-todos").checked = true;
	document.getElementById("femenino").checked = true;
	document.getElementById("masculino").checked = true;
	document.getElementById("pais").value = "192-Venezuela";
	document.getElementById("estado").value = "7-Carabobo";
	document.getElementById("ciudad").value = "todos";
	document.getElementById("sector").value = "";
	document.getElementsByName("vehiculo")[0].checked = true;
	document.getElementById("profesion").value = "";
	document.getElementById("ocupacion").value = "";
	document.getElementById("edocivil-todos").checked = true;
	document.getElementById("soltero").checked = true;
	document.getElementById("casado").checked = true;
	document.getElementById("divorciado").checked = true;
	document.getElementById("viudo").checked = true;
	document.getElementById("complicado").checked = true;
	document.getElementsByName("padre")[0].checked = true;
	document.getElementsByName("madre")[0].checked = true;
	document.getElementById("hijos-todos").checked = true;
	document.getElementById("00-05").checked = true;
	document.getElementById("05-10").checked = true;
	document.getElementById("11-20").checked = true;
	document.getElementById("21-99").checked = true;
	document.getElementById("otrossms").value = "";
	document.getElementById("otrosemails").value = "";
	/////////////////////////////////////////////////////////////////////////////////////////

   document.getElementById("numpagina").innerText = 1;
	txt = 'Seleccionar canal';
	document.getElementById("txtpagina").innerText = txt;

	document.getElementById("tab1").style.display = 'block';
	document.getElementById("tab2").style.display = 'none';
	document.getElementById("tab3").style.display = 'none';
	document.getElementById("tab4").style.display = 'none';
	document.getElementById("enviar").style.display = 'none';
	datossms(false);
	datosemail(false);
	document.getElementById("sms").focus();
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
	if (tab2==4) {
		document.getElementById("enviar").style.display = 'none';
	}
   document.getElementById("numpagina").innerText = tab1;
   switch (tab1) {
      case 1:
         txt = 'Seleccionar canal';
         break;
      case 2:
         txt = 'Escribir mensaje';
         break;
      case 3:
         txt = 'Definir destinatarios';
         break;
      case 4:
         txt = 'Confirmar';
         break;
      }
	document.getElementById("txtpagina").innerText = txt;
}

function adelante(tab1,tab2) { 
	let canales = 0;  
	if (document.getElementById("sms").checked) {
		canales = 10;
	}
	if(document.getElementById("email").checked) {
		canales = canales + 1;
	}
	if (tab1==1) {
		if (document.getElementById("sms").checked || document.getElementById("email").checked) {
			avanza(tab1, tab2);
		} else {
			alert("Debe seleccionar al menos un canal.");
		}
	} else {
		if (tab1==2) { 
			let avanzar = 0;
			if (document.getElementById("sms").checked) {
				if (document.getElementById("contenidosms").value.length>0) {
					avanzar = 10;
				}
			}
			if (document.getElementById("email").checked) {
				if (document.getElementById("contenidoemail").value.length>0) {
					avanzar = avanzar + 1;
				}
			}
			if(avanzar==canales) {
				avanza(tab1, tab2);
			} else {
				alert("No se puede enviar un mensaje vacío.");
			}
		} else {
			if (tab1==3) {
				let edadok = false, sexook = false, edocivilok = false, hijosok = false;
				if (document.getElementById("01-20").checked || document.getElementById("21-30").checked || document.getElementById("31-40").checked || document.getElementById("41-50").checked || document.getElementById("51-60").checked || document.getElementById("61-99").checked) {
					edadok = true;
				} else {
					edadok = false;
				}
				if (document.getElementById("femenino").checked || document.getElementById("masculino").checked) {
					sexook = true;
				} else {
					sexook = false;
				}
				if (document.getElementById("soltero").checked || document.getElementById("casado").checked || document.getElementById("divorciado").checked || document.getElementById("viudo").checked || document.getElementById("complicado").checked) {
					edocivilok = true;
				} else {
					edocivilok = false;
				}
				if (document.getElementById("00-05").checked || document.getElementById("05-10").checked || document.getElementById("11-20").checked || document.getElementById("21-99").checked) {
					hijosok = true;
				} else {
					hijosok = false;
				}
				if (edadok && sexook && edocivilok && hijosok) {
					if (document.getElementById("prospectos").checked) {
						let avanzar2 = 0;
						if (document.getElementById("sms").checked) {
							if (document.getElementById("otrossms").value.length>0) {
								if (numerosvalidos()) {
									avanzar2 = 10;
								}
							} else {
								alert('Debe indicar el número de al menos un prospecto.');
							}
						}
						if (document.getElementById("email").checked) {
							if (document.getElementById("otrosemails").value.length>0) {
								if (emailsvalidos()) {
									avanzar2 = avanzar2 + 1;
								}
							} else {
								alert('Debe indicar el email de al menos un prospecto.')
							}
						}
						if(avanzar2==canales) {
							avanza(tab1,tab2);
						}
					} else {
						avanza(tab1,tab2);
					}
				} else {
					alert("Debe marcar alguna opción en:\nRangos de edad\nSexo\nEstado civil\nEdades de los hijos");
				}
			}
		}
	}
}

function avanza(tab1,tab2) {
	document.getElementById("tab"+tab1).style.display = 'none';
	document.getElementById("tab"+tab2).style.display = 'block';
	if (tab2==4) {
		preparar();

		document.getElementById("parrafosms").innerText = document.getElementById("contenidosms").value;
		document.getElementById("parrafoasunto").innerText = document.getElementById("asuntoemail").value;
		document.getElementById("parrafoemail").innerText = document.getElementById("contenidoemail").value;
		let dest = "";
		// Rangos de edad
		if (document.getElementById("edad-todos").checked) {
			dest = dest+"Todos los rangos de edad";
		} else {
			dest = dest+"Rangos de edad: ";
			coma = "";
			first = true;
			if (document.getElementById("01-20").checked) {
				dest = dest+"Menos de 20 años";
				first = false;
			}
			if (document.getElementById("21-30").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"De 21 a 30 años";
			}
			if (document.getElementById("31-40").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"De 31 a 40 años";
			}
			if (document.getElementById("41-50").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"De 41 a 50 años";
			}
			if (document.getElementById("51-60").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"De 51 a 60 años";
			}
			if (document.getElementById("61-99").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"Mayores de 60 años";
			}
		}
		dest = dest+".<br/>";
		// Sexo
		if (document.getElementById("sexo-todos").checked) {
			dest = dest+"Todos los géneros";
		} else {
			dest = dest+"Sexo: ";
			if (document.getElementById("femenino").checked) {
				dest = dest+"Femenino";
			}
			if (document.getElementById("masculino").checked) {
				dest = dest+"Masculino";
			}
		}
		dest = dest+".<br/>";
		// País
		if (document.getElementById("pais").value=="todos") {
			dest = dest+"Todos los paises."+"<br/>";
		} else {
			xpais = document.getElementById("pais").value.split("-");
			dest = dest+"País: "+xpais[1].trim()+".<br/>";
		}
		// dest = dest+"País: "+document.getElementById("pais").innerText.trim()+".<br/>";
		// Estado
		if (document.getElementById("estado").value=="todos") {
			dest = dest+"Todos los estados."+"<br/>";
		} else {
			xestado = document.getElementById("estado").value.split("-");
			dest = dest+"Estado: "+xestado[1].trim()+".<br/>";
		}
		// dest = dest+"Estado: "+document.getElementById("estado").innerText.trim()+".<br/>";
		// Ciudad
		if (document.getElementById("ciudad").value=="todos") {
			dest = dest+"Todas las ciudades."+"<br/>";
		} else {
			xciudad = document.getElementById("ciudad").value.split("-");
			dest = dest+"Ciudad: "+xciudad[1].trim();
		}
		// dest = dest+"Ciudad: "+document.getElementById("ciudad").innerText+".<br/>";
		// Sector
		if (document.getElementById("sector").value!="") {
			dest = dest+"Sector de la ciudad: "+document.getElementById("sector").value.trim();
			dest = dest+".<br/>";
		}
		// Vehículo
		dest = dest+"Poseedor de vehículo: ";
		for (let i = 0; i < document.getElementsByName("vehiculo").length; i++) {
			if (document.getElementsByName("vehiculo")[i].checked) {
				if (document.getElementsByName("vehiculo")[i].value=="todos") {
					dest = dest+"Indistinto";
				} else {

					dest = dest+document.getElementsByName("vehiculo")[i].value.substring(0,1).toUpperCase()+document.getElementsByName("vehiculo")[i].value.substring(1,100);
				}
			}		
		}
		dest = dest + ".<br/>";
		// Profesión
		if (document.getElementById("profesion").value!="") {
			dest = dest+"Profesión: "+document.getElementById("profesion").value.trim();
			dest = dest+".<br/>";
		}
		// Ocupación
		if (document.getElementById("ocupacion").value!="") {
			dest = dest+"Ocupación: "+document.getElementById("ocupacion").value.trim();
			dest = dest+".<br/>";
		}
		// Estado civil
		if (document.getElementById("edocivil-todos").checked) {
			dest = dest+"Todos los estados civiles";
		} else {
			dest = dest+"Estado civil: ";
			coma = "";
			first = true;
			if (document.getElementById("soltero").checked) {
				dest = dest+"Soltero";
				first = false;
			}
			if (document.getElementById("casado").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"Casado";
			}
			if (document.getElementById("divorciado").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"Divorciado";
			}
			if (document.getElementById("viudo").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"Viudo";
			}
			if (document.getElementById("complicado").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"Otro";
			}
		}
		dest = dest+".<br/>";
		// Padre vivo
		dest = dest+"Padre vivo: ";
		for (let i = 0; i < document.getElementsByName("padre").length; i++) {
			if (document.getElementsByName("padre")[i].checked) {
				if (document.getElementsByName("padre")[i].value=="todos") {
					dest = dest+"Indistinto";
				} else {

					dest = dest+document.getElementsByName("padre")[i].value.substring(0,1).toUpperCase()+document.getElementsByName("padre")[i].value.substring(1,100);
				}
			}		
		}
		dest = dest + ".<br/>";
		// Madre viva
		dest = dest+"Madre viva: ";
		for (let i = 0; i < document.getElementsByName("madre").length; i++) {
			if (document.getElementsByName("madre")[i].checked) {
				if (document.getElementsByName("madre")[i].value=="todos") {
					dest = dest+"Indistinto";
				} else {

					dest = dest+document.getElementsByName("madre")[i].value.substring(0,1).toUpperCase()+document.getElementsByName("madre")[i].value.substring(1,100);
				}
			}		
		}
		dest = dest + ".<br/>";
		// Edades de los hijos
		if (document.getElementById("hijos-todos").checked) {
			dest = dest+"Hijos de todas las edades (o sin hijos)";
		} else {
			dest = dest+"Edades de los hijos: ";
			coma = "";
			first = true;
			if (document.getElementById("00-05").checked) {
				dest = dest+"Menos de 5 años";
				first = false;
			}
			if (document.getElementById("05-10").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"De 5 a 10 años";
			}
			if (document.getElementById("11-20").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"De 11 a 20 años";
			}
			if (document.getElementById("21-99").checked) {
				if (first) { coma = "", first = false; } else { coma = ", "; }
				dest = dest+coma+"Más de 20 años";
			}
		}
		dest = dest+".<br/>";
		dest2 = ""
		// SMS Adicionales
		if (document.getElementById("otrossms").value!="") {
			dest2 = dest2+"Números de prospectos (para SMS):<br/>"+document.getElementById("otrossms").value.trim();
			dest2 = dest2+".<br/>";
		}
		// emails Adicionales
		if (document.getElementById("otrosemails").value!="") {
			dest2 = dest2+"Emails de prospectos:<br/>"+document.getElementById("otrosemails").value.trim();
			dest2 = dest2+".<br/>";
		}

		document.getElementById("parrafofiltrossocios").innerHTML = dest;
		document.getElementById("parrafofiltrosprospectos").innerHTML = dest2;

		document.getElementById("enviar").style.display = 'inline-block';
	}
	document.getElementById("numpagina").innerText = tab2;
	switch (tab2) {
		case 1:
			txt = 'Seleccionar canal';
			break;
		case 2:
			txt = 'Escribir mensaje';
			break;
		case 3:
			txt = 'Definir destinatarios';
			break;
		case 4:
			txt = 'Confirmar';
			break;
		}
	document.getElementById("txtpagina").innerText = txt;			
}

function datossms(checked) {
	if(checked) {
		document.getElementById("txtsms").style.display = "block";
		document.getElementById("adicsms").style.display = "block";
		document.getElementById("finalsms").style.display = "block";
	} else {
		document.getElementById("txtsms").style.display = "none";
		document.getElementById("adicsms").style.display = "none";
		document.getElementById("finalsms").style.display = "none";

		document.getElementById("contenidosms").value = "";
		document.getElementById("otrossms").value = "";
	}
}

function datosemail(checked) {
	if(checked) {
		document.getElementById("txtasunto").style.display = "flex";
		document.getElementById("txtemail").style.display = "flex";
		document.getElementById("adicemail").style.display = "block";
		document.getElementById("finalemail").style.display = "block";
	} else {
		document.getElementById("txtasunto").style.display = "none";
		document.getElementById("txtemail").style.display = "none";
		document.getElementById("adicemail").style.display = "none";
		document.getElementById("finalemail").style.display = "none";

		document.getElementById("contenidoemail").value = "";
		document.getElementById("otrosemails").value = "";
	}
}

function displaysocios() {
	if (document.getElementById("socios").checked) {
		document.getElementById("seccionsocios").style.display = 'block';
		document.getElementById("filtrossocios").style.display = 'block';
	} else {
		document.getElementById("seccionsocios").style.display = 'none';
		document.getElementById("filtrossocios").style.display = 'none';
	}
}

function displayprospectos() {
	if (document.getElementById("prospectos").checked) {
		document.getElementById("seccionprospectos").style.display = 'block';
		document.getElementById("filtrosprospectos").style.display = 'block';
	} else {
		document.getElementById("seccionprospectos").style.display = 'none';
		document.getElementById("filtrosprospectos").style.display = 'none';
	}
}

function edadtodos() {
	if (document.getElementById("edad-todos").checked) {
		document.getElementById("01-20").checked = true;
		document.getElementById("21-30").checked = true;
		document.getElementById("31-40").checked = true;
		document.getElementById("41-50").checked = true;
		document.getElementById("51-60").checked = true;
		document.getElementById("61-99").checked = true;
	} else {
		document.getElementById("01-20").checked = false;
		document.getElementById("21-30").checked = false;
		document.getElementById("31-40").checked = false;
		document.getElementById("41-50").checked = false;
		document.getElementById("51-60").checked = false;
		document.getElementById("61-99").checked = false;
	}
}

function edadindividual() {
	if (document.getElementById("01-20").checked && document.getElementById("21-30").checked && document.getElementById("31-40").checked && document.getElementById("41-50").checked && document.getElementById("51-60").checked && document.getElementById("61-99").checked) {
		document.getElementById("edad-todos").checked = true;
	} else {
		document.getElementById("edad-todos").checked = false;
	}
}

function sexotodos() {
	if (document.getElementById("sexo-todos").checked) {
		document.getElementById("femenino").checked = true;
		document.getElementById("masculino").checked = true;
	} else {
		document.getElementById("femenino").checked = false;
		document.getElementById("masculino").checked = false;
	}
}

function sexoindividual() {
	if (document.getElementById("femenino").checked && document.getElementById("masculino").checked) {
		document.getElementById("sexo-todos").checked = true;
	} else {
		document.getElementById("sexo-todos").checked = false;
	}
}

function edociviltodos() {
	if (document.getElementById("edocivil-todos").checked) {
		document.getElementById("soltero").checked = true;
		document.getElementById("casado").checked = true;
		document.getElementById("divorciado").checked = true;
		document.getElementById("viudo").checked = true;
		document.getElementById("complicado").checked = true;
	} else {
		document.getElementById("soltero").checked = false;
		document.getElementById("casado").checked = false;
		document.getElementById("divorciado").checked = false;
		document.getElementById("viudo").checked = false;
		document.getElementById("complicado").checked = false;
	}
}

function edocivilindividual() {
	if (document.getElementById("soltero").checked && document.getElementById("casado").checked && document.getElementById("divorciado").checked && document.getElementById("viudo").checked && document.getElementById("complicado").checked) {
		document.getElementById("edocivil-todos").checked = true;
	} else {
		document.getElementById("edocivil-todos").checked = false;
	}
}

function hijostodos() {
	if (document.getElementById("hijos-todos").checked) {
		document.getElementById("00-05").checked = true;
		document.getElementById("05-10").checked = true;
		document.getElementById("11-20").checked = true;
		document.getElementById("21-99").checked = true;
	} else {
		document.getElementById("00-05").checked = false;
		document.getElementById("05-10").checked = false;
		document.getElementById("11-20").checked = false;
		document.getElementById("21-99").checked = false;
	}
}

function hijosindividual() {
	if (document.getElementById("00-05").checked && document.getElementById("05-10").checked && document.getElementById("11-20").checked && document.getElementById("21-99").checked) {
		document.getElementById("hijos-todos").checked = true;
	} else {
		document.getElementById("hijos-todos").checked = false;
	}
}

function numerosvalidos() {
	valor = document.getElementById("otrossms").value;
	lista = "0123456789;";
	let novalido = 0;

	for (i = 0; i < valor.length; i++) {
		posicion = lista.indexOf(valor.substr(i,1));
		if (posicion<0) {
			novalido++;
		}
	}
	if (novalido>0) {
		alert("En el campo de números adicionales para SMS sólo se permiten los caracteres 0123456789 y el punto y coma (;)");
		resultado = false;
	} else {
		let arreglo = valor.split(";");
		let msg = 0, xres = 100;
		for ( i = 0; i < arreglo.length; i++) {
			if (arreglo[i].length==12) {		// Primera validación: longitud
				if (arreglo[i].substr(0,3)=="584") {	// Segunda validación: prefijo 584 
					xres = 0;
					// resultado = true;
				} else {
					msg++;
					mensaje = 'Hay números de teléfono que no tienen el formato adecuado:\nDeben iniciar con 584...';
					xres++;
					// resultado = false;
				}
			} else {
				if (arreglo[i].length==0) {
					msg++;
					mensaje = 'Hay números de teléfono que no tienen el formato adecuado:\nNo puede haber números vacíos.';
				} else {
					msg++;
					mensaje = 'Hay números de teléfono que no tienen el formato adecuado:\n12 caracteres numéricos.';
				}
				xres++;
				// resultado = false;
			} 
		}
		if (msg>0) {
			alert(mensaje);
			resultado = false;
		} else {
			resultado = true;
		}
	}
	return resultado;
}

function emailsvalidos() {
	valor = document.getElementById("otrosemails").value;
	let arreglo = valor.split(";");
	let xres = 0;
	for ( i = 0; i < arreglo.length; i++) {
		correo = arreglo[i];
		if (correo!="") {
			arroba = 0;
			punto = 0;
			posa = 0;
			posp = 0;
			for (index = 0; index < correo.length; index++) {
				if (correo[index] == "@") { arroba++; posa = index; }
				if (correo[index] == ".") { punto++; posp = index; }
			}
			if (arroba + punto > 1 && posp > posa) {
				xres++;
			} else {
				xmensaje = 'Algún email de los prospectos es inválido:\nDebe contener "@" y por lo menos un punto (.)';
			}
		} else {
			xmensaje = 'No puede haber email vacío.';
		}
	}
	if (xres==arreglo.length) {
		resultado = true;
	} else {
		alert(xmensaje);
		resultado = false;
	}
	return resultado;
}

// Enviar los datos del formulario para procesar en el servidor
function preparar() {
	var datos = new FormData();
	datos.append("sms", document.getElementById("sms").checked);
	datos.append("email", document.getElementById("email").checked);
	datos.append("contenidosms", document.getElementById("contenidosms").value);
	datos.append("contenidoemail", document.getElementById("contenidoemail").value);
	datos.append("socios", document.getElementById("socios").checked);
	datos.append("prospectos", document.getElementById("prospectos").checked);
	datos.append("edad-todos", document.getElementById("edad-todos").checked);
	datos.append("01-20", document.getElementById("01-20").checked);
	datos.append("21-30", document.getElementById("21-30").checked);
	datos.append("31-40", document.getElementById("31-40").checked);
	datos.append("41-50", document.getElementById("41-50").checked);
	datos.append("51-60", document.getElementById("51-60").checked);
	datos.append("61-99", document.getElementById("61-99").checked);
	datos.append("sexo-todos", document.getElementById("sexo-todos").checked);
	datos.append("femenino", document.getElementById("femenino").checked);
	datos.append("masculino", document.getElementById("masculino").checked);
	datos.append("pais", document.getElementById("pais").value);
	datos.append("estado", document.getElementById("estado").value);
	datos.append("ciudad", document.getElementById("ciudad").value);
	datos.append("sector", document.getElementById("sector").value);
	xvhcl = ""
	if (document.getElementsByName("vehiculo")[0].checked) {
		xvhcl = "todos";
	}
	if (document.getElementsByName("vehiculo")[1].checked) {
		xvhcl = "si";
	}
	if (document.getElementsByName("vehiculo")[2].checked) {
		xvhcl = "no";
	}
	datos.append("vehiculo", xvhcl);
	datos.append("profesion", document.getElementById("profesion").value);
	datos.append("ocupacion", document.getElementById("ocupacion").value);
	datos.append("edocivil-todos", document.getElementById("edocivil-todos").checked);
	datos.append("soltero", document.getElementById("soltero").checked);
	datos.append("casado", document.getElementById("casado").checked);
	datos.append("divorciado", document.getElementById("divorciado").checked);
	datos.append("viudo", document.getElementById("viudo").checked);
	datos.append("complicado", document.getElementById("complicado").checked);
	xpdr = ""
	if (document.getElementsByName("padre")[0].checked) {
		xpdr = "todos";
	}
	if (document.getElementsByName("padre")[1].checked) {
		xpdr = "si";
	}
	if (document.getElementsByName("padre")[2].checked) {
		xpdr = "no";
	}
	datos.append("padrevivo", xpdr);
	xmdr = ""
	if (document.getElementsByName("madre")[0].checked) {
		xmdr = "todos";
	}
	if (document.getElementsByName("madre")[1].checked) {
		xmdr = "si";
	}
	if (document.getElementsByName("madre")[2].checked) {
		xmdr = "no";
	}
	datos.append("madreviva", xmdr);
	datos.append("hijos-todos", document.getElementById("hijos-todos").checked);
	datos.append("00-05", document.getElementById("00-05").checked);
	datos.append("05-10", document.getElementById("05-10").checked);
	datos.append("11-20", document.getElementById("11-20").checked);
	datos.append("21-99", document.getElementById("21-99").checked);
	datos.append("otrossms", document.getElementById("otrossms").value);
	datos.append("otrosemails", document.getElementById("otrosemails").value);

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			console.log(respuesta);
			if (respuesta.exito == 'SI') {
				let xmensaje = "";
				console.log(respuesta);
				document.getElementById("query").value = respuesta.query;
				if (document.getElementById("sms").checked && document.getElementById("email").checked) {
					xmensaje = "Se enviará(n) "+respuesta.filas+" mensaje(s) (emails y SMS).";
				} else {
					if (document.getElementById("sms").checked) {
						xmensaje = "Se enviará(n) "+respuesta.filas+" SMS.";
					}
					if (document.getElementById("email").checked) {
						xmensaje = "Se enviará(n) "+respuesta.filas+" emails.";
					}
				}
		
				document.getElementById("mensajefinal").innerText = xmensaje;
			} else {
				alert(respuesta.mensaje);
			}
		}
	};
	xmlhttp.open("POST", "../php/preparamensajes.php", false);
	xmlhttp.send(datos);
}

function enviar() {
	var datos = new FormData();
	datos.append("sms", document.getElementById("sms").checked);
	datos.append("email", document.getElementById("email").checked);
	datos.append("contenidosms", document.getElementById("contenidosms").value);
	datos.append("asuntoemail", document.getElementById("asuntoemail").value);
	datos.append("contenidoemail", document.getElementById("contenidoemail").value);
	datos.append("socios", document.getElementById("socios").checked);
	datos.append("prospectos", document.getElementById("prospectos").checked);
	datos.append("otrossms", document.getElementById("otrossms").value);
	datos.append("otrosemails", document.getElementById("otrosemails").value);
	datos.append("query", document.getElementById("query").value);
	datos.append("idproveedor", idproveedor);

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			console.log(respuesta);
			if (respuesta.exito == 'SI') {
				alert(respuesta.mensaje);
				limpiar();
			} else {
				alert(respuesta.mensaje);
			}
		}
	};
	xmlhttp.open("POST", "../php/enviamensajes.php", false);
	xmlhttp.send(datos);
}