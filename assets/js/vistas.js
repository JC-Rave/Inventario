$(document).ready(iniciar);

function iniciar(){
	window.setTimeout(function() {
      $("#caja-cargando").hide();
    },1300);
    $("#subir_excel").change(subirArchivo);
}
function subirArchivo(){
	$("#caja-cargando").show();
	var archivos=new FormData($("#form_archivo")[0]);
	$.ajax({
		type:'post',
		data:archivos,
		dataType:'json',
		contentType:false,
		processData:false,
		url:base_url('Leer_excel/subirArchivo'),
		success:function(datos) {
			$("#caja-cargando").hide();
			if (datos.aviso) {
				Swal.fire({
             	  title:'Exito!',
             	  text:datos.texto,
             	  icon:'success',
             	  showConfirmButton:false,
             	  timer:1200
             	});
			}else{
				Swal.fire({
		     	  title:'Oh no!',
		     	  text:"fallo al subir archivo",
		     	  icon:'error',
		     	  showConfirmButton:false,
		     	  timer:1200
		     	});
			}
		}
	}).fail(function(){
		$("#caja-cargando").hide();
		Swal.fire({
     	  title:'Oh no!',
     	  text:"fallo al subir archivo",
     	  icon:'error',
     	  showConfirmButton:false,
     	  timer:1200
     	});
	});
}