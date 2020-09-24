<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Devolutivos_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model(array('Devolutivos_model','Productos'));	
	}

	public function index(){}
 
	public function agregarDevolutivo(){
		if (!empty($_POST)) {
			ini_set('max_execution_time', 300);
			set_time_limit(300);
			$cantidad=$this->input->post('cant_add_dev');
			if (empty($cantidad)||$cantidad==0) {
				$cantidad=1;
			}
			$data_prod=array();
			$data_prod['aviso']=false;
			$data_prod['error']=false;
			$data_prod['texto']='';
			$data_prod['productos']=array();
			$categoria=$this->input->post("categoria_add_dev");
			$estado=$this->input->post("estado_add_dev");
			$unidad=$this->input->post("unidad_add_dev");
			$linea=$this->input->post("linea_add_dev");
			if (!isset($_POST['usuario_add_dev']) || empty($_POST['usuario_add_dev'])) {
				$usuario=$this->input->post("usuario_add_dev2");
			}else{
				$usuario=$this->input->post("usuario_add_dev"); 
			}
			
			$nombrep= $this->input->post("nombre_add_dev");
			$descripcion= $this->input->post("descripcion_add_dev");			
			$precio= $this->input->post("precio_add_dev");			 
			$tipo="Devolutivo";
			$placa=$this->input->post("placa_add_dev");			
			$serial=$this->input->post("serial_add_dev");			
			$csena=$this->input->post("csena_add_dev");		
			$imagen=$this->input->post("imagenp");
			$imagenNom=$this->input->post("imagen");
			if (empty($imagen) || $imagen==="" || $imagenNom==="sinFoto.png") {
				$imagen=null;
			}
			for ($i=0; $i < $cantidad; $i++) { 
				# code...
				try {
					$this->db->trans_begin(); 
					$res_prod=json_decode($this->Productos->agregarProducto($categoria,$estado,$unidad,$linea,$usuario,$nombrep,$descripcion,$precio,$tipo,$imagen),true);				
					//condición para agregar el producto a la tabla urls_produtos
					$this->load->model('Urls_productos');
					$proveedores=$this->Urls_productos->consultar_idProveedor($nombrep);
					if (!empty($proveedores)) {
						$datos=[];
						foreach ($proveedores as $proveedor) {
							$fila=array(
								'id_proveedor'=>$proveedor->id_proveedor, 
								'id_producto'=>$res_prod['id_producto']
							);

							array_push($datos, $fila);
						}
						
						$this->Urls_productos->insert($datos);
					}

					//fin condición
					if ($this->db->trans_status()===FALSE) {
						// Cancelo guardado de productos
						$this->db->trans_rollback();
						$data_prod['aviso']=false;
						$data_prod['error']=true;
						$data_prod['texto']='Problemas en la transaccion de productos.';
						$i=$cantidad;
					}else{
						if ($res_prod['aviso']) {
							$res_dev=json_decode($this->Devolutivos_model->addDevolutivo($res_prod['id_producto'],$placa,$serial,$csena),true);
							if ($this->db->trans_status()===FALSE) {
								// Cancelo guardado de devolutivos y productos
								$this->db->trans_rollback();
								$data_prod['aviso']=false;
								$data_prod['error']=true;
								$data_prod['texto']='Problemas en la transaccion de devolutivos.';
								$i=$cantidad;
							}else{
								if ($res_dev['aviso']) {
									$data_prod['aviso']=true;
									$data_prod['texto']='Se agregó el devolutivo correctamente';
									$array=array(
										'id_producto'=> $res_prod['id_producto'],//0
										'categoria_producto'=>$categoria,//1-
									    'estado_producto'=> $estado,//2
									    'unidad_medida'=> $unidad,//3-
									    'linea_producto'=> $linea,//4
									    'usuario_producto'=> $usuario,//5
									    'nombre_producto'=> $nombrep,//6-
									    'descripcion_producto'=> $descripcion,//7
									    'precio_producto'=> $precio,//8
									    'tipo_producto'=> $tipo,//9
									    'imagen'=> $imagenNom,//10-
									    'placa'=>$placa,//11-
									    'codigo_sena'=>$csena,//12-
									    'serial'=>$serial,//13-
									    'imagenp'=>$imagen//14-
									);
									if ($cantidad==1) {
										$data_prod['productos']=$array;
									}else{
										array_push($data_prod['productos'],$array);
									}
									$this->db->trans_commit();
								}else{
									echo json_encode($res_dev);
								}								
							}
						}else{
							echo json_encode($res_prod);
						}					
					}
				} catch (Exception $e) {
					echo array('aviso' => false,'error'=>true,'texto'=>'Problemas en try catch.'.$e->getMessage());
				}	
			}
			echo json_encode($data_prod);
			unset($data_prod,$cantidad,$categoria,$estado,$unidad,$linea,$usuario,$nombrep,$descripcion,$precio,$tipo,$placa,$serial,$csena);
		}else{
			echo json_encode(array("aviso"=>false,"error"=>true,"texto"=>"No llega la información"));
		}
		// limpiar variables
		
		
	} 

	public function consultarDevolutivos(){
		echo $this->Devolutivos_model->getDevolutivos();
	}

	public function modificarDevolutivo(){
		if (!empty($_POST)) {
			# code...
			$repetir=$this->input->post('repetir');
			if ($repetir==="true") {
				$data_prod=array();
				$data_prod['aviso']=false;
				$data_prod['error']=false;
				$data_prod['texto']='';
				$data_prod['productos']=array();
				$cantidad=$this->input->post('cant_edit_dev');
				$devolutivos=$this->input->post('devolutivos');
				for ($i=0; $i < $cantidad; $i++) { 
					$id_producto=$devolutivos[$i]['idProducto_edit_dev'];//
					$categoria=$devolutivos[$i]['categoria_edit_dev'];//
					$estado=$devolutivos[$i]['estado_edit_dev'];//
					$unidad=$devolutivos[$i]['unidad_edit_dev'];//
					$linea=$devolutivos[$i]['linea_edit_dev'];//
					if (!isset($devolutivos[$i]['usuario_edit_dev']) || $devolutivos[$i]['usuario_edit_dev2']==="") {
						$usuario=$devolutivos[$i]['usuario_edit_dev2'];//
					}else{
						$usuario=$devolutivos[$i]['usuario_edit_dev'];//
					}
					$nombrep= $devolutivos[$i]['nombre_edit_dev'];//
					$descripcion= $devolutivos[$i]['descripcion_edit_dev'];//
					$precio= $devolutivos[$i]['precio_edit_dev'];//
					$tipo="Devolutivo";//
					$imagen=$devolutivos[$i]['imagenp'];//
					$imagenNom=$devolutivos[$i]['imagen'];//
					if ($imagenNom==="sinFoto.png") {
						$imagen=null;
					}
					try {
						$this->db->trans_begin();
						$res_prod=json_decode($this->Productos->modificarProducto($id_producto,$categoria,$estado,$unidad,$linea,$usuario,$nombrep,$descripcion,$precio,$tipo,$imagen),true);
						if ($this->db->trans_status()===FALSE) {
							// Cancelo edición de productos
							$this->db->trans_rollback();
							$data_prod['aviso']=false;
							$data_prod['error']=true;
							$data_prod['texto']='Problemas en la transaccion de productos.';
							$i=$cantidad;
						}else{
							$data_prod['aviso']=true;
							$data_prod['repetir']=true;
							$data_prod['texto']='Se modificó el devolutivo correctamente';
							$array=array(
								'id_producto'=> $id_producto,//0
								'categoria_producto'=>$categoria,//1-
							    'estado_producto'=> $estado,//2
							    'unidad_medida'=> $unidad,//3-
							    'linea_producto'=> $linea,//4
							    'usuario_producto'=> $usuario,//5
							    'nombre_producto'=> $nombrep,//6-
							    'descripcion_producto'=> $descripcion,//7
							    'precio_producto'=> $precio,//8
							    'imagen'=> $imagenNom,//9-
							    'imagenp'=>$imagen//10-
							);
							if ($cantidad==1) {
								$data_prod['productos']=$array;
							}else{
								array_push($data_prod['productos'],$array);
							}
							$this->db->trans_commit();
						}

					} catch (Exception $e) {
						$this->db->trans_rollback();
						$data_prod['aviso']=false;
						$data_prod['error']=true;
						$data_prod['texto']='Problemas en try catch.'.$e->getMessage();
					}
				}
				echo json_encode($data_prod);
			}else if ($repetir==="false") {
				$id_producto=$this->input->post("idProducto_edit_dev");//
				$categoria=$this->input->post("categoria_edit_dev");//
				$estado=$this->input->post("estado_edit_dev");//
				$unidad=$this->input->post("unidad_edit_dev");//
				$linea=$this->input->post("linea_edit_dev");//
				if (!isset($_POST['usuario_edit_dev'])) {
					$usuario=$this->input->post("usuario_edit_dev2");//
				}else{
					$usuario=$this->input->post("usuario_edit_dev");//
				}
				$nombrep= $this->input->post("nombre_edit_dev");//
				$descripcion= $this->input->post("descripcion_edit_dev");//			
				$precio= $this->input->post("precio_edit_dev");	//		
				$tipo="Devolutivo";//
				$imagen=$this->input->post("imagen");
				$imagenNom=$this->input->post("imagen");
				if (empty($imagen) || $imagen==="" || $imagenNom==="sinFoto.png") {
					$imagen=null;
				}
				$placa=$this->input->post("placa_edit_dev");//		
				$serial=$this->input->post("serial_edit_dev");//			
				$csena=$this->input->post("csena_edit_dev");//
				try {
					$this->db->trans_begin(); 
					$res_prod=json_decode($this->Productos->modificarProducto($id_producto,$categoria,$estado,$unidad,$linea,$usuario,$nombrep,$descripcion,$precio,$tipo,$imagen),true);
					if ($this->db->trans_status()===FALSE) {
						// Cancelo guardado de productos
						$this->db->trans_rollback();
						echo array('aviso' => false,'error'=>true,'texto'=>'Problemas en la transaccion de productos.');
					}else{
						if ($res_prod['aviso'] || $res_prod['aviso']==false && $res_prod['error']) {
							$res_dev=json_decode($this->Devolutivos_model->setDevolutivo($id_producto,$placa,$serial,$csena),true);
							if ($this->db->trans_status()===FALSE) {
								// Cancelo guardado de devolutivos y productos
								$this->db->trans_rollback();

								echo array('aviso' => false,'error'=>true, 'texto'=>'Problemas en la transaccion de devolutivos.');
							}else{
								if ($res_dev['aviso'] || $res_dev['aviso']==false && $res_dev['error']) {
									if ($res_dev['aviso']==false && $res_dev['error'] && $res_prod['aviso']==false && $res_prod['error']) {
										$this->db->trans_rollback();
										echo json_encode($res_dev);
									}else{
										$data=array(
												'aviso'=>true,
												'repetir'=>false,
												'texto'=> 'Se modificó el devolutivo correctamente',
												'id_producto'=> $id_producto,
												'categoria_producto'=>$categoria,
											    'estado_producto'=> $estado,
											    'unidad_medida'=> $unidad,
											    'linea_producto'=> $linea,
											    'usuario_producto'=> $usuario,
											    'nombre_producto'=> $nombrep,
											    'descripcion_producto'=> $descripcion,
											    'precio_producto'=> $precio,
											    'tipo_producto'=> $tipo,
											    'imagen'=> $imagenNom,
											    'placa'=>$placa,
											    'codigo_sena'=>$csena,
											    'serial'=>$serial,
											    'imagenp'=>$imagen,
											);									
											$this->db->trans_commit();							
										echo json_encode($data);
									}
								}else{
									echo json_encode($res_dev);
									$this->db->trans_rollback();
								}								
							}
						}else{
							echo json_encode($res_prod);
							$this->db->trans_rollback();
						}					
					}
				} catch (Exception $e) {
					echo array('aviso' => false,'error'=>true,'texto'=>'Problemas en try catch.'.$e->getMessage());
				}
				unset($data_prod,$cantidad,$categoria,$estado,$unidad,$linea,$usuario,$nombrep,$descripcion,$precio,$tipo,$placa,$serial,$csena,$imagen);
			}
		}else{
			echo "No llegan post";
		}
	}

	public function consultarMantenimientos(){
		if (!empty($_POST)) {
			$id_devolutivo=$this->input->post('id_devolutivo');
			echo $this->Devolutivos_model->getMantenimientosDevolutivo($id_devolutivo);
		}else{
			echo json_encode(array("aviso"=>false,"error"=>false,"texto"=>"No llegó el id"));
		}
	}
	public function agregarMantenimientoDevolutivo(){
		if (!empty($_POST)) {
			$inicial=$this->input->post('fecha_inicial');
			$actual=$this->input->post('fecha_actual');
			if ($this->input->post('opt_man')==="true") {
				$final=$this->input->post('fecha_final');
			}else{
				$final=$inicial;
			}
			$mantenimiento=$this->input->post('mantenimiento');
			$idDevolutivo=$this->input->post('devolutivo');
			if ($this->input->post('estado')==="Vigente" || $this->input->post('estado')==="En proceso") {
				$fecha1=new DateTime($inicial);
				$fecha2=new DateTime($actual);
				$diferencia=$fecha2->diff($fecha1);
				if ($diferencia->invert==1) {
					if ($this->input->post('estado')==="En proceso" || $this->input->post('opt_man')==="true") {
						$fecha3=new DateTime($final);
						$diferencia=$fecha2->diff($fecha3);
						if ($diferencia->invert==1) {
							$estado="Expirado";
						}else{
							if ($diferencia->days==0) {
								$estado="Ahora";
							}else{
								if ($this->input->post('estado')==="Vigente") {
									$estado="Expirado";
								}else{
									$estado="En proceso";
								}
							}
						}
					}else{
						$estado="Expirado";
					}
				}else{
					if ($diferencia->days==0) {
						$estado="Ahora";
					}else{
						$estado="Vigente";
					}
				}				
			}else{
				$estado=$this->input->post('estado');
			}
			$this->db->trans_begin();
			$json=$this->Devolutivos_model->agregarMantenimientoDevolutivo($idDevolutivo,$inicial,$final,$mantenimiento,$estado);
			if ($this->db->trans_status()===FALSE){
				$this->db->$this->db->trans_rollback();
			}else{
				$this->db->trans_commit();	
				echo $json;
			}
		}
	}
	public function editarMantenimientoDevolutivo(){
		if (!empty($_POST)) {
			$inicial=$this->input->post('fecha_inicial');
			$actual=$this->input->post('fecha_actual');
			if ($this->input->post('opt_man')==="true") {
				$final=$this->input->post('fecha_final');
			}else{
				$final=$inicial;
			}
			$registrado=$this->input->post('registrado');
			$mantenimiento=$this->input->post('mantenimiento');
			$idDevolutivo=$this->input->post('devolutivo');
			if ($this->input->post('estado')==="Vigente" || $this->input->post('estado')==="En proceso") {
				$fecha1=new DateTime($inicial);
				$fecha2=new DateTime($actual);
				$diferencia=$fecha2->diff($fecha1);
				if ($diferencia->invert==1) {
					if ($this->input->post('estado')==="En proceso" || $this->input->post('opt_man')==="true") {
						$fecha3=new DateTime($final);
						$diferencia=$fecha2->diff($fecha3);
						if ($diferencia->invert==1) {
							$estado="Expirado";
						}else{
							if ($diferencia->days==0) {
								$estado="Ahora";
							}else{
								if ($this->input->post('estado')==="Vigente") {
									$estado="Expirado";
								}else{
									$estado="En proceso";
								}
							}
						}
					}else{
						$estado="Expirado";
					}
				}else{
					if ($diferencia->days==0) {
						$estado="Ahora";
					}else{
						$estado="Vigente";
					}
				}				
			}else{
				$estado=$this->input->post('estado');
			}
			$this->db->trans_begin();
			$json=$this->Devolutivos_model->editarMantenimientoDevolutivo($registrado,$idDevolutivo,$inicial,$final,$mantenimiento,$estado);
			if ($this->db->trans_status()===FALSE){
				$this->db->$this->db->trans_rollback();
			}else{
				$this->db->trans_commit();	
				echo $json;
			}
		}
	}
	public function anularMantenimiento(){
		if (!empty($_POST)) {
			$idDevolutivo=$this->input->post('devolutivo');
			$registrado=$this->input->post('registrado');
			$this->db->trans_begin();
			$json=$this->Devolutivos_model->anularMantenimientoDevolutivo($registrado,$idDevolutivo);
			if ($this->db->trans_status()===FALSE){
				$this->db->$this->db->trans_rollback();
			}else{
				$this->db->trans_commit();	
				echo $json;
			}
		}	
	}
	public function consultarUrlsProveedores(){
		$id_devolutivo=$this->input->post('idDevolutivo');
		echo $this->Devolutivos_model->getUrlsProveedores($id_devolutivo);	
	}
}

