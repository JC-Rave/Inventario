<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfil_controller extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model(array('Usuarios', 'Personas'));		
	}

	public function index(){
		if ($this->session->tipo_usuario=='ADMINISTRADOR') {
			$documento=$this->input->post('documento');
			$linea=$this->input->post('linea');			

			$reglas=[
				$this->session->documento,
				$documento
			];

			$error_documento=$this->unicos($reglas, 'id_usuario');
		}

		$correo=$this->input->post('correo');
		$reglas=[
			$this->session->usuario,
			$correo
		];
		$error_correo=$this->unicos($reglas, 'usuario');

		if (rules_editCuenta() || $error_correo[0] || isset($error_documento) && 
			$error_documento[0]) {
			$error_correo=$error_correo[1]==''?form_error('correo'):$error_correo[1];
			$resultado[]=array(
				'res' => 'invalid',
				'nombre' => form_error('nombre'),
				'apellido' => form_error('apellido'),
				'telefono' => form_error('telefono'),
				'correo' => $error_correo,
				'confirmar' => form_error('confirmar_correo')
			);

			if (isset($error_documento)) {
				array_push($resultado, array('documento' => $error_documento[1]));
			}

		}else{
			$nombre=$this->input->post('nombre');			
			$apellido=$this->input->post('apellido');			
			$telefono=$this->input->post('telefono');

			$recuperar=errorException_handlers('eliminar');
			try {
				//creo un transaccion para evitar que se guarden los cambios en caso
				//de que algo falle
				$this->db->trans_begin();

				//preparos los datos para hacer una actulizacion a la tabla personas
				if(!isset($documento)){$documento=$this->session->documento;}
                $datos = array(
                	'modificar' => array(
						'documento_persona' => $documento,
						'nombre_persona' => $nombre,
						'apellido_persona' => $apellido,
						'telefono_persona' => $telefono
					),
					$this->session->documento
				);

				//actualizo datos en la tabla personas y obtengo las filas afectadas
				$afectadas[]=$this->Personas->editar_persona($datos);

				//preparos los datos para hacer una actulizacion a la tabla usuarios
				if(!isset($linea)){
					if ($this->session->linea=='TICs') {
						$linea='1';
					}else if ($this->session->linea=='Biotecnología') {
						$linea='2';
					}else if ($this->session->linea=='Nanotecnología') {
						$linea='3';
					}else if ($this->session->linea=='Química') {
						$linea='4';
					}else if ($this->session->linea=='Física') {
						$linea='5';
					}else if ($this->session->linea=='Matemáticas y diseño') {
						$linea='6';
					}else if ($this->session->linea=='Electrónica y robótica'){
						$linea='7';
					}else{
						$linea='8';
					}
				}
                $datos = array(
                	'modificar' => array(
						'usuario' => $correo,
						'linea'=>$linea
					),
					$documento
				);

				//actualizo datos en la tabla usuarios y obtengo las filas afectadas
				$afectadas[]=$this->Usuarios->editar_usuario($datos);

				//consulto si hubo errores en la transaccion
				$men[]='Error al actulizar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
				$men[]='El usuario se ha actulizado correctamente.';
				$resultado=$this->transStatus($men, $afectadas);

				if ($resultado[0]['res']) {
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

					$sesion=array(
						'documento' => $documento,
						'nombre' => $nombre,
						'apellido' => $apellido,
						'telefono' => $telefono,
						'usuario' => $correo,
						'linea' => $linea
					);
					
					$this->session->set_userdata($sesion);
				}

			} catch (Error $e) {
				//cancelo los procesos generados desde que se hizo la transaccion.
		        $this->db->trans_rollback();   

		        $resultado[]=array(
		        	'res' => false,
		        	'mensaje' => 'Error: Proceso fallido. Intentelo mas tarde.'	
		        );
		        
			} catch (Exception $e) {
				//cancelo los procesos generados desde que se hizo la transaccion.
		        $this->db->trans_rollback();   

		        $resultado[]=array(
		        	'res' => false,
		        	'mensaje' => 'Error: Proceso fallido. Intentelo mas tarde.'	
		        );
			}
			errorException_handlers('recuperar', $recuperar[0], $recuperar[1]);
		}
		//retorno los resultados
		echo json_encode($resultado);
	}

	public function cargarImagen(){
		//selecciona el nombre para la imagen
		$nombre='user_'.$this->session->documento;

		//prepara la configuracion para la imagen
		$config=array(
			'upload_path' => './assets/files',
			'file_name' => $nombre,
			'allowed_types' => 'jpg|png'
		);
		
		//cargo la lireria upload con las configuraciones
		$this->load->library('upload', $config);
		
		//sube la imagen y pregunta el resultado
		if (!$this->upload->do_upload('editar_imagen')){
			//en caso de error
			//preparo los resultados a devolver
			$resultado[]=array(
				'res' => false,
				'mensaje' => 'Ocurrio un error al cargar la imagen. Intentalo mas tarde.'
			);

		}else{
			//en caso de success
			//elimino la imagen antigua con unlink en caso de haber
			if ($this->session->foto!=null) {
				unlink('assets/files/'.$this->session->foto);
			}
			
			//obtnego los datos completos de la imagen subida
			$data = $this->upload->data();

			//modifico la varible de sesion foto
			$this->session->set_userdata('foto', $data['file_name']);

			// preparo la modificacion del nombre de la imagen en DB
			$datos = array(
            	'modificar' => array(
					'img' => $data['file_name']
				),
				$this->session->documento
			);

			//modifico el nombre de la imagen en DB
			$this->Usuarios->editar_usuario($datos);

			//preparo configuracion para cambar el tamaño de la imagen
			$config=array(
				'image_library' => 'gd2',
				'source_image' => './assets/files/'.$this->session->foto,
				'maintain_ratio' => false,
				'width' => 225,
				'height' => 225
			);

			//cargo la libreria image_lib con las configuraciones
			$this->load->library('image_lib', $config);

			//cambio el tamaño de la imagen
			$this->image_lib->resize();

			//preparo los resultados a devolver
			$resultado[]=array(
				'res' => true,
				'mensaje' => 'Imagen cambiada correctamente.',
				'foto' => $this->session->foto
			);

		}

		echo json_encode($resultado);
	}

	public function transStatus($mensaje, $afectadas=''){
		if (!$this->db->trans_status()){      
	        //cancelo los procesos generados desde que se hizo la transaccion.
	        $this->db->trans_rollback();   

	        $resultado[]=array(
	        	'res' => false,
	        	'mensaje' => $mensaje[0]
	        );  
	    }else{      
	        //guardo los procesos generados desde que se hizo la transaccion. 
	        $this->db->trans_commit();    
	        
	        $resultado[]=array(
	        	'res' => true,
	        	'mensaje' => $mensaje[1],
	        	'afectadas' => $afectadas
	        );
	    }

	    return $resultado;
	}

	public function unicos($reglas, $campo){
		$error=false;
		$aux=$campo=='id_usuario'?'documento':'correo';
		$mensaje='';

		if (strcasecmp($reglas[0], $reglas[1])!=0 && $reglas[1]!='') {
			if ($this->Usuarios->validar_unico($campo, $reglas[1])) {
				$mensaje='El '.$aux.' ya se encuentra registrado';
				$error=true;
			}

		}else if ($reglas[1]=='') {
			$mensaje='El '.$aux.' no puede ser vacio';
			$error=true;
		}

		return array($error, $mensaje);
	}
}

/* End of file perfil_controller.php */
/* Location: ./application/controllers/perfil_controller.php */