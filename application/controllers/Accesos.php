<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accesos extends CI_Controller { 

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model(array('Usuarios', 'Personas'));	
	}

	public function index(){}

	public function registrar_usuario(){
		//funcion del helper validaciones que cargo automaticamente con autoload.php
		//y sirve para confirmar que los campos ingresados sean correctos
		if (rules_regUsuario()){
			//si falla guardo los errores individualmente con form_error('nombre del input') en un arreglo
			$resultado[]=array(
				'res' => 'invalid',
				'cedula' => form_error('cedula'),
				'nombre' => form_error('nombre'),
				'apellido' => form_error('apellido'),
				'telefono' => form_error('telefono'),
				'correo' => form_error('correo'),
				'confirmar' => form_error('confirmar'),
				'tipo_user' => form_error('tipo_user'),
				'linea' => form_error('linea')
			);
		}else{
			//cargo la libreria de encriptacion para la contraseña
			$this->load->library('encryption');

			//cargo helper generador de strign random(para genar la contraseña)
			$this->load->helper('string');

			//obtengo los valores de los inputs llegados por post desde el archivo acceso.js
			$cedula=$this->input->post('cedula');
			$nombre=$this->input->post('nombre');
			$apellido=$this->input->post('apellido');
			$telefono=$this->input->post('telefono');
			$correo=$this->input->post('correo');
			$confirmar=$this->input->post('confirmar');
			$tipo_user=$this->input->post('tipo_user');
			$linea=$this->input->post('linea');

			/* genero una contraseña con php nativo(comentado) o con el helper
			  string
		      $pass=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
			*/ 	
			$pass=random_string('alnum', 10);
			$recuperar=errorException_handlers('eliminar');
			try {
				//creo un transaccion para evitar que se guarden los cambios en caso
				//de que algo falle
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
				$datos = array(
					'id_usuario' => $cedula,
					'usuario' => $correo,
					'password' => $this->encryption->encrypt($pass),
					'tipo_usuario' => $tipo_user,
					'linea' => $linea
				);

                //inserto datos en la tabla usuarios
				$this->Usuarios->reg_usuario($datos);

				//consulto si hubo errores en la transaccion
				$men[]='Error al registrar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
				$men[]='Se ha registrado la cuenta con exito. El usuario y la contraseña se han enviado al correo registrado.';
				$resultado=$this->transStatus($men);

				if ($resultado[0]['res'] && $tipo_user=='1') {
	                $datos = array(
	                	'modificar' => array(
							'tipo_usuario' => '2',
						),
						$this->session->documento
					);
					$this->Usuarios->editar_usuario($datos);

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

	public function consultarUsuarios(){
		echo $this->Usuarios->getUsuariosActivos(true);		
	}

	public function editar_usuario(){
		$documentos=$this->input->post('documento');
		$usuarios=$this->input->post('usuario');
		$reglas=[
			$documentos[0],
			$documentos[1],
			$usuarios[0],
			$usuarios[1]
		];
		$errores=$this->unicos($reglas);

		// funcion helper
		if (rules_editUsuario() || $errores[0]) {
			//genero un arreglo con los errores de validacion
			$resultado[]=array(
				'res' => 'invalid',
				'cedula' => $errores[1]['documento'],
				'nombre' => form_error('nombre'),
				'apellido' => form_error('apellido'),
				'telefono' => form_error('telefono'),
				'usuario' => $errores[1]['usuario'],
				'estado' => form_error('estado'),
				'tipo_user' => form_error('tipo_user'),
				'linea' => form_error('linea')
			);

		}else{
			//obtengo los valores de los inputs llegados por post desde el archivo acceso.js
			$documento=$reglas[1];
			$nombre=$this->input->post('nombre');
			$apellido=$this->input->post('apellido');
			$telefono=$this->input->post('telefono');
			$usuario=$reglas[3];
			$tipo_user=$this->input->post('tipo_user');
			$linea=$this->input->post('linea');
			$estado=$this->input->post('estado');

			$recuperar=errorException_handlers('eliminar');
			try {
				//creo un transaccion para evitar que se guarden los cambios en caso
				//de que algo falle
				$this->db->trans_begin();

				//preparos los datos para hacer una actulizacion a la tabla personas
                $datos = array(
                	'modificar' => array(
						'documento_persona' => $documento,
						'nombre_persona' => $nombre,
						'apellido_persona' => $apellido,
						'telefono_persona' => $telefono
					),
					$reglas[0]
				);

				//actualizo datos en la tabla personas y obtengo las filas afectadas
				if ($tipo_user=='1' && $estado=='a' || $tipo_user=='2') {
					$afectadas[]=$this->Personas->editar_persona($datos);
				}

				//preparos los datos para hacer una actulizacion a la tabla usuarios
                $datos = array(
                	'modificar' => array(
						'usuario' => $usuario,
						'tipo_usuario'=>$tipo_user,
						'linea'=>$linea,
						'estado'=>$estado
					),
					$documento
				);

				//actualizo datos en la tabla usuarios y obtengo las filas afectadas
				if ($tipo_user=='1' && $estado=='a' || $tipo_user=='2'){
					$afectadas[]=$this->Usuarios->editar_usuario($datos);
				}

				//consulto si hubo errores en la transaccion
				$men[]='Error al actulizar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
				$men[]='El usuario se ha actulizado correctamente.';
				$resultado=$this->transStatus($men, $afectadas);

				if ($resultado[0]['res'] && $tipo_user=='1') {
	                $datos = array(
	                	'modificar' => array(
							'tipo_usuario' => '2',
						),
						$this->session->documento
					);
					$this->Usuarios->editar_usuario($datos);

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

	public function tranferirProductos(){
		$transde=$this->input->post('transDe');
		$transa=$this->input->post('transA');

		//confirmo que el usuario que tiene el inventario este inactivo y que 
		//el usuario que lo recibe este activo y sin inventario
		if ($this->Usuarios->usuariosInt_coninv($transde) && 
			$this->Usuarios->usuariosAct_sininv($transa)) {
			//cargo el modelo
			$this->load->model('Productos');

			$recuperar=errorException_handlers('eliminar');
			try {
				$users=$this->Usuarios->consultar_idUsuario([$transde, $transa]);
				$datos=array(
					'modificar'=> array(
						'usuario_producto'=>$users[1]
					),
					$users[0]
				);

				$this->Productos->transferir_inventario($datos);
				$resultado[]=array(
					'res'=> true,
					'mensaje' =>'Transferencia completa.'
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

		}else{
			$resultado[]=array(
				'res'=> false,
				'mensaje' =>'Ha ocurrido un error. 
					Vuelve a intentarlo mas tarde.'
			);
		}

		echo json_encode($resultado);
	}

	public function preparar_select(){
		$resultado[]=$this->Usuarios->usuariosInt_coninv();
		$resultado[]=$this->Usuarios->usuariosAct_sininv();

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

	public function unicos($reglas, $campo=['id_usuario', 'usuario']){
		$error=false;
		$c=0;
		for ($i=0; $i<4 ; $i+=2) {
			$aux=$c==0?'documento':$campo[$c];
			$errores[$aux]='';

			if (strcasecmp($reglas[$i], $reglas[$i+1])!=0 && $reglas[$i+1]!='') {
				$sentencia=$this->Usuarios->validar_unico($campo[$c], $reglas[$i+1]);

				if ($sentencia) {
					$errores[$aux]='El '.$aux.' ya se encuentra registrado';
					$error=true;
				}
			}else if ($reglas[$i+1]=='') {
				$errores[$aux]='El '.$aux.' no puede ser vacio';
				$error=true;
			}

			$c++;
		}

		return array($error, $errores);
	}
	

}