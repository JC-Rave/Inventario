<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 col-6">
        <h1 class="m-0 text-dark">Administrar Proveedores</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <button id="reg_pro" type="button" class="p-3 m-0 btn btn-success float-right" data-toggle="modal" data-target="#reg_editar_proveedor">Registrar Proveedor</button>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<input type="hidden" id="tipo_user" value="<?= $this->session->tipo_usuario ?>">
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- AQUI IRA TODO EL CONTENIDO QUE SE LE AGREGUE A LA PAGINA -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table id="tb_proveedores" class="desing table table-bordered table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Nit</th>
                  <th>Proveedor</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Url</th>
                  <th>Estado</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <!-- $datos['proveedores'] que esta en el controlador se convierte en la variable $proveedores -->
                <?php foreach ($proveedores as $dato): ?>
                  <!-- imprimo los proveedores -->
                  <tr>
                    <td class="visualizar"><?= $dato->nit; ?></td>
                    <td class="visualizar"><?= $dato->nombre_proveedor; ?></td>
                    <td class="visualizar"><?= $dato->telefono_proveedor; ?></td>
                    <td class="visualizar"><?= $dato->correo_proveedor; ?></td>
                    <td><a target="_black" style="color: #3B89EA" href="<?= $dato->url; ?>"><?= wordwrap($dato->url, 40, '<br/>', true); ?></a></td>
                    <td class="visualizar"><center style="border-radius: 5px;" class="font-weight-bold <?= $dato->estado=='a'?'bg-success':'bg-danger' ?>"><?= $dato->estado=='a'?'Activo':'Inactivo' ?></center></td>    
                    <td>
                      <center>
                        <button class="visualizar btn btn-sm btn-info">
                          <i class="fas fa-eye"></i>
                        </button>
                        
                        <?php if ($dato->estado=='a' || $this->session->tipo_usuario=='ADMINISTRADOR'): ?>
                          <button class="editar_proveedor btn btn-sm btn-warning" data-toggle="modal" data-target="#reg_editar_proveedor">
                            <i class="fas fa-edit"></i>
                          </button>
                        <?php endif ?>
                      </center>  
                    </td>
                  </tr>
                <?php endforeach;  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</section>

<div class="modal fade" id="reg_editar_proveedor" style="overflow-y: scroll;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="titulo modal-title"></h4>
        <button type="button" class="cancelar close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="fm_registrar">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="nit">Nit *</label>
                <input type="text" class="form-control" id="nit" name="nit" placeholder="10A50FS123">
                <div id="error_nit" class="invalid-feedback">
                    
                </div>
              </div>
            </div>  
            <div class="col-md-4">
              <div class="form-group">
                <label for="proveedor">Proveedor *</label>
                <input type="text" class="form-control" id="proveedor" name="proveedor" placeholder="Trident">
                <div id="error_proveedor" class="invalid-feedback">
                    
                </div>
              </div>
            </div>  
            <div class="col-md-4">
              <div class="form-group">
                <label for="telefono">Telefono *</label>
                <input type="number" class="form-control" id="telefono" name="telefono" placeholder="313...">
                <div id="error_telefono" class="invalid-feedback">
                    
                </div>
              </div>
            </div>  
            <div class="col-md-6 div_correo">
              <div class="form-group">
                <label for="correo">Correo *</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@gmail.com">
                <div id="error_correo" class="invalid-feedback">
                    
                </div>
              </div>
            </div>  
            <div class="col-md-6">
              <div class="form-group">
                <label for="url">Url *</label>
                <input type="url" class="form-control" id="url" name="url" placeholder="http://www.ejemplo.com">
                <div id="error_url" class="invalid-feedback">
                    
                </div>
              </div>
            </div>
            <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
              <div class="col-md-3 div_estado">
                <div class="form-group">
                  <label for="estado">Estado *</label>
                  <select id="estado" name="estado" class="custom-select">
                    <option value="a">Activo</option>
                    <option value="i">Inactivo</option>
                  </select>
                </div>
              </div>
            <?php endif ?>
            <div class="col-md-12 mb-2">
              <div class="dropdown-divider"></div>
            </div>
            <div class="col-md-12">
              <table id="inventario" class="desing2 table table-bordered table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Tipo Producto</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($productos as $dato): ?>
                    <tr>
                      <td><?= $dato->nombre_producto; ?></td>
                      <td><?= $dato->tipo_producto; ?></td>
                      <td>
                        <center>
                          <button type="button" class="agregar btn btn-sm btn-info"  data-toggle="modal" data-target="#config_suministro">
                            <i class="fas fa-plus"></i>
                          </button>
                        </center> 
                      </td>
                    </tr>
                  <?php endforeach;  ?>
                </tbody>
              </table>
            </div>
            <div class="col-md-12 mb-2">
              <div class="dropdown-divider"></div>
            </div>
            <div class="col-md-12">
              <table id="suministra" class="desing2 table table-bordered table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descripcón</th>
                    <th>Acción</th>
                    <th>Tipo Producto</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <p></p>
        <div class="row">
          <button type="button" class="cancelar btn btn-default" data-dismiss="modal">Cerrar</button>
          <div class="col-4">
            <button id="btn_registrar" type="button" class="btn btn-primary">Registrar</button>
            <button id="btn_editar" type="button" class="btn btn-primary">Editar</button>
          </div>  
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="md_visulizar">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="fm_visulizar">
          <div class="row justify-content-center">
            <div class="col-md-4">
              <div class="form-group">
                <label for="ver_nit">Nit</label>
                <input disabled type="text" class="form-control" id="ver_nit" name="ver_nit">
              </div>
            </div>  
            <div class="col-md-4">
              <div class="form-group">
                <label for="ver_proveedor">Proveedor</label>
                <input disabled type="text" class="form-control" id="ver_proveedor" name="ver_proveedor">
              </div>
            </div>  
            <div class="col-md-4">
              <div class="form-group">
                <label for="ver_telefono">Telefono</label>
                <input disabled type="number" class="form-control" id="ver_telefono" name="ver_telefono">
              </div>
            </div>  
            <div class="col-md-3">
              <div class="form-group">
                <label for="ver_correo">Correo</label>
                <input disabled type="email" class="form-control" id="ver_correo" name="ver_correo">
              </div>
            </div>  
            <div class="col-md-3">
              <div class="form-group">
                <label for="ver_estado">Estado</label>
                <input disabled type="text" class="form-control" id="ver_estado" name="ver_estado">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Url</label>
                <div id="ver_url">
                  
                </div>
              </div>
            </div>
            <div class="col-md-12 mb-2">
              <div class="dropdown-divider"></div>
            </div>
            <div class="col-md-12">
              <table id="ver_suministra" class="desing2 table table-bordered table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descripcón</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
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
        <form role="form" id="fm_config">
          <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" placeholder="Precio del producto que designo el proveedor" class="form-control" id="precio" name="precio">
            <div id="error_precio" class="invalid-feedback">
                
            </div>
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea rows="5"  placeholder="Descripción por la que identifica el proveedor este producto" class="form-control" id="descripcion" name="descripcion"></textarea>
            <div id="error_des" class="invalid-feedback">
                
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
<!-- /.modal -->
<!-- /.content -->
