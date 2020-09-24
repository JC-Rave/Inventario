<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unidad_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model(array('Unidadmedida'));	
	}

	public function index(){}

	public function regUnidad(){
		if (rules_regUnidad()) {
			$resultado[]=array(
				'res' => 'invalid',
				'nombre_unidad' => form_error('nombre_unidad')
			);

		}else{
            $nombre_unidad=$this->input->post('nombre_unidad');
			$recuperar=errorException_handlers('eliminar');
            try {
            	$datos = array(
					'nombre_unidad' => $nombre_unidad
				);
				$this->Unidadmedida->reg_unidad($datos);

				$resultado[]=array(
		        	'res' => true,
		        	'mensaje' => 'Unidad de medida registrada.'
		        );

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

	public function consultarUnidades(){
		echo $this->Unidadmedida->consultarUnidades();
	}

	public function editar_unidad(){
        $nombre_unidad=$this->input->post('nombre');
        $error_nombre=$this->unicos($nombre_unidad);

		if ($error_nombre[0]) {
			$resultado[]=array(
				'res' => 'invalid',
				'nombre_unidad' => $error_nombre[1]
			);

		}else{
            $estado=$this->input->post('estado');
			$recuperar=errorException_handlers('eliminar');
            try {
            	$datos = array(
	            	'modificar' => array(
						'nombre_unidad' => $nombre_unidad[1],
						'estado' => $estado
					),
					$nombre_unidad[0]
				);
				$afectadas=$this->Unidadmedida->editar_unidad($datos);

				$resultado[]=array(
		        	'res' => true,
		        	'mensaje' => 'Unidad de medida actualizada.',
		        	'afectadas' => $afectadas
		        );

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
		}
		echo json_encode($resultado);
	}

	public function unicos($reglas){
		$error=false;
		$mensaje='';

		if (strcasecmp($reglas[0], $reglas[1])!=0 && $reglas[1]!='') {
			if ($this->Unidadmedida->validar_unico('nombre_unidad', $reglas[1])) {
				$mensaje='El nombre ya se encuentra registrado';
				$error=true;
			}

		}else if ($reglas[1]=='') {
			$mensaje='El nombre no puede ser vacio';
			$error=true;
		}

		return array($error, $mensaje);
	}
}    

