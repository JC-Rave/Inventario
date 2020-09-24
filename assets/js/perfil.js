$(document).ready(iniciar);
var documento;
var linea;
var nombre;
var apellido;
var telefono;
var correo;
var confirmar;

function iniciar() {
	//obtengo el valor actual de los campos en caso de cancelar el proceso de editar
	documento=$("#documento").val();
	linea=$("#linea").val();
	nombre=$("#nombre").val();
	apellido=$("#apellido").val();
	telefono=$("#telefono").val();
	correo=$("#correo").val();
	confirmar=$("#confirmar_correo").val();

	//configuro los procesos para editar
	$("#btn_editar").on("click", configurar);

	//cancelo los procesos echos
	$("#btn_cancelar").on("click", cancelar);

	//edito el perfil
	$("#btn_guardar").on("click", editar);

	//cargar imagen
	$("#btn_cargar").on("click", cargar_imagen);

	//utilizo una de las funciones de escuha del teclado para el diseño de interfaz
	$('#documento').keyup(function() {diseño(!error?'':'documento');});
	$('#nombre').keyup(function() {diseño(!error?'':'nombre');});
	$('#apellido').keyup(function() {diseño(!error?'':'apellido');});
	$('#telefono').keyup(function() {diseño(!error?'':'telefono');});
	$('#correo').keyup(function() {diseño(!error?'':'correo');});
	$('#confirmar_correo').keyup(function() {diseño(!error?'':'confirmar_correo');});
	window.setTimeout(function() {
      $("#caja-cargando").hide();
    },1300);
}

function editar() {
	$("#caja-cargando").show();
	$.ajax({
		type:'post',
		data:$("#editar_perfil").serialize(),
		dataType:'json',
		url:base_url('Perfil_controller'),
		success:function(respuesta) {
			// verifico la respuesta llegada por clave res.
			$("#caja-cargando").hide();
            if (respuesta[0].res=='invalid') {
                invalidar_campos(respuesta);

            }else if (respuesta[0].res && respuesta[0]['afectadas'][0]!=0 || respuesta[0]['afectadas'][1]!=0){
				//configuro los procesos al estado normal
				configurar(true);

				$("#user_name").html($("#nombre").val()+' '+$("#apellido").val());
				$("#perfil_nav span").html($("#nombre").val()+' '+$("#apellido").val());

				if ($("#permiso").text()=='ADMINISTRADOR') {
					if ($("#linea").val()=='1') {
							var new_linea='Instructor de TICs';
						}else if ($("#linea").val()=='2') {
							var new_linea='Instructor de Biotecnología';
						}else if ($("#linea").val()=='3') {
							var new_linea='Instructor de Nanotecnología';
						}else if ($("#linea").val()=='4') {
							var new_linea='Instructor de Química';
						}else if ($("#linea").val()=='5') {
							var new_linea='Instructor de Física';
						}else if ($("#linea").val()=='6') {
							var new_linea='Instructor de Matemáticas';
						}else if ($("#linea").val()=='7'){
							var new_linea='Instructor de Electrónica y robótica';
						}else{
							var new_linea='Encargado de Administrativa';
						}
					$("#linea_encargada").html(new_linea);
				}

				alerta(respuesta);
				cancelar();

            }else if (!respuesta[0].res){
            	cancelar();
				alerta(respuesta);

            }else{
            	//configuro los procesos al estado normal
				configurar(true);
				cancelar();
            }
		}
	});
}

function cargar_imagen() {
	var imagen=new FormData($("#cargar_imagen")[0]);
	$("#caja-cargando").show();
	$.ajax({
		type:'post',
		data:imagen,
		dataType:'json',
		url:base_url('Perfil_controller/cargarImagen'),
		contentType:false,
		processData:false,
		success:function(respuesta) {
			$("#caja-cargando").hide();
			if (respuesta[0].res) {
				$("#perfil_nav img").prop('src', base_url('assets/files/'+respuesta[0].foto));
				$("#img_perfil").prop('src', base_url('assets/files/'+respuesta[0].foto));

				$("#cargar_imagen label").html('Seleccionar Imagen');
				// window.location.href=base_url('Vistas/perfil');

			}else{
				alerta(respuesta);
			}
		}
	});
}

function cancelar() {
	//elimino el diseño de error de los inputs en caso de haber
	error=false;
    restablecer('documento');
    restablecer('nombre');
    restablecer('apellido');
    restablecer('telefono');
    restablecer('correo');
    restablecer('confirmar_correo');

	//devuelvo los valores actuales de los campos
	$("#documento").val(documento);
	$("#linea").val(linea);
	$("#nombre").val(nombre);
	$("#apellido").val(apellido);
	$("#telefono").val(telefono);
	$("#correo").val(correo);
	$("#confirmar_correo").val(confirmar);

	//configuro los procesos al estado normal
	configurar(true);
}

function configurar(configuracion=false) {
	//habilitos los campos a modificar
	$("#nombre").attr('disabled', configuracion==true ? true : false);
	$("#apellido").attr('disabled', configuracion==true ? true : false);
	$("#telefono").attr('disabled', configuracion==true ? true : false);
	$("#correo").attr('disabled', configuracion==true ? true : false);
	$("#confirmar_correo").attr('disabled', configuracion==true ? true : false);

	if ($("#permiso").text()=='ADMINISTRADOR') {
		$("#documento").attr('disabled', configuracion==true ? true : false);
		$("#linea").attr('disabled', configuracion==true ? true : false);
	}

	//organizo las acciones(botones)
	$("#btn_editar").attr('hidden', configuracion==true ? false : true);
	$("#btn_guardar").attr('hidden', configuracion==true ? true : false);
	$("#btn_cancelar").attr('hidden', configuracion==true ? true : false);
}

function invalidar_campos(respuesta) {
    error=true;
	/*
		en cada condicion consulto si un input o select es ivalido de ser asi:
		- agrego una clase para advertir al usuario que el campo es invalido
		- agrego el error que evita continuar con los procesos normalmente
	*/
	if (respuesta.hasOwnProperty(documento) && respuesta[1].documento.length>0) {
		$('#documento').val('');
		$('#documento').addClass('is-invalid');
		$('#error_documento').html(respuesta[0].documento);
	}

	if (respuesta[0].nombre.length>0) {
		$('#nombre').val('');
		$('#nombre').addClass('is-invalid');
		$('#error_nombre').html(respuesta[0].nombre);
	}

	if (respuesta[0].apellido.length>0) {
		$('#apellido').val('');
		$('#apellido').addClass('is-invalid');
		$('#error_apellido').html(respuesta[0].apellido);
	}

	if (respuesta[0].telefono.length>0) {
		$('#telefono').val('');
		$('#telefono').addClass('is-invalid');
		$('#error_telefono').html(respuesta[0].telefono);
	}

	if (respuesta[0].correo.length>0) {
		$('#correo').val('');
		$('#correo').addClass('is-invalid');
		$('#error_correo').html(respuesta[0].correo);
	}

	if (respuesta[0].confirmar.length>0) {
		$('#confirmar_correo').val('');
		$('#confirmar_correo').addClass('is-invalid');
		$('#error_confirmar').html(respuesta[0].confirmar);
	}
}

function alerta(respuesta) {
    Swal.fire({
      position: 'top',
      icon: respuesta[0].res?'success':'error',
      text: respuesta[0].mensaje,
      showConfirmButton: true
    }).then((result) => {
        setTimeout(function() {
            $(".sidebar-mini").css('padding-right', '0px');
        },170);
    });
}