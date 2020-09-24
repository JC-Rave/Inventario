<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model(array('Proveedores','Productos','Urls_productos'));	
	}

	public function regProveedor(){
		//funcion del helper validaciones que cargo automaticamente con autoload.php
		//y sirve para confirmar que los campos ingresados sean correctos
		if (rules_regProveedores()) {
			//si falla guardo los errores individualmente con form_error('nombre del input') en un arreglo
			$resultado[]=array(
				'res' => 'invalid',
				'nit' => form_error('nit'),
				'proveedor' => form_error('proveedor'),
				'telefono' => form_error('telefono'),
				'correo' => form_error('correo'),
				'url' => form_error('url')
			);

		}else{
			//obtengo los valores de los inputs llegados por post desde el archivo proveedores.js
			$nit=$this->input->post('nit');
			$proveedor=$this->input->post('proveedor');
			$correo=$this->input->post('correo');
			$telefono=$this->input->post('telefono');
			$url=$this->input->post('url');
			$productos=$this->input->post('productos');

			$recuperar=errorException_handlers('eliminar');
			try {
				//creo un transaccion para evitar que se guarden los cambios en caso
				//de que algo falle
				$this->db->trans_begin(); 

				//preparos los datos para hacer una inserccion a la tabla proveedores
                $datos = array(
					'nit' => $nit,
					'nombre_proveedor' => $proveedor,
					'telefono_proveedor' => $telefono,
					'correo_proveedor' => $correo,
					'url' => $url
				);

				//inserto datos en la tabla proveedores
				$id=$this->Proveedores->reg_proveedor($datos);

				//asigno los productos que suministra el proveedor
				$this->asignacion($productos, $id);

				//consulto si hubo errores en la transaccion
				$men[]='Error al registrar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
				$men[]='El proveedor ha sido registrado correctamente.';
				$resultado=$this->transStatus($men);

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

	public function verProveedor(){
		//obtengo post llegado por proveedores.js
		$nit=$this->input->post('nit');

		//consulto los productos que suministra
		echo json_encode($this->Productos->suministra_proveedor($nit));
	}

	public function editarProveedor(){
		$reglas=[
			$this->input->post('nit'),
			$this->input->post('nuevo_nit'),
			$this->input->post('correo'),
			$this->input->post('nuevo_correo'),
			$this->input->post('url'),
			$this->input->post('nuevo_url')
		];
		$errores=$this->unicos($reglas);

		//funcion del helper validaciones que cargo automaticamente con autoload.php
		//y sirve para confirmar que los campos ingresados sean correctos
		if (rules_editProveedores() || $errores[0]) {
			//si falla guardo los errores individualmente con form_error('nombre del input') en un arreglo
			$resultado[]=array(
				'res' => 'invalid',
				'nit' => $errores[1]['nit'],
				'proveedor' => form_error('proveedor'),
				'telefono' => form_error('telefono'),
				'correo' => $errores[1]['correo'],
				'url' => $errores[1]['url']
			);

		}else{
			//obtengo los valores de los inputs llegados por post desde el archivo proveedores.js
			$nit=$reglas[1];
			$proveedor=$this->input->post('proveedor');
			$telefono=$this->input->post('telefono');
			$correo=$reglas[3];
			$url=$reglas[5];
			$estado=$this->input->post('estado');
			$productos=$this->input->post('productos');

			$recuperar=errorException_handlers('eliminar');
			try {
				//creo un transaccion para evitar que se guarden los cambios en caso
				//de que algo falle
				$this->db->trans_begin();

				//preparos los datos para hacer una actulizacion a la tabla proveedores
                $datos = array(
                	'modificar' => array(
						'nit' => $nit,
						'nombre_proveedor' => $proveedor,
						'telefono_proveedor' => $telefono,
						'correo_proveedor' => $correo,
						'url' => $url,
						'estado'=>$estado
					),
					$reglas[0],
					$nit
				);

				//actualizo datos en la tabla proveedores y obtengo su id
				$id=$this->Proveedores->editar_proveedor($datos);
				$afectadas[]=$id[1];
				$afectadas[]=$this->asignacion($productos, $id[0], true);

				//consulto si hubo errores en la transaccion
				$men[]='Error al actulizar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
				$men[]='El proveedor ha sido actulizado correctamente.';
				$resultado=$this->transStatus($men, $afectadas);

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

	public function asignacion($productos, $id, $accion=false){
		if (!empty($productos)) {
			for ($i=0; $i<count($productos); $i++) { 
				$nombresPro[]=$productos[$i][0];
			}

			//consulto datos en la tabla productos
			if (!$accion) {
				$cod_productos=$this->Productos->consultProdutos($nombresPro);
			}else{
				$cod_productos=$this->Productos->consultProdutos($nombresPro,$id);
				$afectadas[]=$this->Urls_productos->deleteSobrantes($nombresPro,$id);
			}

			if (!empty($cod_productos)){
				//preparos los datos para hacer una inserccion a la tabla urls_productos
				$datos=[];
				foreach ($cod_productos as $llaves) {
					foreach ($productos as $producto) {
						if ($producto[0]==$llaves->nombre_producto) {
							$fila=array(
								'id_proveedor'=>$id, 
								'id_producto'=>$llaves->id_producto,
								'precio' => $producto[1],
								'descripcion' => $producto[2],
							);

							array_push($datos, $fila);
							break;
						}
					}
				}

				//inserto datos en la tabla urls_productos
				$afectadas[]=$this->Urls_productos->insert($datos);
			}

			if ($accion) {
				$datos=[];
				$nombresPro=$this->Productos->consultProdutos($nombresPro);
				foreach ($nombresPro as $llaves) {
					foreach ($productos as $producto) {
						if ($producto[0]==$llaves->nombre_producto) {
							$fila=array(
								'id_proveedor'=>$id, 
								'id_producto'=>$llaves->id_producto,
								'precio' => $producto[1],
								'descripcion' => $producto[2],
							);
							
							array_push($datos, $fila);
							break;
						}
					}
				}

				$afectadas[]=$this->Urls_productos->update($datos);
		        rsort($afectadas);
			}
	        
	        $afectadas=$afectadas[0];
		}else{
			$afectadas=$this->Urls_productos->delete($id[0]);
		}

		return $afectadas;
	}

	public function transStatus($mensaje, $afectadas=[0]){
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

	public function unicos($reglas, $campo=['nit', 'correo_proveedor', 'url']){
		$error=false;
		$c=0;
		for ($i=0; $i<6 ; $i+=2) {
			$aux=$c==1?'correo':$campo[$c];
			$errores[$aux]='';

			if (strcasecmp($reglas[$i], $reglas[$i+1])!=0 && $reglas[$i+1]!='') {
				$sentencia=$this->Proveedores->validar_unico($campo[$c], $reglas[$i+1]);

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
