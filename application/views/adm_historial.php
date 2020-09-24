<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Historial de consumo</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<input type="hidden" id="loggedUser" value="<?= $this->session->documento ?>">
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- AQUI IRA TODO EL CONTENIDO QUE SE LE AGREGUE A LA PAGINA -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table id="tb_historial" class="desing2 table table-bordered table-hover text-nowrap">
              <thead>
                <tr>
                  <th>usuario</th>
                  <th>Fecha</th>
                  <th>Encargado</th>
                  <th>Producto</th>
                  <th>Imagen</th>
                  <th>Cantidad</th>
                  <th>Unidad de medida</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($historiales as $historial): ?>
                  <tr>
                    <td><?= $historial->usuario_producto ?></td>
                    <td><?= $historial->fecha ?></td>
                    <td><?= $historial->nombre_persona.' '.$historial->apellido_persona ?></td>
                    <td><?= $historial->nombre_producto ?></td>
                    <td style="min-width: 200px; max-width: 200px;" class="p-0"><img width="100%" height="186px" src="<?= $historial->imagen!=null?base_url('assets/files/'.$historial->imagen):base_url('assets/img/sinFoto.png') ?>"/></td>
                    <td><?= $historial->cantidad ?></td>
                    <td><?= $historial->nombre_unidad ?></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</section>
