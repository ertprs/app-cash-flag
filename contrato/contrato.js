function generarcontrato() {
	var datos = new FormData();
	datos.append("razonsocial",  contenido.contentWindow.razonsocial.innerText);
	datos.append("nombre",       contenido.contentWindow.nombre.innerText);
	datos.append("rif",          contenido.contentWindow.rif.innerText);
	datos.append("direccion",    contenido.contentWindow.direccion.innerText);
	datos.append("email",        contenido.contentWindow.email.innerText);
	datos.append("firmasgc",     contenido.contentWindow.firmasgc.innerText);
	datos.append("firmacliente", contenido.contentWindow.firmacliente.innerText);
	datos.append("fecha",        contenido.contentWindow.fecha.innerText);

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			if (respuesta.exito == 'SI') {
				console.log(respuesta.archivo);
				// console.log(respuesta.contenido);
				// var file = new File(['string'], respuesta.archivo);
				// console.log(file);
				// window.open(file.name,"_blank");

				addFile(respuesta.contenido).then(hash => {
					if(confirm("Contrato registrado exitosamente. El documento está diponible y público en la siguiente url:\n\n"+"https://ipfs.io/ipfs/"+hash+"\n\n¿Desea abrirlo?")) {
						window.open("https://ipfs.io/ipfs/"+hash,"_blank");
					}
				});
			} else {
				alert("Ocurrió un error en al firma, intente de nuevo");
			}
		}
	};
	xmlhttp.open("POST", "../php/generafileipfs.php", false);
	xmlhttp.send(datos);
}