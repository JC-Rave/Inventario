$(document).ready(iniciar);
var tabla;
var desde;
var hasta;

function iniciar(){
    desde='';
    hasta='';
    traducir();
    tabla=$("#tb_historial").DataTable();

    config_tabla();
    $.when(generar_filtro()).then(function () {
        window.setTimeout(function() {
            $("#caja-cargando").hide();
        },1300);
    });
}

function generar_filtro() {
    filtrartabla(tabla,0,$("#loggedUser").val());
    var filtro=`<ul class="mb-0 pl-0" style="list-style-type:none">
        <li>
            <button href="#" type="button" id="dropdown" data-toggle="dropdown" 
            class="p-2 m-0 btn btn-primary dropdown-toggle multi-level-dropdown" 
            aria-haspopup="true" aria-expanded="false">Filtrar</button>
            <ul style="list-style-type:none" class="dropdown-menu mt-2 rounded-0 white border-0 z-depth-1">`;

    $.ajax({
        type:'post',
        dataType:'json',
        url:base_url('Historial_controller'),
        success:function(respuesta) {
            filtro+=`<li class="dropdown-item dropdown-submenu p-0">
                        <a href="#" data-toggle="dropdown" 
                        class="dropdown-toggle dropdown-item w-100">Fecha</a>
                        <ul id="f_fecha" class="dropdown-menu mr-2 rounded-0  
                        white border-0 z-depth-1 r-100">
                            <li class="dropdown-item p-0">
                                <a href="#" class="dropdown-item w-100" data-tiempo="7">Ultimos 7 días</a>
                            </li>
                            <li class="dropdown-item p-0">
                                <a href="#" class="dropdown-item w-100" data-tiempo="30">Ultimos 30 días</a>
                            </li>
                            <li class="dropdown-item p-0">
                                <a href="#" class="dropdown-item w-100" data-tiempo="actual">Este mes</a>
                            </li>
                            <li class="dropdown-item p-0">
                                <a href="#" class="dropdown-item w-100" data-tiempo="pasado">Mes pasado</a>
                            </li>
                            <li class="dropdown-item p-0">
                                <a href="#" class="dropdown-item w-100" data-tiempo="">Escoger rango</a>
                            </li>
                        </ul>
                    </li>`;

            if (respuesta['usuarios'].length) {
                filtro+=`<li class="dropdown-item dropdown-submenu p-0">
                    <a href="#" data-toggle="dropdown" 
                    class="dropdown-toggle dropdown-item w-100">Instructores</a>
                    <ul id="f_instru" class="dropdown-menu mr-2 rounded-0  white border-0 z-depth-1 r-100">`;

                for (var i=0; i<respuesta['usuarios'].length; i++) {
                    filtro+=`<li class="dropdown-item p-0">
                            <a href="#" class="dropdown-item w-100" data-usuario="`+respuesta['usuarios'][i].id_usuario+`">`+respuesta['usuarios'][i].nombre_persona+` `+respuesta['usuarios'][i].apellido_persona+`</a>
                    </li>`;
                }

                filtro+=`</ul></li>`;
            }

            filtro+=`</ul></li></ul>`;       

            $("#filtro").html(filtro);

            $("#f_fecha a").on("click", function() {
                if ($(this).data('tiempo')=='7' || $(this).data('tiempo')=='30') {
                    desde=moment().subtract($(this).data('tiempo'), 'days').format('YYYY/MM/DD');
                    hasta=moment().format('YYYY/MM/DD');

                    tabla.draw();
                }else if ($(this).data('tiempo')=='actual') {
                    desde=moment().startOf('month').format('YYYY/MM/DD');
                    hasta=moment().endOf('month').format('YYYY/MM/DD');

                    tabla.draw();
                }else if($(this).data('tiempo')=='pasado'){
                    desde=moment().subtract(1, 'month').startOf('month').format('YYYY/MM/DD');
                    hasta=moment().subtract(1, 'month').endOf('month').format('YYYY/MM/DD');

                    tabla.draw();
                }

                if ($(this).data('tiempo')!='') {
                    if ($("#fecha_filtrada").length && !$("#fecha_filtrada .input-group").length) {
                        $("#fecha_filtrada h3").html($(this).text()+': '+moment(desde, 'YYYY/MM/DD').format('DD/MM/YYYY')+' - '+moment(hasta, 'YYYY/MM/DD').format('DD/MM/YYYY'));

                    }else{
                        $("#fecha_filtrada").remove();

                        $("#filtros").append(`<div id="fecha_filtrada" class="order-1 pl-1 pr-1">
                            <div class="card">
                                <div class="card-header p-1">
                                    <h3 class="card-title mr-1">`+$(this).text()+': '+moment(desde, 'YYYY/MM/DD').format('DD/MM/YYYY')+' - '+moment(hasta, 'YYYY/MM/DD').format('DD/MM/YYYY')+`</h3>
                                    <div class="card-tools ml-1 mr-1">
                                      <div id="eliminar_fFecha" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>`);

                        $("#eliminar_fFecha").on('click', function() {
                            $("#fecha_filtrada").remove();
                            hasta='';
                            desde='';
                            tabla.draw();
                        });
                    }

                }else{
                    $("#fecha_filtrada").remove();

                    $("#filtros").append(`<div id="fecha_filtrada" class="order-1 pl-1 pr-1">
                        <div class="card">
                            <div class="card-header p-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="rango_fechas">
                                    <div class="card-tools ml-2 mr-1">
                                      <div id="eliminar_fFecha" type="button" class="m-0 p-0 btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`);

                    $('#rango_fechas').daterangepicker({
                        autoApply: true,
                        locale:{
                            format: 'DD/MM/YYYY',
                            separator: ' - ',
                            applyLabel: 'Aplicar',
                            cancelLabel: 'Cancelar',
                            fromLabel: 'De',
                            toLabel: 'a',
                            customRangeLabel: 'custom',
                            weekLabel: 'W',
                            daysOfWeek:[
                                'Dom.',
                                'Lun.',
                                'Mar.',
                                'Mie.',
                                'Jue.',
                                'Vie.',
                                'Sáb.'
                            ],
                            monthNames:[
                                'Enero',
                                'Febrero',
                                'Marzo',
                                'Abril',
                                'Mayo',
                                'Junio',
                                'Julio',
                                'Agosto',
                                'Septiembre',
                                'Octubre',
                                'Noviembre',
                                'Diciembre'
                            ],
                            firstDay: '1'
                        },
                        startDate: moment().startOf('month'),
                        endDate  : moment()
                    }, function (start, end, label) {
                        desde=start.format('YYYY/MM/DD');
                        hasta=end.format('YYYY/MM/DD');
                        tabla.draw();
                    });

                    $("#eliminar_fFecha").on('click', function() {
                        $("#fecha_filtrada").remove();
                        hasta='';
                        desde='';
                        tabla.draw();
                    });

                    desde=moment().startOf('month').format('YYYY/MM/DD');
                    hasta=moment().format('YYYY/MM/DD');
                    tabla.draw();
                }
            });

            $("#f_instru a").on("click", function() {
                filtrartabla(tabla,0,$(this).data('usuario'));
                if ($("#instru_filtrada").length) {
                    $("#instru_filtrada h3").html($(this).text());

                }else{
                    $("#filtros").append(`<div id="instru_filtrada" class="order-2 pl-1 pr-1">
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
                        filtrartabla(tabla,0,$("#loggedUser").val());
                        $("#instru_filtrada").remove();
                    });
                }
            });
        }
    });
}

function config_tabla() {
    visualizarColumnaEntabla(tabla,0,false);
    $("#tb_historial").attr('style', 'width:100% !important;');

    $.fn.dataTable.ext.search.push(function (config, data, dataIndex) {
        var fecha=moment(data[1], 'DD/MM/YYYY').format('YYYY/MM/DD');
        if ((desde=='' && hasta=='') || moment(fecha).isSameOrAfter(desde) && moment(fecha).isSameOrBefore(hasta)) {
            return true;
        }else{
            return false;
        }
    });
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
        "dom":'<"row"<"col-5 col-md-9"<"#filtros.row"<"#filtro.w-auto ml-2 mr-3">>><"col-4 col-md-3"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        "lengthMenu": [ [4, 25, 50, -1], [4, 25, 50, "All"] ],
        "lengthChange": false,
        "order":[[1, "asc"]]
    });
}


