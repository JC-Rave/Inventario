<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_ajax();

		//cargo los modelos necesarios.
		$this->load->model(array('Pedidos', 'Productos', 'Devolutivos_model', 'Consumibles', 'Proveedores', 'Urls_productos', 'Detalle_pedido', 'Unidadmedida', 'Galeria_productos'));	
	}

	public function index(){
		$this->load->model('Usuarios');
		$resultado=$this->Usuarios->usuariosConPed();

		echo json_encode($resultado);
	}

	public function registrar_pedido(){
		$productos=$this->input->post('pedido');
		$estado=$this->input->post('estado');
		$codigoPed=$this->input->post('codigoPed');

		if (empty($codigoPed)) {
			if (!empty($productos) && !empty($estado)) {
				// helper quitar_ciErrors
				$recuperar=errorException_handlers('eliminar');

				try {
					$this->db->trans_begin();
					$pedido=$this->Pedidos->reg_pedido($this->session->documento, $estado['value']);

					$this->procesar_datos($productos, $pedido);
	   				
	   				//consulto si hubo errores en la transaccion
					$men[]='Error al registrar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
					$men[]='Pedido registrado con exito.';
					$resultado=$this->transStatus($men);

				} catch (Error $e){
					//cancelo los procesos generados desde que se hizo la transaccion.
			        $this->db->trans_rollback();   

			        $resultado[]=array(
			        	'res' => false,
			        	'mensaje' => 'Error: Proceso fallido. Intentelo mas tarde.'	
			        );

				}catch (Exception $e) {
					//cancelo los procesos generados desde que se hizo la transaccion.
			        $this->db->trans_rollback();   

			        $resultado[]=array(
			        	'res' => false,
			        	'mensaje' => 'Error: Proceso fallido. Intentelo mas tarde.'	
			        );
				}

				// helper quitar_ciErrors
				errorException_handlers('recuperar', $recuperar[0], $recuperar[1]);

			}else{
				$resultado[]=array(
					'res' => 'invalid',
					'pedido' => empty($productos)?'':'La tabla pedido esta vacia.',
					'estado' => empty($estado)?'':'Seleccione el estado del pedido.'
				);
			}
		}else{
			$resultado[]=array(
	        	'res' => false,
	        	'mensaje' => 'Error: Proceso fallido. Intentelo mas tarde.'	
	        );
		}

		echo json_encode($resultado);
	}

	public function editar_pedido(){
		$productos=$this->input->post('pedido');
		$estado=$this->input->post('estado');
		$codigoPed=$this->input->post('codigoPed');

		if (!empty($codigoPed)) {
			if (!is_numeric($codigoPed)) {
				$codigoPed=$this->encryption->decrypt($codigoPed);
			}

			if (!empty($productos) && !empty($estado)) {

				$recuperar=errorException_handlers('eliminar');

				try {
					$this->db->trans_begin();

					$editar_productos=[];
					$delete_urlsProductos=[];
					$new_urlsProductos=[];

					$detalle_pedido=[];
					$editar_detalleProd=[];
					$delete_productos=[];		

					$nombres_productos=[];
					$total=0;
					foreach ($productos as $producto) {
						$proveedores=[];
						for ($i=0; $i<3 ; $i++) { 
							if (!empty($producto[16+$i])) {
								$resultado=$this->Proveedores->consultar_idProveedor($producto[16+$i]);

								array_push($proveedores, $resultado[0]);
							}else{
								array_push($proveedores, '');
							}
						}

						$user_pedido=$this->Pedidos->consultar_usuarioPedido($codigoPed);
						$medidaProd=$this->Unidadmedida->consultar_idUnidad($producto[4]);
						$imagen=$producto[20]=='Seleccionar Imagen'?null:$this->Galeria_productos->consultar_idImagen($producto[20]);

						if ($producto[12]=='Pedido') {
							//existe dentro del pedido
							$existe=$this->Productos->existProducto(empty($producto[28])?$producto[2]:$producto[28], $codigoPed);

							if ($existe[0]) {
								$aux=0;
								foreach ($existe[1] as $ext) {
									$array=array(
										'id_producto' => $ext->id_producto,
										'categoria_producto' => $producto[25],
										'unidad_medida' => $producto[24]=='Devolutivo'?$medidaProd:$producto[19],
										'nombre_producto' => empty($producto[28])?$producto[2]:$producto[28],
										'descripcion_producto' => $producto[15],
										'imagenp' => $imagen
									);

									array_push($editar_productos, $array);
									array_push($delete_urlsProductos, $ext->id_producto);

									for ($i=0; $i<3; $i++) { 
										if (!empty($proveedores[$i])) {
											$array=array(
												'id_proveedor' => $proveedores[$i],
												'id_producto' => $ext->id_producto,
												'precio' => $producto[6+$i],
												'descripcion' => $producto[21+$i]
											);
											array_push($new_urlsProductos, $array);
										}
									}

									$productoPed=array(
										'producto' => $ext->id_producto,
										'cantidad' => $producto[5], 
										'precio_1' => $producto[6], 
										'precio_2' => $producto[7], 
										'precio_3' => $producto[8], 
										'total_producto' => $producto[10],
										'proveedor_1' => $proveedores[0],
										'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
										'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
										'descripcion' => $producto[1], 
									);

									array_push($editar_detalleProd, $productoPed);
									$aux++;
								}

								$aux2=(int)$producto[5]-$aux;
								if ($producto[24]=='Devolutivo' && $aux2>0) {
									$array=array(
										'categoria_producto' => $producto[25],
										'estado_producto' => null,
										'unidad_medida' => $producto[24]=='Devolutivo'?$medidaProd:$producto[19],
										'linea_producto' => null,
										'usuario_producto' => $user_pedido,
										'nombre_producto' => empty($producto[28])?$producto[2]:$producto[28],
										'descripcion_producto' => $producto[15],
										'precio_producto' => empty($producto[26])?null:$producto[26],
										'tipo_producto' => 'Pedido',
										'imagenp' => $imagen
									);

									for ($i=0; $i<$aux2; $i++) { 
										$id=$this->Productos->reg_producto($array);
										$this->Devolutivos_model->addDevolutivo($id,'','','');

										for ($i=0; $i<3 ; $i++) { 
											if (!empty($proveedores[$i])) {
												$enlace=array(
													'id_proveedor' => $proveedores[$i],
													'id_producto' => $id,
													'precio' => $producto[6+$i],
													'descripcion' => $producto[21+$i]
												);
												
												array_push($new_urlsProductos, $enlace);
											}
										}

										$productoPed=array(
											'pedido' => $codigoPed,
											'producto' => $id,
											'cantidad' => $producto[5], 
											'precio_1' => $producto[6], 
											'precio_2' => $producto[7], 
											'precio_3' => $producto[8], 
											'total_producto' => $producto[10],
											'proveedor_1' => $proveedores[0],
											'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
											'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
											'descripcion' => $producto[1], 
										);
										array_push($detalle_pedido, $productoPed);
									}

								}else if ($producto[24]=='Devolutivo' && $aux2<0) {
									for ($i=0; $i<$aux-(int)$producto[5]; $i++) { 
										array_push($delete_productos, $existe[1][$i]->id_producto);
									}
								}

							}else{
								$array=array(
									'categoria_producto' => $producto[25],
									'estado_producto' => null,
									'unidad_medida' => $producto[24]=='Devolutivo'?$medidaProd:$producto[19],
									'linea_producto' => null,
									'usuario_producto' => $user_pedido,
									'nombre_producto' => $producto[2],
									'descripcion_producto' => $producto[15],
									'precio_producto' => empty($producto[26])?null:$producto[26],
									'tipo_producto' => 'Pedido',
									'imagenp' => $imagen
								);

								$n=0;
								do {
									$id=$this->Productos->reg_producto($array);
									$producto[24]=='Devolutivo'?$this->Devolutivos_model->addDevolutivo($id,'','',''):$this->Consumibles->reg_consumible(array('id_consumible' => $id, 'cantidad_consumible' => 0));

									for ($i=0; $i<3 ; $i++) { 
										if (!empty($proveedores[$i])) {
											$enlace=array(
												'id_proveedor' => $proveedores[$i],
												'id_producto' => $id,
												'precio' => $producto[6+$i],
												'descripcion' => $producto[21+$i]
											);
											
											array_push($new_urlsProductos, $enlace);
										}
									}

									$productoPed=array(
										'pedido' => $codigoPed,
										'producto' => $id,
										'cantidad' => $producto[5], 
										'precio_1' => $producto[6], 
										'precio_2' => $producto[7], 
										'precio_3' => $producto[8], 
										'total_producto' => $producto[10],
										'proveedor_1' => $proveedores[0],
										'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
										'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
										'descripcion' => $producto[1], 
									);
									array_push($detalle_pedido, $productoPed);

									$producto[24]=='Devolutivo'?$n++:$n=(int)$producto[5];
								} while ($n<(int)$producto[5]);
							}

						}else{
							//existe dentro del pedido
							$existe=$this->Productos->existProducto($producto[2], $codigoPed);

							if ($existe[0]) {
								$aux=0;
								foreach ($existe[1] as $ext) {									
									$productoPed=array(
										'producto' => $ext->id_producto,
										'cantidad' => $producto[5], 
										'precio_1' => $producto[6], 
										'precio_2' => $producto[7], 
										'precio_3' => $producto[8], 
										'total_producto' => $producto[10],
										'proveedor_1' => $proveedores[0],
										'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
										'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
										'descripcion' => $producto[1], 
									);

									array_push($editar_detalleProd, $productoPed);
									$aux++;
								}

								$aux2=(int)$producto[5]-$aux;
								if ($producto[24]=='Devolutivo' && $aux2>0) {
									$array=array(
										'categoria_producto' => $producto[25],
										'estado_producto' => null,
										'unidad_medida' => $producto[24]=='Devolutivo'?$medidaProd:$producto[19],
										'linea_producto' => null,
										'usuario_producto' => $user_pedido,
										'nombre_producto' => empty($producto[28])?$producto[2]:$producto[28],
										'descripcion_producto' => $producto[15],
										'precio_producto' => empty($producto[26])?null:$producto[26],
										'tipo_producto' => 'Pedido',
										'imagenp' => $imagen
									);

									for ($i=0; $i<$aux2; $i++) { 
										$id=$this->Productos->reg_producto($array);
										$this->Devolutivos_model->addDevolutivo($id,'','','');

										for ($i=0; $i<3 ; $i++) { 
											if (!empty($proveedores[$i])) {
												$enlace=array(
													'id_proveedor' => $proveedores[$i],
													'id_producto' => $id,
													'precio' => $producto[6+$i],
													'descripcion' => $producto[21+$i]
												);
												
												array_push($new_urlsProductos, $enlace);
											}
										}

										$productoPed=array(
											'pedido' => $codigoPed,
											'producto' => $id,
											'cantidad' => $producto[5], 
											'precio_1' => $producto[6], 
											'precio_2' => $producto[7], 
											'precio_3' => $producto[8], 
											'total_producto' => $producto[10],
											'proveedor_1' => $proveedores[0],
											'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
											'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
											'descripcion' => $producto[1], 
										);
										array_push($detalle_pedido, $productoPed);
									}

								}else if ($producto[24]=='Devolutivo' && $aux2<0) {
									for ($i=0; $i<$aux-(int)$producto[5]; $i++) { 

										array_push($delete_productos, $existe[1][$i]->id_producto);
									}
								}

							}else{
								$existMaterial=$this->Productos->exiteMaterial($producto[2], $user_pedido);

								if ($producto[24]=='Devolutivo' || !$existMaterial[0]) {
									$devo=array(
										'categoria_producto' => $producto[25],
										'estado_producto' => null,
										'unidad_medida' => $producto[24]=='Devolutivo'?$medidaProd:$producto[19],
										'linea_producto' => null,
										'usuario_producto' => $user_pedido,
										'nombre_producto' => $producto[2],
										'descripcion_producto' => $producto[15],
										'precio_producto' => empty($producto[26])?null:$producto[26],
										'tipo_producto' => 'Pedido',
										'imagenp' => $imagen
									);

									$n=0;
									do {
										$id=$this->Productos->reg_producto($devo);
										$producto[24]=='Devolutivo'?$this->Devolutivos_model->addDevolutivo($id,'','',''):$this->Consumibles->reg_consumible(array('id_consumible' => $id, 'cantidad_consumible' => 0));

										for ($i=0; $i<3 ; $i++) { 
											if (!empty($proveedores[$i])) {
												$enlace=array(
													'id_proveedor' => $proveedores[$i],
													'id_producto' => $id,
													'precio' => $producto[6+$i],
													'descripcion' => $producto[21+$i]
												);
												
												array_push($new_urlsProductos, $enlace);
											}
										}

										$productoPed=array(
											'pedido' => $codigoPed,
											'producto' => $id,
											'cantidad' => $producto[5], 
											'precio_1' => $producto[6], 
											'precio_2' => $producto[7], 
											'precio_3' => $producto[8], 
											'total_producto' => $producto[10],
											'proveedor_1' => $proveedores[0],
											'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
											'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
											'descripcion' => $producto[1], 
										);
										array_push($detalle_pedido, $productoPed);

										$producto[24]=='Devolutivo'?$n++:$n=(int)$producto[5];
									} while ($n<(int)$producto[5]);

								}else{
									$productoPed=array(
										'pedido' => $codigoPed,
										'producto' => $existMaterial[1],
										'cantidad' => $producto[5], 
										'precio_1' => $producto[6], 
										'precio_2' => $producto[7], 
										'precio_3' => $producto[8], 
										'total_producto' => $producto[10],
										'proveedor_1' => $proveedores[0],
										'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
										'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
										'descripcion' => $producto[1], 
									);
									array_push($detalle_pedido, $productoPed);
								}
							}
						}

						$total+=(float)$producto[10];
						array_push($nombres_productos, $producto[2]);
					}

					if (!empty($editar_productos)) {
						$this->Productos->editar_productos($editar_productos);
						$this->Urls_productos->deleteDetalle($delete_urlsProductos);
					}

					if (!empty($new_urlsProductos)) {
						$this->Urls_productos->insert($new_urlsProductos);
					}

					if (!empty($detalle_pedido)) {
						$this->Detalle_pedido->insert($detalle_pedido);
					}

					$this->Detalle_pedido->editar_productos($editar_detalleProd);

					$prod_elim=$this->Productos->get_productosEliminar($nombres_productos, $codigoPed);

					foreach ($prod_elim as $codigo) {
						array_push($delete_productos, $codigo->id_producto);
					}

					if (!empty($delete_productos)) {
						$this->Productos->deleteDetalle($delete_productos);
					}

					$detalle_pedidoElim=$this->Productos->get_productosEliminar($nombres_productos, $codigoPed, true);
					$delete_productos=[];

					foreach ($detalle_pedidoElim as $codigo) {
						array_push($delete_productos, $codigo->id_producto);
					}

					if (!empty($delete_productos)) {
						$this->Detalle_pedido->deleteDetalle($delete_productos);
					}

					$this->Pedidos->editar_pedido($codigoPed, $estado['value'], $total);

					//consulto si hubo errores en la transaccion
					$men[]='Error al editar. Vuelve a intentarlo en unos minutos, si el problema persiste recargar la pagina.';
					$men[]='Pedido editado con exito.';
					$resultado=$this->transStatus($men);

					/* if ($estado['value']=='Entregado') {
					 	$consumibles=$this->Consumibles->cosultar_idConsumible($codigoPed);

					 	$devolutivos=$this->Devolutivos_model->cosultar_idDevolutivo($codigoPed);

					 	if (!empty($consumibles)) {
					 		$datos=$this->Consumibles->cosultar_idConsumible($consumibles, true);

					 		$this->Productos->editarTipoProducto($consumibles, array('tipo_producto' => 'Consumible'));
					 		$this->Consumibles->edit_cantConsumibles($datos);
					 	}

					 	if(!empty($devolutivos)){
					 		$this->Productos->editarTipoProducto($devolutivos,array('tipo_producto' => 'Devolutivo'));
					 	}
					 }
				 	*/
				 
				} catch (Error $e){
					//cancelo los procesos generados desde que se hizo la transaccion.
			        $this->db->trans_rollback();   

			        $resultado[]=array(
			        	'res' => false,
			        	'mensaje' => 'Error:'.$e->getMessage().' Intentelo mas tarde.'	
			        );

				} catch (Exception $e) {
					//cancelo los procesos generados desde que se hizo la transaccion.
			        $this->db->trans_rollback();   

			        $resultado[]=array(
			        	'res' => false,
			        	'mensaje' => 'Error: Proceso fallido. Intentelo mas tarde.'	
			        );
				}

				// helper quitar_ciErrors
				errorException_handlers('recuperar', $recuperar[0], $recuperar[1]);

			}else{
				$resultado[]=array(
					'res' => 'invalid',
					'pedido' => empty($productos)?'':'La tabla pedido esta vacia.',
					'estado' => empty($estado)?'':'Seleccione el estado del pedido.'
				);
			}

		}else{
			$resultado[]=array(
	        	'res' => false,
	        	'mensaje' => 'Error: Proceso fallido. Intentelo mas tarde.'	
	        );
		}

		echo json_encode($resultado);
	}

	public function procesar_datos($productos, $pedido){
		$datos=[];
		$detalle_pedido=[];
		$total=0;
		foreach ($productos as $producto) {
			$proveedores=[];
			for ($i=0; $i<3 ; $i++) { 
				if (!empty($producto[16+$i])) {
					$resultado=$this->Proveedores->consultar_idProveedor($producto[16+$i]);

					array_push($proveedores, $resultado[0]);
				}else{
					array_push($proveedores, '');
				}
			}


			$existMaterial=$this->Productos->exiteMaterial($producto[2]);
			$medidaProd=$this->Unidadmedida->consultar_idUnidad($producto[4]);
			$imagen=$producto[20]=='Seleccionar Imagen'?null:$this->Galeria_productos->consultar_idImagen($producto[20]);

			if ($producto[24]=='Devolutivo' || !$existMaterial[0]) {
				$devo=array(
					'categoria_producto' => $producto[25],
					'estado_producto' => null,
					'unidad_medida' => $producto[24]=='Devolutivo'?$medidaProd:$producto[19],
					'linea_producto' => null,
					'usuario_producto' => $this->session->documento,
					'nombre_producto' => $producto[2],
					'descripcion_producto' => $producto[15],
					'precio_producto' => empty($producto[26])?null:$producto[26],
					'tipo_producto' => 'Pedido',
					'imagenp' => $imagen
				);

				$n=0;
				do {
					$id=$this->Productos->reg_producto($devo);
					$producto[24]=='Devolutivo'?$this->Devolutivos_model->addDevolutivo($id,'','',''):$this->Consumibles->reg_consumible(array('id_consumible' => $id, 'cantidad_consumible' => 0));

					for ($i=0; $i<3 ; $i++) { 
						if (!empty($proveedores[$i])) {
							$enlace=array(
								'id_proveedor' => $proveedores[$i],
								'id_producto' => $id,
								'precio' => $producto[6+$i],
								'descripcion' => $producto[21+$i]
							);
							
							array_push($datos, $enlace);
						}
					}

					$productoPed=array(
						'pedido' => $pedido,
						'producto' => $id,
						'cantidad' => $producto[5], 
						'precio_1' => $producto[6], 
						'precio_2' => $producto[7], 
						'precio_3' => $producto[8], 
						'total_producto' => $producto[10],
						'proveedor_1' => $proveedores[0],
						'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
						'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
						'descripcion' => $producto[1], 
					);
					array_push($detalle_pedido, $productoPed);

					$producto[24]=='Devolutivo'?$n++:$n=(int)$producto[5];
				} while ($n<(int)$producto[5]);

			}else{
				$productoPed=array(
					'pedido' => $pedido,
					'producto' => $existMaterial[1],
					'cantidad' => $producto[5], 
					'precio_1' => $producto[6], 
					'precio_2' => $producto[7], 
					'precio_3' => $producto[8], 
					'total_producto' => $producto[10],
					'proveedor_1' => $proveedores[0],
					'proveedor_2' => $proveedores[1]==''?null:$proveedores[1],
					'proveedor_3' => $proveedores[2]==''?null:$proveedores[2],
					'descripcion' => $producto[1], 
				);
				array_push($detalle_pedido, $productoPed);
			}

			$total+=(float)$producto[10];
		}

		if (!empty($datos)) {
			$this->Urls_productos->insert($datos);
		}
		$this->Detalle_pedido->insert($detalle_pedido);

		$datos = array(
	    	'modificar' => array(
				'total' => $total
			),
			$pedido
		);
		$this->Pedidos->update($datos);
	}

	public function detallePedido(){
		$pedido=$this->input->post('detallePedido');
		$resultado=$this->Detalle_pedido->consultar_productos($pedido);

		$detallePedido=[];
		foreach ($resultado as $producto) {
			$n=1;
			$promedio=(int)$producto->precio_1;
			if ($producto->precio_2!='0.00') {
				$n++;
				$promedio+=(int)$producto->precio_2;
			}else{
				$producto->precio_2='';
			}

			if ($producto->precio_3!='0.00') {
				$n++;
				$promedio+=(int)$producto->precio_3;				
			}else{
				$producto->precio_3='';
			}

			$promedio=number_format($promedio/$n, 2, '.', '');
			$total=number_format($promedio*(int)$producto->cantidad, 2, '.', '');

			$n=2;
			$observacion='<b class="font-weight-bold">Empresa 1:</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_1.'">'.wordwrap($producto->url_1, 125, '<br/>', true).'</a>';

			if (!empty($producto->url_2)) {
				$observacion.='<br/><br/><b class="font-weight-bold">Empresa '.$n.':</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_2.'">'.wordwrap($producto->url_2, 125, '<br/>', true).'</a>';
				$n++;
			}

			if (!empty($producto->url_3)) {
				$observacion.='<br/><br/><b class="font-weight-bold">Empresa '.$n.':</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_3.'">'.wordwrap($producto->url_3, 125, '<br/>', true).'</a>';
			}

			$fila=array(
				'descripcion' => $producto->descripcion,
				'imagen' => $producto->imagen,
				'nombre_unidad' => $producto->nombre_unidad,
				'cantidad' => $producto->cantidad,
				'precio_1' => $producto->precio_1,
				'precio_2' => $producto->precio_2,
				'precio_3' => $producto->precio_3,
				'promedio' => $promedio,
				'total' => $total,
				'observacion' => $observacion,
			);

			array_push($detallePedido, $fila);
		}

		echo json_encode($detallePedido);
	}

	public function consultar_detalle(){
		$pedido=$this->input->post('pedido');

		$resultado=[];
		$productos=$this->Detalle_pedido->detallePedido($pedido);
	    foreach ($productos as $producto):
	      $n=1;
	      $promedio=(int)$producto->precio_1;

	      if ($producto->precio_2!='0.00') {
	        $n++;
	        $promedio+=(int)$producto->precio_2;
	      }else{
	        $producto->precio_2='';
	      }

	      if ($producto->precio_3!='0.00') {
	        $n++;
	        $promedio+=(int)$producto->precio_3;        
	      }else{
	        $producto->precio_3='';
	      }

	      $promedio=number_format($promedio/$n, 2, '.', '');
	      $total=$promedio*(int)$producto->cantidad;
	      $total=number_format($total, 2, '.', '');

	      $n=2;
	      $observacion='<b class="font-weight-bold">Empresa 1:</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_1.'">'.wordwrap($producto->url_1, 125, '<br/>', true).'</a>';

	      if (!empty($producto->url_2)) {
	        $observacion.='<br/><br/><b class="font-weight-bold">Empresa '.$n.':</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_2.'">'.wordwrap($producto->url_2, 125, '<br/>', true).'</a>';
	        $n++;
	      }

	      if (!empty($producto->url_3)) {
	        $observacion.='<br/><br/><b class="font-weight-bold">Empresa '.$n.':</b><br/><a target="_black" style="color: #3B89EA" href="'.$producto->url_3.'">'.wordwrap($producto->url_3, 125, '<br/>', true).'</a>';
	      }

	      $producto->imagen=$producto->imagen!=null?base_url('assets/files/'.$producto->imagen):base_url('assets/img/sinFoto.png');
	      $producto->nombre=$producto->nombre!=null?$producto->nombre:'Seleccionar Imagen';

	      empty($producto->insertar)?$producto->insertar='Devolutivo':$producto->insertar='Consumible';

	      $fila=array(
	      	'descripcion' => $producto->descripcion,
	      	'nombre_producto' => $producto->nombre_producto,
	      	'imagen' => $producto->imagen,
	      	'nombre_unidad' => $producto->nombre_unidad,
	      	'cantidad' => $producto->cantidad,
	      	'precio_1' => $producto->precio_1,
	      	'precio_2' => $producto->precio_2,
	      	'precio_3' => $producto->precio_3,
	      	'promedio' => $promedio,
	      	'total' => $total,
	      	'tipo' => $producto->tipo,
	      	'cantidad_actual' => $producto->cantidad_actual,
	      	'nombre_categoria' => $producto->nombre_categoria,
	      	'descripcion_producto' => wordwrap($producto->descripcion_producto, 60, '<br/>', false),
	      	'nit_1' => $producto->nit_1,
	      	'nit_2' => $producto->nit_2,
	      	'nit_3' => $producto->nit_3,
	      	'id_unidad' => $producto->id_unidad,
	      	'nombre' => $producto->nombre,
	      	'descripcion_1' => $producto->descripcion_1,
	      	'descripcion_2' => $producto->descripcion_2,
	      	'descripcion_3' => $producto->descripcion_3,
	      	'insertar' => $producto->insertar,
	      	'id_categoria' => $producto->id_categoria,
	      	'precio_producto' => $producto->precio_producto,
	      	'observacion' => $observacion
	      );

	      array_push($resultado, $fila);
	    endforeach; 

	    echo json_encode($resultado);
	}
	
	public function generar_select(){
		$nombre=$this->input->post('producto');
		$resultado=$this->Proveedores->query_proveedores($nombre);
		echo json_encode($resultado);
	}

	public function transStatus($mensaje){
		if (!$this->db->trans_status()){      
	        //cancelo los procesos generados desde que se hizo la transaccion.
	        $this->db->trans_rollback();   

	        $resultado[]=array(
	        	'res' => false,
	        	'mensaje' => $mensaje[0]
	        );  
	    }else{      
	        //guardo los procesos generados desde que se hizo la transaccion. 
	        $this->db->trans_commit();    
	        
	        $resultado[]=array(
	        	'res' => true,
	        	'mensaje' => $mensaje[1]
	        );    
	    }

	    return $resultado;
	}
}

/* End of file Pedidos_controller.php */
/* Location: ./application/controllers/Pedidos_controller.php */