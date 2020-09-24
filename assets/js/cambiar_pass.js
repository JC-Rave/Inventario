$(document).ready(iniciar);

function iniciar(){
	window.setTimeout(function() {
      $("#caja-cargando").hide();
    },1300);
	//cambiar contraseña
	$("#btn_cambiar").on("click", cambiarPass);

	//utilizo una de las funciones de escuha del teclado para el diseño de interfaz
	$('#actual_pass').keyup(function() {diseño(!error?'':'actual_pass');});
	$('#new_pass').keyup(function() {diseño(!error?'':'new_pass');});
	$('#confirm_new_pass').keyup(function() {diseño(!error?'':'confirm_new_pass');});
}

function cambiarPass() {
	$.ajax({
		type:'post',
		data:$("#form_cambiar").serialize(),
		dataType:'json',
		url:base_url('Login_controller/cambiar_pass'),
		success:function(respuesta) {
			if (respuesta[0].res=='invalid') {
				invalidar_campos(respuesta);

			}else if (!respuesta[0].res){
				error=true;
				if (respuesta[0].actual.length>0) {
					$('#actual_pass').val('');
					$('#actual_pass').addClass('is-invalid');
					$('#error_actual').html(respuesta[0].actual);
				}

			}else{
				$('#actual_pass').val('');
				$('#new_pass').val('');
				$('#confirm_new_pass').val('');

				Swal.fire({
				  	position: 'top',
				  	icon: 'success',
				  	text: respuesta[0].mensaje,
				  	showConfirmButton: true
				}).then((result) => {
					$(location).attr('href',base_url("Login_controller"));
			    	setTimeout(function() {
				        $(".sidebar-mini").css('padding-right', '0px');
				    },170);
				});
			}
		}
	});
}

function invalidar_campos(respuesta) {
    error=true;
	/*
		en cada condicion consulto si un input o select es ivalido de ser asi:
		- agrego una clase para advertir al usuario que el campo es invalido
		- agrego el error que evita continuar con los procesos normalmente
	*/
	if (respuesta[0].actual.length>0) {
		$('#actual_pass').val('');
		$('#actual_pass').addClass('is-invalid');
		$('#error_actual').html(respuesta[0].actual);
	}

	if (respuesta[0].nueva.length>0) {
		$('#new_pass').val('');
		$('#new_pass').addClass('is-invalid');
		$('#error_nueva').html(respuesta[0].nueva);
	}

	if (respuesta[0].confirmar.length>0) {
		$('#confirm_new_pass').val('');
		$('#confirm_new_pass').addClass('is-invalid');
		$('#error_confirmar').html(respuesta[0].confirmar);
	}
}