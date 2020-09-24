<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 col-6">
        <h1 class="m-0 text-dark">Galeria de imagenes</h1>
      </div><!-- /.col -->
      <div class="col-sm-6 col-6">
        <button id="btn_preRegistrar" type="button" class="p-3 m-0 btn btn-success float-right" data-toggle="modal" data-target="#md_registrar">Agregar Imagen</button>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Main row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div id="imgs" class="row">
              <?php foreach ($imagenes as $imagen): ?>
                <div class="col-lg-4 col-sm-6">
                  <figure>
                    <img style="cursor: pointer;" class="border p-1 img-fluid" src="<?= base_url('assets/files/'.$imagen->imagen) ?>" alt="">
                    <figcaption class="col">
                      <div class="row justify-content-between pt-1">
                        <div class="col-md-10"><?= $imagen->nombre ?></div>
                        <div>
                          <button class="btn_preEditar m-0 p-1 btn btn-warning" data-toggle="modal" data-target="#md_editar">
                            <i class="fas fa-edit"></i>
                          </button>
                        </div>
                      </div>
                    </figcaption>
                  </figure>
                </div>
              <?php endforeach; ?>
              <?php if(empty($imagenes)): ?>
                <div class="col">
                  <table class="table table-bordered table-hover text-nowrap">
                    <tbody>
                      <tr>
                        <td style="border-top-width: 0px; text-align: center;">
                          <p class="font-weight-bold m-0" style="font-size: 18px;">No se encontraron resultados</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="paginador float-sm-right">
                  <ul id="paginacion">

                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>

<!-- modal agregar imagen-->
<div class="modal fade" id="md_registrar">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Agregar Imagen</h4>
        <button type="button" class="cancelar close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="registrar_imagen">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nombre">Nombre</label>
                <input class="form-control" id="nombre" name="nombre" placeholder="nombre de la imagen">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="imagen">Imagen</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" accept="image/png, .jpeg, .jpg" class="custom-file-input" id="imagen" name="imagen">
                    <label id="holder_imagen" class="custom-file-label" for="imagen">Seleccionar Imagen</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <figure class="col pb-1 pt-1 d-flex justify-content-center">
                  <img width="451" height="320" class="border p-1" id="ver_imagen" alt="nueva imagen" src="">
                </figure>
              </div>          
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <p>En esta sección podrás agregar imagenes para los productos.<br><strong class="font-weight-bold">Nota: Todos los campos son obligatorios.</strong></p>
        <div>
          <button type="button" class="cancelar btn btn-default" data-dismiss="modal">Cancelar</button>  
          <button id="btn_registrar" type="button" class="btn btn-primary float-right">Guardar</button>     
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- modal editar imagen-->
<div class="modal fade" id="md_editar">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title"></h4>
        <button type="button" class="cancelar close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" id="editar_imagen">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="form-group">
                <label for="editnombre">Nombre</label>
                <input class="form-control" id="editnombre" name="editnombre" placeholder="nombre de la imagen">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="editimagen">Imagen</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" accept="image/png, .jpeg, .jpg" class="custom-file-input" id="editimagen" name="editimagen">
                    <label id="editholder_imagen" class="custom-file-label" for="editimagen">Seleccionar Imagen</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <figure class="col pb-1 pt-1 d-flex justify-content-center">
                  <img width="451" height="320" class="border p-1" id="editver_imagen" alt="" src="">
                </figure>
              </div>          
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <p>En esta sección podrás editar las imagenes para los productos.<br><b class="font-weight-bold">Nota: Todos los campos son obligatorios.</b></p>
        <div>
          <button type="button" class="cancelar btn btn-default" data-dismiss="modal">Cancelar</button>  
          <button id="btn_editar" type="button" class="btn btn-primary float-right">Editar</button>     
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>