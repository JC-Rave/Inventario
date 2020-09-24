<!-- Content Header (Page header) -->
<div class="content-header"> 
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Panel de Control</h1>
        <form id="form_archivo" enctype="multipart/form-data">
          <input type="file" id="subir_excel" name="subir_excel" style="display: none;" accept=".xlsx">
          <label for="subir_excel" class="btn btn-sm pl-2 pr-2 btn-success"><i class="far fa-file-excel" style="font-size: 1.5rem;"></i></label>
        </form>
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
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <!-- AQUI IRA TODO EL CONTENIDO QUE SE LE AGREGUE A LA PAGINA -->
      <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $num_user ?></h3>

              <p>Usuarios Registrados</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="<?= base_url('Vistas/adm_usuarios'); ?>" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      <?php endif ?>
      <!-- ./col -->
      <div class="col-lg-<?= $this->session->tipo_usuario!='ADMINISTRADOR'?'4':'3' ?> col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?= $num_pedidos ?></h3>

            <p>Pedidos</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="<?= base_url('Vistas/adm_pedidos'); ?>" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>65</h3>

            <p>Prestamos</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-<?= $this->session->tipo_usuario!='ADMINISTRADOR'?'4':'3' ?> col-6">
        <!-- small box -->
        <div class="small-box bg-lightblue">
          <div class="inner">
            <h3>53<sup style="font-size: 20px">%</sup></h3>

            <p>Solicitudes</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
      
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->