<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Categorias_model extends CI_Model {	 
	public function addCategoria($nom,$desc,$est){  
		try {						
			$data = array( 						    
			    'nombre_categoria'=> $this->db->escape_str($nom), 
			    'descripcion_categoria'	=> $this->db->escape_str($desc),
			    'estado'	=>  $this->db->escape_str($est)
			);
			$this->db->insert('categorias', $data);			
			$resultado= $this->db->affected_rows();
			if ($resultado) {				
				$array=['aviso' => true,
						'texto' => 'Se agregó la categoría con éxito',
						'id_categoria'=> $this->db->insert_id(),
						'nombre_categoria'=> $nom,
						'descripcion_categoria'=> $desc,
						'estado'=> $est
					];
				return json_encode($array);  
			}else{			
				return json_encode(array('aviso' => false, 'texto' => 'No se agregó  la categoría :'.$resultado));
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al agregar la categoría: Error -> '.$e->getMesage()));
		}
	}

	public function getCategorias(){
		try {			
			$resultado = $this->db->get('categorias');

			if ($resultado) {
				$array=$resultado->result_array();
				$array+=['aviso' => true,'texto' => 'Consulta hecha con exito'];
				return json_encode($array);
			}else{			
				return json_encode(array('aviso' => false, 'texto' => 'No se logro consultar  las categorias'));
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al obtener todas las categorías: Error -> '.$e->getMesage()));
		}
	}

	public function getCategoria($id_cat){
		try {
			$consulta = "SELECT id_categoria,nombre_categoria,descripcion_categoria,estado FROM categorias WHERE id_categoria= ? ";
			$resultado = $this->db->query($consulta,array($id_cat));

			if ($resultado->num_rows() != 0) {
				$array=$resultado->row_array();
				$array+=['aviso' => true,'texto' => 'Categoría obtenida con exito'];
				return json_encode($array);
			}else{			 
				return json_encode(array('aviso' => false, 'texto' => 'No se logró obtener la categoría'));
			}
		} catch (Exception $e) {
			return json_encode(array('aviso' => false, 'texto' => 'Problemas al obtener la categoría: Error -> '.$e->getMesage()));
		}
	}

	public function setCategoria($id,$nom,$desc,$est){
		try {			
			$data = array( 		    
			    'nombre_categoria'=> $this->db->escape_str($nom), 
			    'descripcion_categoria'	=>  $this->db->escape_str($desc),
			    'estado'	=>  $this->db->escape_str($est)
			);
			$this->db->where('id_categoria', $id);
			$this->db->update('categorias', $data);			
			$resultado = $this->db->affected_rows();
			if ($resultado) {				
				$array=['aviso' => true,
						'texto' => 'Se modificó la categoría con éxito',
						'id_categoria'=> $id,
						'nombre_categoria'=> $nom,
						'descripcion_categoria'=> $desc,
						'estado'=> $est
						];
				return json_encode($array);
			}else{		
				$array=['aviso' => false,
						'error'=>false,
						'texto' => 'La categoría permanece igual'						
						];	
				return json_encode($array);
			}
		} catch (Exception $e) {
			$array=['aviso' => false,
					'error'=>true,
					'texto' => 'Problemas al modificar la categoría'						
					];	
			return json_encode($array);
		}
	}		
	public function consultar_nombreCategorias(){
		$this->db->distinct();
		$this->db->select('nombre_categoria');
		$this->db->from('categorias');
		$this->db->join('productos', 'productos.categoria_producto = categorias.id_categoria');
		$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
		$datos=$this->db->get();

		return $datos->result();	
	}

	public function preSelect(){
		$this->db->select('id_categoria, nombre_categoria');
		$this->db->from('categorias');
		$this->db->where('estado', 'a');
		$datos=$this->db->get();

		return $datos->result();
	}
} 
/* End of file categorias_model.php */
/* Location: ./application/models/categorias_model.php */