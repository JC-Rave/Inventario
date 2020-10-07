<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detalle_pedido extends CI_Model {

	public function insert($datos){
		//si utiliza transaccion antes de llamar la funcion pre-inserta una persona
		//de lo contrario inserta una persona
		$this->db->insert_batch('detalle_pedido', $datos);

		return $this->db->affected_rows();
	}

	public function consultar_productos($pedido){
		$subconsulta='(SELECT url FROM proveedores WHERE id_proveedor=proveedor_1) url_1, (SELECT url FROM proveedores WHERE id_proveedor=proveedor_2) url_2, (SELECT url FROM proveedores WHERE id_proveedor=proveedor_3) url_3';

		$this->db->distinct();
		$this->db->select('detalle_pedido.descripcion, galeria_productos.imagen, unidad_medida.nombre_unidad, detalle_pedido.cantidad, detalle_pedido.precio_1, detalle_pedido.precio_2, detalle_pedido.precio_3, '.$subconsulta);
		$this->db->from('detalle_pedido');
		$this->db->join('productos', 'productos.id_producto = detalle_pedido.producto');
		$this->db->join('galeria_productos', 'galeria_productos.id_galeria = productos.imagenp', 'left');
		$this->db->join('unidad_medida', 'unidad_medida.id_unidad = productos.unidad_medida');
		$this->db->where('pedido', $pedido);
		$datos=$this->db->get();

		return $datos->result();
	}

	public function detallePedido($pedido){
		$subconsulta='(SELECT cantidad_consumible FROM consumibles 
		JOIN productos ON (productos.id_producto=consumibles.id_consumible) 
		WHERE id_producto=producto) cantidad_actual, (SELECT nit FROM proveedores 
		WHERE id_proveedor=proveedor_1) nit_1, (SELECT nit FROM proveedores 
		WHERE id_proveedor=proveedor_2) nit_2, (SELECT nit FROM proveedores 
		WHERE id_proveedor=proveedor_3) nit_3, (SELECT url FROM proveedores 
		WHERE id_proveedor=proveedor_1) url_1, (SELECT url FROM proveedores 
		WHERE id_proveedor=proveedor_2) url_2, (SELECT url FROM proveedores 
		WHERE id_proveedor=proveedor_3) url_3, (SELECT DISTINCT tipo_producto 
		FROM productos WHERE nombre_producto IN(SELECT nombre_producto FROM productos
		WHERE id_producto=producto) AND tipo_producto!="Pedido") tipo, 
		(SELECT urls_productos.descripcion FROM urls_productos 
		WHERE urls_productos.id_proveedor=proveedor_1
		AND urls_productos.id_producto=producto) descripcion_1, 
		(SELECT urls_productos.descripcion FROM urls_productos 
		WHERE urls_productos.id_proveedor=proveedor_2 
		AND urls_productos.id_producto=producto) descripcion_2, 
		(SELECT urls_productos.descripcion FROM urls_productos 
		WHERE urls_productos.id_proveedor=proveedor_3 
		AND urls_productos.id_producto=producto) descripcion_3, 
		(SELECT id_consumible FROM consumibles WHERE id_consumible=producto) insertar';

		$this->db->distinct();
		$this->db->select('detalle_pedido.descripcion, nombre_producto, galeria_productos.imagen, nombre, id_unidad, nombre_unidad, cantidad, precio_1, precio_2, precio_3, id_categoria, nombre_categoria, descripcion_producto, precio_producto, '.$subconsulta);
		$this->db->from('detalle_pedido');
		$this->db->join('productos', 'productos.id_producto = detalle_pedido.producto');
		$this->db->join('galeria_productos', 'galeria_productos.id_galeria = productos.imagenp', 'left');
		$this->db->join('unidad_medida', 'unidad_medida.id_unidad = productos.unidad_medida');
		$this->db->join('categorias', 'categorias.id_categoria = productos.categoria_producto');
		$this->db->where('detalle_pedido.pedido', $pedido);
		$datos=$this->db->get();

		return $datos->result();
	}

	public function editar_productos($datos){
		$this->db->update_batch('detalle_pedido', $datos, 'producto');
	}

	public function deleteDetalle($datos){
		$this->db->where_in('producto', $datos);
		$this->db->delete('detalle_pedido');
	}

}

/* End of file Detalle_pedido.php */
/* Location: ./application/models/Detalle_pedido.php */