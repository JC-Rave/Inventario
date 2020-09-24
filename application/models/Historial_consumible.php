<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial_consumible extends CI_Model {

	public function consultar_historial(){
		$this->db->select('DATE_FORMAT(fecha, "%d/%m/%Y") fecha, cantidad, nombre_producto, usuario_producto, nombre_persona, apellido_persona, galeria_productos.imagen, nombre_unidad');	
		$this->db->from('historial_consumible');
		$this->db->join('productos', 'productos.id_producto = historial_consumible.consumible');
		$this->db->join('personas', 'personas.documento_persona = productos.usuario_producto');
		$this->db->join('unidad_medida', 'unidad_medida.id_unidad = productos.unidad_medida');
		$this->db->join('galeria_productos', 'galeria_productos.id_galeria = productos.imagenp', 'left');
		$datos=$this->db->get();

		return $datos->result();
	}	

}

/* End of file Historial_consumible.php */
/* Location: ./application/models/Historial_consumible.php */