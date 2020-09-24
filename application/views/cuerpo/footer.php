		</div>
		<!-- /.content-wrapper -->
		 
		<footer class="main-footer">
			<strong>SENA &copy; 2019-2020 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
			Todos los derechos reservados
			<div class="float-right d-none d-sm-inline-block">
			  <b>Version</b> 3.0.2 
			</div>
		</footer>
	</div> 
	<!-- ./wrapper -->

	<!-- jQuery -->
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
	<!-- identifico la vista en la que estoy --> 
	<?php 
	 	$start="<script src=".base_url();
	 	$end="></script>";
	 	$scripts='';
	 	
		if ($this->uri->segment(2)=='reg_usuario' || $this->uri->segment(2)=='adm_usuarios'){
			$scripts=$start.='assets/js/acceso.js'.$end;

		}else if ($this->uri->segment(1)=='Vistas' && $this->uri->segment(2)=='' || $this->uri->segment(1)=='vistas' && $this->uri->segment(2)=='') {
			$scripts=$start.'assets/js/vistas.js'.$end;
			
		}else if ($this->uri->segment(2)=='adm_proveedores') {
			$scripts=$start.'assets/js/proveedores.js'.$end;
			
		}else if ($this->uri->segment(2)=='categorias') {
			$scripts=$start.'assets/js/categorias.js'.$end;	
			
		}else if ($this->uri->segment(2)=='unidad_medida') {
			$scripts=$start.'assets/js/medida.js'.$end;	
			
		}else if ($this->uri->segment(2)=='adm_devolutivos') {
			$scripts=$start.'assets/js/devolutivos.js'.$end;
			$scripts.=$start.'plugins/imageselect/imageselect.js'.$end;	
			
		}else if ($this->uri->segment(2)=='adm_materiales') {
			$scripts=$start.'assets/js/materiales.js'.$end;	
			$scripts.=$start.'plugins/imageselect/imageselect.js'.$end;	
			
		}else if ($this->uri->segment(2)=='perfil') {
			$scripts=$start.'assets/js/perfil.js'.$end;	
			
		}else if ($this->uri->segment(2)=='cambiar_pass') {
			$scripts=$start.'assets/js/cambiar_pass.js'.$end;	
			
		}else if ($this->uri->segment(2)=='adm_solicitudes') {
			$scripts=$start.'assets/js/solicitudes.js'.$end;	
			
		}else if ($this->uri->segment(2)=='reg_solicitud') {
			$scripts=$start.'assets/js/reg_solicitud.js'.$end;
			$scripts.=$start.'plugins/Select2/dist/js/select2.full.min.js'.$end;
			$scripts.=$start.'plugins/imageselect/imageselect.js'.$end;
			
		}else if ($this->uri->segment(2)=='adm_pedidos') {
			$scripts=$start.'assets/js/pedidos.js'.$end;	
			
		}else if ($this->uri->segment(2)=='reg_pedido') {
			$scripts=$start.'assets/js/reg_pedidos.js'.$end;
			$scripts.=$start.'plugins/Select2/dist/js/select2.full.min.js'.$end;
			$scripts.=$start.'plugins/imageselect/imageselect.js'.$end;	

		}else if ($this->uri->segment(2)=='detalle_pedido') {
			$scripts=$start.'assets/js/reg_pedidos.js'.$end;
			$scripts.=$start.'plugins/Select2/dist/js/select2.full.min.js'.$end;
			$scripts.=$start.'plugins/imageselect/imageselect.js'.$end;

		}else if ($this->uri->segment(2)=='galeria') {
			$scripts=$start.'assets/js/galeria.js'.$end;	
			$scripts.=$start.'plugins/Twbs-Pagination/jquery.twbsPagination.min.js'.$end;
			
		}else if ($this->uri->segment(2)=='historial_consumo') {
			$scripts=$start.'plugins/daterangepicker/moment.min.js'.$end;
			$scripts.=$start.'plugins/daterangepicker/daterangepicker.js'.$end;
			$scripts.=$start.'assets/js/historial.js'.$end;
			
		}
		

		echo $scripts;
	?>
		

</body>
</html>