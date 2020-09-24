<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitudes_controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
		is_ajax();
		//cargo los modelos necesarios.
		$this->load->model(array('Solicitudes','Salidas','Personas_Externas','Consumibles','Productos'));	
	}
	public function agregarSolicitud(){
		if (!empty($_POST['usuario_solicitud'])) {
			$usuario=$this->input->post('usuario_solicitud');
			$estado_solicitud="En proceso";
			echo json_encode($this->Solicitudes->agregarSolicitud($usuario,$estado_solicitud));
		}else{
			echo json_encode(array('aviso' => false,'error'=>true,'texto'=>'No llegan los datos'));
		}
	}
	public function cancelSolicitud(){
		if (!empty($_POST['id_solicitud'])) {
			$id_solicitud=$this->input->post('id_solicitud');
			$estado_solicitud="Cancelado";
			$this->db->trans_begin(); 
			$arr=$this->Salidas->consultarSalidasCantidad($id_solicitud);
			if ($arr['aviso']) {
				for ($i=0; $i < count($arr['salidas']); $i++) { 
					$cantSal=$arr['salidas'][$i]['cantidad_salida'];
					$cantConsum=$arr['salidas'][$i]['cantidad_consumible'];
					$tot=$cantConsum+$cantSal;
					$product=$arr['salidas'][$i]['producto_salida'];
					$this->Consumibles->editarCantidadConsumible($product,$tot);
				}
			}
			$arr=json_decode($this->Salidas->consultarSalidas($id_solicitud),true);
			if ($arr['aviso']) {
				for ($i=0; $i < count($arr['salidasProd']); $i++) { 
					if ($arr['salidasProd'][$i]['estado_producto']==8) {
						$product=$arr['estado_salida'][$i]['producto_salida'];
						$array[0]=$product;
						$array['modificar']=array("estado_producto"=>null);
						$this->Productos->editar_material($array);	
					}		
				}	
			}
			$this->Solicitudes->setEstadoSolicitud($id_solicitud,$estado_solicitud);
			$this->Salidas->eliminarSalidas($id_solicitud);
			if ($this->db->trans_status()===FALSE) {
				$this->db->trans_rollback();
				$data_solic['aviso']=false;
				$data_solic['texto']='Problemas en la transaccion de cancelar Solicitud.';
				$i=count($productos);
			}else{
				$data_solic['aviso']=true;
				$data_solic['texto']='Cancelación de solicitud hecho con exito!';
				$this->db->trans_commit();
			}
			echo json_encode($data_solic);
		}
	}
	public function agregarSalidas(){  
		if (!empty($_POST['id_solicitud'])) {
			$total_solicitud=$this->input->post('total_solicitud');
			$id_solicitud=$this->input->post('id_solicitud');
			$estado_solicitud=$this->input->post('estado_solicitud');
			$data_solic=array();
			$data_solic['aviso']=false;
			$data_solic['texto']='';
			$data_solic['salidas']=array();
			$this->db->trans_begin();  
			$this->Solicitudes->setTotalSolicitud($id_solicitud,$total_solicitud);
			$this->Solicitudes->setEstadoSolicitud($id_solicitud,$estado_solicitud);
			$productos=$this->input->post('productos');
			if ($productos!=="" && $productos!=null && $productos) {
				for ($i=0; $i < count($productos); $i++) { 
					if ($productos[$i]['consumible']==="true" || $productos[$i]['consumible']===true) {
						$this->Consumibles->editarCantidadConsumible($productos[$i]['producto_salida'],$productos[$i]['n_materiales'],false);
					}
					$producto_salida=$productos[$i]['producto_salida'];
					$cantidad_salida=$productos[$i]['cantidad_salida'];
					$estado_salida=$productos[$i]['estado_salida'];
					if ($estado_salida==="No retorna" && $productos[$i]['consumible']==="false") {
						$array[0]=$producto_salida;
						$array['modificar']=array("estado_producto"=>8);
						$this->Productos->editar_material($array);	
					}
					$tipo_salida=$productos[$i]['tipo_salida'];
					$exterior=$productos[$i]['exterior'];
					$persona=$productos[$i]['persona_id'];
					$salida=json_decode($this->Salidas->agregarSalida($id_solicitud,$producto_salida,$cantidad_salida,$estado_salida,$tipo_salida,$persona,$exterior),true);
					if ($this->db->trans_status()===FALSE) {
						$this->db->trans_rollback();
						$data_solic['aviso']=false;
						$data_solic['texto']='Problemas en la transaccion de productos.';
						$i=count($productos);
					}
				}	
			}
			if ($this->db->trans_status()===FALSE) {
				$this->db->trans_rollback();
				$data_solic['aviso']=false;
				$data_solic['texto']='Problemas en la transaccion de productos.';
			}else{
				$data_solic['aviso']=true;
				$data_solic['texto']='Registro de solicitud hecho con exito!';
				$this->db->trans_commit();
			}
			echo json_encode($data_solic);
		}else{
			echo json_encode(array('aviso' => false,'error'=>true,'texto'=>'No llegan los datos'));
		}
	}
	public function consultarPersonaExterna(){
		if (!empty($_POST['documento'])) {
			$documento=$this->input->post('documento');
			echo $this->Personas_Externas->consultarPersonaExterna($documento);
		}else{
			echo json_encode(array('aviso' => false,'error'=>true,'texto'=>'No llegan los datos'));
		}
	}
	public function agregarPersonaExterna(){
		if (!empty($_POST['documento_exterior'])) {
			$documento=$this->input->post('documento_exterior');
			$nombre=$this->input->post('nombre_exterior');
			$empresa=$this->input->post('empresa_exterior');
			$cargo=$this->input->post('cargo_exterior');
			$telefono=$this->input->post('telefono_exterior');
			echo $this->Personas_Externas->agregarPersonaExterna($documento,$nombre,$empresa,$cargo,$telefono);
		}else{
			echo json_encode(array('aviso' => false,'error'=>true,'texto'=>'No llegan los datos'));
		}	
	}
	public function consultarSalidas(){
		if (!empty($_POST['solicitud_salida'])) {
			$solicitud_salida=$this->input->post('solicitud_salida');
			echo $this->Salidas->consultarSalidas($solicitud_salida);
		}else{
			echo json_encode(array('aviso' => false,'error'=>true,'texto'=>'No llegan los datos'));
		}
	}
	public function consultarSolicitud(){
		if (!empty($_POST['usuario_solicitud'])) {
			$usuario_solicitud=$this->input->post('usuario_solicitud');
			echo json_encode($this->Solicitudes->consultarUltimaSolicitudUsuario($usuario_solicitud));
		}
	}
	public function editarSalidas(){
		if (!empty($_POST['id_solicitud'])) {
			$total_solicitud=$this->input->post('total_solicitud');
			$id_solicitud=$this->input->post('id_solicitud');
			$estado_solicitud=$this->input->post('estado_solicitud');
			$data_solic=array();
			$data_solic['aviso']=false;
			$data_solic['texto']='';
			$data_solic['salidas']=array();
			$productos=$this->input->post('productos');
			if ($productos!=="" && $productos!=null && $productos) {
				$this->db->trans_begin();
				$this->Solicitudes->setTotalSolicitud($id_solicitud,$total_solicitud);
				$this->Solicitudes->setEstadoSolicitud($id_solicitud,$estado_solicitud);
				$arr=$this->Salidas->consultarSalidasCantidad($id_solicitud);
				if ($arr['aviso']) {
					for ($i=0; $i < count($arr['salidas']); $i++) { 
						$cantSal=$arr['salidas'][$i]['cantidad_salida'];
						$cantConsum=$arr['salidas'][$i]['cantidad_consumible'];
						$tot=$cantConsum+$cantSal;
						$product=$arr['salidas'][$i]['producto_salida'];
						$this->Consumibles->editarCantidadConsumible($product,$tot);
					}
				}
				$arr=json_decode($this->Salidas->consultarSalidas($id_solicitud),true);
				if ($arr['aviso']) {
					for ($i=0; $i < count($arr['salidasProd']); $i++) { 
						if ($arr['salidasProd'][$i]['estado_producto']==8) {
							$product=$arr['salidasProd'][$i]['producto_salida'];
							$array[0]=$product;
							$array['modificar']=array("estado_producto"=>null);
							$this->Productos->editar_material($array);	
						}		
					}	
				}
				$this->Salidas->eliminarSalidas($id_solicitud);
				if ($this->db->trans_status()===FALSE) {
					$this->db->trans_rollback();
					$data_solic['aviso']=false;
					$data_solic['texto']='Problemas en la eliminación de productos.';
					$i=count($productos);
				}else{
					for ($i=0; $i < count($productos); $i++) { 
						if ($productos[$i]['consumible']==="true" || $productos[$i]['consumible']===true) {
							$this->Consumibles->editarCantidadConsumible($productos[$i]['producto_salida'],$productos[$i]['n_materiales']);
						}
						$producto_salida=$productos[$i]['producto_salida'];
						$cantidad_salida=$productos[$i]['cantidad_salida'];
						$estado_salida=$productos[$i]['estado_salida'];
						if ($estado_salida==="No retorna") {
							$array[0]=$producto_salida;
							$array['modificar']=array("estado_producto"=>8);
							$this->Productos->editar_material($array);	
						}
						$tipo_salida=$productos[$i]['tipo_salida'];
						$exterior=$productos[$i]['exterior'];
						$persona=$productos[$i]['persona_id'];
						$salida=json_decode($this->Salidas->agregarSalida($id_solicitud,$producto_salida,$cantidad_salida,$estado_salida,$tipo_salida,$persona,$exterior),true);
						if ($this->db->trans_status()===FALSE) {
							$this->db->trans_rollback();
							$data_solic['aviso']=false;
							$data_solic['texto']='Problemas en la transaccion de productos.';
							$i=count($productos);
						}
					}
					if ($this->db->trans_status()===FALSE) {
						$this->db->trans_rollback();
						$data_solic['aviso']=false;
						$data_solic['texto']='Problemas en la transaccion de productos.';
						$i=count($productos);
					}else{
						$data_solic['aviso']=true;
						$data_solic['texto']='Edición de solicitud hecho con exito!';
						$this->db->trans_commit();
					}	
				}
			}
			echo json_encode($data_solic);
		}	
	}
	public function terminarSalidasPrestamos(){
		if (!empty($_POST['id_solicitud'])) {
			$id_solicitud=$this->input->post('id_solicitud');
			$estado_salida='Retornado';
			$estado_solicitud='Terminado';
			$this->db->trans_begin();
			$arr=$this->Salidas->consultarSalidasCantidad($id_solicitud,"En prestamo");
			if ($arr['aviso']) {
				for ($i=0; $i < count($arr['salidas']); $i++) { 
					$cantSal=$arr['salidas'][$i]['cantidad_salida'];
					$cantConsum=$arr['salidas'][$i]['cantidad_consumible'];
					$tot=$cantConsum+$cantSal;
					$product=$arr['salidas'][$i]['producto_salida'];
					$this->Consumibles->editarCantidadConsumible($product,$tot);
				}
			}
			if (json_decode($this->Salidas->setEstadosSalida($id_solicitud,$estado_salida),true)['aviso']) {
				$arr=json_decode($this->Solicitudes->setEstadoSolicitud($id_solicitud,$estado_solicitud),true);	
			}
			if ($this->db->trans_status()===FALSE) {
				$this->db->trans_rollback();
				$arr['aviso']=false;
			}else{
				$this->db->trans_commit();	
			}
			echo json_encode($arr);
		}
	}
	public function terminarSalidaPrestamo(){
		if (!empty($_POST['id_salida'])) {
			$id_salida=$this->input->post('id_salida');
			$estado_salida='Retornado';
			$id_solicitud=$this->input->post('id_solicitud');
			$id_producto=$this->input->post('id_producto');
			$consumible=$this->input->post('consumible');
			$cantidad=$this->input->post('cantidad');
			$this->db->trans_begin();
			if ($consumible==="true" || $consumible===true) {
				$this->Consumibles->editarCantidadConsumible($id_producto,$cantidad,true);
			}
			$arr=json_decode($this->Salidas->setEstadoSalida($id_salida,$estado_salida),true);	
			
			$arr['n_salidas']=$this->Salidas->consultarNSalidasEnPrestamo($id_solicitud);
			$arr['aviso2']=false;
			if ($arr['n_salidas']==0) {
				$estado_solicitud="Terminado";
				$arr['aviso2']=json_decode($this->Solicitudes->setEstadoSolicitud($id_solicitud,$estado_solicitud),true)['aviso'];
			}
			if ($this->db->trans_status()===FALSE) {
				$this->db->trans_rollback();
				$arr['aviso']=false;
			}else{
				$this->db->trans_commit();
			}
			echo json_encode($arr);
			unset($arr);
		}
	}
}

/* End of file Solicitudes_controller.php */
/* Location: ./application/controllers/Solicitudes_controller.php */