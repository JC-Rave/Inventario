<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 col-6">
        <h1 class="m-0 text-dark">Solicitudes</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <button type="button" id="crearSolicitud" class="btn btn-success float-right">Crear Solicitud</button>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<input type="hidden" id="url_base" name="url_base" value="<?= base_url(); ?>">
<input id="consult_lin" type="hidden" name="" value="<?php echo base_url('index.php/Lineas_controller/consultarLineas') ?>">
<input id="consult_est" type="hidden" name="" value="<?php echo base_url('index.php/Estados_controller/consultarEstados') ?>">
<input id="consult_cat" type="hidden" name="" value="<?php echo base_url('index.php/Categorias_controller/consultarCategorias') ?>">
<input id="consult_users" type="hidden" name="" value="<?php echo base_url('index.php/Accesos/consultarUsuarios') ?>">
<input id="consult_und" type="hidden" name="" value="<?php echo base_url('index.php/Unidad_controller/consultarUnidades') ?>">
<!-- ------------------------------------------------------------------------------------------------- -->
<input id="id_usuario" type="hidden" name="" value="<?php echo $this->session->documento?>">
<input id="nombre_usuario" type="hidden" name="" value="<?php echo $this->session->nombre." ".$this->session->apellido?>">
<input id="tipo_usuario" type="hidden" name="" value="<?php echo $this->session->tipo_usuario?>">
<input id="linea_usuario" type="hidden" name="" value="<?php echo $this->session->linea?>" >
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Main row -->
    <div class="col-12">
        <div class="card" id="caja-solicitudes">
          <div class="card-body">
            <table id="table_solicitudes" class="desing table table-bordered table-hover text-nowrap" style="width: 100% !important;">
              <thead>
                <tr>
                  <th>id</th><!-- 0 -->
                  <th>Usuario</th><!-- 1 -->                 
                  <th>fecha</th><!-- 2 -->
                  <th>Estado</th><!-- 3 -->                                         
                  <th>Valor</th><!-- 4 -->
                  <th style="width: 40px !important;">Acciones</th> <!-- 5 -->
                </tr>
              </thead>
              <tbody>
                <?php 
                  $cont=0;
                  $totalSolicitudes=0;
                  foreach ($solicitudes as $solicitud):
                    if ($this->session->tipo_usuario==="ADMINISTRADOR" || $this->session->tipo_usuario==="INSTRUCTOR" && $solicitud->usuario_solicitud===$this->session->documento) {
                      $cont++;
                ?>
                  <tr>
                    <td style="display: none;"><?=$solicitud->id_solicitud?></td>
                    <td class="text-center"><?=$solicitud->nombre_persona?></td>
                    <td class="text-center"><?=explode(" ", $solicitud->fecha_solicitud)[0]?></td>
                    <td class="text-center"><?=$solicitud->estado_solicitud?></td>
                    <td><?php if ($solicitud->total_solicitud==null || $solicitud->total_solicitud===""){$precio=0;echo $precio;}else{echo $solicitud->total_solicitud;}?></td>
                    <td class="text-left">
                      <?php 
                        if ($solicitud->estado_solicitud==="En proceso" || $solicitud->estado_solicitud==="En prestamo" || $solicitud->estado_solicitud==="Pausado"){
                          echo "<a href='".base_url('Vistas/reg_solicitud/').$this->encryption->encrypt($solicitud->id_solicitud)."'  class='btn btnEdit btn-sm btn-warning pr-3 pl-3 mr-1  ml-1'><i class='fas fa-edit' title='Editar solicitud'></i></a>";
                          $totalSolicitudes+=$solicitud->total_solicitud;
                          if ($solicitud->estado_solicitud==="En proceso" || $solicitud->estado_solicitud==="Pausado"){
                            echo "<button type='button' value='".$solicitud->id_solicitud."' class='btn btnCancel btn-sm btn-danger pr-3 pl-3 mr-1 ml-1' title='Cancelar solicitud'><i class='fas fa-ban'></i></button>";  
                          }else if ($solicitud->estado_solicitud==="En prestamo"){
                            echo "<button type='button' value='".$solicitud->id_solicitud."' class='btn btnCancel btn-sm btn-danger pr-3 pl-3 mr-1 ml-1' title='Cancelar solicitud'><i class='fas fa-ban'></i></button><button type='button' value='".$solicitud->id_solicitud."' class='btn btnTerminar btn-sm btn-success pr-3 pl-3 mr-1 ml-1' title='Terminar solicitud'><i class='fas fa-check'></i></button>";  
                          }
                        }else {
                          if ($solicitud->estado_solicitud==="Terminado") {
                            echo "<button type='button' value='".$solicitud->id_solicitud."' class='btn btnVis btn-sm btn-primary pr-3 pl-3 mr-1 ml-1' title='Visualizar solicitud'><i class='fas fa-eye'></i></button>"; 
                              $totalSolicitudes+=$solicitud->total_solicitud;
                          }
                        }
                        ?>
                    </td>
                  </tr>
                <?php 
                    }
                  endforeach;  
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4">Total</th>
                  <th colspan="2">$<span><?= $totalSolicitudes?></span></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="card" id="caja-salidas" style="display: none;">
          <div class="card-body">
            <div class="row justify-content-between">
              <div id="btn-volver">
                <button type="button" class=""><i class="fas fa-angle-left"></i></button><span>Volver</span>
              </div>
            </div>
            <div class="row d-flex justify-content-center mb-3"><h1>Solicitud N°</h1></div>
            <div class="row bg-light pl-2 pr-2 pt-1 pb-1 rounded" id="detalle_solicitud">
              <div class="row col-12 justify-content-around mt-2 align-items-center">
                <div class="col-md-4">
                  <span class="font-weight-bold">Usuario: </span><span id="nom_sol_user"></span>
                </div>
                <div class="col-md-4">
                  <span class="font-weight-bold">Fecha de creación: </span><span id="fecha_sol"></span>
                </div>
              </div>
              <div class="row col-12 justify-content-center">
                <hr class="text-dark bg-dark row col-11">
              </div>
              <div class="row col-12 justify-content-around mb-2 align-items-center">
                <div class="col-md-4">
                  <span class="font-weight-bold">Estado:</span><span id="est_sol"></span>
                </div>
                <div class="col-md-4">
                  <span class="font-weight-bold">Total Solicitud: $</span><span id="total_sol"></span>
                </div>
              </div>
              <div class="col-12">
                
              </div>
            </div>
            <div class="row mb-3 mt-3" id="cantidades">
              <div class="col-4">
                <span class="mr-1">N° Productos:</span><span class="font-weight-bold" id="cant-salida">0</span>
              </div>
              <div class="col-8">
                <span class="mr-1">N° Productos Restantes:</span><span class="font-weight-bold" id="cant-salida-maxima">90</span>
              </div>
            </div>
            <div class="mt-3">
              <table id="table_productos_solicitados" class="desing2 table table-bordered table-hover text-wrap w-100">
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
      </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
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
                      <img id="imagen_vis_mat" src="<?=base_url('assets/img/sinFoto.png')?>" class="img-fluid" alt="foto del material"/>
                    </figure>
                  </div>
                </div>
              </div>

              <div class="col-md-12 pr-4 pl-4">
                <div class="dropdown-divider"></div>
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
</section>

