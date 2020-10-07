<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Urls_productos extends CI_Model {
 
	public function insert($datos){
		//si utiliza transaccion antes de llamar la funcion pre-inserta una persona
		//de lo contrario inserta una persona
		$this->db->insert_batch('urls_productos', $datos);

		return $this->db->affected_rows();
	}

	public function update($datos){
		$afectadas=0;
		foreach ($datos as $dato) {
			$this->db->update('urls_productos', $dato, array('id_producto' => $dato['id_producto'], 'id_proveedor' => $dato['id_proveedor']));

			if ($this->db->affected_rows()) {
				$afectadas=$this->db->affected_rows();
			}
		}

		return $afectadas;
	}

	public function deleteSobrantes($valores, $clave){
		//genero una consulta para traer las filas que seran borradas
		$subconsulta='`urls_productos`.`id_producto` NOT IN('.$this->db->select('productos.id_producto')->from('productos')->where_in('nombre_producto', $valores)->get_compiled_select().')';

		$this->db->select('urls_productos.id_producto');
		$this->db->from('urls_productos');
		$this->db->join('productos', 'productos.id_producto = urls_productos.id_producto');
		$this->db->where('usuario_producto', $this->session->documento);
		$this->db->where('id_proveedor', $clave);
		$this->db->where($subconsulta);
		$query=$this->db->get();

		//preparo los datos llegados
		$datos=[];
		foreach ($query->result() as $row){
        	array_push($datos, $row->id_producto);
		}

		//elimino las filas
		if (!empty($datos)) {
			$this->db->where('id_proveedor', $clave);
			$this->db->where_in('id_producto', $datos);
			$this->db->delete('urls_productos');
		}

		return $this->db->affected_rows();
	}

	public function delete($clave){
		//eliminos todos los registros que contenga la clave
		$this->db->select('productos.id_producto');
		$this->db->from('productos');
		$this->db->join('urls_productos', 'productos.id_producto = urls_productos.id_producto');
		$this->db->where('usuario_producto', $this->session->documento);
		$query=$this->db->get();

		//preparo los datos llegados
		$datos=[];
		foreach ($query->result() as $row){
        	array_push($datos, $row->id_producto);
		}

		if (!empty($datos)) {
			$this->db->where_in('id_producto', $datos);
			$this->db->delete('urls_productos', array('id_proveedor' => $clave));
		}

		return $this->db->affected_rows();
	}

	public function consultar_idProveedor($nombre_producto){
		$this->db->distinct();
		$this->db->select('urls_productos.id_proveedor');
		$this->db->from('urls_productos');
		$this->db->join('productos', 'productos.id_producto = urls_productos.id_producto');

		if ($this->session->tipo_usuario!='ADMINISTRADOR') {
			$this->db->where('usuario_producto', $this->session->documento);
		}
		$this->db->where('nombre_producto', $nombre_producto);
		$datos=$this->db->get();

		return $datos->result();
	}

	public function deleteDetalle($datos){
		$this->db->where_in('id_producto', $datos);
		$this->db->delete('urls_productos');
	}

}

/* End of file Urls_productos.php */
/* Location: ./application/models/Urls_productos.php */