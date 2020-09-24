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

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= base_url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>">

  <!-- Sweet Alert 2 -->
  <link rel="stylesheet" href="<?= base_url('plugins/SweetAlert2/dist/sweetalert2.min.css'); ?>">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- DataTables -->  
  <link rel="stylesheet" href="<?= base_url('plugins/datatables/css/dataTables.bootstrap4.min.css'); ?>">

  <!-- MDB multinivel -->
  <link rel="stylesheet" href="<?= base_url('plugins/MDB_multinivel/css/mdb.min.css'); ?>">
  <!-- Mi css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/micss.css'); ?>">
  
  <?php  
    if ($this->uri->segment(2)=='adm_usuarios'){
      echo "<link rel='stylesheet' href=".base_url('assets/css/accesocss.css').">";
    }else if ($this->uri->segment(2)=='adm_proveedores' ||$this->uri->segment(2)=='adm_materiales') {
      echo "<link rel='stylesheet' href=".base_url('assets/css/configTabla.css').">";
    }else if ($this->uri->segment(2)=='reg_pedido') {
      echo "<link rel='stylesheet' href=".base_url('assets/css/configTabla.css').">";
      echo "<link rel='stylesheet' href=".base_url('plugins/Select2/dist/css/select2.min.css').">";
    }else if ($this->uri->segment(2)=='reg_solicitud') {
      echo "<link rel='stylesheet' href=".base_url('assets/css/configTabla.css').">";
      echo "<link rel='stylesheet' href=".base_url('plugins/Select2/dist/css/select2.min.css').">";
    }else if ($this->uri->segment(2)=='adm_pedidos') {
      echo "<link rel='stylesheet' href=".base_url('assets/css/configTabla.css').">";
      echo "<link rel='stylesheet' href=".base_url('assets/css/pedidocss.css').">";
    }else if ($this->uri->segment(2)=='detalle_pedido') {
      echo "<link rel='stylesheet' href=".base_url('assets/css/configTabla.css').">";
      echo "<link rel='stylesheet' href=".base_url('assets/css/pedidocss.css').">";
      echo "<link rel='stylesheet' href=".base_url('plugins/Select2/dist/css/select2.min.css').">";
    }else if ($this->uri->segment(2)=='reg_solicitud') {
      echo "<link rel='stylesheet' href=".base_url('assets/css/configTabla.css').">";
      echo "<link rel='stylesheet' href=".base_url('plugins/Select2/dist/css/select2.min.css').">";
    }else if ($this->uri->segment(2)=='historial_consumo') {
      echo "<link rel='stylesheet' href=".base_url('plugins/daterangepicker/daterangepicker.css').">";
    }
  ?>
</head>
<style type="text/css">
  @keyframes tipsy {
    from {
      transform: translateX(-50%) translateY(-50%) rotate(0deg);
    }
    to{
      transform: translateX(-50%) translateY(-50%) rotate(360deg);
    }
  }
  #cargando img{
    padding: 10px;
    border-radius: 50%;
    background-color: rgba(255,255,255,.8);
  }
  #cargando {
    color: #fffbf1;
    text-shadow: 0 20px 25px #2e2e31, 0 40px 60px #2e2e31;
    font-size: 30px;
    font-weight: bold;
    text-decoration: none;
    letter-spacing: -3px;
    margin: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
  }

  #cargando:before,
  #cargando:after {
    content: '';
    padding: 2.5em;
    position: absolute;
    left: 50%;
    width: 100%;
    top: 50%;
    display: block;
    border:15px solid red;
    border-top: 15px solid red;
    border-radius: 50%;
    transform: translateX(-50%) translateY(-50%) rotate(0deg);
    animation: 2s infinite linear tipsy;
  }
  #cargando:before {
    border-color: rgba(0, 0, 0,0) rgba(0, 0, 0, .3) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
    z-index: 10;
  }
  #cargando:after {
    border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) rgba(0, 0, 0,0) rgba(0, 0, 0, .3);
  }
  #caja-cargando{
      top:0px;
      left:0px;
      width: 100%; 
      height: 100%;
      display: flex; 
      justify-content: center;
      align-items: center;
      z-index: 2000; 
      position: fixed;
      font-family: helvetica, arial, sans-serif; 
      background-color: rgba(46,46,49,0.4);
      opacity: 1;
  }
</style>
<body class="hold-transition sidebar-mini layout-fixed 
<?= $this->uri->segment(2)=='perfil' || $this->uri->segment(2)=='cambiar_pass'?'sidebar-collapse':''; ?>">
  <div id="caja-cargando">
    <span id="cargando"><img src=" <?php echo base_url('assets/img/logo_sena.png')?> " style="width: 150px;height: 150px;" alt="Cargando..."></span>
  </div>
  <div class="wrapper" style="z-index: 10px;" >
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
          <a id="perfil_nav" class="user-panel pr-3 dropdown-toggle" style="color: rgb(0,0,0);" href="#" id="users" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="img-profile rounded-circle" src="<?= $this->session->foto==null?base_url('assets/img/sin_foto.png'):base_url('assets/files/'.$this->session->foto) ?>">
            <span style="color: rgb(0,0,0);"><?= $this->session->nombre.' '.$this->session->apellido ?></span>
          </a>
          <!-- Dropdown - User Information -->
          <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="users">
            <a class="dropdown-item" href="<?= base_url('Vistas/perfil'); ?>">
              <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
              Perfil
            </a>
            <a class="dropdown-item" href="<?= base_url('Vistas/cambiar_pass'); ?>">
              <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
              Cambiar contraseña
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?=base_url('Login_controller/logout');?>">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Cerrar Sesión
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    