<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 col-6">
        <h1 class="m-0 text-dark">Unidad de medida</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <button id="btn_preRegistrar" type="button" class="p-3 m-0 btn btn-success float-right" data-toggle="modal" data-target="#reg_unidad">Agregar medida</button>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- AQUI IRA TODO EL CONTENIDO QUE SE LE AGREGUE A LA PAGINA -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table id="tb_unidad" class="desing table table-bordered table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Estado</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($unidades as $unidad): ?>
                  <tr>
                    <td><?= $unidad->nombre_unidad; ?></td>
                    <td><center style="border-radius: 5px;" class="font-weight-bold <?= $unidad->estado=='a'?'bg-success':'bg-danger' ?>"><?= $unidad->estado=='a'?'Activo':'Inactivo' ?></center>
                    </td>    
                    <td>
                      <center>
                        <button class="editar_unidad btn btn-sm btn-warning" data-toggle="modal" data-target="#editar_unidad">
                          <i class="fas fa-edit"></i>
                        </button>
                      </center>  
                    </td>
                  </tr>
                <?php endforeach;  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</section>

<div class="modal fade" id="reg_unidad">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="titulo modal-title">Agregar medida</h4>
        <button type="button" class="cancelar close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="fm_registrar">
          <div class="form-group">
            <label for="nombre_unidad">Nombre</label>
            <input class="form-control" id="nombre_unidad" name="nombre_unidad" placeholder="ejem: paquete, unidad, etc...">
            <div id="error_nombre" class="invalid-feedback">   

            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="cancelar btn btn-default" data-dismiss="modal">Cerrar</button>
        <button id="btn_registrar" type="button" class="btn btn-primary float-right">Guardar</button> 
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="editar_unidad">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="fm_editar">
          <div class="form-group">
            <label for="edit_nombre">Nombre</label>
            <input class="form-control" id="edit_nombre" name="edit_nombre">
            <div id="error_edit_nombre" class="invalid-feedback">   

            </div>
          </div>
          <div class="form-group">
            <label for="edit_estado">Estado</label>
            <select class="custom-select" id="edit_estado" name="edit_estado">
              <option value="a">Activo</option>
              <option value="i">Inactivo</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary float-right" id="btn_editar">Editar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /.content -->
