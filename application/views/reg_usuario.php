<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Registrar Usuarios</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
  	<!-- Main row -->
    <div class="row justify-content-center">
      <!-- AQUI IRA TODO EL CONTENIDO QUE SE LE AGREGUE A LA PAGINA -->
      <div class="col-md-10">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Formulario de Registro</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" id="registrar_usuarios">
            <div class="row justify-content-center">
              <div class="col-md-6">
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
                <div class="card-body">
                  <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="car@gmail.com " required>
                    <div id="error_correo" class="invalid-feedback">
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="confirmar">Confirmar Correo</label>
                    <input type="email" class="form-control" id="confirmar" name="confirmar" placeholder="car@gmail.com " required>
                    <div id="error_confirmar" class="invalid-feedback">
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="tipo_user">Tipo De Usuario</label>
                    <select class="custom-select" id="tipo_user" name="tipo_user" required>
                      <option value="1">ADMINISTRADOR</option>
                      <option value="2" selected>INSTRUCTOR</option>
                    </select>
                    <div id="error_tipo_user" class="invalid-feedback">
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="linea">Linea</label>
                    <select class="custom-select" id="linea" name="linea" required>
                      <option value="0" selected disabled>Selecionar</option>
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
                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </form>
          <div class="card-footer">
            <button id="btn_registrar" type="button" class="btn btn-primary">Registrar</button>
          </div>
        </div>
      </div> 
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->