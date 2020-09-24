$(document).ready(iniciar);
var tr;
var nobCategoria;
var table;
function iniciar(){	
	window.setTimeout(function() {
      $("#caja-cargando").hide();
    },1300);
	table=$("#table-categorias").DataTable();
	$("#btn_add_categoria").on("click",guardarCategoria);
	$("#btn_edit_categoria").on("click",editarCategoria);
	$("#btn-agregar").on("click",limpiarAddFormCategoria);
	$("#edit_nom_cat").keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
            editarCategoria();
        }
		
	});
	$("#edit_descripcion_cat").keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){ 
            editarCategoria();
        }
		
	});
	$("#add_nom_cat").keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
            guardarCategoria()
        }
		
	});
	$("#add_descripcion_cat").keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){ 
            guardarCategoria()
        }
		
	});
	$("#add_form_categoria :input").keypress(function(event) {
		$("#text-add-categoria").html("");
		if ($("#text-add-categoria").hasClass('text-info')) {
			$("#text-add-categoria").removeClass('text-info')
		}else if($("#text-add-categoria").hasClass('text-danger')){
			$("#text-add-categoria").removeClass('text-info')
		}
	});
	$("#edit_form_categoria :input").keypress(function(event) {
		$("#text-edit-categoria").html("");
		if ($("#text-edit-categoria").hasClass('text-info')) {
			$("#text-edit-categoria").removeClass('text-info')
		}else if($("#text-add-categoria").hasClass('text-danger')){
			$("#text-edit-categoria").removeClass('text-info')
		}
	});
	$(".close").on('click', function() {
		limpiarEditFormCategoria();
		limpiarAddFormCategoria();		
	});		
	agregarFiltro();	
	$("thead tr").find('th:eq(3)').attr('style', 'width:100px;');
	$("thead tr").find('th:eq(2)').attr('style', 'width:120px;');
	consultarCategorias();
}
function limpiarAddFormCategoria(){
	$("#add_form_categoria :input").val("");
	if ($("#text-add-categoria").hasClass('text-info')) {
		$("#text-add-categoria").removeClass('text-info')
	}else if($("#text-add-categoria").hasClass('text-danger')){
		$("#text-add-categoria").removeClass('text-info')
	}
	$("#add_form_categoria").find("#add_estado_cat").val("a");
	$("#add_form_categoria").find("#add_estado_cat option[value='a']").attr('selected', true);
	$("#add_form_categoria").find("#add_estado_cat option[value='i']").attr('selected', false);
} 
function limpiarEditFormCategoria(){
	$("#edit_form_categoria :input").val("");
	if ($("#text-edit-categoria").hasClass('text-info')) {
		$("#text-edit-categoria").removeClass('text-info')
	}else if($("#text-edit-categoria").hasClass('text-danger')){
		$("#text-edit-categoria").removeClass('text-info')
	}
	$("#text-edit-categoria").text("");
}
function guardarCategoria(){	
	if ($("#add_nom_cat").val()==="" || $("#add_descripcion_cat").val()==="" || $("#add_estado_cat").val()==="") {
		var texto="No puedes continuar. Los campos están vacíos!"
		Swal.fire({
     	  title:'Vaya!',
     	  text:texto,
     	  icon:'info',
     	  showConfirmButton:false,
     	  timer:1300
     	});
	}else{
		var nomb=String($("#add_nom_cat").val()).toUpperCase();
		$("#add_nom_cat").val(nomb);
		if (buscarEnColumna(table,$("#add_nom_cat").val())) {
			$.ajax({
				"url":$("#add_form_categoria").attr("action"),
				"type":$("#add_form_categoria").attr("method"),
				"data":$("#add_form_categoria").serialize(),
				"dataType":"json",
				success: function(datos){					
					if (datos.aviso) {	
						
						armarTablaCategorias(datos,2);
		             	Swal.fire({
		             	  title:'Exito!',
		             	  text:datos.texto,
		             	  icon:'success',
		             	  showConfirmButton:false,
		             	  timer:1000
		             	});
		             	$('#add_categoria').modal('toggle');
		             	limpiarAddFormCategoria();
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
					}
					
				}
			});				
		}else{
			var texto="No se puede guardar la categoría. La categoría ya existe!"
			Swal.fire({
         	  title:'Vaya!',
         	  text:texto,
         	  icon:'info',
         	  showConfirmButton:false,
         	  timer:1500
         	});
		}
	}
}
function editarCategoria(){
	if ($("#edit_nom_cat").val()==="" || $("#edit_descripcion_cat").val()==="" || $("#edit_estado_cat").val()==="") {
		$("#text-edit-categoria").addClass('text-danger');
		$("#text-edit-categoria").html("Por favor	 llene todos los campos");
	}else{	
		var opt=0;

		if (limpiarVocales($("#edit_nom_cat").val())===limpiarVocales(nobCategoria)) {
			opt=1;
		}				
		$("#edit_nom_cat").val(String($("#edit_nom_cat").val()).toUpperCase());
		if (buscarEnColumna(table,$("#edit_nom_cat").val(),0,opt)) {			
			$.ajax({
				"url":$("#edit_form_categoria").attr("action"),
				"type":$("#edit_form_categoria").attr("method"),
				"data":$("#edit_form_categoria").serialize(),		
				"dataType":"json",
				success: function(datos){
					console.log(datos);	
					if (datos.aviso) {		
						armarTablaCategorias(datos,1);					
						console.log(tr+"->"+table.row(tr).data());
			           	Swal.fire({
		             	  title:'Exito!',
		             	  text:datos.texto,
		             	  icon:'success',
		             	  showConfirmButton:false,
		             	  timer:1700
		             	});			           	
			           	limpiarEditFormCategoria();				
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
					}
					$('#edit_categoria').modal('toggle');
				}
			});
		}else{
           	Swal.fire({
         	  title:'Vaya!',
         	  text:'No se puede modificar la categoria. La categoría ya existe',
         	  icon:'info',
         	  showConfirmButton:false,
         	  timer:1500
         	});
		}
		
	}
}
function armarTablaCategorias(datos,opcion=0){	
	var datostb=[];		
	if (opcion==0) {
		$.each(datos, function () {				
			if (this.id_categoria) {
				if (this.estado==='a') {
					datostb=[this.nombre_categoria,this.descripcion_categoria,"<center class='est-cat bg-success font-weight-bold text-center' style='border-radius:5px;'>Activo</center>","<button class='btn btn-sm btn-warning btnEdit' data-toggle='modal' data-target='#edit_categoria' value='"+this.id_categoria+"'><i class='fas fa-edit'></i></button>"];				
				}else if (this.estado==='i') {
					datostb=[this.nombre_categoria,this.descripcion_categoria,"<center class='est-cat bg-danger font-weight-bold text-center' style='border-radius:5px;'>Inactivo</center>","<button class='btn btn-sm btn-warning btnEdit' data-toggle='modal' data-target='#edit_categoria' value='"+this.id_categoria+"'><i class='fas fa-edit'></i></button>"];
				}				
				var rowNode=table.row.add(datostb).draw().node();								
				$(rowNode).find('td:eq(0)').addClass("nom-cat");
				$(rowNode).find('td:eq(1)').addClass("desc-cat");			
				$(rowNode).find('td:eq(3)').addClass("btns-cat d-flex justify-content-center");
			}										
		});
	}else{
		if (datos.estado==='a') {
			datostb=[datos.nombre_categoria,datos.descripcion_categoria,"<center class='est-cat bg-success font-weight-bold text-center' style='border-radius:5px;'>Activo</center>","<button class='btn btn-sm btn-warning btnEdit' data-toggle='modal' data-target='#edit_categoria' value='"+datos.id_categoria+"'><i class='fas fa-edit'></i></button>"];				
		}else if (datos.estado==='i') {
			datostb=[datos.nombre_categoria,datos.descripcion_categoria,"<center class='est-cat bg-danger font-weight-bold text-center' style='border-radius:5px;'>Inactivo</center>","<button class='btn btn-sm btn-warning btnEdit' data-toggle='modal' data-target='#edit_categoria' value='"+datos.id_categoria+"'><i class='fas fa-edit'></i></button>"];
		}
		if (opcion==1) {			
			var rowNode=table.row(tr).data(datostb).draw().node();
			table.row(tr).invalidate();
		}else if (opcion==2) {						
			var rowNode=table.row.add(datostb).draw().node();
		}
		$(rowNode).find('td:eq(0)').addClass("nom-cat");
		$(rowNode).find('td:eq(1)').addClass("desc-cat");			
		$(rowNode).find('td:eq(3)').addClass("btns-cat d-flex justify-content-center");
	}	
	$(".btnEdit").on('click',formEditinput); 	
}
function consultarCategorias(option=null){
	$.ajax({
		"url":$('#consult_cat').val(),
		"type":"POST",		
		"dataType":"json",
		success: function(datos){
			if (datos.aviso) {	
				if (datos[0].nombre_categoria) {					
					armarTablaCategorias(datos);
				}
			}
		}
	});
}
function formEditinput(){		
	nobCategoria= $(this).parents('tr').find('.nom-cat').text();	
	dataFila(nobCategoria);
	tr=dataFila(nobCategoria);	
	var descripcion= $(this).parents('tr').find('.desc-cat').text();
	var estado=$(this).parents('tr').find('.est-cat').text();		
	if (estado==="Activo") {
		$("#edit_estado_cat").val("a");
		$("#edit_estado_cat option[value='a']").attr('selected',true);

		$("#edit_estado_cat option[value='i']").attr('selected',false);
	}else if (estado==="Inactivo") {
		$("#edit_estado_cat").val("i");
		$("#edit_estado_cat option[value='a']").attr('selected',false);
		$("#edit_estado_cat option[value='i']").attr('selected',true);
	}	
	var ID= $(this).val();	
	$("#edit_tittle_categoria").html("Categoria "+nobCategoria);
	$("#edit_id_cat").val(ID);
	$("#edit_nom_cat").val(nobCategoria);
	$("#edit_descripcion_cat").val(descripcion);	
}
// Funcion para obtener la fila real en donde se encuentra almacenado el data real de la fila
function dataFila(nombre,col=0){	
	var row;	
	var Nfilas=table.rows().count();
	for (row = 0; row <= Nfilas; row++) {
		if (limpiarVocales(table.data()[row][col])==limpiarVocales(nombre)) {
			return row;
		}	
	}	
}
function agregarFiltro(){
	$("#filtro").html(`<ul class="mb-0 pl-0" style="list-style-type:none">
  		<li>
    		<button href="#" type="button" id="dropdown" data-toggle="dropdown" 
    		class="p-2 m-0 mr-3 btn btn-primary dropdown-toggle multi-level-dropdown" 
    		aria-haspopup="true" aria-expanded="false">Filtrar</button>
    		<ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">		      	
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
	</ul>	
	`);
	$("#filtros").append(`<div id="cajas-filtro" class="col-9 d-flex flex-wrap justify-content-start"></div>`);
	$("#f_estado li a").on("click", function() {
		filtrartabla(table,2,$(this).text());
		if ($("#estado_filtrada").length) {
			$("#estado_filtrada h3").html($(this).text());

		}else{
			$("#cajas-filtro").append(`<div id="estado_filtrada" style="width:auto;" class="order-1 ml-1 mr-1">
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
		  		filtrartabla(table,2,'');
		  		$("#estado_filtrada").remove();
		  	});
		}
	});
}
