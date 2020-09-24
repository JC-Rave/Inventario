
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
<body class="hold-transition login-page">
<div class="col-3" style="position: absolute;top:5px;right:5px;background: rgba(255,255,255,.9);border-radius: 30px;">
  <img src="<?= base_url('assets/img/logo_sena2.png'); ?>" class="img-fluid">
</div>
<?php  
    $url=$this->uri->segment_array();
    global $id_sol;
    $id_sol="";
    for ($i=1; $i <= count($url); $i++) { 
      if ($i>2 && $i!=count($url)) {
        $id_sol.=$url[$i]."/";
      }else if ($i==count($url)){
        $id_sol.=$url[$i];
      }
    }
    $id_sol2=$this->uri->segment(3);
    $id_sol=$this->encryption->decrypt(strval($id_sol));
  ?>
<div style="position: fixed; left: 0px;top: -130px;width: 100%;height: 100%;z-index: -100;">
  <video autoplay="autoplay" muted loop="loop" preload="auto" style="position: absolute;top:0px;left:-50px;width:110%;z-index:-1000;overflow: hidden;"/>
    <source src="<?= base_url('assets/img/video_fondo.mp4'); ?>" type="video/mp4"/>
  </video/>
</div>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="login-box">
        <!-- /.login-logo -->
        <div class="card">
          <div class="card-body login-card-body">
            <p class="login-box-msg">Ingrese la nueva cotraseña para completar el proceso.</p>
            <form role="form" id="form_cambiar">
              <input type="hidden" id="documento" name="documento" value="<?php echo $id_sol ?>">
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
<!-- /.login-box -->
<script src="<?= base_url('plugins/jquery/jquery.min.js'); ?>"></script>

  <!-- Bootstrap -->
  <script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

  <!-- overlayScrollbars -->
  <script src="<?= base_url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>

  <!-- Sweet Alert 2 -->
  <script src="<?= base_url('plugins/SweetAlert2/dist/sweetalert2.all.min.js'); ?>"></script>

  <!-- DataTables --> 
  <script src="<?= base_url('plugins/datatables/js/jquery.dataTables.min.js'); ?>"></script>
  <script src="<?= base_url('plugins/datatables/js/dataTables.bootstrap4.min.js'); ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('assets/js/adminlte/adminlte.min.js'); ?>"></script>

  <!-- MDB multinivel -->
  <script src="<?= base_url('plugins/MDB_multinivel/js/mdb.min.js'); ?>"></script>
  <!-- Main -->
  <script src="<?= base_url('assets/js/main.js'); ?>"></script> 
  <!-- ddslick -->
  <script src="<?= base_url('plugins/ddslick/jquery.ddslick.min.js'); ?>"></script>
  <script src="<?= base_url('assets/js/cambiar_pass.js'); ?>"></script> 
</body>
</html>
