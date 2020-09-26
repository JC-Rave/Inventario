<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-md-6 col-6"> 
            <h1 class="m-0 text-dark">Categorias</h1>                                  
          </div><!-- /.col -->
          <div class="col-md-6 col-6">
              <button id="btn-agregar" type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#add_categoria">Agregar Categoria</button>                                  
          </div>          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
</div>
<input id="consult_cat" type="hidden" name="" value="<?php echo base_url('Categorias_controller/consultarCategorias') ?>">
<input id="delet_cat" type="hidden" name="" value="<?php echo base_url('index.php/Categorias_controller/eliminarCategoria')?>">
<!-- /.content-header -->
<div id="alert" style="display:none; position: fixed; right: 0px; top: 66px; z-index: 100">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong id="alert-strong"></strong> 
</div>
<!-- Main content -->
<section class="content">
      <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
          <!-- AQUI IRA TODO EL CONTENIDO QUE SE LE AGREGUE A LA PAGINA -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">                             
                <table id="table-categorias" class="desing table table-bordered table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Categoria</th>
                      <th>Descripción</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="tbody_categorias">                        
                  </tbody>
                </table>                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<div class="modal fade" id="add_categoria">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h4 class="modal-title">Registro de Categoria</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form role="form" id="add_form_categoria" action="<?php echo base_url('index.php/Categorias_controller/agregarCategoria');?>" method="POST">
              <div class="form-group">
                <label for="add_nom_cat">Nombre de la categoria</label>
                <input type="text" class="form-control" id="add_nom_cat" name="add_nom_cat" placeholder="Papeleria" required="true">
              </div>
              <div class="form-group">
                <label for="add_descripcion_cat">Descripción de la categoria</label>
                <input type="text" class="form-control" name="add_descripcion_cat" id="add_descripcion_cat" placeholder="Añada una descripción" required="true">
              </div>
              <div class="form-group">
                <label for="estado_cat">Estado de la categoria</label>
                <select id="add_estado_cat" name="add_estado_cat" class="form-control" required="true">
                  <option selected="true" value="a">Activo</option>
                  <option value="i">Inactivo</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between align-items-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <p class="m-0 font-weight-bold" id="text-add-categoria"></p>
            <button id="btn_add_categoria" type="button" class="btn btn-primary">Agregar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="edit_categoria">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h4 class="modal-title" id="edit_tittle_categoria">Categoria</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form role="form" id="edit_form_categoria" action="<?php echo base_url('index.php/Categorias_controller/modificarCategoria');?>" method="POST">              
              <div class="form-group">
                <label for="edit_nom_cat">Nombre de la categoria</label>
                <input type="text" class="form-control" name="edit_nom_cat" id="edit_nom_cat" placeholder="Papeleria" required="true">
                <input type="hidden" class="form-control" name="edit_id_cat" id="edit_id_cat">
              </div>
              <div class="form-group">
                <label for="edit_descripcion_cat">Descripción de la categoria</label>
                <input type="text" class="form-control" id="edit_descripcion_cat" name="edit_descripcion_cat" placeholder="Añada una descripción"required="true">
              </div>
              <div class="form-group">
                <label for="edit_estado_cat">Estado de la categoria</label>
                <select id="edit_estado_cat" name="edit_estado_cat" class="form-control" required="true">
                  <option value="a">Activo</option>
                  <option value="i">Inactivo</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <p class="text-danger m-0 font-weight-bold" id="text-edit-categoria"></p>
            <button id="btn_edit_categoria" type="button" class="btn btn-primary">Modificar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>

   
  