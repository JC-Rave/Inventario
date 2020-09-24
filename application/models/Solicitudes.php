<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitudes extends CI_Model {

	public function consultarSolicitudesUser($usuario_solicitud){
		$this->db->select('solicitudes.*,personas.*');
		$this->db->join('usuarios','usuarios.id_usuario=solicitudes.usuario_solicitud');
		$this->db->join('personas','usuarios.id_usuario=personas.documento_persona');
		$this->db->from('solicitudes');
		$this->db->where('solicitudes.usuario_solicitud',$usuario_solicitud);
		$datos=$this->db->get();
		return $datos->result();
	}
	public function agregarSolicitud($usuario,$estado_solicitud	){
		try {
			$data = array( 						   
			    'usuario_solicitud'=> $this->db->escape_str($usuario),
			    'estado_solicitud'=> $this->db->escape_str($estado_solicitud)
			);	
			$this->db->insert('solicitudes', $data);
			$resultado= $this->db->affected_rows();			
			if ($resultado) {				
				$array=['aviso' => true,
						'texto' => 'Se agregó la solicitud correctamente',
						'solicitud'=> $this->db->insert_id()
					];
				return json_encode($array);  
			}else{			
				return json_encode(array('aviso' => false,'error'=>true,'texto' => 'No se agregó  la solicitud :'.$resultado));
			}
		} catch (Exception $e) {
			json_encode(array('aviso' => false,'error'=>false,'texto' => 'Problemas al agregar la psolicitud :'.$e->getMessage()));
		}
	}
	public function consultarUltimaSolicitudUsuario($usuario_solicitud){	
		$consulta="SELECT * FROM solicitudes WHERE solicitudes.usuario_solicitud=".$usuario_solicitud." ORDER BY id_solicitud DESC LIMIT 1";
		$datos=$this->db->query($consulta);
		$consulta="SELECT id_solicitud FROM solicitudes WHERE solicitudes.usuario_solicitud=".$usuario_solicitud." AND estado_solicitud='En proceso' OR solicitudes.usuario_solicitud=".$usuario_solicitud." AND estado_solicitud='Terminado'" ;
		$datos2=$this->db->query($consulta);
		if ($datos->num_rows()!=0) {
			$result=$datos->row_array();
			$array['aviso']=true;
			$array['texto']="consulta exitosa";
			$array['id_solicitud']=intval($result['id_solicitud']);				
			$array['usuario_solicitud']=$result['usuario_solicitud'];
			$array['fecha_solicitud']=$result['fecha_solicitud'];
			$array['estado_solicitud']=$result['estado_solicitud'];
			if ($datos2->num_rows()!=0) {
				$array['n_solicitudes']=$datos2->num_rows();
			}
		}else{
			$array['aviso']=true;
			$array['texto']="No se logró realizar la consulta";
		}
		return $array;
	}
	public function setTotalSolicitud($id_solicitud,$total_solicitud){
		
		$data = array( 		    
		    'total_solicitud'=> $this->db->escape_str($total_solicitud)
		);
		$this->db->where('id_solicitud', $id_solicitud);
		$this->db->update('solicitudes', $data);			
		$resultado = $this->db->affected_rows();
		if ($resultado) {				
			$array=['aviso' => true,
					'texto' => 'Se modificó el total de solicitud con éxito',
					'id_solicitud'=> $id_solicitud,
					'total_solicitud'=> $total_solicitud
					];
			return json_encode($array);
		}else{		
			$array=['aviso' => false,
					'texto' => 'No se logró cambiar el total'						
					];	
			return json_encode($array);
		}
	}
	public function setEstadoSolicitud($id_solicitud,$estado_solicitud){
		$data = array( 		    
		    'estado_solicitud'=> $this->db->escape_str($estado_solicitud)
		);
		$this->db->where('id_solicitud', $id_solicitud);
		$this->db->update('solicitudes', $data);			
		$resultado = $this->db->affected_rows();
		if ($resultado) {				
			$array=['aviso' => true,
					'texto' => 'Se modificó el estado de solicitud con éxito',
					'id_solicitud'=> $id_solicitud,
					'estado_solicitud'=> $estado_solicitud
					];
			return json_encode($array);
		}else{		
			$array=['aviso' => false,
					'texto' => 'No se logró cambiar el total'						
					];	
			return json_encode($array);
		}	
	}
}

/* End of file Solicitudes.php */
/* Location: ./application/models/Solicitudes.php */