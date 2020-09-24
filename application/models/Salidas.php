<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salidas extends CI_Model {

	public function consultarSalidas($id_solicitud){
		$this->db->select("salidas.*,productos.*,devolutivo.*,consumibles.*,categorias.*,unidad_medida.*,lineas.*,personas.*,estados_productos.*");
		$this->db->from("salidas");
		$this->db->join('productos', 'productos.id_producto = salidas.producto_salida');
		$this->db->join('devolutivo', 'devolutivo.id_devolutivo = productos.id_producto','left');
		$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto','left');
		$this->db->join('galeria_productos','galeria_productos.id_galeria = productos.imagenp','left');
		$this->db->join('categorias', 'categorias.id_categoria = productos.categoria_producto');
		$this->db->join('unidad_medida', 'unidad_medida.id_unidad = productos.unidad_medida');
		$this->db->join('lineas', 'lineas.id_linea = productos.linea_producto');
		$this->db->join('estados_productos', 'estados_productos.id_estado = productos.estado_producto','left');
		$this->db->join('usuarios','usuarios.id_usuario=productos.usuario_producto');
		$this->db->join('personas', 'personas.documento_persona = productos.usuario_producto');
		$this->db->where("salidas.solicitud_salida",$this->db->escape_str($id_solicitud));
		$resultado=$this->db->get();
		$this->db->select("salidas.*,personas_externas.*,tipo_usuarios.*,personas.*");
		$this->db->from("salidas");
		$this->db->join('personas_externas','personas_externas.documento_exterior=salidas.persona_exterior','left');
		$this->db->join('usuarios','usuarios.id_usuario=salidas.persona_usuario','left');
		$this->db->join('tipo_usuarios','tipo_usuarios.id_tipo=usuarios.tipo_usuario','left');
		$this->db->join('personas', 'personas.documento_persona = usuarios.id_usuario','left');
		$this->db->where("salidas.solicitud_salida",$this->db->escape_str($id_solicitud));
		$resultado2=$this->db->get();
		$this->db->select("solicitudes.*,personas.*,tipo_usuarios.*");
		$this->db->from("solicitudes");
		$this->db->join('usuarios', 'usuarios.id_usuario = solicitudes.usuario_solicitud');
		$this->db->join('tipo_usuarios','tipo_usuarios.id_tipo=usuarios.tipo_usuario');
		$this->db->join('personas', 'personas.documento_persona = usuarios.id_usuario');
		$this->db->where("solicitudes.id_solicitud",$this->db->escape_str($id_solicitud));
		$resultado3=$this->db->get();
		if ($resultado->num_rows()!=0 && $resultado2->num_rows()!=0 && $resultado3->num_rows()!=0) {
			$array['aviso']=true;
			$array['texto']='Consulta hecha con exito';
			$array['salidasProd']=$resultado->result();
			$array['salidasPers']=$resultado2->result();
		}else{
			$array['aviso']=false;
			$array['texto']='No se logro consultar  las salidas rows r1->'.$resultado->num_rows()." r2->".$resultado2->num_rows()." id_solicitud->".$id_solicitud;
		}
		$solicitud=$resultado3->row_array();
		$array['solicitud']=array(
				"id_solicitud"=>$solicitud['id_solicitud'],
				"usuario_solicitud"=>$solicitud['usuario_solicitud'],
				"fecha_solicitud"=>$solicitud['fecha_solicitud'],
				"nombre_persona"=>$solicitud['nombre_persona'],
				"apellido_persona"=>$solicitud['apellido_persona'],
				"estado_solicitud"=>$solicitud['estado_solicitud'],
				"total_solicitud"=>$solicitud['total_solicitud']
			);
		$consulta="SELECT id_solicitud FROM solicitudes WHERE solicitudes.usuario_solicitud=".$solicitud['usuario_solicitud']." AND estado_solicitud='En proceso' AND id_solicitud BETWEEN 0 AND ".$id_solicitud." OR solicitudes.usuario_solicitud=".$solicitud['usuario_solicitud']." AND estado_solicitud='Terminado' AND id_solicitud BETWEEN 0 AND ".$id_solicitud;
		$resultado4=$this->db->query($consulta);
		if ($resultado4->num_rows()!=0) {
			$array['n_solicitud']=$resultado4->num_rows();
		}else{
			$array['n_solicitud']=1;
		}
		return json_encode($array);	
	}	
	public function agregarSalida($solicitud,$producto_salida,$cantidad_salida,$estado_salida,$tipo_salida,$persona,$exterior){
		try {
			if ($exterior==="true") {
				$data = array( 						   
				    'solicitud_salida'=> $this->db->escape_str($solicitud),
				    'producto_salida'=> $this->db->escape_str($producto_salida),
				    'cantidad_salida'=> $this->db->escape_str($cantidad_salida),
				    'estado_salida'=> $this->db->escape_str($estado_salida),
				    'tipo_salida'=> $this->db->escape_str($tipo_salida),
				    'persona_exterior'=> $this->db->escape_str($persona)
				);		
			}else if ($exterior==="false"){
				$data = array( 						   
				    'solicitud_salida'=> $this->db->escape_str($solicitud),
				    'producto_salida'=> $this->db->escape_str($producto_salida),
				    'cantidad_salida'=> $this->db->escape_str($cantidad_salida),
				    'estado_salida'=> $this->db->escape_str($estado_salida),
				    'tipo_salida'=> $this->db->escape_str($tipo_salida),
				    'persona_usuario'=> $this->db->escape_str($persona)
				);		
			}
			$this->db->insert('salidas', $data);
			$resultado= $this->db->affected_rows();			
			unset($data);
			if ($resultado) {				
				$array=['aviso' => true,
						'texto' => 'Se agregó la salida correctamente',
						'salida'=> $this->db->insert_id()
					];
				return json_encode($array);  
			}else{			
				return json_encode(array('aviso' => false,'error'=>true,'texto' => 'No se agregó  la salida :'.$resultado));
			}
		} catch (Exception $e) {
			json_encode(array('aviso' => false,'error'=>false,'texto' => 'Problemas al agregar las salidas :'.$e->getMessage()));
		}
	}
	public function eliminarSalidas($id_solicitud){
		$this->db->where('solicitud_salida', $id_solicitud);
		$this->db->delete('salidas');
	}
	public function setEstadoSalida($id_salida,$estado_salida){
		$data = array( 		    
		    'estado_salida'=> $this->db->escape_str($estado_salida)
		);
		$this->db->where('id_salida', $id_salida);
		$this->db->update('salidas', $data);			
		$resultado = $this->db->affected_rows();
		if ($resultado) {				
			$array=['aviso' => true,
					'texto' => 'Se modificó el estado de la salida con éxito',
					'id_salida'=> $id_salida,
					'estado_salida'=> $estado_salida
					];
			return json_encode($array);
		}else{		
			$array=['aviso' => false,
					'texto' => 'No se logró cambiar el estado de la salida'						
					];	
			return json_encode($array);
		}	
	}
	public function setEstadosSalida($id_solicitud,$estado_salida){
		$data = array( 		    
		    'estado_salida'=> $this->db->escape_str($estado_salida)
		);
		$this->db->where('solicitud_salida', $id_solicitud);
		$this->db->update('salidas', $data);			
		$resultado = $this->db->affected_rows();
		if ($resultado) {				
			$array=['aviso' => true,
					'texto' => 'Se modificó el estado de las salidas con éxito',
					'id_solicitud'=> $id_solicitud,
					'estado_salida'=> $estado_salida
					];
			return json_encode($array);
		}else{		
			$array=['aviso' => false,
					'texto' => 'No se logró cambiar el estado de las salidas'						
					];	
			return json_encode($array);
		}	
	}
	public function consultarNSalidasEnPrestamo($id_solicitud){
		$this->db->select("id_salida");
		$this->db->from("salidas");
		$this->db->where("(salidas.estado_salida='En prestamo' AND salidas.solicitud_salida=".$id_solicitud.")");
		$resultado=$this->db->get();
		return $resultado->num_rows();
	}
	public function consultarSalidasCantidad($id_solicitud,$estado_salida=""){

		$this->db->select("salidas.*,productos.*,consumibles.*");
		$this->db->from("salidas");
		$this->db->join('productos', 'productos.id_producto = salidas.producto_salida');
		$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
		if ($estado_salida==="En prestamo") {
			$this->db->where("(salidas.solicitud_salida=".$this->db->escape_str($id_solicitud)." AND salidas.estado_salida='En prestamo' AND salidas.estado_salida!='Retornado')");			
		}else{
			$this->db->where("(salidas.solicitud_salida=".$this->db->escape_str($id_solicitud)." AND salidas.estado_salida!='Retornado')");			
		}
		$resultado=$this->db->get();
		if ($resultado->num_rows()) {
			$array['aviso']=true;
			$array['salidas']=$resultado->result_array();
		}
		else{
			$array['aviso']=false;
		}
		return $array;
	}
}

/* End of file Salidas.php */
/* Location: ./application/models/Salidas.php */