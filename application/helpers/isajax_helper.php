<?php 
	function is_ajax(){
		$CI =& get_instance();
		if (!$CI->input->is_ajax_request()) {
			redirect('Vistas','refresh');
		}
	}
