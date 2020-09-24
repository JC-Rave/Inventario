<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TecnoAcademia | Inventario</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css'); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/css/adminlte/adminlte.min.css'); ?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head> 
<body class="hold-transition register-page">
<div class="col-3" style="position: absolute;top:5px;right:5px;background: rgba(255,255,255,.9);border-radius: 30px;">
  <img src="<?= base_url('assets/img/logo_sena2.png'); ?>" class="img-fluid">
</div>
<div style="position: fixed; left: 0px;top: -130px;width: 100%;height: 100%;z-index: -100;">
  <video autoplay="autoplay" muted loop="loop" preload="auto" style="position: absolute;top:0px;left:-50px;width:110%;z-index:-1000;overflow: hidden;"/>
    <source src="<?= base_url('assets/img/video_fondo.mp4'); ?>" type="video/mp4"/>
  </video/>
</div>
<div class="register-box mt-5">
  <div class="card">
    <div class="register-logo mb-1 pb-1 ">
      <a href="#">Inventario Tecno<b>Academia</b></a>
    </div>
    <div class="card-body register-card-body">
      <p class="login-box-msg">Crear cuenta instructor</p>

      <form action="" method="POST">
        <?php if (count($errores)>1): 
          if (!$errores['invalid']): ?>
            <div class="row">
              <div class="col-12 input-group mb-3">
                <div class="col-12 p-1 alert alert-danger mb-0" role="alert">
                  <center>
                    <?= $errores['msj']; ?>
                  </center>  
                </div>
              </div>
            </div>  
          <?php endif;
        endif; ?>
        <div class="input-group mb-3">
          <input id="cedula" value="<?= set_value('cedula') ?>" name="cedula" type="number" class="form-control" placeholder="Documento" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-address-card"></span>
            </div>
          </div>
          <div id="error_documento" class="invalid-feedback">

          </div>
        </div>
        <div class="input-group mb-3">
          <input id="nombre" value="<?= set_value('nombre') ?>" name="nombre" type="text" class="form-control" placeholder="Nombres" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-address-book"></span>
            </div>
          </div>
          <div id="error_nombre" class="invalid-feedback">
              
          </div>
        </div>
        <div class="input-group mb-3">
          <input id="apellido" value="<?= set_value('apellido') ?>" name="apellido" type="text" class="form-control" placeholder="Apellidos" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-address-book"></span>
            </div>
          </div>
          <div id="error_apellido" class="invalid-feedback">
              
          </div>
        </div>
        <div class="input-group mb-3">
          <input id="celular" value="<?= set_value('celular') ?>" name="celular"  type="number" class="form-control" placeholder="Telefono" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone-alt"></span>
            </div>
          </div>
          <div id="error_celular" class="invalid-feedback">
              
          </div>
        </div>
        <div class="input-group mb-3">
          <input id="correo" value="<?= set_value('correo') ?>" name="correo" type="email" class="form-control" placeholder="Correo" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <div id="error_correo" class="invalid-feedback">
              
          </div>
        </div>
        <div class="input-group mb-3">
          <input id="confirm_correo" value="<?= set_value('confirm_correo') ?>" name="confirm_correo" type="correo" class="form-control" placeholder="Confirmar correo" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <div id="error_confirmar" class="invalid-feedback">
              
          </div>
        </div>
        <div class="input-group mb-3">
          <select id="linea" name="linea" required class="form-control custom-select">
            <option value="0" disabled <?= set_select('linea', '0', $errores['opc_linea']==''?true:false); ?>>Seleccionar linea</option>
            <option value="1" <?= set_select('linea', '1', $errores['opc_linea']=='1'?true:false); ?>>Tecnologías de la información y la comunicación (TICs)</option>
            <option value="2" <?= set_select('linea', '2', $errores['opc_linea']=='2'?true:false); ?>>Biotecnología</option>
            <option value="3" <?= set_select('linea', '3', $errores['opc_linea']=='3'?true:false); ?>>Nanotecnología</option>
            <option value="4" <?= set_select('linea', '4', $errores['opc_linea']=='4'?true:false); ?>>Química</option>
            <option value="5" <?= set_select('linea', '5', $errores['opc_linea']=='5'?true:false); ?>>Física</option>
            <option value="6" <?= set_select('linea', '6', $errores['opc_linea']=='6'?true:false); ?>>Matemática y diseño</option>
            <option value="7" <?= set_select('linea', '7', $errores['opc_linea']=='7'?true:false); ?>>Electrónica y robótica</option>
            <option value="8" <?= set_select('linea', '8', $errores['opc_linea']=='8'?true:false); ?>>Administrativa</option>
          </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
          </div>
          <div id="error_linea" class="invalid-feedback">
              
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button id="continuar" type="submit" class="btn btn-primary btn-block">Registrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-0">
        <a href="<?= base_url('Login_controller'); ?>" class="text-center">Iniciar sesión</a>
      </p>  
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<?php if (count($errores)>1): ?>
  <script src="<?= base_url('plugins/jquery/jquery.min.js'); ?>"></script>
  <script src="<?= base_url('assets/js/main.js'); ?>"></script>
  <script type="text/javascript">
    $(document).ready(iniciar);

    function iniciar() {
      <?php if ($errores['invalid']): ?>
        error=true;
        var cedula='<?= $errores['cedula'] ?>';
        var nombre='<?= $errores['nombre'] ?>';
        var apellido='<?= $errores['apellido'] ?>';
        var celular='<?= $errores['telefono'] ?>';
        var correo='<?= $errores['correo'] ?>';
        var confirmar='<?= $errores['confirmar'] ?>';
        var linea='<?= $errores['linea'] ?>';

        if (cedula.length>0) {
          $('#cedula').val('');
          $('#cedula').addClass('is-invalid');
          $('#error_documento').html(cedula);
        }

        if (nombre.length>0) {
          $('#nombre').val('');
          $('#nombre').addClass('is-invalid');
          $('#error_nombre').html(nombre);
        }

        if (apellido.length>0) {
          $('#apellido').val('');
          $('#apellido').addClass('is-invalid');
          $('#error_apellido').html(apellido);
        }

        if (celular.length>0) {
          $('#celular').val('');
          $('#celular').addClass('is-invalid');
          $('#error_celular').html(celular);
        }

        if (correo.length>0) {
          $('#correo').val('');
          $('#correo').addClass('is-invalid');
          $('#error_correo').html(correo);
        }

        if (confirmar.length>0) {
          $('#confirm_correo').val('');
          $('#confirm_correo').addClass('is-invalid');
          $('#error_confirmar').html(confirmar);
        }

        if (linea.length>0) {
          $('#linea').val(0);
          $('#linea').addClass('is-invalid');
          $('#error_linea').html(linea);
        }
      <?php endif ?>

      $('#cedula').keyup(function() {diseño(!error?'':'cedula');});
      $('#nombre').keyup(function() {diseño(!error?'':'nombre');});
      $('#apellido').keyup(function() {diseño(!error?'':'apellido');});
      $('#celular').keyup(function() {diseño(!error?'':'celular');});
      $('#correo').keyup(function() {diseño(!error?'':'correo');});
      $('#confirm_correo').keyup(function() {diseño(!error?'':'confirm_correo');});
      $('#linea').change(function() {diseño(!error?'':'linea');});
    }
  </script>
<?php endif ?>

</body>
</html>
