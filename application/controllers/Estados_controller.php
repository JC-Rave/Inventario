<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estados_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model('Estados_productos');	
	}

	public function index(){}

	public function consultarEstados(){
		echo $this->Estados_productos->getEstados();
	}	
}

