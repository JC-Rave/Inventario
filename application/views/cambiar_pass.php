<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Cambiar Contraseña</h1>
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
      <div class="login-box">
        <!-- /.login-logo -->
        <div class="card">
          <div class="card-body login-card-body">
            <p class="login-box-msg">Ingresa su actual contraseña y la nueva cotraseña para completar el proceso.</p>
            <form role="form" id="form_cambiar">
              <div class="form-group">
                <label for="actual_pass">Actual Contraseña:</label>
                <div class="input-group mb-3">
                  <input id="actual_pass" name="actual_pass" type="password" class="form-control" placeholder="************">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>                  
                  <div id="error_actual" class="invalid-feedback">
                      
                  </div>
                </div>
              </div>  
              <hr />
              <div class="form-group">
                <label for="new_pass">Nueva Contraseña:</label>
                <div class="input-group mb-3">
                  <input id="new_pass" name="new_pass" type="password" class="form-control" placeholder="********">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                  <div id="error_nueva" class="invalid-feedback">
                      
                  </div>
                </div>
              </div>  
              <div class="form-group">
                <label for="confirm_new_pass">Confirmar Contraseña:</label>  
                <div class="input-group mb-3">
                  <input id="confirm_new_pass" name="confirm_new_pass" type="password" class="form-control" placeholder="********">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                  <div id="error_confirmar" class="invalid-feedback">
                      
                  </div>
                </div>
              </div>  
              <div class="row">
                <div class="col-12">
                  <button id="btn_cambiar" type="button" class="btn btn-primary btn-block">Cambiar Contraseña</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
          </div>
          <!-- /.login-card-body -->
        </div>
      </div> 
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->