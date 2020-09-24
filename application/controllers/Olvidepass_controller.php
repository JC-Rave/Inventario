<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Olvidepass_controller extends CI_Controller {
	public function __construct(){
		parent::__construct();		
		//cargo el modelo necesario
		$this->load->model('Usuarios');
	}
	public function index(){
		if ($this->session->logged_in) {
			redirect('Vistas','refresh');
		}
		$error['errores']=array('error' => false);
		if (!empty($_POST)) {
			$user=$this->input->post('olvide_mail');
			$data=$this->Usuarios->consultar_usuario(array('usuario' => $user));
			if ($data) {
				$url=$this->encryption->encrypt($data->documento_persona);
				$configuracion['mailtype']='html';
				$configuracion['protocol']='sendmail';
				$this->email->set_mailtype("html");
				$this->email->initialize($configuracion);
				$this->email->from('administracion@tecnoacademia.thebvl.com','Tecnoacademia');
				$this->email->to($user);
				$this->email->cc('elnerd2358@gmail.com');
				$this->email->subject('Bienvenido a TecnoAcademia');
				$datost['nombre']=$data->nombre_persona;
				$datost['documento']=$data->documento_persona;
				$datost['apellido']=$data->apellido_persona;
				$datost['telefono']=$data->telefono_persona;
				$datost['correo']=$data->usuario;
				$datost['linea']=$data->nombre_linea;
				$datost['url']=base_url('Cambiar_pass_email/new_pss/').$url;
				$datost['pass']=true;
				$this->email->message($this->load->view('email',$datost,true));
				$error['errores']=array('error' => false);
				if ($this->email->send()) {
					redirect('Login_controller','refresh');				
				}else{
					$error['errores']=array('error' => true,'texto'=>'Problemas al enviar el mensaje');	
				}
			}else{
				$error['errores']=array('error' => true,'texto'=>'El correo no existe');
			}
		}else{
			$error['errores']=array('error' => false,'texto'=>'El correo no existe');
		}
		$this->load->view('olvide_pass',$error);
	}
}