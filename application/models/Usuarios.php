<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Model { 
 
	public function reg_usuario($datos){
		//si utiliza transaccion antes de llamar la funcion pre-inserta un usuario
		//de lo contrario inserta un usuario
		$this->db->insert('usuarios', $datos);
	}

	public function editar_usuario($datos){
		//si utiliza transaccion antes de llamar la funcion pre-actuliza una persona
		//de lo contrario actuliza
		$this->db->update('usuarios', $datos['modificar'], array('id_usuario'=>$datos[0]));

		//retorno filas afectadas
		return $this->db->affected_rows();
	}

	public function getUsuariosActivos($accion=false){
		if ($accion) {
			try {			
				$this->db->select('id_usuario,usuario,tipo_usuario,linea,img,estado,nombre_persona,apellido_persona,telefono_persona,nombre_tipo,nombre_linea');
				$this->db->from('usuarios');
				$this->db->join('personas','personas.documento_persona = usuarios.id_usuario');
				$this->db->join('tipo_usuarios','tipo_usuarios.id_tipo = usuarios.tipo_usuario');
				$this->db->join('lineas','lineas.id_linea = usuarios.linea');			
				$this->db->where('estado','a');
				$resultado = $this->db->get();

				if ($resultado) {
					$array=$resultado->result_array();
					$array+=['aviso' => true,'texto' => 'Consulta hecha con exito'];
					return json_encode($array);
				}else{			
					return json_encode(array('aviso' => false, 'texto' => 'No se logro consultar  los usuarios'));
				}
			} catch (Exception $e) {
				return json_encode(array('aviso' => false, 'texto' => 'Problemas al obtener todas los usuarios activos: Error -> '.$e->getMesage()));
			}			
		}else{
			show_404();
		}
	}

	public function consultar_usuarios($accion=false, $boolean=false){		
		//genero una consulta para traer los datos necesaros de los usuarios
		if (!$accion) {
			$this->db->select('personas.*, usuario, estado, nombre_tipo, nombre_linea, img');
			$this->db->from('usuarios');
			$this->db->join('personas', 'personas.documento_persona = usuarios.id_usuario');
			$this->db->join('tipo_usuarios', 'tipo_usuarios.id_tipo = usuarios.tipo_usuario');
			$this->db->join('lineas', 'lineas.id_linea = usuarios.linea');
			$this->db->where('id_usuario!=', $this->session->documento);
			$datos=$this->db->get();

		}else if($boolean){
			$this->db->distinct();
			$this->db->select('nombre_persona, apellido_persona, id_usuario');
			$this->db->from('usuarios');
			$this->db->join('personas', 'personas.documento_persona = usuarios.id_usuario');
			$this->db->join('productos', 'productos.usuario_producto = usuarios.id_usuario');
			$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
			$this->db->where('estado', 'a');
			$this->db->where('id_usuario!=', $this->session->documento);
			$datos=$this->db->get();

		}else{
			$this->db->where('tipo_usuario!=', '1');
			return $this->db->count_all_results('usuarios');
		}

		//retorno todos los datos como objetos
		return $datos->result();
	}

	public function usuariosConPed(){
		$this->db->distinct();
		$this->db->select('nombre_persona, apellido_persona, documento_persona');
		$this->db->from('personas');
		$this->db->join('pedidos', 'pedidos.usuario_pedido=personas.documento_persona');
		$this->db->where('documento_persona!=', $this->session->documento);
		$datos=$this->db->get();

		return $datos->result();
	}

	public function consultar_usuario($credencial){
		$this->db->select('personas.*, password,usuario, nombre_tipo, nombre_linea, img, estado');
		$this->db->from('usuarios');
		$this->db->join('personas', 'personas.documento_persona = 
			usuarios.id_usuario');
		$this->db->join('tipo_usuarios', 'tipo_usuarios.id_tipo = usuarios.tipo_usuario');
		$this->db->join('lineas', 'lineas.id_linea = usuarios.linea');
		$this->db->where($credencial);
		$datos=$this->db->get();

		return $datos->row();
	}

	public function consultar_idUsuario($users){
		for ($i=0; $i<2; $i++) { 
			$this->db->select('id_usuario');
			$this->db->from('usuarios');
			$this->db->where('usuario', $users[$i]);
			$id=$this->db->get()->row();

			$id_users[]=$id->id_usuario;
		}

		return $id_users;
	}

	public function consultar_passUsuario($documento=""){
		$this->db->select('password');
		$this->db->from('usuarios');
		if ($documento!=="") {
			$this->db->where('id_usuario', $documento);	
		}else{
			$this->db->where('id_usuario', $this->session->documento);	
		}
		
		$pass=$this->db->get();

		return $pass->row();
	}

	public function validar_unico($campo, $comparar){
		$this->db->select('id_usuario');
		$this->db->from('usuarios');
		$this->db->where($campo, $comparar);

		return $this->db->count_all_results()==1?true:false;
	}

	public function usuariosInt_coninv($user=''){
		$this->db->distinct();
		$this->db->select('usuario, nombre_persona, apellido_persona');
		$this->db->from('usuarios');
		$this->db->join('personas','personas.documento_persona=usuarios.id_usuario');
		$this->db->join('productos','productos.usuario_producto=usuarios.id_usuario');
		$this->db->where('estado', 'i');

		if ($user!='') {
			$this->db->where('usuario', $user);	
			return $this->db->count_all_results()==1?true:false;

		}
		$datos=$this->db->get();
		return $datos->result();
	}

	public function usuariosAct_sininv($user=''){
		$subconsulta='`usuarios`.`id_usuario` NOT IN(SELECT `usuario_producto` 
		FROM productos)';
		
		$this->db->select('usuario, nombre_persona, apellido_persona');
		$this->db->from('usuarios');
		$this->db->join('personas','personas.documento_persona=usuarios.id_usuario');
		$this->db->where('estado', 'a');
		$this->db->where($subconsulta);

		if ($user!='') {
			$this->db->where('usuario', $user);	
			return $this->db->count_all_results()==1?true:false;

		}
		$datos=$this->db->get();
		return $datos->result();
	}

}

