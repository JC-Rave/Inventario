<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vistas extends CI_Controller {
 
	public function __construct(){
		parent::__construct();
		if (!$this->session->logged_in) {
			redirect('Login_controller','refresh');
		}
	}

	public function index(){
		$this->load->model(array('Usuarios','Pedidos'));

		//obetengo la respuesta de base de datos y la envio a la funcion vista
		$datos['num_user']=$this->Usuarios->consultar_usuarios(true);
		$datos['num_pedidos']=$this->Pedidos->consultar_pedidos(true);
		$this->vista('principal', $datos);
	}
	public function perfil(){
		$this->load->model('Productos');

		//obetengo la respuesta de base de datos y la envio a la funcion vista
		$datos['num_productos']=$this->Productos->nProductosAcargo();
		$this->vista('perfil', $datos);
	}

	public function cambiar_pass(){$this->vista('cambiar_pass');}

	public function adm_usuarios(){
		if ($this->session->tipo_usuario=='INSTRUCTOR'){
			redirect('Vistas','refresh');
		}

		//cargo los modelos necesarios
		$this->load->model('Usuarios');

		//obetengo la respuesta de base de datos y la envio a la funcion vista
		$datos['datas']=$this->Usuarios->consultar_usuarios();		
		$this->vista('adm_usuarios', $datos);
	}

	public function reg_usuario(){
		if ($this->session->tipo_usuario=='INSTRUCTOR'){
			redirect('Vistas','refresh');
		}

		$this->vista('reg_usuario');
	}

	public function adm_proveedores(){
		//cargo los modelos necesarios
		$this->load->model(array('Productos', 'Proveedores'));

		//obetengo la respuesta de base de datos y la envio a la funcion vista
		$datos['proveedores']=$this->Proveedores->consultar_proveedores();
		$datos['productos']=$this->Productos->consultProdutos();
		
		$this->vista('adm_proveedores', $datos);
	}

	public function adm_materiales(){
		//cargo los modelos necesarios
		$this->load->model(array('Productos', 'Galeria_productos'));

		//obetengo la respuesta de base de datos y la envio a la funcion vista
		$datos['materiales']=$this->Productos->consultar_materiales();
		$datos['imagenes']=$this->Galeria_productos->consultar_imagenes();
		$this->vista('adm_materiales', $datos);
	}

	public function adm_devolutivos(){$this->vista('adm_devolutivos');}

	public function adm_pedidos(){
		//cargo los modelos necesarios
		$this->load->model('Pedidos');

		$datos['pedidos']=$this->Pedidos->consultar_pedidos();
		$this->vista('adm_pedidos', $datos);
	}

	public function detalle_pedido(){
		$url=$this->uri->segment_array();
		$pedido='';
		for ($i=3; $i <=count($url) ; $i++) { 
			$i==count($url)?$pedido.=$url[$i]:$pedido.=$url[$i].'/';
		}
		$pedido=$this->encryption->decrypt($pedido);

		//cargo los modelos necesarios
		$this->load->model(array('Productos', 'Galeria_productos', 
			'Categorias_model', 'Unidadmedida', 'Proveedores', 'Detalle_pedido', 'Pedidos'));

		!$pedido?$id_usuario='':$id_usuario=$this->Pedidos->consultar_usuarioPedido($pedido);
		$datos['materiales']=$this->Productos->consultar_productos($id_usuario);
		$datos['devolutivos']=$this->Productos->consultar_productos($id_usuario, 'devolutivos');
		$datos['proveedores']=$this->Proveedores->consultar_proveedores(true);
		$datos['imagenes']=$this->Galeria_productos->consultar_imagenes();
		$datos['categorias']=$this->Categorias_model->preSelect();
		$datos['medidas']=$this->Unidadmedida->preSelect();
		$datos['productos']=!$pedido?'':$this->Detalle_pedido->detallePedido($pedido);

		$this->vista('reg_pedido', $datos);
	}

	public function adm_solicitudes(){
		$this->load->model('Solicitudes');
		$datos['solicitudes']=$this->Solicitudes->consultarSolicitudesUser($this->session->documento);
		$this->vista('adm_solicitudes',$datos);
	}
	
	public function reg_solicitud(){
		$this->load->model(array('Salidas','Solicitudes'));
		$this->vista('reg_solicitud');
	}

	public function galeria(){
		$this->load->model('Galeria_productos');
		$datos['imagenes']=$this->Galeria_productos->consultar_imagenes();
 
		$this->vista('galeria', $datos);
	}

	public function unidad_medida(){
		if ($this->session->tipo_usuario=='INSTRUCTOR'){
			redirect('Vistas','refresh');
		}
		$this->load->model('Unidadmedida');

		$datos['unidades']=$this->Unidadmedida->consultar_unidades();
		$this->vista('unidad_medida', $datos);
	}

	public function categorias(){
		if ($this->session->tipo_usuario=='INSTRUCTOR'){
			redirect('Vistas','refresh');
		}
		$this->vista('categorias');
	}
	public function historial_consumo(){
		$this->load->model('Historial_consumible');
		$datos['historiales']=$this->Historial_consumible->consultar_historial();

		$this->vista('adm_historial', $datos);
	}
	public function vista($vista, $datos=''){
		$this->load->view('cuerpo/header');
		$this->load->view('cuerpo/sidebar');
		//cargo la vista solicitada y envio los datos a la misma
		$this->load->view($vista, $datos);
		$this->load->view('cuerpo/footer');
	}
}