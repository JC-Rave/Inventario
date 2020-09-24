<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unidadmedida extends CI_Model {

	public function reg_unidad($datos){
		$this->db->insert('unidad_medida', $datos);
	}

	public function editar_unidad($datos){
		$this->db->update('unidad_medida', $datos['modificar'], array('nombre_unidad' => $datos[0]));

		return $this->db->affected_rows();
	}

	public function consultar_nombreunidad(){
		$this->db->distinct();
		$this->db->select('nombre_unidad');
		$this->db->from('unidad_medida');
		$this->db->join('productos', 'productos.unidad_medida = unidad_medida.id_unidad');
		$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
		$datos=$this->db->get();

		return $datos->result();	
	}	

	public function consultar_unidades(){
		$this->db->select('nombre_unidad, estado');
		$datos=$this->db->get('unidad_medida');		

		return $datos->result();
    }

    public function consultarUnidades(){
    	//extraigo todos los datos de la tabla unidad_medida
		$datos=$this->db->get('unidad_medida');
		if ($datos) {
			$array=$datos->result_array();
			$array+=['aviso'=>true,
					'texto'=>"consulta hecha con exito"];
		}else{
			$array=['aviso'=>false,'texto'=>"No hay unidades de medida"];
		}
		//retorno todos los datos como objetos
		return json_encode($array);
    }

    public function consultar_idUnidad($nombre){
    	$this->db->select('id_unidad');
    	$this->db->from('unidad_medida');
    	$this->db->where('nombre_unidad', $nombre);
    	$id=$this->db->get()->row();

    	return $id->id_unidad;
    }
   	
	public function preSelect(){
		$this->db->select('id_unidad, nombre_unidad');
		$this->db->from('unidad_medida');
		$this->db->where('estado', 'a');
		$datos=$this->db->get();

		return $datos->result();
	} 

	public function validar_unico($campo, $comparar){
		$this->db->select('id_unidad');
		$this->db->from('unidad_medida');
		$this->db->where($campo, $comparar);

		return $this->db->count_all_results()==1?true:false;
	}

}

/* End of file Unidad_medida.php */
/* Location: ./application/models/Unidad_medida.php */