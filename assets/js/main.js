var error=false;
var idleControl=60000;
$("#caja-cargando").show(); 
$(document).ready(function() {
  //activa la funcion timerIncrement cada minuto para aumentar el 
  //tiempo de inactividad y verificar si la sesion ya expiro
  var idleInterval=setInterval("timerIncrement()", idleControl);
  
  datatable();
});

function timerIncrement() {
  $.ajax({
    dataType:'json',
    url:base_url('Login_controller/consultar_sesion'),
    success:function(respuesta) {
      if (respuesta==null) {
        setTimeout(function() {window.location.href=base_url();},2000);

      }
    }
  });
}

function datatable() {
  $(".desing").DataTable({
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
<<<<<<< HEAD
    "dom":'<"row"<"col-sm-12 col-md-7"<"#filtros.row pl-2"<"#filtro.w-auto mr-3">>><"col-sm-12 col-md-5"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row"<"col-sm-12 col-md-8"i><"col-sm-12 col-md-4 d-flex align-items-center justify-content-center"p>>',
=======
    "dom":'<"row"<"col-5 col-md-7"<"#filtros.row pl-2"<"#filtro.w-auto mr-3">>><"col-4 col-md-5"f>><"row"<"table-responsive p-0"<"col-sm-12 col-md-12"rt>>><"row"<"col-sm-12 col-md-8"i><"col-sm-12 col-md-4 d-flex align-items-center justify-content-center"p>>',
>>>>>>> pedidos
    "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
    "lengthChange": false
  });
};

//eliminar la clase de error del input para mejor ambiente al usuario en caso de diligenciar
//un campo incorrecto
function diseño(buscar) {
  if (buscar!='') {
    if ($("#"+buscar).val().length>=1) {
      $("#"+buscar).removeClass('is-invalid');
    }else {
      $("#"+buscar).addClass('is-invalid');
    }
  }
}

function restablecer(id) {
  $("#"+id).removeClass('is-invalid');
}
// Funcion para limpiar las vocales de sus caracteres especiales.
function limpiarVocales(nombre){   
  nombre=String(nombre);
  nombre=nombre.trim();
  nombre=nombre.toLowerCase();
  nombre=nombre.replace(/á/g,'a');
  nombre=nombre.replace(/ä/g,'a');
  nombre=nombre.replace(/é/g,'e');
  nombre=nombre.replace(/ë/g,'e');
  nombre=nombre.replace(/í/g,'i');  
  nombre=nombre.replace(/ï/g,'i');  
  nombre=nombre.replace(/ó/g,'o'); 
  nombre=nombre.replace(/ö/g,'o');      
  nombre=nombre.replace(/ú/g,'u');
  nombre=nombre.replace(/ü/g,'u');
  return nombre;
}
// Función destinada a la limpieza de todos los de la tabla sin destruirla.
function limpiarTabla(tabla){
  tabla.clear().draw();
}

/* BuscarEnColumna és una función que ayudará a buscar cualquier información dentro de la columna que desee
 Siempre y cuando este dato no se encuentre dentro de un contenedor, a menos que coloque el contenedor completo.
 La funcion recibe como parametros 
 Obligatorios:
 -Tabla: La tabla como Objeto DataTable
 -Nombre: El texto que quiere comparar 
 Opcionales:
 -Columna: El numero de la columna de izquierda a derecha empezando desde el cero(0). Por defecto 
  es cero(0) donde utiliza la primera columna.
 -Opcion: El numero de datos, es un numero entero, que se les tienen permitido coincidir. Por defecto 
  es cero(0) donde solo la primera coincidencia detiene la funcion
*/
function buscarEnColumna(tabla,nombre,columna=0,opcion=0){
  var respuesta=true; 
  var cont= 0;
  tabla.rows().every(function(){    
    rowData=this.data();
    cellName=rowData[columna];        
    if (limpiarVocales(cellName)== limpiarVocales(nombre)) {      
      if (opcion!=0) {
        if (cont>=opcion) {
          respuesta=false;                    
          return respuesta;
        }
        cont++;
      }else{
        respuesta=false;    
        return respuesta;
      }
      
    }                     
  });
  return respuesta;
}
// Funcion para rearmar la tabla por lo que buscas en la columna. Si no encuentra nada mostrará cero resultados.
// Recibe como parametros.
// Obligatorios:
// -Tabla: la tabla como objeto DataTable
// -Columna: El numero de la columna de izquierda a derecha empezando desde el cero(0). puede recibir 
//       arreglo de columnas ej: [0,3,5]
// Opcional: 
// Texto: Recibe el texto exacto que quiere encontrar en la columna/s. Por defecto se encuentra vacío 
//      mostrando todos los datos que se encuentran en el objeto DataTable
function filtrartabla(tabla,columna,texto=''){  
  if (Array.isArray(columna)) {
    tabla.columns(columna).every(function(){    
      var column=this;
      if (texto==='') {
        column.search('').draw(false); 
      }else{
        column.search('^'+texto+'$',true,false).draw(false);
      }         
      
    }); 
  }else{
    tabla.column(columna).each(function(){    
      var column=this;
      if (texto==='') {
        column.search('').draw(false); 
      }else{
        column.search('^'+texto+'$',true,false).draw(false);
      }         
      
    }); 
  }  
}
// Funcion para poder ocultar o visualizar las columnas dentro de un datatable
// Recibe como parametros:
// Obligatorio:
//  tabla - Como objeto DataTable.
// Opcionales:
//  columna - El numero de la columna que quiere manipular empezando desde el cero de izquierda a derecha.
//             Puede recibir un numero entero que no sobrepase el numero máximo de columnas en la tabla o arreglo de numeros, como también un String identificador
//             ID o una clase ej: 1,[1,2,4],"#mi_columna",".columnas_ocultas".
//  visible - Es un boolean para conocer que acción quiere hacer. Si es true la columna será visible.
//            Si es false la columna estará oculta. Por defecto es true.
function visualizarColumnaEntabla(tabla,columna=0,visible=true){  
  if (Array.isArray(columna)) {
    tabla.columns(columna).visible(visible,false);
  }else{
    tabla.column(columna).visible(visible);
  }
  tabla.columns.adjust().draw( false );    
}

