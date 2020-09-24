var devolutivos;
var eventMant;
var tr; 
var table;
var url_base;
var categorias;
var unidades;
var lineas;
var estados;
var usuario;
var imagenes;
var idDevolutivo;
var rtime; 
var timeout = false; 
var milisg = 400;
var tableMant;
var tableProv;
$(document).ready(iniciar); 

function iniciar(){
	$("#caja-cargando").show();
	datatable2();	
	eventMant=true;
	categorias=new Array();
	unidades=new Array();
	lineas=new Array();
	estados=new Array();
	usuario=new Array();
	imagenes=new Array();	
	usuario['id']=$("#id_usuario").val();
	usuario['nombre']=$("#nombre_usuario").val();
	usuario['tipo']=$("#tipo_usuario").val();
	usuario['linea']=$("#linea_usuario").val();
	table=$("#table-devolutivos").DataTable();
	url_base=$("#base_url_dev").val();	
	$.when(agregarFiltro()).then(function(e){
		consultarDevolutivos();
		agregarFunciones();	
		configurarSelectsImg(); 
	});	
	$(window).resize(function() { 
		rtime = new Date(); 
		if (timeout === false) { 
			timeout = true; 
			setTimeout(resizeend, milisg); 
		} 
	}); 	
	tableMant=$("#table-mantenimiento-dev").DataTable();
	tableProv=$("#table-proveedores-dev").DataTable();
	visualizarColumnaEntabla(table,[1,2,9,11,12,13,14,15,16,17,18],false);
	visualizarColumnaEntabla(tableProv,4,false);
	// Configuración inicial de elementos en el DOM
	$("#btn-detalles-vis").hide();
	$("#table-devolutivos thead tr").find('th:eq(3)').attr('style', 'width:80px !important;');	
	$("#table-devolutivos thead tr").find('th:eq(1)').attr('style','width:30px !important;');
	$("#table-devolutivos").attr('style', 'width:100% !important;');
	$("#table-devolutivos_filter").addClass('text-md-right text-sm-center');
	// Funciones asignadas a los elementos
	agregarFunciones();		
	$("#form_add_dev").submit(function(e){		
		e.preventDefault();	
		guardarDevolutivo();			
	});
	$("#form_edit_dev").submit(function(e){		
		e.preventDefault();	
		editarDevolutivo();			
	});
	$("#form_add_mant").submit(function(e){		
		e.preventDefault();	
		if (verificarEstadosMantenimiento()) {
			guardarMantenimiento();
		}
		
	});
	$("#form_edit_mant").submit(function(e){
		e.preventDefault();	
		if (verificarEstadosMantenimiento()) {
			editarMantenimiento();
		}
	});
	$("#form_fecha_mant").submit(function(e){
		e.preventDefault();	
		filtrarFechas(tableMant,2,$("#fecha_mantenimiento_ini").val(),$("#fecha_mantenimiento_fin").val());
		$("#fecha_mantenimiento_ini").val("");
		$("#fecha_mantenimiento_fin").val("");
	});
	$("#btn-cancelar_add").on('click',function(){
		$('#modal-add-devolutivo').modal('toggle');
		limpiarAddFormDevolutivo();
	});	
	$("#btnAdd").on('click',function(){		
		limpiarAddFormDevolutivo();
	});
	$("#btn-mantenimiento-vis").on('click',function(){
		eventMant=false;
		$("#form_vis_dev").find(".contenedor-slide").addClass('rezisable-slide');
		$("#modal-vis-devolutivo").find(".modal-footer p").text("En esta sección visualizarás los mantenimientos que fueron hechos o serán hechos a este devolutivo.");
		cambioTamanos();
		$("#btn-agregarMant-vis").show();
		$("#btn-detalles-vis").show();
		$("#slide1-elemento2").hide();
		$("#btn-proveedores-vis").hide();
		$("#btn-agregarMantCancel-vis").hide();
		$("#btn-mantenimiento-vis").hide();	
		$("#contenedor-slide1").removeClass("elemento-noprecionado");
		$("#contenedor-slide1").addClass("elemento-precionado");
		$("#contenedor-slide2").removeClass("segundo-elemento-noprecionado");
		$("#contenedor-slide2").addClass("segundo-elemento-precionado");
		$("#modal-vis-devolutivo").find(".modal-title").text("Historial  de mantenimiento");
		consultarMantenimientos();
	});
	$("#btn-Provdetalles-vis").on('click', function() {
		$("#btn-proveedores-vis").show();
		$("#btn-detalles-vis").click();
		$("#contenedor-slide2").removeClass("elemento-noprecionado");
		$("#contenedor-slide2").addClass("segundo-elemento-noprecionado");
		eventMant=true;
		cambioTamanos();
	});
	$("#btn-detalles-vis").on('click',function(){
		$("#btn-proveedores-vis").show();
		btnFunctionMant()		
		$("#btn-detalles-vis").parents("div.d-flex").removeClass('justify-content-end');
		$("#btn-detalles-vis").parents("div.d-flex").addClass('justify-content-between');
		$("#contenedor-slide2").removeClass("segundo-elemento-precionado1");
		$("#contenedor-slide2").addClass("segundo-elemento-noprecionado");
		$("#contenedor-slide3").removeClass("tercer-elemento-precionado");
		$("#contenedor-slide3").addClass("tercer-elemento-noprecionado");
	});
	$("#btn-proveedores-vis").on('click',function(){
		$("#modal-vis-devolutivo").find(".modal-title").text("Proveedores del devolutivo");
		$("#modal-vis-devolutivo").find(".modal-footer p").text("En esta sección visualizarás los proveedores vinculados a este devolutivo.");
		$("#btn-proveedores-vis").hide();
		$("#btn-mantenimiento-vis").hide();
		$("#btn-Provdetalles-vis").show();
		$("#btn-detalles-vis").parents("div.d-flex").removeClass('justify-content-between');
		$("#btn-detalles-vis").parents("div.d-flex").addClass('justify-content-end');
		$("#contenedor-slide2").removeClass("segundo-elemento-noprecionado");
		$("#contenedor-slide2").addClass("segundo-elemento-precionado1");
		$("#contenedor-slide3").removeClass("tercer-elemento-noprecionado");
		$("#contenedor-slide3").addClass("tercer-elemento-precionado");
		consultarProveedoresDevolutivo();
		eventMant=null;
		cambioTamanos();
	});
	$("#btn-agregarMant-vis").on('click',function(){
		limpiarFechas();
		$("#modal-vis-devolutivo").find(".modal-footer p").text("En esta sección podrás agregar nuevos mantenimientos realizados anteriormente o se realizarán al futuro a este devolutivo.");
		$("#rango-add-fecha").prop('checked',false);
		$("#slide1-elemento1").hide();
		$("#slide1-elemento2").show();
		$("#btn-detalles-vis").hide();
		$("#btn-agregarMantCancel-vis").show();
		$("#btn-agregarMant-vis").hide();
		$("#btn-detalles-vis").parents("div.d-flex").removeClass('justify-content-between');
		$("#btn-detalles-vis").parents("div.d-flex").addClass('justify-content-end');
		$("#caja-date-add-fin").hide();
		$("#form_add_mant").show();
		$("#form_edit_mant").hide();
		eventMant=false;
		cambioTamanos();
	});
	$("#btn-agregarMantCancel-vis").on('click',function(){
		$("#table-mantenimiento-dev tr.oculto").show();
		$("#table-mantenimiento-dev tr.oculto").removeClass('oculto');
		limpiarFechas();
		$("#modal-vis-devolutivo").find(".modal-footer p").text("En esta sección visualizarás los mantenimientos que fueron hechos o serán hechos a este devolutivo.");
		$("#rango-add-fecha").prop('checked',false);
		$("#slide1-elemento2").hide();
		$("#slide1-elemento1").show();
		$("#btn-detalles-vis").show();
		$("#btn-agregarMant-vis").show();
		$("#btn-agregarMantCancel-vis").hide();
		$("#btn-detalles-vis").parents("div.d-flex").removeClass('justify-content-end');
		$("#btn-detalles-vis").parents("div.d-flex").addClass('justify-content-between');
		$("#form_add_mant").show();
		$("#form_edit_mant").hide();
		eventMant=false;
		cambioTamanos();
	});
	$("#date-add-ini").on('change',function(){
		var fecha_info=new Date($("#date-add-ini").val());
		$("#date-add-fin").attr('min',$("#date-add-ini").val());
		var dias=2;
		fecha_info.setDate(fecha_info.getDate()+dias);
		let dia=fecha_info.getDate();
		let mes=fecha_info.getMonth()+1;
		let anio=fecha_info.getFullYear();
		
		if(mes < 10){
			$("#date-add-fin").val(`${anio}-0${mes}-${dia}`);
		}else{
		  	$("#date-add-fin").val(`${anio}-${mes}-${dia}`);
		}
		
	});
	$("#date-edit-ini").on('change',function(){
		var fecha_info=new Date($("#date-edit-ini").val());
		$("#date-edit-fin").attr('min',$("#date-edit-ini").val());
		var dias=2;
		fecha_info.setDate(fecha_info.getDate()+dias);
		let dia=fecha_info.getDate();
		let mes=fecha_info.getMonth()+1;
		let anio=fecha_info.getFullYear();
		
		if(mes < 10){
			$("#date-edit-fin").val(`${anio}-0${mes}-${dia}`);
		}else{
		  	$("#date-edit-fin").val(`${anio}-${mes}-${dia}`);
		}
		
	});
	$("#rango-add-fecha").on('change',function(){
		if ($("#rango-add-fecha").prop('checked')) {
			$("#caja-date-add-fin").show();
			$("#date-add-ini").parents("div.form-group").find('label').text('Fecha inicio:');
		}else{
			$("#date-add-ini").parents("div.form-group").find('label').text('Fecha:');
			$("#caja-date-add-fin").hide();
		}
		cambioTamanos();
	});
	$("#rango-edit-fecha").on('change',function(){
		if ($("#rango-edit-fecha").prop('checked')) {
			$("#caja-date-edit-fin").show();
			$("#date-edit-ini").parents("div.form-group").find('label').text('Fecha inicio:');
		}else{
			$("#caja-date-edit-fin").hide();
			$("#date-edit-ini").parents("div.form-group").find('label').text('Fecha:');
		}
	});
	$("#fecha_mantenimiento_ini").on('change',function(){
		$("#fecha_mantenimiento_fin").attr('min',$("#fecha_mantenimiento_ini").val());
		$("#fecha_mantenimiento_fin").val($("#fecha_mantenimiento_ini").val());
	});
	$("#edicion_avanzada").change(function(){
		if ($("#edicion_avanzada").prop('checked')===true) {
			$("#label_edicion_avanzada").addClass('bg-primary');
			$("#cantEdit_edit_dev").show();
			$("#placa_edit_dev").hide();
			$("#csena_edit_dev").hide();
			$("#serial_edit_dev").hide();
			$("#label_cantEdit_edit_dev").show();
			$("#label_placa_edit_dev").hide();
			$("#label_csena_edit_dev").hide();
			$("#label_serial_edit_dev").hide();
		}else{
			$("#label_edicion_avanzada").removeClass('bg-primary');
			$("#cantEdit_edit_dev").hide();
			$("#placa_edit_dev").show();
			$("#csena_edit_dev").show();
			$("#serial_edit_dev").show();
			$("#label_cantEdit_edit_dev").hide();
			$("#label_placa_edit_dev").show();
			$("#label_csena_edit_dev").show();
			$("#label_serial_edit_dev").show();
		}
	});
	 
}
function configurarSelectsImg(){
	$(".imagen-select, .imagen-select2").ddslick({
		width: '100%',
		onSelected: function(selectedData) {
			$("#select_imagen_add_dev").addClass('label-input-img');
			$("#select_imagen_edit_dev").addClass('label-input-img');
			$("#select_imagen_add_dev img.dd-selected-image").hide();
			$("#select_imagen_edit_dev img.dd-selected-image").hide();
			$(".dd-selected-text").css({'line-height': '32px', 'margin-bottom': '0','cursor':'pointer'});
			$(".dd-option-image").css({'max-width': '100px', 'width': '100px', 'height': '100px'});
			$("#select_imagen_add_dev label.dd-option-text").css({'cursor':'pointer'});
			$("#select_imagen_edit_dev label.dd-option-text").css({'cursor':'pointer'});
			$("#select_imagen_add_dev ul.dd-options").css({'height':'250px','overflow-y':'scroll'});
			$("#select_imagen_edit_dev ul.dd-options").css({'height':'250px','overflow-y':'scroll'});			
		}
	});

	$(".dd-container, .dd-select, .dd-selected").css('height', '38px');
	$("#select_imagen_add_dev .dd-selected-value").attr({'name': 'imagenp', 'id': 'select_imagen_add_input_dev'});
	$("#select_imagen_edit_dev .dd-selected-value").attr({'name': 'imagenp', 'id': 'select_imagen_edit_input_dev'});
	$("#select_imagen_add_dev ul.dd-options").find('a.dd-option').click(function(){
		$("#file_img_add").attr('src',$(this).find("img").attr('src'));
	});
	$("#select_imagen_edit_dev ul.dd-options").find('a.dd-option').click(function(){
		$("#file_img_edit").attr('src',$(this).find("img").attr('src'));
	});
	$("#selectEdit_imagen .dd-selected-value").attr({'name': 'edit_imagen', 'id': 'edit_imagen'});
	$(".dd-selected-image").css({'width': '34px', 'height': '32px'});
	$(".dd-selected-text").css({'line-height': '32px', 'margin-bottom': '0'});
	$(".dd-selected").css('padding', '2px 7px');
	$(".dd-option-image").css({'max-width': '100px', 'width': '100px', 'height': '100px'});
	$(".dd-select").on("click", function () {
		$(".dd-option-text").css({'margin-bottom': '0', 'line-height': '100px'});
	});
}
function verificarEstadosMantenimiento(){
	var respuesta;
	if ($("#est-add-select").val()==="Terminado") {
		if (evaluarFecha($("#date-add-ini").val())<=0) {
			respuesta=true;
		}else{
			respuesta=false;
			var texto;
			if ($("#rango-add-fecha").prop('checked')) {
				texto="La fecha inicial es mayor a la actual para un estado Terminado"
			}else{
				texto="La fecha es mayor a la actual para un estado Terminado"
			}
			Swal.fire({
					  title:'Vaya!',
					  text:texto,
					  icon:'info',
					  showConfirmButton:false,
					  timer:2000
					});
		}
	}else if ($("#est-add-select").val()==="Vigente"){
		if (buscarEnColumna(tableMant,"Vigente",4,0)) {
			respuesta=true;
		}else{
			respuesta=false;
			Swal.fire({
					  title:'Vaya!',
					  text:"Solo puede haber un mantenimiento vigente",
					  icon:'info',
					  showConfirmButton:false,
					  timer:2000
					});	
		}
	}else{
		respuesta=true;
	}
	return respuesta;
}
function filtrarFechas(tabla,columna,fecha1,fecha2=""){
	$.fn.dataTable.ext.search.pop();
	filtrartabla(tabla,columna,'');
	var r_fecha1=new Date(fecha1).getTime();
	var r_fecha2=new Date(fecha2).getTime();
	var id=$(tabla.table().node()).attr('id');
	$.fn.dataTable.ext.search.push(
	    function( settings, data, dataIndex ) {
	    	if (settings.nTable.id !==id) {
	    		return true;
	    	}
	        var min = r_fecha1;
	        var max = r_fecha2;
	        var r_fechaCell = new Date(data[columna]).getTime(); // use data for the r_fechaCell column
	        if ( ( min <= r_fechaCell   && r_fechaCell <= max ) ){
	            return true;
	        }
	        return false;
	    }
	);
	filtroFechas(fecha1,fecha2);
}
function evaluarFecha(fecha){
	var inimili=new Date(fecha).getTime();
	var actumili=new Date($("#fecha-actual").val()).getTime();
	var difday=inimili-actumili;
	return difday/(1000*60*60*24);
}
function btnFunctionMant(){
	$("#btn-agregarMantCancel-vis").hide();
	$("#btn-Provdetalles-vis").hide();
	eventMant=true;
	cambioTamanos();
	if (usuario["tipo"]==="INSTRUCTOR" && table.row(tr).data()[9]===usuario['id'] || usuario["tipo"]==="ADMINISTRADOR") {
		$("#btn-mantenimiento-vis").show();
	}else{
		$("#btn-mantenimiento-vis").hide();
	}
	$("#btn-detalles-vis").hide();
	$("#btn-agregarMant-vis").hide();
	$("#contenedor-slide1").removeClass("elemento-precionado");
	$("#contenedor-slide1").addClass("elemento-noprecionado");
	$("#contenedor-slide2").removeClass("segundo-elemento-precionado");
	$("#contenedor-slide2").addClass("segundo-elemento-noprecionado");
	$("#modal-vis-devolutivo").find(".modal-title").text("Detalles del devolutivo");
	tableMant.clear().draw();
	$("#modal-vis-devolutivo").find(".modal-footer p").text("En esta sección podrás visualizar los datos de los materiales devolutivos en el inventario.");
	$("#slide1-elemento1").show();
	$("#slide1-elemento2").hide(	);
}
function resizeend() { 
	if (new Date() - rtime < milisg) { 
		setTimeout(resizeend, milisg); 
	} else { 
		timeout = false; 
		cambioTamanos();		
	} 
}
function cambioTamanos(){
	if (eventMant==true) {
		var h=$("#contenedor-slide2").height();
	}else if (eventMant==false){
		var h=$("#contenedor-slide1").height();
	}else{
		var h=$("#contenedor-slide3").height();
	}
	$("#form_vis_dev").find(".contenedor-slide").height(h+30);
	if ($(".contenedor-slide").height()==0) {
		if ($(window).width()<=765) {
			$("#form_vis_dev").find(".contenedor-slide").height(1610);
		}else{
			$("#form_vis_dev").find(".contenedor-slide").height(790);
		}
	}
	$("#table-devolutivos thead tr").find('th:eq(1)').attr('style','width:30px !important;');
	$('#modal-vis-devolutivo').modal('handleUpdate');
}
function filtroUsuarios(id,nombre){
	filtrartabla(table,9,id);		
	if ($("#usuario_filtrado").length) {
		$("#usuario_filtrado h3").html(nombre);

	}else{			
		$("#cajas-filtro").append(`<div id="usuario_filtrado" style="width:auto;" class="order-1 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_fusuario" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

        $("#eliminar_fusuario").on('click', function() {
			filtrartabla(table,9,'');                 
			$("#usuario_filtrado").remove();
		});         
    }     
}
function filtroUnidad(nombre){
	filtrartabla(table,5,nombre);
	if ($("#unidad_filtrada").length) {
		$("#unidad_filtrada h3").html(nombre);

	}else{
		$("#cajas-filtro").append(`<div id="unidad_filtrada" style="width:auto;" class="order-2 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_funidad" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	$("#eliminar_funidad").on('click', function() {
	  		filtrartabla(table,5,'');
	  		$("#unidad_filtrada").remove();
	  	});
	}
}
function filtroCategorias(nombre){
	filtrartabla(table,6,nombre);	
	if ($("#categoria_filtrada").length) {
		$("#categoria_filtrada h3").html(nombre);

	}else{			
		$("#cajas-filtro").append(`<div id="categoria_filtrada" style="width:auto;" class="order-3 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_fcategoria" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

        $("#eliminar_fcategoria").on('click', function() {
			filtrartabla(table,6,'');                 
			$("#categoria_filtrada").remove();
		});         
    }     
}
function filtroLinea(nombre,nombre2){
	filtrartabla(table,7,nombre);         
	if ($("#linea_filtrada").length){             
		$("#linea_filtrada h3").html(nombre2);
	}else{
		$("#cajas-filtro").append(`<div id="linea_filtrada" style="width:auto;" class="order-4 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre2+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_flinea" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	$("#eliminar_flinea").on('click', function() {
	  		filtrartabla(table,7,'');
	  		$("#linea_filtrada").remove();
	  	});
	}
}
function filtroEstado(nombre){
	filtrartabla(table,8,nombre);
	if ($("#estado_filtrada").length) {
		$("#estado_filtrada h3").html(nombre);

	}else{
		$("#cajas-filtro").append(`<div id="estado_filtrada" style="width:auto;" class="order-5 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_festado" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	$("#eliminar_festado").on('click', function() {
	  		filtrartabla(table,8,'');
	  		$("#estado_filtrada").remove();
	  	});
	}
}
function filtroErrores(nombre,nombre2){
	filtrartabla(table,18,nombre);
	if ($("#errores_filtrada").length) {
		$("#errores_filtrada h3").html(nombre2);

	}else{
		$("#cajas-filtro").append(`<div id="errores_filtrada" style="width:auto;" class="order-6 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre2+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_ferror" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	$("#eliminar_ferror").on('click', function() {
	  		filtrartabla(table,18,'');
	  		$("#errores_filtrada").remove();
	  	});
	}
}
function filtroEstadoMant(nombre){
	filtrartabla(tableMant,4,nombre);
	cambioTamanos();
	if ($("#estadoMant_filtrada").length) {
		$("#estadoMant_filtrada h3").html(nombre);

	}else{
		$("#cajas-filtro2").append(`<div id="estadoMant_filtrada" style="width:auto;" class="order-5 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_festadoMant" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	$("#eliminar_festadoMant").on('click', function() {
	  		filtrartabla(tableMant,4,'');
	  		$("#estadoMant_filtrada").remove();
	  		cambioTamanos();
	  	});
	}
}
function filtroMantenimiento(nombre){
	filtrartabla(tableMant,1,nombre);
	cambioTamanos();
	if ($("#Mantenimiento_filtrada").length) {
		$("#Mantenimiento_filtrada h3").html(nombre);

	}else{
		$("#cajas-filtro2").append(`<div id="Mantenimiento_filtrada" style="width:auto;" class="order-5 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_fMantenimiento" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	$("#eliminar_fMantenimiento").on('click', function() {
	  		filtrartabla(tableMant,1,'');
	  		$("#Mantenimiento_filtrada").remove();
	  		cambioTamanos();
	  	});
	}
}
function filtroFechas(fecha1,fecha2){
	fecha1=String(fecha1);
	fecha2=String(fecha2);
	fecha1=fecha1.replace(/-/g,'/');
	fecha2=fecha2.replace(/-/g,'/');
	tableMant.draw(false);
	if ($("#FechaMantenimiento_filtrada").length) {
		$("#FechaMantenimiento_filtrada h3").html(fecha1+" - "+fecha2);

	}else{
		$("#cajas-filtro2").append(`<div id="FechaMantenimiento_filtrada" style="width:auto;" class="order-5 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+fecha1+" - "+fecha2+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_fechaMantenimiento" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	$("#eliminar_fechaMantenimiento").on('click', function() {
	  		$.fn.dataTable.ext.search.pop();
	  		filtrartabla(tableMant,2,'');
	  		tableMant.draw(false);
	  		$("#FechaMantenimiento_filtrada").remove();
	  		cambioTamanos();
	  	});
	}
}
function agregarFunciones(){
	$("#f_usuario a").on("click", function() {
		filtroUsuarios($(this).attr('data-usuario'),$(this).text());
    });
	$("#f_unidad a").on("click", function() {
		filtroUnidad($(this).text());		
	});
	$("#f_categoria a").on("click", function() {
		filtroCategorias($(this).text());
    });
         
    $("#f_linea a").on("click", function() {
		filtroLinea($(this).attr('data-linea'),$(this).text());
	});
	$("#f_estado a").on("click", function() {
		filtroEstado($(this).text());
	});
	$("#f_errores a").on("click", function() {
		filtroErrores($(this).attr('data-error'),$(this).text());
	});
	$("#f_estadoMant a").on("click", function() {
		filtroEstadoMant($(this).attr('data-estado'));
	});
	$("#f_Mant a").on("click", function() {
		filtroMantenimiento($(this).attr('data-mant'));
	});
}
function limpiarAddFormDevolutivo(){
	$("#form_add_dev :input").val("");			
	$("#form_add_dev").find("#file_img_add").attr('src',url_base+"assets/img/sinFoto.png");
	$("#btn-guardar_add").removeAttr('disabled');
	$(".btn-cerrar").removeAttr('disabled');
} 
function limpiarEditFormDevolutivo(){
	$("#form_edit_dev :input").val("")
	$("#form_edit_dev #cantEdit_edit_dev").val(0);	
	$("#form_add_dev :input").val("");			
	$("#form_add_dev").find("#file_img_add").attr('src',url_base+"assets/img/sinFoto.png");
	$("#btn-guardar_edit").removeAttr('disabled');
	$(".btn-cerrar").removeAttr('disabled');
}
function guardarDevolutivo(){
	$("#btn-guardar_add").attr("disabled","true");
	$(".btn-cerrar").attr("disabled","true");
	$("#caja-cargando").show();		
	var texto;
	if ($("#nombre_add_dev").val()==="" || $("#descripcion_add_dev").val()==="" || $("#linea_add_dev").val()==="" || $("#estado_add_dev").val()==="" || $("#cantidad_add_dev").val()==="" || $("#unidad_add_dev").val()==="" || $("#categoria_add_dev").val()==="") {
     	$("#caja-cargando").hide();
		texto="No puedes continuar. Hay campos obligatorios vacíos!"
		Swal.fire({
     	  title:'Vaya!',
     	  text:texto,
     	  icon:'info',
     	  showConfirmButton:false,
     	  timer:1300
     	});
     	$("#btn-guardar_add").removeAttr('disabled');
		$(".btn-cerrar").removeAttr('disabled');
	}else{
		if ($("#placa_add_dev").val()!=="" ) {
			var col1=buscarEnColumna(table,$("#placa_add_dev").val(),0,0);	
			var texto="La placa ya existe!"
		}else {
			var col1=true;
		}
		if ($("#csena_add_dev").val()!=="") {
			var col2=buscarEnColumna(table,$("#csena_add_dev").val(),1,0);;
			var texto="El codigo sena ya existe!"
		}else{
			var col2=true;
		} 
		if ($("#serial_add_dev").val()!==""){
			var col3=buscarEnColumna(table,$("#serial_add_dev").val(),2,0);;
			var texto="El serial ya existe!"
		}else{
			var col3=true;
		}
		if ($("#cant_add_dev").val()>0 && $("#placa_add_dev").val()==="" && $("#csena_add_dev").val()==="" && $("#serial_add_dev").val()==="" || $("#cant_add_dev").val()==0 || $("#cant_add_dev").val()==="") {
			col1=col1;
		}else{
			col1=false;
			var texto="No puedes registrar una cantidad mayor a 1 con los campos de codigo sena, placa y serial llenos"
		}				
		if ( col1 && col2 && col3) {
			let array=String($("#file_img_add").attr('src')).split('/');
			let img=array[array.length-1];
			var json={
				"categoria_add_dev":$("#modal-add-devolutivo #categoria_add_dev").val(),
				"estado_add_dev":$("#modal-add-devolutivo #estado_add_dev").val(),
				"unidad_add_dev":$("#modal-add-devolutivo #unidad_add_dev").val(),
				"cant_add_dev":$("#modal-add-devolutivo #cant_add_dev").val(),
				"linea_add_dev":$("#modal-add-devolutivo #linea_add_dev").val(),
				"usuario_add_dev":$("#modal-add-devolutivo #usuario_add_dev").val(),
				"usuario_add_dev2":$("#id_usuario").val(),
				"nombre_add_dev":$("#modal-add-devolutivo #nombre_add_dev").val(),
				"descripcion_add_dev":$("#modal-add-devolutivo #descripcion_add_dev").val(),
				"precio_add_dev":$("#modal-add-devolutivo #precio_add_dev").val(),
				"placa_add_dev":$("#modal-add-devolutivo #placa_add_dev").val(),
				"serial_add_dev":$("#modal-add-devolutivo #serial_add_dev").val(),
				"csena_add_dev":$("#modal-add-devolutivo #csena_add_dev").val(),
				"imagenp":$("#select_imagen_add_input_dev").val(),
				"imagen":img
			};
			$.ajax({
				"url":$("#form_add_dev").attr("action"),
				"type":'post',
				"data":json,
				"dataType":"json",
				success: function(datos){
					$("#caja-cargando").hide();
					var option;
					if ($("#cant_add_dev").val()==="" || $("#cant_add_dev").val()==0 || $("#cant_add_dev").val()==1) {
						option=2;
					}else{
						option=0;
					}	
					if (datos.aviso) {							
						armarTablaDevolutivos(datos.productos,option);
		             	Swal.fire({
		             	  title:'Exito!',
		             	  text:datos.texto,
		             	  icon:'success',
		             	  showConfirmButton:false,
		             	  timer:1000
		             	});
		             	$('#modal-add-devolutivo').modal('toggle');
		             	limpiarAddFormDevolutivo();
					}else{
						if (datos.error) {
						  	Swal.fire({
							  title:'Oh no!',
							  text:datos.texto,
							  icon:'error',
							  showConfirmButton:false,
							  timer:2000
							});							
						}else{
							Swal.fire({
							  title:'Vaya!',
							  text:datos.texto,
							  icon:'info',
							  showConfirmButton:false,
							  timer:2000
							});
						}							             			            
						$("#btn-guardar_add").removeAttr('disabled');
						$(".btn-cerrar").removeAttr('disabled');
					}
					
				}
			}).fail(function(){
				$("#caja-cargando").hide();	
			});				
		}else{	
			$("#caja-cargando").hide();
			Swal.fire({
         	  title:'Vaya!',
         	  text:texto,
         	  icon:'info',
         	  showConfirmButton:false,
         	  timer:1500
         	});
         	$("#btn-guardar_add").removeAttr('disabled');
			$(".btn-cerrar").removeAttr('disabled');
		}		
	}
}
function editarDevolutivo(){
	$("#btn-guardar_edit").attr("disabled","true");
	$(".btn-cerrar").attr("disabled","true");
	$("#caja-cargando").show();
	if ($("#nombre_edit_dev").val()==="" || $("#descripcion_edit_dev").val()==="" || $("#linea_edit_dev").val()==="" || $("#estado_edit_dev").val()==="" || $("#cantidad_edit_dev").val()==="" || $("#unidad_edit_dev").val()==="" || $("#categoria_edit_dev").val()==="") {
		texto="No puedes continuar. Hay campos están vacíos!";
		$("#caja-cargando").hide();
		Swal.fire({
     	  title:'Vaya!',
     	  text:texto,
     	  icon:'info',
     	  showConfirmButton:false,
     	  timer:1300
     	});
     	$("#btn-guardar_edit").removeAttr('disabled');
		$(".btn-cerrar").removeAttr('disabled');
	}else{	
		if ($("#placa_add_dev").val()!=="" ) {
			var col1=buscarEnColumna(table,$("#placa_add_dev").val(),0,0);	
			var texto="La placa ya existe!"
		}else {
			var col1=true;
		}
		if ($("#csena_add_dev").val()!=="") {
			var col2=buscarEnColumna(table,$("#csena_add_dev").val(),1,0);;
			var texto="El codigo sena ya existe!"
		}else{
			var col2=true;
		} 
		if ($("#serial_add_dev").val()!==""){
			var col3=buscarEnColumna(table,$("#serial_add_dev").val(),2,0);;
			var texto="El serial ya existe!"
		}else{
			var col3=true;
		}	
		if ($("#edicion_avanzada").prop('checked')===true){
			var arrayChecked=$("#tab-cant-devs").find('.check_data:checked').toArray();
			if (arrayChecked.length>1) {
				var jsonDev={};
				jsonDev['repetir']=true;
				jsonDev['devolutivos']=[];
				jsonDev['cant_edit_dev']=arrayChecked.length;
				let array=String($("#file_img_edit").attr('src')).split('/');
				var img=array[array.length-1];
				for (var i = 0; i < arrayChecked.length; i++) {
					let id= String($(arrayChecked[i]).attr('id')).split('_')[1];
					tr=dataFila(table,parseInt(id,10),12);
					jsonDev['devolutivos'][i]={
						"categoria_edit_dev":$("#modal-edit-devolutivo #categoria_edit_dev").val(),
						"estado_edit_dev":$("#modal-edit-devolutivo #estado_edit_dev").val(),
						"unidad_edit_dev":$("#modal-edit-devolutivo #unidad_edit_dev").val(),
						"linea_edit_dev":$("#modal-edit-devolutivo #linea_edit_dev").val(),
						"usuario_edit_dev":$("#modal-edit-devolutivo #usuario_edit_dev").val(),
						"usuario_edit_dev2":table.row(tr).data()[9],
						"nombre_edit_dev":$("#modal-edit-devolutivo #nombre_edit_dev").val(),
						"descripcion_edit_dev":$("#modal-edit-devolutivo #descripcion_edit_dev").val(),
						"precio_edit_dev":$("#modal-edit-devolutivo #precio_edit_dev").val(),
						"imagenp":$("#select_imagen_edit_input_dev").val(),
						"imagen":img,
						"idProducto_edit_dev":id
					};
				}
			}else{
				var col1=false;	
				var texto="No puedes continuar. Debe seleccionar dos o más productos en la tabla";	
			}
		}else{
			let array=String($("#file_img_edit").attr('src')).split('/');
			let img=array[array.length-1];
			var jsonDev={};
			jsonDev['repetir']=false;
			jsonDev['cant_edit_dev']=1;
			jsonDev['devolutivos'][0]={
				"categoria_edit_dev":$("#modal-edit-devolutivo #categoria_edit_dev").val(),
				"estado_edit_dev":$("#modal-edit-devolutivo #estado_edit_dev").val(),
				"unidad_edit_dev":$("#modal-edit-devolutivo #unidad_edit_dev").val(),
				"linea_edit_dev":$("#modal-edit-devolutivo #linea_edit_dev").val(),
				"usuario_edit_dev":$("#modal-edit-devolutivo #usuario_edit_dev").val(),
				"usuario_edit_dev2":table.row(tr).data()[9],
				"nombre_edit_dev":$("#modal-edit-devolutivo #nombre_edit_dev").val(),
				"descripcion_edit_dev":$("#modal-edit-devolutivo #descripcion_edit_dev").val(),
				"precio_edit_dev":$("#modal-edit-devolutivo #precio_edit_dev").val(),
				"placa_edit_dev":$("#modal-edit-devolutivo #placa_edit_dev").val(),
				"serial_edit_dev":$("#modal-edit-devolutivo #serial_edit_dev").val(),
				"csena_edit_dev":$("#modal-edit-devolutivo #csena_edit_dev").val(),
				"imagenp":$("#select_imagen_edit_input_dev").val(),
				"imagen":img,
				"idProducto_edit_dev":idDevolutivo
			};
		}
		if ( col1 && col2 && col3) {
			$.ajax({
				"url":$("#form_edit_dev").attr("action"),
				"type":$("#form_edit_dev").attr("method"),
				"data":jsonDev,
				"dataType":"json",
				success: function(datos){
					$("#caja-cargando").hide();
					if (datos.aviso) {				
						console.log(datos);
						console.log(datos.productos);
						if (datos.repetir) {
							armarTablaDevolutivos(datos.productos,1,true);
						}else{
							armarTablaDevolutivos(datos,1);
						}
		             	Swal.fire({
		             	  title:'Exito!',
		             	  text:datos.texto,
		             	  icon:'success',
		             	  showConfirmButton:false,
		             	  timer:1000
		             	});
		             	$('#modal-edit-devolutivo').modal('toggle');
		             	limpiarEditFormDevolutivo();
					}else{
						if (datos.error) {
					  		Swal.fire({
								title:'Vaya!',
								text:datos.texto,
								icon:'info',
								showConfirmButton:false,
								timer:2000
							});						
						}else{
							Swal.fire({
							  title:'Oh no!',
							  text:datos.texto,
							  icon:'error',
							  showConfirmButton:false,
							  timer:2000
							});
						}
						$("#btn-guardar_edit").removeAttr('disabled');
						$(".btn-cerrar").removeAttr('disabled');
					}
					
				}
			}).fail(function(){
				$("#caja-cargando").hide();
				$("#btn-guardar_edit").removeAttr('disabled');
				$(".btn-cerrar").removeAttr('disabled');
			});
		}else{
			$("#caja-cargando").hide();
			Swal.fire({
         	  title:'Vaya!',
         	  text:texto,
         	  icon:'info',
         	  showConfirmButton:false,
         	  timer:1500
         	});
         	$("#btn-guardar_edit").removeAttr('disabled');
			$(".btn-cerrar").removeAttr('disabled');
		}
		
	}
}
function armarTablaDevolutivos(datos,opcion=0,bool=false){	
	var datostb=[];
	table=$("#table-devolutivos").DataTable();	
	if (opcion==0) {
		$.each(datos, function () {	
			if (this.id_producto) {	
				var nerror;
				var btn;
				if (this.imagen==null || this.imagen==="") {
					this.imagen='sinFoto.png';
				}
				if (this.imagen==='sinFoto.png') {
					var dir="img";
				}else{
					var dir="files";
				}				
				if (usuario["tipo"]==="INSTRUCTOR" && usuario["id"]===this.usuario_producto || usuario["tipo"]==="ADMINISTRADOR") {
					btn="<center class='d-flex'><button class='visualizar btnVis btn btn-sm btn-info pl-3 pr-3' data-toggle='modal' data-target='#modal-vis-devolutivo' value='"+this.id_producto+"'><i class='fas fa-eye'></i><button class='btn btn-sm btn-warning btnEdit pl-3 pr-3 ' data-toggle='modal' data-target='#modal-edit-devolutivo' value='"+this.id_producto+"'><i class='fas fa-edit'></i></button></center>";
				}else{
					btn="<center class='d-flex'><button class='visualizar btnVis btn btn-sm btn-info pl-3 pr-3' data-toggle='modal' data-target='#modal-vis-devolutivo' value='"+this.id_producto+"'><i class='fas fa-eye'></i></center>";
				}
				if (this.categoria_producto!=null && categorias[this.categoria_producto]['estado']==='i') {
					var nom="La categoría "+categorias[this.categoria_producto]['nombre']+" está inactiva";
				}else if (this.categoria_producto!=null){
					var nom=categorias[this.categoria_producto]['nombre'];
				}else{
					var nom="";
				}
				if (this.unidad_medida!=null && unidades[this.unidad_medida]['estado']==='i') {
					var unid="La Unidad "+unidades[this.unidad_medida]['nombre']+" está inactiva";
				}else if (this.unidad_medida!=null){
					var unid=unidades[this.unidad_medida]['nombre'];
				}else{
					var unid="";
				}
				if (this.placa==="" || this.codigo_sena==="" || this.serial==="" || this.imagen==='sinFoto.png') {
					nerror=1;
				}else{
					nerror=0;
				}
				if(this.categoria_producto==null || this.unidad_medida==null || unidades[this.unidad_medida]['estado']==='i' || categorias[this.categoria_producto]['estado']==='i'){
					nerror=2;
				}else{
					nerror=nerror;
				}
				imagenes[this.id_producto]=this.imagen;
				datostb=[
						this.placa,//0-
						this.codigo_sena,//1-
						this.serial,//2-
						"<img class='img-fluid' src='"+url_base+"assets/"+dir+"/"+this.imagen+"' alt='' data-id='"+this.imagenp+"'>",//3-
						this.nombre_producto,//4-
						unid,//5-
						nom,//6-
						this.linea_producto!=null?lineas[this.linea_producto]:"",//7
						this.estado_producto!=null?estados[this.estado_producto]:"",//8
						this.usuario_producto,//9
						btn,//10
						this.precio_producto,//11
						this.id_producto,//12
						this.descripcion_producto,//13
						this.unidad_medida,//14-
						this.categoria_producto,//15-
						this.linea_producto,//16-
						this.estado_producto,//17
						nerror//18
					];		
				if (this.estado_producto!=8) {
					var rowNode=table.row.add(datostb).draw(false).node();
					$(rowNode).find('td:eq(1)').addClass("img-dev p-0");
					$(rowNode).find('td:eq(7)').addClass("btns-dev");
					// Borde de la fila en DataTable
					if (usuario["tipo"]==="INSTRUCTOR" && usuario["id"]===this.usuario_producto || usuario["tipo"]==="ADMINISTRADOR") {
						if (this.unidad_medida!=null && this.categoria_producto!=null) {
							validarFila(rowNode,unidades[this.unidad_medida]['estado'],categorias[this.categoria_producto]['estado'],this.descripcion_producto,this.placa,this.serial,this.codigo_sena,this.imagen,this.nombre_producto,unidades[this.unidad_medida]['nombre'],categorias[this.categoria_producto]['nombre'],lineas[this.linea_producto],estados[this.estado_producto],this.usuario_producto,this.precio_producto);	
						}else{
							validarFila(rowNode,"","",this.descripcion_producto,this.placa,this.serial,this.codigo_sena,this.imagen,this.nombre_producto,"","",lineas[this.linea_producto],estados[this.estado_producto],this.usuario_producto,this.precio_producto);	
						}
						
					}
					$(rowNode).find(".btnEdit").click(formInput);
					$(rowNode).find(".btnVis").click(formInput); 
				}				
				
			}										
		});
		filtroUsuarios(usuario['id'],"Mis devolutivos");
	}else{
		if (bool==false) {
			var nerror;
			if (datos.imagen==null || datos.imagen==="") {
				datos.imagen='sinFoto.png';
			}else{
				
			}
			if (datos.imagen==='sinFoto.png') {
				var dir="img";
			}else{
				var dir="files";
			}
			if (categorias[datos.categoria_producto]['estado']==='i') {
				var nom="La categoría "+categorias[datos.categoria_producto]['nombre']+" está inactiva";
			}else{
				var nom=categorias[datos.categoria_producto]['nombre'];
			}
			if (unidades[datos.unidad_medida]['estado']==='i') {
				var unid="La Unidad "+unidades[datos.unidad_medida]['nombre']+" está inactiva";
			}else{
				var unid=unidades[datos.unidad_medida]['nombre'];
			}
			if (datos.placa==="" || datos.codigo_sena==="" || datos.serial==="" || datos.imagen==='sinFoto.png') {
				nerror=1;
			}else{
				nerror=0;
			}
			if(unidades[datos.unidad_medida]['estado']==='i' || categorias[datos.categoria_producto]['estado']==='i'){
				nerror=2;
			}else{
				nerror=nerror;
			}
			imagenes[datos.id_producto]=datos.imagen;
			datostb=[datos.placa,//0
					datos.codigo_sena,//1
					datos.serial,//2
					`<img class="img-fluid" src="`+url_base+`./assets/`+dir+`/`+datos.imagen+`" alt="" data-id='`+datos.imagenp+`'>`,//3
					datos.nombre_producto,//4
					unid,//5
					nom,//6
					lineas[datos.linea_producto],//7
					estados[datos.estado_producto],//8
					datos.usuario_producto,//9
					"<center class='d-flex'><button class='visualizar btnVis btn btn-sm btn-info pl-3 pr-3' data-toggle='modal' data-target='#modal-vis-devolutivo' value='"+datos.id_producto+"'><i class='fas fa-eye'></i><button class='btn btn-sm btn-warning btnEdit pl-3 pr-3 ' data-toggle='modal' data-target='#modal-edit-devolutivo' value='"+datos.id_producto+"'><i class='fas fa-edit'></i></button></center>",//10
					datos.precio_producto,//11 
					datos.id_producto,//12
					datos.descripcion_producto,//13
					datos.unidad_medida,//14
					datos.categoria_producto,//15
					datos.linea_producto,//16
					datos.estado_producto,//17
					nerror//18
				];	
			if (opcion==1) {
				var rowNode=table.row(tr).data(datostb).draw(false).node();
				table.row(tr).invalidate();
			}else if (opcion==2) {
				var rowNode=table.row.add(datostb).draw(false).node();
			}
			$(rowNode).find('td:eq(0)').addClass("placa-dev");
			$(rowNode).find('td:eq(1)').addClass("img-dev p-0");
			$(rowNode).find('td:eq(2)').addClass("nomp-dev");
			$(rowNode).find('td:eq(3)').addClass("nomu-dev");
			$(rowNode).find('td:eq(4)').addClass("nomc-dev");
			$(rowNode).find('td:eq(5)').addClass("noml-dev");
			$(rowNode).find('td:eq(6)').addClass("desce-dev");
			$(rowNode).find('td:eq(9)').addClass("btns-cat");
			validarFila(rowNode,unidades[datos.unidad_medida]['estado'],categorias[datos.categoria_producto]['estado'],datos.descripcion_producto,datos.placa,datos.serial,datos.codigo_sena,datos.imagen,datos.nombre_producto,unidades[datos.unidad_medida]['nombre'],categorias[datos.categoria_producto]['nombre'],lineas[datos.linea_producto],estados[datos.estado_producto],datos.usuario_producto,datos.precio_producto);
			$(rowNode).find(".btnEdit").on('click',formInput);
			$(rowNode).find(".btnVis").on('click',formInput); 	
		}else{
			for(var j = 0; j < datos.length; j++){
				if (datos[j].id_producto) {
					console.log("dato->"+j);
					var fila=dataFila(table,datos[j].id_producto,12);
					var nerror;
					let placa=table.cell(fila,0).data();
					let codigo_sena=table.cell(fila,1).data();
					let serial=table.cell(fila,2).data();
					if (datos[j].imagen==null || datos[j].imagen==="") {
						datos[j].imagen='sinFoto.png';
					}else{
						
					}
					if (datos[j].imagen==='sinFoto.png') {
						var dir="img";
					}else{
						var dir="files";
					}
					if (categorias[datos[j].categoria_producto]['estado']==='i') {
						var nom="La categoría "+categorias[datos[j].categoria_producto]['nombre']+" está inactiva";
					}else{
						var nom=categorias[datos[j].categoria_producto]['nombre'];
					}
					if (unidades[datos[j].unidad_medida]['estado']==='i') {
						var unid="La Unidad "+unidades[datos[j].unidad_medida]['nombre']+" está inactiva";
					}else{
						var unid=unidades[datos[j].unidad_medida]['nombre'];
					}
					if (placa==="" || codigo_sena==="" || serial==="" || datos[j].imagen==='sinFoto.png') {
						nerror=1;
					}else{
						nerror=0;
					}
					if(unidades[datos[j].unidad_medida]['estado']==='i' || categorias[datos[j].categoria_producto]['estado']==='i'){
						nerror=2;
					}else{
						nerror=nerror;
					}
					imagenes[datos[j].id_producto]=datos[j].imagen;
					table.cell(fila,3).data(`<img class="img-fluid" src="`+url_base+`./assets/`+dir+`/`+datos[j].imagen+`" alt="" data-id='`+datos[j].imagenp+`'>`).draw(false);
					table.cell(fila,4).data(datos[j].nombre_producto).draw(false);
					table.cell(fila,5).data(unid).draw(false);
					table.cell(fila,6).data(nom).draw(false);
					table.cell(fila,7).data(lineas[datos[j].linea_producto]).draw(false);
					table.cell(fila,8).data(estados[datos[j].estado_producto]).draw(false);
					table.cell(fila,9).data(datos[j].usuario_producto).draw(false);
					table.cell(fila,11).data(datos[j].precio_producto).draw(false);
					table.cell(fila,13).data(datos[j].descripcion_producto).draw(false);
					validarFila(table.row(fila).node(),unidades[datos[j].unidad_medida]['estado'],categorias[datos[j].categoria_producto]['estado'],datos[j].descripcion_producto,placa,serial,codigo_sena,datos[j].imagen,datos[j].nombre_producto,unidades[datos[j].unidad_medida]['nombre'],categorias[datos[j].categoria_producto]['nombre'],lineas[datos[j].linea_producto],estados[datos[j].estado_producto],datos[j].usuario_producto,datos[j].precio_producto);
				}
			}
		}
		
	}	
}
function validarFila(rowNode,unidadesEst,categoriasEst,descripcion,placa,serial,csena,imagen,producto,unidad,categoria,linea,estado,usuario,precio){
	if ($(rowNode).hasClass("caja-info")) {
		$(rowNode).removeClass("caja-info")
		$(rowNode).find('.icon-row-info.info-tooltip').remove();
	}else if ($(rowNode).hasClass("caja-warning")){
		$(rowNode).removeClass("caja-warning")
		$(rowNode).find('.icon-row-warning.warning-tooltip').remove();
	}
	if (unidadesEst==='i' || categoriasEst==='i' || placa==="" || placa==null || serial==="" || serial==null || csena==="" || csena==null  || imagen==="" || imagen==null || imagen==="sinFoto.png" || producto==="" || producto==null  || unidad==="" || unidad==null || categoria==="" || categoria===null || linea==="" || linea==null || estado==="" || estado==null  || usuario==="" || usuario==null || precio==="" || precio==null || precio==0) {
		var mensaje="";
		var bengala=true;
		if (unidadesEst==='i') {
			mensaje+=mensaje==""?"no tiene: !Importante Unidad de medida inactiva":", !Importante Unidad de medida inactiva";
			bengala=false;
		}	
		if (categoriasEst==='i') {
			mensaje+=mensaje==""?"no tiene: !Importante Categoría inactiva":", !Importante Categoría inactiva";
			bengala=false;
		}		
		if (descripcion==="" || descripcion==null) {
			mensaje+=mensaje==""?"no tiene: !Importante Descripción":", !Importante Descripción";						
			bengala=false;
		}		
		if (placa==="" || placa==null) {
			mensaje+=mensaje==""?"no tiene: Placa":", Placa";						
		}		
		if (serial==="" || serial==null) {
			mensaje+=mensaje==""?"no tiene: Serial":", Serial";						
		}
		if (csena==="" || csena==null) {
			mensaje+=mensaje==""?"no tiene: Código Sena":", Código Sena";						
		}
		if (imagen==="" || imagen==null || imagen==="sinFoto.png") {
			mensaje+=mensaje===""?"no tiene: Imagen":", Imagen";						
		}
		if (precio==="" || precio==null || precio==0) {
			mensaje+=mensaje===""?"no tiene: Precio":", Precio";						
		}				
		if (producto==="" || producto==null) {
			mensaje+=mensaje===""?"no tiene: !Importante Producto":", !Importante Producto";
			bengala=false;
		}
		if (categoria==="" || categoria==null) {
			mensaje+=mensaje===""?"no tiene: !Importante Categoría":", !Importante Categoría";						
			bengala=false;
		}
		if (unidad==="" || unidad==null) {
			mensaje+=mensaje===""?"no tiene: !Importante Unidad de medida":", !Importante Unidad de medida";						
			bengala=false;
		}
		if (linea==="" || linea==null) {
			mensaje+=mensaje===""?"no tiene: !Importante Línea":", !Importante Línea";						
			bengala=false;
		}
		if (estado==="" || estado==null) {
			mensaje+=mensaje===""?"no tiene: !Importante Estado":", !Importante Estado";						
			bengala=false;
		}
		if (usuario==="" || usuario==null) {
			mensaje+=mensaje===""?"no tiene: !Importante Usuario":", !Importante Usuario";						
			bengala=false;
		}
		if (bengala) {
			var mensaje1=mensaje;
			mensaje="Vaya!. Al parecer la fila "+mensaje1;
			agregarTooltip(rowNode,0,mensaje);
		}else{
			var mensaje1=mensaje;
			mensaje="Cuidado!. Al parecer la fila "+mensaje1;
			agregarTooltip(rowNode,1,mensaje);
		}						
	}
}
function agregarTooltip(rowNode,tipo=0,mensaje=""){	
	switch (tipo) {
		case 0:
			$(rowNode).addClass("caja-info");			
			$(rowNode).find('td:eq(0)').append(`<div data-toggle="tooltip" data-placement="right" title="`+mensaje+`" class="icon-row-info info-tooltip">!</div>`);
			break;
		case 1:
			$(rowNode).addClass("caja-warning");			
			$(rowNode).find('td:eq(0)').append(`<div data-toggle="tooltip" data-placement="right" title="`+mensaje+`" class="icon-row-warning warning-tooltip">!</div>`);
			break;
	}
	$(rowNode).find('[data-toggle="tooltip"]').tooltip(); 	
}
function consultarDevolutivos(){
	$("#caja-cargando").show();
	$.ajax({
		"url":$('#consult_dev').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){
			$("#caja-cargando").hide();
			if (datos.aviso) {	
				if (datos[0].id_producto) {
					armarTablaDevolutivos(datos);
				}
			}else{
				filtroUsuarios(usuario['id'],"Mis devolutivos");
			}
		}
	}).fail(function(){

	});	
}
function formInput(){	
	idDevolutivo= $(this).parents('tr').find('.btnVis').val();
	tr=dataFila(table,idDevolutivo,12);	
	$("#btn-Provdetalles-vis").click();
	btnFunctionMant();
	if ($(this).hasClass("btnEdit")) {
		limpiarEditFormDevolutivo();
		// Llenar inputs de modal editar
		if ($("#edicion_avanzada").prop('checked')===true) {
			let datos=contarFilasRepetidasEnTablaDevolutivos(table,table.row(tr).data()[4],table.row(tr).data()[15],table.row(tr).data()[16],table.row(tr).data()[17],table.row(tr).data()[14],table.row(tr).data()[9]);
			$("#tab-cant-devs tbody").html("");
			$("#tab-cant-devs tbody").append(datos['fila']);
			$("#cant-devs").show();
			$("#todos_dev").prop('checked',false);
			$(".check_data").on('change',checkData);
			$("#todos_dev").change(function() {
				if ($(this).prop('checked')) {
					$(".check_data").prop('checked',true);	
					$("#form_edit_dev #cantEdit_edit_dev").val(datos['cont']);
				}else{
					$(".check_data").prop('checked',false);	
					$("#form_edit_dev #cantEdit_edit_dev").val(0);
				}
			});
			var arrayChecked=$("#tab-cant-devs").find('.check_data:checked').toArray();
		}else{
			$("#cant-devs").hide();
		}
		$("#usuario_edit_dev").val(table.row(tr).data()[9])
		$("#nombre_edit_dev").val(table.row(tr).data()[4]);
		$("#placa_edit_dev").val(table.row(tr).data()[0]);
		$("#csena_edit_dev").val(table.row(tr).data()[1]);
		$("#serial_edit_dev").val(table.row(tr).data()[2]);
		$("#descripcion_edit_dev").val(table.row(tr).data()[13]);
		$("#precio_edit_dev").val(table.row(tr).data()[11]);
		$("#linea_edit_dev").val(table.row(tr).data()[16]);
		$("#estado_edit_dev").val(table.row(tr).data()[17]);
		$("#unidad_edit_dev").val(table.row(tr).data()[14]);
		$("#categoria_edit_dev").val(table.row(tr).data()[15]);		
		if (imagenes[idDevolutivo]==="sinFoto.png") {
			$("#file_img_edit").attr("src",url_base+"assets/img/sinFoto.png");
			$("#label_imagen_edit_dev").find("span").html("Imagen");
		}else{
			$("#select_imagen_edit_input_dev").val($(table.row(tr).node()).find("img").attr("data-id"));
			$("#file_img_edit").attr("src",url_base+"assets/files/"+imagenes[idDevolutivo]);
			$("#label_imagen_edit_dev").find("span").html(imagenes[idDevolutivo]);
			$.get($("#file_img_edit").attr("src")).fail(function(){
				$("#file_img_edit").attr("src",url_base+"assets/img/sinFoto.png");
				imagenes[idDevolutivo]="sinFoto.png";
				$("#label_imagen_edit_dev").find("span").html("Imagen");
			});
		}		
	}else if ($(this).hasClass("btnVis")){
		$("#usuario_vis_dev").val(table.row(tr).data()[9]);	
		$("#nombre_vis_dev").val(table.row(tr).data()[4]);
		$("#placa_vis_dev").val(table.row(tr).data()[0]);
		$("#csena_vis_dev").val(table.row(tr).data()[1]);
		$("#serial_vis_dev").val(table.row(tr).data()[2]);
		$("#descripcion_vis_dev").val(table.row(tr).data()[13]);
		$("#precio_vis_dev").val(table.row(tr).data()[11]);
		$("#linea_vis_dev").val(table.row(tr).data()[16]);
		$("#estado_vis_dev").val(table.row(tr).data()[17]);
		$("#unidad_vis_dev").val(table.row(tr).data()[14]);
		$("#categoria_vis_dev").val(table.row(tr).data()[15]);
		if (imagenes[idDevolutivo]==="sinFoto.png") {			
			$("#file_img_vis").attr("src",url_base+"assets/img/sinFoto.png");
			$("#label_imagen_vis_dev").find("span").html("Imagen");			
		}else{
			$("#file_img_vis").attr("src",url_base+"assets/files/"+imagenes[idDevolutivo]);
			$("#label_imagen_vis_dev").find("span").html(imagenes[idDevolutivo]);
			$.get($("#file_img_vis").attr("src")).fail(function(){
				$("#file_img_vis").attr("src",url_base+"assets/img/sinFoto.png");
				imagenes[idDevolutivo]="sinFoto.png";
				$("#label_imagen_vis_dev").find("span").html("Imagen");
			});
		}
		if (usuario["tipo"]==="INSTRUCTOR" && table.row(tr).data()[9]===usuario['id'] || usuario["tipo"]==="ADMINISTRADOR") {
			$("#btn-mantenimiento-vis").show();
		}else{
			$("#btn-mantenimiento-vis").hide();
		}

	}
}
// Funcion para obtener la fila real en donde se encuentra almacenado el data real de la fila
function dataFila(tablen,nombre,col=0){	
	var row;	
	var Nfilas=tablen.rows().count();
	for (row = 0; row <= Nfilas; row++) {
		if (limpiarVocales(tablen.data()[row][col])==limpiarVocales(nombre)) {
			return row;
		}	
	}	
}
function agregarFiltro(){	
	var promise=$.Deferred();
	$("#filtro").html(`<ul class="mb-0 pl-0" style="list-style-type:none;">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 mr-3 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>
    		<ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">		      	
    			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Usuario</a>
        			<ul id="f_usuario" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        									
        			</ul>
      			</li>
      			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Unidad</a>
        			<ul id="f_unidad" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
        			</ul>        			
      			</li>
      			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Categoría</a>
        			<ul id="f_categoria" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        			
        			</ul>
      			</li>
      			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Linea</a>
        			<ul id="f_linea" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
        			</ul>
      			</li>
      			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Estado</a>
        			<ul id="f_estado" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
        			</ul>
      			</li>
      			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Errores</a>
        			<ul id="f_errores" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
        				<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-error="1">Informativos</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-error="2">Advertencias</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-error="0">Sin Errores</a>
						</li>
        			</ul>
      			</li>
    		</ul>
  		</li>
	</ul>	
	`);
	
	$("#filtros").append(`<div id="cajas-filtro" class="d-flex flex-wrap justify-content-start col-md-9" style="width:auto;"></div>`);
	$("#filtro2").html(`<ul class="mb-0 pl-0" style="list-style-type:none;">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 mr-3 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>
    		<ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">		      	
    			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Estado</a>
        			<ul id="f_estadoMant" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        									
        				<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="Terminado">Terminado</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="Vigente">Vigente</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="Expirado">Expirado</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="Ahora">Ahora</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="Anulado">Anulado</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="En proceso">En proceso</a>
						</li>
        			</ul>
      			</li>
      			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Mantenimiento</a>
        			<ul id="f_Mant" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
        				<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-mant="Correctivo">Correctivo</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-mant="Preventivo">Preventivo</a>
						</li>
        			</ul>        			
      			</li>
    		</ul>
  		</li>
	</ul>	
	`);
	$("#filtros2").append(`<form id="form_fecha_mant" class='row align-items-center justify-content-center'><input type='date' id='fecha_mantenimiento_ini' class="form-control w-auto mr-1" required> <input type='date' id='fecha_mantenimiento_fin' class="form-control w-auto mr-1"><button type='submit' class='btn btn-sm btn-primary pr-3 pl-3'><i class="fas fa-search"></i></button></form><div id="cajas-filtro2" class="d-flex flex-wrap justify-content-start col-md-9 mt-2" style="width:auto;"></div>`);
	consultarImagenesDevolutivo();
	consultarFiltroCategorias();
	consultarFiltroUsuarios();
	consultarFiltroUnidades();
	consultarFiltroLineas();
	consultarFiltroEstados()
	window.setTimeout(function() {
    	promise.resolve();
  	},3000);	
	return promise.promise();
}
function consultarFiltroCategorias(){
	var selectAdd=$("#categoria_add_dev");	
	var selectEdit=$("#categoria_edit_dev");	
	var selectVis=$("#categoria_vis_dev");	
	$.ajax({
		"url":$('#consult_cat').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){						
			if (datos.aviso) {	
				if (datos[0]) {
					if (datos[0].nombre_categoria) {
						$.each(datos,function(){
							if (this.estado==='a') {
								$("#f_categoria").append(`<li class="dropdown-item p-0">
													<a href="#" class="dropdown-item w-100">`+this.nombre_categoria+`</a>
												</li>`);
								selectAdd.append(`<option value="`+this.id_categoria+`">`+this.nombre_categoria+`</option>`);
								selectEdit.append(`<option value="`+this.id_categoria+`">`+this.nombre_categoria+`</option>`);
								selectVis.append(`<option value="`+this.id_categoria+`">`+this.nombre_categoria+`</option>`);
							}
							categorias[this.id_categoria]=new Array();	
							categorias[this.id_categoria]['nombre']=this.nombre_categoria;
							categorias[this.id_categoria]['estado']=this.estado;
						});
					}
					
				}
			}else{
				$("#f_categoria").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">No hay categorías registradas</a>
											</li>`);
			}
		}
	}); 
}
function consultarFiltroUsuarios(){
	var selectAdd=$("#usuario_add_dev");	
	var selectEdit=$("#usuario_edit_dev");	
	var selectVis=$("#usuario_vis_dev");
	$.ajax({
		"url":$('#consult_users').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){							
			if (datos.aviso) {	
				if (datos[0].id_usuario) {
					$.each(datos,function(){
						if (this.id_usuario) {
							if (usuario["tipo"]==="INSTRUCTOR" && this.nombre_tipo==="INSTRUCTOR" || usuario["tipo"]==="ADMINISTRADOR") {
								if (usuario["tipo"]==="ADMINISTRADOR" && usuario['id']===this.id_usuario) {
									$("#f_usuario").append(`<li class="dropdown-item p-0">
														<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">Mis devolutivos</a>
													</li>`);
									selectAdd.append(`<option value="`+this.id_usuario+`">Mi usuario</option>`);
									selectEdit.append(`<option value="`+this.id_usuario+`">Mi usuario</option>`);
									selectVis.append(`<option value=`+this.id_usuario+`>Mi devolutivo</option>`);
								}else if (usuario["tipo"]==="ADMINISTRADOR"){
									$("#f_usuario").append(`<li class="dropdown-item p-0">
														<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</a>
													</li>`);
									selectAdd.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
									selectEdit.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
									selectVis.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
								}							
								if (usuario["tipo"]==="INSTRUCTOR" && this.nombre_tipo==="INSTRUCTOR") {
									if (usuario['id']===this.id_usuario) {
										$("#f_usuario").append(`<li class="dropdown-item p-0">
															<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">Mis devolutivos</a>
														</li>`);
										selectAdd.append(`<option value="`+this.id_usuario+`">Mi usuario</option>`);
										selectEdit.append(`<option value="`+this.id_usuario+`">Mi usuario</option>`);
										selectVis.append(`<option value=`+this.id_usuario+`>Mi devolutivo</option>`);
									}else{
										$("#f_usuario").append(`<li class="dropdown-item p-0">
														<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</a>
													</li>`);
										selectAdd.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
										selectEdit.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
										selectVis.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
									}									
								}

							}else{
								selectAdd.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
								selectEdit.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
								selectVis.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
							}
						}						
					});
				}
			}else{
				$("#f_usuario").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">No hay Usuarios registrados</a>
											</li>`);
			}
		}
	});
}
function consultarFiltroUnidades(){
	var selectAdd=$("#unidad_add_dev");	
	var selectEdit=$("#unidad_edit_dev");	
	var selectVis=$("#unidad_vis_dev");	
	$.ajax({
		"url":$('#consult_und').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){
			if (datos.aviso) {	
				if (datos[0].nombre_unidad) {
					$.each(datos,function(){
						if (this.estado==='a') {
							$("#f_unidad").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">`+this.nombre_unidad+`</a>
											</li>`);
							if (limpiarVocales(this.nombre_unidad)===limpiarVocales("Unidad")) {
								selectAdd.append(`<option value="`+this.id_unidad+`">`+this.nombre_unidad+`</option>`);
								selectEdit.append(`<option value="`+this.id_unidad+`">`+this.nombre_unidad+`</option>`);
								selectVis.append(`<option value="`+this.id_unidad+`">`+this.nombre_unidad+`</option>`);
								selectEdit.val(this.id_unidad);
								selectAdd.val(this.id_unidad);
							}
						}		
						unidades[this.id_unidad]=new Array();	
						unidades[this.id_unidad]['nombre']=this.nombre_unidad;
						unidades[this.id_unidad]['estado']=this.estado;
					});
				}
			}else{
				$("#f_unidad").append(`<li class="dropdown-item p-0">
											<a href="#" class="dropdown-item w-100">Nohay unidades</a>
										</li>`);
			}
		}
	});
} 
function consultarFiltroLineas(){
	var selectAdd=$("#linea_add_dev");	
	var selectEdit=$("#linea_edit_dev");	
	var selectVis=$("#linea_vis_dev");	
	$.ajax({
		"url":$('#consult_lin').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){						
			if (datos.aviso) {	
				if (datos[0].nombre_linea) {
					$.each(datos,function(){
						if (this.nombre_linea) {
							if (usuario["linea"]===this.nombre_linea && usuario["tipo"]==="INSTRUCTOR") {
								$("#f_linea").append(`<li class="dropdown-item p-0">
													<a href="#" class="dropdown-item w-100" data-linea="`+this.nombre_linea+`">Mi linea</a>
												</li>`);
							}else{
								$("#f_linea").append(`<li class="dropdown-item p-0">
													<a href="#" class="dropdown-item w-100" data-linea="`+this.nombre_linea+`">`+this.nombre_linea+`</a>
												</li>`);
							}
							selectAdd.append(`<option value="`+this.id_linea+`">`+this.nombre_linea+`</option>`);
							selectEdit.append(`<option value="`+this.id_linea+`">`+this.nombre_linea+`</option>`);
							selectVis.append(`<option value="`+this.id_linea+`">`+this.nombre_linea+`</option>`);
						}
						var id_lin=this.id_linea;
						var nom_lin=this.nombre_linea;
						lineas[id_lin]=nom_lin;
					});
				}
			}else{
				$("#f_linea").append(`<li class="dropdown-item p-0">
											<a href="#" class="dropdown-item w-100">No hay lineas</a>
										</li>`);
			}
		}
	});
}
function consultarFiltroEstados(){
	var selectAdd=$("#estado_add_dev");	
	var selectEdit=$("#estado_edit_dev");	
	var selectVis=$("#estado_vis_dev");	
	$.ajax({
		"url":$('#consult_est').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){						
			if (datos.aviso) {	
				if (datos[0].descripcion_estado) {
					$.each(datos,function(){
						if (this.descripcion_estado) {
							$("#f_estado").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">`+this.descripcion_estado+`</a>
											</li>`);
							selectAdd.append(`<option value="`+this.id_estado+`">`+this.descripcion_estado+`</option>`);
							selectEdit.append(`<option value="`+this.id_estado+`">`+this.descripcion_estado+`</option>`);
							selectVis.append(`<option value="`+this.id_estado+`">`+this.descripcion_estado+`</option>`);
						}						
						var id_est=this.id_estado;
						var nom_est=this.descripcion_estado;
						estados[id_est]=nom_est;
					});					
				}else{
					$("#f_estado").append(`<li class="dropdown-item p-0">
											<a href="#" class="dropdown-item w-100">`+datos.descripcion_estado+`</a>
										</li>`);
				}
			}
		}
	});
}
function consultarMantenimientos(){
	$("#caja-cargando").show();
	$.ajax({
		"url":$("#input-mantenimiento").val(),
		"type":"POST",
		"data":{"id_devolutivo":idDevolutivo},
		"dataType":"json",
		success: function(datos){
			$("#caja-cargando").hide();
			if (datos.aviso) {	
				if (datos[0].devolutivo) {
					armarTablaMantenimiento(datos);
				}
			}else{
			}
		}
	});	
}
function armarTablaMantenimiento(datos,opcion=0){	
	var datostb=[];
	tableMant=$("#table-mantenimiento-dev").DataTable();
	var inicaja="<center class='d-flex'>";
	var fincaja="</center>";
	if (opcion==0) {
		$.each(datos, function () {	
			if (this.devolutivo) {
				var btn1="<button type='button' class='btnEditMan btn btn-sm btn-warning pl-3 pr-3' data-toggle='modal' value='"+this.devolutivo+"'><i class='fas fa-edit'></i></i></button>";
				var btn2="<button type='button' class='btnCancelMant btn btn-sm btn-danger  pl-3 pr-3 ' data-toggle='modal' value='"+this.devolutivo+"'>X</button>";
				var btns=inicaja+btn1+btn2+fincaja;
				if (this.estado_matenimiento==="Anulado" || this.estado_matenimiento==="Terminado") {
					btns="";
				}
				datostb=[
						this.registrado,//0
						this.tipo_matenimiento,//3
						this.fecha_inicio,//1
						this.fecha_fin,//2
						this.estado_matenimiento,//4
						btns//5
					];						
				var rowNode=tableMant.row.add(datostb).draw(false).node();
				$(rowNode).find('td:eq(0)').addClass("reg-man pl-1 pr-1");
				$(rowNode).find('td:eq(1)').addClass("mant-man pl-1 pr-1 text-center");
				$(rowNode).find('td:eq(2)').addClass("ini-man pl-1 pr-1 text-center");
				$(rowNode).find('td:eq(3)').addClass("fin-man pl-1 pr-1 text-center");
				$(rowNode).find('td:eq(4)').addClass("est-man text-center");
				$(rowNode).find('td:eq(5)').addClass("btns-man pl-1 pr-1 text-center");
				// Borde de la fila en DataTable
				
			}										
			$(".btnEditMan").on('click',formMant);
			$(".btnCancelMant").on('click',formMant); 	
		});
	}else if (opcion==1 || opcion==2){
		var btn1="<button type='button' class='btnEditMan btn btn-sm btn-warning pl-3 pr-3' data-toggle='modal' value='"+datos.devolutivo+"'><i class='fas fa-edit'></i></i></button>";
		var btn2="<button type='button' class='btnCancelMant btn btn-sm btn-danger  pl-3 pr-3 ' data-toggle='modal' value='"+datos.devolutivo+"'>X</button>";
		var btns=inicaja+btn1+btn2+fincaja;
		if (datos.estado_matenimiento==="Anulado" || datos.estado_matenimiento==="Terminado") {
			btns="";
		}
		datostb=[
				datos.registrado,//0
				datos.tipo_matenimiento,//3
				datos.fecha_inicio,//1
				datos.fecha_fin,//2
				datos.estado_matenimiento,//4
				btns//5
			];						
		if (opcion==1) {
			var rowNode=tableMant.row(tr).data(datostb).draw(false).node();
			table.row(tr).invalidate();
		}else if (opcion==2) {
			var rowNode=tableMant.row.add(datostb).draw(false).node();
		}
		$(rowNode).find('td:eq(0)').addClass("reg-man pl-1 pr-1");
		$(rowNode).find('td:eq(1)').addClass("mant-man pl-1 pr-1 text-center");
		$(rowNode).find('td:eq(2)').addClass("ini-man pl-1 pr-1 text-center");
		$(rowNode).find('td:eq(3)').addClass("fin-man pl-1 pr-1 text-center");
		$(rowNode).find('td:eq(4)').addClass("est-man text-center");
		$(rowNode).find('td:eq(5)').addClass("btns-man pl-1 pr-1 text-center");
		$(".btnEditMan").on('click',formMant);
		$(".btnCancelMant").on('click',formMant);
	}else if (opcion==3) {
		datostb=[
				tableMant.row(tr).data()[0],//0
				tableMant.row(tr).data()[1],//3
				tableMant.row(tr).data()[2],//1
				tableMant.row(tr).data()[3],//2
				'Anulado',//4
				''//5
			];
		var rowNode=tableMant.row(tr).data(datostb).draw(false).node();
		table.row(tr).invalidate();
		$(rowNode).find('td:eq(0)').addClass("reg-man pl-1 pr-1");
		$(rowNode).find('td:eq(1)').addClass("mant-man pl-1 pr-1");
		$(rowNode).find('td:eq(2)').addClass("ini-man pl-1 pr-1 text-center");
		$(rowNode).find('td:eq(3)').addClass("fin-man pl-1 pr-1 text-center");
		$(rowNode).find('td:eq(4)').addClass("est-man text-center");
		$(rowNode).find('td:eq(5)').addClass("btns-man pl-1 pr-1 text-center");
		$(".btnEditMan").on('click',formMant);
		$(".btnCancelMant").on('click',formMant);
	}
	cambioTamanos()	 
}
function formMant(){
	tableMant=$("#table-mantenimiento-dev").DataTable();
	tr=dataFila(tableMant,$(this).parents('tr').find('.reg-man').text());
	if ($(this).hasClass("btnEditMan")) {
		$("#registrado_mant").val(tableMant.row(tr).data()[0]),
		$("#btn-agregarMant-vis").click();
		$("#form_add_mant").hide();
		$("#form_edit_mant").show();
		$("#mant-edit-select").val(tableMant.row(tr).data()[1]);
		$("#date-edit-ini").val(tableMant.row(tr).data()[2]);
		$("#date-edit-fin").val(tableMant.row(tr).data()[3]);
		if (tableMant.row(tr).data()[3]==='0000-00-00' || tableMant.row(tr).data()[3]==null || tableMant.row(tr).data()[2]===tableMant.row(tr).data()[3]) {
			$("#rango-edit-fecha").prop('checked',false);
			$("#caja-date-edit-fin").hide();
			$("#date-edit-ini").parents("div.form-group").find('label').text('Fecha:');
		}else{
			$("#rango-edit-fecha").prop('checked',true);
			$("#caja-date-edit-fin").show();
			$("#date-edit-ini").parents("div.form-group").find('label').text('Fecha inicio:');
		}
		$("#date-edit-fin").attr('min',$("#date-edit-ini").val());
		if (tableMant.row(tr).data()[4]==="Expirado" || tableMant.row(tr).data()[4]==="Ahora") {
			$("#est-edit-select").val("Vigente");
		}else{
			$("#est-edit-select").val(tableMant.row(tr).data()[4]);
		}
		cambioTamanos();
	}else if ($(this).hasClass("btnCancelMant")){
		var registro=tableMant.row(tr).data()[0];
		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success',
		    cancelButton: 'btn btn-danger'
		  },
		  buttonsStyling: false
		})
		swalWithBootstrapButtons.fire({
		  title: 'Estas seguro?',
		  text: "El mantenimiento será anulado!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonText: 'Si, anulelo!',
		  cancelButtonText: 'No, lo cancelo!',
		  reverseButtons: true
		}).then((result) => {
		  if (result.value) {
		    anularMantenimiento(registro);
		  } else if (result.dismiss === Swal.DismissReason.cancel) {
		    Swal.fire({
	             	  	title:'Cancelado!',
		             	text:'Has cancelado el proceso',
	             	  	icon:'error',
	             	  	showConfirmButton:false,
	             	  	timer:1200
	             	});
		  }
		});

	}
}
function anularMantenimiento(registrado){
	$("#caja-cargando").show();
	$.ajax({
		"url":$("#anular_mant").val(),
		"type":"post",
		"data":{"registrado":registrado,
				"devolutivo":idDevolutivo
		},
		"dataType":"json",
		success:function(datos){
			$("#caja-cargando").hide();
			if (datos.aviso) {
				armarTablaMantenimiento(datos,3);
				Swal.fire({
		             	  title:'Exito!',
		             	  text:datos.texto,
		             	  icon:'success',
		             	  showConfirmButton:false,
		             	  timer:1200
		             	});
				$("#btn-agregarMantCancel-vis").click();
			}else{
				Swal.fire({
		             	  title:'Vaya!',
		             	  text:datos.texto,
		             	  icon:'info',
		             	  showConfirmButton:false,
		             	  timer:1200
		             	});
			}
		}

	});	
}
function guardarMantenimiento(){
	var opt=$("#rango-add-fecha").prop('checked');
	$("#caja-cargando").show();
	$.ajax({
		"url":$("#form_add_mant").attr('action'),
		"type":$("#form_add_mant").attr('method'),
		"data":{"opt_man":opt,
				"fecha_inicial":$("#date-add-ini").val(),
				"fecha_final":$("#date-add-fin").val(),
				"mantenimiento":$("#mant-add-select").val(),
				"devolutivo":idDevolutivo,
				"fecha_actual":$("#fecha-actual").val(),
				"estado":$("#est-add-select").val()
		},
		"dataType":"json",
		success:function(datos){
			$("#caja-cargando").hide();
			if (datos.aviso) {
				armarTablaMantenimiento(datos,2);
				limpiarFechas();
				Swal.fire({
		             	  title:'Exito!',
		             	  text:datos.texto,
		             	  icon:'success',
		             	  showConfirmButton:false,
		             	  timer:1200
		             	});
				$("#btn-agregarMantCancel-vis").click();
			}else{
				Swal.fire({
		             	  title:'Vaya!',
		             	  text:datos.texto,
		             	  icon:'info',
		             	  showConfirmButton:false,
		             	  timer:1200
		             	});
			}
		}

	});
}
function limpiarFechas(){
	$("#date-add-ini").val("");
	$("#date-add-fin").val("");
}
function editarMantenimiento(){
	var opt=$("#rango-edit-fecha").prop('checked');
	$("#caja-cargando").show();
	$.ajax({
		"url":$("#form_edit_mant").attr('action'),
		"type":$("#form_edit_mant").attr('method'),
		"data":{"opt_man":opt,
				"fecha_inicial":$("#date-edit-ini").val(),
				"fecha_final":$("#date-edit-fin").val(),
				"mantenimiento":$("#mant-edit-select").val(),
				"devolutivo":idDevolutivo,
				"registrado":$("#registrado_mant").val(),
				"fecha_actual":$("#fecha-actual").val(),
				"estado":$("#est-edit-select").val()
		},
		"dataType":"json",
		success:function(datos){
			$("#caja-cargando").hide();
			if (datos.aviso) {
				armarTablaMantenimiento(datos,1);
				limpiarFechas();
				Swal.fire({
		             	  title:'Exito!',
		             	  text:datos.texto,
		             	  icon:'success',
		             	  showConfirmButton:false,
		             	  timer:1200
		             	});
				$("#btn-agregarMantCancel-vis").click();
			}else{
				Swal.fire({
		             	  title:'Vaya!',
		             	  text:datos.texto,
		             	  icon:'info',
		             	  showConfirmButton:false,
		             	  timer:1200
		             	});
			}
		}

	});
}
function datatable2() {
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
    "dom":'<"row"<"col-sm-12 col-md-7"<"#filtros2.row align-items-top"<"#filtro2.w-auto mb-2 mr-3">>><"col-sm-12 col-md-5"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row"<"col-sm-12 col-md-8"i><"col-sm-12 col-md-4 d-flex align-items-center justify-content-center"p>>',
    "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
    "lengthChange": false
  });
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
    "dom":'<"row"<"col-sm-12 col-md-7"<"#filtros3.row"<"#filtro3.w-auto mr-3">>><"col-sm-12 col-md-5"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row"<"col-sm-12 col-md-8"i><"col-sm-12 col-md-4 d-flex align-items-center justify-content-center"p>>',
    "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
    "lengthChange": false
  });
}
function consultarProveedoresDevolutivo(){
	$("#caja-cargando").show();
	$.ajax({
		"url":url_base+"index.php/Devolutivos_controller/consultarUrlsProveedores",
		"type":"POST",
		"data":{"idDevolutivo":idDevolutivo},
		"dataType":"json",
		success: function(datos){
			$("#caja-cargando").hide();
			if (datos.aviso) {	
				armarTablaProveedores(datos,0);
			}else{
			}
		}
	});	
}
function armarTablaProveedores(datos,opcion=0){	
	var datostb=[];
	tableProv=$("#table-proveedores-dev").DataTable();
	if (opcion==0) {
		$.each(datos, function () {	
			if (this.id_proveedor) {
				datostb=[
						this.nit,//0
						this.nombre_proveedor,//3
						this.telefono_proveedor,//1
						"<a href='"+this.url+"' target='_blank'>"+this.url+"</a>",//2
						this.id_proveedor//4
					];						
				var rowNode=tableProv.row.add(datostb).draw(false).node();
				$(rowNode).find('td:eq(0)').addClass("nit-prov pl-1 pr-1 text-center");
				$(rowNode).find('td:eq(1)').addClass("nom-prov pl-1 pr-1 text-center");
				$(rowNode).find('td:eq(2)').addClass("tel-provr pl-1 pr-1 text-center");
				$(rowNode).find('td:eq(3)').addClass("url_pocr pl-1 pr-1");
				// Borde de la fila en DataTable
				
			}										
		});
	}else{
		datostb=[
				datos.nit,//0
				datos.nombre_proveedor,//3
				datos.telefono_proveedor,//1
				datos.url,//2
				datos.id_proveedor//4
			];					
		if (opcion==1) {
			var rowNode=tableProv.row(tr).data(datostb).draw(false).node();
			table.row(tr).invalidate();
		}else if (opcion==2) {
			var rowNode=tableProv.row.add(datostb).draw(false).node();
		}
		$(rowNode).find('td:eq(0)').addClass("reg-man pl-1 pr-1");
		$(rowNode).find('td:eq(1)').addClass("mant-man pl-1 pr-1");
		$(rowNode).find('td:eq(2)').addClass("ini-man pl-1 pr-1 text-center");
		$(rowNode).find('td:eq(3)').addClass("fin-man pl-1 pr-1 text-center");
	}	
	cambioTamanos();	
}
function consultarImagenesDevolutivo(){
	$.ajax({
		url: url_base+'Galeria_controller/consultarImagenesDevolutivo',
		type: 'post',
		dataType: 'json',
		success: function(datos){ 
			if (datos.aviso) {
				for(var i = 0; i < datos.imagenes.length; i++){
					let opt=`<option data-imagesrc="`+url_base+`assets/files/`+datos.imagenes[i].imagen+`" value="`+datos.imagenes[i].id_galeria+`">`+datos.imagenes[i].nombre+`</option>`;
					$("#select_imagen_add_dev").append(opt);
				}
			}
		}
	});	
}
function contarFilasRepetidasEnTablaDevolutivos(tabla,nombre,categoria,linea,estado,unidad,usuario){
  var array=[];
  array['cont']= 0;
  array['fila']=``;
  tabla.rows().every(function(){    
    rowData=this.data();
    if (limpiarVocales(rowData[4])== limpiarVocales(nombre) && rowData[15]==categoria && rowData[16]==linea && rowData[17]==estado && rowData[14]==unidad && limpiarVocales(rowData[9])== limpiarVocales(usuario)) {      
      array['cont']++;
      array['fila']+=`<tr>
                        <td class="text-center">
                        	<div class="custom-control custom-checkbox">
	                          <input type="checkbox" class="custom-control-input check_data" id="devCheck_`+rowData[12]+`">
	                          <label for="devCheck_`+rowData[12]+`" class="custom-control-label"></label>
	                        </div>
                        </td>
                        <td>`+rowData[4]+`</td>
                        <td class="text-center">`+rowData[0]+`</td>
                        <td class="text-center">`+rowData[2]+`</td>
                        <td class="text-center">`+rowData[1]+`</td>
                        <td class="text-center">`+rowData[7]+`</td>
                      </tr>`;
    }                     
  });
  return array;
}
function checkData(){
	var cant=$("#form_edit_dev #cantEdit_edit_dev").val();
	if ($(this).prop('checked')) {
		cant++;
	}else{
		$("#tab-cant-devs #todos_dev").prop('checked',false);
		cant--;
	}
	let cantTable=$("#tab-cant-devs").find('.check_data').toArray().length;
	if (cantTable==cant) {
		$("#tab-cant-devs #todos_dev").prop('checked',true);
		$("#form_edit_dev #cantEdit_edit_dev").val(cant);
	}else{
		$("#form_edit_dev #cantEdit_edit_dev").val(cant);
	}
}
 // Si paso paso 5 min sin entender pregunto