<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lineas_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model('Lineas_model');	
	}

	public function index(){}

	public function consultarLineas(){
		echo $this->Lineas_model->getLineas();
	}	
}

