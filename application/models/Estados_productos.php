<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estados_productos extends CI_Model {

	public function getEstados(){
		try {			
			$resultado = $this->db->get('estados_productos');

			if ($resultado) {
				$array=$resultado->result_array();
				$array+=['aviso' => true,'texto' => 'Consulta hecha con éxito'];
				return json_encode($array);
			}else{			
				return json_encode(array('aviso' => false, 'texto' => 'No se logró consultar los Estados de productos'));
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al obtener todos los Estados de productos: Error -> '.$e->getMesage()));
		}
	}

	public function consultar_nombreEstados(){
		$this->db->distinct();
		$this->db->select('descripcion_estado');
		$this->db->from('estados_productos');
		$this->db->join('productos', 'productos.estado_producto = estados_productos.id_estado');
		$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
		$datos=$this->db->get();

		return $datos->result();		
	}	

}

/* End of file Estados_productos.php */
/* Location: ./application/models/Estados_productos.php */