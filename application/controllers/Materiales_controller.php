<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materiales_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necsarios
		$this->load->model(array('Productos', 'Consumibles', 'Galeria_productos'));	
	}

	public function index(){
		//cargo los modelos
		$this->load->model(array('Categorias_model', 'Unidadmedida', 
			'Lineas_model', 'Usuarios'));

		$resultado['usuarios']=$this->Usuarios->consultar_usuarios(true, true);
		$resultado['unidades']=$this->Unidadmedida->consultar_nombreUnidad();
		$resultado['lineas']=$this->Lineas_model->consultar_nombreLinea();
		$resultado['categorias']=$this->Categorias_model->consultar_nombreCategorias();

		echo json_encode($resultado);
	}

	public function reg_material(){
		if ($this->session->tipo_usuario=='ADMINISTRADOR') {
			$this->load->model('Personas');	

			$encargar=$this->input->post('encargar')=='Seleccionar instructor'?$this->session->documento:$this->input->post('encargar');
			$persona=$this->Personas->get_nombreApellido($encargar);
		}else{
			$encargar=$this->session->documento;
			$persona=$this->session->nombre.' '.$this->session->apellido;
		}

		$nombre=$this->input->post('nombre');
		$reglas=[
			null,
			$nombre,
			$encargar,
			null
		];
		$error_nombre=$this->unicos($reglas);

		if (rules_regMaterial() || $error_nombre[0]){
			$error_nombre=$error_nombre[1]==''?form_error('nombre'):$error_nombre[1];
			$this->form_validation->set_error_delimiters('', '');
			$resultado[]=array(
				'res' => 'invalid',
				'nombre' => $error_nombre,
				'cantidad' => form_error('cantidad'),
				'precio' => form_error('precio'),
				'categoria' => form_error('categoria'),
				'unidad' => form_error('unidad'),
				'ubicacion' => form_error('ubicacion'),
				'descripcion' => form_error('descripcion')
			);

		}else{
			//cargo el modelo
			$this->load->model('Urls_productos');

			$cantidad=$this->input->post('cantidad');
			$precio=$this->input->post('precio');
			$categoria=$this->input->post('categoria');
			$unidad=$this->input->post('unidad');
			$ubicacion=$this->input->post('ubicacion');
			$descripcion=$this->input->post('descripcion');
			$imagen=$this->input->post('imagen');

			$recuperar=errorException_handlers('eliminar');
			try {
				$this->db->trans_begin();

				$datos=array(
					'categoria_producto' => $categoria,
					'unidad_medida' => $unidad,
					'linea_producto' => $ubicacion,
					'usuario_producto' => $encargar,
					'nombre_producto' => $nombre,
					'descripcion_producto' => $descripcion,
					'precio_producto' => $precio,
					'tipo_producto' => 'Consumible'
				);

				$id=$this->Productos->reg_material($datos);
				$datos=array(
					'id_consumible' => $id,
					'cantidad_consumible' => $cantidad
				);
				$this->Consumibles->reg_consumible($datos);

				if($imagen!='Seleccionar Imagen'){
					$respuesta=$this->Galeria_productos->consultarImagen($imagen, true);
					$imagen=$respuesta->imagen;

					$datos = array(
		            	'modificar' => array(
							'imagenp' => $respuesta->id_galeria
						),
						$id
					);
					$this->Productos->editar_material($datos);
				}

				$proveedores=$this->Urls_productos->consultar_idProveedor($nombre);
				if (!empty($proveedores)) {
					$datos=[];
					foreach ($proveedores as $proveedor) {
						$fila=array(
							'id_proveedor'=>$proveedor->id_proveedor, 
							'id_producto'=>$id
						);

						array_push($datos, $fila);
					}
					
					$this->Urls_productos->insert($datos);
				}
				
				//consulto si hubo errores en la transaccion
				$men[]='Error al registrar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
				$men[]='Material registrado con exito.';

				$datos=array(
					'documento' => $encargar,
					'persona' => $persona,
					'foto' => $imagen!='Seleccionar Imagen'?$imagen:'',
					'imgNombre' => $imagen!='Seleccionar Imagen'?$respuesta->nombre:''
				);
				$resultado=$this->transStatus($men, $datos);

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

	public function editar_material(){
		$users=$this->input->post('edit_encargar');
		if ($this->session->tipo_usuario=='ADMINISTRADOR') {
			$this->load->model('Personas');	

			$encargar=$users[1]=='Seleccionar instructor'?$this->session->documento:$users[1];
			$persona=$this->Personas->get_nombreApellido($encargar);
		}else{
			$encargar=$this->session->documento;
			$persona=$this->session->nombre.' '.$this->session->apellido;
		}

		$nombre=$this->input->post('edit_nombre');
		$nom_actual=$this->input->post('nombre_actual');
		$reglas=[
			$nom_actual,
			$nombre,
			$encargar,
			$users[0]
		];
		$error_nombre=$this->unicos($reglas);

		if (rules_editMaterial() || $error_nombre[0]){
			$error_nombre=$error_nombre[1]==''?form_error('edit_nombre'):$error_nombre[1];
			$this->form_validation->set_error_delimiters('', '');
			$resultado[]=array(
				'res' => 'invalid',
				'nombre' => $error_nombre,
				'cantidad' => form_error('edit_cantidad'),
				'precio' => form_error('edit_precio'),
				'categoria' => form_error('edit_categoria'),
				'unidad' => form_error('edit_unidad'),
				'ubicacion' => form_error('edit_ubicacion'),
				'descripcion' => form_error('edit_descripcion')
			);

		}else{
			$cantidad=$this->input->post('edit_cantidad');
			$precio=$this->input->post('edit_precio');
			$categoria=$this->input->post('edit_categoria');
			$unidad=$this->input->post('edit_unidad');
			$ubicacion=$this->input->post('edit_ubicacion');
			$descripcion=$this->input->post('edit_descripcion');
			$imagen=$this->input->post('edit_imagen');

			$recuperar=errorException_handlers('eliminar');
			try {
				$this->db->trans_begin();
				$id=$this->Productos->consultar_idProducto($nom_actual, $users[0]);

				$datos = array(
	            	'modificar' => array(
						'categoria_producto' => $categoria,
						'unidad_medida' => $unidad,
						'linea_producto' => $ubicacion,
						'usuario_producto' => $encargar,
						'nombre_producto' => $nombre,
						'descripcion_producto' => $descripcion,
						'precio_producto' => $precio,
					),
					$id->id_producto
				);
				$afectadas[]=$this->Productos->editar_material($datos);

				if($imagen!='Seleccionar Imagen'){
					$respuesta=$this->Galeria_productos->consultarImagen($imagen, true);
					$imagen=$respuesta->imagen;

					$datos = array(
		            	'modificar' => array(
							'imagenp' => $respuesta->id_galeria
						),
						$id->id_producto
					);
				}else{
					$datos = array(
		            	'modificar' => array(
							'imagenp' => null
						),
						$id->id_producto
					);
				}
				$afectadas[]=$this->Productos->editar_material($datos);

				$datos=array(
					'modificar' => array(
						'cantidad_consumible' => $cantidad,
					),
					$id->id_producto
				);
				$afectadas[]=$this->Consumibles->editar_consumible($datos);

				//consulto si hubo errores en la transaccion
				$men[]='Error al Actulizara. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
				$men[]='Material Actulizado con exito.';

				$datos=array(
					'documento' => $encargar,
					'persona' => $persona,
					'foto' => $imagen!='Seleccionar Imagen'?$imagen:'',
					'imgNombre' => $imagen!='Seleccionar Imagen'?$respuesta->nombre:''
				);
				rsort($afectadas);
				$resultado=$this->transStatus($men, $datos, $afectadas[0]);

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

	public function ver_provMaterial(){
		$this->load->model('Proveedores');
		$nombre=$this->input->post('nombre');
		$usuario=$this->input->post('usuario');
		echo json_encode($this->Proveedores->consultProveedores($nombre, $usuario));
	}

	public function generar_selects(){
		//cargo los modelos
		$this->load->model(array('Categorias_model', 'Unidadmedida', 'Personas'));

		$resultado['categorias']=$this->Categorias_model->preSelect();
		$resultado['medidas']=$this->Unidadmedida->preSelect();
		$resultado['personas']=$this->Personas->preSelect();
		
		echo json_encode($resultado);
	}

	public function transStatus($mensaje, $datos, $afectadas=''){
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
	        	'datos' => $datos,
	        	'mensaje' => $mensaje[1],
	        	'afectadas' => $afectadas
	        );    
	    }

	    return $resultado;
	}

	public function unicos($reglas){
		$error=false;
		$mensaje='';

		if (strcasecmp($reglas[0], $reglas[1])!=0 && $reglas[1]!='' || $reglas[2]!=$reglas[3]) {
			if ($this->Productos->validar_unico('nombre_producto', $reglas[1], $reglas[2])) {
				$mensaje='El usuario ya posee un material con ese nombre y/o se encuentra en un pedido.';
				$error=true;
			}

		}else if ($reglas[1]=='') {
			$mensaje='El nombre del material no puede ser vacio.';
			$error=true;
		}

		return array($error, $mensaje);
	}

	public function consultarMateriales(){
		$array=$this->Productos->consultar_materiales();
		$array+=['aviso'=>true,'texto'=>'consulta hecha con exito'];
		echo json_encode($array);
	}
}

/* End of file Materiales_controller.php */
/* Location: ./application/controllers/Materiales_controller.php */
