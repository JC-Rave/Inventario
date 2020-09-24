$(document).ready(iniciar);
var codigoPed;
var foco;
var data;
var actualPed;
var actual;
var estado;
var pedido;
var actual;
var tabla;
var tb_pedido;
var tb_detalle;

function iniciar(){
	tabla=$("#tb_pedidos").DataTable();
	traducir();
	tb_pedido=$("#tb_detallePed").DataTable();
	tb_detalle=$("#tb_editarPed").DataTable();
	config_tablas();

	$.when(generar_filtro()).then(function () {
        window.setTimeout(function() {
            $("#caja-cargando").hide();
        },1300);
    });

    $("#tb_pedidos tbody").on("click", ".visualizar", ver_pedido);
    $("#tb_pedidos tbody").on("click", ".btn_preEditar", preEditar);
    $("#tb_editarPed tbody").on("click", ".preCambiar", preCambiar);
    $("#btn_editarCantidad").on("click", cambiar);
    $("#btn_editar").on("click", editar);

    $("#tb_detallePed tbody").on("click", 'td.sub_tabla', function () {
    	var tr=$(this).closest('tr');
    	var row=tb_pedido.row(tr);

    	if (row.child.isShown()) {
    		row.child.hide();
    		tr.removeClass('shown');
    	}else{
    		row.child(format(row.data())).show();
    		tr.addClass('shown');
    	}
    });

    $("#tb_editarPed tbody").on("click", 'td.sub_tabla', function () {
    	var tr=$(this).closest('tr');
    	var row=tb_detalle.row(tr);

    	if (row.child.isShown()) {
    		row.child.hide();
    		tr.removeClass('shown');
    	}else{
    		row.child(format(row.data(), true)).show();
    		tr.addClass('shown');
    	}
    });

	$('#cantidad').keyup(function() {diseño(!error?'':'cantidad');});
}

function preEditar() {
	foco=$(this).parents('tr');
    data=tabla.row(foco).data();
    actualPed=[tabla.row(foco).data(), tabla.row(foco)];
    estado=$("#tipo_user").val()=='ADMINISTRADOR'?data[4]:data[2];
    codigoPed=$("#tipo_user").val()=='ADMINISTRADOR'?data[7]:data[5];
    $("#tb_editarPed_wrapper .label").html('<label>Estado del pedido: '+estado+'</label>');

    limpiarTabla(tb_detalle);
    $.ajax({
    	type:'post',
    	data:{'pedido': $("#tipo_user").val()=='ADMINISTRADOR'?data[7]:data[5]},
    	dataType:'json',
    	url:base_url('Pedidos_controller/consultar_detalle'),
    	success:function(respuesta) {
    		$.each(respuesta, function () {
    			var fila=tb_detalle.row.add([
    				'',
    				this.descripcion,
    				this.nombre_producto,
    				`<center><img width='100%;' height='186px;' src='`+this.imagen+`'/></center>`,
    				this.nombre_unidad,
    				this.cantidad,
    				this.precio_1,
    				this.precio_2,
    				this.precio_3,
    				this.promedio,
    				this.total,
    				`<center>
			            <button type="button" class="btn btn-sm btn-info preCambiar" data-toggle="modal" data-target="#config_pedido">
			                <i class="fas fa-plus"></i>
			            </button><button type="button" class="btn btn-sm btn-danger eliminar">
		                    <i class="fas fa-trash"></i>
		                </button>
			        </center>`,
    				this.tipo,
    				this.cantidad_actual,
    				this.nombre_categoria,
    				this.descripcion_producto,
    				this.nit_1,
    				this.nit_2,
    				this.nit_3,
    				this.id_unidad,
    				this.nombre,
    				this.descripcion_1,
    				this.descripcion_2,
    				this.descripcion_3,
    				this.insertar,
    				this.id_categoria,
    				this.precio_producto,
    				this.observacion,
				]).draw(false).node();

				$(fila).find('td:eq(0)').addClass('sub_tabla');
				$(fila).find('td:eq(1)').addClass('config');
				$(fila).find('td:eq(1)').attr('style', 'min-width: 400px;');
				$(fila).find('td:eq(2)').addClass('p-0');
				$(fila).find('td:eq(2)').attr('style', 'min-width:200px;');
    		});
    	}
    });

    $("#md_editar").modal("show");
}

function editar() {
	pedido=[];
	$.each(tb_detalle.rows().data(), function () {
		pedido.push(this);
	});

	if (pedido.length) {
		Swal.fire({
			title: 'Estado del pedido',
			input: 'select',
			inputPlaceholder: 'Seleccionar estado',
			inputValue: 'Pendiente',
			inputOptions: {
				'Cancelado': 'Cancelar',
				'Pendiente': 'Pendiente',
				'Entregado': 'Entregado'
			},
			showCancelButton: true,
			confirmButtonText: 'continuar',
			cancelButtonText: 'cancelar',
			confirmButtonClass:'btn btn-info',
			cancelButtonClass:'btn btn-danger',
			inputValidator: (value) => {
				return new Promise((resolve) => {
					if (value!='') {
			   		 	resolve();
					}else{
						resolve('Seleccione el estado del pedido.');
					}
				});
			}
		}).then(function (estado) {
			if(estado.value!=undefined){
				pedido={'pedido':pedido, 'estado':estado, 'codigoPed': codigoPed};
				var men=estado.value=='Cancelado'?'Seguro desea cancelar el pedido.':(estado.value=='Entregado'?'Desea completar el pedido...':'');
				if (estado.value=='Cancelado' || estado.value=='Entregado') {
					Swal.fire({
				      	position: 'top',
				      	icon: 'info',
				      	title: estado.value=='Cancelado'?'Cancelar el pedido...':'Completar el pedido...',
				      	text:  estado.value=='Cancelado'?'Si continúa los productos no se agregaran a su inventario.':'Confirme su pedido. Una vez entragado el pedido los productos se agregaran a su inventario.',
				      	showCancelButton: true,
						confirmButtonText: 'Continuar',
						cancelButtonText: 'Cancelar',
						confirmButtonClass:'btn btn-info',
						cancelButtonClass:'btn btn-danger',
			  			confirmButtonText: 'Continuar',
			  			cancelButtonText: 'Cancelar'
				    }).then((result) => {
				        setTimeout(function() {
				            $(".sidebar-mini").css('padding-right', '0px');
				        },170);

				        if (result.value) {
							ejecutar(pedido);
				        }
				    });
				}else{
					ejecutar(pedido);			
				}
			}
		});
	}else{
		Swal.fire({
	      	position: 'top',
	      	icon: 'error',
	      	text: 'La tabla pedido esta vacia.',
	    }).then((result) => {
	        setTimeout(function() {
        		$(".sidebar-mini").css('padding-right', '0px');
	        },170);
	    });
	}
}

function ejecutar(pedido) {
	$("#caja-cargando").show();

	$.ajax({
		type:'post',
		data:pedido,
		dataType:'json',
		url:base_url('Pedidos_controller/editar_pedido'),
		success:function (respuesta) {
            $("#caja-cargando").hide();
            if (respuesta[0].res=='invalid') {
            	Swal.fire({
			      	position: 'top',
			      	icon: 'error',
			      	text: respuesta[0].pedido+'<br/>'+respuesta[0].estado,
			    }).then((result) => {
			        setTimeout(function() {
		        		$(".sidebar-mini").css('padding-right', '0px');
			        },170);
			    });

            }else if(respuesta[0].res){
            	var total=0;
            	$.each(pedido.pedido, function () {
            		total+=parseFloat(this[10]);
            	});

            	var accion;
            	if (pedido.estado.value=='Pendiente') {
            		accion=`<button class='btn_preEditar btn btn-sm btn-warning'>
                  		<i class='fas fa-edit'></i>
                    </button>`;
            	}else {
            		accion=``;
            	}

				var fecha=new Date();
				var finalizado=pedido.estado.value=='Pendiente'?actualPed[0][1]:
				(pedido.estado.value=='Cancelado'?'No Finalizado':
				fecha.getDate()+'/'+(fecha.getMonth()+1)+'/'+fecha.getFullYear()+' '+fecha.getHours()+':'+fecha.getMinutes()+':'+fecha.getSeconds());
            	if ($("#tipo_user").val()=='ADMINISTRADOR') {
	            	actualPed[1].data([
	            		actualPed[0][0],
						finalizado,
	            		actualPed[0][2],
	            		actualPed[0][3],
	            		pedido.estado.value,
	            		total,
	            		`<center>
                    		<button class="visualizar btn btn-sm btn-info">
	                        	<i class="fas fa-eye"></i>
	                      </button>`+accion,
	            		actualPed[0][7],
	        		]).draw(false);
            	}else{
	            	actualPed[1].data([
	            		actualPed[0][0],
						finalizado,
	            		pedido.estado.value,
	            		total,
	            		`<center>
                      		<button class="visualizar btn btn-sm btn-info">
	                        	<i class="fas fa-eye"></i>
	                      	</button>`+accion,
	            		actualPed[0][5],
	        		]).draw(false);
            	}

            	$("#md_editar .close").click();
            	alerta(respuesta);
            }
		}
	});
}

function preCambiar() {
    error=false;
    restablecer('cantidad');

	foco=$(this).parents('tr');
    data=tb_detalle.row(foco).data();
    actual=tb_detalle.row(foco);

    $("#cantidad").val(data[5]);
}

function cambiar() {
    var cantidad=$("#cantidad").val();

	if (!cantidad.length || cantidad<=0) {
        error=true;
        $('#cantidad').addClass('is-invalid');
        $('#error_cantidad').html('El campo no puede ser vacio, 0 o un valor negativo');
	}else{
		var total=cantidad*parseFloat(data[9]);
    	total=total.toFixed(2);

		actual.data([
			data[0],
			data[1],
			data[2],
			data[3],
			data[4],
			cantidad,
			data[6],
			data[7],
			data[8],
			data[9],
			total,
			data[11],
			data[12],
			data[13],
			data[14],
			data[15],
			data[16],
			data[17],
			data[18],
			data[19],
			data[20],
			data[21],
			data[22],
			data[23],
			data[24],
			data[25],
			data[26],
			data[27],
		]).draw(false);

		$("#config_pedido .close").click();
	}
}

function ver_pedido() {
	limpiarTabla(tb_pedido);
	foco=$(this).parents('tr');
    data=tabla.row(foco).data();
    estado=$("#tipo_user").val()=='ADMINISTRADOR'?data[4]:data[2];

    $("#tb_detallePed_wrapper .label").html('<label>Estado del pedido: '+estado+'</label>');
    $.ajax({
    	type:'post',
    	data:{'detallePedido': $("#tipo_user").val()=='ADMINISTRADOR'?data[7]:data[5]},
    	dataType:'json',
    	url:base_url('Pedidos_controller/detallePedido'),
    	success:function (respuesta) {
    		$.each(respuesta, function () {
    			var imagen=this.imagen==null?base_url('assets/img/sinFoto.png'):base_url('assets/files/'+this.imagen);
				var fila=tb_pedido.row.add([
					'',
					this.descripcion,
					'<center><img width="100%" height="186px" src="'+imagen+'"/></center>',
					this.nombre_unidad,
					this.cantidad,
					this.precio_1,
					this.precio_2,
					this.precio_3,
					this.promedio,
					this.total,
					this.observacion
				]).draw(false).node();  			

				$(fila).find('td:eq(0)').addClass('sub_tabla');
				$(fila).find('td:eq(2)').addClass('p-0');
				$(fila).find('td:eq(2)').attr('style', 'min-width:200px; max-width:200px;');
    		});
    	}
 	});

    $("#md_visualizar").modal("show");
}

function generar_filtro() {
    var promise=$.Deferred();

	$("#tipo_user").val()=='ADMINISTRADOR'?filtrartabla(tabla,2,$("#loggedUser").val()):'';

	var filtro=`<ul class="mb-0 pl-0" style="list-style-type:none">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>`;

	$.ajax({
		dataType:'json',
		url:base_url('Pedidos_controller'),
		success:function(respuesta) {
			if ($("#tipo_user").val()=='ADMINISTRADOR'){
				if (respuesta.length) {
					filtro+=`<ul style="list-style-type:none" class="opcion 
					dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">
						<li class="dropdown-item dropdown-submenu p-0">
	       					<a href="#" data-toggle="dropdown" class="dropdown-toggle 
	       					dropdown-item w-100">Instructores</a>
	        				<ul id="f_instru" class="dropdown-menu mr-2 rounded-0 
	        				white border-0 z-depth-1 r-100">`;

					$.each(respuesta, function() {
						filtro+=`<li class="dropdown-item p-0">
	            				<a href="#" class="dropdown-item w-100" data-usuario="`+this.documento_persona+`">`+this.nombre_persona+` `+this.apellido_persona+`</a>
						</li>`;
					});

					filtro+=`</ul>
					</li>
					<li class="dropdown-item dropdown-submenu p-0">
		   				<a href="#" data-toggle="dropdown" 
		   				class="dropdown-toggle dropdown-item w-100">Estados</a>
		    			<ul id="f_estado" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">
		    				<li class="dropdown-item p-0">
		        				<a href="#" class="dropdown-item w-100">En proceso</a>
							</li>
							<li class="dropdown-item p-0">
		        				<a href="#" class="dropdown-item w-100">Pendiente</a>
							</li>
							<li class="dropdown-item p-0">
		        				<a href="#" class="dropdown-item w-100">Cancelado</a>
							</li>
							<li class="dropdown-item p-0">
		        				<a href="#" class="dropdown-item w-100">Entregado</a>
							</li>
						</ul>
					</li>`;

				}else{
					filtro+=`<ul id="f_estado" style="list-style-type:none" 
					class="opcion dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">
						<li class="dropdown-item p-0">
		    				<a href="#" class="dropdown-item w-100">En proceso</a>
						</li>
						<li class="dropdown-item p-0">
		    				<a href="#" class="dropdown-item w-100">Pendiente</a>
						</li>
						<li class="dropdown-item p-0">
		    				<a href="#" class="dropdown-item w-100">Cancelado</a>
						</li>
						<li class="dropdown-item p-0">
		    				<a href="#" class="dropdown-item w-100">Entregado</a>
						</li>`;
				}

			}else{
				filtro+=`<ul id="f_estado" style="list-style-type:none" class="opcion 
				dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">
					<li class="dropdown-item p-0">
	    				<a href="#" class="dropdown-item w-100">En proceso</a>
					</li>
					<li class="dropdown-item p-0">
	    				<a href="#" class="dropdown-item w-100">Pendiente</a>
					</li>
					<li class="dropdown-item p-0">
	    				<a href="#" class="dropdown-item w-100">Cancelado</a>
					</li>
					<li class="dropdown-item p-0">
	    				<a href="#" class="dropdown-item w-100">Entregado</a>
					</li>`;
			}

			filtro+=`</ul></li></ul>`;	
			$("#filtro").html(filtro);

			$("#tipo_user").val()=='ADMINISTRADOR'?filtrartabla(tabla,4,'En proceso'):filtrartabla(tabla,2,'En proceso');
			$("#filtros").append(`<div id="estado_filtrada" class="order-2 pl-1 pr-1">
		        <div class="card">
		          	<div class="card-header p-1">
		        		<h3 class="card-title mr-1">En proceso</h3>
		            	<div class="card-tools ml-1 mr-1">
		                  <div id="eliminar_festado" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
		                  </div>
		                </div>
		          	</div>
		        </div>
		  	</div>`);
		  	$("#eliminar_festado").on('click', function() {
				$("#tipo_user").val()=='ADMINISTRADOR'?filtrartabla(tabla,4,''):filtrartabla(tabla,2,'');
		  		$("#estado_filtrada").remove();
		  	});

			if ($("#tipo_user").val()=='ADMINISTRADOR'){
				$("#f_instru a").on("click", function() {
					filtrartabla(tabla,2,$(this).data('usuario'));
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
					  		filtrartabla(tabla,2,$("#loggedUser").val());
					  		$("#instru_filtrada").remove();
					  	});
					}
				});
			}

			$("#f_estado a").on("click", function() {
				if ($("#tipo_user").val()=='ADMINISTRADOR'){
					filtrartabla(tabla,4,$(this).text());
				}else{
					filtrartabla(tabla,2,$(this).text());
				}

				if ($("#estado_filtrada").length) {
					$("#estado_filtrada h3").html($(this).text());

				}else{
					$("#filtros").append(`<div id="estado_filtrada" class="order-2 pl-1 pr-1">
				        <div class="card">
				          	<div class="card-header p-1">
				        		<h3 class="card-title mr-1">`+$(this).text()+`</h3>
				            	<div class="card-tools ml-1 mr-1">
				                  <div id="eliminar_festado" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
				                  </div>
				                </div>
				          	</div>
				        </div>
				  	</div>`);

				  	$("#eliminar_festado").on('click', function() {
				  		if ($("#tipo_user").val()=='ADMINISTRADOR'){
							filtrartabla(tabla,4,'');
						}else{
							filtrartabla(tabla,2,'');
						}
				  		$("#estado_filtrada").remove();
				  	});
				}
			});
		}
	});
	
	window.setTimeout(function () {
        promise.resolve();
    },1500);
    return promise.promise();
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

function config_tablas() {
	$("#tipo_user").val()=='ADMINISTRADOR'?visualizarColumnaEntabla(tabla,[2,7],false):
	visualizarColumnaEntabla(tabla,5,false);

  	visualizarColumnaEntabla(tb_pedido,[5,6,7,10],false);
  	visualizarColumnaEntabla(tb_detalle,[2,6,7,8,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27],false);
	$("#tb_pedidos").attr('style', 'width:100% !important;');
	$("#tb_detallePed").attr('style', 'width:100% !important;');
	$("#tb_editarPed").attr('style', 'width:100% !important;');
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
        "dom":'<"row"<"label col-5 col-md-6"><"col-4 pl-0 col-md-6"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row justify-content-left"<"col-sm-12 col-md-12"p>>',
        "lengthMenu": [ [5, 25, 50, -1], [5, 25, 50, "All"] ],
        "lengthChange": false,
        "order":[[1, "asc"]]
    });
}

function format(d, proceso=false) {
	var precios, observacion;
	if (proceso) {
		precios=[d[6], d[7], d[8]];
		observacion=d[27];
	}else{
		precios=[d[5], d[6], d[7]];
		observacion=d[10];
	}

	var subTabla=`<table cellpadding="5" cellspacing="0" border="0" style="width: 100%;">
		<tr>
			<td><b class="font-weight-bold">Precio empresa 1:</b></td>
			<td>`+precios[0]+`</td>
		</tr>`;

	if (precios[1]!='') {
		subTabla+=`<tr>
			<td><b class="font-weight-bold">Precio empresa 2:</b></td>
			<td>`+precios[1]+`</td>
		</tr>`;
	}

	if (precios[2]!='') {
		subTabla+=`<tr>
			<td><b class="font-weight-bold">Precio empresa 3:</b></td>
			<td>`+precios[2]+`</td>
		</tr>`;
	}

	subTabla+=`<tr>
		<td><b class="font-weight-bold">Observaciones:</b></td>
		<td style="text-align: center;">`+observacion+`</td>
	</tr></table>`;

	return subTabla;
}
