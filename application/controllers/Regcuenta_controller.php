<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regcuenta_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//cargo la libreria de encriptacion para la contraseña
		$this->load->library(array('form_validation'));

		//cargo helper generador de strign random(para genar la contraseña)
		$this->load->helper('string');

		//cargo el modelo necesario
		$this->load->model(array('Usuarios', 'Personas'));
	} 

	public function index(){
		if ($this->session->logged_in) {
			redirect('Vistas','refresh');
		}

		$error['errores']=array('opc_linea' => '');

		if (!empty($_POST)) {
			if (rules_regCuenta()) {
				$this->form_validation->set_error_delimiters('', '');
				$error['errores']=array(
					'invalid' => true,
					'opc_linea' => $this->input->post('linea'),
					'cedula' => form_error('cedula'),
					'nombre' => form_error('nombre'),
					'apellido' => form_error('apellido'),
					'telefono' => form_error('celular'),
					'correo' => form_error('correo'),
					'confirmar' => form_error('confirm_correo'),
					'linea' => form_error('linea')
				);
			}else{
				//obtengo los datos a registrar
				$cedula=$this->input->post('cedula');
				$nombre=$this->input->post('nombre');
				$apellido=$this->input->post('apellido');
				$telefono=$this->input->post('celular');
				$correo=$this->input->post('correo');
				$linea=$this->input->post('linea');

				/* genero una contraseña con php nativo(comentado) o con el helper
				  string
			      $pass=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
				*/ 	
				$pass=random_string('alnum', 10);
				$recuperar=errorException_handlers('eliminar');
				try {
					$this->db->trans_begin();

					//preparos los datos para hacer una inserccion a la tabla personas
	                $datos = array(
						'documento_persona' => $cedula,
						'nombre_persona' => $nombre,
						'apellido_persona' => $apellido,
						'telefono_persona' => $telefono
					);

	                //inserto datos en la tabla personas
					$this->Personas->reg_persona($datos);

					//preparos los datos para hacer una inserccion a la tabla usuarios
					$verdPass=$this->encryption->encrypt($pass);
					$datos = array(
						'id_usuario' => $cedula,
						'usuario' => $correo,
						'password' => $verdPass,
						'tipo_usuario' => '2',
						'linea' => $linea
					);
					// envío el correo
					if ($linea=='1') {
						$linea='TICs';
					}else if ($linea=='2') {
						$linea='Biotecnología';
					}else if ($linea=='3') {
						$linea='Nanotecnología';
					}else if ($linea=='4') {
						$linea='Química';
					}else if ($linea=='5') {
						$linea='Física';
					}else if ($linea=='6') {
						$linea='Matemáticas y diseño';
					}else if ($linea=='7'){
						$linea='Electrónica y robótica';
					}else{
						$linea='Administrativa';
					}
					$configuracion['mailtype']='html';
					$configuracion['protocol']='sendmail';
					$this->email->set_mailtype("html");
					$this->email->initialize($configuracion);
					$this->email->from('administracion@tecnoacademia.thebvl.com','Tecnoacademia');
					$this->email->to($correo);
					$this->email->cc('elnerd2358@gmail.com');
					$this->email->subject('Bienvenido a TecnoAcademia');
					$datost['pass']=false;
					$datost['nombre']=$nombre;
					$datost['documento']=$cedula;
					$datost['apellido']=$apellido;
					$datost['telefono']=$telefono;
					$datost['correo']=$correo;
					$datost['linea']=$linea;
					$datost['contrasena']=$this->encryption->decrypt($verdPass);
					$this->email->message($this->load->view('email',$datost,true));
					if ($this->email->send()) {
						//inserto datos en la tabla usuarios
						$this->Usuarios->reg_usuario($datos);

						

						$sesion=array(
							'logged_in' => true,
							'documento' => $cedula,
							'nombre' => $nombre,
							'apellido' => $apellido,
							'telefono' => $telefono,
							'usuario' => $correo,
							'tipo_usuario' => 'INSTRUCTOR',
							'linea' => $linea,
							'foto' => null,
							'estado' => 'a'
						);

						if (!$this->db->trans_status()){      
					        //cancelo los procesos generados desde que se hizo la transaccion.
					        $this->db->trans_rollback();   

					    }else{      
					        //guardo los procesos generados desde que se hizo la transaccion. 
					        $this->db->trans_commit();    
					        
							$this->session->set_userdata($sesion);
							redirect('Vistas','refresh');
					    }
					}else{
						$this->db->trans_rollback();   						
					}
	                

				} catch (Error $e) {
					//cancelo los procesos generados desde que se hizo la transaccion.
			        $this->db->trans_rollback();   

			        $resultado[]=array(
			        	'res' => false,
			        	'mensaje' => 'Error: Proceso fallido. Intentelo mas tarde.'	
			        );
			        
				}
				errorException_handlers('recuperar', $recuperar[0], $recuperar[1]);
			}
		}
		$this->load->view('reg_cuenta', $error);
	}

}