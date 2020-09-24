$(document).ready(iniciar);
var tbsolicitudes;
var url_base;
var categorias;
var unidades;
var lineas;
var estados;
var usuarios;
var usuario;
var tableSolicitudes;
var table_salida;
function iniciar(){
	categorias=new Array();
	usuarios=new Array();
	unidades=new Array();
	lineas=new Array();
	estados=new Array();
	usuario=new Array();
	imagenes=new Array();
	usuario['id']=$("#id_usuario").val();
	usuario['nombre']=$("#nombre_usuario").val();
	usuario['tipo']=$("#tipo_usuario").val();
	usuario['linea']=$("#linea_usuario").val();
	url_base=$("#url_base").val();
	$.when(dataTables()).then(function(e){
		agregarFunciones();	
		visualizarColumnaEntabla(table_salida,[7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29],false);
		window.setTimeout(function() {
			$("#caja-cargando").hide();
			$("#caja-salidas").hide();
    	},1300);
	});
    $("#crearSolicitud").click(crearSolicitud);
	tbsolicitudes=$("#table_solicitudes").DataTable();
	visualizarColumnaEntabla(tbsolicitudes,0,false);
	agregarFuncionesTable();
}
function crearSolicitud(){
	$("#caja-cargando").show();
	if (buscarEnColumna(tableSolicitudes,"En proceso",3)) {
		$.ajax({
			url: url_base+'Solicitudes_controller/agregarSolicitud',
			type: 'POST',
			dataType: 'json',
			data: {"usuario_solicitud":usuario['id']},
			success:function(){
				$(location).attr('href',url_base+"/Vistas/reg_solicitud");
			}
		}).fail(function() {
			$("#caja-cargando").hide();
			let tipo="info";
			let titulo="Vaya!";
			let texto="No se logró crear la solicitud";		
			alertasTemporal(tipo,titulo,texto);
		});
	}else{
		$("#caja-cargando").hide();
			let tipo="info";
			let titulo="Vaya!";
			let texto="No puedes continuar porque existe una solicitud en proceso";		
			alertasTemporal(tipo,titulo,texto);
	}
}
function alertasTemporal(tipo,titulo,texto){
	Swal.fire({
	  title:titulo,
	  text:texto,
	  icon:tipo,
	  showConfirmButton:false,
	  timer:1700
	});			
}
function TerminarPrestamos(){
		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success',
		    cancelButton: 'btn btn-danger'
		  },
		  buttonsStyling: false
		});
		swalWithBootstrapButtons.fire({
		  title: 'Estas seguro?',
		  text: "Se retornaran todos los productos prestados de la solicitud al inventario!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonText: 'Si, Continúe!',
		  cancelButtonText: 'No, lo cancelo!',
		  reverseButtons: true
		}).then((result) => {
	  		if (result.value) {
	  			var id=$(this).val();
	  			var tr=dataFila(tableSolicitudes,id,0);
	  			$("#caja-cargando").show();
	  			$.ajax({
	  				"url":url_base+"/Solicitudes_controller/terminarSalidasPrestamos",
	  				"type":"post",
	  				"data":{"id_solicitud":id},
	  				"dataType":"json",
	  				success:function(datos){
	  					$("#caja-cargando").hide();
	  					if (datos.aviso) {
	  						let titulo="Exito!";
	  						let tipo="success";
	  						let texto="Se terminó la solicitud!";
	  						alertasTemporal(tipo,titulo,texto);
	  						tableSolicitudes.cell(tr,3).data("Terminado").draw(false);
	  						let btn="<button type='button' value='"+id+"' class='btn btnVis btn-sm btn-primary pr-3 pl-3 mr-1 ml-1' title='Visualizar solicitud'><i class='fas fa-eye'></i></button>"
	  						tableSolicitudes.cell(tr,5).data(btn).draw(false);
	  						let rowNode=tableSolicitudes.row(tr).node();
	  						$(rowNode).find(".btnVis").click(visualizarSolicitud);
	  					}else{
	  						let titulo="Vaya!";
	  						let tipo="info";
	  						let texto="No se pudo terminar la solicitud";
	  						alertasTemporal(tipo,titulo,texto);
	  					}
	  				}
	  			}).fail(function(){
	  				$("#caja-cargando").hide();
	  				let titulo="Vaya!";
	  				let tipo="info";
	  				let texto="Ocurrio un error inseperado al terminar préstamos";
	  				alertasTemporal(tipo,titulo,texto);
	  			});	
	  		} else if (result.dismiss === Swal.DismissReason.cancel) {
	  			let titulo="Cancelado!";
				let tipo="error";
				let texto="Has cancelado la operación";
				alertasTemporal(tipo,titulo,texto);
	  		}
	  	});
}
function dataFila(tablen,nombre,col=0){	
	var row;	
	var Nfilas=tablen.rows().count();
	for (row = 0; row <= Nfilas; row++) {
		if (limpiarVocales(tablen.data()[row][col])==limpiarVocales(nombre)) {
			return row;
		}	
	}	
}
function agregarFuncionesTable(){
	tableSolicitudes.rows().every(function(){    
	    rowNode=this.node();
	    $(rowNode).find(".btnCancel").click(cancelarSolicitud); 
	    $(rowNode).find(".btnVis").click(visualizarSolicitud);
	    $(rowNode).find(".btnTerminar").click(TerminarPrestamos);
	});
}
function cancelarSolicitud(){
	const swalWithBootstrapButtons = Swal.mixin({
	  customClass: {
	    confirmButton: 'btn btn-success',
	    cancelButton: 'btn btn-danger'
	  },
	  buttonsStyling: false
	});
	swalWithBootstrapButtons.fire({
		  title: 'Estas seguro?',
		  text: "Estas seguro de cancelar la solicitud!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonText: 'Si, Continúe!',
		  cancelButtonText: 'No, lo cancelo!',
		  reverseButtons: true
		}).then((result) => {
	  		if (result.value) {
	  			var id=$(this).val();
	  			var tr=dataFila(tableSolicitudes,id,0);
	  			$("#caja-cargando").show();
	  			$.ajax({
	  				"url":url_base+"/Solicitudes_controller/cancelSolicitud",
	  				"type":"post",
	  				"data":{"id_solicitud":id},
	  				"dataType":"json",
	  				success:function(datos){
	  					$("#caja-cargando").hide();
	  					if (datos.aviso) {
	  						let titulo="Exito!";
	  						let tipo="success";
	  						let texto="Se canceló la solicitud!";
	  						alertasTemporal(tipo,titulo,texto);
	  						tableSolicitudes.cell(tr,3).data("Cancelado").draw(false);
	  						tableSolicitudes.cell(tr,5).data("").draw(false);
	  					}else{
	  						let titulo="Vaya!";
	  						let tipo="info";
	  						let texto="No se pudo cancelar la solicitud";
	  						alertasTemporal(tipo,titulo,texto);
	  					}
	  				}
	  			}).fail(function(){
	  				$("#caja-cargando").hide();
	  				let titulo="Vaya!";
	  				let tipo="info";
	  				let texto="Ocurrio un error inseperado al cancelar la solicitud";
	  				alertasTemporal(tipo,titulo,texto);
	  			});
	  		} else if (result.dismiss === Swal.DismissReason.cancel) {
	  			let titulo="Cancelado!";
				let tipo="error";
				let texto="Has cancelado la operación";
				alertasTemporal(tipo,titulo,texto);
	  		}
	  	});
}
function visualizarSolicitud(){
	var id=$(this).val();
	consultarSalidas(id);
}
function dataTables(){
	var promise=$.Deferred();
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
	    "fixedColumns":{
	    	"heightMatch":'none'
	    },
	    "dom":'<"row"<"col-sm-12 col-md-7"<"#filtros2.row"<"#filtro2.w-auto mr-3">>><"col-sm-12 col-md-5"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row"<"col-sm-12 col-md-8"i><"col-sm-12 col-md-4 d-flex align-items-center justify-content-center"p>>',
	    "lengthMenu": [ [8, 25, 50, -1], [8, 25, 50, "All"] ],
	    "lengthChange": false
	});
	$("#filtro2").addClass('ml-2');
	$("#filtro2").html(`<ul class="mb-0 pl-0" style="list-style-type:none;">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 mr-3 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>
    		<ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">		      	
	  			<li class="dropdown-item dropdown-submenu p-0">
	   				<a href="#" data-toggle="dropdown" 
	   				class="dropdown-toggle dropdown-item w-100">Unidad</a>
	    			<ul class="f_unidad dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
	    			</ul>        			
	  			</li>
	  			<li class="dropdown-item dropdown-submenu p-0">
	   				<a href="#" data-toggle="dropdown" 
	   				class="dropdown-toggle dropdown-item w-100">Categoría</a>
	    			<ul class="f_categoria dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        			
	    			</ul>
	  			</li>
	  			<li class="dropdown-item dropdown-submenu p-0">
	   				<a href="#" data-toggle="dropdown" 
	   				class="dropdown-toggle dropdown-item w-100">Linea</a>
	    			<ul class="f_linea dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
	    			</ul>
	  			</li>
	  			<li class="dropdown-item dropdown-submenu p-0">
	   				<a href="#" data-toggle="dropdown" 
	   				class="dropdown-toggle dropdown-item w-100">Estado</a>
	    			<ul class="f_estado dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
	    			</ul>
	  			</li>
    			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Estado</a>
        			<ul class="f_estadoSalida dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">
        				<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="En prestamo">En prestamo</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="Retornado">Retornado</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-estado="No retorna">No retorna</a>
						</li>
        			</ul>
      			</li>
      			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Salida</a>
        			<ul class="f_tipoSalida dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        				
        				<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-salida="Prestamo">Prestamo</a>
						</li>
						<li class="dropdown-item p-0">
							<a href="#" class="dropdown-item w-100" data-salida="Definitiva">Definitiva</a>
						</li>
        			</ul>        			
      			</li>
    		</ul>
  		</li>
	</ul>	
	`);
	$("#filtros2").append(`<div id="cajas-filtro2" class="d-flex flex-wrap justify-content-start col-md-9" style="width:auto;"></div>`);	
	tableSolicitudes=$("#table_solicitudes").DataTable();
	table_salida=$("#table_productos_solicitados").DataTable();
	window.setTimeout(function() {
		consultarFiltroCategorias();
		consultarFiltroUnidades();
		consultarFiltroEstados();
		consultarFiltroLineas();
		consultarFiltroUsuarios();
		promise.resolve();
  	},1500);	
	return promise.promise();
}
function consultarSalidas(id){
	$("#caja-cargando").show();
	$.ajax({
		"url": url_base+'Solicitudes_controller/consultarSalidas',
		"type": 'POST',
		"dataType": 'json',
		"data": {"solicitud_salida": id},
		success:function(datos){
			$("#caja-cargando").hide();
			$("#caja-solicitudes").hide();
			$("#caja-salidas").show();
			$("#btn-volver").click(function(){
				$("#caja-solicitudes").show();
				$("#caja-salidas").hide();
			});
			$("#nom_sol_user").text(datos.solicitud.nombre_persona+" "+datos.solicitud.apellido_persona);
			let fecha=String(datos.solicitud.fecha_solicitud);
			fecha=fecha.split(" ")[0];
			$("#fecha_sol").text(fecha);
			$("#est_sol").text(datos.solicitud.estado_solicitud);
			$("#total_sol").text(datos.solicitud.total_solicitud);
			if (datos.aviso) {
				armarTablaSalidas(datos.salidasProd,datos.salidasPers);
			}
		}
	}).fail(function(){
		$("#caja-cargando").hide();
		let titulo="Vaya!";
		let tipo="info";
		let texto="Ocurrio un error inseperado al visualizar solicitud";
		alertasTemporal(tipo,titulo,texto);
	});
}
function armarTablaSalidas(datos,datos2){
	var datostb=[];
	table_salida=$("#table_productos_solicitados").DataTable();
	var cantSalida=parseInt($("#cantidades #cant-salida").text(),10);
	var cantSalidaMax=parseInt($("#cantidades #cant-salida-maxima").text(),10);
	for (var i = 0; i < datos.length; i++) {
		if (datos[i].id_salida) {
			if (datos[i].tipo_producto==="Devolutivo") {
				var btn=`<center class='d-flex'><button class="btnVisProd Dev btn btn-sm pl-3 pr-3 btn-primary" data-toggle="modal" data-target="#modal-vis-devolutivo" value="`+datos[i].id_producto+`"><i class='fas fa-eye'></i></button></center>`;
			}else if (datos[i].tipo_producto==="Consumible"){
				var btn=`<center class='d-flex'><button class="btnVisProd Mat btn btn-sm pl-3 pr-3 btn-primary" data-toggle="modal" data-target="#modal-vis-material" value="`+datos[i].id_producto+`"><i class='fas fa-eye'></i></button></center>`
			}
				
			if (datos[i].imagen==null || datos[i].imagen==="") {
				datos[i].imagen='sinFoto.png';
			}
			if (datos[i].imagen==='sinFoto.png') {
				var dir="img";
			}else{
				var dir="files";
			}
			if (categorias[datos[i].categoria_producto]['estado']==='i') {
				var nom="La categoría "+categorias[datos[i].categoria_producto]['nombre']+" está inactiva";
			}else{
				var nom=categorias[datos[i].categoria_producto]['nombre'];
			}
			if (unidades[datos[i].unidad_medida]['estado']==='i') {
				var unid="La Unidad "+unidades[datos[i].unidad_medida]['nombre']+" está inactiva";
			}else{
				var unid=unidades[datos[i].unidad_medida]['nombre'];
			}
			imagenes[datos[i].id_producto]=datos[i].imagen;
			var userName;
			var userId;
			var userEmpresa;
			var userCargo;
			var userTelefono;
			var userExterno;
			for (var j =0; j < datos2.length; j++){
				if (datos[i].id_salida==datos2[j].id_salida){
					if (datos2[j].persona_exterior==null || datos2[j].persona_exterior==="") {
						userName=datos2[j].nombre_persona;
						userId=datos2[j].persona_usuario;
						userEmpresa="TecnoAcademia"
						userCargo=datos2[j].nombre_tipo;
						userTelefono=datos2[j].telefono_persona;
						userExterno="false";
					}else{
						userName=datos2[j].nombre_exterior;
						userId=datos2[j].persona_exterior;
						userEmpresa=datos2[j].empresa_exterior;
						userCargo=datos2[j].cargo_exterior;
						userTelefono=datos2[j].telefono_exterior;
						userExterno="true";
					}
					j=datos2.length;					
				}
			}
			
			datostb=[
					userName,//0
					datos[i].nombre_producto,//1
					datos[i].cantidad_salida,//2
					datos[i].tipo_salida,//3
					datos[i].estado_salida,//4
					datos[i].precio_producto,//5
					btn,//6
					datos[i].placa,//7
					datos[i].codigo_sena,//8
					datos[i].serial,//9
					"<img class='img-fluid' src='"+url_base+"assets/"+dir+"/"+datos[i].imagen+"' alt=''>",//10
					unid,//11
					nom,//12
					datos[i].nombre_linea,//13
					datos[i].descripcion_estado,//14
					datos[i].usuario_producto,//15
					datos[i].id_producto,//16
					datos[i].descripcion_producto,//17
					datos[i].unidad_medida,//18
					datos[i].categoria_producto,//19
					datos[i].linea_producto,//20
					datos[i].estado_producto,//21
					datos[i].tipo_producto,//22
					datos[i].nombre_persona+" "+datos[i].apellido_persona,//23
					userId,//24
					userExterno,//25
					userEmpresa,//26
					userCargo,//27
					userTelefono,//28
					datos[i].id_salida//29
				];
				var rowNode=table_salida.row.add(datostb).draw(false).node();
				$(rowNode).find("button.btnVisProd").click(formInput); 	
				// Módifico el valor de la tabla
				if (datos[i].tipo_salida==="Definitiva") {
					var val=parseInt( $("#table_productos_solicitados #precio_total").text(),10);
					val+=parseInt(datos[i].precio_producto,10);
					$("#table_productos_solicitados #precio_total").text(val);	
				}
				cantSalida+=1;
				cantSalidaMax-=1;
		}
	}
	$("#cantidades #cant-salida").text(cantSalida);
	$("#cantidades #cant-salida-maxima").text(cantSalidaMax);										
	
}
function filtroUsuarios(tabla,columna,id,nombre,node){
	filtrartabla(tabla,columna,id);		
	if (node.find("#usuario_filtrado").length) {
		node.find("#usuario_filtrado h3").html(nombre);
	}else{			
		node.append(`<div id="usuario_filtrado" style="width:auto;" class="order-1 ml-1 mr-1">
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

        node.find("#eliminar_fusuario").on('click', function() {
			filtrartabla(tabla,columna,'');                 
			node.find("#usuario_filtrado").remove();
		});         
    }     
}
function filtroUnidad(tabla,columna,nombre,node){
	filtrartabla(tabla,columna,nombre);
	if (node.find("#unidad_filtrada").length) {
		node.find("#unidad_filtrada h3").html(nombre);

	}else{
		node.append(`<div id="unidad_filtrada" style="width:auto;" class="order-2 ml-1 mr-1">
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

	  	node.find("#eliminar_funidad").on('click', function() {
	  		filtrartabla(tabla,columna,'');
	  		node.find("#unidad_filtrada").remove();
	  	});
	}
}
function filtroCategorias(tabla,columna,nombre,node){
	filtrartabla(tabla,columna,nombre);	
	if (node.find("#categoria_filtrada").length) {
		node.find("#categoria_filtrada h3").html(nombre);
	}else{			
		node.append(`<div id="categoria_filtrada" style="width:auto;" class="order-3 ml-1 mr-1">
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

        node.find("#eliminar_fcategoria").on('click', function() {
			filtrartabla(tabla,columna,'');                 
			node.find("#categoria_filtrada").remove();
		});         
    }     
}
function filtroLinea(tabla,columna,nombre,nombre2,node){
	filtrartabla(tabla,columna,nombre);         
	if (node.find("#linea_filtrada").length){             
		node.find("#linea_filtrada h3").html(nombre2);
	}else{
		node.append(`<div id="linea_filtrada" style="width:auto;" class="order-4 ml-1 mr-1">
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

	  	node.find("#eliminar_flinea").on('click', function() {
	  		filtrartabla(tabla,columna,'');
	  		node.find("#linea_filtrada").remove();
	  	});
	}
}
function filtroEstado(tabla,columna,nombre,node){
	filtrartabla(tabla,columna,nombre);
	if (node.find("#estado_filtrada").length) {
		node.find("#estado_filtrada h3").html(nombre);
	}else{
		node.append(`<div id="estado_filtrada" style="width:auto;" class="order-5 ml-1 mr-1">
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

	  	node.find("#eliminar_festado").on('click', function() {
	  		filtrartabla(tabla,columna,'');
	  		node.find("#estado_filtrada").remove();
	  	});
	}
}
function filtroEstadosSalida(tabla,columna,nombre,node){
	filtrartabla(tabla,columna,nombre);
	if (node.find("#estado_salida").length) {
		node.find("#estado_salida h3").html(nombre);

	}else{
		node.append(`<div id="estado_salida" style="width:auto;" class="order-2 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_festadoSalida" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	node.find("#eliminar_festadoSalida").on('click', function() {
	  		filtrartabla(tabla,columna,'');
	  		node.find("#estado_salida").remove();
	  	});
	}
}
function filtroTipoSalida(tabla,columna,nombre,node){
	filtrartabla(tabla,columna,nombre);
	if (node.find("#tipo_salida").length) {
		node.find("#tipo_salida h3").html(nombre);

	}else{
		node.append(`<div id="tipo_salida" style="width:auto;" class="order-2 ml-1 mr-1">
	        <div class="card">
	          	<div class="card-header p-1 d-flex align-items-center">
	        		<h3 class="card-title mr-1">`+nombre+`</h3>
	            	<div class="card-tools ml-1 mr-1">
	                  <div id="eliminar_ftipoSalida" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
	                  </div>
	                </div>
	          	</div>
	        </div>
	  	</div>`);

	  	node.find("#eliminar_ftipoSalida").on('click', function() {
	  		filtrartabla(tabla,columna,'');
	  		node.find("#tipo_salida").remove();
	  	});
	}
}
function consultarFiltroCategorias(){
	var selectVis=$("#categoria_vis_dev");	
	$.ajax({
		"url":$('#consult_cat').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){						
			if (datos.aviso) {	
				if (datos[0].nombre_categoria) {
					$.each(datos,function(){
						if (this.estado==='a') {
							$("#filtro2 .f_categoria").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">`+this.nombre_categoria+`</a>
											</li>`);
							selectVis.append(`<option value="`+this.id_categoria+`">`+this.nombre_categoria+`</option>`);
						}
						categorias[this.id_categoria]=new Array();	
						categorias[this.id_categoria]['nombre']=this.nombre_categoria;
						categorias[this.id_categoria]['estado']=this.estado;
					});
				}
			}
		}
	}); 
}
// No se usa hasta decidir si se puede registrar salidas a nombre de otro usuario
function consultarFiltroUsuarios(){
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
							usuarios[this.id_usuario]=new Array();	
							usuarios[this.id_usuario]['nombre']=this.nombre_persona+" "+this.apellido_persona;
							if (usuario["tipo"]==="INSTRUCTOR" && this.nombre_tipo==="INSTRUCTOR" || usuario["tipo"]==="ADMINISTRADOR") {
								if (usuario["tipo"]==="ADMINISTRADOR" && usuario['id']===this.id_usuario) {
									// $("#filtro2 .f_usuario").append(`<li class="dropdown-item p-0">
									// 					<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">Mis devolutivos</a>
									// 				</li>`);
									selectVis.append(`<option value=`+this.id_usuario+`>Mi devolutivo</option>`);
								}else if (usuario["tipo"]==="ADMINISTRADOR"){
									// $("#filtro2 .f_usuario").append(`<li class="dropdown-item p-0">
									// 					<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</a>
									// 				</li>`);
									selectVis.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
								}							
								if (usuario["tipo"]==="INSTRUCTOR" && this.nombre_tipo==="INSTRUCTOR") {
									if (usuario['id']===this.id_usuario) {
										// $("#filtro2 .f_usuario").append(`<li class="dropdown-item p-0">
										// 					<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">Mis devolutivos</a>
										// 				</li>`);
										selectVis.append(`<option value=`+this.id_usuario+`>Mi devolutivo</option>`);
									}									
								}
							}else{
								selectVis.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
							}
						}						
					});
				}
			}
		}
	});
}
function consultarFiltroUnidades(){
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
							$("#filtro2 .f_unidad").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">`+this.nombre_unidad+`</a>
											</li>`);
							selectVis.append(`<option value="`+this.id_unidad+`">`+this.nombre_unidad+`</option>`);
						}		
						unidades[this.id_unidad]=new Array();	
						unidades[this.id_unidad]['nombre']=this.nombre_unidad;
						unidades[this.id_unidad]['estado']=this.estado;
					});
				}
			}
		}
	});
}
function consultarFiltroLineas(){
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
								$("#filtro2 .f_linea").append(`<li class="dropdown-item p-0">
													<a href="#" class="dropdown-item w-100" data-linea="`+this.nombre_linea+`">Mi linea</a>
												</li>`);
							}else{
								$("#filtro2 .f_linea").append(`<li class="dropdown-item p-0">
													<a href="#" class="dropdown-item w-100" data-linea="`+this.nombre_linea+`">`+this.nombre_linea+`</a>
												</li>`);
							}
							selectVis.append(`<option value="`+this.id_linea+`">`+this.nombre_linea+`</option>`);
						}
						var id_lin=this.id_linea;
						var nom_lin=this.nombre_linea;
						lineas[id_lin]=nom_lin;
					});
				}
			}
		}
	});
}
function consultarFiltroEstados(){
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
							$("#filtro2 .f_estado").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">`+this.descripcion_estado+`</a>
											</li>`);
							selectVis.append(`<option value="`+this.id_estado+`">`+this.descripcion_estado+`</option>`);
						}						
						var id_est=this.id_estado;
						var nom_est=this.descripcion_estado;
						estados[id_est]=nom_est;
					});					
				}
			}
		}
	});
}
function formInput(){	
	table_salida=$("#table_productos_solicitados").DataTable();
	if ($(this).hasClass("Mat")) {
		var idProducto= $(this).val();
		tr=dataFila(table_salida,idProducto,16);	
		// Llenar inputs de modal editar
		$("#nombre_vis_mat").val(table_salida.row(tr).data()[1])
		$("#cant_vis_mat").val(table_salida.row(tr).data()[2]);
		$("#precio_vis_mat").val(table_salida.row(tr).data()[5]);
		$("#linea_vis_mat").val(table_salida.row(tr).data()[13]);
		$("#descripcion_vis_mat").val(table_salida.row(tr).data()[17]);
		$("#categoria_vis_mat").val(table_salida.row(tr).data()[12]);
		$("#unidad_vis_mat").val(table_salida.row(tr).data()[11]);
		$("#usuario_vis_mat").val(table_salida.row(tr).data()[23]);
		if (imagenes[idProducto]==="sinFoto.png") {
			$("#imagen_vis_mat").attr("src",url_base+"assets/img/sinFoto.png");
		}else{
			$("#imagen_vis_mat").attr("src",url_base+"assets/files/"+imagenes[idProducto]);
			$.get($("#imagen_vis_mat").attr("src")).fail(function(){
				$("#imagen_vis_mat").attr("src",url_base+"assets/img/sinFoto.png");
				imagenes[idProducto]="sinFoto.png";
			});
		}		
	}else if ($(this).hasClass("Dev")){
		var idProducto= $(this).val();
		tr=dataFila(table_salida,idProducto,16);	
		$("#usuario_vis_dev").val(table_salida.row(tr).data()[15]);	
		$("#nombre_vis_dev").val(table_salida.row(tr).data()[1]);
		$("#placa_vis_dev").val(table_salida.row(tr).data()[7]);
		$("#csena_vis_dev").val(table_salida.row(tr).data()[8]);
		$("#serial_vis_dev").val(table_salida.row(tr).data()[9]);
		$("#descripcion_vis_dev").val(table_salida.row(tr).data()[17]);
		$("#precio_vis_dev").val(table_salida.row(tr).data()[5]);
		$("#linea_vis_dev").val(table_salida.row(tr).data()[20]);
		$("#estado_vis_dev").val(table_salida.row(tr).data()[21]);
		$("#unidad_vis_dev").val(table_salida.row(tr).data()[18]);
		$("#categoria_vis_dev").val(table_salida.row(tr).data()[19]);
		if (imagenes[idProducto]==="sinFoto.png") {			
			$("#file_img_vis").attr("src",url_base+"assets/img/sinFoto.png");
			$("#label_imagen_vis_dev").find("span").html("Imagen");			
		}else{
			$("#file_img_vis").attr("src",url_base+"assets/files/"+imagenes[idProducto]);
			$("#label_imagen_vis_dev").find("span").html(imagenes[idProducto]);
			$.get($("#file_img_vis").attr("src")).fail(function(){
				$("#file_img_vis").attr("src",url_base+"assets/img/sinFoto.png");
				imagenes[idProducto]="sinFoto.png";
				$("#label_imagen_vis_dev").find("span").html("Imagen");
			});
		}
	}
}
function agregarFunciones(){
	$("#filtro2 .f_usuario a").on("click", function() {
		filtroUsuarios(table_salida,9,$(this).attr('data-usuario'),$(this).text(),$("#cajas-filtro"));
    });
	$("#filtro2 .f_unidad a").on("click", function() {
		filtroUnidad(table_salida,5,$(this).text(),$("#cajas-filtro"));		
	});
	$("#filtro2 .f_categoria a").on("click", function() {
		filtroCategorias(table_salida,6,$(this).text(),$("#cajas-filtro"));
    });
         
    $("#filtro2 .f_linea a").on("click", function() {
		filtroLinea(table_salida,7,$(this).attr('data-linea'),$(this).text(),$("#cajas-filtro"));
	});
	$("#filtro2 .f_estado a").on("click", function() {
		filtroEstado(table_salida,8,$(this).text(),$("#cajas-filtro"));
	});
	$("#filtro2 .f_usuario a").on("click", function() {
		filtroUsuarios(table_salida,0,$(this).attr('data-usuario'),$(this).text(),$("#cajas-filtro3"));
    });
	$("#filtro2 .f_unidad a").on("click", function() {
		filtroUnidad(table_salida,7,$(this).text(),$("#cajas-filtro3"));		
	});
	$("#filtro2 .f_categoria a").on("click", function() {
		filtroCategorias(table_salida,4,$(this).text(),$("#cajas-filtro3"));
    });
         
    $("#filtro2 .f_linea a").on("click", function() {
		filtroLinea(table_salida,5,$(this).attr('data-linea'),$(this).text(),$("#cajas-filtro3"));
	});
	$("#filtro2 .f_estadoSalida a").on("click", function() {
		filtroLinea(table_salida,4,$(this).attr('data-estado'),$(this).text(),$("#cajas-filtro2"));
	});
	$("#filtro2 .f_tipoSalida a").on("click", function() {
		filtroLinea(table_salida,3,$(this).attr('data-salida'),$(this).text(),$("#cajas-filtro2"));
	});
}
function limpiarCajaSalidas(){
	table_salida.clear().draw();
	$("#nom_sol_user").text("");
	$("#fecha_sol").text("");
	$("#est_sol").text("");
	$("#total_sol").text("");
}