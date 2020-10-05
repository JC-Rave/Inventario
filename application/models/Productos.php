<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Productos extends CI_Model {		
	public function getUltimoIdInsertado($bool){	
		if ($bool) {
			$consulta="SELECT * FROM productos ORDER BY id_producto DESC LIMIT 1";
			$datos=$this->db->query($consulta);
			if ($datos->num_rows()!=0) {
					$result=$datos->row_array();
					$res=intval($result['id_producto']);					
			}else{
				$res=0;	
			}
			return $res;
		}
	}

	public function reg_producto($datos){
		$this->db->insert('productos', $datos);

		return $this->db->insert_id();
	}

	public function consultar_productos($usuario, $tipo='materiales'){
		$subconsulta='estado_producto IN(SELECT id_estado FROM estados_productos WHERE descripcion_estado!="de baja") OR estado_producto IS NULL AND tipo_producto!="Pedido"';

		$select='nombre_producto, descripcion_producto, categoria_producto, nombre_categoria, tipo_producto, galeria_productos.nombre vImagen, galeria_productos.imagen';
		$select.=$tipo=='materiales'?', cantidad_consumible, nombre_unidad':', precio_producto';

		$this->db->distinct();
		$this->db->select($select);
		$this->db->from('productos');
		$this->db->join('categorias', 'categorias.id_categoria = productos.categoria_producto');
		$this->db->join('galeria_productos', 'galeria_productos.id_galeria =  productos.imagenp', 'left');
		$this->db->join('urls_productos', 'urls_productos.id_producto = productos.id_producto');
		$this->db->join('proveedores', 'proveedores.id_proveedor = urls_productos.id_proveedor');

		if ($tipo=='materiales') {
			$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
			$this->db->join('unidad_medida', 'unidad_medida.id_unidad = productos.unidad_medida');

		}else{
			$this->db->join('devolutivo', 'devolutivo.id_devolutivo = productos.id_producto');
		}

		$this->db->where($subconsulta);
		$this->db->where('proveedores.estado', 'a');
		if (empty($usuario)) {
			$this->db->where('usuario_producto', $this->session->documento);
		}else{
			$this->db->where('usuario_producto', $usuario);
		}

		$datos=$this->db->get();
		return $datos->result();
	}

	public function consultProdutos($valores=false, $clave=false){
		//genero una consulta para traer los datos necesaros de los productos
		if (!$valores) {
			$subconsulta='estado_producto IN(SELECT id_estado FROM estados_productos WHERE descripcion_estado!="de baja") OR estado_producto IS NULL AND tipo_producto!="Pedido"';
			$this->db->distinct();
			$this->db->select('nombre_producto, tipo_producto');
			$this->db->from('productos');
			$this->db->where($subconsulta);
			$this->db->where('usuario_producto', $this->session->documento);

			$datos=$this->db->get();

		}else if(!$clave){
			$this->db->select('id_producto, nombre_producto');
			$this->db->where_in('nombre_producto', $valores);
			$datos=$this->db->get('productos');

		}else{
			$this->db->distinct();
			$this->db->select('nombre_producto');
			$this->db->from('productos');
			$this->db->join('urls_productos', 'urls_productos.id_producto = productos.id_producto');
			$this->db->where_in('nombre_producto', $valores);
			$this->db->where('id_proveedor', $clave);
			$this->db->where('usuario_producto', $this->session->documento);
			$datos=$this->db->get();

			if (!empty($datos)) {
				$n=count($valores);
				for ($i=0; $i<$n; $i++) {
					foreach ($datos->result() as $dato) {
						if ($valores[$i]==$dato->nombre_producto) {
							unset($valores[$i]);
							break;
						}
					}
				}
				array_values($valores);
			}

			if (!empty($valores)) {
				$subconsulta='`productos`.`id_producto` NOT IN(SELECT `urls_productos`.`id_producto` FROM `urls_productos` 
					WHERE `urls_productos`.`id_proveedor`='.$clave.')';

				$this->db->select('productos.id_producto, nombre_producto');
				$this->db->from('productos');
				$this->db->where_in('nombre_producto', $valores);
				$this->db->where($subconsulta);

				$datos=$this->db->get();

			}else{
				$datos='';
			}
		}

		//retorno todos los datos como objetos
		if (empty($datos)) {
			return $datos;
		}

		return $datos->result();
	}

	public function agregarProducto($categoria,$estado,$unidad,$linea,$usuario,$nombre,$descripcion,$precio,$tipo,$imagen){
		try {
			$data = array( 						   
			    'categoria_producto'=> $categoria, 
			    'estado_producto'=>$estado,
			    'unidad_medida'=> $unidad,
			    'linea_producto'=> $linea,
			    'usuario_producto'=> $this->db->escape_str($usuario),
			    'nombre_producto'=> $this->db->escape_str($nombre),
			    'descripcion_producto'=> $descripcion,
			    'precio_producto'=> $this->db->escape_str($precio),
			    'tipo_producto'=> $this->db->escape_str($tipo),
			    'imagenp'=>$imagen
			);
			$this->db->insert('productos', $data);
			$resultado= $this->db->affected_rows();			
			if ($resultado) {				
				$array=['aviso' => true,
						'texto' => 'Se agregó el producto con éxito',
						'id_producto'=> $this->db->insert_id()
					];
				return json_encode($array);  
			}else{			
				return json_encode(array('aviso' => false,'error'=>true,'texto' => 'No se agregó  el producto :'.$resultado));
			}
		} catch (Exception $e) {
			json_encode(array('aviso' => false,'error'=>false,'texto' => 'Problemas al agregar el producto :'.$e->getMessage()));
		}
	} 
	
	public function modificarProducto($id_producto,$categoria,$estado,$unidad,$linea,$usuario,$nombre,$descripcion,$precio,$tipo,$imagen){
		try {
			$data = array( 						   
			    'categoria_producto'=> $categoria, 
			    'estado_producto'=>$estado,
			    'unidad_medida'=> $unidad,
			    'linea_producto'=> $linea,
			    'usuario_producto'=> $this->db->escape_str($usuario),
			    'nombre_producto'=> $this->db->escape_str($nombre),
			    'descripcion_producto'=>$descripcion,
			    'precio_producto'=> $this->db->escape_str($precio),
			    'tipo_producto'=> $this->db->escape_str($tipo),
			    'imagenp'=> $imagen
			);
			$this->db->where('id_producto', $id_producto);
			$this->db->update('productos', $data);			
			$resultado= $this->db->affected_rows();			
			if ($resultado) {				
				$array=['aviso' => true,
						'error'=>true,
						'texto' => ' Se modificó el producto con éxito',
					];
				return json_encode($array);  
			}else{			
				return json_encode(array('aviso' => false,'error'=>true,'texto' => ' El producto sigue igual :'.$resultado));
			}
		} catch (Exception $e) {
			json_encode(array('aviso' => false,'error'=>false,'texto' => ' Problemas al modificar el producto :'.$e->getMessage()));
		}
	}

	public function consultar_materiales(){
		$this->db->select('nombre_categoria, nombre_unidad, nombre_linea, id_usuario, nombre_persona, apellido_persona, productos.*,galeria_productos.*, cantidad_consumible,tipo_producto');
		$this->db->from('productos');
		$this->db->join('categorias', 'categorias.id_categoria = productos.categoria_producto');
		$this->db->join('unidad_medida', 'unidad_medida.id_unidad = productos.unidad_medida');
		$this->db->join('lineas', 'lineas.id_linea = productos.linea_producto', 'left');
		$this->db->join('usuarios','usuarios.id_usuario=productos.usuario_producto');
		$this->db->join('personas', 'personas.documento_persona = productos.usuario_producto');
		$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
		$this->db->join('galeria_productos', 'galeria_productos.id_galeria = productos.imagenp', 'left');
		$this->db->where('tipo_producto!=', 'Pedido');

		$datos=$this->db->get();
		return $datos->result();
	}
	
	public function editarTipoProducto($id_productos, $tipo){
		$this->db->where_in('id_producto', $id_productos);
		$this->db->update('productos', $tipo);	
	}

	public function suministra_proveedor($nit){
		$subconsulta='estado_producto IN(SELECT id_estado FROM estados_productos WHERE descripcion_estado!="de baja") OR estado_producto IS NULL AND tipo_producto!="Pedido"';

		//genero una consulta para traer los datos necesaros de los productos
		$this->db->distinct();
		$this->db->select('nombre_producto, tipo_producto, urls_productos.precio, urls_productos.descripcion');
		$this->db->from('productos');
		$this->db->join('urls_productos', 'productos.id_producto=urls_productos.id_producto');
		$this->db->join('proveedores', 'proveedores.id_proveedor=urls_productos.id_proveedor');
		$this->db->where($subconsulta);
		$this->db->where('usuario_producto', $this->session->documento);
		$this->db->where('nit', $nit);

		$datos=$this->db->get();

		//retorno todos los datos como objetos
		return $datos->result();
	}

	public function nProductosAcargo(){
		$this->db->where('usuario_producto', $this->session->documento);
		$this->db->from('productos');
		$this->db->join('devolutivo', 'devolutivo.id_devolutivo = productos.id_producto');
		$dev=$this->db->count_all_results();

		$this->db->where('usuario_producto', $this->session->documento);
		$this->db->from('productos');
		$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
		$mat=$this->db->count_all_results();

		return [$dev, $mat];
	}

	public function transferir_inventario($datos){
		$this->db->update('productos', $datos['modificar'], array('usuario_producto'=>$datos[0]));
	}	

	public function validar_unico($campo, $comparar, $clave){
		$this->db->select('id_producto');
		$this->db->from('productos');
		$this->db->where($campo, $comparar);
		$this->db->where('usuario_producto', $clave);

		return $this->db->count_all_results()>=1?true:false;
	}

	public function reg_material($datos){
		$this->db->insert('productos', $datos);

		return $this->db->insert_id();
	} 	

	public function consultar_idProducto($nombre, $usuario){
		$this->db->limit(1);
		$this->db->select('id_producto');
		$this->db->from('productos');
		$this->db->where('nombre_producto', $nombre);
		$this->db->where('usuario_producto', $usuario);
		$datos=$this->db->get()->row();

		return $datos;		
	}

	public function editar_material($datos){
		$this->db->update('productos', $datos['modificar'], array('id_producto'=>$datos[0]));

		return $this->db->affected_rows();
	}

	public function exiteMaterial($nombre){
		$this->db->select('id_producto');
		$this->db->from('productos');
		$this->db->join('consumibles', 'consumibles.id_consumible = productos.id_producto');
		$this->db->where('usuario_producto', $this->session->documento);
		$this->db->where('tipo_producto!=', 'Pedido');
		$this->db->where('nombre_producto', $nombre);
		$datos=$this->db->get()->row();

		return empty($datos)?[false, '']:[true, $datos->id_producto];
	}

	public function deleteDetalle($datos){
		$this->db->where_in('id_producto', $datos);
		$this->db->delete('productos');
	}


	public function existProducto($nombre, $cod_pedido){
		$this->db->select('id_producto');
		$this->db->from('productos');
		$this->db->join('detalle_pedido', 'detalle_pedido.producto = productos.id_producto');
		$this->db->where('pedido', $cod_pedido);
		$this->db->where('nombre_producto', $nombre);
		$datos=$this->db->get()->result();

		return empty($datos)?[false, '']:[true, $datos];
	}
}

/* End of file productos.php */
/* Location: ./application/models/productos.php */