<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Perfil</h1>
      </div>
      <div class="col-sm-6">
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img id="img_perfil" class="profile-user-img img-fluid img-circle"
               src="<?= $this->session->foto==null?base_url('assets/img/sin_foto.png')
               :base_url('assets/files/'.$this->session->foto) ?>" alt="User profile picture">

            </div>

            <h3 id="user_name" class="profile-username text-center"><?= $this->session->nombre.' '.$this->session->apellido ?></h3>

            <p id="linea_encargada" class="text-muted text-center">Instructor de <?= $this->session->linea ?></p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Devolutivos</b> <a class="float-right"><?= $num_productos[0] ?></a>
              </li>
              <li class="list-group-item">
                <b>Consumibles</b> <a class="float-right"><?= $num_productos[1] ?></a>
              </li>
              <li class="list-group-item">
                <b>Permiso</b> <a id="permiso" class="float-right"><?= $this->session->tipo_usuario ?></a>
              </li>
            </ul>

            <a href="<?= base_url('Vistas/cambiar_pass'); ?>" class="btn btn-primary btn-block"><b>Cambiar contraseña</b></a>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Datos Generales</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form role="form" id="editar_perfil">
              <div class="row justify-content-center">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="documento">Documento:</label>
                    <input disabled id="documento" name="documento" class="form-control" placeholder="100..." value="<?= $this->session->documento ?>">
                    <div id="error_documento" class="invalid-feedback">
                      
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="linea">Linea:</label>
                    <select disabled id="linea" name="linea" class="custom-select" placeholder="Fisica">
                      <option value="1" <?= $this->session->linea=='TICs'?'selected':'' ?>>TICs</option>
                      <option value="2" <?= $this->session->linea=='Biotecnología'?'selected':'' ?>>Biotecnología</option>
                      <option value="3" <?= $this->session->linea=='Nanotecnología'?'selected':'' ?>>Nanotecnología</option>
                      <option value="4" <?= $this->session->linea=='Química'?'selected':'' ?>>Química</option>
                      <option value="5" <?= $this->session->linea=='Física'?'selected':'' ?>>Física</option>
                      <option value="6" <?= $this->session->linea=='Matemática y diseño'?'selected':'' ?>>Matemática y diseño</option>
                      <option value="7" <?= $this->session->linea=='Electrónica y robótica'?'selected':'' ?>>Electrónica y robótica</option>
                      <option value="8" <?= $this->session->linea=='Administrativa'?'selected':'' ?>>Administrativa</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="nombre">Nombres:</label>
                    <input disabled id="nombre" name="nombre" class="form-control" placeholder="Carlos" value="<?= $this->session->nombre ?>">
                    <div id="error_nombre" class="invalid-feedback">
                      
                    </div>
                  </div>
                </div>

                <div class="col-md-4">  
                  <div class="form-group">
                    <label for="apellido">Apellidos:</label>
                    <input disabled id="apellido" name="apellido" class="form-control" placeholder="Gutierrez" value="<?= $this->session->apellido ?>">
                    <div id="error_apellido" class="invalid-feedback">
                      
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="telefono">Telefono:</label>
                    <input disabled id="telefono" name="telefono" class="form-control" placeholder="312..." value="<?= $this->session->telefono ?>">
                    <div id="error_telefono" class="invalid-feedback">
                      
                    </div>
                  </div>
                </div>

                <div class="col-md-6">  
                  <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input disabled id="correo" name="correo" class="form-control" placeholder="ejem@gmail.com" value="<?= $this->session->usuario ?>">
                    <div id="error_correo" class="invalid-feedback">
                      
                    </div>
                  </div>
                </div>

                <div class="col-md-6">  
                  <div class="form-group">
                    <label for="confirmar_correo">Confirmar Correo:</label>
                    <input disabled id="confirmar_correo" name="confirmar_correo" class="form-control" placeholder="ejem@gmail.com" value="<?= $this->session->usuario ?>">
                    <div id="error_confirmar" class="invalid-feedback">
                      
                    </div>  
                  </div>
                </div>
              </div>
            </form>
            <form role="form" id="cargar_imagen">
              <div class="row justify-content-center">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="editar_imagen">Cambiar Foto</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" accept="image/png, .jpeg, .jpg" class="custom-file-input" id="editar_imagen" name="editar_imagen">
                        <label class="custom-file-label" for="editar_imagen">Seleccionar Imagen</label>
                      </div>
                      <div class="input-group-append">
                        <button type="button" class="input-group-text" id="btn_cargar">Cargar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <div class="float-right">
              <button id="btn_guardar" type="button" class="btn btn-primary pl-5 pr-5" hidden><b>Guardar Cambios</b></button>
              <button id="btn_editar" type="button" class="btn btn-primary pl-5 pr-5"><b>Editar</b></button>
            </div>

            <button id="btn_cancelar" type="button" class="btn btn-default pl-5 pr-5" hidden><b>Cancelar</b></button>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->