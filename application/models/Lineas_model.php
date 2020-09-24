<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lineas_model extends CI_Model {	
	public function getLineas(){
		try {			 
			$resultado = $this->db->get('lineas');

			if ($resultado) {
				$array=$resultado->result_array();
				$array+=['aviso' => true,'texto' => 'Consulta hecha con exito'];
				return json_encode($array);
			}else{			
				return json_encode(array('aviso' => false, 'texto' => 'No se logro consultar  las lineas'));
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al obtener todas las lineas: Error -> '.$e->getMesage()));
		}
	} 
	public function consultar_nombreLinea(){
		$this->db->distinct();
		$this->db->select('nombre_linea');
		$this->db->from('lineas');
		$this->db->join('productos', 'productos.linea_producto = lineas.id_linea');
		$this->db->join('consumibles','consumibles.id_consumible=productos.id_producto');
		$datos=$this->db->get();

		return $datos->result();	
	}
}
/* End of file lineas_model.php */
/* Location: ./application/models/Lineas_model.php */