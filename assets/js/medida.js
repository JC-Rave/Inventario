$(document).ready(iniciar);
var actual;
var tabla;

function iniciar(){
    generar_filtro();    
    tabla=$("#tb_unidad").DataTable();

    $("#btn_registrar").on("click", registrar);
    $("#btn_editar").on("click", editar);

    $('#nombre_unidad').keyup(function() {diseño(!error?'':'nombre_unidad');});
    $('#edit_nombre_unidad').keyup(function() {diseño(!error?'':'edit_nombre_unidad');});

    $(".cancelar").on("click", cancelar);
    $("#tb_unidad tbody").on("click", ".editar_unidad", pre_editar);
    window.setTimeout(function() {
      $("#caja-cargando").hide();
    },1300);
}

function registrar() {
    $.ajax({
        type:'post',
        data:$("#fm_registrar").serialize(),
        dataType:'json',
        url:base_url('Unidad_controller/regUnidad'),
        success:function(respuesta) {
            if (respuesta[0].res=='invalid') {
                invalidar_campos(respuesta);

            }else if(!respuesta[0].res){
                alerta(respuesta);

            }else{
                error=false;
                $("#reg_unidad .close").click();

                tabla.row.add([
                    $('#nombre_unidad').val(),
                    `<center style="border-radius: 5px;" class="font-weight-bold bg-success">Activo</center>`,
                    `<center>
                        <button class="editar_unidad btn btn-sm btn-warning"  data-toggle="modal" data-target="#editar_unidad">
                            <i class="fas fa-edit"></i>
                        </button>
                    </center>`
                ]).draw(false);

                limpiar();
                alerta(respuesta);
            }
        }
    });
}

function editar() {
    var json={
        'nombre':[actual[0], $("#edit_nombre").val()],
        'estado':$("#edit_estado").val()
    };

    $.ajax({
        type:'post',
        data:json,
        dataType:'json',
        url:base_url('Unidad_controller/editar_unidad'),
        success:function(respuesta) {
            if (respuesta[0].res=='invalid') {
                invalidar_campos(respuesta, 'edit_nombre');

            }else if (!respuesta[0].res) {
                alert(respuesta);

            }else if(respuesta[0].afectadas!=0){
                var clase=$("#edit_estado").val()=='a'?'bg-success':'bg-danger';
                var estado=$("#edit_estado").val()=='a'?'Activo':'Inactivo';

                actual[1].data({
                    0:$("#edit_nombre").val(),
                    1:`<center style="border-radius: 5px;" class="font-weight-bold `+clase+`">`+estado+`</center>`,
                    2:`<center>
                        <button class="editar_unidad btn btn-sm btn-warning" data-toggle="modal" data-target="#editar_unidad">
                            <i class="fas fa-edit"></i>
                        </button>
                    </center>`
                }).draw(false);

                error=false;
                alerta(respuesta);
                $("#editar_unidad .close").click();

            }else{
                $("#editar_unidad .close").click();
            }
        }
    });    
}

function generar_filtro() {
    $("#filtro").html(`<ul class="mb-0 pl-0" style="list-style-type:none">
        <li>
            <button href="#" type="button" id="dropdown" data-toggle="dropdown" 
            class="p-2 m-0 btn btn-primary dropdown-toggle multi-level-dropdown" 
            aria-haspopup="true" aria-expanded="false">Filtrar</button>
            <ul id="f_estado" style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">
                <li class="dropdown-item dropdown-submenu p-0">
                    <a href="#" class="dropdown-item w-100">Activo</a>
                </li>
                <li class="dropdown-item dropdown-submenu p-0">
                    <a href="#" class="dropdown-item w-100">Inactivo</a>
                </li>
            </ul>
        </li>
    </ul>`);

    $("#f_estado a").on("click", function() {
        filtrartabla(tabla,1,$(this).text());
        if ($("#estado_filtrada").length) {
            $("#estado_filtrada h3").html($(this).text());

        }else{
            $("#filtros").append(`<div id="estado_filtrada" style="width:auto;">
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
                filtrartabla(tabla,1,'');
                $("#estado_filtrada").remove();
            });
        }
    });
}

function pre_editar() {
    cancelar(['edit_nombre', 'edit_estado']);
    actual=[];

    var foco=$(this).parents('tr');
    var unidad=tabla.row(foco).data();

    $("#editar_unidad h4").html('Editar medida: '+unidad[0]);    
    actual=[unidad[0], tabla.row(foco)];

    $("#edit_nombre").val(unidad[0]);
    $("#edit_estado").val($(unidad[1]).text()=='Activo'?'a':'i');
}

function cancelar(ids=['nombre_unidad']) {
    error=false;
    restablecer(ids[0]);

    if (ids[0]=='nombre_unidad') {
        limpiar();

    }else{
        restablecer(ids[1]);
    }
}

function alerta(respuesta) {
    Swal.fire({
      position: 'top',
      icon: respuesta[0].res==true?'success':'error',
      title: respuesta[0].mensaje,
      showConfirmButton: true
    }).then((result) => {
        setTimeout(function() {
            $(".sidebar-mini").css('padding-right', '0px');
        },170);
    });
}

function invalidar_campos(respuesta, id='nombre_unidad') {
    error=true;
    /*
        en cada condicion consulto si un input o select es ivalido de ser asi:
        - agrego una clase para advertir al usuario que el campo es invalido
        - agrego el error que evita continuar con los procesos normalmente
    */
    if (respuesta[0].nombre_unidad.length>0) {
        $('#'+id).val('');
        $('#'+id).addClass('is-invalid');

        if (id=='nombre_unidad') {
            $('#error_nombre').html(respuesta[0].nombre_unidad);

        }else{
            $('#error_'+id).html(respuesta[0].nombre_unidad);
        }
    }
}

function limpiar() {$('#nombre_unidad').val('');}
