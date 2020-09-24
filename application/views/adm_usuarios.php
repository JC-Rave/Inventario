<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 col-6">
        <h1 class="m-0 text-dark">Administrar Usuarios</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <button id="tranferir_inventario" type="button" class="p-3 m-0 btn btn-success float-right" data-toggle="modal" data-target="#md_tranferir">Transferir Inventario</button>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
  	<!-- Main row -->
    <div class="row">
      <!-- AQUI IRA TODO EL CONTENIDO QUE SE LE AGREGUE A LA PAGINA -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
              <table id="tb_usuarios" class="desing table table-bordered table-hover text-nowrap">
                <thead>
                  <tr>
                    <th id="0">Usuario</th>
                    <th id="1">Linea</th>
                    <th id="2">Tipo de usuario</th>
                    <th id="3">Estado</th>
                    <th id="4">Acción</th>
                    <th id="5">Documento</th>
                    <th id="6">Nombre</th>
                    <th id="7">Apellido</th>
                    <th id="8">telefono</th>
                    <th id="9">img</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($datas as $dato): ?>
                    <tr>
                      <td class="visualizar user"><div><?= $dato->usuario ?><img src="<?= $dato->img!=null?base_url('assets/files/'.$dato->img):base_url('assets/img/sin_foto.png') ?>"></div></td>
                      <td class="visualizar"><?= $dato->nombre_linea ?></td>
                      <td class="visualizar"><?= $dato->nombre_tipo ?></td>
                      <td class="visualizar">
                        <center style="border-radius: 5px;" class="font-weight-bold <?= $dato->estado=='a'?'bg-success':'bg-danger' ?>"><?= $dato->estado=='a'?'Activo':'Inactivo' ?></center>
                      </td>    
                      <td>
                        <center>
                          <button class="visualizar btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                          </button>

                          <button class="pre_editar btn btn-sm btn-warning" data-toggle="modal" data-target="#editar_usuario">
                            <i class="fas fa-edit"></i>
                          </button>
                        </center>  
                      </td>  
                      <td><?= $dato->documento_persona ?></td>
                      <td><?= $dato->nombre_persona ?></td>
                      <td><?= $dato->apellido_persona ?></td>
                      <td><?= $dato->telefono_persona ?></td>
                      <td><?= $dato->img ?></td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row (main row) -->
  </div>
  <!-- /.container-fluid -->
</section>

<div class="modal fade" id="editar_usuario">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title titulo_edit">Editar Usuario</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="edit_form">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <h4>Datos personales</h4>
              <div class="card-body">
                <div class="form-group">
                  <label for="cedula">Documento</label>
                  <input type="number" class="form-control" id="cedula" name="cedula" placeholder="100..." required>
                  <div id="error_documento" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="nombre">Nombres</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Carlos " required>
                  <div id="error_nombre" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="apellido">Apellidos</label>
                  <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Gallego " required>
                  <div id="error_apellido" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="telefono">Telefono</label>
                  <input type="number" class="form-control" id="telefono" name="telefono" placeholder="313... " required>
                  <div id="error_telefono" class="invalid-feedback">
                    
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>

            <div class="col-md-6">
              <h4>Datos de Cuenta</h4>
              <div class="card-body">
                <div class="form-group">
                  <label for="usuario">Usuario</label>
                  <input type="email" class="form-control" id="usuario" name="usuario" placeholder="car@gmail.com " required>
                  <div id="error_usuario" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="tipo_user">Tipo De Usuario</label>
                  <select class="custom-select" id="tipo_user" name="tipo_user" required>
                    <option value="1">ADMINISTRADOR</option>
                    <option value="2">INSTRUCTOR</option>
                  </select>
                  <div id="error_tipo_user" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="linea">Linea</label>
                  <select class="custom-select" id="linea" name="linea" required>
                    <option value="1">TICs</option>
                    <option value="2">Biotecnología</option>
                    <option value="3">Nanotecnología</option>
                    <option value="4">Química</option>
                    <option value="5">Física</option>
                    <option value="6">Matemática y diseño</option>
                    <option value="7">Electrónica y robótica</option>
                    <option value="8">Administrativa</option>
                  </select>
                  <div id="error_linea" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="estado">Estado</label>
                  <select class="custom-select" id="estado" name="estado">
                    <option value="a">Activo</option>
                    <option value="i">Inactivo</option>
                  </select>
                  <div id="error_estado" class="invalid-feedback">
                    
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" id="btn_editar" class="btn btn-primary float-right">Editar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="ver_usuario">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title titulo"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <h4>Datos personales</h4>
              <div class="card-body">
                <div class="form-group">
                  <label for="ver_documento">Documento</label>
                  <input disabled type="number" class="form-control" id="ver_documento" name="ver_documento">
                </div>
                <div class="form-group">
                  <label for="ver_nombre">Nombres</label>
                  <input disabled type="text" class="form-control" id="ver_nombre" name="ver_nombre">
                </div>
                <div class="form-group">
                  <label for="ver_apellido">Apellidos</label>
                  <input disabled type="text" class="form-control" id="ver_apellido" name="ver_apellido">
                </div>
                <div class="form-group">
                  <label for="ver_telefono">Telefono</label>
                  <input disabled type="number" class="form-control" id="ver_telefono" name="ver_telefono">
                </div>
                <div class="form-group">
                  <label for="ver_linea">Linea</label>
                  <input disabled type="text" class="form-control" id="ver_linea" name="ver_linea">
                </div>
              </div>
              <!-- /.card-body -->
            </div>

            <div class="col-md-6">
              <h4>Datos de Cuenta</h4>
              <div class="card-body">
                <div class="form-group">
                  <label for="ver_user">Usuario</label>
                  <input disabled type="email" class="form-control" id="ver_user" name="ver_user">
                </div>
                <div class="form-group">
                  <label for="ver_estado">Estado</label>
                  <input disabled class="form-control" type="text" name="ver_estado" id="ver_estado">
                </div>
                <div class="form-group">
                  <center>
                    <figure class="border col-7 pb-1 pt-1 d-flex 
                    justify-content-center">
                      <img id="ver_imagen" class="img-fluid" alt="foto del usuario"/>
                    </figure>
                  </center>
                </div>
              </div>
              <!-- /.card-body -->
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

<div class="modal fade" id="md_tranferir">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Tranferencia de inventerio</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <form role="form">
              <div class="card-body">
                <div class="form-group">
                  <label for="tranferir_de">DE:</label>
                  <select class="custom-select" id="tranferir_de" name="tranferir_de" required>
                    
                  </select>
                  <div id="error_trans_de" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="col-md-12 mb-2">
                  <div class="dropdown-divider"></div>
                </div>
                <div class="form-group">
                  <label for="de_documento">Documento</label>
                  <input disabled class="form-control" id="de_documento" name="de_documento">
                </div>
                <div class="form-group">
                  <label for="de_usuario">Usuario</label>
                  <input disabled class="form-control" id="de_usuario" name="de_usuario">
                </div>
                <div class="form-group">
                  <label for="de_telefono">Telefono</label>
                  <input disabled class="form-control" id="de_telefono" name="de_telefono">
                </div>
                <div class="form-group">
                  <label for="de_linea">Linea</label>
                  <input disabled class="form-control" id="de_linea" name="de_linea">
                </div>
              </div>
              <!-- /.card-body -->
            </form>  
          </div>

          <div class="col-md-6">
            <form role="form">
              <div class="card-body">
                <div class="form-group">
                  <label for="tranferir_a">A:</label>
                  <select class="custom-select" id="tranferir_a" name="tranferir_a" required>
                    
                  </select>
                  <div id="error_trans_a" class="invalid-feedback">
                    
                  </div>
                </div>
                <div class="col-md-12 mb-2">
                  <div class="dropdown-divider"></div>
                </div>
                <div class="form-group">
                  <label for="a_documento">Documento</label>
                  <input disabled class="form-control" id="a_documento" name="a_documento">
                </div>
                <div class="form-group">
                  <label for="a_usuario">Usuario</label>
                  <input disabled class="form-control" id="a_usuario" name="a_usuario">
                </div>
                <div class="form-group">
                  <label for="a_telefono">Telefono</label>
                  <input disabled class="form-control" id="a_telefono" name="a_telefono">
                </div>
                <div class="form-group">
                  <label for="a_linea">Linea</label>
                  <input disabled class="form-control" id="a_linea" name="a_linea">
                </div>
              </div>
              <!-- /.card-body -->
            </form>  
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button id="btn_tranferir" type="button" class="btn btn-primary float-right">Tranferir</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.content -->