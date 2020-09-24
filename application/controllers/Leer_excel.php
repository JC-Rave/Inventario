<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;
class Leer_excel extends CI_Controller {

	public function __construct(){
		parent::__construct();		
		//cargo el modelo necesario

	}

	public function index(){
				
	}
	public function subirArchivo(){
		ini_set('max_execution_time', 300); //300 seconds = 5 minutes
		set_time_limit(300);
		$this->load->model(array('Devolutivos_model','Lineas_model','Unidadmedida','Usuarios','Estados_productos','Productos'));	
		$this->load->library('CompareText');	
		$config['upload_path']='./assets/files/archivos';
		$config['file_name']='excel.xlsx';
		$config['allowed_types']='*';

		$this->load->library('upload',$config);

		if (!$this->upload->do_upload('subir_excel')) {
			$data['errorSubida']=$this->upload->display_errors();
			echo $this->upload->display_errors();
		}else{
			$data['uploadSuccess']=$this->upload->data();
		}
		// Obtengo archivo y lo cargo para empezar a leerlo
		$inputFileName = './assets/files/archivos/'.$data['uploadSuccess']['file_name'];
		$inputFileType =IOFactory::identify($inputFileName);
		$reader =IOFactory::createReader($inputFileType);
		$spreadsheet = $reader->load($inputFileName);
		// Obtengo numero de hojas
		$hojas=$spreadsheet->getSheetCount();
		$devolutivos=json_decode($this->Devolutivos_model->getDevolutivos(),true);
		$lineas=json_decode($this->Lineas_model->getLineas(),true);
		$unidades=json_decode($this->Unidadmedida->consultarUnidades(),true);
		$usuarios=json_decode($this->Usuarios->getUsuariosActivos(true),true);
		$estados=json_decode($this->Estados_productos->getEstados(),true);
		$usuario=$this->session->documento;
		$unidad=null;
		for ($i=0; $i < count($unidades)-2; $i++) { 
			if ($this->comparetext->icmp("unidad",$unidades[$i]['nombre_unidad'])==0) {
				$unidad=$unidades[$i]['id_unidad'];
			}
		}
		$tipo="Devolutivo";
		$estado=null;
		$nombre="";
		$linea=null;
		$textDescripcion="";
		$serial="";
		$placa="";
		$codigoSena="";
		$precio=0;
		$categoria=null;
		$imagen=null;
		$data_prod['aviso']=false;
		$data_prod['error']=false;
		$data_prod['texto']='';
		try {
			$this->db->trans_begin(); 	
			for ($i=0; $i < $hojas; $i++) {
				$hoja=$spreadsheet->getSheet($i);
				// Filas
				$colNombre="";
				$colLinea="";
				$colDescripcion1="";
				$colDescripcion2="";
				$colSerial="";
				$colPlaca="";
				$colCodigo="";
				$colPrecio="";
				$colUsuario="";
				$colEstado="";
				global $boolNombre;
				global $boolLinea;
				global $boolDescripcion1;
				global $boolDescripcion2;
				global $boolSerial;
				global $boolPlaca;
				global $boolCodigo;
				global $boolPrecio;
				global $boolUsuario;
				global $boolEstado;
				$boolNombre=true;
				$boolLinea=true;
				$boolDescripcion1=true;
				$boolDescripcion2=true;
				$boolSerial=true;
				$boolPlaca=true;
				$boolCodigo=true;
				$boolPrecio=true;
				$boolUsuario=true;
				$boolEstado=true;
				foreach ($hoja->getRowIterator() as $fila) {
					// Columnas
					foreach ($fila->getCellIterator() as $celda) {
						$valor= $celda->getValue();
						$row=$celda->getRow();
						$col=$celda->getColumn();
						if (!empty($valor) && $valor!=="") {
							if ($boolNombre==false && !empty($colNombre) && $colNombre!=="" && $colNombre===$col) {
								$nombre=$valor;
							}
							if ($boolLinea==false && $colLinea===$col) {
								for ($j=0; $j < count($lineas)-2; $j++) { 
									if ($this->comparetext->icmp($valor,$lineas[$j]['nombre_linea'])==0) {
										$linea=	$lineas[$j]['id_linea'];
										$j=count($lineas);
									}
								}
							}
							if ($boolDescripcion1==false && $colDescripcion1===$col) {
								$textDescripcion=$valor;	
							}
							if ($boolDescripcion2==false && $colDescripcion2===$col) {
								$textDescripcion.=" ".$valor;
							}
							if ($boolSerial==false && $colSerial===$col) {
								$serial=$valor;		
							}
							if ($boolPlaca==false && $colPlaca===$col) {
								$placa=$valor;	
							}
							if ($boolCodigo==false && $colCodigo===$col) {
								$codigoSena=$valor;
							}
							if ($boolPrecio==false && $colPrecio===$col) {
								$precio=str_replace("$","",$valor);
							}
							if ($boolUsuario==false && $colUsuario===$col) {
								for ($j=0; $j < count($usuarios)-2; $j++) { 
									if ($this->comparetext->icmp($valor,$usuarios[$j]['nombre_persona']." ".$usuarios[$j]['apellido_persona'])==0) {
										$usaurio=$usuarios[$j]['id_usuario'];
										$j=count($usuarios);
									}
								}
							}
							if ($boolEstado==false && $colEstado===$col) {
								for ($j=0; $j < count($estados)-2; $j++) { 
									if ($this->comparetext->icmp(str_replace(",","",$valor),str_replace(",","",$estados[$j]['descripcion_estado']))==0) {
										$estado=$estados[$j]['id_estado'];
										$j=count($estados);
									}
								}
							}
							if ($boolNombre==true && $this->comparetext->icmp($valor,"Nombre del Equipo o instrumento")==0) {
								$colNombre=$col;		
								$boolNombre=false;
							}
							if ($boolLinea==true && $this->comparetext->icmp($valor,"Nombre del Laboratorio donde se encuentra el equipo")==0) {
								$colLinea=$col;		
								$boolLinea=false;
							}
							if ($boolDescripcion1==true && $this->comparetext->icmp($valor,"Especificaciones técnicas del equipo o instrumento")==0) {
								$colDescripcion1=$col;		
								$boolDescripcion1=false;
							}
							if ($boolDescripcion2==true && $this->comparetext->icmp($valor,"Descripción del Equipo (Detalle cual es la función del equipo en el Laboratorio, cual es su Uso)")==0) {
								$colDescripcion2=$col;		
								$boolDescripcion2=false;
							}
							if ($boolSerial==true && $this->comparetext->icmp($valor,"Serial")==0) {
								$colSerial=$col;		
								$boolSerial=false;
							}
							if ($boolPlaca==true && $this->comparetext->icmp($valor,"Placa SENA")==0) {
								$colPlaca=$col;		
								$boolPlaca=false;
							}
							if ($boolCodigo==true && $this->comparetext->icmp($valor,"Código interno")==0) {
								$colCodigo=$col;		
								$boolCodigo=false;
							}
							if ($boolPrecio==true && $this->comparetext->icmp($valor,"Valor del Equipo")==0) {
								$colPrecio=$col;		
								$boolPrecio=false;
							}
							if ($boolUsuario==true && $this->comparetext->icmp($valor,"Gestor Técnico del laboratorio o responsable del área")==0) {
								$colUsuario=$col;		
								$boolUsuario=false;
							}
							if ($boolEstado==true && $this->comparetext->icmp(str_replace(" ","",$valor),str_replace(" ","","Seleccione el ESTADO"))==0) {
								$colEstado=$col;		
								$boolEstado=false;
							}
							
						}
					}
					// var_dump($boolDescripcion1);
					if ($boolNombre==false && $boolLinea==false && $boolDescripcion1==false && $boolDescripcion2==false && $boolSerial==false && $boolPlaca==false && $boolCodigo==false && $boolPrecio==false && $boolUsuario==false && $boolEstado==false && !empty($nombre) && $nombre!=="") {
								$boolInsert=true;
								for ($j=0; $j < count($devolutivos)-2; $j++) { 
									if ($this->comparetext->icmp($placa,$devolutivos[$j]['placa'])==0 || $this->comparetext->icmp($codigoSena,$devolutivos[$j]['codigo_sena'])==0 || $this->comparetext->icmp($serial,$devolutivos[$j]['serial'])==0) {
										$boolInsert=false;
										$j=count($devolutivos);
									}
								}
								if ($boolInsert) {
									if ( empty($serial) || $this->comparetext->icmp($serial,"N/A")==0 || $this->comparetext->icmp($serial,"X")==0 || $this->comparetext->icmp($serial,".")==0) {
										$serial="";
									}
									$res_prod=json_decode($this->Productos->agregarProducto($categoria,$estado,$unidad,$linea,$usuario,$nombre,$textDescripcion,$precio,$tipo,$imagen),true);				
									//condición para agregar el producto a la tabla urls_produtos
									$this->load->model('Urls_productos');
									$proveedores=$this->Urls_productos->consultar_idProveedor($nombre);
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
										echo json_encode($data_prod);
										return;
									}else{
										if ($res_prod['aviso']) {
											$res_dev=json_decode($this->Devolutivos_model->addDevolutivo($res_prod['id_producto'],$placa,$serial,$codigoSena),true);
											if ($this->db->trans_status()===FALSE) {
												// Cancelo guardado de devolutivos y productos
												$this->db->trans_rollback();
												$data_prod['aviso']=false;
												$data_prod['error']=true;
												$data_prod['texto']='Problemas en la transaccion de devolutivos.';
												echo json_encode($data_prod);
												return;
											}else{
												if ($res_dev['aviso']) {
													$data_prod['aviso']=true;
													$data_prod['texto']='Se agregó el archivo correctamente';
													
												}else{
													echo json_encode($res_dev);
													return;
												}								
											}
										}else{
											echo json_encode($res_prod);
											return;
										}					
									}
									
								}else{

								}
							}
				}
			}
			if ($i>=$hojas) {
				if ($this->db->trans_status()===FALSE) {
				// Cancelo guardado de productos
					$this->db->trans_rollback();
					$data_prod['aviso']=false;
					$data_prod['error']=true;
					$data_prod['texto']='Problemas en la transaccion de productos.';
					echo json_encode($data_prod);
					return;
				}else{
					$this->db->trans_commit();
					echo json_encode($data_prod);
					return;
				}
			}
		} catch (Exception $e) {
			echo json_encode(array('aviso' => false,'error'=>true,'texto'=>'Problemas en try catch.'.$e->getMessage()));
			return;
		}		
	}

}

/* End of file Leer_excel.php */
/* Location: ./application/controllers/Leer_excel.php */