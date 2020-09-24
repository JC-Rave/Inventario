<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_controller extends CI_Controller {
	
	public function __construct(){
		parent::__construct();		
		//cargo el modelo necesario
		$this->load->model('Usuarios');
	}

	public function index(){
		if ($this->session->logged_in) {
			redirect('Vistas','refresh');
		}

		$error['error']='';

		if (!empty($_POST)) {
			//obtengo las credenciales
			$user=$this->input->post('user');
			$pass=$this->input->post('password');

			//obtengo el resultado de la consulta
			$datos=$this->Usuarios->consultar_usuario(array('usuario' => $user));
			if ($datos) {
				if ($pass===$this->encryption->decrypt($datos->password)) {
					if ($datos->estado=='a') {
						$sesion=array(
							'logged_in' => true,
							'documento' => $datos->documento_persona,
							'nombre' => $datos->nombre_persona,
							'apellido' => $datos->apellido_persona,
							'telefono' => $datos->telefono_persona,
							'usuario' => $user,
							'tipo_usuario' => $datos->nombre_tipo,
							'linea' => $datos->nombre_linea,
							'foto' => $datos->img,
							'estado' => $datos->estado
						);
						
						$this->session->set_userdata($sesion);
						redirect('Vistas','refresh');

					}else{
						$error['error']='El usuario esta inhabilitado. Comunicate con el administrador para resolver su problema.';
					}

				}else{
					$error['error']='Usuario y/o contraseña incorrecta.';
				}

			}else{
				$error['error']='Usuario y/o contraseña incorrecta.';
			}
		}

		$this->load->view('login', $error);
	}

	public function cambiar_pass(){
		if ($this->input->is_ajax_request()) {
			if (rules_editPass() && empty($this->input->post('documento'))){
				$this->form_validation->set_error_delimiters('', '');
				$resultado[]=array(
					'res' => 'invalid',
					'actual' => form_error('actual_pass'),
					'nueva' => form_error('new_pass'),
					'confirmar' => form_error('confirm_new_pass')
				);
			}else{
				$nueva=$this->input->post('new_pass');
				$confirmar=$this->input->post('confirm_new_pass');
				if (!empty($nueva) && !empty($confirmar) && $nueva===$confirmar) {
					if (!empty($this->input->post('documento'))) {
						$documento=$this->input->post('documento');
						$actual="";
					}else{
						$actual=$this->input->post('actual_pass');	
					}
					//consulto el pass del usuario en DB y la verifico con la ingresada
					if (!empty($this->input->post('documento'))) {
						$pass=$this->Usuarios->consultar_passUsuario($documento);
					}else{
						$pass=$this->Usuarios->consultar_passUsuario();	
					}
					if ($actual===$this->encryption->decrypt($pass->password) || !empty($this->input->post('documento'))) {
						//preparos los datos para hacer una actulizacion a la tabla usuarios
						if (!empty($this->input->post('documento'))) {
							$datos = array(
			                	'modificar' => array(
									'password' => $this->encryption->encrypt($nueva),
								),
								$documento
							);
						}else{
			                $datos = array(
			                	'modificar' => array(
									'password' => $this->encryption->encrypt($nueva),
								),
								$this->session->documento
							);
						}
						$afectadas=$this->Usuarios->editar_usuario($datos);

						if ($afectadas==1) {
							$resultado[]=array(
								'res' => true,
								'mensaje' => 'Contraseña Cambiada correctamente.'
							);
							
						}else{
							$resultado[]=array(
								'res' => 'sin cambios',
								'mensaje' => 'No ha echo ningun cambio.'
							);
						}
						
					}else{
						$resultado[]=array(
							'res' => false,
							'actual' => 'Contraseña Incorrecta.'
						);
					}					
				}else{
					if (empty($nueva) && empty($confirmar)) {
						$resultado[]=array(
							'res' => 'invalid',
							'actual' => '',
							'nueva' => 'Campo vacío',
							'confirmar'=> 'Campo vacío'
						);	
					}else{
						$resultado[]=array(
							'res' => 'invalid',
							'actual' => '',
							'nueva' => '',
							'confirmar'=> 'La contraseña no es igual'
						);	
					}
				}
			}

			echo json_encode($resultado);

		}else{
			show_404();
		}
	}

	public function consultar_sesion(){
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->session->logged_in);

		}else{
			show_404();
		}
	}

	public function logout(){
		$logout = array(
			'logged_in', 
			'documento', 
			'nombre', 
			'apellido',
			'telefono', 
			'usuario', 
			'tipo_usuario', 
			'linea', 
			'foto', 
			'estado'
		);

		$this->session->unset_userdata($logout);
		redirect('Login_controller','refresh');
	}
}
