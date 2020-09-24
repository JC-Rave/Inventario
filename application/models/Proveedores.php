<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Proveedores extends CI_Model {

	public function consultar_proveedores($bool=false){
		if ($bool) {
			$this->db->where('estado', 'a');
		}
		//extraigo todos los datos de la tabla proveedores		
		$datos=$this->db->get('proveedores');

		//retorno todos los datos como objetos
		return $datos->result(); 
	}

	public function reg_proveedor($datos){
		//si utiliza transaccion antes de llamar la funcion pre-inserta un proveedor
		//de lo contrario inserta un proveedor
		$this->db->insert('proveedores', $datos);
		
		//devuelvo el id del proveedor insertado
		return $this->db->insert_id();
	}

	public function editar_proveedor($datos){
		//si utiliza transaccion antes de llamar la funcion pre-actuliza un proveedor
		//de lo contrario actuliza un proveedor
		$this->db->update('proveedores', $datos['modificar'], array('nit'=>$datos[0]));
		$afectadas=$this->db->affected_rows();
		
		//obtengo el id del proveedor modificado
		$this->db->select('id_proveedor');
		$this->db->where('nit', $datos[1]);
		$id=$this->db->get('proveedores')->row();

		//retorno el id
		return array($id->id_proveedor, $afectadas);
	}

	public function consultProveedores($nombre, $usuario){
		$this->db->distinct();
		$this->db->select('nit, nombre_proveedor, url, urls_productos.precio, urls_productos.descripcion');
		$this->db->from('proveedores');
		$this->db->join('urls_productos', 'urls_productos.id_proveedor = proveedores.id_proveedor');
		$this->db->join('productos', 'productos.id_producto = urls_productos.id_producto');
		$this->db->where('nombre_producto', $nombre);

		if ($this->session->tipo_usuario=='INSTRUCTOR') {
			$this->db->where('usuario_producto', $usuario);
		}
		$datos=$this->db->get();

		return $datos->result();
	}

	public function query_proveedores($nombre){
		$this->db->distinct();
		$this->db->select('nit, nombre_proveedor, url, urls_productos.precio, urls_productos.descripcion');
		$this->db->from('proveedores');
		$this->db->join('urls_productos', 'urls_productos.id_proveedor = proveedores.id_proveedor');
		$this->db->join('productos', 'productos.id_producto = urls_productos.id_producto');
		$this->db->where('nombre_producto', $nombre);
		$this->db->where('estado', 'a');
		$this->db->where('usuario_producto', $this->session->documento);
		$datos=$this->db->get();

		return $datos->result();
	}

	public function validar_unico($campo, $comparar){
		$this->db->select('id_proveedor');
		$this->db->from('proveedores');
		$this->db->where($campo, $comparar);

		return $this->db->count_all_results()==1?true:false;
	}

	public function consultar_idProveedor($nit){
		$this->db->select('id_proveedor');
		$this->db->where('nit', $nit);
		$id=$this->db->get('proveedores')->row();

		return $id->id_proveedor;
	}
	

}