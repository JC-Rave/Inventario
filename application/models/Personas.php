<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personas extends CI_Model { 
 
	public function reg_persona($datos){
		//si utiliza transaccion antes de llamar la funcion pre-inserta una persona
		//de lo contrario inserta una persona
		$this->db->insert('personas', $datos);
	}

	public function editar_persona($datos){
		//si utiliza transaccion antes de llamar la funcion pre-actuliza una persona
		//de lo contrario actuliza
		$this->db->update('personas', $datos['modificar'], array('documento_persona'=>$datos[0]));

		//retorno filas afectadas
		return $this->db->affected_rows();
	}

	public function get_nombreApellido($clave){
		$this->db->select('nombre_persona, apellido_persona');
		$this->db->from('personas');
		$this->db->where('documento_persona', $clave);
		$datos=$this->db->get()->row();

		return $datos->nombre_persona.' '.$datos->apellido_persona;
	}

	public function preSelect(){
		$this->db->select('documento_persona, nombre_persona, apellido_persona');
		$this->db->from('personas');
		$this->db->join('usuarios', 'usuarios.id_usuario=personas.documento_persona');
		$this->db->where('estado', 'a');
		$this->db->where('id_usuario!=', $this->session->documento);
		$datos=$this->db->get();

		return $datos->result();
	}
	public function consultar_personas(){
		$this->db->distinct();
		$this->db->select('nombre_persona, apellido_persona, documento_persona');
		$this->db->from('personas');
		$this->db->join('pedidos', 'pedidos.usuario_pedido=personas.documento_persona');
		if ($this->session->tipo_usuario=='INSTRUCTOR') {
			$this->db->join('usuarios','usuarios.id_usuario=personas.documento_persona');
			$this->db->where('estado', 'a');
		}
		$this->db->where('documento_persona!=', $this->session->documento);
		$datos=$this->db->get();

		return $datos->result();
	}

	public function consultarPersonas(){
		$this->db->select('nombre_persona, apellido_persona, id_usuario');
		$this->db->from('personas');
		$this->db->join('usuarios', 'usuarios.id_usuario = personas.documento_persona');
		$this->db->where('estado', 'a');
		$this->db->where('id_usuario!=', $this->session->documento);
		$datos=$this->db->get();

		return $datos->result();
	}
}

