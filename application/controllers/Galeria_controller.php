<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galeria_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model('Galeria_productos');	
	}

	public function index(){}

	public function registrar_imagen(){
		if (rules_regImagen()) {
			$this->form_validation->set_error_delimiters('', '');
			$resultado[]=array(
				'res' => 'invalid',
				'nombre' => form_error('nombre'),
				'imagen' => ''
			);
		}else{
			$recuperar=errorException_handlers('eliminar');
			try {
				$this->db->trans_begin();

				$nombre=$this->input->post('nombre');
				$config=array(
					'upload_path' => './assets/files',
					'file_name' => 'producto_',
					'allowed_types' => 'jpg|png'
				);
				
				//cargo la lireria upload con las configuraciones
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('imagen')){
					//en caso de error
					
					$resultado[]=array(
						'res' => 'invalid',
						'nombre' => "",
						'imagen' => "Error al cargar la imagen"
					);

					echo json_encode($resultado);
			        $this->db->trans_rollback();
					return;
				}else{
					$data = $this->upload->data('file_name');
					$this->Galeria_productos->registrar(array(
						"nombre"=>$nombre, 
						"imagen"=>$data
					));

					//preparo configuracion para cambar el tamaño de la imagen
					$config=array(
						'image_library' => 'gd2',
						'source_image' => './assets/files/'.$data,
						'maintain_ratio' => false,
						'width' => 500,
						'height' => 400
					);

					//cargo la libreria image_lib con las configuraciones
					$this->load->library('image_lib', $config);

					//cambio el tamaño de la imagen
					if (!$this->image_lib->resize()){
						//en caso de error
						$resultado[]=array(
							'res' => false,
							'mensaje' => "Error al registrar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.",
						);

						echo json_encode($resultado);
				        $this->db->trans_rollback();
						unlink('assets/files/'.$data);
						return;
					}

					//consulto si hubo errores en la transaccion
					$men[]='Error al registrar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
					$men[]='Imagen registrada con exito.';
					$resultado=$this->transStatus($men, $data);
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

		echo json_encode($resultado);
	}

	public function editar_imagen($imagen, $nom_actual){
		$nombre=$this->input->post('editnombre');
		$nom_actual=urldecode($nom_actual);
		$reglas=[
			$nom_actual,
			$nombre
		];

		$error_nombre=$this->unicos($reglas);
		if ($error_nombre[0]) {
			$resultado[]=array(
				'res' => 'invalid',
				'nombre' => $error_nombre[1],
				'imagen' => ''
			);

		}else{
			$recuperar=errorException_handlers('eliminar');
			try {
				$this->db->trans_begin();

				$nombre_img[]=$this->Galeria_productos->consultarImagen($reglas[0]);;
				if ($imagen=='true') {
					$config=array(
						'upload_path' => './assets/files',
						'file_name' => 'producto_',
						'allowed_types' => 'jpg|png'
					);
					
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('editimagen')){
						//en caso de error
			        	$resultado[]=array(
							'res' => 'invalid',
							'nombre' => '',
							'imagen' => 'Error al cargar la imagen'
						);

						echo json_encode($resultado);
				        $this->db->trans_rollback();
						return;

					}else{
						$data = $this->upload->data('file_name');

						//preparo configuracion para cambar el tamaño de la imagen
						$config=array(
							'image_library' => 'gd2',
							'source_image' => './assets/files/'.$data,
							'maintain_ratio' => false,
							'width' => 500,
							'height' => 400
						);

						$this->load->library('image_lib', $config);
						if (!$this->image_lib->resize()){
							//en caso de error
							$resultado[]=array(
								'res' => false,
								'mensaje' => "Error al editar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.",
							);

							echo json_encode($resultado);
					        $this->db->trans_rollback();
							unlink('assets/files/'.$data);
							return;
						}

						$nombre_img[]=$data;
					}

				}else{
					$nombre_img[]='';
				}

				$datos = array(
	            	'modificar' => array(
						'nombre' => $reglas[1],
						'imagen' => empty($nombre_img[1])?$nombre_img[0]:$nombre_img[1]
					),
					$reglas[0]
				);
				$afectadas=$this->Galeria_productos->editar($datos);

				//consulto si hubo errores en la transaccion
				$men[]='Error al editar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
				$men[]='Imagen editada con exito.';
				$resultado=$this->transStatus($men, $nombre_img, $afectadas);

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

		echo json_encode($resultado);
	}

	public function transStatus($mensaje, $data, $afectadas=''){
		if (!$this->db->trans_status()){      
	        //cancelo los procesos generados desde que se hizo la transaccion.
	        $this->db->trans_rollback();   

	        $resultado[]=array(
	        	'res' => false,
	        	'mensaje' => $mensaje[0]
	        );

	        if (file_exists(base_url('assets/files/'.$data[1]))) {
				unlink('assets/files/'.$data[1]);
	        }

	    }else{      
	        //guardo los procesos generados desde que se hizo la transaccion. 
	        $this->db->trans_commit();    
	        
	        $resultado[]=array(
	        	'res' => true,
	        	'mensaje' => $mensaje[1],
	        	'imagen' => empty($data[1])?$data[0]:$data[1],
	        	'afectadas' => $afectadas
	        );

	        if (!empty($data[1])) {
				unlink('assets/files/'.$data[0]);
	        }
	    }

	    return $resultado;
	}

	public function unicos($reglas){
		$error=false;
		$mensaje='';

		if (strcasecmp($reglas[0], $reglas[1])!=0 && $reglas[1]!='') {
			if ($this->Galeria_productos->validar_unico('nombre', $reglas[1])) {
				$mensaje='El nombre ya se encuentra registrado.';
				$error=true;
			}

		}else if ($reglas[1]=='') {
			$mensaje='El nombre no puede ser vacio';
			$error=true;
		}

		return array($error, $mensaje);
	}
	public function consultarImagenesDevolutivo(){
		echo $this->Galeria_productos->consultarImagenesProd();
	}

}

/* End of file Galeria_controller.php */
/* Location: ./application/controllers/Galeria_controller.php */