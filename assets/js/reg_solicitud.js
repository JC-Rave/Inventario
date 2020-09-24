var table_salida;
var id_sol1;
var id_sol2;
var tableDevolutivo;
var tableMateriales;
var imagenes;
var url_base;
var categorias;
var unidades;
var lineas;
var estados;
var usuarios;
var usuario;
var idProducto;
var tr;
var edit;
$(document).ready(iniciar);
function iniciar(){
	categorias=new Array();
	usuarios=new Array();
	unidades=new Array();
	lineas=new Array();
	estados=new Array();
	usuario=new Array();
	imagenes=new Array();
	edit=false;
	usuario['id']=$("#id_usuario").val();
	usuario['nombre']=$("#nombre_usuario").val();
	usuario['tipo']=$("#tipo_usuario").val();
	usuario['linea']=$("#linea_usuario").val();
	url_base=$("#base_url_dev").val();
	id_sol1=$("#regurl-edit").val();
	id_sol2=$("#regurl-edit2").val();
	$("#usuario_solicitud").val(usuario['nombre']);
	$("#modal-prep-producto #t_salida").val("");
	$("#modal-prep-producto #accionBuscar").val("false");
	$("#volverSolicitud").hide();
	$("#editarSolicitud").hide();
	$("#editarSolicitud").click(editarSolicitud);
	$("#volverSolicitud").click(volverSolicitud);
	$("#crearSolicitud").click(agregarSolicitud);
	$("#cancelarSolicitud").click(cancelarSolicitud);
	$("#terminarPrestamos").click(TerminarPrestamos);
	$("#modal-prep-producto #documento_exterior").on('keyup keypress',function(event) {
		$("#modal-prep-producto #caja-texto").hide();
		if ($("#modal-prep-producto #documento_exterior").hasClass("is-invalid")) {
			$("#modal-prep-producto #documento_exterior").removeClass("is-invalid");
		}
		$("#div-oculto").hide();
		$("#div-oculto existExterna").val("");
		$("#div-oculto :input").attr("readonly",false);
		$("#div-oculto :input").attr("required",false);
		$("#div-oculto :input").val("");
		$("#modal-prep-producto #accionBuscar").val("false");
	});
	$("#modal-prep-producto #est_salida").click(function(e){
		e.preventDefault();
	});	
	// Se Crea, asigna y personaliza datatables 
	dataTables();	
	table_salida=$("#table_productos_solicitados").DataTable();
	tableDevolutivo=$("#table-devolutivos").DataTable();
	tableMateriales=$("#table-materiales").DataTable();
	
	// Ocultamos columnas que no necesitamos visualizar en las tablas
	visualizarColumnaEntabla(tableDevolutivo,[1,2,9,11,12,13,14,15,16,17,18],false);
	visualizarColumnaEntabla(tableMateriales,[0,9,10,11,12],false);
	visualizarColumnaEntabla(table_salida,[7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29],false);

	$.when(agregarFiltro()).then(function(e){
		if (String(id_sol1).length==0 && String(id_sol2).length>0) {
			volverSolicitud();
		}else if (String(id_sol1).length>0) {
			if (Number.isInteger(parseInt(id_sol1))) {
				edit=true;
				prepararEdicionSolicitud();
			}else{
				volverSolicitud();
			}
		}else{
			consultarSolicitud();
		}
		agregarFunciones();	
		window.setTimeout(function() {
			if ($("#estado_solicitud")!=="En prestamo") {
				consultarDevolutivos();
				consultarMateriales();
			}
			window.setTimeout(function() {
				$("#caja-cargando").hide();
	    	},1300);
	    },1300);
	});	
	$("#form-add-salida").submit(function (e) {
		e.preventDefault();
		if ($("#modal-prep-producto #accionBuscar").val()==="true") {
			var cant=$("#modal-prep-producto #cant_p").val();
			if (cant==0) {
				alertasTemporal('info','Vaya!','No puedes agregar hasta elegir almenos un producto');
			}else{
				if ($("#modal-prep-producto #t_salida").val()!=="") {
					if ($("#modal-prep-producto #existExterna").val()==="false") {
						agregarPersonaExterna();
					}
					$('#modal-prep-producto').modal('toggle');
					llenarTablaSalida();
				}
				
			}
		}else{
			alertasTemporal('info','Vaya!','No puedes continuar hasta buscar la persona');
		}
	});
	$("#buscar-persona-externo").click(consultarPersonaExterna);
	$("#materiales-tab").click(function(){
		$("#table-materiales th:eq(2)").attr("style","width:30px !important;");
	});
	$("#devolutivos_tab").click(function(){
		$("#table-devolutivos th:eq(1)").attr("style","width:30px !important;");
	});
	
	$("#modal-prep-producto #t_salida").change(function(){
		if ($("#modal-prep-producto #t_salida").val()==="Definitiva") {
			$("#modal-prep-producto #est_salida").val("No retorna");
		}else if ($("#modal-prep-producto #t_salida").val()==="Prestamo"){
			$("#modal-prep-producto #est_salida").val("En prestamo");
		}
	});

}
function volverSolicitud(){
	$(location).attr('href',url_base+"/Vistas/adm_solicitudes");
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
	  				"data":{"id_solicitud":$("#inN_solicitud").val()},
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
function consultarSolicitud(){
	$.ajax({
		url: url_base+'Solicitudes_controller/consultarSolicitud',
		type: 'POST',
		dataType: 'json',
		data: {"usuario_solicitud":usuario['id']},
		success:function(datos){
			if (datos.aviso) {
				$("#n_solicitud").text(datos.n_solicitudes);
				if (datos.estado_solicitud==="Cancelado") {
					volverSolicitud();
				}
				if (datos.estado_solicitud==="Pausado") {
					datos.estado_solicitud="En proceso";
				}
				$("#estado_solicitud").val(datos.estado_solicitud);
				if (datos.estado_solicitud=="En proceso") {
					let btn=`<div class="mb-3 col-8 text-right"><button id="pausar_solicitud" class="btn btn-info p-2">Pausar<i class="fas fa-pause ml-2"></i></button></div>`;
					$("#header_tablaSalidas").append(btn);
					$("#header_tablaSalidas #pausar_solicitud").click(pausarSolicitud);	
				}
				let fecha=String(datos.fecha_solicitud);
				fecha=fecha.split(" ")[0];
				$("#fecha_creacion_solicitud").text(fecha);
				$("#inN_solicitud").val(datos.id_solicitud);
			}else{
				volverSolicitud();
			}
		}
	}).fail(function() {
		volverSolicitud();
	});
}
function prepararEdicionSolicitud(){
	if (edit) {
		$("#volverSolicitud").show();
		$("#editarSolicitud").show();
		$("#cancelarSolicitud").hide();
		$("#crearSolicitud").hide();
		consultarSalidas();
	}
}

function consultarSalidas(){
	$.ajax({
		"url": url_base+'Solicitudes_controller/consultarSalidas',
		"type": 'POST',
		"dataType": 'json',
		"data": {"solicitud_salida": id_sol1},
		success:function(datos){
			$("#n_solicitud").text(datos.n_solicitud);
			if (datos.solicitud.estado_solicitud==="Cancelado" || datos.solicitud.estado_solicitud==="Terminado") {
				volverSolicitud();
			}
			if (datos.solicitud.estado_solicitud==="En proceso" || datos.solicitud.estado_solicitud==="Pausado" ){
				if (datos.solicitud.estado_solicitud==="En proceso") {
					var btn=`<div class="mb-3 col-8 text-right"><button id="pausar_solicitud" class="btn btn-info p-2">Pausar<i class="fas fa-pause ml-2"></i></button></div>`;
				}else{
					var btn=`<div class="mb-3 col-8 text-right"><button id="pausar_solicitud" class="btn btn-info p-2">Guardar y Pausar<i class="fas fa-pause ml-2"></i></button></div>`;
				}
				$("#header_tablaSalidas").append(btn);
				$("#header_tablaSalidas #pausar_solicitud").click(pausarSolicitud);	
				datos.solicitud.estado_solicitud="En proceso";
			}
			if (datos.solicitud.estado_solicitud==="En prestamo") {
				$("#caja_productos").hide();
				$("#editarSolicitud").hide();
				$("#terminarPrestamos").show();
			}
			$("#estado_solicitud").val(datos.solicitud.estado_solicitud);
			let fecha=String(datos.solicitud.fecha_solicitud);
			fecha=fecha.split(" ")[0];
			$("#fecha_creacion_solicitud").text(fecha);
			$("#inN_solicitud").val(datos.solicitud.id_solicitud);
			if (datos.aviso) {
				armarTablaSalidas(datos.salidasProd,datos.salidasPers,datos.solicitud.estado_solicitud);
			}
		}
	});
}
// Funcion para crear mensajes emergentes con limite de tiempo
function alertasTemporal(tipo,titulo,texto){
	Swal.fire({
	  title:titulo,
	  text:texto,
	  icon:tipo,
	  showConfirmButton:false,
	  timer:1700
	});			
}
function alertasBoton(tipo,titulo,texto){
	Swal.fire({
	  title:titulo,
	  text:texto,
	  icon:tipo,
	  showConfirmButton:true,
	});			
}
// Funcion encargada para crear cada una de las filas dentro de la tabla que se vinculará a la solicitud
// Está adaptada para poder recibir un material consumible o un devolutivo
function llenarTablaSalida(){
	var cant=$("#modal-prep-producto #cant_p").val();
	table_salida= $("#table_productos_solicitados").DataTable();
	var cantSalida=parseInt($("#cantidades #cant-salida").text(),10);
	var cantSalidaMax=parseInt($("#cantidades #cant-salida-maxima").text(),10);
	if ($("#modal-prep-producto #t_productos_salida").val()==="Devolutivo") {
		// Al ser un devolutivo pueden ser varios a la vez, lo cual se colocan en un cicle para multiples inserciones en la tabla.
		// Transformo todos los inputs checkeados para luego contar cuantos son y obtener los identificadores del producto.
		var arrayChecked=$("#modal-prep-producto #tab-cant-devs").find('.check_data:checked').toArray();
		for (var i = 0; i <arrayChecked.length; i++) {
			if (cantSalidaMax>0) {
				let id= String($(arrayChecked[i]).attr('id'));
				// Transformo el id del input en dos array. El id está separado por guión bajo ( _ ) donde la posición 1 tendrá el id
				// ej: devCheck_######
				id=id.split('_');
				// Obtengo la fila verdadera dentro de la tabla
				tr=dataFila(tableDevolutivo,parseInt(id[1],10),12);
				// Datos que recibirá una sola fila dentro de la tabla de solicitud
				let datostb=[
					$("#div-oculto #nombre_exterior").val(),//0
					tableDevolutivo.row(tr).data()[4],//1
					"1",//2
					$("#modal-prep-producto #t_salida").val(),//3
					$("#modal-prep-producto #est_salida").val(),//4
					tableDevolutivo.row(tr).data()[11],//5
					`<button class="btnCancelProd btnDev btn btn-sm pl-3 pr-3 btn-danger" data-toggle="modal" data-target="" value="`+tableDevolutivo.row(tr).data()[12]+`"><i class="fas fa-minus-circle"></i></button>`,//6
					tableDevolutivo.row(tr).data()[0],//7
					tableDevolutivo.row(tr).data()[1],//8
					tableDevolutivo.row(tr).data()[2],//9
					tableDevolutivo.row(tr).data()[3],//10
					tableDevolutivo.row(tr).data()[5],//11
					tableDevolutivo.row(tr).data()[6],//12
					tableDevolutivo.row(tr).data()[7],//13
					tableDevolutivo.row(tr).data()[8],//14
					tableDevolutivo.row(tr).data()[9],//15
					tableDevolutivo.row(tr).data()[12],//16
					tableDevolutivo.row(tr).data()[13],//17
					tableDevolutivo.row(tr).data()[14],//18
					tableDevolutivo.row(tr).data()[15],//19
					tableDevolutivo.row(tr).data()[16],//20
					tableDevolutivo.row(tr).data()[17],//21
					tableDevolutivo.row(tr).data()[18],//22
					usuarios[tableDevolutivo.row(tr).data()[9]]['nombre'],//23
					$("#modal-prep-producto #documento_exterior").val(),//24
					$("#div-oculto #personaExterna").val(),//25
					$("#div-oculto #empresa_exterior").val(),//26
					$("#div-oculto #cargo_exterior").val(),//27
					$("#div-oculto #telefono_exterior").val(),//28
					""//29
				];
				var rownode=table_salida.row.add(datostb).draw(false).node();
				// Mpdifico el valor de la tabla
				if ($("#modal-prep-producto #t_salida").val()==="Definitiva") {
					var val=parseInt( $("#table_productos_solicitados #precio_total").text(),10);
					val+=parseInt(tableDevolutivo.row(tr).data()[11],10);
					$("#table_productos_solicitados #precio_total").text(val);
				}
				cantSalida+=1;
				cantSalidaMax-=1;
				tableDevolutivo.row(tr).remove().draw();
				$(rownode).find(".btnCancelProd").click(borrarProducto);
			}else{
				let tipo="info";
				let titulo="Vaya!";
				let texto="No puedes agregar más devolutivos a la solicitud";
				alertasTemporal(tipo,titulo,texto);
				i=arrayChecked.length;
			}
		}
	}else if ($("#modal-prep-producto #t_productos_salida").val()==="Consumible") {
		let id=$("#modal-prep-producto #id_productos_salida").val()
		tableMateriales=$("#table-materiales").DataTable();
		tr=dataFila(tableMateriales,parseInt(id,10),11);
		// Confirmo si en la tabla de solicitud tiene ya el mismo consumible
		// De ser así, pregunta si también tiene a la misma persona encargada
		// De ser así, editará la cantidad alamacenada en la tabla materiales y en la tabla de la solicitud
		// De lo contrario la agregará como una nueva fila
		if (buscarEnColumna(table_salida,tableMateriales.row(tr).data()[11],16)===false && $("#modal-prep-producto #documento_exterior").val()===table_salida.row(dataFila(table_salida,tableMateriales.row(tr).data()[11],16)).data()[24] && $("#modal-prep-producto #t_salida").val()===table_salida.row(dataFila(table_salida,tableMateriales.row(tr).data()[11],16)).data()[3]){
			tr2=dataFila(table_salida,tableMateriales.row(tr).data()[11],16);
			let tot=parseInt(table_salida.cell(tr2,2).data(),10)+parseInt($("#modal-prep-producto #cant_p").val(),10);
			table_salida.cell(tr2,2).data(tot).draw();
			let val=parseInt( $("#table_productos_solicitados #precio_total").text(),10);
			val+=(parseInt(tableMateriales.row(tr).data()[10],10)*parseInt($("#modal-prep-producto #cant_p").val(),10));
			$("#table_productos_solicitados #precio_total").text(val);
			$(".btnCancelProd").on('click',borrarProducto);
			var int1=parseInt(tableMateriales.row(tr).data()[6],10);
			var int2=parseInt($("#modal-prep-producto #cant_p").val(),10);
			if (int1===int2) {
				tableMateriales.row(tr).remove().draw();
			}else if (int1>int2){			
				int1-=int2;
				tableMateriales.cell(tr,6).data(int1).draw();
			}
		}else{
			if (cantSalidaMax>0) {
				var datostb=[
					$("#div-oculto #nombre_exterior").val(),//0
					tableMateriales.row(tr).data()[2],//1
					$("#modal-prep-producto #cant_p").val(),//3
					$("#modal-prep-producto #t_salida").val(),//4
					$("#modal-prep-producto #est_salida").val(),//5
					tableMateriales.row(tr).data()[10],//6
					`<button class="btnCancelProd btnMat btn btn-sm pl-3 pr-3 btn-danger" data-toggle="modal" data-target="" value="`+tableMateriales.row(tr).data()[11]+`"><i class="fas fa-minus-circle"></i></button>`,//6
					"",//7
					"",//8
					"",//9
					tableMateriales.row(tr).data()[3],//10
					tableMateriales.row(tr).data()[7],//11
					tableMateriales.row(tr).data()[4],//12
					tableMateriales.row(tr).data()[5],//13
					tableMateriales.row(tr).data()[3],//14
					tableMateriales.row(tr).data()[0],//15
					tableMateriales.row(tr).data()[11],//16
					tableMateriales.row(tr).data()[9],//17
					"",//18
					"",//19
					"",//20
					"",//21
					tableMateriales.row(tr).data()[12],//22
					tableMateriales.row(tr).data()[1],//23
					$("#modal-prep-producto #documento_exterior").val(),//24
					$("#div-oculto #personaExterna").val(),//25
					$("#div-oculto #empresa_exterior").val(),//26
					$("#div-oculto #cargo_exterior").val(),//27
					$("#div-oculto #telefono_exterior").val(),//28
					""//29
				];
				cantSalida++;
				cantSalidaMax--;
				table_salida.row.add(datostb).draw();
				if ($("#modal-prep-producto #t_salida").val()==="Definitiva") {
					let val=parseInt( $("#table_productos_solicitados #precio_total").text(),10);
					val+=(parseInt(tableMateriales.row(tr).data()[10],10)*parseInt($("#modal-prep-producto #cant_p").val(),10));
					$("#table_productos_solicitados #precio_total").text(val);	
				}
				$(".btnCancelProd").on('click',borrarProducto);
				var int1=parseInt(tableMateriales.row(tr).data()[6],10);
				var int2=parseInt($("#modal-prep-producto #cant_p").val(),10);
				if (int1===int2) {
					tableMateriales.row(tr).remove().draw();
				}else if (int1>int2){			
					int1-=int2;
					tableMateriales.cell(tr,6).data(int1).draw();
				}	
			}else{
				let tipo="info";
				let titulo="Vaya!";
				let texto="No puedes agregar más consumibles a la solicitud";
				alertasTemporal(tipo,titulo,texto);
				i=arrayChecked.length;
			}
		}
		
	}
	$("#cantidades #cant-salida").text(cantSalida);
	$("#cantidades #cant-salida-maxima").text(cantSalidaMax);
}
// Función para borrar una de las filas dentro de la tabla de solicitud y la devolvemos a su tabla de origen
function borrarProducto(){
	var cantSalida=parseInt($("#cantidades #cant-salida").text(),10);
	var cantSalidaMax=parseInt($("#cantidades #cant-salida-maxima").text(),10);
	idProducto=$(this).val();
	tr=dataFila(table_salida,idProducto,16);		
	const swalWithBootstrapButtons = Swal.mixin({
	  customClass: {
	    confirmButton: 'btn btn-success',
	    cancelButton: 'btn btn-danger'
	  },
	  buttonsStyling: false
	});
	swalWithBootstrapButtons.fire({
		  title: 'Estas seguro?',
		  text: "El producto será retirado de la tabla!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonText: 'Si, retírelo!',
		  cancelButtonText: 'No, lo cancelo!',
		  reverseButtons: true
		}).then((result) => {
		  	if (result.value) {
		  		cantSalida--;
				cantSalidaMax++;
		  		// tableDevolutivo.row.add(datostb);
			  	if ($(this).hasClass("btnDev")) {
			  		var datostb=[
						table_salida.row(tr).data()[7],//0
						table_salida.row(tr).data()[8],//1
						table_salida.row(tr).data()[9],//2
						table_salida.row(tr).data()[10],//3
						table_salida.row(tr).data()[1],//4
						table_salida.row(tr).data()[11],//5
						table_salida.row(tr).data()[12],//6
						table_salida.row(tr).data()[13],//7
						table_salida.row(tr).data()[14],//8
						table_salida.row(tr).data()[15],//9
						`<center class="d-flex justify-content-center"><button class="btnVisDev btn btn-sm pl-3 pr-3 btn-primary" data-toggle="modal" data-target="#modal-vis-devolutivo" value="`+table_salida.row(tr).data()[16]+`"><i class='fas fa-eye'></i></button><button class="btnAddDev btn btn-sm pl-3 pr-3 btn-success" data-toggle="modal" data-target="#modal-prep-producto" value="`+table_salida.row(tr).data()[16]+`"><i class='fas fa-plus'></i></button></center>`,//10
						table_salida.row(tr).data()[5],//11
						table_salida.row(tr).data()[16],//12
						table_salida.row(tr).data()[17],//13
						table_salida.row(tr).data()[18],//14
						table_salida.row(tr).data()[19],//15
						table_salida.row(tr).data()[20],//16
						table_salida.row(tr).data()[21],//17
						table_salida.row(tr).data()[22],//18
					];
			  		var rowNode=tableDevolutivo.row.add(datostb).draw().node();
			  		$(rowNode).find('td:eq(0)').addClass("placa-dev");
					$(rowNode).find('td:eq(1)').addClass("img-dev p-0");
					$(rowNode).find('td:eq(2)').addClass("nomp-dev");
					$(rowNode).find('td:eq(3)').addClass("nomu-dev");
					$(rowNode).find('td:eq(4)').addClass("nomc-dev");
					$(rowNode).find('td:eq(5)').addClass("noml-dev");
					$(rowNode).find('td:eq(6)').addClass("desce-dev");
					$(rowNode).find('td:eq(7)').addClass("btns-dev text-center");
					$(rowNode).find(".btnAddDev").on('click',prepararProducto); 	
					$(rowNode).find(".btnVisDev").on('click',formInput); 	
			  	}else if ($(this).hasClass("btnMat")) {
			  		var datostb=[
						table_salida.row(tr).data()[15],//0
						table_salida.row(tr).data()[0],//1
						table_salida.row(tr).data()[1],//2
						table_salida.row(tr).data()[10],//3
						table_salida.row(tr).data()[12],//4
						table_salida.row(tr).data()[13],//5
						table_salida.row(tr).data()[2],//6
						table_salida.row(tr).data()[11],//7
						`<center class="d-flex justify-content-center"><button class="btnVisMat btn btn-sm pl-3 pr-3 btn-primary" data-toggle="modal" data-target="#modal-vis-material" value="`+table_salida.row(tr).data()[16]+`"><i class='fas fa-eye'></i></button><button class="btnAddMat btn btn-sm pl-3 pr-3 btn-success" data-toggle="modal" data-target="#modal-prep-producto" value="`+table_salida.row(tr).data()[16]+`"><i class='fas fa-plus'></i></button></center>`,//8
						table_salida.row(tr).data()[17],//9
						table_salida.row(tr).data()[5],//10
						table_salida.row(tr).data()[16],//11
						table_salida.row(tr).data()[22]//12
					];
			  		if (buscarEnColumna(tableMateriales,table_salida.row(tr).data()[16],11)===false) {
			  				tr2=dataFila(tableMateriales,table_salida.row(tr).data()[16],11);
			  			let tot=parseInt(tableMateriales.row(tr2).data()[6],10)+parseInt(table_salida.row(tr).data()[2],10)
			  			tableMateriales.cell(tr2,6).data(tot).draw();
			  		}else{
			  			var rowNode=tableMateriales.row.add(datostb).draw().node();
			  			$(rowNode).find('td:eq(0)').addClass("encargado-mat text-center");
						$(rowNode).find('td:eq(1)').addClass("nomp-mat pt-1 pb-1");
						$(rowNode).find('td:eq(2)').addClass("img-mat p-0");
						$(rowNode).find('td:eq(3)').addClass("nomc-mat text-center");
						$(rowNode).find('td:eq(4)').addClass("noml-mat text-center");
						$(rowNode).find('td:eq(5)').addClass("cant-mat text-center");
						$(rowNode).find('td:eq(6)').addClass("nomu-mat text-center");
						$(rowNode).find('td:eq(7)').addClass("btns-mat text-center");
						$(rowNode).find(".btnAddMat").on('click',prepararProducto); 	
						$(rowNode).find(".btnVisMat").on('click',formInput); 	
				  	}
			  	}
			  	if (table_salida.row(tr).data()[3]==="Definitiva") {
			  		let val=parseInt( $("#table_productos_solicitados #precio_total").text(),10);
					val-=(parseInt(table_salida.row(tr).data()[5],10)*parseInt(table_salida.row(tr).data()[2],10));
					$("#table_productos_solicitados #precio_total").text(val);
			  	}
		  		table_salida.row(tr).remove().draw();
		  		$("#cantidades #cant-salida").text(cantSalida);
				$("#cantidades #cant-salida-maxima").text(cantSalidaMax);
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
function dataTables(){
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
	    "fixedColumns":{
	    	"heightMatch":'none'
	    },
	    "dom":'<"row"<"col-sm-12 col-md-7"<"#filtros3.row"<"#filtro3.w-auto mr-3">>><"col-sm-12 col-md-5"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row"<"col-sm-12 col-md-8"i><"col-sm-12 col-md-4 d-flex align-items-center justify-content-center"p>>',
	    "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
	    "lengthChange": false
	});
	
}

function consultarDevolutivos(){
	$.ajax({
		"url":$('#consult_dev').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){
			if (datos.aviso) {	
				if (datos[0].id_producto) {
					armarTablaDevolutivos(datos);
				}else{
				  	Swal.fire({
					  title:'Vaya!',
					  text:'No se puede consultar',
					  icon:'info',
					  showConfirmButton:false,
					  timer:1500
					});			
				}
			}else{
				filtroUsuarios(tableDevolutivo,9,usuario['id'],"Mis devolutivos",$("#cajas-filtro"));
			}
		}
	});	
}
function armarTablaDevolutivos(datos){	
	var datostb=[];
	tableDevolutivo=$("#table-devolutivos").DataTable();
	$.each(datos, function () {	
		if (this.id_producto) {	
			var btn;
			btn=`<center class="d-flex justify-content-center"><button class="btnVisDev btn btn-sm pl-3 pr-3 btn-primary" data-toggle="modal" data-target="#modal-vis-devolutivo" value="`+this.id_producto+`"><i class='fas fa-eye'></i></button><button class="btnAddDev btn btn-sm pl-3 pr-3 btn-success" data-toggle="modal" data-target="#modal-prep-producto" value="`+this.id_producto+`"><i class='fas fa-plus'></i></button></center>`;
			if (this.imagen==null || this.imagen==="") {
				this.imagen='sinFoto.png';
			}
			if (this.imagen==='sinFoto.png') {
				var dir="img";
			}else{
				var dir="files";
			}
			if (categorias[this.categoria_producto]['estado']==='i') {
				var nom="La categoría "+categorias[this.categoria_producto]['nombre']+" está inactiva";
			}else{
				var nom=categorias[this.categoria_producto]['nombre'];
			}
			if (unidades[this.unidad_medida]['estado']==='i') {
				var unid="La Unidad "+unidades[this.unidad_medida]['nombre']+" está inactiva";
			}else{
				var unid=unidades[this.unidad_medida]['nombre'];
			}
			imagenes[this.id_producto]=this.imagen;
			datostb=[
					this.placa,//0
					this.codigo_sena,//1
					this.serial,//2
					"<img class='img-fluid' src='"+url_base+"assets/"+dir+"/"+this.imagen+"' alt=''>",//3
					this.nombre_producto,//4
					unid,//5
					nom,//6
					this.nombre_linea,//7
					this.descripcion_estado,//8
					this.usuario_producto,//9
					btn,//10
					this.precio_producto,//11
					this.id_producto,//12
					this.descripcion_producto,//13
					this.unidad_medida,//14
					this.categoria_producto,//15
					this.linea_producto,//16
					this.estado_producto,//17
					this.tipo_producto//18
				];		
			if (usuario["tipo"]==="INSTRUCTOR" && usuario["id"]===this.usuario_producto || usuario["tipo"]==="ADMINISTRADOR") {
				var rowNode=tableDevolutivo.row.add(datostb).draw().node();
				$(rowNode).find('td:eq(7)').addClass("btns-dev text-center");
				// Borde de la fila en DataTable
				$(rowNode).find(".btnAddDev").on('click',prepararProducto); 	
				$(rowNode).find(".btnVisDev").on('click',formInput); 	
			}
			
		}										
	});
	filtroUsuarios(tableDevolutivo,9,usuario['id'],"Mis devolutivos",$("#cajas-filtro"));
}
function armarTablaSalidas(datos,datos2,dato3){
	var datostb=[];
	table_salida=$("#table_productos_solicitados").DataTable();
	var cantSalida=parseInt($("#cantidades #cant-salida").text(),10);
	var cantSalidaMax=parseInt($("#cantidades #cant-salida-maxima").text(),10);
	for (var i = 0; i < datos.length; i++) {
		if (datos[i].id_salida) {
			if (dato3==="En prestamo") {
				if (datos[i].estado_salida==="En prestamo") {
					var btn=`<center class='d-flex'><button class="btnRetorSal btn btn-sm pl-3 pr-3 btn-success" data-toggle="modal" data-target="" value="`+datos[i].id_salida+`"><i class="fas fa-check"></i></button></center>`;	
				}else{
					var btn=``;
				}
			}else{
				if (datos[i].tipo_producto==="Devolutivo") {
					var btn=`<button class="btnCancelProd btnDev btn btn-sm pl-3 pr-3 btn-danger" data-toggle="modal" data-target="" value="`+datos[i].id_producto+`"><i class="fas fa-minus-circle"></i></button>`;
				}else{
					var btn=`<button class="btnCancelProd btnMat btn btn-sm pl-3 pr-3 btn-danger" data-toggle="modal" data-target="" value="`+datos[i].id_producto+`"><i class="fas fa-minus-circle"></i></button>`
				}
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
					usuarios[datos[i].usuario_producto]['nombre'],//23
					userId,//24
					userExterno,//25
					userEmpresa,//26
					userCargo,//27
					userTelefono,//28
					datos[i].id_salida//29
				];
				var rownode=table_salida.row.add(datostb).draw(false).node();
				// Módifico el valor de la tabla
				if (datos[i].tipo_salida==="Definitiva") {
					var val=parseInt( $("#table_productos_solicitados #precio_total").text(),10);
					val+=parseInt(datos[i].precio_producto,10);
					$("#table_productos_solicitados #precio_total").text(val);	
				}
				cantSalida+=1;
				cantSalidaMax-=1;
				tableDevolutivo.row(tr).remove().draw();
				$(rownode).find(".btnCancelProd").click(borrarProducto);
				if (dato3==="En prestamo") {
					$(rownode).find(".btnRetorSal").click(terminarPrestamo);
				}
		}
	}
	$("#cantidades #cant-salida").text(cantSalida);
	$("#cantidades #cant-salida-maxima").text(cantSalidaMax);										
	
}
function armarTablaMateriales(datos){	
	var datostb=[];
	tableMateriales=$("#table-materiales").DataTable();
	$.each(datos, function () {	
		if (this.id_producto) {	
			var btn;
			btn=`<center class="d-flex justify-content-center"><button class="btnVisMat btn btn-sm pl-3 pr-3 btn-primary" data-toggle="modal" data-target="#modal-vis-material" value="`+this.id_producto+`"><i class='fas fa-eye'></i></button><button class="btnAddMat btn btn-sm pl-3 pr-3 btn-success" data-toggle="modal" data-target="#modal-prep-producto" value="`+this.id_producto+`"><i class='fas fa-plus'></i></button></center>`;
			if (this.imagen==null || this.imagen==="") {
				this.imagen='sinFoto.png';
			}
			if (this.imagen==='sinFoto.png') {
				var dir="img";
			}else{
				var dir="files";
			}
			if (categorias[this.categoria_producto]['estado']==='i') {
				var nom="La categoría "+categorias[this.categoria_producto]['nombre']+" está inactiva";
			}else{
				var nom=categorias[this.categoria_producto]['nombre'];
			}
			if (unidades[this.unidad_medida]['estado']==='i') {
				var unid="La Unidad "+unidades[this.unidad_medida]['nombre']+" está inactiva";
			}else{
				var unid=unidades[this.unidad_medida]['nombre'];
			}
			imagenes[this.id_producto]=this.imagen;
			datostb=[
					this.usuario_producto,//0
					this.nombre_persona+" "+this.apellido_persona,//1
					this.nombre_producto,//2
					"<img class='img-fluid' src='"+url_base+"assets/"+dir+"/"+this.imagen+"' alt=''>",//3
					nom,//4
					this.nombre_linea,//5
					this.cantidad_consumible,//6
					unid,//7
					btn,//8
					this.descripcion_producto,//9
					this.precio_producto,//10
					this.id_producto,//11
					this.tipo_producto//12
				];	
			if (usuario["tipo"]==="INSTRUCTOR" && usuario["id"]===this.usuario_producto || usuario["tipo"]==="ADMINISTRADOR") {
				var rowNode=tableMateriales.row.add(datostb).draw().node();
				$(rowNode).find('td:eq(0)').addClass("encargado-mat text-center");
				$(rowNode).find('td:eq(1)').addClass("nomp-mat pt-1 pb-1");
				$(rowNode).find('td:eq(2)').addClass("img-mat p-0");
				$(rowNode).find('td:eq(3)').addClass("nomc-mat text-center");
				$(rowNode).find('td:eq(4)').addClass("noml-mat text-center");
				$(rowNode).find('td:eq(5)').addClass("cant-mat text-center");
				$(rowNode).find('td:eq(6)').addClass("nomu-mat text-center");
				$(rowNode).find('td:eq(7)').addClass("btns-mat text-center");
				// Borde de la fila en DataTable
				$(rowNode).find(".btnAddMat").on('click',prepararProducto); 	
				$(rowNode).find(".btnVisMat").on('click',formInput); 	
			}
		}										
	});
	filtroUsuarios(tableMateriales,0,usuario['id'],"Mis consumibles",$("#cajas-filtro3"));
}
function consultarMateriales(){
	$.ajax({
		"url":$('#consult_mat').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){
			if (datos.aviso) {	
				armarTablaMateriales(datos);
			}else{
				Swal.fire({
					  title:'Vaya!',
					  text:'No se puede consultar',
					  icon:'info',
					  showConfirmButton:false,
					  timer:1500
					});			
			}
		}
	});	
}
function formInput(){	
	
	if ($(this).hasClass("btnVisMat")) {
		idProducto= $(this).parents('tr').find('.btnVisMat').val();
		tr=dataFila(tableMateriales,idProducto,11);	
		// Llenar inputs de modal editar
		$("#nombre_vis_mat").val(tableMateriales.row(tr).data()[2])
		$("#cant_vis_mat").val(tableMateriales.row(tr).data()[6]);
		$("#precio_vis_mat").val(tableMateriales.row(tr).data()[10]);
		$("#linea_vis_mat").val(tableMateriales.row(tr).data()[5]);
		$("#descripcion_vis_mat").val(tableMateriales.row(tr).data()[9]);
		$("#categoria_vis_mat").val(tableMateriales.row(tr).data()[4]);
		$("#unidad_vis_mat").val(tableMateriales.row(tr).data()[7]);
		$("#usuario_vis_mat").val(tableMateriales.row(tr).data()[1]);
		if (imagenes[idProducto]==="sinFoto.png") {
			$("#imagen_vis_mat").attr("src",url_base+"assets/img/sinFoto.png");
		}else{
			$("#imagen_vis_mat").attr("src",url_base+"assets/files/"+imagenes[idProducto]);
			$.get($("#imagen_vis_mat").attr("src")).fail(function(){
				$("#imagen_vis_mat").attr("src",url_base+"assets/img/sinFoto.png");
				imagenes[idProducto]="sinFoto.png";
			});
		}		
	}else if ($(this).hasClass("btnVisDev")){
		idProducto= $(this).parents('tr').find('.btnVisDev').val();
		tr=dataFila(tableDevolutivo,idProducto,12);	
		$("#usuario_vis_dev").val(tableDevolutivo.row(tr).data()[9]);	
		$("#nombre_vis_dev").val(tableDevolutivo.row(tr).data()[4]);
		$("#placa_vis_dev").val(tableDevolutivo.row(tr).data()[0]);
		$("#csena_vis_dev").val(tableDevolutivo.row(tr).data()[1]);
		$("#serial_vis_dev").val(tableDevolutivo.row(tr).data()[2]);
		$("#descripcion_vis_dev").val(tableDevolutivo.row(tr).data()[13]);
		$("#precio_vis_dev").val(tableDevolutivo.row(tr).data()[11]);
		$("#linea_vis_dev").val(tableDevolutivo.row(tr).data()[16]);
		$("#estado_vis_dev").val(tableDevolutivo.row(tr).data()[17]);
		$("#unidad_vis_dev").val(tableDevolutivo.row(tr).data()[14]);
		$("#categoria_vis_dev").val(tableDevolutivo.row(tr).data()[15]);
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
	$("#filtro .f_usuario a").on("click", function() {
		filtroUsuarios(tableDevolutivo,9,$(this).attr('data-usuario'),$(this).text(),$("#cajas-filtro"));
    });
	$("#filtro .f_unidad a").on("click", function() {
		filtroUnidad(tableDevolutivo,5,$(this).text(),$("#cajas-filtro"));		
	});
	$("#filtro .f_categoria a").on("click", function() {
		filtroCategorias(tableDevolutivo,6,$(this).text(),$("#cajas-filtro"));
    });
         
    $("#filtro .f_linea a").on("click", function() {
		filtroLinea(tableDevolutivo,7,$(this).attr('data-linea'),$(this).text(),$("#cajas-filtro"));
	});
	$("#filtro .f_estado a").on("click", function() {
		filtroEstado(tableDevolutivo,8,$(this).text(),$("#cajas-filtro"));
	});
	$("#filtro3 .f_usuario a").on("click", function() {
		filtroUsuarios(tableMateriales,0,$(this).attr('data-usuario'),$(this).text(),$("#cajas-filtro3"));
    });
	$("#filtro3 .f_unidad a").on("click", function() {
		filtroUnidad(tableMateriales,7,$(this).text(),$("#cajas-filtro3"));		
	});
	$("#filtro3 .f_categoria a").on("click", function() {
		filtroCategorias(tableMateriales,4,$(this).text(),$("#cajas-filtro3"));
    });
         
    $("#filtro3 .f_linea a").on("click", function() {
		filtroLinea(tableMateriales,5,$(this).attr('data-linea'),$(this).text(),$("#cajas-filtro3"));
	});
	$("#filtro2 .f_estadoSalida a").on("click", function() {
		filtroLinea(table_salida,4,$(this).attr('data-estado'),$(this).text(),$("#cajas-filtro2"));
	});
	$("#filtro2 .f_tipoSalida a").on("click", function() {
		filtroLinea(table_salida,3,$(this).attr('data-salida'),$(this).text(),$("#cajas-filtro2"));
	});
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
        			<ul class="f_usuario dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        									
        			</ul>
      			</li>
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
    		</ul>
  		</li>
	</ul>	
	`);
	$("#filtros").append(`<div id="cajas-filtro" class="d-flex flex-wrap justify-content-start col-md-9" style="width:auto;"></div>`);
	$("#filtro2").addClass('ml-2');
	$("#filtro2").html(`<ul class="mb-0 pl-0" style="list-style-type:none;">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 mr-3 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>
    		<ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">		      	
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
	$("#filtro3").html(`<ul class="mb-0 pl-0" style="list-style-type:none;">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 mr-3 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>
    		<ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">		      	
    			<li class="dropdown-item dropdown-submenu p-0">
       				<a href="#" data-toggle="dropdown" 
       				class="dropdown-toggle dropdown-item w-100">Usuario</a>
        			<ul class="f_usuario dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">          				        									
        			</ul>
      			</li>
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
    		</ul>
  		</li>
	</ul>	
	`);
	
	$("#filtros3").append(`<div id="cajas-filtro3" class="d-flex flex-wrap justify-content-start col-md-9" style="width:auto;"></div>`);
	consultarFiltroCategorias();
	consultarFiltroUsuarios();
	consultarFiltroUnidades();
	consultarFiltroLineas();
	consultarFiltroEstados()
	window.setTimeout(function() {
    	promise.resolve();
  	},1500);	
	return promise.promise();
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
							$("#filtro .f_categoria").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">`+this.nombre_categoria+`</a>
											</li>`);
							$("#filtro3 .f_categoria").append(`<li class="dropdown-item p-0">
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
									$("#filtro .f_usuario").append(`<li class="dropdown-item p-0">
														<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">Mis devolutivos</a>
													</li>`);
									$("#filtro3 .f_usuario").append(`<li class="dropdown-item p-0">
														<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">Mis consumibles</a>
													</li>`);
									selectVis.append(`<option value=`+this.id_usuario+`>Mi devolutivo</option>`);
								}else if (usuario["tipo"]==="ADMINISTRADOR"){
									$("#filtro .f_usuario").append(`<li class="dropdown-item p-0">
														<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</a>
													</li>`);
									$("#filtro3 .f_usuario").append(`<li class="dropdown-item p-0">
														<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</a>
													</li>`);
									selectVis.append(`<option value="`+this.id_usuario+`">`+this.nombre_persona+" "+this.apellido_persona+`</option>`);
								}							
								if (usuario["tipo"]==="INSTRUCTOR" && this.nombre_tipo==="INSTRUCTOR") {
									if (usuario['id']===this.id_usuario) {
										$("#filtro .f_usuario").append(`<li class="dropdown-item p-0">
															<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">Mis devolutivos</a>
														</li>`);
										$("#filtro3 .f_usuario").append(`<li class="dropdown-item p-0">
															<a href="#" class="dropdown-item w-100" data-usuario="`+this.id_usuario+`">Mis consumibles</a>
														</li>`);
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
							$("#filtro .f_unidad").append(`<li class="dropdown-item p-0">
												<a href="#" class="dropdown-item w-100">`+this.nombre_unidad+`</a>
											</li>`);
							$("#filtro3 .f_unidad").append(`<li class="dropdown-item p-0">
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
								$("#filtro .f_linea").append(`<li class="dropdown-item p-0">
													<a href="#" class="dropdown-item w-100" data-linea="`+this.nombre_linea+`">Mi linea</a>
												</li>`);
								$("#filtro3 .f_linea").append(`<li class="dropdown-item p-0">
													<a href="#" class="dropdown-item w-100" data-linea="`+this.nombre_linea+`">Mi linea</a>
												</li>`);
							}else{
								$("#filtro .f_linea").append(`<li class="dropdown-item p-0">
													<a href="#" class="dropdown-item w-100" data-linea="`+this.nombre_linea+`">`+this.nombre_linea+`</a>
												</li>`);
								$("#filtro3 .f_linea").append(`<li class="dropdown-item p-0">
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
							$("#filtro .f_estado").append(`<li class="dropdown-item p-0">
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
function dataFila(tablen,nombre,col=0){	
	var row;	
	var Nfilas=tablen.rows().count();
	for (row = 0; row <= Nfilas; row++) {
		if (limpiarVocales(tablen.data()[row][col])==limpiarVocales(nombre)) {
			return row;
		}	
	}	
}
function prepararProducto(){
	$("#modal-prep-producto #accionBuscar").val("false");
	$("#modal-prep-producto #t_salida").val("");
	$("#modal-prep-producto #t_salida").val("");
	$("#modal-prep-producto #documento_exterior").val("");
	$("#caja-texto").hide();
	$("#div-oculto").hide();
	$("#div-oculto existExterna").val("");
	$("#div-oculto :input").attr("readonly",false);
	$("#div-oculto :input").attr("required",false);
	$("#div-oculto :input").val("");
	$("#caja-texto span").removeClass('text-success');
	$("#caja-texto span").removeClass('text-danger');
	$("#cant_p").val("");
	if ($(this).hasClass('btnAddMat')) {
		idProducto= $(this).parents('tr').find('.btnAddMat').val();
		tr=dataFila(tableMateriales,idProducto,11);	
		$("#modal-prep-producto #t_productos_salida").val("Consumible");
		$("#modal-prep-producto #id_productos_salida").val(idProducto);
		$("#cant_max_p").val(tableMateriales.row(tr).data()[6]);
		$("#cant_p").attr("max",tableMateriales.row(tr).data()[6]);
		$("#tab-cant-devs").hide();
		$("#cant_p").attr("readonly",false);
	}else if ($(this).hasClass('btnAddDev')){
		idProducto= $(this).parents('tr').find('.btnVisDev').val();
		tr=dataFila(tableDevolutivo,idProducto,12);	
		let datos=contarFilasRepetidasEnTablaDevolutivos(tableDevolutivo,tableDevolutivo.row(tr).data()[4],tableDevolutivo.row(tr).data()[9]);
		$("#modal-prep-producto #t_productos_salida").val("Devolutivo");
		$("#modal-prep-producto #id_productos_salida").val(idProducto);
		$("#cant_max_p").val(datos['cont']);
		$("#cant_p").attr("max",datos['cont']);
		$("#cant_p").attr("readonly",true);
		$("#tab-cant-devs tbody").html("");
		$("#tab-cant-devs tbody").append(datos['fila']);
		$(".check_data").on('change',checkData);
		$("#tab-cant-devs").show();
		$("#todos_dev").prop('checked',false);
		$("#todos_dev").change(function() {
			if ($(this).prop('checked')) {
				$(".check_data").prop('checked',true);	
				$("#cant_p").val(datos['cont']);			
			}else{
				$(".check_data").prop('checked',false);	
				$("#cant_p").val(0);
			}
		});
		
	}
}
function checkData(){
	var cant=$("#cant_p").val();
	if ($(this).prop('checked')) {
		cant++;
	}else{
		$("#tab-cant-devs #todos_dev").prop('checked',false);
		cant--;
	}
	let cantTable=$("#tab-cant-devs").find('.check_data').toArray().length;
	if (cantTable==cant) {
		$("#tab-cant-devs #todos_dev").prop('checked',true);
		$("#cant_p").val(cant);
	}else{
		$("#cant_p").val(cant);
	}
}
function contarFilasRepetidasEnTablaDevolutivos(tabla,nombre,usuario){
  var array=[];
  array['cont']= 0;
  array['fila']=``;
  tabla.rows().every(function(){    
    rowData=this.data();
    if (limpiarVocales(rowData[4])== limpiarVocales(nombre) && limpiarVocales(rowData[9])== limpiarVocales(usuario)) {      
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
function consultarPersonaExterna(){	
	if (parseInt($("#modal-prep-producto #documento_exterior").val())>=1000000000 && parseInt($("#modal-prep-producto #documento_exterior").val())<99999999999) {
		$("#modal-prep-producto #accionBuscar").val("true");
		$("#caja-cargando").show();
		$("#caja-texto").hide();
		$("#div-oculto").hide();
		$("#div-oculto :input").attr("readonly",false);
		$("#div-oculto :input").attr("required",false);
		$("#div-oculto :input").val("");
		$("#div-oculto existExterna").val("");
		$("#caja-texto span").removeClass('text-success');
		$("#caja-texto span").removeClass('text-danger');
		$.ajax({
			"url":url_base+"Solicitudes_controller/consultarPersonaExterna",
			"type":"POST",		
			"data":{"documento":$("#documento_exterior").val()},
			"dataType":"json",
			success: function(datos){
				$("#caja-cargando").hide();
				if (datos.aviso) {
					var persona=datos.persona;
					$("#caja-texto span").addClass('text-success');
					$("#caja-texto span").text('Usuario encontrado');				
					$("#nombre_exterior").val(persona.nombre_exterior);
					$("#empresa_exterior").val(persona.empresa_exterior);
					$("#cargo_exterior").val(persona.cargo_exterior);
					$("#telefono_exterior").val(persona.telefono_exterior);
					$("#div-oculto :input").attr("readonly",true);
					$("#div-oculto :input").attr("required",true);
					$("#div-oculto #existExterna").val(true);
					$("#div-oculto #personaExterna").val(datos.externa);
					$("#caja-texto").show();
					$("#div-oculto").show();
				}else{
					$("#div-oculto :input").attr("required",true);
					$("#div-oculto #existExterna").val(false);
					$("#caja-texto span").text('No se encontró el usuario');
					$("#caja-texto span").addClass('text-danger');
					$("#caja-texto").show();
					$("#div-oculto").show();
				}
			}
		}).fail(function(){
			$("#caja-cargando").hide();
			let titulo="Error!";
			let tipo="error";
			let texto="Problemas al agregar la solicitud. Intentelo más tarde";
			$("#modal-prep-producto #accionBuscar").val("false");
			alertasTemporal(tipo,titulo,texto);			
		}); 
	}else{
		if (!$("#modal-prep-producto #caja-texto span").hasClass('text-danger')) {
			$("#modal-prep-producto #caja-texto span").addClass('text-danger');
		}
		$("#modal-prep-producto #caja-texto").find("span").text('El documento digitado no es válido');	
		$("#modal-prep-producto #caja-texto").show();
		$("#modal-prep-producto #documento_exterior").addClass("is-invalid");	
	}
	
}
function agregarPersonaExterna(){
	$("#caja-cargando").show();
	var json={
		"documento_exterior":$("#modal-prep-producto #documento_exterior").val(),
		"nombre_exterior":$("#nombre_exterior").val(),
		"empresa_exterior":$("#empresa_exterior").val(),
		"cargo_exterior":$("#cargo_exterior").val(),
		"telefono_exterior":$("#telefono_exterior").val()
	}
	$.ajax({
		"url":url_base+"/Solicitudes_controller/agregarPersonaExterna",
		"type":"post",
		"data":json,
		"dataType":"json",
		success:function(datos){
			$("#caja-cargando").hide();
			if (datos.aviso) {
				$("#div-oculto #personaExterna").val(datos.externa);
			}
		}
	}).fail(function(){
		$("#caja-cargando").hide();
	});
}
function agregarSolicitud(){
	var bool=true;
	if (table_salida.rows().count()>0) {
		if (buscarEnColumna(table_salida,"En prestamo",4)===false){
			const swalWithBootstrapButtons = Swal.mixin({
			  customClass: {
			    confirmButton: 'btn btn-success',
			    cancelButton: 'btn btn-danger'
			  },
			  buttonsStyling: false
			});
			swalWithBootstrapButtons.fire({
				  title: 'Estas seguro?',
				  text: "La solicitud tienen productos en préstamo. La solicitud se actualizará a En préstamo lo cual no podrá modificar o retirar los productos de la solicitud!",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Si, Deseo continuar!',
				  cancelButtonText: 'No, lo cancelo!',
				  reverseButtons: true
			}).then((result) => {
		  		if (result.value) {
		  			$("#caja-cargando").show();
		  			$.ajax({
		  				"url":url_base+"Solicitudes_controller/agregarSalidas",
		  				"type":"post",
		  				"data":prepararDatosSolicitud(),
		  				"dataType":"json",
		  				success:function(datos){
		  					if (datos.aviso) {
		  						$("#caja-cargando").hide();
		  						let titulo="Exito!";
								let tipo="success";
								let texto=datos.texto;
								alertasBoton(tipo,titulo,texto);			
								$("div.swal2-container.swal2-center.swal2-backdrop-show").find("div.swal2-actions button.swal2-confirm.swal2-styled").click(function(){
									volverSolicitud();
								});
		  					}else{
		  						$("#caja-cargando").hide();
		  						let titulo="Vaya!";
								let tipo="info";
								let texto="No se logró agregar la solicitud";
								alertasTemporal(tipo,titulo,texto);			
		  					}
		  				},
		  			}).fail(function(){
						$("#caja-cargando").hide();
		  				let titulo="Vaya!";
						let tipo="info";
						let texto="No se logró agregar la solicitud";
						alertasTemporal(tipo,titulo,texto);	
		  			});
		  		} else if (result.dismiss === Swal.DismissReason.cancel) {
		  			let titulo="Cancelado!";
					let tipo="error";
					let texto="Has cancelado la operación";
					alertasTemporal(tipo,titulo,texto);
		  		}
		  	});	
		}else{
			const swalWithBootstrapButtons = Swal.mixin({
			  customClass: {
			    confirmButton: 'btn btn-success',
			    cancelButton: 'btn btn-danger'
			  },
			  buttonsStyling: false
			});
			swalWithBootstrapButtons.fire({
				  title: 'Estas seguro?',
				  text: "Estas seguro de crear la solicitud!",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Si, Continúe!',
				  cancelButtonText: 'No, lo cancelo!',
				  reverseButtons: true
				}).then((result) => {
			  		if (result.value) {
			  			$("#caja-cargando").show();
			  			$.ajax({
			  				"url":url_base+"Solicitudes_controller/agregarSalidas",
			  				"type":"post",
			  				"data":prepararDatosSolicitud(),
			  				"dataType":"json",
			  				success:function(datos){
			  					if (datos.aviso) {
			  						$("#caja-cargando").hide();
			  						let titulo="Exito!";
									let tipo="success";
									let texto=datos.texto;
									alertasBoton(tipo,titulo,texto);			
									$("div.swal2-container.swal2-center.swal2-backdrop-show").find("div.swal2-actions button.swal2-confirm.swal2-styled").click(function(){
										volverSolicitud();
									});
			  					}else{
			  						$("#caja-cargando").hide();
			  						let titulo="Vaya!";
									let tipo="info";
									let texto="No se logró agregar la solicitud";
									alertasTemporal(tipo,titulo,texto);			
			  					}
			  				},
			  			}).fail(function(){
							$("#caja-cargando").hide();
			  				let titulo="Vaya!";
							let tipo="info";
							let texto="No se logró agregar la solicitud";
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
	}else{
		let titulo="Vaya!";
		let tipo="info";
		let texto="No puedes continuar. Por favor llena la tabla con almenos un producto";
		alertasTemporal(tipo,titulo,texto);
	}
}
function editarSolicitud(){
	var bool=true;
	if (table_salida.rows().count()>0) {
		if (buscarEnColumna(table_salida,"En prestamo",4)===false){
			const swalWithBootstrapButtons = Swal.mixin({
			  customClass: {
			    confirmButton: 'btn btn-success',
			    cancelButton: 'btn btn-danger'
			  },
			  buttonsStyling: false
			});
			swalWithBootstrapButtons.fire({
				  title: 'Estas seguro?',
				  text: "La solicitud tienen productos en préstamo. La solicitud se actualizará a En préstamo lo cual no podrá modificar el estado de los productos!",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Si, Deseo continuar!',
				  cancelButtonText: 'No, lo cancelo!',
				  reverseButtons: true
			}).then((result) => {
		  		if (result.value) {
		  			$("#caja-cargando").show();
		  			$.ajax({
		  				"url":url_base+"Solicitudes_controller/editarSalidas",
		  				"type":"post",
		  				"data":prepararDatosSolicitud(),
		  				"dataType":"json",
		  				success:function(datos){
		  					if (datos.aviso) {
		  						$("#caja-cargando").hide();
		  						let titulo="Exito!";
								let tipo="success";
								let texto=datos.texto;
								alertasBoton(tipo,titulo,texto);			
								$("div.swal2-container.swal2-center.swal2-backdrop-show").find("div.swal2-actions button.swal2-confirm.swal2-styled").click(function(){
									volverSolicitud();
								});
		  					}else{
		  						$("#caja-cargando").hide();
		  						let titulo="Vaya!";
								let tipo="info";
								let texto="No se logró editar la solicitud";
								alertasTemporal(tipo,titulo,texto);			
		  					}
		  				},
		  			}).fail(function(){
						$("#caja-cargando").hide();
		  				let titulo="Vaya!";
						let tipo="info";
						let texto="No se logró editar la solicitud";
						alertasTemporal(tipo,titulo,texto);	
		  			});
		  		} else if (result.dismiss === Swal.DismissReason.cancel) {
		  			bool=false;
		  			let titulo="Cancelado!";
					let tipo="error";
					let texto="Has cancelado la operación";
					alertasTemporal(tipo,titulo,texto);
		  		}
		  	});	
		}else{
			const swalWithBootstrapButtons = Swal.mixin({
			  customClass: {
			    confirmButton: 'btn btn-success',
			    cancelButton: 'btn btn-danger'
			  },
			  buttonsStyling: false
			});
			swalWithBootstrapButtons.fire({
				  title: 'Estas seguro?',
				  text: "Estas seguro de editar la solicitud!",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Si, Continúe!',
				  cancelButtonText: 'No, lo cancelo!',
				  reverseButtons: true
				}).then((result) => {
			  		if (result.value) {
			  			$("#caja-cargando").show();
			  			$.ajax({
			  				"url":url_base+"Solicitudes_controller/editarSalidas",
			  				"type":"post",
			  				"data":prepararDatosSolicitud(),
			  				"dataType":"json",
			  				success:function(datos){
			  					if (datos.aviso) {
			  						$("#caja-cargando").hide();
			  						let titulo="Exito!";
									let tipo="success";
									let texto=datos.texto;
									alertasBoton(tipo,titulo,texto);			
									$("div.swal2-container.swal2-center.swal2-backdrop-show").find("div.swal2-actions button.swal2-confirm.swal2-styled").click(function(){
										volverSolicitud();
									});
			  					}else{
			  						$("#caja-cargando").hide();
			  						let titulo="Vaya!";
									let tipo="info";
									let texto="No se logró editar la solicitud";
									alertasTemporal(tipo,titulo,texto);			
			  					}
			  				},
			  			}).fail(function(){
							$("#caja-cargando").hide();
			  				let titulo="Vaya!";
							let tipo="info";
							let texto="No se logró editar la solicitud";
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
	}else{
		let titulo="Vaya!";
		let tipo="info";
		let texto="No puedes continuar. Por favor llena la tabla con almenos un producto";
		alertasTemporal(tipo,titulo,texto);
	}
}
function prepararDatosSolicitud(estado=""){
	var productos=table_salida.rows().data().toArray();
	var jsProd={};
	jsProd['id_solicitud']=$("#inN_solicitud").val();
	jsProd['usuario_solicitud']=usuario['id'];
	jsProd['total_solicitud']=$("#table_productos_solicitados #precio_total").text();
	jsProd['productos']={};
	for (var i = 0; i < productos.length; i++) {
		jsProd['productos'][i]={
			"producto_salida":productos[i][16],
			"cantidad_salida":productos[i][2],
			"estado_salida":productos[i][4],
			"tipo_salida":productos[i][3],
			"persona_id":productos[i][24],
			"persona_nombre":productos[i][0],
			"persona_empresa":productos[i][26],
			"persona_cargo":productos[i][27],
			"persona_telefono":productos[i][28],
			"exterior":productos[i][25]
		};
		if (buscarEnColumna(tableMateriales,productos[i][16],11)===false) {
			jsProd['productos'][i].consumible=true;
			jsProd['productos'][i].n_materiales=tableMateriales.row(dataFila(tableMateriales,productos[i][16],11)).data()[6];
		}else if (productos[i][22]==="Consumible"){
			jsProd['productos'][i].consumible=true;
			jsProd['productos'][i].n_materiales=0;
		}else{
			jsProd['productos'][i].consumible=false;
		}
	}
	if (estado==="") {
		if (buscarEnColumna(table_salida,"En prestamo",4)===false) {
			jsProd['estado_solicitud']="En prestamo";
		}else{
			jsProd['estado_solicitud']="Terminado";
		}
	}else{
		jsProd['estado_solicitud']=estado;
	}
	
	return jsProd;
}
function terminarPrestamo(){
	var id=$(this).val();
	var tr =datatFila(table_salida,id,29);
	if (table_salida.cell(tr,22).data()==="Consumible") {
		var consumible=true;
		var cantidad=table_salida.cell(tr,2).data();
	}else{
		var consumible=false;
		var cantidad=0;
	}
	var producto=table_salida.cell(tr,16).data();
	$("#caja-cargando").show();
	$.ajax({
		"url":url_base+"/Solicitudes_controller/terminarSalidaPrestamo",
		"type":"post",
		"data":{"id_salida":id,"id_solicitud":id_sol1,"id_producto":producto,"consumible":consumible,"cantidad":cantidad},
		"dataType":"json",
		success:function(datos){
			$("#caja-cargando").hide();
			if (datos.aviso) {
				if (datos.aviso2) {
					volverSolicitud();
				}else{
					if (buscarEnColumna(table_salida,datos.id_salida,29)===false){
						let fila=dataFila(table_salida,datos.id_salida,29);
						table_salida.cell(fila,4).data(datos.estado_salida).draw(false);
						table_salida.cell(fila,6).data("").draw(false);
						let titulo="Exito!";
						let tipo="success";
						let texto="Se modficó el estado la salida";
						alertasTemporal(tipo,titulo,texto);
					}
				}
			}
		}
	}).fail(function(){
		$("#caja-cargando").hide();
		let titulo="Vaya!";
		let tipo="info";
		let texto="Ocurrio un error inseperado al modificar la salida";
		alertasTemporal(tipo,titulo,texto);
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
  				$("#caja-cargando").show();
  				$.ajax({
  					"url":url_base+"/Solicitudes_controller/terminarSalidasPrestamos",
  					"type":"post",
  					"data":{"id_solicitud":id_sol1},
  					"dataType":"json",
  					success:function(datos){
  						if (datos.aviso) {
  							volverSolicitud();
  						}else{
  							$("#caja-cargando").hide();
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
function pausarSolicitud(){
	if (edit) {
		var url=url_base+"Solicitudes_controller/editarSalidas"
	}else{
		var url=url_base+"Solicitudes_controller/agregarSalidas";
	}

	const swalWithBootstrapButtons = Swal.mixin({
	  customClass: {
	    confirmButton: 'btn btn-success',
	    cancelButton: 'btn btn-danger'
	  },
	  buttonsStyling: false
	});
	swalWithBootstrapButtons.fire({
		  title: 'Estas seguro?',
		  text: "Estas seguro de pausar la solicitud!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonText: 'Si, Continúe!',
		  cancelButtonText: 'No, lo cancelo!',
		  reverseButtons: true
	}).then((result) => {
  		if (result.value) {
  			$("#caja-cargando").show();
  			$.ajax({
  				"url":url_base+"Solicitudes_controller/agregarSalidas",
  				"type":"post",
  				"data":prepararDatosSolicitud("Pausado"),
  				"dataType":"json",
  				success:function(datos){
  					if (datos.aviso) {
  						$("#caja-cargando").hide();
  						let titulo="Exito!";
						let tipo="success";
						let texto="Solicitud pausada correctamente";
						alertasBoton(tipo,titulo,texto);			
						$("div.swal2-container.swal2-center.swal2-backdrop-show").find("div.swal2-actions button.swal2-confirm.swal2-styled").click(function(){
							volverSolicitud();
						});
  					}else{
  						$("#caja-cargando").hide();
  						let titulo="Vaya!";
						let tipo="info";
						let texto="No se logró agregar la solicitud";
						alertasTemporal(tipo,titulo,texto);			
  					}
  				},
  			}).fail(function(){
				$("#caja-cargando").hide();
  				let titulo="Vaya!";
				let tipo="info";
				let texto="No se logró agregar la solicitud";
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