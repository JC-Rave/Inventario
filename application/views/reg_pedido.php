<?php 
  $url=$this->uri->segment_array();
  $pedido='';
  for ($i=3; $i <=count($url) ; $i++) { 
    $i==count($url)?$pedido.=$url[$i]:$pedido.=$url[$i].'/';
  }
?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 col-6">
        <h1 class="m-0 text-dark"><?= empty($pedido)?'Crear ':'Editar ' ?>Pedido</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <a href="<?= base_url('Vistas/adm_pedidos'); ?>" class="p-3 m-0 btn btn-default float-right">Retroceder</a>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<input type="hidden" name="codigoPed" id="codigoPed" value="<?= $pedido ?>">

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Main row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link text-dark active" id="materiales-tab" data-toggle="tab" href="#materiales" role="tab" aria-controls="materiales_tap" aria-selected="true">Consumibles</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark" id="devolutivos_tab" data-toggle="tab" href="#devolutivos" role="tab" aria-controls="devolutivos_tab" aria-selected="false">Devolutivos</a>
            </li>                 
          </ul>
          <div class="card-body">
            <div class="tab-content" id="myTabContent">                
              <div class="tab-pane fade show active" id="materiales" role="tabpanel" aria-labelledby="materiales-tab">              
                <table id="inv_materiales" class="desing3 table table-bordered table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>imagen</th>
                      <th>Cantidad</th>
                      <th>Unidad de Medida</th>
                      <th>Categoria</th>
                      <th>Descripción</th>
                      <th>Acción</th>
                      <th>tipo</th>
                      <th>value categoria</th>
                      <th>value imagen</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($materiales as $material): ?>
                      <tr>
                        <td><?= $material->nombre_producto ?></td>
                        <td style="min-width: 200px; max-width: 200px;" class="p-0"><center><img width="100%" height="186px" src="<?= $material->imagen==''?base_url('assets/img/sinFoto.png'):base_url('assets/files/'.$material->imagen) ?>" /></center></td>
                        <td><?= $material->cantidad_consumible ?></td>
                        <td><?= $material->nombre_unidad ?></td>
                        <td><?= $material->nombre_categoria ?></td>
                        <td><?= wordwrap($material->descripcion_producto, 60, '<br/>', false) ?></td>
                        <td>
                          <center>
                            <button type="button" class="agregar btn btn-sm btn-info" data-toggle="modal" data-target="#config_pedido">
                              <i class="fas fa-plus"></i>
                            </button>
                          </center>
                        </td>
                        <td><?= $material->tipo_producto ?></td>
                        <td><?= $material->categoria_producto ?></td>
                        <td><?= $material->vImagen==null?'Seleccionar Imagen':$material->vImagen ?></td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>             
              </div>

              <div class="tab-pane fade" id="devolutivos" role="tabpanel" aria-labelledby="devolutivos_tab">
                <table id="inv_devolutivos" class="desing2 table table-bordered table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>imagen</th>
                      <th>Categoria</th>
                      <th>Descripción</th>
                      <th>Acción</th>
                      <th>tipo</th>
                      <th>value categoria</th>
                      <th>value imagen</th>
                      <th>precio</th>
                    </tr> 
                  </thead>
                  <tbody>
                    <?php foreach ($devolutivos as $devolutivo): ?>
                      <tr>
                        <td><?= $devolutivo->nombre_producto ?></td>
                        <td style="min-width: 200px; max-width: 200px;" class="p-0"><center><img width="100%" height="186px" src="<?= $devolutivo->imagen==''?base_url('assets/img/sinFoto.png'):base_url('assets/files/'.$devolutivo->imagen) ?>" /></center></td>
                        <td><?= $devolutivo->nombre_categoria ?></td>
                        <td><?= wordwrap($devolutivo->descripcion_producto, 60, '<br/>', false) ?></td>
                        <td>
                          <center>
                            <button value="devolutivos" type="button" class="agregar btn btn-sm btn-info" data-toggle="modal" data-target="#config_pedido">
                              <i class="fas fa-plus"></i>
                            </button>
                          </center>
                        </td>
                        <td><?= $devolutivo->tipo_producto ?></td>
                        <td><?= $devolutivo->categoria_producto ?></td>
                        <td><?= $devolutivo->vImagen==null?'Seleccionar Imagen':$devolutivo->vImagen ?></td>
                        <td><?= $devolutivo->precio_producto ?></td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div><br/>
            <div class="dropdown-divider">
            </div><br/>
  
            <table id="new_pedido" class="desing2 table table-bordered table-hover text-nowrap">
              <thead>
                 <tr>
                   <th id="0"></th>
                   <th id="1">Descripción de proveedor(es)</th>
                   <th id="2">Nombre</th>
                   <th id="3">Imagen</th>
                   <th id="4">Unidad de medida</th>
                   <th id="5">Cantidad requerida</th>
                   <th id="6">P.Unitario 1</th>
                   <th id="7">P.Unitario 2</th>
                   <th id="8">P.Unitario 3</th>
                   <th id="9">Precio Promedio</th>
                   <th id="10">total</th>
                   <th id="11">Acción</th>
                   <th id="12">tipo</th>
                   <th id="13">cantidad actual</th>
                   <th id="14">categoria</th>
                   <th id="15">descripcion producto</th>
                   <th id="16">nit empresa 1</th>
                   <th id="17">nit empresa 2</th>
                   <th id="18">nit empresa 3</th>
                   <th id="19">value unidad</th>
                   <th id="20">value imagen</th>
                   <th id="21">descripcion empresa 1</th>
                   <th id="22">descripcion empresa 2</th>
                   <th id="23">descripcion empresa 3</th>
                   <th id="24">insertar en</th>
                   <th id="25">value categoria</th>
                   <th id="26">precio producto</th>
                   <th id="27">observacion</th>
                   <th id="28">nombre actual</th>
                 </tr>
              </thead>
              <tbody>
                <?php
                  if (!empty($productos)):
                    foreach ($productos as $producto):
                      $n=1;
                      $promedio=(int)$producto->precio_1;

                      if ($producto->precio_2!='0.00') {
                        $n++;
                        $promedio+=(int)$producto->precio_2;
                      }else{
                        $producto->precio_2='';
                      }

                      if ($producto->precio_3!='0.00') {
                        $n++;
                        $promedio+=(int)$producto->precio_3;        
                      }else{
                        $producto->precio_3='';
                      }

                      $promedio=number_format($promedio/$n, 2, '.', '');
                      $total=$promedio*(int)$producto->cantidad;
                      $total=number_format($total, 2, '.', '');

                      $n=2;
                      $observacion='<b class="font-weight-bold">Empresa 1:</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_1.'">'.wordwrap($producto->url_1, 125, '<br/>', true).'</a>';

                      if (!empty($producto->url_2)) {
                        $observacion.='<br/><br/><b class="font-weight-bold">Empresa '.$n.':</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_2.'">'.wordwrap($producto->url_2, 125, '<br/>', true).'</a>';
                        $n++;
                      }

                      if (!empty($producto->url_3)) {
                        $observacion.='<br/><br/><b class="font-weight-bold">Empresa '.$n.':</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_3.'">'.wordwrap($producto->url_3, 125, '<br/>', true).'</a>';
                      }

                      if (empty($producto->tipo)) {
                        $producto->tipo='Pedido';
                        $btn_editar='<button type="button" class="btn btn-sm btn-warning editarNew" data-toggle="modal" data-target="#config_newProducto">
                          <i class="fas fa-edit"></i>
                        </button>';
                      }else{
                        $btn_editar="<button type='button' class='btn btn-sm btn-warning editar_detalles' data-toggle='modal' data-target='#config_pedido'>
                         <i class='fas fa-edit'></i>
                        </button>";
                      }

                      $producto->imagen=$producto->imagen!=null?base_url('assets/files/'.$producto->imagen):base_url('assets/img/sinFoto.png');
                      $producto->nombre=$producto->nombre!=null?$producto->nombre:'Seleccionar Imagen';

                      empty($producto->insertar)?$producto->insertar='Devolutivo':$producto->insertar='Consumible';

                      echo "<tr>
                       <td class='sub_tabla'></td>
                       <td class='config' style='min-width: 400px;'>".$producto->descripcion."</td>
                       <td>".$producto->nombre_producto."</td>
                       <td class='p-0' style='min-width:200px;'><center><img width='100%;' height='186px;' src='".$producto->imagen."'/></center></td>
                       <td>".$producto->nombre_unidad."</td>
                       <td>".$producto->cantidad."</td>
                       <td>".$producto->precio_1."</td>
                       <td>".$producto->precio_2."</td>
                       <td>".$producto->precio_3."</td>
                       <td>".$promedio."</td>
                       <td>".$total."</td>
                       <td><center>".
                        $btn_editar."<button type='button' class='btn btn-sm btn-danger eliminar'>
                         <i class='fas fa-trash'></i>
                        </button>
                       </center></td>
                       <td>".$producto->tipo."</td>
                       <td>".$producto->cantidad_actual."</td>
                       <td>".$producto->nombre_categoria."</td>
                       <td>".wordwrap($producto->descripcion_producto, 60, '<br/>', false)."</td>
                       <td>".$producto->nit_1."</td>
                       <td>".$producto->nit_2."</td>
                       <td>".$producto->nit_3."</td>
                       <td>".$producto->id_unidad."</td>
                       <td>".$producto->nombre."</td>
                       <td>".$producto->descripcion_1."</td>
                       <td>".$producto->descripcion_2."</td>
                       <td>".$producto->descripcion_3."</td>
                       <td>".$producto->insertar."</td>
                       <td>".$producto->id_categoria."</td>
                       <td>".$producto->precio_producto."</td>
                       <td>".$observacion."</td>
                       <td>".$producto->nombre_producto."</td>
                      </tr>";
                    endforeach; 
                  endif;
                ?>
              </tbody>
            </table>  
          </div>
          <div class="card-footer">
            <div class="float-right">
              <button id="btn_guardar_editar" type="button" class="btn btn-primary"><?=empty($pedido)?'Guardar':'Editar'?></button>
            </div>

            <a href="<?= base_url('Vistas/adm_pedidos'); ?>" class="btn btn-default">Cancelar</a>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- modal Agregar producto-->
<div class="modal fade show" id="config_pedido">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-teal">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="fm_config">
          <div class="row">
            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="cantidad">Cantidad</label>
                  <input type="number" placeholder="Cantidad que desea pedir" class="form-control" id="cantidad" name="cantidad">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="proveedor">Proveedor(es)</label>
                  <div class="select2-info">
                    <select id="proveedor" name="proveedor" class="select2" multiple data-placeholder="Seleccionar proveedor(es)" data-dropdown-css-class="select2-info" style="width: 100%;">

                    </select>
                  </div>
                </div>
              </div>
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
                      <th>url</th>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btn_editar_detalle" type="button" class="btn btn-primary float-right">Editar</button>
        <button id="btn_continuar" type="button" class="btn btn-primary float-right">Continuar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- modal Agregar nuevo producto-->
<div class="modal fade show" id="config_newProducto" style="overflow-y: scroll;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-teal">
        <h4 class="modal-title">Pedido de nuevo producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="fm_configNewProducto">
          <div class="row">
            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="newNombre">Nombre</label>
                  <input placeholder="Nombre del producto ejm: Lapiz" class="form-control" id="newNombre" name="newNombre">
                  <div id="error_newNombre" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="newCategoria">Categoria</label>
                  <select class="custom-select" id="newCategoria"name="newCategoria">
                    <option value="0" disabled selected>Selecionar categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                      <option value="<?= $categoria->id_categoria ?>"><?= $categoria->nombre_categoria ?></option>
                    <?php endforeach ?>
                  </select>
                  <div id="error_newCategoria" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="newCantidad">Cantidad</label>
                  <input type="number" placeholder="Cantidad requerida ejm: 12" class="form-control" id="newCantidad" name="newCantidad">
                  <div id="error_newCantidad" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group" id="caja_unidad">
                  <label for="newUnidad">Unidad de medida</label>
                  <select class="custom-select" id="newUnidad" name="newUnidad">
                    <option value="0" disabled selected>Selecionar medida</option>
                    <?php foreach ($medidas as $medida): ?>
                      <option value="<?= $medida->id_unidad ?>"><?= $medida->nombre_unidad ?></option>
                    <?php endforeach ?>
                  </select>
                  <div id="error_newUnidad" class="invalid-feedback">
                    
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="imagen">Imagen (Opcional)</label>
                  <select id="selectImagen" name="selectImagen"class="imagen-select">
                    <option data-imagesrc="<?= base_url('assets/img/sinFoto.png') ?>">Seleccionar Imagen</option>
                    <?php foreach ($imagenes as $imagen): ?>
                      <option value="<?= $imagen->nombre ?>" data-imagesrc="<?= base_url('assets/files/'.$imagen->imagen) ?>" ><?= $imagen->nombre ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" placeholder="descripción del producto..."></textarea>
                    <div id="error_descripcion" class="invalid-feedback">
                      
                    </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 mb-2">
              <div class="dropdown-divider"></div>
            </div>

            <div class="col">
              <div class="card-body pb-0">
                <table id="tb_proveedores" class="desing2 table table-bordered table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Nit</th>
                      <th>Proveedor</th>
                      <th>Telefono</th>
                      <th>Correo</th>
                      <th>Url</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($proveedores as $proveedor): ?>
                      <tr>
                        <td><?= $proveedor->nit; ?></td>
                        <td><?= $proveedor->nombre_proveedor; ?></td>
                        <td><?= $proveedor->telefono_proveedor; ?></td>
                        <td><?= $proveedor->correo_proveedor; ?></td>
                        <td><a target="_black" style="color: #3B89EA;" href="<?= $proveedor->url; ?>"><?= wordwrap($proveedor->url, 40, '<br/>', true); ?></a></td>
                        <td>
                          <center class='opciones'>
                            <button type="button" class="agregarProv btn btn-sm btn-info" data-toggle="modal" data-target="#config_suministro">
                              <i class="fas fa-plus"></i>
                            </button>
                          </center>
                        </td>
                      </tr>
                    <?php endforeach;  ?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div class="dropdown-divider"></div>
            </div>

            <div class="col">
              <div class="card-body pb-0">
                <table id="tb_seleccionados" class="desing2 table table-bordered table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Nit</th>
                      <th>Proveedor</th>
                      <th>Precio</th>
                      <th>Descripción</th>
                      <th>Acción</th>
                      <th>Telefono</th>
                      <th>Correo</th>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btn_editarNew" type="button" class="btn btn-primary float-right">Editar</button>
        <button id="btn_agregarNew" type="button" class="btn btn-primary float-right">Continuar</button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade show" id="config_suministro">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-teal">
        <h4 class="modal-title">Especificación del producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="fm_configProv">
          <div class="form-group">
            <label for="precio_prov">Precio</label>
            <input type="number" placeholder="Precio del producto que designo el proveedor" class="form-control" id="precio_prov" name="precio_prov">
            <div id="error_precioProv" class="invalid-feedback">
                
            </div>
          </div>
          <div class="form-group">
            <label for="descripcion_prov">Descripción</label>
            <textarea rows="5"  placeholder="Descripción por la que identifica el proveedor este producto" class="form-control" id="descripcion_prov" name="descripcion_prov"></textarea>
            <div id="error_desProv" class="invalid-feedback">
                
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btn_editarProv" type="button" class="btn btn-primary float-right">Editar</button>
        <button id="btn_agregarProv" type="button" class="btn btn-primary float-right">Continuar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>