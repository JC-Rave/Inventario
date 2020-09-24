<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 col-6">
        <h1 class="m-0 text-dark">Administrar Pedidos</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <a href="<?= base_url('Vistas/detalle_pedido'); ?>" class="p-3 m-0 btn btn-success float-right">Crear Pedido</a>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<input type="hidden" id="tipo_user" value="<?= $this->session->tipo_usuario ?>">
<input type="hidden" id="loggedUser" value="<?= $this->session->documento ?>">
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Main row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table id="tb_pedidos" class="desing table table-bordered table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Fecha pedido</th>
                  <th>Fecha Entregado</th>
                  <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
                    <th>Usuario</th>
                    <th>Encargado</th>
                  <?php endif ?>
                  <th>estado</th>                      
                  <th>total</th>                                      
                  <th>Acción</th>                                      
                  <th>id pedido</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                  <tr>
                    <td class="visualizar"><?= $pedido->fecha_pedido==null?'Generando pedido':$pedido->fecha_pedido ?></td>
                    <td class="visualizar"><?= $pedido->fecha_entregado==null && $pedido->estado_pedido=='Cancelado'?'No Finalizado':($pedido->fecha_entregado==null && $pedido->estado_pedido=='Pendiente'?'No Entregado':$pedido->fecha_entregado) ?></td>
                    <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
                      <td class="visualizar"><?= $pedido->usuario_pedido ?></td>
                      <td class="visualizar"><?= $pedido->nombre_persona.' '.$pedido->apellido_persona?></td>
                    <?php endif ?>
                    <td class="visualizar"><?= $pedido->estado_pedido ?></td>
                    <td class="visualizar"><?= $pedido->total ?></td>
                    <td>
                      <center>
                        <button class="visualizar btn btn-sm btn-info">
                          <i class="fas fa-eye"></i>
                        </button>
                        
                        <?php 
                          if ($pedido->estado_pedido=='En proceso'){ 
                            $codigo=$this->encryption->encrypt($pedido->id_pedido);

                            echo "<a href='".base_url('Vistas/detalle_pedido/'.$codigo)."' class='btn btn-sm btn-warning'>
                              <i class='fas fa-edit'></i>
                            </a>";

                          }else if($pedido->estado_pedido=='Pendiente'){
                            echo "<button class='btn_preEditar btn btn-sm btn-warning'>
                              <i class='fas fa-edit'></i>
                            </button>";
                          }
                        ?>                        
                      </center>
                    </td>
                    <td class="visualizar"><?= $pedido->id_pedido ?></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>

<!-- modal editar pedido -->
<div class="modal fade" id="md_editar" style="overflow-y: scroll;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Editar pedido</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="tb_editarPed" class="desing2 table table-bordered table-hover text-nowrap">
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
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <div>
          <p>En esta sección podrás editar la cantidad requerida de los productos pedido seleccionado.<br><b class="font-weight-bold">Nota: Si presiona en el boton mas podras ver los precios y observaciones de cada empresa.</b></p>
        </div>
        <div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button id="btn_editar" type="button" class="btn btn-primary float-right">Editar</button> 
        </div>            
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- modal configurar pedido -->
<div class="modal fade show" id="config_pedido">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-teal">
        <h4 class="modal-title">Editar cantidad</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="fm_config">
          <div class="row">
            <div class="col-md-12">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="cantidad">Cantidad</label>
                  <input type="number" placeholder="Cantidad que desea pedir" class="form-control" id="cantidad" name="cantidad">
                  <div id="error_cantidad" class="invalid-feedback">
                      
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btn_editarCantidad" type="button" class="btn btn-primary float-right">Editar</button> 
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- modal detalle pedido-->
<div class="modal fade" id="md_visualizar">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Detalles del pedido</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="tb_detallePed" class="desing2 table table-bordered table-hover text-nowrap">
          <thead>
            <tr>
              <th id="0"></th>
              <th id="1">Descripción de proveedor(es)</th>
              <th id="2">Imagen</th>
              <th id="3">Unidad de medida</th>
              <th id="4">Cantidad requerida</th>
              <th id="5">Unitario_1</th>
              <th id="6">Unitario_2</th>
              <th id="7">Unitario_3</th>
              <th id="8">Precio Promedio</th>
              <th id="9">Total</th>
              <th id="10">Observaciones</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <div>
          <p>En esta sección podrás ver a detalle todos los productos del pedido seleccionado.<br><b class="font-weight-bold">Nota: Si presiona en el boton mas podras ver los precios y observaciones de cada empresa.</b></p>
        </div>
        <div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>            
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.content -->