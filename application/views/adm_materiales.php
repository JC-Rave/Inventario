<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2"> 
      <div class="col-sm-6 col-6">
        <h1 class="m-0 text-dark">Administrar Materiales</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <button id="btn_preRegistrar" type="button" class="p-3 m-0 btn btn-success float-right" data-toggle="modal" data-target="#md_registrar">Agregar Material</button>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<input type="hidden" id="loggedUser" value="<?= $this->session->documento ?>">
<input type="hidden" id="tipoUser" value="<?= $this->session->tipo_usuario ?>">
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Main row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table id="tb_materiales" class="desing table table-bordered table-hover text-nowrap">
              <thead>
                <tr>
                  <th>documento</th>
                  <th>encargado</th>
                  <th>Nombre</th>
                  <th>Imagen</th>
                  <th>Categoria</th>
                  <th>Ubicación</th>
                  <th>Cantidad</th>
                  <th>Unidad de medida</th>
                  <th>Acción</th>
                  <th>descripción</th>
                  <th>precio</th>
                  <th>img</th>
                  <th>imgNombre</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($materiales as $material): ?>
                  <tr>
                    <td><?= $material->id_usuario ?></td>
                    <td class="visualizar"><?= $material->nombre_persona.' '.$material->apellido_persona ?></td>
                    <td class="visualizar"><?= $material->nombre_producto ?></td>
                    <td style="min-width: 110px;" class="p-0 visualizar"><img class="img-fluid" src="<?= $material->imagen!=''?base_url('assets/files/'.$material->imagen):base_url('assets/img/sinFoto.png') ?>"/></td>
                    <td class="visualizar"><?= $material->nombre_categoria ?></td>
                    <td class="visualizar"><?= $material->nombre_linea ?></td>
                    <td class="visualizar"><?= $material->cantidad_consumible ?></td>
                    <td class="visualizar"><?= $material->nombre_unidad ?></td>
                    <td>
                      <center>
                        <button class="visualizar btn btn-sm btn-info">
                          <i class="fas fa-eye"></i>
                        </button>
                        
                        <?php if ($this->session->documento==$material->id_usuario ||$this->session->tipo_usuario=='ADMINISTRADOR'): ?>
                          <button class="btn_preEditar btn btn-sm btn-warning" data-toggle="modal" data-target="#md_editar">
                            <i class="fas fa-edit"></i>
                          </button>
                        <?php endif ?>
                      </center>  
                    </td>
                    <td><?= $material->descripcion_producto?></td>
                    <td><?= $material->precio_producto ?></td>
                    <td><?= $material->imagen ?></td>
                    <td><?= $material->nombre ?></td>
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

<!-- modal Agregar Consumible-->
<div class="modal fade" id="md_registrar">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Agregar Material</h4>
        <button type="button" class="cancelar close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="registrar_material">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="nombre">Nombre *</label>
                  <input class="form-control" id="nombre" name="nombre" placeholder="ejm: Lapiz" required>
                  <div id="error_nombre" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="cantidad">Cantidad *</label>
                  <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="ejem: 12" required>
                  <div id="error_cantidad" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="precio">Precio unitario</label>
                  <input type="number" class="form-control" id="precio" name="precio" placeholder="ejem: 12520.00" required>
                  <div id="error_precio" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="imagen">Imagen (Opcional)</label>
                  <select id="selectImagen" name="selectImagen"class="imagen-select">
                    <option data-imagesrc="<?= base_url('assets/img/sinFoto.png') ?>">Seleccionar Imagen</option>
                    <?php foreach ($imagenes as $imagen): ?>
                      <option value="<?= $imagen->nombre ?>" data-imagesrc="<?= base_url('assets/files/'.$imagen->imagen) ?>" ><?= $imagen->nombre ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="categoria">Categoria *</label>
                  <select class="custom-select" id="categoria" name="categoria">
                    <option value="0" disabled selected>Selecionar categoria</option>

                  </select>
                  <div id="error_categoria" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="unidad">Unidad de medida *</label>
                  <select class="custom-select" id="unidad" name="unidad">
                    <option value="0" disabled selected>Selecionar medida</option>

                  </select>
                  <div id="error_unidad" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="ubicacion">Ubicación *</label>
                  <select class="custom-select" id="ubicacion" name="ubicacion" required>
                    <option value="0" selected disabled>Selecionar ubicación</option>
                    <option value="1">TICs</option>
                    <option value="2">Biotecnología</option>
                    <option value="3">Nanotecnología</option>
                    <option value="4">Química</option>
                    <option value="5">Física</option>
                    <option value="6">Matemáticas y diseño</option>
                    <option value="7">Electrónica y robótica</option>
                    <option value="8">Administrativa</option>
                  </select>
                  <div id="error_ubicacion" class="invalid-feedback">
                    
                  </div>
                </div>
                <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
                  <div class="form-group">
                    <label for="encargar">Instructor encargado (Opcional)</label>
                    <select class="custom-select" id="encargar" name="encargar">
                      <option selected>Seleccionar instructor</option>

                    </select>
                  </div>
                <?php else: ?>
                  <div class="form-group">
                      <label for="descripcion">Descripción *</label>
                      <textarea name="descripcion" id="descripcion" rows="3" class="form-control" placeholder="descripción del material..."></textarea>
                      <div id="error_descripcion" class="invalid-feedback">
                        
                      </div>
                  </div>
                <?php endif ?>
              </div>
            </div>

            <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
              <div class="col-md-12">
                <div class="card-body pt-2 pb-0">
                  <div class="form-group">
                    <label for="descripcion">Descripción *</label>
                    <textarea name="descripcion" id="descripcion" rows="2" class="form-control" placeholder="descripción del material..."></textarea>
                    <div id="error_descripcion" class="invalid-feedback">
                      
                    </div>
                  </div>
                </div>
              </div>
            <?php endif ?>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <p>En esta sección podrás agregar nuevos materiales consumibles al inventario.<br><strong>Nota: Los campos con asterisco (*) son obligatorios.</strong></p>
        <div>
          <button type="button" class="cancelar btn btn-default" data-dismiss="modal">Cancelar</button>
          <button id="btn_registrar" type="button" class="btn btn-primary float-right">Guardar</button>            
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- modal Editar Consumible-->
<div class="modal fade" id="md_editar">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="editar_material">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="edit_nombre">Nombre *</label>
                  <input type="text" class="form-control" id="edit_nombre" name="edit_nombre" placeholder="ejm: Lapiz">
                  <div id="error_edit_nombre" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="edit_cantidad">Cantidad *</label>
                  <input type="number" class="form-control" id="edit_cantidad" name="edit_cantidad" placeholder="ejem: 12" required>
                  <div id="error_edit_cantidad" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="edit_precio">Precio unitario</label>
                  <input type="number" class="form-control" id="edit_precio" name="edit_precio" placeholder="ejem: 12520.00" required>
                  <div id="error_edit_precio" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="edit_imagen">Imagen (Opcional)</label>
                  <select id="selectEdit_imagen" name="selectEdit_imagen" class="imagen-select2">

                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="edit_categoria">Categoria *</label>
                  <select class="custom-select" id="edit_categoria" name="edit_categoria">
                    <option value="0" disabled selected>Selecionar categoria</option>

                  </select>
                  <div id="error_edit_categoria" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="edit_unidad">Unidad de medida *</label>
                  <select class="custom-select" id="edit_unidad" name="edit_unidad">
                    <option value="0" disabled selected>Selecionar medida</option>

                  </select>
                  <div id="error_edit_unidad" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="edit_ubicacion">Ubicación *</label>
                  <select class="custom-select" id="edit_ubicacion" name="edit_ubicacion" required>
                    <option value="0" selected disabled>Selecionar ubicación</option>
                    <option value="1">TICs</option>
                    <option value="2">Biotecnología</option>
                    <option value="3">Nanotecnología</option>
                    <option value="4">Química</option>
                    <option value="5">Física</option>
                    <option value="6">Matemáticas y diseño</option>
                    <option value="7">Electrónica y robótica</option>
                    <option value="8">Administrativa</option>
                  </select>
                  <div id="error_edit_ubicacion" class="invalid-feedback">
                    
                  </div>
                </div>
                <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
                  <div class="form-group">
                    <label for="edit_encargar">Instructor encargado (Opcional)</label>
                    <select class="custom-select" id="edit_encargar" name="edit_encargar">
                      <option selected>Seleccionar instructor</option>

                    </select>
                  </div>
                <?php else: ?>
                  <div class="form-group">
                      <label for="edit_descripcion">Descripción *</label>
                      <textarea name="edit_descripcion" id="edit_descripcion" rows="3" class="form-control" placeholder="descripción del material..."></textarea>
                      <div id="error_edit_descripcion" class="invalid-feedback">
                        
                      </div>
                  </div>
                <?php endif ?>
              </div>
            </div>

            <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
              <div class="col-md-12">
                <div class="card-body pt-2 pb-0">
                  <div class="form-group">
                    <label for="edit_descripcion">Descripción *</label>
                    <textarea name="edit_descripcion" id="edit_descripcion" rows="2" class="form-control" placeholder="descripción del material..."></textarea>
                    <div id="error_edit_descripcion" class="invalid-feedback">
                      
                    </div>
                  </div>
                </div>
              </div>
            <?php endif ?>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <p>En esta sección podrás editar los materiales consumibles del inventario.<br><strong>Nota: Los campos con asterisco (*) son obligatorios.</strong></p>
        <div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button id="btn_editar" type="button" class="btn btn-primary float-right">editar</button> 
        </div>  
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- modal ver Consumible-->
<div class="modal fade" id="md_visualizar">
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
                  <label for="ver_nombre">Nombre</label>
                  <input disabled class="form-control" id="ver_nombre">
                </div>
                <div class="form-group">
                  <label for="ver_cantidad">Cantidad</label>
                  <input disabled class="form-control" id="ver_cantidad">
                </div>
                <div class="form-group">
                  <label for="ver_precio">Precio unitario</label>
                  <input disabled class="form-control" id="ver_precio">
                </div>
                <div class="form-group">
                  <label for="ver_ubicacion">Ubicación</label>
                  <input disabled class="form-control" id="ver_ubicacion">
                </div>
                <div class="form-group">
                  <label for="ver_descripcion">Descripción</label>
                  <textarea disabled class="form-control" id="ver_descripcion" rows="8"></textarea>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card-body pb-0">
                <div class="form-group">
                  <label for="ver_categoria">Categoria</label>
                  <input disabled class="form-control" id="ver_categoria">
                </div>
                <div class="form-group">
                  <label for="ver_unidad">Unidad de medida</label>
                  <input disabled class="form-control" id="ver_unidad">
                </div>
                <div class="form-group acargo">
                  <label for="ver_encargar">Instructor encargado</label>
                  <input disabled class="form-control" id="ver_encargar">
                </div>
                <div class="form-group">
                  <figure class="col d-flex justify-content-center">
                    <img width="451" height="320" id="ver_imagen" class="border p-1" alt="foto del material"/>
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
<!-- /.content -->