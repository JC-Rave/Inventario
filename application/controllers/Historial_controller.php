<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model('Personas');		
	}

	public function index(){
		$resultado['usuarios']=$this->Personas->consultarPersonas();
		echo json_encode($resultado);		
	}

}

/* End of file Historial_controller.php */
/* Location: ./application/controllers/Historial_controller.php */