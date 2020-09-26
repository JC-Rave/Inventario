$(document).ready(iniciar);
var foco="";
var data=[];
var filaPed="";
var filaPro="";
var desGeneral="";
var desIndividual=[];
var observacion="";
var promedio=0;
var total=0;
var actual="";
var pedido;

var tb_materiales;
var tb_devolutivos;
var tb_newPedido;
var tb_provee;
var tb_proveedores;
var tb_seleccionados;

function iniciar() {
	traducir();
	tb_materiales=$("#inv_materiales").DataTable();
	tb_devolutivos=$("#inv_devolutivos").DataTable();
	tb_newPedido=$("#new_pedido").DataTable();
	tb_provee=$("#provee").DataTable();
	tb_proveedores=$("#tb_proveedores").DataTable();
	tb_seleccionados=$("#tb_seleccionados").DataTable();
	config_select();
	$.when(config_tablas()).then(function () {
        window.setTimeout(function() {
            $("#caja-cargando").hide();
        },1300);
    });

	$("#btn_guardar_editar").on("click", function () {
		guardar_editar($("#btn_guardar_editar").text());
	});

	//config productos
	$("#inv_materiales tbody, #inv_devolutivos tbody").on("click", '.agregar', pre_agregar);
    $("#btn_continuar").on("click", agregar);    
    $("#btn_editar_detalle").on("click", editarDetalle);
    $("#new_pedido tbody").on("click", ".editar_detalles", pre_editarDetalle);
	$("#new_pedido tbody").on("click", '.eliminar', eliminar);

	//config nuevo producto
	$("#btn_agregarNew").on("click", function (event) {agregarNuevo(event.target.value)});
	$("#btn_editarNew").on("click", function (event) {editarNuveo(event.target.value)});
	$(".new_producto").on("click", function (event) {pre_agregarNuevo(event.target.value)});
    $("#new_pedido tbody").on("click", ".editarNew", pre_editarNuevo);

	//config proveedores
    $("#btn_agregarProv").on("click", agregarProv);
    $("#btn_editarProv").on("click", editarDetalleProv);
	$("#tb_proveedores tbody").on("click", '.agregarProv', pre_agregarProv);
    $("#tb_seleccionados tbody").on("click", ".editar_detallesProv", pre_editarDetalleProv);
    $("#tb_seleccionados tbody").on("click", ".eliminarProv", eliminarProv);

    $('#newNombre').keyup(function() {diseño(!error?'':'newNombre');});
    $('#newCategoria').change(function() {diseño(!error?'':'newCategoria');});
    $('#newCantidad').keyup(function() {diseño(!error?'':'newCantidad');});
    $('#newUnidad').change(function() {diseño(!error?'':'newUnidad');});
    $('#descripcion').keyup(function() {diseño(!error?'':'descripcion');});
    $('#precio_prov').keyup(function() {diseño(!error?'':'precio_prov');});
    $('#descripcion_prov').keyup(function() {diseño(!error?'':'descripcion_prov');});

    $("#new_pedido tbody").on("click", 'td.sub_tabla', function () {
    	var tr=$(this).closest('tr');
    	var row=tb_newPedido.row(tr);

    	if (row.child.isShown()) {
    		row.child.hide();
    		tr.removeClass('shown');
    	}else{
    		row.child(format(row.data())).show();
    		tr.addClass('shown');
    	}
    });
}

function guardar_editar(proceso) {
	proceso=proceso=='Editar'?'editar_pedido':'registrar_pedido';
	pedido=[];
	$.each(tb_newPedido.rows().data(), function () {
		pedido.push(this);
	});

	var value=proceso=='editar_pedido'?'En proceso':'';
	if (pedido.length) {
		Swal.fire({
			title: 'Estado del pedido',
			input: 'select',
			inputPlaceholder: 'Seleccionar estado',
			inputValue: value,
			inputOptions: {
				'En proceso': 'En proceso',
				'Pendiente': 'Generar'
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
	            $("#caja-cargando").show();

				pedido={'pedido':pedido, 'estado':estado, 'codigoPed': $("#codigoPed").val()};
				$.ajax({
					type:'post',
					data:pedido,
					dataType:'json',
					url:base_url('Pedidos_controller/'+proceso),
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

			            }else {
			            	alerta(respuesta);
			            }
					}
				});
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

//config productos
function pre_agregar() {
    limpiarTabla(tb_provee);
    $("#cantidad").val('');
    $("#proveedor").val(null).trigger('change');
    $("#btn_continuar").show();
    $("#btn_editar_detalle").hide();

    foco=$(this).parents('tr');
	data=foco.prevObject["0"].value.length?tb_devolutivos.row(foco).data():tb_materiales.row(foco).data();
	$("#config_pedido h4").html(`Especificación del producto: `+data[0]);

	$.ajax({
		type:'post',
		data:{'producto': data[0]},
		dataType:'json',
		url:base_url('Pedidos_controller/generar_select'),
		success:function(respuesta) {
			var options='';

			$.each(respuesta, function() {
				options+=`<option value="`+this.nit+`">`+this.nombre_proveedor+`</option>`;

				var fila=tb_provee.row.add([
					this.nit,
					this.nombre_proveedor,
					this.precio,
					this.descripcion,
					`<a target="_black" style="color: #3B89EA;" href="`+this.url+`">`+acortar(this.url)+`</a>`
				]).draw(false).node();

				$(fila).find('td:eq(2)').addClass('config');
			});

			$('#proveedor').html(options);
		}
	});
}

function agregar() {
    var cantidad=$("#cantidad").val();
    var proveedor=$("#proveedor").val();
    var respuesta=config(cantidad, proveedor);

    var precios=respuesta[1];
    if (respuesta[0]) {
    	if (foco.prevObject["0"].value.length) {
	        tb_devolutivos.row(foco).remove().draw(false);
	        var mas=[
	        	'',
	            desGeneral,
	            data[0],
	            data[1],
	            'Unidad',
	            cantidad,
	            precios[0],
	            precios[1]==undefined?'':precios[1],
	            precios[2]==undefined?'':precios[2],
	            promedio,
	            total,
	            `<center>
	                <button type="button" class="btn btn-sm btn-warning editar_detalles" data-toggle="modal" data-target="#config_pedido">
	                    <i class="fas fa-edit"></i>
	                </button><button type="button" class="btn btn-sm btn-danger eliminar">
	                    <i class="fas fa-trash"></i>
	                </button>
	            </center>`,
	            data[5],
	            '',
	            data[2],
	            data[3],
	            proveedor[0],
	            proveedor[1]==undefined?'':proveedor[1],
	            proveedor[2]==undefined?'':proveedor[2],
	            '',
	            data[7],
	            desIndividual[0],
	            desIndividual[1]==undefined?'':desIndividual[1],
	            desIndividual[2]==undefined?'':desIndividual[2],
	            data[5],
	            data[6],
	            data[8],
	            observacion
	        ];

    	}else{
	        tb_materiales.row(foco).remove().draw(false);
	        var mas=[
	        	'',
	            desGeneral,
	            data[0],
	            data[1],
	            data[3],
	            cantidad,
	            precios[0],
	            precios[1]==undefined?'':precios[1],
	            precios[2]==undefined?'':precios[2],
	            promedio,
	            total,
	            `<center>
	                <button type="button" class="btn btn-sm btn-warning editar_detalles" data-toggle="modal" data-target="#config_pedido">
	                    <i class="fas fa-edit"></i>
	                </button><button type="button" class="btn btn-sm btn-danger eliminar">
	                    <i class="fas fa-trash"></i>
	                </button>
	            </center>`,
	            data[7],
	            data[2],
	            data[4],
	            data[5],
	            proveedor[0],
	            proveedor[1]==undefined?'':proveedor[1],
	            proveedor[2]==undefined?'':proveedor[2],
	            '',
	            data[9],
	            desIndividual[0],
	            desIndividual[1]==undefined?'':desIndividual[1],
	            desIndividual[2]==undefined?'':desIndividual[2],
	            data[7],
	            data[8],
	            '',
	            observacion
	        ];
    	}

    	var fila=tb_newPedido.row.add(mas).draw(false).node();

		$(fila).find('td:eq(0)').addClass('sub_tabla');
		$(fila).find('td:eq(1)').addClass('config');
		$(fila).find('td:eq(1)').attr('style', 'min-width:400px');
		$(fila).find('td:eq(2)').addClass('p-0');
		$(fila).find('td:eq(2)').attr('style', 'min-width:200px');

        $("#config_pedido .close").click();
    }
}

function pre_editarDetalle() {
    limpiarTabla(tb_provee);
    $("#proveedor").val(null).trigger('change');
    $("#btn_continuar").hide();
    $("#btn_editar_detalle").show();

    foco=$(this).parents('tr');
    data=tb_newPedido.row(foco).data();
    filaPed=tb_newPedido.row(foco);
	$("#config_pedido h4").html(`Especificación del producto: `+data[2]);

    $("#cantidad").val(data[5]);
	$.ajax({
		type:'post',
		data:{'producto': data[2]},
		dataType:'json',
		url:base_url('Pedidos_controller/generar_select'),
		success:function(respuesta) {
			var options='';

			$.each(respuesta, function() {
				options+=`<option value="`+this.nit+`">`+this.nombre_proveedor+`</option>`;

				var fila=tb_provee.row.add([
					this.nit,
					this.nombre_proveedor,
					this.precio,
					this.descripcion,
					`<a target="_black" style="color: #3B89EA;" href="`+this.url+`">`+acortar(this.url)+`</a>`
				]).draw(false).node();

				$(fila).find('td:eq(2)').addClass('config');
			});

			$('#proveedor').html(options);
    		$("#proveedor").val([data[16], data[17], data[18]]).trigger('change');
		}
	});
}

function editarDetalle() {
    var cantidad=$("#cantidad").val();
    var proveedor=$("#proveedor").val();
    var respuesta=config(cantidad, proveedor);

    var precios=respuesta[1];
    if (respuesta[0]) {
        filaPed.data([
        	'',
            desGeneral,
            data[2],
            data[3],
            data[4],
            cantidad,
            precios[0],
            precios[1]==undefined?'':precios[1],
            precios[2]==undefined?'':precios[2],
            promedio,
            total,
            `<center>
                <button type="button" class="btn btn-sm btn-warning editar_detalles" data-toggle="modal" data-target="#config_pedido">
                    <i class="fas fa-edit"></i>
                </button><button type="button" class="btn btn-sm btn-danger eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </center>`,
            data[12],
            data[13],
            data[14],
            data[15],
            proveedor[0],
            proveedor[1]==undefined?'':proveedor[1],
            proveedor[2]==undefined?'':proveedor[2],
            '',
            data[20],
            desIndividual[0],
            desIndividual[1]==undefined?'':desIndividual[1],
            desIndividual[2]==undefined?'':desIndividual[2],
            data[24],
            data[25],
            data[26],
            observacion
        ]).draw(false);

        $("#config_pedido .close").click();
    }
}

function eliminar() {
    foco=$(this).parents('tr');
    data=tb_newPedido.row(foco).data();
   
    tb_newPedido.row(foco).remove().draw(false);
    if (data[12]=='Devolutivo') {
    	var fila=tb_devolutivos.row.add([
    		data[2],
    		data[3],
    		data[14],
    		data[15],
	        `<center>
	            <button value="devolutivos" type="button" class="btn btn-sm btn-info agregar" data-toggle="modal" data-target="#config_pedido">
	                <i class="fas fa-plus"></i>
	            </button>
	        </center>`,
	        data[12],
	        data[25],
	        data[20],
	        data[26]
		]).draw(false).node();


    }else if (data[12]=='Consumible') {
	    var fila=tb_materiales.row.add([
	        data[2],
	        data[3],
	        data[13],
	        data[4],
	        data[14],
	        data[15],
	        `<center>
	            <button type="button" class="btn btn-sm btn-info agregar" data-toggle="modal" data-target="#config_pedido">
	                <i class="fas fa-plus"></i>
	            </button>
	        </center>`,
	        data[12],
	        data[25],
	        data[20]
	    ]).draw(false).node();
    }

	$(fila).find('td:eq(1)').addClass('p-0');
	$(fila).find('td:eq(1)').attr('style', 'min-width:200px; max-width:200px')
}

function config(cantidad, proveedor) {
	var precios=[];

	if (!cantidad.length || !proveedor.length) {
        Swal.fire({
	      	position: 'top',
	      	icon: 'error',
	      	title: 'Debe llenar ambos campos',
	      	showConfirmButton: true
	    }).then((result) => {
	        setTimeout(function() {
	           $(".sidebar-mini").css('padding-right', '0px');
	        },170);
	    });
	    return [false, ''];

    }else{
    	desIndividual=[];
    	desGeneral='';
    	observacion='';
    	promedio=0;
    	total=0;
    	$.each(proveedor, function (vuelta, value) {
	    	$.each(tb_provee.rows().data(), function() {
	    		if (value==this[0]) {
	    			if (vuelta==proveedor.length-1) {
		    			desGeneral+=`<b class="font-weight-bold">Empresa `+(vuelta+1)+`: </b>`+this[3];
		    			observacion+=`<b class="font-weight-bold">Empresa `+(vuelta+1)+`:</b><br/>`+this[4];
	    			}else{
		    			desGeneral+=`<b class="font-weight-bold">Empresa `+(vuelta+1)+`: </b>`+this[3]+`<br/><br/>`;
		    			observacion+=`<b class="font-weight-bold">Empresa `+(vuelta+1)+`:</b><br/>`+this[4]+`<br/><br/>`;

	    			}
	    			desIndividual.push(this[3]);
	    			promedio+=parseFloat(this[2]);
	    			precios.push(this[2]);
	    			return false;
	    		}
	    	});
    	});

    	promedio=promedio/proveedor.length;
    	promedio=promedio.toFixed(2);
    	total=promedio*cantidad;
    	total=total.toFixed(2);

    	return [true, precios];
    }
}

//config nuevo producto
function pre_agregarNuevo(producto) {
	limpiar();

	$("#btn_editarNew").hide();
	$("#btn_agregarNew").show();
	$("#btn_agregarNew").attr('value', producto);

	if (producto=='Devolutivo') {
		$("#caja_unidad").hide();
		$("#descripcion").attr('style', 'height:124px;');
	}else{
		$("#caja_unidad").show();
		$("#descripcion").attr('style', 'height:209px;');
	}

	$("#config_newProducto h4").html('Pedido: '+producto+' nuevo');
	$("#newNombre").val('');
	$("#newCategoria").val(0);
	$("#newCantidad").val('');
	$("#newUnidad").val(0);
	$("#descripcion").val('');
	$("#selectImagen").ddslick('select', {index: 0});

	actual='';
	restablecerTabla();
}

function agregarNuevo(producto) {
	var nombre=$("#newNombre").val();
	var categoria=$("#newCategoria").val()==null?'':$("#newCategoria").val();
	var cantidad=$("#newCantidad").val();
	var unidad=[producto=='Consumible'?$("#newUnidad option:selected").text():'Unidad', $("#newUnidad").val()==null && producto=='Devolutivo'?'1':($("#newUnidad").val()==null?'':$("#newUnidad").val())];
	var imagen=$("#selectImagen").data('ddslick');
	var newdescripcion=$("#descripcion").val();
	var proveedores=tb_seleccionados.rows().data();
	var aux=false;

	imagen=[imagen.selectedData.imageSrc, imagen.selectedData.value];
	if (configNuevo(nombre, categoria, cantidad, unidad, newdescripcion, proveedores, aux)) {
		var fila=tb_newPedido.row.add([
			'',
			desGeneral,
			nombre,
			`<center><img width="100%" height="160px" src="`+imagen[0]+`"/></center>`,
			unidad[0],
			cantidad,
			proveedores[0][2],
			proveedores[1]==undefined?'':proveedores[1][2],
			proveedores[2]==undefined?'':proveedores[2][2],
			promedio,
	        total,
	        `<center>
	            <button type="button" class="btn btn-sm btn-warning editarNew" data-toggle="modal" data-target="#config_newProducto">
	                <i class="fas fa-edit"></i>
	            </button><button type="button" class="btn btn-sm btn-danger eliminar">
	                <i class="fas fa-trash"></i>
	            </button>
	        </center>`,
			'pedido',
			'',
			'',
			newdescripcion,
			proveedores[0][0],
			proveedores[1]==undefined?'':proveedores[1][0],
			proveedores[2]==undefined?'':proveedores[2][0],
			unidad[1],
			imagen[1],
			proveedores[0][3],
			proveedores[1]==undefined?'':proveedores[1][3],
			proveedores[2]==undefined?'':proveedores[2][3],
			producto,
			categoria,
			'',
			observacion
		]).draw(false).node();

		$(fila).find('td:eq(0)').addClass('sub_tabla');
		$(fila).find('td:eq(1)').addClass('config');
		$(fila).find('td:eq(1)').attr('style', 'min-width:400px');
		$(fila).find('td:eq(2)').addClass('p-0');
		$(fila).find('td:eq(2)').attr('style', 'min-width:200px');

	    $("#config_newProducto .close").click();
	}
}

function pre_editarNuevo() {
	limpiar();
	restablecerTabla();

	foco=$(this).parents('tr');
    data=tb_newPedido.row(foco).data();
    filaPed=tb_newPedido.row(foco);

	$("#btn_editarNew").show();
	$("#btn_agregarNew").hide();
	$("#btn_editarNew").attr('value', data[24]);

    actual=data[2];
    if (data[24]=='Devolutivo') {
		$("#caja_unidad").hide();
		$("#descripcion").attr('style', 'height:124px;');
	}else{
		$("#caja_unidad").show();
		$("#descripcion").attr('style', 'height:209px;');
	}

	$("#config_newProducto h4").html('Especificación del producto nuevo: '+data[2]);
	$("#newNombre").val(data[2]);
	$("#newCategoria").val(data[25]);
	$("#newCantidad").val(data[5]);
	$("#newUnidad").val(data[19]);
	$("#descripcion").val(data[15]);

	var options=$("#selectImagen .dd-option-text").toArray();
	for(var i=0; i<options.length; i++){
		if($(options[i]).text()==data[20]){
			$("#selectImagen").ddslick('select', {index: i});
			break;
		}
	}

	for(var i=0; i<3; i++){
		$.each(tb_proveedores.rows().data(), function(index){
			if (this[0]==data[16+i]) {
				tb_proveedores.row(':eq('+index+')').remove().draw(false);
				tb_seleccionados.row.add([
					this[0],
					this[1],
					data[6+i],
					data[21+i],
		            `<center>
		                <button type="button" class="btn btn-sm btn-warning editar_detallesProv" data-toggle="modal" data-target="#config_suministro">
		                    <i class="fas fa-edit"></i>
		                </button><button type="button" class="btn btn-sm btn-danger eliminarProv">
		                    <i class="fas fa-trash"></i>
		                </button>
		            </center>`,
					this[2],
					this[3],
					this[4]
				]).draw(false);

		    	return false;
			}
		});
	}

	if (tb_seleccionados.rows().data().length==3) {
		maxProv(`<center class="opciones">Sólo puede seleccionar <br/> 3 proveedores.</center>`);
	}
}

function editarNuveo(producto) {
	var nombre=$("#newNombre").val();
	var categoria=$("#newCategoria").val()==null?'':$("#newCategoria").val();
	var cantidad=$("#newCantidad").val();
	var unidad=[producto=='Consumible'?$("#newUnidad option:selected").text():'Unidad', $("#newUnidad").val()==null && producto=='Devolutivo'?'1':($("#newUnidad").val()==null?'':$("#newUnidad").val())];
	var imagen=$("#selectImagen").data('ddslick');
	var newdescripcion=$("#descripcion").val();
	var proveedores=tb_seleccionados.rows().data();
	var aux=false;

	imagen=[imagen.selectedData.imageSrc, imagen.selectedData.value];
	if(configNuevo(nombre, categoria, cantidad, unidad, newdescripcion, proveedores, aux)){
		filaPed.data([
			'',
			desGeneral,
			nombre,
			`<center><img width="100%" height="160px" src="`+imagen[0]+`"/></center>`,
			unidad[0],
			cantidad,
			proveedores[0][2],
			proveedores[1]==undefined?'':proveedores[1][2],
			proveedores[2]==undefined?'':proveedores[2][2],
			promedio,
	        total,
	        `<center>
	            <button type="button" class="btn btn-sm btn-warning editarNew" data-toggle="modal" data-target="#config_newProducto">
	                <i class="fas fa-edit"></i>
	            </button><button type="button" class="btn btn-sm btn-danger eliminar">
	                <i class="fas fa-trash"></i>
	            </button>
	        </center>`,
			'pedido',
	        '',
			'',
			newdescripcion,
			proveedores[0][0],
			proveedores[1]==undefined?'':proveedores[1][0],
			proveedores[2]==undefined?'':proveedores[2][0],
			unidad[1],
			imagen[1],
			proveedores[0][3],
			proveedores[1]==undefined?'':proveedores[1][3],
			proveedores[2]==undefined?'':proveedores[2][3],
			producto,
			categoria,
			'',
			observacion			
		]).draw(false);

	    $("#config_newProducto .close").click();
	}
}

function configNuevo(nombre, categoria, cantidad, unidad, newdescripcion, proveedores, aux) {
	$.each(tb_materiales.rows().data(), function() {
		if(this[0]==nombre){
			aux=true;
			return false;
		}
	});

	if (!aux) {
		$.each(tb_devolutivos.rows().data(), function() {
			if(this[0]==nombre){
				aux=true;
				return false;
			}
		});
	}

	if (nombre!=actual) {
		if (!aux) {
			$.each(tb_newPedido.rows().data(), function() {
				if(this[0]==nombre){
					aux=true;
					return false;
				}
			});
		}
	}

	if (aux || !nombre.length || !categoria.length || !cantidad.length || !unidad[1].length || !newdescripcion.length) {
		invalidar_campos(aux, nombre, categoria, cantidad, unidad[1], newdescripcion);
		$('#config_newProducto').animate({scrollTop: 0});
		return false;

	}else if(!proveedores.length){
        Swal.fire({
	      	position: 'top',
	      	icon: 'error',
	      	title: 'Debe seleccionar almenos un proveedor',
	      	showConfirmButton: true
	    }).then((result) => {
	        setTimeout(function() {
	           $(".sidebar-mini").css('padding-right', '0px');
	        },170);
	    });
	    return false;

	}else{
    	desIndividual=[];
    	desGeneral='';
    	observacion='';
    	promedio=0;
    	total=0;

    	$.each(proveedores, function(index) {
			if (index==proveedores.length-1) {
    			desGeneral+=`<b class="font-weight-bold">Empresa `+(index+1)+`: </b>`+this[3];
    			observacion+=`<b class="font-weight-bold">Empresa `+(index+1)+`:</b><br/><a target="_black" style="color: #3B89EA;" href="`+$(this[7]).text()+`">`+acortar($(this[7]).text())+`</a>`;
			}else{
    			desGeneral+=`<b class="font-weight-bold">Empresa `+(index+1)+`: </b>`+this[3]+`<br/><br/>`;
    			observacion+=`<b class="font-weight-bold">Empresa `+(index+1)+`:</b><br/><a target="_black" style="color: #3B89EA;" href="`+$(this[7]).text()+`">`+acortar($(this[7]).text())+`</a><br/><br/>`;

			}

			desIndividual.push(this[3])
			promedio+=parseFloat(this[2]);
    	});

    	promedio=promedio/proveedores.length;
    	promedio=promedio.toFixed(2);
    	total=promedio*cantidad;
    	total=total.toFixed(2);

    	return true;
    }
}

//config proveedores
function pre_agregarProv() {
    error=false;
    restablecer('precio_prov');
    restablecer('descripcion_prov');

    $("#btn_agregarProv").show();
    $("#btn_editarProv").hide();
    $("#precio_prov").val('');
    $("#descripcion_prov").val('');

    foco=$(this).parents('tr');
    data=tb_proveedores.row(foco).data();
}

function agregarProv() {
    var precio=$("#precio_prov").val();
    var descripcion=$("#descripcion_prov").val();

    if (configProv(precio, descripcion)) {
        tb_proveedores.row(foco).remove().draw(false);
        tb_seleccionados.row.add([
            data[0],
            data[1],
            precio,
            descripcion,
            `<center>
                <button type="button" class="btn btn-sm btn-warning editar_detallesProv" data-toggle="modal" data-target="#config_suministro">
                    <i class="fas fa-edit"></i>
                </button><button type="button" class="btn btn-sm btn-danger eliminarProv">
                    <i class="fas fa-trash"></i>
                </button>
            </center>`,
            data[2],
            data[3],
            data[4],
        ]).draw(false);

    	if (tb_seleccionados.rows().data().length==3) {
    		maxProv(`<center class="opciones">Sólo puede seleccionar <br/> 3 proveedores.</center>`);
    	}

        $("#config_suministro .close").click();
        error=false;
    }
}

function pre_editarDetalleProv() {
    error=false;
    $("#btn_agregarProv").hide();
    $("#btn_editarProv").show();
    restablecer('precio_prov');
    restablecer('descripcion_prov');

    foco=$(this).parents('tr');
    data=tb_seleccionados.row(foco).data();
    filaPro=tb_seleccionados.row(foco);

    $("#precio_prov").val(data[2]);
    $("#descripcion_prov").val(data[3]);
}

function editarDetalleProv() {
    var precio=$("#precio_prov").val();
    var descripcion=$("#descripcion_prov").val();

    if (configProv(precio, descripcion)) {
        filaPro.data([
            data[0],
            data[1],
            precio,
            descripcion,
            `<center>
                <button type="button" class="btn btn-sm btn-warning editar_detallesProv" data-toggle="modal" data-target="#config_suministro">
                    <i class="fas fa-edit"></i>
                </button><button type="button" class="btn btn-sm btn-danger eliminarProv">
                    <i class="fas fa-trash"></i>
                </button>
            </center>`,
            data[5],
            data[6],
            data[7],
        ]).draw(false);

        $("#config_suministro .close").click();
        error=false;
    }
}

function eliminarProv() {
    foco=$(this).parents('tr');
    data=tb_seleccionados.row(foco).data();
   
    tb_seleccionados.row(foco).remove().draw(false);
    tb_proveedores.row.add([
        data[0],
        data[1],
        data[5],
        data[6],
        data[7],
        `<center class="opciones">
            <button type="button" class="agregarProv btn btn-sm btn-info" data-toggle="modal" data-target="#config_suministro">
                <i class="fas fa-plus"></i>
            </button>
        </center>`
    ]).draw(false);

	if (tb_seleccionados.rows().data().length==2) {
		maxProv(`<center class="opciones">
            <button type="button" class="agregarProv btn btn-sm btn-info" data-toggle="modal" data-target="#config_suministro">
                <i class="fas fa-plus"></i>
            </button>
        </center>`);
	}
}

function configProv(precio, descripcion) {
	if (!precio.length || !descripcion.length) {
        error=true;
        if (!precio.length) {
            $('#precio_prov').addClass('is-invalid');
            $('#error_precioProv').html('El campo es obligatorio');
        }

        if (!descripcion.length) {
            $('#descripcion_prov').addClass('is-invalid');
            $('#error_desProv').html('El campo es obligatorio');
        }
        return false;

    }else{
        return true;
    }
}

function maxProv(proceso) {
	var proveedores=tb_proveedores.rows().data();
	limpiarTabla(tb_proveedores);
	$.each(proveedores, function () {
		tb_proveedores.row.add([
			this[0],
			this[1],
			this[2],
			this[3],
			this[4],
	        proceso
		]).draw(false);
	});
}

//config general
function config_select() {
	$(".imagen-select").ddslick({
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
	});

	$('#proveedor, #newProveedor').select2({
		language:{
			maximumSelected: function () {return 'Sólo puede seleccionar 3 Proveedores';},
		    noResults: function () {return 'No se encontraron resultados';},
		    searching: function () {return 'Buscando…';},
		    removeAllItems: function () {return 'Eliminar todos los elementos';},
		    errorLoading: function () {return 'No se pudieron cargar los resultados';},
		    loadingMore: function () {return 'Cargando más resultados…';}
		},
		maximumSelectionLength: 3
	});
}

function config_tablas() {
    var promise=$.Deferred();

    $("#inv_materiales_wrapper .button").html('<button value="Consumible" class="p-2 m-0 btn btn-info new_producto" data-toggle="modal" data-target="#config_newProducto">Nuevo material</button>');
    $("#inv_devolutivos_wrapper .button").html('<button value="Devolutivo" class="p-2 m-0 btn btn-info new_producto" data-toggle="modal" data-target="#config_newProducto">Nuevo devolutivo</button>');
    $("#tb_proveedores_wrapper .button").html('<label>Seleccionar proveedores</label>');
    $("#tb_seleccionados_wrapper .button").html('<label>Proveedores seleccionados</label>');
    $("#new_pedido_wrapper .button").html('<label>Pedido</label>');
    $("#provee_wrapper .button").html('<label>Proveedores</label>');

  	visualizarColumnaEntabla(tb_materiales,[7,8,9],false);
  	visualizarColumnaEntabla(tb_devolutivos,[5,6,7,8],false);
  	visualizarColumnaEntabla(tb_newPedido,[2,6,7,8,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27],false);
  	visualizarColumnaEntabla(tb_seleccionados,[5,6,7],false);
  	visualizarColumnaEntabla(tb_provee,[0,4],false);

	$("#inv_materiales").attr('style', 'width:100% !important;');
	$("#inv_devolutivos").attr('style', 'width:100% !important;');
	$("#tb_seleccionados").attr('style', 'width:100% !important;');
	$("#new_pedido").attr('style', 'width:100% !important;');
	$("#provee").attr('style', 'width:100% !important;');

	$.each(tb_newPedido.rows().data(), function(index, value){
		$.each(tb_materiales.rows().data(), function (index2) {
			if (value[2]==this[0]) {
				tb_materiales.row(':eq('+index2+')').remove().draw(false);
				return false;
			}
		});

		$.each(tb_devolutivos.rows().data(), function (index2) {
			if (value[2]==this[0]) {
				tb_devolutivos.row(':eq('+index2+')').remove().draw(false);
				return false;
			}
		});
	});

	window.setTimeout(function () {
        promise.resolve();
    },1500);
    return promise.promise();
}

function invalidar_campos(aux, nombre, categoria, cantidad, unidad, descripcion) {
	error=true;
	if (aux) {
        $('#newNombre').addClass('is-invalid');
        $('#error_newNombre').html('Ya existe un producto con ese nombre');
    }

	if (!nombre.length) {
        $('#newNombre').addClass('is-invalid');
        $('#error_newNombre').html('El campo es obligatorio');
    }

	if (!categoria.length) {
        $('#newCategoria').addClass('is-invalid');
        $('#error_newCategoria').html('El campo es obligatorio');
    }

	if (!cantidad.length) {
        $('#newCantidad').addClass('is-invalid');
        $('#error_newCantidad').html('El campo es obligatorio');
    }

    if (!unidad.length) {
        $('#newUnidad').addClass('is-invalid');
        $('#error_newUnidad').html('El campo es obligatorio');
    }

    if (!descripcion.length) {
        $('#descripcion').addClass('is-invalid');
        $('#error_descripcion').html('El campo es obligatorio');
    }
}

function limpiar() {
	error=false;
	restablecer('newNombre');
	restablecer('newCategoria');
	restablecer('newCantidad');
	restablecer('newUnidad');
	restablecer('descripcion');
}

function alerta(respuesta) {
    Swal.fire({
      position: 'top',
      icon: respuesta[0].res==true?'success':'error',
      text: respuesta[0].mensaje,
      showConfirmButton: true
    }).then((result) => {
    	if (respuesta[0].res) {
    		window.location.href=base_url('Vistas/adm_pedidos');
    	}else{
	        setTimeout(function() {
	            $(".sidebar-mini").css('padding-right', '0px');
	        },170);
    	}
    });
}

function restablecerTabla() {
	maxProv(`<center class="opciones">
        <button type="button" class="agregarProv btn btn-sm btn-info" data-toggle="modal" data-target="#config_suministro">
            <i class="fas fa-plus"></i>
        </button>
    </center>`);

	$.each(tb_seleccionados.rows().data(), function() {
		tb_proveedores.row.add([
	        this[0],
	        this[1],
	        this[5],
	        this[6],
	        this[7],
	        `<center class="opciones">
	            <button type="button" class="agregarProv btn btn-sm btn-info" data-toggle="modal" data-target="#config_suministro">
	                <i class="fas fa-plus"></i>
	            </button>
	        </center>`
	    ]).draw(false);
	});
	limpiarTabla(tb_seleccionados);
}

function acortar(url) {
    var url_modi;
    var n;

    for (var i=125; i<url.length; i+=125) {
        if (i==125) {
            url_modi=url.substr(0, i)+'<br/>';
            n=125;

        }else{
            url_modi+=url.substr(i-125, 125)+'<br/>';
            n+=125;
        }
    }

    if (url.length>125) {
        url_modi+=url.substr(n);

    }else{
        url_modi=url;
    }

    return url_modi;
}

function traducir() {
    $(".desing3").DataTable({
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
        "dom":'<"row"<"button col-5 col-md-6"><"col-4 col-md-6"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row justify-content-left"<"col-sm-12 col-md-12"p>>',
        "lengthMenu": [ [4, 25, 50, -1], [4, 25, 50, "All"] ],
        "lengthChange": false,
        "order":[[2, "asc"]]
    });

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
        "dom":'<"row"<"button col-5 col-md-6"><"col-4 col-md-6"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row justify-content-left"<"col-sm-12 col-md-12"p>>',
        "lengthMenu": [ [4, 25, 50, -1], [4, 25, 50, "All"] ],
        "lengthChange": false
    });
}

function format(d) {
	var subTabla=`<table cellpadding="5" cellspacing="0" border="0" style="width: 100%;">
		<tr>
			<td><b class="font-weight-bold">Precio empresa 1:</b></td>
			<td>`+d[6]+`</td>
		</tr>`;

	if (d[7]!='') {
		subTabla+=`<tr>
			<td><b class="font-weight-bold">Precio empresa 2:</b></td>
			<td>`+d[7]+`</td>
		</tr>`;
	}

	if (d[8]!='') {
		subTabla+=`<tr>
			<td><b class="font-weight-bold">Precio empresa 3:</b></td>
			<td>`+d[8]+`</td>
		</tr>`;
	}

	subTabla+=`<tr>
		<td><b class="font-weight-bold">Observaciones:</b></td>
		<td style="text-align: center;">`+d[27]+`</td>
	</tr></table>`;

	return subTabla;
}