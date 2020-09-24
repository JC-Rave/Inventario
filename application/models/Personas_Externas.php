<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Personas_Externas extends CI_Model {

	public function consultarPersonaExterna($documento){
		try {			
			$this->db->select("*");
			$this->db->from("personas_externas");
			$this->db->where("documento_exterior",$this->db->escape_str($documento));
			$resultado=$this->db->get();
			if ($resultado->num_rows()!=0) {
				$array=['aviso' => true,
						'texto' => 'Consulta hecha con exito',
						'persona'=>$resultado->result_array()[0],
						'externa'=>true
					];
				return json_encode($array);
			}else{	
				$this->db->select("*");
				$this->db->join('usuarios', 'usuarios.id_usuario=personas.documento_persona');
				$this->db->join('tipo_usuarios', 'tipo_usuarios.id_tipo=usuarios.tipo_usuario');
				$this->db->from("personas");
				$this->db->where("documento_persona",$this->db->escape_str($documento));
				$resultado=$this->db->get();
				if ($resultado->num_rows()!=0) {
					$persona=$resultado->result_array();
					if ($persona[0]["estado"]==="a") {
						$array=[
							'aviso' => true,
							'texto' => 'Consulta hecha con exito',
							'persona'=>[
								"documento_exterior"=>$persona[0]["documento_persona"],
								"nombre_exterior"=>$persona[0]["nombre_persona"],
								"empresa_exterior"=>"TecnoAcademia",
								"cargo_exterior"=>$persona[0]["nombre_tipo"],
								"telefono_exterior"=>$persona[0]["telefono_persona"]
							],
							'externa'=>false
						];
						return json_encode($array);
					}else{
						return json_encode(array('aviso' => false, 'texto' => 'No se logro consultar  la persona 1'));	
					}
				}else{
					return json_encode(array('aviso' => false, 'texto' => 'No se logro consultar  la persona 2'));	
				}
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al obtener la persona: Error -> '.$e->getMesage()));
		}
	}	
	public function agregarPersonaExterna($documento,$nombre,$empresa,$cargo,$telefono){
		try {
			$data = array( 						   
			    'documento_exterior'=> $this->db->escape_str($documento),
			    'nombre_exterior'=> $this->db->escape_str($nombre),
			    'empresa_exterior'=> $this->db->escape_str($empresa),
			    'cargo_exterior'=> $this->db->escape_str($cargo),
			    'telefono_exterior'=> $this->db->escape_str($telefono)
			);	
			$this->db->insert('personas_externas', $data);
			$resultado= $this->db->affected_rows();			
			if ($resultado) {				
				$array=['aviso' => true,
						'texto' => 'Se agregó la persona correctamente',
						'documento_exterior'=> $documento,
						'externa'=>true
					];
				return json_encode($array);  
			}else{			
				return json_encode(array('aviso' => false,'error'=>true,'texto' => 'No se agregó  el producto :'.$resultado));
			}
		} catch (Exception $e) {
			json_encode(array('aviso' => false,'error'=>false,'texto' => 'Problemas al agregar la persona :'.$e->getMessage()));
		}
		
	}
}
/* End of file Personas_Externas.php */
/* Location: ./application/models/Personas_Externas.php */