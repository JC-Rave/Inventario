<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cambiar_pass_email extends CI_Controller {
	public function index(){
		if (!empty($_POST)) {

		}
		$this->load->view('cambiar_pass_email');
	}
	public function new_pss(){
		$error['errores']=array('error' => false,'exito'=>false);
		
		$this->load->view('cambiar_pass_email',$error);	
	}
}

/* End of file Cambiar_pass_email.php */
/* Location: ./application/controllers/Cambiar_pass_email.php */