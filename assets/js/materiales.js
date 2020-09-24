$(document).ready(iniciar);
var tabla;
var actual;

function iniciar() {
	traducir();
    $("#provee_wrapper .label").html('<label>Proveedores</label>');
	tabla=$("#tb_materiales").DataTable();

	generar_selects();
    $.when(generar_filtro(true)).then(function () {
        window.setTimeout(function() {
            $("#caja-cargando").hide();
        },1300);
    });

	$("#btn_registrar").on("click", registrar);
	$("#btn_editar").on("click", editar);

	$(".cancelar").on("click", function(event) {cancelar();});
	escuchas();

	$("#tb_materiales tbody").on("click", ".visualizar", ver_material);
	$("#tb_materiales tbody").on("click", ".btn_preEditar", pre_editar);
}

function registrar() {
	$("#caja-cargando").show();
	$.ajax({
		type:'post',
		data:$("#registrar_material").serialize(),
		dataType:'json',
		url:base_url('Materiales_controller/reg_material'),
		success:function(respuesta) {
            $("#caja-cargando").hide();
			if (respuesta[0].res=='invalid') {
                invalidar_campos(respuesta);

			}else if (!respuesta[0].res) {
				alerta(respuesta);

			}else{
				var imagen=respuesta[0]['datos'].foto!=''?base_url('assets/files/'+respuesta[0]['datos'].foto):base_url('assets/img/sinFoto.png');
				var fila=tabla.row.add([
					respuesta[0]['datos'].documento,
					respuesta[0]['datos'].persona,
					$("#nombre").val(),
					`<img class="img-fluid" src="`+imagen+`"/>`,
					$("#categoria option:selected").text(),
					$("#ubicacion option:selected").text(),
					$("#cantidad").val(),
					$("#unidad option:selected").text(),
					`<center>
                        <button class="visualizar btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </button>

                        <button class="btn_preEditar btn btn-sm btn-warning"  data-toggle="modal" data-target="#md_editar">
                            <i class="fas fa-edit"></i>
                        </button>
                    </center>`,
					$("#descripcion").val(),
					$("#precio").val(),
					respuesta[0]['datos'].foto,
					respuesta[0]['datos'].imgNombre
				]).draw(false).node();

                // agrego clases a las celdas necesarias para visulizacion especifica
                $(fila).find('td:eq(0)').addClass('visualizar');
				$(fila).find('td:eq(1)').addClass('visualizar');
				$(fila).find('td:eq(2)').addClass('p-0 visualizar');
				$(fila).find('td:eq(3)').addClass('visualizar');
				$(fila).find('td:eq(4)').addClass('visualizar');
				$(fila).find('td:eq(5)').addClass('visualizar');
				$(fila).find('td:eq(6)').addClass('visualizar');

                error=false;
                $("#md_registrar .close").click();
                limpiar();

                alerta(respuesta);
                generar_filtro();

                if ($("#instru_filtrada").length){
                	$("#eliminar_finstru").click();
                }
			}
		}
	}).fail(function(){
		$("#caja-cargando").hide();
	});
}

function pre_editar() {
	$("#edit_categoria option").attr('selected', false);
	$("#edit_ubicacion option").attr('selected', false);
	$("#edit_unidad option").attr('selected', false);
	$("#edit_encargar option").attr('selected', false);

	cancelar([
		'edit_nombre', 
		'edit_cantidad', 
		'edit_precio', 
		'edit_categoria', 
		'edit_unidad', 
		'edit_ubicacion', 
		'edit_descripcion'
	]);

	actual=[];
	var foco=$(this).parents('tr');
    var material=tabla.row(foco).data();

    actual=[material[2], tabla.row(foco), material[0]];
	$("#md_editar h4").html('Editar material: '+material[2]);

	$("#edit_nombre").val(material[2]);
	$("#edit_cantidad").val(material[6]);
	$("#edit_precio").val(material[10]);
	$("#edit_categoria option:contains("+material[4]+")").attr('selected', true);
	$("#edit_unidad option:contains("+material[7]+")").attr('selected', true);
	if (material[5].length) {
		$("#edit_ubicacion option:contains("+material[5]+")").attr('selected', true);
	}else{
		$("#edit_ubicacion").val(0);
	}
	$("#edit_descripcion").val(material[9]);
	$("#edit_encargar").val($("#loggedUser").val()==material[0]?'Seleccionar instructor':material[0]);

	var options=$("#selectEdit_imagen .dd-option-text").toArray();
	var text=material[12]!=''?material[12]:'Seleccionar Imagen';

	for(var i=0; i<options.length; i++){
		if($(options[i]).text()==text){
			$("#selectEdit_imagen").ddslick('select', {index: i});
			break;
		}
	}
}

function editar() {
	$("#caja-cargando").show();
	var json={
		'nombre_actual': actual[0],
		'edit_nombre':  $("#edit_nombre").val(),
		'edit_cantidad': $("#edit_cantidad").val(),
		'edit_precio': $("#edit_precio").val(),
		'edit_imagen': $("#edit_imagen").val(),
		'edit_categoria': $("#edit_categoria").val(),
		'edit_unidad': $("#edit_unidad").val(),
		'edit_ubicacion': $("#edit_ubicacion").val(),
		'edit_encargar': [actual[2], $("#tipoUser").val()=='ADMINISTRADOR'?$("#edit_encargar").val():''],
		'edit_descripcion': $("#edit_descripcion").val(),
	};

	$.ajax({
		type:'post',
		data:json,
		dataType:'json',
		url:base_url('Materiales_controller/editar_material'),
		success:function(respuesta){
            $("#caja-cargando").hide();
			if (respuesta[0].res=='invalid') {
                invalidar_campos(respuesta, ['edit_nombre', 'edit_cantidad', 'edit_precio', 'edit_categoria', 'edit_unidad', 'edit_ubicacion', 'edit_descripcion']);

			}else if (!respuesta[0].res) {
				alerta(respuesta);

			}else if (respuesta[0].afectadas!=0){
				var foto=respuesta[0]['datos'].foto!=''?base_url('assets/files/'+respuesta[0]['datos'].foto):base_url('assets/img/sinFoto.png');

				actual[1].data({
					0:respuesta[0]['datos'].documento,
					1:respuesta[0]['datos'].persona,
					2:$("#edit_nombre").val(),
					3:`<img class="img-fluid" src="`+foto+`"/>`,
					4:$("#edit_categoria option:selected").text(),
					5:$("#edit_ubicacion option:selected").text(),
					6:$("#edit_cantidad").val(),
					7:$("#edit_unidad option:selected").text(),
					8:`<center>
                        <button class="visualizar btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </button>

                        <button class="btn_preEditar btn btn-sm btn-warning"  data-toggle="modal" data-target="#md_editar">
                            <i class="fas fa-edit"></i>
                        </button>
                    </center>`,
					9:$("#edit_descripcion").val(),
					10:$("#edit_precio").val(),
					11:respuesta[0]['datos'].foto,
					12:respuesta[0]['datos'].imgNombre
				}).draw(false);

                error=false;
                $("#md_editar .close").click();

                alerta(respuesta);
                generar_filtro();

				$("#edit_imagen").val('');
				$("#edit_holder_imagen").html('Seleccionar Imagen');

                if ($("#instru_filtrada").length){
                	filtrartabla(tabla,0,actual[2]);
                }
			}else{
                $("#md_editar .close").click();
			}
		}
	}).fail(function(){
		$("#caja-cargando").hide();
	});
}

function ver_material() {
	var provee=$("#provee").DataTable();
	limpiarTabla(provee);

    var foco=$(this).parents('tr');
    var data=tabla.row(foco).data();

    $("#ver_nombre").val(data[2]);
    $("#ver_cantidad").val(data[6]);
    $("#ver_precio").val(data[10]);
    $("#ver_ubicacion").val(data[5]);
    $("#ver_descripcion").val(data[9]);
    $("#ver_categoria").val(data[4]);
    $("#ver_unidad").val(data[7]);
    $("#ver_encargar").val(data[1]);
    $("#ver_imagen").attr({'src': data[11]!=''?base_url('assets/files/'+data[11]):base_url('assets/img/sinFoto.png'), 'title': data[12]!=''?data[12]:'sin imagen'});

    $("#md_visualizar h4").html('Detalle del material: '+data[2]);

 	$.ajax({
 		type:'post',
 		data:{'nombre': data[2], 'usuario': data[0]},
 		dataType:'json',
 		url:base_url('Materiales_controller/ver_provMaterial'),
 		success:function(respuesta) {
 			$.each(respuesta, function(){
 				var fila=provee.row.add([
 					this.nit,
 					this.nombre_proveedor,
 					this.precio,
 					this.descripcion,
 					`<a target="_black" style="color: #3B89EA" href="`+this.url+`">`+acortar(this.url)+`</a>`,
				]).draw(false).node();

				$(fila).find('td:eq(3)').addClass('config');
				$(fila).find('td:eq(4)').css('width', '100');
			});
 		}
 	});
    $("#md_visualizar").modal("show");
}

function cancelar(ids=['nombre', 'cantidad', 'precio', 'categoria', 'unidad', 'ubicacion', 'descripcion']) {
    error=false;
    restablecer(ids[0]);
    restablecer(ids[1]);
    restablecer(ids[2]);
    restablecer(ids[3]);
    restablecer(ids[4]);
    restablecer(ids[5]);
    restablecer(ids[6]);

    if (ids[0]=='nombre') {
    	limpiar();
    }

	$("#selectImagen").ddslick('select', {index: 0});
}

function generar_selects() {
	$(".imagen-select, .imagen-select2").ddslick({
		width: '100%',
		height: '250px',
		background: '#fff',
		onSelected: function(selectedData) {
			$(".dd-selected-image").css({'width': '34px', 'height': '32px'});
			$(".dd-selected-text").css({'line-height': '32px', 'margin-bottom': '0'});
			$(".dd-option-image").css({'max-width': '100px', 'width': '100px', 'height': '100px'});
		}
	});

	$(".dd-container, .dd-select, .dd-selected").css('height', '38px');
	$("#selectImagen .dd-selected-value").attr({'name': 'imagen', 'id': 'imagen'});
	$("#selectEdit_imagen .dd-selected-value").attr({'name': 'edit_imagen', 'id': 'edit_imagen'});
	$(".dd-selected-image").css({'width': '34px', 'height': '32px'});
	$(".dd-selected-text").css({'line-height': '32px', 'margin-bottom': '0'});
	$(".dd-selected").css('padding', '2px 7px');
	$(".dd-option-image").css({'max-width': '100px', 'width': '100px', 'height': '100px'});
	$(".dd-select").on("click", function () {
		$(".dd-option-text").css({'margin-bottom': '0', 'line-height': '100px'});
	})

	$.ajax({
		dataType:'json',
		url:base_url('Materiales_controller/generar_selects'),
		success:function(respuesta) {
			var options;

			$.each(respuesta['categorias'], function(){
				var opcion="<option value='"+this.id_categoria+"'>"+this.nombre_categoria+"</option>";
				$("#categoria").append(opcion);
				$("#edit_categoria").append(opcion);
			});


			$.each(respuesta['medidas'], function(){
				var opcion="<option value='"+this.id_unidad+"'>"+this.nombre_unidad+"</option>";
				$("#unidad").append(opcion);
				$("#edit_unidad").append(opcion);
			});

			$.each(respuesta['personas'], function(){
				var opcion="<option value='"+this.documento_persona+"'>"+this.nombre_persona+" "+this.apellido_persona+"</option>";
				$("#encargar").append(opcion);
				$("#edit_encargar").append(opcion);
			});
		}
	});
}

function generar_filtro(cargador=false) {
	var promise;
	cargador?promise=$.Deferred():'';

  	visualizarColumnaEntabla(tabla,[0,9,10,11,12],false);
	$("#tb_materiales").attr('style', 'width:100% !important;');

	var filtro=`<ul class="mb-0 pl-0" style="list-style-type:none">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>
    		<ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">`;

	$.ajax({
		type:'post',
		dataType:'json',
		url:base_url('Materiales_controller'),
		success:function(respuesta) {
			if (respuesta['usuarios'].length) {
				filtro+=`<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Instructores</a>
        			<ul id="f_instru" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">`;

				for (var i=0; i<respuesta['usuarios'].length; i++) {
					filtro+=`<li class="dropdown-item p-0">
            				<a href="#" class="dropdown-item w-100" data-usuario="`+respuesta['usuarios'][i].id_usuario+`">`+respuesta['usuarios'][i].nombre_persona+` `+respuesta['usuarios'][i].apellido_persona+`</a>
					</li>`;
				}

				filtro+=`</ul></li>`;
			}

			if (respuesta['categorias'].length) {
				filtro+=`<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Categorias</a>
        			<ul id="f_categoria" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">`;

				for (var i=0; i<respuesta['categorias'].length; i++) {
					filtro+=`<li class="dropdown-item p-0">
            				<a href="#" class="dropdown-item w-100">`+respuesta['categorias'][i].nombre_categoria+`</a>
					</li>`;
				}

				filtro+=`</ul></li>`;
			}

			if (respuesta['lineas'].length) {
				filtro+=`<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Ubicación</a>
        			<ul id="f_linea" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">`;

				for (var i=0; i<respuesta['lineas'].length; i++) {
					filtro+=`<li class="dropdown-item p-0">
            				<a href="#" class="dropdown-item w-100">`+respuesta['lineas'][i].nombre_linea+`</a>
					</li>`;
				}

				filtro+=`</ul></li>`;
			}

			if (respuesta['unidades'].length) {
				filtro+=`<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Unidades</a>
        			<ul id="f_unidades" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">`;

				for (var i=0; i<respuesta['unidades'].length; i++) {
					filtro+=`<li class="dropdown-item p-0">
            				<a href="#" class="dropdown-item w-100">`+respuesta['unidades'][i].nombre_unidad+`</a>
					</li>`;
				}

				filtro+=`</ul></li>`;
			}

			filtro+=`</ul></li></ul>`;	
			$("#filtro").html(filtro);

			$("#f_instru a").on("click", function() {
				filtrartabla(tabla,0,$(this).data('usuario'));
				if ($("#instru_filtrada").length) {
					$("#instru_filtrada h3").html($(this).text());

				}else{
					$("#filtros").append(`<div id="instru_filtrada" class="order-1 pl-1 pr-1">
				        <div class="card">
				          	<div class="card-header p-1">
				        		<h3 class="card-title mr-1">`+$(this).text()+`</h3>
				            	<div class="card-tools ml-1 mr-1">
				                  <div id="eliminar_finstru" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
				                  </div>
				                </div>
				          	</div>
				        </div>
				  	</div>`);

				  	$("#eliminar_finstru").on('click', function() {
				  		filtrartabla(tabla,0,$("#loggedUser").val());
				  		$("#instru_filtrada").remove();
				  	});

				}
			});

			$("#f_categoria a").on("click", function() {
				filtrartabla(tabla,4,$(this).text());
				if ($("#categoria_filtrada").length) {
					$("#categoria_filtrada h3").html($(this).text());

				}else{
					$("#filtros").append(`<div id="categoria_filtrada" class="order-2 pl-1 pr-1">
				        <div class="card">
				          	<div class="card-header p-1">
				        		<h3 class="card-title mr-1">`+$(this).text()+`</h3>
				            	<div class="card-tools ml-1 mr-1">
				                  <div id="eliminar_fcategoria" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
				                  </div>
				                </div>
				          	</div>
				        </div>
				  	</div>`);

				  	$("#eliminar_fcategoria").on('click', function() {
				  		filtrartabla(tabla,4,'');
				  		$("#categoria_filtrada").remove();
				  	});
				}
			});

			$("#f_linea a").on("click", function() {
				filtrartabla(tabla,5,$(this).text());
				if ($("#linea_filtrada").length) {
					$("#linea_filtrada h3").html($(this).text());

				}else{
					$("#filtros").append(`<div id="linea_filtrada"  class="order-3 pl-1 pr-1">
				        <div class="card">
				          	<div class="card-header p-1">
				        		<h3 class="card-title mr-1">`+$(this).text()+`</h3>
				            	<div class="card-tools ml-1 mr-1">
				                  <div id="eliminar_flinea" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
				                  </div>
				                </div>
				          	</div>
				        </div>
				  	</div>`);

				  	$("#eliminar_flinea").on('click', function() {
				  		filtrartabla(tabla,5,'');
				  		$("#linea_filtrada").remove();
				  	});
				}
			});

			$("#f_unidades a").on("click", function() {
				filtrartabla(tabla,7,$(this).text());
				if ($("#unidad_filtrada").length) {
					$("#unidad_filtrada h3").html($(this).text());

				}else{
					$("#filtros").append(`<div id="unidad_filtrada" class="order-4 pl-1 pr-1">
				        <div class="card">
				          	<div class="card-header p-1">
				        		<h3 class="card-title mr-1">`+$(this).text()+`</h3>
				            	<div class="card-tools ml-1 mr-1">
				                  <div id="eliminar_funidad" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
				                  </div>
				                </div>
				          	</div>
				        </div>
				  	</div>`);

				  	$("#eliminar_funidad").on('click', function() {
				  		filtrartabla(tabla,7,'');
				  		$("#unidad_filtrada").remove();
				  	});
				}
			});
		}
	});

	filtrartabla(tabla,0,$("#loggedUser").val());

	if (cargador) {
	    window.setTimeout(function () {
	        promise.resolve();
	    },1500);
	    
	    return promise.promise();
	}
}

function escuchas() {
	$('#nombre').keyup(function() {diseño(!error?'':'nombre');});
	$('#cantidad').keyup(function() {diseño(!error?'':'cantidad');});
	$('#precio').keyup(function() {diseño(!error?'':'precio');});
	$('#descripcion').keyup(function() {diseño(!error?'':'descripcion');});
	$('#categoria').mouseup(function() {diseño(!error?'':'categoria');});
	$('#unidad').mouseup(function() {diseño(!error?'':'unidad');});
	$('#ubicacion').mouseup(function() {diseño(!error?'':'ubicacion');});

	$('#edit_nombre').keyup(function() {diseño(!error?'':'edit_nombre');});
	$('#edit_cantidad').keyup(function() {diseño(!error?'':'edit_cantidad');});
	$('#edit_precio').keyup(function() {diseño(!error?'':'edit_precio');});
	$('#edit_descripcion').keyup(function() {diseño(!error?'':'edit_descripcion');});
	$('#edit_categoria').mouseup(function() {diseño(!error?'':'edit_categoria');});
	$('#edit_unidad').mouseup(function() {diseño(!error?'':'edit_unidad');});
	$('#edit_ubicacion').mouseup(function() {diseño(!error?'':'edit_ubicacion');});
}

function invalidar_campos(respuesta, ids=['nombre', 'cantidad', 'precio', 'categoria', 'unidad', 'ubicacion', 'descripcion']) {
    error=true;
	/*
		en cada condicion consulto si un input o select es ivalido de ser asi:
		- agrego una clase para advertir al usuario que el campo es invalido
		- agrego el error que evita continuar con los procesos normalmente
	*/
	if (respuesta[0].nombre.length>0) {
		$('#'+ids[0]).val('');
		$('#'+ids[0]).addClass('is-invalid');
		$('#error_'+ids[0]).html(respuesta[0].nombre);
	}

	if (respuesta[0].cantidad.length>0) {
		$('#'+ids[1]).val('');
		$('#'+ids[1]).addClass('is-invalid');
		$('#error_'+ids[1]).html(respuesta[0].cantidad);
	}

	if (respuesta[0].precio.length>0) {
		$('#'+ids[2]).val('');
		$('#'+ids[2]).addClass('is-invalid');
		$('#error_'+ids[2]).html(respuesta[0].precio);
	}

	if (respuesta[0].categoria.length>0) {
		$('#'+ids[3]).addClass('is-invalid');
		$('#error_'+ids[3]).html(respuesta[0].categoria);
	}

	if (respuesta[0].unidad.length>0) {
		$('#'+ids[4]).addClass('is-invalid');
		$('#error_'+ids[4]).html(respuesta[0].unidad);
	}

	if (respuesta[0].ubicacion.length>0) {
		$('#'+ids[5]).addClass('is-invalid');
		$('#error_'+ids[5]).html(respuesta[0].ubicacion);
	}

	if (respuesta[0].descripcion.length>0) {
		$('#'+ids[6]).val('');
		$('#'+ids[6]).addClass('is-invalid');
		$('#error_'+ids[6]).html(respuesta[0].descripcion);
	}
}

function limpiar() {
	$("#nombre").val('');
	$("#cantidad").val('');
	$("#precio").val('');
	$("#imagen").val('');
	$("#categoria").val(0);
	$("#unidad").val(0);
	$("#ubicacion").val(0);
	$("#encargar").val('Seleccionar instructor');

	$("#descripcion").val('');
	$("#selectImagen").ddslick('select', {index: 0});
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

function acortar(url) {
    var url_modi;
    var n;

    for (var i=40; i<url.length; i+=40) {
        if (i==40) {
            url_modi=url.substr(0, i)+'<br/>';
            n=40;

        }else{
            url_modi+=url.substr(i-40, 40)+'<br/>';
            n+=40;
        }
    }

    if (url.length>40) {
        url_modi+=url.substr(n);

    }else{
        url_modi=url;
    }

    return url_modi;
}

function traducir() {
    $(".desing2").DataTable({
        "language":{
            "sProcessing": "Procesando ...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando ...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "botones": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        },
        "dom":'<"row"<"label col-sm-12 col-md-6"><"col-sm-12 col-md-6"f>><"row"<"col-sm-12 col-md-12"rt>><"row justify-content-left"<"col-sm-12 col-md-12"p>>',
        "lengthMenu": [ [5, 25, 50, -1], [5, 25, 50, "All"] ],
        "lengthChange": false
    });
}