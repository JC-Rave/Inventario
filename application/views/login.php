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
<body class="login-page" style="min-height: 512.391px;">
<div class="col-3" style="position: absolute;top:5px;right:5px;background: rgba(255,255,255,.9);border-radius: 30px;">
  <img src="<?= base_url('assets/img/logo_sena2.png'); ?>" class="img-fluid">
</div>
<div style="position: fixed; left: 0px;top: -130px;width: 100%;height: 100%;z-index: -100;">
  <video autoplay="autoplay" muted loop="loop" preload="auto" style="position: absolute;top:0px;left:-50px;width:110%;z-index:-1000;overflow: hidden;"/>
    <source src="<?= base_url('assets/img/video_fondo.mp4'); ?>" type="video/mp4"/>
  </video/>
</div>
<div class="login-box col-md-7 col-sm-11">
  <!-- /.login-logo -->
  <div class="card">
    <div class="login-logo">
      <a href="#">Inventario Tecno<b>Academia</b></a>
    </div>
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicia sesión para comenzar</p>

      <form action="" method="POST">
        <?php if (!empty($error)): ?>
          <div class="row">
            <div class="col-12 input-group mb-3">
              <div class="col-12 p-1 alert alert-danger mb-0" role="alert">
                <center>
                  <?= $error; ?>
                </center>  
              </div>
            </div>
          </div>  
        <?php endif; ?>
        <div class="input-group mb-3">
          <input name="user" id="user" type="email" class="form-control" placeholder="Usuario" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="password" id="password" type="password" class="form-control" placeholder="Contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="<?= base_url('Olvidepass_controller'); ?>">Olvidé mi contraseña</a>
      </p>
      <p class="mb-0">
        <a href="<?= base_url('Regcuenta_controller'); ?>" class="text-center">¿No tienes cuenta? Crea una</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

</body>
</html>



