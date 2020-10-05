<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Pedidos extends CI_Model {

	public function consultar_pedidos($proceso=false){
		$this->db->select('id_pedido, DATE_FORMAT(fecha_pedido, "%d/%m/%Y %H:%i:%s") fecha_pedido, DATE_FORMAT(fecha_entregado, "%d/%m/%Y %H:%i:%s") fecha_entregado, usuario_pedido, total, estado_pedido, nombre_persona, apellido_persona');	
		$this->db->from('pedidos');
		$this->db->join('personas', 'personas.documento_persona = pedidos.usuario_pedido');

		if ($this->session->tipo_usuario=='INSTRUCTOR') {
			$this->db->where('usuario_pedido', $this->session->documento);
		}

		if ($proceso) {
			return $this->db->count_all_results();
		}

		$datos=$this->db->get();
		return $datos->result();
	}

	public function reg_pedido($usuario_pedido, $estado_pedido){	
		$fecha=$estado_pedido=='Pendiente'?date('Y-m-d H:i'):NULL;
		$sql="INSERT INTO `pedidos` VALUES(NULL, ?, NULL, ?, 0, ?)";

		$this->db->query($sql, array($fecha, $usuario_pedido, $estado_pedido));
		return $this->db->insert_id();
	}

	public function editar_pedido($pedido, $estado_pedido){
		$this->db->query('CALL editar_pedido('.$pedido.', "'.$estado_pedido.'")');
	}

	public function update($datos){
		$this->db->update('pedidos', $datos['modificar'], array('id_pedido'=>$datos[0]));

		return $this->db->affected_rows();
	}

	public function consultar_usuarioPedido($pedido){
		$this->db->select('usuario_pedido');
		$this->db->from('pedidos');
		$this->db->where('id_pedido', $pedido);
		$id=$this->db->get()->row();

		return $id->usuario_pedido;
	}

}

/* End of file Pedidos.php */
/* Location: ./application/models/Pedidos.php */