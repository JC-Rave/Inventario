<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galeria_productos extends CI_Model {

	public function consultar_imagenes(){
		$this->db->select('nombre, imagen');
		$this->db->order_by('nombre', 'asc');
		$datos=$this->db->get('galeria_productos');
 
		return $datos->result();
	}

	public function registrar($datos){
		$this->db->insert('galeria_productos', $datos);
	}

	public function editar($datos){
		$this->db->update('galeria_productos',$datos['modificar'],array('nombre'=>$datos[0]));

		return $this->db->affected_rows();
	}

	public function consultarImagen($nombre, $bool=false){
		$buscar=!$bool?'imagen':'*';
		$this->db->select($buscar);
		$this->db->where('nombre', $nombre);
		$dato=$this->db->get('galeria_productos')->row();

		return !$bool?$dato->imagen:$dato;
	}

	public function consultar_idImagen($nombre){
		$this->db->select('id_galeria');
		$this->db->from('galeria_productos');
		$this->db->where('nombre', $nombre);
		$id=$this->db->get()->row();

		return $id->id_galeria;
	}

	public function validar_unico($campo, $comparar){
		$this->db->select('id_galeria');
		$this->db->from('galeria_productos');
		$this->db->where($campo, $comparar);

		return $this->db->count_all_results()>=1?true:false;
	}
	public function consultarImagenesProd($producto=""){
		$this->db->select('*');
		$this->db->from('galeria_productos');
		if ($producto!=="") {
			$this->db->where('galeria_productos.tipo_img',$producto);
		}
		$datos=$this->db->get();
		if ($datos->num_rows()!=0) {
			$array['aviso']=true;
			$array['imagenes']=$datos->result_array();
		}else{
			$array['aviso']=false;
		}
		return json_encode($array);
	} 

}

/* End of file Galeria.php */
/* Location: ./application/models/Galeria.php */