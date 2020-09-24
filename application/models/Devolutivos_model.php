<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Devolutivos_model extends CI_Model {	

	public function addDevolutivo($id_producto,$placa,$serial,$csena){  
		try {			
			$data = array( 		
				'id_devolutivo'=>$this->db->escape_str($id_producto), 			   
			    'placa'=> $this->db->escape_str($placa),  
			    'codigo_sena'	=> $this->db->escape_str($csena),
			    'serial'	=>  $this->db->escape_str($serial)
			);
			$this->db->insert('devolutivo', $data);			
			$resultado= $this->db->affected_rows(); 
			if ($resultado) {				
				$array=['aviso' => true,
						'texto' => 'Se agregó el devolutivo con éxito',
						'id_devolutivo'=> $this->db->insert_id()						
					];
				return json_encode($array);  
			}else{			
				return json_encode(array('aviso' => false, 'texto' => 'No se agregó  el devolutivo :'.$resultado));
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al agregar la categoría: Error -> '.$e->getMesage()));
		}
	}

	public function getDevolutivos(){
		try {			
			$this->db->select('*');
			$this->db->from('productos');
			$this->db->join('devolutivo','devolutivo.id_devolutivo = productos.id_producto');
			$this->db->join('categorias','categorias.id_categoria = productos.categoria_producto','left');
			$this->db->join('unidad_medida','unidad_medida.id_unidad = productos.unidad_medida','left');
			$this->db->join('estados_productos','estados_productos.id_estado = productos.estado_producto','left');
			$this->db->join('lineas','lineas.id_linea = productos.linea_producto','left');
			$this->db->join('usuarios','usuarios.id_usuario = productos.usuario_producto'); 
			$this->db->join('personas','personas.documento_persona = usuarios.id_usuario');
			$this->db->join('galeria_productos','galeria_productos.id_galeria = productos.imagenp','left');
			$this->db->where("(productos.id_producto NOT IN (SELECT producto_salida FROM salidas WHERE salidas.estado_salida='En prestamo' OR salidas.estado_salida='No retorna'))");
			$resultado = $this->db->get();
			if ($resultado->num_rows()>0) {
				$array=$resultado->result_array();
				$array+=['aviso' => true,'texto' => 'Consulta hecha con exito'];
				return json_encode($array);
			}else{			
				return json_encode(array('aviso' => false, 'texto' => 'No se logro consultar  los devolutivos'));
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al obtener todos los devolutivos: Error -> '.$e->getMesage()));
		}
	}

	public function setDevolutivo($id_producto,$placa,$serial,$csena){
		try {			
			$data = array( 		    
			    'id_devolutivo'=> $this->db->escape_str($id_producto), 
			    'placa'	=> $this->db->escape_str($placa),
			    'codigo_sena'=> $this->db->escape_str($csena),
			    'serial'=>  $this->db->escape_str($serial)
			);
			$this->db->where('id_devolutivo', $id_producto);
			$this->db->update('devolutivo', $data);			
			$resultado = $this->db->affected_rows();
			if ($resultado) {				
				$array=['aviso' => true,
						'error'=>true,
						'texto' => 'Se modificó el devolutivo con éxito',
						'id_devolutivo'=> $id_producto
						];
				return json_encode($array);
			}else{		
				$array=['aviso' => false,
						'error'=>true,
						'texto' => 'El devolutivo permanece igual'						
						];	
				return json_encode($array);
			}
		} catch (Exception $e) {
			$array=['aviso' => false,
					'error'=>false,
					'texto' => 'Problemas al modificar la categoría'						
					];	
			return json_encode($array);
		}
	}
	
	public function getMantenimientosDevolutivo($id){
		try {			
			$this->db->select('*');
			$this->db->from('mantenimiento_equipos');
			$this->db->where('devolutivo',$this->db->escape_str($id));
			$resultado = $this->db->get();
			if ($resultado->num_rows()>0) {				
				$array=$resultado->result_array();
				$array+=['aviso' => true,'texto' => 'Consulta hecha con exito'];
				return json_encode($array);
			}else{		
				return json_encode(array('aviso' => false, 'texto' => 'No se logro consultar  los mantenimientos'));
				return json_encode($array);
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al obtener todos los mantenimientos: Error -> '.$e->getMesage()));
		}
	}

	public function getUltimoMantenimiento($bool=false){	
		if ($bool) {
			$consulta="SELECT * FROM mantenimiento_equipos ORDER BY registrado DESC LIMIT 1";
			$datos=$this->db->query($consulta);
			if ($datos->num_rows()!=0) {
					$result=$datos->row_array();
					$array=array(
								"aviso"=>true,
								"texto"=>"Se agregó el mantenimiento",
								"devolutivo"=>$result['devolutivo'],
								"registrado"=>$result['registrado'],
								"fecha_inicio"=>$result['fecha_inicio'],
								"fecha_fin"=>$result['fecha_fin'],
								"tipo_matenimiento"=>$result['tipo_matenimiento'],
								"estado_matenimiento"=>$result['estado_matenimiento']
					);
					return $array;
			}else{
				return json_encode(array('aviso' => false, 'texto' => 'No se consultó  el mantenimiento :'.$resultado));		
			}

		}
	}

	public function agregarMantenimientoDevolutivo($devolutivo,$inicio,$fecha_fin,$tipo_matenimiento,$estado_matenimiento){
		$sql="INSERT INTO mantenimiento_equipos (devolutivo,fecha_inicio,fecha_fin,tipo_matenimiento,estado_matenimiento) VALUES (".$this->db->escape_str($devolutivo).",".$this->db->escape($inicio).",".$this->db->escape($fecha_fin).",".$this->db->escape($tipo_matenimiento).",".$this->db->escape($estado_matenimiento).")";
		$this->db->query($sql);
		$resultado= $this->db->affected_rows();
		if ($resultado) {		
			return json_encode($this->getUltimoMantenimiento(true));  
		}else{			
			return json_encode(array('aviso' => false, 'texto' => 'No se agregó  el mantenimiento :'.$resultado));
		}
	}

	public function editarMantenimientoDevolutivo($registrado,$devolutivo,$inicio,$fecha_fin,$tipo_matenimiento,$estado_matenimiento){
		$data = array( 	
			    'fecha_inicio'=>$this->db->escape_str($inicio), 
			    'fecha_fin'=>$this->db->escape_str($fecha_fin),
			    'tipo_matenimiento'=>$this->db->escape_str($tipo_matenimiento),
			    'estado_matenimiento'=>$this->db->escape_str($estado_matenimiento)
			);
		$this->db->where('devolutivo',$devolutivo,);
		$this->db->where('registrado',$registrado);
		$this->db->update('mantenimiento_equipos',$data);
		$resultado = $this->db->affected_rows();
		if ($resultado) {				
			$array=['aviso' => true,
					'error'=>true,
					'texto' => 'Se modificó el mantenimiento con éxito',
					'devolutivo'=> $devolutivo,
					'registrado'=> $registrado,
					'fecha_inicio'=> $inicio,
					'fecha_fin'=> $fecha_fin,
					'tipo_matenimiento'=> $tipo_matenimiento,
					'estado_matenimiento'=> $estado_matenimiento
					];
			return json_encode($array);
		}else{		
			$array=['aviso' => false,
					'error'=>true,
					'texto' => 'El mantenimiento permanece igual'.$resultado
					];	
			return json_encode($array);
		}
	}

	public function anularMantenimientoDevolutivo($registrado,$devolutivo){
		$data = array( 	
			    'estado_matenimiento'=>'Anulado'
			);
		$this->db->where('devolutivo',$devolutivo);
		$this->db->where('registrado',$registrado);
		$this->db->update('mantenimiento_equipos',$data);
		$resultado = $this->db->affected_rows();
		if ($resultado) {				
			$array=['aviso' => true,
					'error'=>true,
					'texto' => 'Se eliminó el mantenimiento con éxito',
					'devolutivo'=> $devolutivo,
					'registrado'=> $registrado
					];
			return json_encode($array);
		}else{		
			$array=['aviso' => false,
					'error'=>true,
					'texto' => 'El mantenimiento no se encuentra ->'.$registrado.$devolutivo
					];	
			return json_encode($array);
		}	
	}
	
	public function getUrlsProveedores($id_devolutivo){
		$this->db->select('*');
		$this->db->from('proveedores');
		$this->db->join('urls_productos','urls_productos.id_proveedor = proveedores.id_proveedor');
		$this->db->where('urls_productos.id_producto',$id_devolutivo);
		$resultado = $this->db->get();
		if ($resultado->num_rows()>0) {
			$array=$resultado->result_array();
			$array+=['aviso' => true,'texto' => 'Consulta hecha con exito'];
			return json_encode($array);
		}else{			
			return json_encode(array('aviso' => false, 'texto' => 'No se logro consultar  los proveedores'));
		}	
	}

	public function deleteDetalle($datos){
		$this->db->where_in('id_devolutivo', $datos);
		$this->db->delete('devolutivo');
	}
	public function cosultar_idDevolutivo($pedido){
		$this->db->select('id_devolutivo');
		$this->db->from('devolutivo');
		$this->db->join('detalle_pedido', 'detalle_pedido.producto = devolutivo.id_devolutivo');
		$this->db->where('pedido', $pedido);
		$codigos=$this->db->get();

		$datos=[];
		foreach ($codigos->result() as $codigo) {
        	array_push($datos, $codigo->id_devolutivo);
		}

		return $datos;
	}
}
/* End of file categorias_model.php */
/* Location: ./application/models/categorias_model.php */
