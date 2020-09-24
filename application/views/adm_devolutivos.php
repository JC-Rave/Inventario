<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2"> 
      <div class="col-sm-6 col-6"> 
        <h1 class="m-0 text-dark">Materiales Devolutivos</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <button id="btnAdd" type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#modal-add-devolutivo">Registrar Devolutivo</button>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<input id="base_url_dev" type="hidden" name="" value="<?php echo base_url() ?>">
<input id="consult_dev" type="hidden" name="" value="<?php echo base_url('Devolutivos_controller/consultarDevolutivos') ?>">
<input id="consult_cat" type="hidden" name="" value="<?php echo base_url('Categorias_controller/consultarCategorias') ?>">
<input id="consult_users" type="hidden" name="" value="<?php echo base_url('Accesos/consultarUsuarios') ?>">
<input id="consult_und" type="hidden" name="" value="<?php echo base_url('index.php/Unidad_controller/consultarUnidades') ?>">
<input id="consult_lin" type="hidden" name="" value="<?php echo base_url('index.php/Lineas_controller/consultarLineas') ?>">
<input id="consult_est" type="hidden" name="" value="<?php echo base_url('index.php/Estados_controller/consultarEstados') ?>">
<input id="anular_mant" type="hidden" name="" value="<?php echo base_url('index.php/Devolutivos_controller/anularMantenimiento')?>">
<!-- Usuario -->
<input id="id_usuario" type="hidden" name="" value="<?php echo $this->session->documento?>">
<input id="nombre_usuario" type="hidden" name="" value="<?php echo $this->session->nombre." ".$this->session->apellido?>">
<input id="tipo_usuario" type="hidden" name="" value="<?php echo $this->session->tipo_usuario?>">
<input id="linea_usuario" type="hidden" name="" value="<?php echo $this->session->linea?>" >
<input id="fecha-actual" type="hidden" name="" value="<?php echo date('Y').'-'.date('m').'-'.date('d')?>">




<!-- Main content -->
<section class="content">
  <div class="container-fluid">
  	<!-- Main row -->
    <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row justify-content-end align-items-center mb-3">
              <input type="checkbox" class="" id="edicion_avanzada" style="display: none;">
              <label for="edicion_avanzada" id="label_edicion_avanzada" class="btn p-2 card-outline-primary"><i class="fas fa-tools"></i></label>
            </div>
            <div class="">
              <table id="table-devolutivos" class="desing table table-bordered table-hover text-wrap">
                <thead>
                  <tr>
                    <th>Placa</th><!-- 0 -->
                    <th>Codigo</th><!-- 1 -->                    
                    <th>serial</th><!-- 2 -->                    
                    <th>Imagen</th><!-- 3 --> 
                    <th>Nombre</th><!-- 4 -->
                    <th>Unidad</th><!-- 5 -->
                    <th>Categoria</th><!-- 6 -->
                    <th>Linea</th><!-- 7 -->
                    <th>Estado</th><!-- 8 -->                    
                    <th>IdUsuario</th><!-- 9 -->       
                    <th>Acciones</th><!-- 10 -->                    
                    <th>Precio</th><!-- 11 -->
                    <th>N°</th><!-- 12 -->
                    <th>Descripcion</th><!-- 13 -->
                    <th>idUnidad</th><!-- 14 -->
                    <th>idCategoria</th><!-- 15 -->
                    <th>idLinea</th><!-- 16 -->
                    <th>idEstado</th><!-- 17 -->
                    <th>Error</th><!-- 18 -->
                  </tr>
                </thead>
                <tbody id="tbody_devolutivos">                        
                  </tbody>
                <tfoot>
                  <tr>                    
                    <th>Placa</th><!-- 0 -->
                    <th>Codigo</th><!-- 1 -->                    
                    <th>serial</th><!-- 2 -->                    
                    <th>Imagen</th><!-- 3 --> 
                    <th>Nombre</th><!-- 4 -->
                    <th>Unidad</th><!-- 5 -->
                    <th>Categoria</th><!-- 6 -->
                    <th>Linea</th><!-- 7 -->
                    <th>Estado</th><!-- 8 -->                    
                    <th>IdUsuario</th><!-- 9 -->       
                    <th>Acciones</th><!-- 10 -->                    
                    <th>Precio</th><!-- 11 -->
                    <th>N°</th><!-- 12 -->
                    <th>Descripcion</th><!-- 13 -->
                    <th>idUnidad</th><!-- 14 -->
                    <th>idCategoria</th><!-- 15 -->
                    <th>idLinea</th><!-- 16 -->
                    <th>idEstado</th><!-- 17 -->
                    <th>Error</th><!-- 18 -->
                  </tr>
                </tfoot>
              </table>
            </div>                             
          </div>
        </div>
      </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>

<!-- modal Agregar devolutivo-->
<div class="modal fade bd-example-modal-lg" id="modal-add-devolutivo">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar Devolutivo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="form" accept-charset="UTF-8" id="form_add_dev" action="<?php echo base_url('Devolutivos_controller/agregarDevolutivo');?>" method="POST" enctype="multipart/form-data">                    
        <div class="modal-body">
          <div class="card-body">
            <div class="row">
               <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label for="nombre_add_dev">Nombre*:</label>
                    <input type="texto" class="form-control" id="nombre_add_dev" name="nombre_add_dev" placeholder="" required pattern="[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+">
                  </div>     
               </div>   
               <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label for="placa_add_dev">Placa:</label>
                    <input type="number" class="form-control" id="placa_add_dev" name="placa_add_dev" placeholder="">
                  </div>     
               </div>               
            </div>
            <div class="row">
               <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label for="csena_add_dev">Codigo Sena:</label>
                    <input type="number" class="form-control" id="csena_add_dev" name="csena_add_dev" placeholder="">
                  </div>     
               </div>   
               <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label for="serial_add_dev">Serial:</label>
                    <input type="text" class="form-control" id="serial_add_dev" name="serial_add_dev" placeholder="" pattern="[A-Za-z1-9]+">
                  </div>     
               </div>               
            </div>                      
            <div class="row">
               <div class="col-md-6 col-sm-12">
                <div class="form-group">
                   <label for="categoria_add_dev">Categoria*:</label>
                   <select name="categoria_add_dev" id="categoria_add_dev" class="form-control " placeholder="Categoría" required>
                   </select>                                                                   
                </div>                               
               </div> 
               <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label for="linea_add_dev">Linea*:</label>
                    <select name="linea_add_dev" id="linea_add_dev" class="form-control">
                    </select>
                  </div>     
               </div>                 
            </div>
            <div class="row">
               <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label for="precio_add_dev">Precio:</label>
                    <input type="number" class="form-control" id="precio_add_dev" name="precio_add_dev">
                  </div>     
               </div>
               <div class="col-md-6 col-sm-12">
                  <div class="form-group col">
                    <label for="estado_add_dev">Estado*:</label>
                    <select name="estado_add_dev" id="estado_add_dev" class="form-control" required placeholder="estado">
                    </select>
                  </div>                               
               </div>                  
            </div>                  
            <div class="row">
               <div class="col-md-6 col-sm-12">                    
                  <div class="row">                    
                    <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                         <label for="unidad_add_dev">Unidad de Medida*</label>
                         <select name="unidad_add_dev" id="unidad_add_dev" class="form-control" placeholder="Unidad" required>
                         </select>                          
                       </div>     
                    </div>
                    <div class="col-md-12 col-sm-12">
                       <div class="form-group">
                         <label for="cant_add_dev">Cantidad(Opcional)</label>
                         <input type="number" class="form-control" id="cant_add_dev" name="cant_add_dev" min="1" value="1" max="90" placeholder="Max. 90">
                       </div>     
                    </div>                           
                    <div class="col-md-12 col-sm-12">
                      <div class="form-group pb-0">
                        <label for="descripcion_add_dev">Descripción*:</label>
                        <textarea class="form-control" id="descripcion_add_dev" name="descripcion_add_dev" required pattern="[A-Za-z0-9]+" <?php if ($this->session->tipo_usuario==="INSTRUCTOR") {echo "style='height: 210px;'";} ?>></textarea>                      
                      </div>     
                    </div>    
                  </div>                   
               </div> 
               <div class="col-md-6 col-sm-12">
                <?php if ($this->session->tipo_usuario==="ADMINISTRADOR") { ?>
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                         <label for="usuario_add_dev">Usuario (Opcional)</label>
                         <select name="usuario_add_dev" id="usuario_add_dev" class="form-control" placeholder="Usuario">
                         </select>                          
                       </div>     
                    </div>
                  </div>
                <?php }?>
                  <div class="row pr-0 pb-2 col-12" >
                    <div class="row pr-0 pb-2 col-12">
                      <div class="col-12 pr-0">
                        <figure class="border col-12 d-flex justify-content-center pl-5 pr-5" id="add_img_dev">
                            <img class="img-fluid img" src="<?=base_url('assets/img/sinFoto.png')?>" alt="" id="file_img_add">
                        </figure>
                        <select id="select_imagen_add_dev" name="select_imagen_add_dev"class="imagen-select label-input-img w-100">
                          <option data-imagesrc="<?= base_url('assets/img/sinFoto.png') ?>" value="" selected="selected">Seleccionar Imagen</option>
                        </select>
                      </div> 
                    </div>                  
                  </div>
               </div>                 
            </div>                                                                      
          </div>
        </div>                    
        <div class="modal-footer justify-content-between">
          <div class="col-md-7 col-sm-12">
            <p>En esta sección podrás agregar nuevos materiales devolutivos al inventario. <br> <strong>Nota: Los campos con asterisco (*) son obligatorios.</strong></p>
          </div>
          <div>
            <button type="button" id="btn-cancelar_add" class="btn btn-default btn-cerrar" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="btn-guardar_add" class="btn btn-primary float-right">Guardar</button>
          </div>            
        </div>
      </form>      
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- modal Editar devolutivo-->
<div class="modal fade bd-example-modal-lg" id="modal-edit-devolutivo">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Editar Devolutivo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="form" id="form_edit_dev" action="<?php echo base_url('index.php/Devolutivos_controller/modificarDevolutivo');?>" method="POST">
        <div class="modal-body">
            <div class="card-body">
            <div class="col-12" id="cant-devs" style="display: none;">
                <div class="row">
                  <h3>Devolutivos</h3>
                </div>
                <table id="tab-cant-devs" class="table table-responsive table-bordered w-100" style="max-height: 400px;overflow: scroll;">
                  <thead class="col-12">
                    <tr class="col-12">
                      <th class="text-center text-nowrap">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="todos_dev">
                          <label for="todos_dev" class="custom-control-label">Todos</label>
                        </div>
                      </th>
                      <th class="col text-wrap">Devolutivo</th>
                      <th class="col text-center">Placa</th>
                      <th class="col text-center">Serial</th>
                      <th class="col text-center">Codigo</th>
                      <th class="col text-center">Linea</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
              </div>
              <div class="row">
                 <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="nombre_edit_dev">Nombre*:</label>
                      <input type="texto" class="form-control" id="nombre_edit_dev" name="nombre_edit_dev" placeholder="" required pattern="[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ'\s]+">
                    </div>     
                 </div>   
                 <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="placa_edit_dev" id="label_placa_edit_dev">Placa:</label>
                      <input type="number" class="form-control" id="placa_edit_dev" name="placa_edit_dev" placeholder="">
                      <label for="cantEdit_edit_dev" id="label_cantEdit_edit_dev" style="display: none;">Cantidad:</label>
                      <input type="number" class="form-control" id="cantEdit_edit_dev" name="cantEdit_edit_dev" style="display: none;" value="0" min="0" readonly="true">
                    </div>                        
                 </div>               
              </div>
              <div class="row">
                 <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="csena_edit_dev" id="label_csena_edit_dev">Codigo Sena:</label>
                      <input type="number" class="form-control" id="csena_edit_dev" name="csena_edit_dev" placeholder="">
                    </div>     
                 </div>   
                 <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="serial_edit_dev" id="label_serial_edit_dev">Serial:</label>
                      <input type="text" class="form-control" id="serial_edit_dev" name="serial_edit_dev" placeholder="" pattern="[A-Za-z1-9]+">
                    </div>     
                 </div>               
              </div>                      
              <div class="row">
                 <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                       <label for="categoria_edit_dev">Categoria*:</label>
                       <select name="categoria_edit_dev" id="categoria_edit_dev" class="form-control " placeholder="Categoría" required>
                       </select>                                                                   
                    </div>                            
                 </div> 
                 <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="linea_edit_dev">Linea*:</label>
                      <select name="linea_edit_dev" id="linea_edit_dev" class="form-control" required>
                      </select>
                    </div>     
                 </div>                 
              </div>
              <div class="row">
                 <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="precio_edit_dev">Precio:</label>
                      <input type="number" class="form-control" id="precio_edit_dev" name="precio_edit_dev" placeholder="$#####">
                    </div>     
                 </div>
                 <div class="col-md-6 col-sm-12">
                    <div class="form-group col">
                      <label for="estado_edit_dev">Estado*:</label>
                      <select name="estado_edit_dev" id="estado_edit_dev" class="form-control" required placeholder="estado">
                      </select>
                    </div>                               
                 </div>                  
              </div>                  
              <div class="row">
                 <div class="col-md-6 col-sm-12">                    
                    <div class="row">                    
                      <div class="col-md-12 col-sm-12">
                         <div class="form-group">
                           <label for="unidad_edit_dev">Unidad de Medida*</label>
                           <select name="unidad_edit_dev" id="unidad_edit_dev" class="form-control" placeholder="Unidad" required>
                           </select>                          
                         </div>     
                      </div>                           
                      <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                          <label for="descripcion_edit_dev">Descripción*:</label>
                          <textarea class="form-control" id="descripcion_edit_dev" name="descripcion_edit_dev" required pattern="[A-Za-z0-9]+" <?php if ($this->session->tipo_usuario==="INSTRUCTOR") {echo "style='height: 210px;'";} ?>></textarea>                      
                        </div>
                      </div>    
                    </div>                   
                 </div> 
                 <div class="col-sm-12 col-md-6">
                    <?php if ($this->session->tipo_usuario==="ADMINISTRADOR") { ?>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label for="usuario_edit_dev">Usuario (Opcional)</label>
                          <select name="usuario_edit_dev" id="usuario_edit_dev" class="form-control" placeholder="Unidad">
                          </select>                          
                       </div>     
                      </div>
                    </div>
                    <?php } ?>
                    <div class="row align-items-end pr-0 pb-2 col-12" <?php if ($this->session->tipo_usuario==="INSTRUCTOR") {echo "style='height: 100%;'";} ?>>
                      <div class="col-12">
                        <figure class="border col-12 d-flex justify-content-center pl-5 pr-5" id="edit_img_dev">                            
                            <img class="img-fluid img" src="<?=base_url('assets/img/sinFoto.png')?>" alt="" id="file_img_edit">
                        </figure>
                        <select id="select_imagen_edit_dev" name="select_imagen_edit_dev"class="imagen-select label-input-img w-100" multiple="">
                        </select>
                        <input type="hidden"  name="imagenVieja_edit_dev" id="imagenVieja_edit_dev">
                      </div> 
                    </div>                  
                 </div>                 
              </div>                                                                      
            </div>
        </div>
        <div class="modal-footer justify-content-between">          
          <div class="col-md-7 col-sm-12">
            <p>En esta sección podrás editar los materiales devolutivos del inventario. <br> <strong>Nota: Los campos con asterisco (*) son obligatorios.</strong></p>
          </div>
          <div class="col-md-5 col-sm-12">
            <button type="button" id="btn-cancelar_edit" class="btn btn-default btn-cerrar" data-dismiss="modal">Cancelar</button>
            <button id="btn-guardar_edit" type="submit" class="btn btn-primary float-right">Editar</button>
          </div>            
        </div>  
      </form>      
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
  </div>
</div>
<!-- modal Visual devolutivo-->
<div class="modal fade bd-example-modal-lg" id="modal-vis-devolutivo">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Visualizar Devolutivo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div  id="form_vis_dev" >
        <div class="modal-body">
            <div class="col-12 d-flex align-items-center justify-content-between">
              <input type="hidden" id="input-mantenimiento" value="<?php echo base_url('index.php/Devolutivos_controller/consultarMantenimientos') ?>">
              <button type="button" class="btn btn-primary pl-3 pr-3" id="btn-proveedores-vis" value=""><i class="fas fa-user-tie pr-3"></i>Proveedores</button> 
              <div id="btn-Provdetalles-vis">
                <span>Detalles producto</span><button type="button"><i class="fas fa-angle-right"></i></button>
              </div>
              <button type="button" class="btn btn-primary pl-3 pr-3" id="btn-mantenimiento-vis" value="">Mantenimiento <i class="fas fa-cog pl-3"></i></button> 
              <div id="btn-detalles-vis">
                <button type="button" class=""><i class="fas fa-angle-left"></i></button><span>Detalles devolutivo</span>
              </div>
              <div id="btn-agregarMant-vis">
                <span>Nuevo mantenimiento</span><button type="button">+</button>
              </div>
              <div id="btn-agregarMantCancel-vis">
                <span>Cancelar</span><button type="button">+</button>
              </div>
            </div>
            <div class="col-12 contenedor-slide">
              <div class="card-body elemento-noprecionado" id="contenedor-slide1">
                <div id="slide1-elemento1">
                  <table id="table-mantenimiento-dev" class="desing2 table table-bordered table-hover text-wrap" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th>Registrado</th><!-- 0 -->
                        <th>Mantenimiento</th><!-- 1 -->
                        <th>Inicio</th><!-- 2 -->
                        <th>Finaliza</th><!-- 3 -->
                        <th>Estado</th><!-- 4 -->
                        <th>Acciones</th><!-- 5 -->
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Registrado</th><!-- 0 -->
                        <th>Mantenimiento</th><!-- 1 -->
                        <th>Inicio</th><!-- 2 -->
                        <th>Finaliza</th><!-- 3 -->
                        <th>Estado</th><!-- 4 -->
                        <th>Acciones</th><!-- 5 -->
                      </tr>
                    </tfoot>
                    
                  </table>
                </div>
                <div id="slide1-elemento2">
                  <form role="form" action="<?php echo base_url('index.php/Devolutivos_controller/agregarMantenimientoDevolutivo') ?>" method="post" id="form_add_mant" >
                    <h3 class="ml-1 mb-5">Registar mantenimiento</h3>
                    <div class="ml-3 mb-5 pl-4 custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="rango-add-fecha">
                      <label for="rango-add-fecha" class="custom-control-label">Rango de fecha</label>
                    </div>
                    <div class="row justify-content-center">
                      <div class="form-group col-md-3 col-sm-12">
                        <label class="row">Fecha:</label>
                        <input type="date" name="date-add-ini" class="form-control" id="date-add-ini" required>
                      </div>
                      <div class="form-group col-md-3 col-sm-12" id="caja-date-add-fin">
                        <label class="row">Fecha de fin:</label>
                        <input type="date" name="date-add-fin" class="form-control" id="date-add-fin">
                      </div>
                      <div class="form-group col-md-3 col-sm-12">
                        <label class="row">Mantenimiento:</label>
                        <select class="form-control" id="mant-add-select" required>
                          <option value="Correctivo">Correctivo</option>
                          <option value="Preventivo">Preventivo</option>
                        </select>
                      </div>
                      <div class="form-group col-md-3 col-sm-12">
                        <label class="row">Estado:</label>
                        <select class="form-control" id="est-add-select" required>
                          <option value="Terminado">Terminado</option>
                          <option value="Vigente">Vigente</option>
                          <option value="En proceso">En proceso</option>
                        </select>
                      </div>
                      <div class="col-12 d-flex justify-content-end mt-3">
                        <button type="submit" id="btn_add_mant" class="btn btn-success mr-3 p-2">Guardar</button>
                      </div>
                    </div>
                  </form>
                  <form action="<?php echo base_url('Devolutivos_controller/editarMantenimientoDevolutivo') ?>" method="post" id="form_edit_mant" style="display: none;">
                    <h3 class="ml-3 mb-5">Editar mantenimiento</h3>
                    <div class="ml-5 mb-5 pl-4 custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="rango-edit-fecha">
                      <label for="rango-edit-fecha" class="custom-control-label">Rango de fecha</label>
                    </div>
                    <input type="hidden" id="registrado_mant" name="registrado_mant">
                    <div class="row justify-content-center">
                      <div class="form-group col-md-3 col-sm-12">
                        <label class="row">Fecha:</label>
                        <input type="date" name="date-edit-ini" class="form-control" id="date-edit-ini" required>
                      </div>
                      <div class="form-group col-md-3 col-sm-12" id="caja-date-edit-fin">
                        <label class="row">Fecha de fin:</label>
                        <input type="date" name="date-edit-fin" class="form-control" id="date-edit-fin">
                      </div>
                      <div class="form-group col-md-3 col-sm-12">
                        <label class="row">Mantenimiento:</label>
                        <select class="form-control" id="mant-edit-select" required>
                          <option value="Correctivo">Correctivo</option>
                          <option value="Preventivo">Preventivo</option>
                        </select>
                      </div>
                      <div class="form-group col-md-3 col-sm-12">
                        <label class="row">Estado:</label>
                        <select class="form-control" id="est-edit-select"  required>
                          <option value="Terminado">Terminado</option>
                          <option value="Vigente">Vigente</option>
                          <option value="En proceso">En proceso</option>
                        </select>
                      </div>
                      <div class="col-12 d-flex justify-content-end mt-3 mr-4 pr-5">
                        <button type="submit" class="btn btn-success mr-5 p-2">Editar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card-body col-12 segundo-elemento-noprecionado" id="contenedor-slide2">
                <div class="row">
                   <div class="col-md-6 col-sm-12">
                      <div class="form-group">
                        <label for="nombre_vis_dev">Nombre*:</label>
                        <input type="texto" class="form-control" id="nombre_vis_dev" name="nombre_vis_dev" placeholder="" required pattern="[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" disabled readonly>
                      </div>     
                   </div>   
                   <div class="col-md-6 col-sm-12">
                      <div class="form-group">
                        <label for="placa_vis_dev">Placa:</label>
                        <input type="number" class="form-control" id="placa_vis_dev" name="placa_vis_dev" placeholder="" disabled readonly>
                      </div>     
                   </div>               
                </div>
                <div class="row">
                   <div class="col-md-6 col-sm-12">
                      <div class="form-group">
                        <label for="csena_vis_dev">Codigo Sena:</label>
                        <input type="number" class="form-control" id="csena_vis_dev" name="csena_vis_dev" placeholder="" disabled readonly>
                      </div>     
                   </div>   
                   <div class="col-md-6 col-sm-12">
                      <div class="form-group">
                        <label for="serial_vis_dev">Serial:</label>
                        <input type="text" class="form-control" id="serial_vis_dev" name="serial_vis_dev" placeholder="" pattern="[A-Za-z1-9]+" disabled readonly>
                      </div>     
                   </div>               
                </div>                      
                <div class="row">
                   <div class="col-md-6 col-sm-12">
                      <div class="form-group">
                         <label for="categoria_vis_dev">Categoria*:</label>
                         <select name="categoria_vis_dev" id="categoria_vis_dev" class="form-control " placeholder="Categoría" required disabled readonly>
                         </select>                                                                   
                      </div>                               
                   </div> 
                   <div class="col-md-6 col-sm-12">
                      <div class="form-group">
                        <label for="linea_vis_dev">Linea*:</label>
                        <select name="linea_vis_dev" id="linea_vis_dev" class="form-control" required disabled readonly>
                        </select>
                      </div>     
                   </div>                 
                </div>
                <div class="row">
                   <div class="col-md-6 col-sm-12">
                      <div class="form-group">
                        <label for="precio_vis_dev">Precio:</label>
                        <input type="number" class="form-control" id="precio_vis_dev" name="precio_vis_dev" required min="50" disabled readonly>
                      </div>     
                   </div>
                   <div class="col-md-6 col-sm-12">
                      <div class="form-group col">
                        <label for="estado_vis_dev">Estado*:</label>
                        <select name="estado_vis_dev" id="estado_vis_dev" class="form-control" required placeholder="estado" disabled readonly>
                        </select>
                      </div>                               
                   </div>                  
                </div>                  
                <div class="row">
                   <div class="col-md-6 col-sm-12">                    
                      <div class="row">                    
                        <div class="col-md-12 col-sm-12">
                           <div class="form-group">
                             <label for="unidad_vis_dev">Unidad de Medida*</label>
                             <select name="unidad_vis_dev" id="unidad_vis_dev" class="form-control" placeholder="Unidad" required disabled readonly>
                             </select>                          
                           </div>     
                        </div>                           
                        <div class="col-md-12 col-sm-12">
                          <div class="form-group">
                            <label for="descripcion_vis_dev">Descripción*:</label>
                            <textarea class="form-control" id="descripcion_vis_dev" name="descripcion_vis_dev" required pattern="[A-Za-z0-9]+" readonly></textarea>                      
                          </div>     
                        </div>    
                      </div>
                   </div> 
                   <div class="col-md-6 col-sm-12">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label for="usuario_vis_dev">Usuario:</label>
                            <select name="usuario_vis_dev" id="usuario_vis_dev" class="form-control" placeholder="Unidad" readonly disabled="">
                            </select>                          
                         </div>     
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <figure class="border col-12 d-flex justify-content-center pl-5 pr-5" id="vis_img_dev">                            
                              <img class="img-fluid img" src="<?=base_url('assets/img/sinFoto.png')?>" alt="" id="file_img_vis">
                          </figure>
                        </div> 
                      </div>                  
                   </div>                                    
                </div>
              </div>
              <div class="card-body col-12 tercer-elemento-noprecionado" id="contenedor-slide3">
                <div>
                  <table id="table-proveedores-dev" class="desing2 table table-bordered table-hover text-nowrap" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th>Nit</th><!-- 0 -->
                        <th>Proveedor</th><!-- 1 -->
                        <th>Teléfono</th><!-- 2 -->
                        <th>Url</th><!-- 3 -->
                        <th>id</th><!-- 4 -->
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Nit</th><!-- 0 -->
                        <th>Proveedor</th><!-- 1 -->
                        <th>Teléfono</th><!-- 2 -->
                        <th>Url</th><!-- 3 -->
                        <th>id</th><!-- 4 -->
                      </tr>
                    </tfoot>
                  </table>
                </div>  
              </div>  
            </div>
        </div>
        <div class="modal-footer">          
          <div class="col-md-7 col-sm-12">
            <p><br></p>
          </div>
          <div class="col-md-5 col-sm-12 d-flex justify-content-end">
            <button type="button" id="btn-cancelar_vis" class="btn btn-default btn-cerrar" data-dismiss="modal">Cerrar</button>
          </div>            
        </div>  
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.content -->
