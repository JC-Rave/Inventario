<?php  
    $url=$this->uri->segment_array();
    global $id_sol;
    $id_sol="";
    for ($i=1; $i <= count($url); $i++) { 
      if ($i>2 && $i!=count($url)) {
        $id_sol.=$url[$i]."/";
      }else if ($i==count($url)){
        $id_sol.=$url[$i];
      }
    }
    $id_sol2=$this->uri->segment(3);
    $id_sol=$this->encryption->decrypt(strval($id_sol));
  ?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-6">
        <h1 class="m-0 text-dark"><?php if ($id_sol){echo "Editar Solicitud N°";}else{echo "Nueva Solicitud N°";} ?><span id='n_solicitud'></span></h1>
      </div><!-- /.col -->
      <div class="col-6 d-flex justify-content-end align-items-center">
        <span class="font-weight-bold mr-1">Fecha de creación:</span><span id="fecha_creacion_solicitud" class="mr-3"></span>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<input type="hidden" id="inN_solicitud" name="inN_solicitud">
<input type="hidden" id="regurl-edit" name="regurl-edit" value="<?= $id_sol; ?>">
<input type="hidden" id="regurl-edit2" name="regurl-edit2" value="<?= $id_sol2; ?>">
<input id="base_url_dev" type="hidden" name="" value="<?php echo base_url() ?>">
<input id="consult_dev" type="hidden" name="" value="<?php echo base_url('Devolutivos_controller/consultarDevolutivos') ?>">
<input id="consult_mat" type="hidden" name="" value="<?php echo base_url('Materiales_controller/consultarMateriales') ?>">
<input id="consult_cat" type="hidden" name="" value="<?php echo base_url('Categorias_controller/consultarCategorias') ?>">
<input id="consult_users" type="hidden" name="" value="<?php echo base_url('Accesos/consultarUsuarios') ?>">
<input id="consult_und" type="hidden" name="" value="<?php echo base_url('index.php/Unidad_controller/consultarUnidades') ?>">
<input id="consult_lin" type="hidden" name="" value="<?php echo base_url('index.php/Lineas_controller/consultarLineas') ?>">
<input id="consult_est" type="hidden" name="" value="<?php echo base_url('index.php/Estados_controller/consultarEstados') ?>">
<!-- Usuario -->
<input id="id_usuario" type="hidden" name="" value="<?php echo $this->session->documento?>">
<input id="nombre_usuario" type="hidden" name="" value="<?php echo $this->session->nombre." ".$this->session->apellido?>">
<input id="tipo_usuario" type="hidden" name="" value="<?php echo $this->session->tipo_usuario?>">
<input id="linea_usuario" type="hidden" name="" value="<?php echo $this->session->linea?>" >
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Main row -->
    <div class="col-12" id="caja_productos">
        <div class="card">
          <!-- Menu Tap -->
           <ul class="nav nav-tabs" id="myTab" role="tablist">
             <li class="nav-item">
               <a class="nav-link text-dark active" id="materiales-tab" data-toggle="tab" href="#materiales" role="tab" aria-controls="materiales_tap" aria-selected="true">Consumibles</a>
             </li>
             <li class="nav-item">
               <a class="nav-link text-dark" id="devolutivos_tab" data-toggle="tab" href="#devolutivos" role="tab" aria-controls="devolutivos_tab" aria-selected="false">Devolutivos</a>
             </li>                 
           </ul>
           <!-- /. Menu Tap -->
          <!-- Container Tap -->
          <div class="tab-content" id="myTabContent">                
            <div class="tab-pane fade show active" id="materiales" role="tabpanel" aria-labelledby="materiales-tab">              
              <div class="container">
                <div class="card-body">
                  <table class="desing3 table table-bordered table-hover text-wrap" id="table-materiales" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th>documento</th><!--0-->
                        <th>encargado</th><!--1-->
                        <th style="width:120px !important;">Nombre</th><!--2-->
                        <th style="width:50px !important;">Imagen</th><!--3-->
                        <th>Categoria</th><!--4-->
                        <th>Ubicación</th><!--5-->
                        <th>Cantidad</th><!--6-->
                        <th>Unidad</th><!--7-->
                        <th>Acción</th><!--8-->
                        <th>descripción</th><!--9-->
                        <th>precio</th><!--10-->
                        <th>idProducto</th><!--11-->
                        <th>tipo</th><!--12-->
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>           
                </div>              
              </div>
            </div>

            <div class="tab-pane fade" id="devolutivos" role="tabpanel" aria-labelledby="devolutivos_tab">
              <div class="container">
                <div class="card-body">
                  <table class="desing table table-bordered table-hover text-wrap" id="table-devolutivos" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th>Placa</th><!-- 0 -->
                        <th>Codigo</th><!-- 1 -->                    
                        <th>serial</th><!-- 2 -->                    
                        <th style="width:50px !important;">Imagen</th><!-- 3 --> 
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
                        <th>tipo</th><!-- 18 -->
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>           
                </div>                                  
              </div>
            </div>
          </div>
          <!-- /. Container Tap -->      
        </div>
      </div>
     <div class="col-12">
         <div class="card">
           <div class="card-header row" id="header_tablaSalidas">
             <h3 class="mb-3 col-4">Solicitud</h3>
           </div>
           <div class="card-body">
              <div class="row">
                <div class="col-md-2 col-sm-12 mt-5">
                  <form class="col-12" id="form-solicitud-salida" method="POST" action="<?= base_url('index.php/') ?>">
                    <div class="form-group row align-items-center">
                      <label class="mb-0 col-12">Usuario:</label>
                      <input type="text" id="usuario_solicitud" class="form-control col-12" name="usuario_solicitud" readonly>
                    </div>
                    <div class="form-group row align-items-center">
                      <label class="mb-0 col-12">Estado:</label>
                      <input type="text" id="estado_solicitud" class="form-control col-12" name="estado_solicitud" readonly>
                    </div>
                  </form>
                </div>
                <div class="col-md-10 col-sm-12">
                  <div class="row mb-3" id="cantidades">
                    <div class="col-4">
                      <span class="mr-1">N° Productos:</span><span class="font-weight-bold" id="cant-salida">0</span>
                    </div>
                    <div class="col-8">
                      <span class="mr-1">N° Productos Restantes:</span><span class="font-weight-bold" id="cant-salida-maxima">90</span>
                    </div>
                  </div>
                 <table id="table_productos_solicitados" class="desing2 table table-bordered table-hover text-wrap">
                   <thead>
                     <tr>
                        <th>Encargado</th><!-- 0 -->                      
                        <th>Producto</th><!-- 1 -->
                        <th>Cantidad</th><!-- 2 -->
                        <th>Salida</th><!-- 3 -->
                        <th>estado</th><!-- 4 -->
                        <th>Precio</th><!-- 5 -->
                        <th>Acciones</th><!-- 6 -->                     
                        <th>Placa</th><!-- 7 -->
                        <th>Codigo</th><!-- 8 -->                    
                        <th>serial</th><!-- 9 -->                    
                        <th>Imagen</th><!-- 10 --> 
                        <th>Unidad</th><!-- 11-->
                        <th>Categoria</th><!-- 12 -->
                        <th>Linea</th><!-- 13 -->
                        <th>Estado</th><!-- 14 -->                    
                        <th>IdUsuario</th><!-- 15 -->       
                        <th>N°</th><!-- 16 -->
                        <th>Descripcion</th><!-- 17 -->
                        <th>idUnidad</th><!-- 18 -->
                        <th>idCategoria</th><!-- 19 -->
                        <th>idLinea</th><!-- 20 -->
                        <th>idEstado</th><!-- 21 -->
                        <th>tipo</th><!-- 22 -->
                        <th>Usuario</th><!--23 -->
                        <th>idExterna</th><!-- 24 --> 
                        <th>ExternaExist</th><!-- 25 -->   
                        <th>ExternaEmpresa</th><!-- 26 -->   
                        <th>ExternaCargo</th><!-- 27 -->   
                        <th>ExternaTelefono</th><!-- 28 -->   
                        <th>id_Salida</th><!-- 29 -->   
                     </tr>
                   </thead>
                   <tbody>
                   </tbody>
                   <tfoot>
                     <tr>
                       <th colspan="5">Total</th>                      
                       <th>$<span id="precio_total">0</span></th>
                       <th></th>                                    
                     </tr>
                   </tfoot>
                 </table>           
                </div>
              </div>
           </div> 
           <div class="card-footer">
              <div class="row justify-content-between">
                <button type="button" id="cancelarSolicitud" class="btn btn-danger pl-3 pr-3 m-0s">Cancelar</button>
                <button type="button" id="volverSolicitud" class="btn btn-danger pl-3 pr-3 m-0s" style="display: none;">Volver</button>
                <button type="button" id="editarSolicitud" class="btn btn-success pl-3 pr-3 m-0s" style="display: none;">Finalizar edición</button>
                <button type="button" id="terminarPrestamos" class="btn btn-success pl-3 pr-3 m-0s" style="display: none;">Terminar préstamos</button>
                <button type="button" id="crearSolicitud" class="btn btn-success pl-3 pr-3 m-0s">Finalizar</button>
              </div>
           </div>             
         </div>
       </div> 
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- modal Visual devolutivo-->
<div class="modal fade bd-example-modal-lg" id="modal-vis-devolutivo">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Devolutivo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div  id="form_vis_dev" >
        <div class="modal-body">
            <div class="col-12">
              <div class="card-body col-12">
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
                        <label for="precio_vis_dev">Precio*:</label>
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
                          <label class="col-12 label-input-img" id="label_imagen_vis_dev"><i class="fas fa-cloud-upload-alt p-1 mr-2"></i><span class="text-wrap">Imagen</span></label>
                          <input type="file"  name="imagen_vis_dev" id="imagen_vis_dev" class="pl-0 pt-0 pb-0 col-12 input-img" accept="image/png, .jpeg, .jpg" disabled readonly>
                        </div> 
                      </div>                  
                   </div>                                    
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
<!-- modal ver Consumible-->
<div class="modal fade bd-example-modal-lg" id="modal-vis-material">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="ver_material">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="nombre_vis_mat">Nombre</label>
                  <input disabled class="form-control" id="nombre_vis_mat">
                </div>
                <div class="form-group">
                  <label for="cant_vis_mat">Cantidad</label>
                  <input disabled class="form-control" id="cant_vis_mat">
                </div>
                <div class="form-group">
                  <label for="precio_vis_mat">Precio unitario</label>
                  <input disabled class="form-control" id="precio_vis_mat">
                </div>
                <div class="form-group">
                  <label for="linea_vis_mat">Ubicación</label>
                  <input disabled class="form-control" id="linea_vis_mat">
                </div>
                <div class="form-group">
                  <label for="descripcion_vis_mat">Descripción</label>
                  <textarea disabled class="form-control" id="descripcion_vis_mat" rows="8"></textarea>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="categoria_vis_mat">Categoria</label>
                  <input disabled class="form-control" id="categoria_vis_mat">
                </div>
                <div class="form-group">
                  <label for="unidad_vis_mat">Unidad de medida</label>
                  <input disabled class="form-control" id="unidad_vis_mat">
                </div>
                <div class="form-group acargo">
                  <label for="usuario_vis_mat">Instructor encargado</label>
                  <input disabled class="form-control" id="usuario_vis_mat">
                </div>
                <div class="form-group">
                  <figure class="border col pb-1 pt-1 d-flex justify-content-center">
                    <img id="imagen_vis_mat" class="img-fluid" alt="foto del material"/>
                  </figure>
                </div>
              </div>
            </div>

            <div class="col-md-12 pr-4 pl-4">
              <div class="dropdown-divider"></div>
            </div>

            <div class="col-md-12">
              <div class="card-body pb-0">
                <table id="provee" class="desing2 table table-bordered table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Nit</th>
                      <th>Proveedor</th>
                      <th>Precio</th>
                      <th>Descripción</th>
                      <th>Url</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>          
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- modal preparar producto-->
<div class="modal fade bd-example-modal-lg" id="modal-prep-producto">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="form-add-salida">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Salida</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            <div>
              <div class="row">
                <h3>Producto</h3>
              </div>
              <div class="row">
                <input type="hidden" id="t_productos_salida" name="t_productos_salida">
                <input type="hidden" id="id_productos_salida" name="id_productos_salida">
                <div class="form-group col-md-6 col-sm-12">
                  <label for="cant_max_p">Cantidad máxima:</label>
                  <input type="number" id="cant_max_p" name="cant_max_p" value="1" class="form-control" readonly required>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                  <label for="cant_p">Cantidad:</label>
                  <input type="number" id="cant_p" name="cant_p" min="1" placeholder="0" class="form-control" pattern="[0-9]+" required>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                  <label for="t_salida">Salida</label>
                  <select id="t_salida" class="form-control" required>
                    <option disabled>Salida</option>
                    <option value="Definitiva">Definitiva</option>
                    <option value="Prestamo">Prestamo</option> 
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                  <label for="est_salida">Estado</label>
                  <input type="text" id="est_salida" class="form-control" required readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-12" id="cant-devs">
                  <table id="tab-cant-devs" class="table table-responsive table-bordered w-100" style="display: none;max-height: 400px;overflow: scroll;">
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
              </div>
              <div class="row">
                <h5 class="col-12 mb-4 mt-4">Persona Externa:</h5>
                  <div class="form-group col-10 ml-4">
                    <div class="row align-items-center justify-content-center">
                      <label class="mb-0 col-3">Documento:</label>
                      <input type="hidden" id="accionBuscar" name="accionBuscar">
                      <input type="number" id="documento_exterior" class="form-control col-6" name="documento_exterior" required min="1000000000" max="99999999999">
                      <button type="button" id="buscar-persona-externo" class="btn btn-sm btn-primary text-center col-1 pl-2 pr-2"><i class="fas fa-search" style="font-size:15px;"></i></button>
                    </div>
                    <div class="col-12 text-center" id="caja-texto" style="display: none;">
                      <span class="text-succes" style="font-weight: bold;"></span>
                    </div>
                  </div>
                  <div id="div-oculto" class="col-12 pl-5 pr-5" style="display: none;">
                    <input type="hidden" id="existExterna" name="existExterna">
                    <input type="hidden" id="personaExterna" name="personaExterna">
                    <div class="row">
                      <div class="form-group col-6">
                        <label class="mb-0 col-12">Nombre:</label>
                        <input type="text" id="nombre_exterior" class="form-control col-12" name="nombre_exterior">
                      </div>
                      <div class="form-group col-6">
                        <label class="mb-0 col-12">Empresa:</label>
                        <input type="text" id="empresa_exterior" class="form-control col-12" name="empresa_exterior">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                        <label class="mb-0 col-12">cargo:</label>
                        <input type="text" id="cargo_exterior" class="form-control col-12" name="cargo_exterior">
                      </div>
                      <div class="form-group col-6">
                        <label class="mb-0 col-12">Teléfono:</label>
                        <input type="text" id="telefono_exterior" class="form-control col-12" name="telefono_exterior">
                      </div>
                    </div>
                  </div>
                <div class="row ml-4">
                  
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>          
          <button type="submit" class="btn btn-success" >Añadir</button>          
        </div>
      </div>
      </form>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>