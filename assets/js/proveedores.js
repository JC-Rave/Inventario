$(document).ready(iniciar);
var data;
var filaPro;
var foco;
var actual;
var tabla;
var tb_inventario;
var tb_suministra;
var tb_verSuministra;

function iniciar(){
    tabla=$("#tb_proveedores").DataTable();

    //para los modales
    traducir();
    tb_suministra=$("#suministra").DataTable();
    tb_inventario=$("#inventario").DataTable();
    tb_verSuministra=$("#ver_suministra").DataTable();
    visualizarColumnaEntabla(tb_suministra,4,false);
    $("#suministra").attr('style', 'width:100% !important;');

    $("#inventario_wrapper .label").html('<label>Inventario</label>');
    $("#suministra_wrapper .label").html('<label>Suministra</label>');
    $("#ver_suministra_wrapper .label").html('<label>Suministra</label>');

    $.when(generar_filtro()).then(function () {
        window.setTimeout(function() {
            $("#caja-cargando").hide();
        },1300);
    });

    //pre-registrar proveedor
    $("#reg_pro").on("click", pre_registrar);

    //pre-editar proveedor
    $("#tb_proveedores tbody").on("click", ".editar_proveedor", pre_editar);
    $("#suministra tbody").on("click", ".editar_detalles", pre_editarDetalle);

    //agregar o eliminar lo que suministra el proveedor
    $("#btn_continuar").on("click", agregar);
    $("#btn_editar_detalle").on("click", editarDetalle);
    $("#inventario tbody").on("click", '.agregar', pre_agregar);
    $("#suministra tbody").on("click", '.eliminar', eliminar);

    //cancelar registro
    $(".cancelar").on("click", cancelar);

    //registrar proveedor
    $("#btn_registrar").on("click", registrar);

    //editar proveedor
    $("#btn_editar").on("click", editar);

    //utilizo una de las funciones de escuha del teclado para el diseño de interfaz
    $('#nit').keyup(function() {diseño(!error?'':'nit');});
    $('#proveedor').keyup(function() {diseño(!error?'':'proveedor');});
    $('#telefono').keyup(function() {diseño(!error?'':'telefono');});
    $('#correo').keyup(function() {diseño(!error?'':'correo');});
    $('#url').keyup(function() {diseño(!error?'':'url');});
    $('#precio').keyup(function() {diseño(!error?'':'precio');});
    $('#descripcion').keyup(function() {diseño(!error?'':'descripcion');});

    //abro el modal para ver los datos y del proveedor y lo que suministra
    $("#tb_proveedores tbody").on("click", ".visualizar", ver_proveedor);
}

function pre_registrar() {
    //modifico el titulo del modal
    $(".titulo").html('Registrar Proveedor');

    //escondo el input estado y acomo el input correo
    if ($("#tipo_user").val()=='ADMINISTRADOR') {
        $(".div_correo").removeClass('col-md-3');
        $(".div_correo").addClass('col-md-6');
        $(".div_estado").hide();
    }

    //cambio el texto informativo
    $("#reg_editar_proveedor p").html(`En esta sección podrás registrar nuevos proveedores al sistema.
        <br><strong>Nota: Los campos con asterisco (*) son obligatorios</strong>`);

    //oculto el boton editar y muestro el de registrar
    $("#btn_editar").hide(); 
    $("#btn_registrar").show();

    //limpio el modal
    limpiar();
    cancelar();
}

function registrar() {  
    $("#caja-cargando").show();
    var nit=$("#nit").val();
    var proveedor=$("#proveedor").val();
    var telefono=$("#telefono").val();
    var correo=$("#correo").val();
    var url=$("#url").val();

    var productos=[];
    for (var i=0; i<tb_suministra.rows().data().length; i++) {
        var fila=tb_suministra.row(i).data();
        fila=[fila[0],fila[1],fila[2]];
        productos.push(fila);
    }

    var json={
        'nit': nit,
        'proveedor': proveedor,
        'telefono': telefono,
        'correo': correo,
        'url': url,
        'productos': productos
    };
    
    $.ajax({
        type:'post',
        data:json,
        dataType:'json',
        url:base_url('Proveedores_controller/regProveedor'),
        success:function(respuesta) {
            $("#caja-cargando").hide();
            // verifico la respuesta llegada por clave res.
            if (respuesta[0].res=='invalid') {
                invalidar_campos(respuesta);
                $('#reg_editar_proveedor').animate({scrollTop: 0});

            }else{
                //limpio los input de errores
                error=false;

                //cierro el modal
                $("#reg_editar_proveedor .close").click();

                //vacio los input
                limpiar();

                //agrego el nuevo registro a la tabla
                var fila=tabla.row.add([
                    nit,
                    proveedor,
                    telefono,
                    correo,
                    `<a target="_black" style="color: #3B89EA" href="`+url+`">`+acortar(url)+`</a>`,
                    `<center style="border-radius: 5px;" class="font-weight-bold bg-success">Activo</center>`,
                    `<center>
                        <button class="visualizar btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </button>

                        <button class="editar_proveedor btn btn-sm btn-warning"  data-toggle="modal" data-target="#reg_editar_proveedor">
                            <i class="fas fa-edit"></i>
                        </button>
                    </center>`
                ]).draw(false).node();

                // agrego clases a las celdas necesarias para visulizacion especifica
                $(fila).find('td').eq(0).attr({'class': 'visualizar'});
                $(fila).find('td').eq(1).attr({'class': 'visualizar'});
                $(fila).find('td').eq(2).attr({'class': 'visualizar'});
                $(fila).find('td').eq(3).attr({'class': 'visualizar'});
                $(fila).find('td').eq(5).attr({'class': 'visualizar'});

                // aviso al usuario de los resultados
                alerta(respuesta);
            }
        }
    }).fail(function(){
        $("#caja-cargando").hide();
    });
}

function pre_editar() {
    //quito etiquetas de error
    cancelar();

    //limpio mi variable global 'actual'
    actual=[];   

    //muestro y acomodo el input estado
    if ($("#tipo_user").val()=='ADMINISTRADOR') {
        $(".div_correo").removeClass('col-md-6');
        $(".div_correo").addClass('col-md-3');
        $(".div_estado").show();        
    }

    //oculto el boton registrar y muestro el de editar
    $("#btn_editar").show();
    $("#btn_registrar").hide();

    //cambio el texto informativo
    $("#reg_editar_proveedor p").html(`En esta sección podrás editar los datos del proveedor en el sistema.
        <br><strong>Nota: Los campos con asterisco (*) son obligatorios</strong>`);

    //obtengo la tabla y los datos del proveedor seleccionado
    var foco=$(this).parents('tr');
    var proveedor=tabla.row(foco).data();

    //modifico el titulo del modal
    $(".titulo").html('Editar Proveedor: '+proveedor[1]); 

    //almaceno en mi variable global los campos obligatorio actuales
    actual=[proveedor[0], proveedor[3], $(proveedor[4]).text(), tabla.row(foco), $(proveedor[5]).text()=='Activo'?'a':'i'];

    //agrego los datos a los input
    $("#nit").val(proveedor[0]);
    $("#proveedor").val(proveedor[1]);
    $("#telefono").val(proveedor[2]);
    $("#correo").val(proveedor[3]);
    $("#url").val($(proveedor[4]).text());
    $("#estado").val($(proveedor[5]).text()=='Activo'?'a':'i');

    //cargo la tabla de suministro con los datos
    var fila;
    $.ajax({
        type:'post',
        data:{'nit':proveedor[0]},
        dataType:'json',
        url:base_url('Proveedores_controller/verProveedor'),
        success:function(respuesta) {
            for (var i=0; i<respuesta.length; i++) {
                for (var j=0; j<tb_inventario.rows().data().length; j++) {
                    fila=tb_inventario.row(j).data();
                    
                    if (fila[0]==respuesta[i].nombre_producto) {
                        tb_inventario.row(j).remove().draw(false);

                        tb_suministra.row.add([
                            fila[0],
                            respuesta[i].precio,
                            respuesta[i].descripcion,
                            `<center>
                                <button type="button" class="btn btn-sm btn-warning editar_detalles" data-toggle="modal" data-target="#config_suministro">
                                    <i class="fas fa-edit"></i>
                                </button><button type="button" class="btn btn-sm btn-danger eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </center>`,
                            fila[1]
                        ]).draw(false);
                        break;
                    }
                }
            }
        }
    });
}

function editar() {
    $("#caja-cargando").show();
    var nit=$("#nit").val();
    var proveedor=$("#proveedor").val();
    var telefono=$("#telefono").val();
    var correo=$("#correo").val();
    var url=$("#url").val();
    var estado=$("#tipo_user").val()=='ADMINISTRADOR'?$("#estado").val():actual[4];
    
    var productos=[];
    for (var i=0; i<tb_suministra.rows().data().length; i++) {
        var fila=tb_suministra.row(i).data();
        fila=[fila[0],fila[1],fila[2]];
        productos.push(fila);
    }

    var json={
        'nit': actual[0],
        'nuevo_nit': nit,
        'proveedor': proveedor,
        'telefono': telefono,
        'correo': actual[1],
        'nuevo_correo': correo,
        'url': actual[2],
        'nuevo_url': url,
        'estado':estado,
        'productos': productos
    };

    $.ajax({
        type:'post',
        data:json,
        dataType:'json',
        url:base_url('Proveedores_controller/editarProveedor'),
        success:function(respuesta) {
            $("#caja-cargando").hide();
            // verifico la respuesta llegada por clave res.
            if (respuesta[0].res=='invalid') {
                invalidar_campos(respuesta);
                $('#reg_editar_proveedor').animate({scrollTop: 0});

            }else if(respuesta[0]['afectadas'][0]!=0 || respuesta[0]['afectadas'][1]!=0){
                var clase=estado=='a'?'bg-success':'bg-danger';
                estado=estado=='a'?'Activo':'Inactivo';

                actual[3].data({
                    0:nit,
                    1:proveedor,
                    2:telefono,
                    3:correo,
                    4:`<a target="_black" style="color: #3B89EA" href="`+url+`">`+acortar(url)+`</a>`,
                    5:`<center style="border-radius: 5px;" class="font-weight-bold `+clase+`">`+estado+`</center>`,
                    6:`<center>
                        <button class="visualizar btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </button>

                        <button class="editar_proveedor btn btn-sm btn-warning" data-toggle="modal" data-target="#reg_editar_proveedor">
                            <i class="fas fa-edit"></i>
                        </button>
                    </center>`
                }).draw(false);

                //limpio los input de errores
                error=false;

                alerta(respuesta);

                //cierro el modal
                $("#reg_editar_proveedor .close").click();
            }else{
                //cierro el modal
                $("#reg_editar_proveedor .close").click();
            }
        }
    }).fail(function(){
        $("#caja-cargando").hide();
    });    
}

function cancelar() {
    error=false;

    restablecer('nit');
    restablecer('proveedor');
    restablecer('telefono');
    restablecer('correo');
    restablecer('url');

    restablecer_tabla();
}

function ver_proveedor() {
    var foco=$(this).parents('tr');
    var data=tabla.row(foco).data();
    $("#md_visulizar h4").html('Detalle del proveedor: '+data[1]);

    $("#ver_nit").val(data[0]);
    $("#ver_proveedor").val(data[1]);
    $("#ver_telefono").val(data[2]);
    $("#ver_correo").val(data[3]);
    $("#ver_url").html(
        `<a href="`+$(data[4]).text()+`" target="_black">`
        +$(data[4]).text()+`</a>`
    );
    $("#ver_estado").val($(data[5]).text());

    for (var i=0; i<tb_verSuministra.rows().data().length; i=0) {
        tb_verSuministra.row(i).remove().draw(false);
    }

    $.ajax({
        type:'post',
        data:{'nit': data[0]},
        dataType:'json',
        url:base_url('Proveedores_controller/verProveedor'),
        success:function(respuesta) {
            for (var i=0; i<respuesta.length; i++) {
                tb_verSuministra.row.add([
                    respuesta[i].nombre_producto,
                    respuesta[i].precio,
                    respuesta[i].descripcion,
                ]).draw(false);
            } 
        }
    });

    $("#md_visulizar").modal("show");
}

function generar_filtro() {
    var promise=$.Deferred();

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

    filtrartabla(tabla,5,'Activo');
    $("#filtros").append(`<div id="estado_filtrada">
        <div class="card">
            <div class="card-header p-1">
                <h3 class="card-title mr-2">Activo</h3>
                <div class="card-tools mr-1">
                  <div id="eliminar_festado" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </div>
                </div>
            </div>
        </div>
    </div>`);

    $("#eliminar_festado").on('click', function() {
        filtrartabla(tabla,5,'');
        $("#estado_filtrada").remove();
    });

    $("#f_estado a").on("click", function() {
        filtrartabla(tabla,5,$(this).text());
        if ($("#estado_filtrada").length) {
            $("#estado_filtrada h3").html($(this).text());

        }else{
            $("#filtros").append(`<div id="estado_filtrada">
                <div class="card">
                    <div class="card-header p-1">
                        <h3 class="card-title mr-2">`+$(this).text()+`</h3>
                        <div class="card-tools mr-1">
                          <div id="eliminar_festado" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                          </div>
                        </div>
                    </div>
                </div>
            </div>`);

            $("#eliminar_festado").on('click', function() {
                filtrartabla(tabla,5,'');
                $("#estado_filtrada").remove();
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
      title: respuesta[0].mensaje,
      showConfirmButton: true
    }).then((result) => {
        setTimeout(function() {
            $(".sidebar-mini").css('padding-right', '0px');
        },170);
    });
}

function invalidar_campos(respuesta) {
    error=true;
    /*
        en cada condicion consulto si un input o select es ivalido de ser asi:
        - agrego una clase para advertir al usuario que el campo es invalido
        - agrego el error que evita continuar con los procesos normalmente
    */
    if (respuesta[0].nit.length>0) {
        $('#nit').val('');
        $('#nit').addClass('is-invalid');
        $('#error_nit').html(respuesta[0].nit);
    }

    if (respuesta[0].proveedor.length>0) {
        $('#proveedor').val('');
        $('#proveedor').addClass('is-invalid');
        $('#error_proveedor').html(respuesta[0].proveedor);

    }

    if (respuesta[0].telefono.length>0) {
        $('#telefono').val('');
        $('#telefono').addClass('is-invalid');
        $('#error_telefono').html(respuesta[0].telefono);

    }

    if (respuesta[0].correo.length>0) {
        $('#correo').val('');
        $('#correo').addClass('is-invalid');
        $('#error_correo').html(respuesta[0].correo);

    }

    if (respuesta[0].url.length>0) {
        $('#url').val('');
        $('#url').addClass('is-invalid');
        $('#error_url').html(respuesta[0].url);
    }
}

function limpiar() {
    $('#nit').val('');
    $('#proveedor').val('');
    $('#telefono').val('');
    $('#correo').val('');
    $('#url').val('');

    restablecer_tabla();
}

function restablecer_tabla() {
    var data;
    for (var i=0; i<tb_suministra.rows().data().length; i=0) {
        data=tb_suministra.row(i).data();
        tb_suministra.row(i).remove().draw(false);

        tb_inventario.row.add([
            data[0],
            data[4],
            `<center>
                <button type="button" class="btn btn-sm btn-info agregar" data-toggle="modal" data-target="#config_suministro">
                    <i class="fas fa-plus"></i>
                </button>
            </center>`
        ]).draw(false);
    }
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

function pre_agregar() {
    error=false;
    restablecer('precio');
    restablecer('descripcion');

    $("#btn_continuar").show();
    $("#btn_editar_detalle").hide();
    $("#precio").val('');
    $("#descripcion").val('');

    foco=$(this).parents('tr');
    data=tb_inventario.row(foco).data();
}

function agregar() {
    var precio=$("#precio").val();
    var descripcion=$("#descripcion").val();

    if (!precio.length || !descripcion.length) {
        error=true;
        if (!precio.length) {
            $('#precio').addClass('is-invalid');
            $('#error_precio').html('El campo es obligatorio');
        }

        if (!descripcion.length) {
            $('#descripcion').addClass('is-invalid');
            $('#error_des').html('El campo es obligatorio');
        }

    }else{
        tb_inventario.row(foco).remove().draw(false);
        tb_suministra.row.add([
            data[0],
            precio,
            descripcion,
            `<center>
                <button type="button" class="btn btn-sm btn-warning editar_detalles" data-toggle="modal" data-target="#config_suministro">
                    <i class="fas fa-edit"></i>
                </button><button type="button" class="btn btn-sm btn-danger eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </center>`,
            data[1],
        ]).draw(false);

        $("#config_suministro .close").click();
        error=false;
    }
}

function pre_editarDetalle() {
    error=false;
    $("#btn_continuar").hide();
    $("#btn_editar_detalle").show();
    restablecer('precio');
    restablecer('descripcion');

    foco=$(this).parents('tr');
    data=tb_suministra.row(foco).data();
    filaPro=tb_suministra.row(foco);

    $("#precio").val(data[1]);
    $("#descripcion").val(data[2]);
}

function editarDetalle() {
    var precio=$("#precio").val();
    var descripcion=$("#descripcion").val();

    if (!precio.length || !descripcion.length) {
        error=true;
        if (!precio.length) {
            $('#precio').addClass('is-invalid');
            $('#error_precio').html('El campo es obligatorio');
        }

        if (!descripcion.length) {
            $('#descripcion').addClass('is-invalid');
            $('#error_des').html('El campo es obligatorio');
        }

    }else{
        filaPro.data([
            data[0],
            precio,
            descripcion,
            `<center>
                <button type="button" class="btn btn-sm btn-warning editar_detalles" data-toggle="modal" data-target="#config_suministro">
                    <i class="fas fa-edit"></i>
                </button><button type="button" class="btn btn-sm btn-danger eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </center>`,
            data[1],
        ]).draw(false);

        $("#config_suministro .close").click();
        error=false;
    }
}

function eliminar() {
    var foco=$(this).parents('tr');
    var data=tb_suministra.row(foco).data();
   
    tb_suministra.row(foco).remove().draw(false);
    tb_inventario.row.add([
        data[0],
        data[4],
        `<center>
            <button type="button" class="btn btn-sm btn-info agregar" data-toggle="modal" data-target="#config_suministro">
                <i class="fas fa-plus"></i>
            </button>
        </center>`
    ]).draw(false);
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