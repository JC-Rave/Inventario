<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consumibles extends CI_Model {
 
	public function reg_consumible($datos){
		$this->db->insert('consumibles', $datos);	
	}

	public function editar_consumible($datos){
		$this->db->update('consumibles', $datos['modificar'], array('id_consumible'=>$datos[0]));

		return $this->db->affected_rows();
	}
	
	public function editarCantidadConsumible($id_consumible,$cantidad){
		$datos=array(
			"cantidad_consumible"=>$cantidad
		);
		$this->db->where('id_consumible',$id_consumible);
		$this->db->update('consumibles',$datos);
		return $this->db->affected_rows();
	}

	public function deleteDetalle($datos){
		$this->db->where_in('id_consumible', $datos);
		$this->db->delete('consumibles');
	}
	public function edit_cantConsumibles($datos){
		$this->db->update_batch('consumibles', $datos, 'id_consumible');
	}


	public function cosultar_idConsumible($codes, $proceso=false){
		$select=$proceso?'id_consumible, cantidad_consumible, cantidad':'id_consumible';

		$this->db->select($select);
		$this->db->from('consumibles');
		$this->db->join('detalle_pedido', 'detalle_pedido.producto = consumibles.id_consumible');
		$proceso?$this->db->where_in('id_consumible', $codes):$this->db->where('pedido', $codes);
		$codigos=$this->db->get();

		$datos=[];
		if ($proceso) {
			foreach ($codigos->result() as $codigo) {
				$fila=array(
					'id_consumible' => $codigo->id_consumible,
					'cantidad_consumible' => (int)$codigo->cantidad_consumible+(int)$codigo->cantidad
				);

				array_push($datos, $fila);
			}
		}else{
			foreach ($codigos->result() as $codigo) {
	        	array_push($datos, $codigo->id_consumible);
			}
		}

		return $datos;
	}
}

/* End of file Consumibles.php */
/* Location: ./application/models/Consumibles.php */