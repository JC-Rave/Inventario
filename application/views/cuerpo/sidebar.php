<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo --> 
  <a href="#" class="brand-link">
    <img src="<?= base_url('assets/img/logo_sena.png'); ?>" alt="Logo" class="brand-image img-circle elevation-3 p-1"
         style="opacity: .8;background-color: rgb(255,255,255);">
    <span class="brand-text font-weight-light">TecnoAcademia</span>
  </a> 

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a id="panel" href="<?= base_url('Vistas'); ?>" class="nav-link 
            <?= $this->uri->segment(2)==''?'active':''; ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Panel de Control
            </p>
          </a>
        </li>
  
        <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
          <li id="acc" class="nav-item has-treeview <?= $this->uri->segment(2)=='adm_usuarios'|| $this->uri->segment(2)=='reg_usuario'?'menu-open':''; ?>">
            <a href="#" class="nav-link 
            <?= $this->uri->segment(2)=='adm_usuarios' || $this->uri->segment(2)=='reg_usuario'?
            'active':''; ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Accesos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a id="user_adm" href="<?= base_url('Vistas/adm_usuarios'); ?>" class="nav-link 
                  <?= $this->uri->segment(2)=='adm_usuarios'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Administrar Usuarios</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="user_reg" href="<?= base_url('Vistas/reg_usuario'); ?>" class="nav-link 
                  <?= $this->uri->segment(2)=='reg_usuario'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registrar Usuarios</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif ?>

        <li class="nav-item">
          <a id="prov" href="<?= base_url('Vistas/adm_proveedores'); ?>" class="nav-link <?= $this->uri->segment(2)=='adm_proveedores'?
          'active':''; ?>">
            <i class="nav-icon fas fa-truck"></i>
            <p>
              Administrar Proveedor
            </p>
          </a>
        </li>

        <li id="inven" class="nav-item has-treeview <?= $this->uri->segment(2)==
        'adm_materiales' || $this->uri->segment(2)=='adm_devolutivos'?'menu-open':''; ?>">
          <a href="#" class="nav-link <?= $this->uri->segment(2)=='adm_materiales' || 
          $this->uri->segment(2)=='adm_devolutivos'?'active':''; ?>">
            <i class="nav-icon fas fa-box-open"></i><i class=""></i>
            <p>
              Inventario
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a id="inv_mat" href="<?= base_url('Vistas/adm_materiales'); ?>" class="nav-link <?= $this->uri->segment(2)=='adm_materiales'?'active':''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Administrar Materiales</p>
              </a>
            </li>
            <li class="nav-item">
              <a id="inv_dev" href="<?= base_url('Vistas/adm_devolutivos'); ?>" class="nav-link <?= $this->uri->segment(2)=='adm_devolutivos'?'active':''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Administrar Devolutivos</p>
              </a>
            </li>
          </ul>
        </li>

        <li id="pp" class="nav-item has-treeview <?= $this->uri->segment(2)=='adm_pedidos'
        || $this->uri->segment(2)=='detalle_pedido'?'menu-open':''; ?>">
          <a href="#" class="nav-link <?= $this->uri->segment(2)=='adm_pedidos' || $this->uri->segment(2)=='detalle_pedido'?'active':''; ?>">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Pedidos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a id="ped" href="<?= base_url('Vistas/adm_pedidos'); ?>" class="nav-link 
                <?= $this->uri->segment(2)=='adm_pedidos' || $this->uri->segment(2)=='detalle_pedido'?'active':''; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Administrar Pedidos</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a id="prov" href="<?= base_url('Vistas/adm_solicitudes'); ?>" class="nav-link 
            <?= $this->uri->segment(2)=='adm_solicitudes' || $this->uri->segment(2)=='reg_solicitud'?'active':''; ?>">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
              Administrar Solicitud
            </p>
          </a>
        </li>

        <?php if ($this->session->tipo_usuario=='INSTRUCTOR'): ?>
          <li class="nav-item">
            <a id="gal" href="<?= base_url('Vistas/galeria'); ?>" class="nav-link 
              <?= $this->uri->segment(2)=='galeria'?'active':''; ?>">
              <i class="nav-icon far fa-image"></i>
              <p>
                Galeria de Imagenes
              </p>
            </a>
          </li>
        <?php endif ?>
        
        <?php if ($this->session->tipo_usuario=='ADMINISTRADOR'): ?>
          <li id="exts" class="nav-item has-treeview <?= $this->uri->segment(2)=='unidad_medida' || $this->uri->segment(2)=='categorias' || $this->uri->segment(2)=='galeria'?'menu-open':''; ?>">
            <a href="#" class="nav-link <?= $this->uri->segment(2)=='unidad_medida'
              || $this->uri->segment(2)=='categorias' || $this->uri->segment(2)=='galeria'?'active':''; ?>">
              <i class="nav-icon far fa-plus-square"></i>
              <p>
                Extras
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a id="ext_gal" href="<?= base_url('Vistas/galeria'); ?>" class="nav-link 
                  <?= $this->uri->segment(2)=='galeria'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Galeria de Imagenes</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="ext_med" href="<?= base_url('Vistas/unidad_medida'); ?>" class="nav-link 
                  <?= $this->uri->segment(2)=='unidad_medida'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Unidad de Medida</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="ext_cat" href="<?= base_url('Vistas/categorias'); ?>" class="nav-link 
                  <?= $this->uri->segment(2)=='categorias'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categorias</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif ?>

        <li class="nav-item">
          <a href="<?= base_url('Vistas/historial_consumo'); ?>" class="nav-link 
            <?= $this->uri->segment(2)=='historial_consumo'?'active':''; ?>">
            <i class="nav-icon fas fa-history"></i>
            <p>
              Historial de Consumo
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
<div id="view" class="content-wrapper">