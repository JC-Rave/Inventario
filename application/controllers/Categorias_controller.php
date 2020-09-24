<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();
		//cargo los modelos necesarios.
		$this->load->model('Categorias_model');	
	}

	public function index(){}
 
	public function agregarCategoria(){ 
		$nombre= $this->input->post("add_nom_cat");
		$descripcion= $this->input->post("add_descripcion_cat");
		$estado= $this->input->post("add_estado_cat");			
		echo $this->Categorias_model->addCategoria($nombre,$descripcion,$estado);
	}

	public function consultarCategorias(){ 
		echo $this->Categorias_model->getCategorias();		
	}

	public function consultarCategoria(){
		echo $this->Categorias_model->getCategoria($id_categoria);
	}

	public function modificarCategoria(){
		$id=$this->input->post("edit_id_cat");
		$nom=$this->input->post("edit_nom_cat");
		$desc=$this->input->post("edit_descripcion_cat");
		$est=$this->input->post("edit_estado_cat");			
		echo $this->Categorias_model->setCategoria($id,$nom,$desc,$est);
	}	
}

