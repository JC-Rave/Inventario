$(document).ready(iniciar);
var imagenes;
var totalpaginas;
var pagina;
var actual;

function iniciar() {
	window.setTimeout(function() {
      $("#caja-cargando").hide();
    },1300);
	$(".table").length?'':paginador();

	$("#btn_preRegistrar").on("click", pre_agregar);
	$("#btn_registrar").on("click", registrar);
	$(".btn_preEditar").on("click", pre_editar);
	$("#btn_editar").on("click", editar);
}

function pre_agregar() {
	limpiar();
	$("#ver_imagen").attr("src", base_url('assets/img/sinFoto.png'));

	preview('imagen', 'ver_imagen');
}

function registrar() {
	var registro=new FormData($("#registrar_imagen")[0]);
	$("#caja-cargando").show();
	$.ajax({
		type:'post',
		data:registro,
		dataType:'json',
		contentType:false,
		processData:false,
		url:base_url('Galeria_controller/registrar_imagen'),
		success:function(respuesta) {
			$("#caja-cargando").hide();
			if (respuesta[0].res=='invalid') {
                Swal.fire({
			      position: 'top',
			      icon: 'error',
			      text: respuesta[0].nombre+' '+respuesta[0].imagen,
			      showConfirmButton: true
			    }).then((result) => {
			        setTimeout(function() {
			            $(".sidebar-mini").css('padding-right', '0px');
			        },170);
			    });

			}else if (!respuesta[0].res) {
				alerta(respuesta);

			}else{
				if ($(".table").length) {
					$("#imgs").empty();
				}

				$("#imgs").prepend(`<div class="col-lg-4 col-sm-6">
                  	<figure>
	                    <img style="cursor: pointer;" class="border p-1 img-fluid" src="`+base_url('assets/files/'+respuesta[0].imagen)+`" alt="">
	                    <figcaption class="col">
                     	 	<div class="row justify-content-between pt-1">
		                        <div class="col-md-10">`+$("#nombre").val()+`</div>
		                        <div>
		                          	<button class="btn_preEditar m-0 p-1 btn btn-warning" data-toggle="modal" data-target="#md_editar">
		                            	<i class="fas fa-edit"></i>
		                          	</button>
		                        </div>
	                      	</div>
	                    </figcaption>
                  	</figure>
                </div>`);

				imagenes=$(".col-lg-4").toArray();
				$(".btn_preEditar").on("click", pre_editar);

				imagen(Math.ceil(imagenes.length/6)!=$(".pagina").length?true:false);
				
                $("#md_registrar .close").click();
				alerta(respuesta);
                limpiar();
			}
		}
	}).fail(function(){
		$("#caja-cargando").hide();
	});
}

function pre_editar() {
	var nombre=$(this).parents('figcaption').find('.col-md-10').text();
	var imagen=$(this).parents('figure').find('img');
	actual=[nombre, imagen, $(this).parents('figcaption').find('.col-md-10')];

	$("#editnombre").val(nombre);
	$("#editver_imagen").attr({'alt': nombre, 'src': $(imagen).attr('src')});

	preview('editimagen', 'editver_imagen', imagen);
}

function editar() {
	var imagen=$("#editimagen").val().length?true:false;
	var editar=new FormData($("#editar_imagen")[0]);
	$("#caja-cargando").show();
	$.ajax({
		type:'post',
		data:editar,
		dataType:'json',
		contentType:false,
		processData:false,
		url:base_url('Galeria_controller/editar_imagen/'+'/'+imagen+'/'+actual[0]),
		success:function(respuesta) {
			$("#caja-cargando").hide();
			if (respuesta[0].res=='invalid') {
                Swal.fire({
			      position: 'top',
			      icon: 'error',
			      text: respuesta[0].nombre+' '+respuesta[0].imagen,
			      showConfirmButton: true
			    }).then((result) => {
			        setTimeout(function() {
			            $(".sidebar-mini").css('padding-right', '0px');
			        },170);
			    });

			}else if (!respuesta[0].res) {
				alerta(respuesta);

			}else if (respuesta[0].afectadas!=0) {
				$(actual[1]).attr('src', base_url('assets/files/'+respuesta[0].imagen));
				$(actual[2]).text($("#editnombre").val());

                $("#md_editar .close").click();
				alerta(respuesta);
				
			}else{
				$("#md_editar .close").click();
			}
		}
	}).fail(function(){
		$("#caja-cargando").hide();
	});
}

function preview(etiqueta, ver_imagen, imagen='') {
	$("#"+etiqueta).change(function (e) {
		if (!e.originalEvent.target.value.length) {
			if (imagen!='') {
				$("#"+ver_imagen).attr("src", $(imagen).attr('src'));
			}else{
				$("#"+ver_imagen).attr("src", base_url('assets/img/sinFoto.png'));
			}
		}else{
			let reader=new FileReader();
			reader.readAsDataURL(e.target.files[0]);

			reader.onload=function () {
				$("#"+ver_imagen).attr("src", reader.result);
			};
		}
	});
}

function limpiar() {
    $('#nombre').val('');
    $('#imagen').val('');
    $('#holder_imagen').html('Seleccionar Imagen');
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

function paginador() {
	imagenes=$(".col-lg-4").toArray();
	totalpaginas=Math.ceil(imagenes.length/6);

	$("#paginacion").twbsPagination({
        totalPages: totalpaginas,
        visiblePages: 7,
        first: null,
        prev: 'Anterior',
        next: 'Siguiente',
        last: null,
        paginationClass: 'pagination m-0 pg-blue',
        pageClass: 'page-item pagina',
        onPageClick: function (event, page) {
        	pagina=parseInt(page);
			pagina=(pagina*6)-6;

			$(imagenes).hide();
			for (var i=pagina; i<pagina+6; i++) {
				$(imagenes[i]).show();
			}
        }
    });
}

function imagen(destroy) {
	pagina=parseInt($(".active a").text());

	if (destroy) {
		$("#paginacion").twbsPagination('destroy');
		totalpaginas=Math.ceil(imagenes.length/6);

		$("#paginacion").twbsPagination({
	        totalPages: totalpaginas,
	        startPage: $(".active a").length?pagina:1,
	        visiblePages: 7,
	        first: null,
	        prev: 'Anterior',
	        next: 'Siguiente',
	        last: null,
	        paginationClass: 'pagination m-0 pg-blue',
	        pageClass: 'page-item pagina',
	        onPageClick: function (event, page) {
	        	pagina=parseInt(page);
				pagina=(pagina*6)-6;

				$(imagenes).hide();
				for (var i=pagina; i<pagina+6; i++) {
					$(imagenes[i]).show();
				}
	        }
	    });

	}else{
		pagina=(pagina*6)-6;
		$(imagenes).hide();
		for (var i=pagina; i<pagina+6; i++) {
			$(imagenes[i]).show();
		}
	}
}