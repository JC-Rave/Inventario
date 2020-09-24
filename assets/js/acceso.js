$(document).ready(iniciar);
var actual;
var tabla;

function iniciar() {
	window.setTimeout(function() {
      $("#caja-cargando").hide();
    },1300);
	tabla=$("#tb_usuarios").DataTable();
	$("#tb_usuarios").attr('style', 'width:100% !important;');
	generar_filtro();

	//activo una funccion al presionar el boton registrar
	$('#btn_registrar').on("click", registrar);

	//preparo el modal para editar
	$("#tb_usuarios tbody").on("click", ".pre_editar", pre_editar);

	//edito el usuario
	$("#btn_editar").on("click", editar);

	//pre-transferir
	$("#tranferir_inventario").on("click", pre_trans);

	//tranferir
	$("#btn_tranferir").on('click', tranferir_inv);

	//utilizo una de las funciones de escuha del teclado para el diseño de interfaz
	$('#cedula').keyup(function() {diseño(!error?'':'cedula');});
	$('#nombre').keyup(function() {diseño(!error?'':'nombre');});
	$('#apellido').keyup(function() {diseño(!error?'':'apellido');});
	$('#telefono').keyup(function() {diseño(!error?'':'telefono');});
	$('#usuario').keyup(function() {diseño(!error?'':'usuario');});
	$('#correo').keyup(function() {diseño(!error?'':'correo');});
	$('#confirmar').keyup(function() {diseño(!error?'':'confirmar');});

	//utilizo una de las funciones de escuha del mouse para el diseño de interfaz
	$('#linea').mouseup(function() {diseño(!error?'':'linea');});

	//abro el modal para ver los datos y del usuario
    $("#tb_usuarios tbody").on("click", ".visualizar", ver_usuario);
}

function registrar() {
	if ($("#tipo_user").val()=='1') {
		Swal.fire({
	      	position: 'top',
	      	icon: 'warning',
	      	text: 'Si haces Administrador al usuario, tus privilegios cambiaran a INSTRUCTOR',
	      	title: 'Se cerrara la sesión al completar el proceso',
	      	showCancelButton: true,
	      	cancelButtonColor: '#d33',
  			confirmButtonText: 'Continuar',
  			cancelButtonText: 'Cancelar'
	    }).then((result) => {
	        setTimeout(function() {
	            $(".sidebar-mini").css('padding-right', '0px');
	        },170);

	        if (result.value) {
				$.ajax({
					type: 'post',
					data:$("#registrar_usuarios").serialize(),
					dataType: 'json',
					url:base_url('Accesos/registrar_usuario'),
					success: function(respuesta){
						//verifico la respuesta llegada por clave res.
						if (respuesta[0].res=='invalid') {
			                invalidar_campos(respuesta);
			                if (respuesta[0].correo.length>0) {
								$('#correo').val('');
								$('#correo').addClass('is-invalid');
								$('#error_correo').html(respuesta[0].correo);
							}

							if (respuesta[0].confirmar.length>0) {
								$('#confirmar').val('');
								$('#confirmar').addClass('is-invalid');
								$('#error_confirmar').html(respuesta[0].confirmar);
							}

						}else{
							window.location.reload();
						}
					}
				});
	        }
	    });

	}else{
		$.ajax({
			type: 'post',
			data:$("#registrar_usuarios").serialize(),
			dataType: 'json',
			url:base_url('Accesos/registrar_usuario'),
			success: function(respuesta){
				//verifico la respuesta llegada por clave res.
				if (respuesta[0].res=='invalid') {
	                invalidar_campos(respuesta);
	                if (respuesta[0].correo.length>0) {
						$('#correo').val('');
						$('#correo').addClass('is-invalid');
						$('#error_correo').html(respuesta[0].correo);
					}

					if (respuesta[0].confirmar.length>0) {
						$('#confirmar').val('');
						$('#confirmar').addClass('is-invalid');
						$('#error_confirmar').html(respuesta[0].confirmar);
					}

				}else{
					alerta(respuesta);
					limpiar();
				}
			}
		});
	}
}

function pre_editar() {
	error=false;
    restablecer('cedula');
    restablecer('nombre');
    restablecer('apellido');
    restablecer('telefono');
    restablecer('usuario');
    restablecer('correo');
    restablecer('confirmar');

	actual=[];

	var foco=$(this).parents('tr');
    var user=tabla.row(foco).data();

    var linea;
    if (user[1]=='TICs') {
    	linea=1;
    }else if(user[1]=='Biotecnología'){
		linea=2;
    }else if(user[1]=='Nanotecnología'){
		linea=3;
    }else if(user[1]=='Química'){
		linea=4;
    }else if(user[1]=='Física'){
		linea=5;
    }else if(user[1]=='Matemática y diseño'){
		linea=6;
    }else if(user[1]=='Electrónica y robótica'){
		linea=7;
    }else{
    	linea=8;
    }
    
	$("#cedula").val(user[5]);
	$("#nombre").val(user[6]);
	$("#apellido").val(user[7]);
	$("#telefono").val(user[8]);
	$("#usuario").val($(user[0]).text());
    $("#linea").val(linea);
    $("#tipo_user").val(2);
    $("#estado").val($(user[3]).text()=='Activo'?'a':'i');

    $("#editar_usuario .titulo_edit").html('Editar usuario: '+$(user[0]).text());
    actual=[user[5], $(user[0]).text(), tabla.row(foco), user[9]];
}

function editar() {
	var documento=$("#cedula").val();
	var nombre=$("#nombre").val();
	var apellido=$("#apellido").val();
	var telefono=$("#telefono").val();
	var usuario=$("#usuario").val();
	var tipo_user=$("#tipo_user").val();
	var linea=$("#linea").val();
	var estado=$("#estado").val();

	var json={
		'documento': [actual[0], documento],
		'nombre': nombre,
		'apellido': apellido,
		'telefono': telefono,
		'usuario': [actual[1], usuario],
		'tipo_user': tipo_user,
		'linea': linea,
		'estado': estado
	}

	if ($("#tipo_user").val()=='1') {
		if ($("#estado").val()=='a') {
			Swal.fire({
		      	position: 'top',
		      	icon: 'warning',
		      	text: 'Si haces Administrador al usuario, tus privilegios cambiaran a INSTRUCTOR',
		      	title: 'Se cerrara la sesión al completar el proceso',
		      	showCancelButton: true,
		      	cancelButtonColor: '#d33',
	  			confirmButtonText: 'Continuar',
	  			cancelButtonText: 'Cancelar'
		    }).then((result) => {
		        setTimeout(function() {
		            $(".sidebar-mini").css('padding-right', '0px');
		        },170);

		        if (result.value) {
					$.ajax({
						type:'post',
						data:json,
						dataType:'json',
						url:base_url('Accesos/editar_usuario'),
						success: function(respuesta) {
							// verifico la respuesta llegada por clave res.
				            if (respuesta[0].res=='invalid') {
				                invalidar_campos(respuesta);
				                if (respuesta[0].usuario.length>0) {
									$('#usuario').val('');
									$('#usuario').addClass('is-invalid');
									$('#error_usuario').html(respuesta[0].usuario);
								}

								if (respuesta[0].estado.length>0) {
									$('#estado').val('');
									$('#estado').addClass('is-invalid');
									$('#error_estado').html(respuesta[0].estado);
								}

				            }else{
				            	//cierro el modal
				                $("#editar_usuario .close").click();

				                window.location.reload();
				            }
						}
					});
				}
			});	

		}else{
			Swal.fire({
		      	position: 'top',
		      	icon: 'error',
		      	text: 'No puedes darle privilegios de ADMINISTRADOR a un usuario inactivo.'
		    }).then((result) => {
		        setTimeout(function() {
		            $(".sidebar-mini").css('padding-right', '0px');
		        },170);
		    });
		}
	}else{
		$.ajax({
			type:'post',
			data:json,
			dataType:'json',
			url:base_url('Accesos/editar_usuario'),
			success: function(respuesta) {
				// verifico la respuesta llegada por clave res.
	            if (respuesta[0].res=='invalid') {
	                invalidar_campos(respuesta);
	                if (respuesta[0].usuario.length>0) {
						$('#usuario').val('');
						$('#usuario').addClass('is-invalid');
						$('#error_usuario').html(respuesta[0].usuario);
					}

					if (respuesta[0].estado.length>0) {
						$('#estado').val('');
						$('#estado').addClass('is-invalid');
						$('#error_estado').html(respuesta[0].estado);
					}

	            }else if (respuesta[0].res && respuesta[0]['afectadas'][0]!=0 || respuesta[0]['afectadas'][1]!=0){
	                var clase=estado=='a'?'bg-success':'bg-danger';
	                var imagen=actual[3]==''?base_url('assets/img/sin_foto.png'):base_url('assets/files/'+actual[3]);
	                estado=$("#estado option:selected").text();
	                linea=$("#linea option:selected").text();
	                tipo_user=$("#tipo_user option:selected").text();

	                actual[2].data({
	                    0:`<div>`+usuario+`<img src="`+imagen+`"/></div>`,
	                    1:linea,
	                    2:tipo_user,
	                    3:`<center style="border-radius: 5px;" class="font-weight-bold `+clase+`">`+estado+`</center>`,
	                    4:`<center>
	                        <button class="visualizar btn btn-sm btn-info">
	                            <i class="fas fa-eye"></i>
	                        </button>

	                        <button class="pre_editar btn btn-sm btn-warning" data-toggle="modal" data-target="#editar_usuario">
	                            <i class="fas fa-edit"></i>
	                        </button>
	                    </center>`,
	                    5:documento,
	                    6:nombre,
	                    7:apellido,
	                    8:telefono,
	                    9:actual[3]
	                }).draw(false);

	                //limpio los input de errores
	                error=false;

	                //cierro el modal
	                $("#editar_usuario .close").click();

	                alerta(respuesta);

	            }else{
	            	//cierro el modal
	                $("#editar_usuario .close").click();
	            }
			}
		});
	}
}

function ver_usuario() {
	var foco=$(this).parents('tr');
    var data=tabla.row(foco).data();

	$("#ver_documento").val(data[5]);
	$("#ver_nombre").val(data[6]);
	$("#ver_apellido").val(data[7]);
	$("#ver_telefono").val(data[8]);
	$("#ver_user").val($(data[0]).text());
    $("#ver_linea").val(data[1]);
    $("#ver_estado").val($(data[3]).text());
	$("#ver_imagen").attr('src', data[9]!=''?base_url('assets/files/'+data[9]):base_url('assets/img/sin_foto.png'));

    $("#ver_usuario").modal("show");
    $("#ver_usuario .titulo").html('Detalle del usuario: '+$(data[0]).text());
}

function pre_trans() {
	$("#md_tranferir input").val('');

	$.ajax({
		type:'post',
		dataType:'json',
		url:base_url('Accesos/preparar_select'),
		success:function(respuesta) {
			var options='<option disabled selected>Seleccionar</option>';

			for (var i=0; i<respuesta[0].length; i++) {
				options+=`<option value="`+respuesta[0][i].usuario+`">`+
				respuesta[0][i].nombre_persona+` `+respuesta[0][i].apellido_persona+`</option>`;
			}
			$("#tranferir_de").html(options);

			options='<option disabled selected>Seleccionar</option>';
			for (var i=0; i<respuesta[1].length; i++) {
				options+=`<option value="`+respuesta[1][i].usuario+`">`+
				respuesta[1][i].nombre_persona+` `+respuesta[1][i].apellido_persona+`</option>`	
			}
			$("#tranferir_a").html(options);

			$("#tranferir_de").change(function(){
				for (var i=0; i<tabla.rows().data().length; i++) {
					var fila=tabla.row(i).data();
					if ($(fila[0]).text()==$(this).val()) {
						$("#de_documento").val(fila[5]);
						$("#de_usuario").val($(fila[0]).text());
						$("#de_telefono").val(fila[8]);
						$("#de_linea").val(fila[1]);
						break;
					}
				}
			});

			$("#tranferir_a").change(function(){
				for (var i=0; i<tabla.rows().data().length; i++) {
					var fila=tabla.row(i).data();
					if ($(fila[0]).text()==$(this).val()) {
						$("#a_documento").val(fila[5]);
						$("#a_usuario").val($(fila[0]).text());
						$("#a_telefono").val(fila[8]);
						$("#a_linea").val(fila[1]);
						break;
					}
				}
			});
		}
	});
}

function tranferir_inv() {
	if ($("#tranferir_de").val()!=null && $("#tranferir_a").val()!=null) {
	    Swal.fire({
      		position: 'top',
	  		title: 'Desea continuar?',
	  		text: 'Verifique la transferencia, recuerde que ¡Una vez echa no podra revertirse.!',
	  		icon: 'question',
	  		showCancelButton: true,
	  		confirmButtonColor: '#3085d6',
	  		cancelButtonColor: '#d33',
	  		confirmButtonText: 'Continuar',
	  		cancelButtonText: 'Cancelar'

		}).then((result) => {
	  		if (result.value) {
				var json={
	    				'transDe':$("#tranferir_de").val(), 
	    				'transA':$("#tranferir_a").val()
    			};

	    		$.ajax({
	    			type:'post',
	    			data:json,
	    			dataType:'json',
	    			url:base_url('Accesos/tranferirProductos'),
	    			success:function(respuesta) {
	    				alerta(respuesta);

	    				//cierro el modal
                		$("#md_tranferir .close").click();
	    			}
	    		});
	  		}
		});
	}else{
		Swal.fire({
      		position: 'top',
      		icon: 'error',
      		text: 'Debe completar ambos campos antes de continuar.',
      		showConfirmButton: true
    	});
	}
}

function invalidar_campos(respuesta) {
    error=true;
	/*
		en cada condicion consulto si un input o select es ivalido de ser asi:
		- agrego una clase para advertir al usuario que el campo es invalido
		- agrego el error que evita continuar con los procesos normalmente
	*/
	if (respuesta[0].cedula.length>0) {
		$('#cedula').val('');
		$('#cedula').addClass('is-invalid');
		$('#error_documento').html(respuesta[0].cedula);
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

	if (respuesta[0].tipo_user.length>0) {
		$('#tipo_user').val('');
		$('#tipo_user').addClass('is-invalid');
		$('#error_tipo_user').html(respuesta[0].tipo_user);
	}

	if (respuesta[0].linea.length>0) {
		$('#linea').val(0);
		$('#linea').addClass('is-invalid');
		$('#error_linea').html(respuesta[0].linea);
	}
}

function alerta(respuesta) {
    Swal.fire({
      position: 'top',
      icon: respuesta[0].res==true?'success':'error',
      text: respuesta[0].mensaje,
      showConfirmButton: true
    }).then((result) => {
        setTimeout(function() {
            $(".sidebar-mini").css('padding-right', '0px');
        },170);
    });
}

function generar_filtro() {
  	visualizarColumnaEntabla(tabla,[5,6,7,8,9],false);

	$("#filtro").html(`<ul class="mb-0 pl-0" style="list-style-type:none">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>
    		<ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">
		      	<li class="dropdown-item dropdown-submenu p-0">
		        	<a href="#" data-toggle="dropdown" 
		        	class="dropdown-toggle dropdown-item w-100">Linea</a>
		        	<ul id="f_linea" class="dropdown-menu  rounded-0 white border-0 z-depth-1">
		          		<li class="dropdown-item p-0">
		            		<a href="#" class="dropdown-item w-100">Nanotecnología</a>
		          		</li>
		          		<li class="dropdown-item p-0">
		            		<a href="#" class="dropdown-item w-100">Matemáticas y diseño</a>
		          		</li>
			          	<li class="dropdown-item p-0">
			        	    <a href="#" class="dropdown-item w-100">Física</a>
		        	  	</li>
		        	  	<li class="dropdown-item p-0">
		            		<a href="#" class="dropdown-item w-100">TICs</a>
		          		</li>
		          		<li class="dropdown-item p-0">
		            		<a href="#" class="dropdown-item w-100">Biotecnología</a>
		          		</li>
			          	<li class="dropdown-item p-0">
			        	    <a href="#" class="dropdown-item w-100">Química</a>
		        	  	</li>
			          	<li class="dropdown-item p-0">
			        	    <a href="#" class="dropdown-item w-100">Electrónica y robótica</a>
		        	  	</li>
		        	  	<li class="dropdown-item p-0">
			        	    <a href="#" class="dropdown-item w-100">Administrativa</a>
		        	  	</li>
		        	</ul>
		      	</li>
      			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Estado</a>
        			<ul id="f_estado" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">
          				<li class="dropdown-item p-0">
            				<a href="#" class="dropdown-item w-100">Activo</a>
          				</li>
          				<li class="dropdown-item p-0">
            				<a href="#" class="dropdown-item w-100">Inactivo</a>
          				</li>
        			</ul>
      			</li>
    		</ul>
  		</li>
	</ul>`);

	$("#f_linea a").on("click", function() {
		filtrartabla(tabla,1,$(this).text());
		if ($("#linea_filtrada").length) {
			$("#linea_filtrada h3").html($(this).text());

		}else{
			$("#filtros").append(`<div id="linea_filtrada" class="order-1">
		        <div class="card">
		          	<div class="card-header p-1">
		        		<h3 class="card-title mr-2">`+$(this).text()+`</h3>
		            	<div class="card-tools mr-1">
		                  <div id="eliminar_flinea" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
		                  </div>
		                </div>
		          	</div>
		        </div>
		  	</div>`);

		  	$("#eliminar_flinea").on('click', function() {
		  		filtrartabla(tabla,1,'');
		  		$("#linea_filtrada").remove();
		  	});
		}
	});

	$("#f_estado a").on("click", function() {
		filtrartabla(tabla,3,$(this).text());
		if ($("#estado_filtrada").length) {
			$("#estado_filtrada h3").html($(this).text());

		}else{
			$("#filtros").append(`<div id="estado_filtrada" class="order-2 pl-2">
		        <div class="card">
		          	<div class="card-header p-1">
		        		<h3 class="card-title mr-2">`+$(this).text()+`</h3>
		            	<div class="card-tools mr-1">
		                  <div id="eliminar_festado" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
		                  </div>
		                </div>
		          	</div>
		        </div>
		  	</div>`);

		  	$("#eliminar_festado").on('click', function() {
		  		filtrartabla(tabla,3,'');
		  		$("#estado_filtrada").remove();
		  	});
		}
	});
}

function limpiar() {
	$('#cedula').val('');
	$('#nombre').val('');
	$('#apellido').val('');
	$('#telefono').val('');
	$('#correo').val('');
	$('#confirmar').val('');
	$('#tipo_user').val(3);
	$('#linea').val(0);
}


