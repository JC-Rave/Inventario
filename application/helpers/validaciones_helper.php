<?php 
    function rules_body($config){
        /*
            obtengo la instancia del superobjeto(codeigniter) para poder usar la librerias, ya que en los helper no puedo utilizar $this para referenciarme a codeigniter. 
            Con & obtengo la instancia origianal y evito crear una copia de la misma
        */
        $CI =& get_instance();
        
        //cargo las librerias necesarias
        $CI->load->library('form_validation');

        //cargo las validaciones ya configuradas en la libreria
        $CI->form_validation->set_rules($config);

        //confirmo errores al validar los inputs
        if (!$CI->form_validation->run()){
            //existen inputs que incumplen las reglas(validaciones) configuradas.
            return true;
        }else{
            //todos los inputs cumplen con las reglas(validaciones) configuradas.
            return false;
        }
    }

    function rules_regUsuario(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'cedula',
                'label' => 'documento',
                'rules' => 'required|is_unique[personas.documento_persona]',
                'errors' => array(
                    'required' => 'El %s contiene caracteres especiales o es vacio.',
                    'is_unique' => 'El %s ya se encuentra registrado.'
                )
            ),
            array(
                'field' => 'nombre',
                'label' => 'nombre',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'apellido',
                'label' => 'apellido',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'telefono',
                'label' => 'telefono',
                'rules' => 'required|numeric|min_length[7]|is_natural',
                'errors' => array(
                    'required' => 'El %s contiene caracteres especiales o es vacio.',
                    'numeric' => 'El %s debe ser unicamente numerico.',
                    'min_length' => 'El %s ingresado no es valido.',
                    'is_natural' => 'Ingrese el %s sin puntos ni comas.'
                )
            ),
            array(
                'field' => 'correo',
                'label' => 'correo',
                'rules' => 'required|valid_email|is_unique[usuarios.usuario]',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'valid_email' => 'Debe ingresar un %s valido.',
                    'is_unique' => 'El %s ya posee una cuenta activa en el sistema.'
                )
            ),
            array(
                'field' => 'confirmar',
                'label' => 'correo',
                'rules' => 'required|valid_email|matches[correo]',
                'errors' => array(
                    'required' => 'El %s de confimación no puede ser vacio.',
                    'valid_email' => 'Debe ingresar un %s valido.',
                    'matches' => 'Los correos no coinciden'
                )
            ),
            array(
                'field' => 'tipo_user',
                'label' => 'tipo de usuario',
                'rules' => 'required|in_list[1,2]',
                'errors' => array(
                    'required' => 'Seleccione un %s.',
                    'in_list' => 'Han modificado el codigo de forma insegura. Refresca la pagina para restaurar todo.'
                )
            ),
            array(
                'field' => 'linea',
                'label' => 'linea',
                'rules' => 'required|in_list[1,2,3,4,5,6,7]',
                'errors' => array(
                    'required' => 'Seleccione una %s.',
                    'in_list' => 'Han modificado el codigo de forma insegura refresca la pagina para restaurar todo.'
                )
            )
        );

        return rules_body($config);
    }

    function rules_editUsuario(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'nombre',
                'label' => 'nombre',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'apellido',
                'label' => 'apellido',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'telefono',
                'label' => 'telefono',
                'rules' => 'required|numeric|min_length[7]|is_natural',
                'errors' => array(
                    'required' => 'El %s contiene caracteres especiales o es vacio.',
                    'numeric' => 'El %s debe ser unicamente numerico.',
                    'min_length' => 'El %s ingresado no es valido.',
                    'is_natural' => 'Ingrese el %s sin puntos ni comas.'
                )
            ),
            array(
                'field' => 'tipo_user',
                'label' => 'tipo de usuario',
                'rules' => 'required|in_list[1,2]',
                'errors' => array(
                    'required' => 'Seleccione un %s.',
                    'in_list' => 'Ha ocurrido un error, la pagina se reiniciara.'
                )
            ),
            array(
                'field' => 'linea',
                'label' => 'linea',
                'rules' => 'required|in_list[1,2,3,4,5,6,7]',
                'errors' => array(
                    'required' => 'Seleccione una %s.',
                    'in_list' => 'Ha ocurrido un error, la pagina se reiniciara.'
                )
            ),
            array(
                'field' => 'estado',
                'label' => 'estado',
                'rules' => 'required|in_list[a,i]',
                'errors' => array(
                    'required' => 'Seleccione un %s.',
                    'in_list' => 'Ha ocurrido un error, la pagina se reiniciara.'
                )
            )
        );

        return rules_body($config);
    }

    function rules_regProveedores(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'nit',
                'label' => 'nit',
                'rules' => 'required|is_unique[proveedores.nit]',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'is_unique' => 'El %s ya se encuentra registrado.'
                )
            ),
            array(
                'field' => 'proveedor',
                'label' => 'proveedor',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres especiales.'
                )
            ),
            array(
                'field' => 'telefono',
                'label' => 'telefono',
                'rules' => 'required|numeric|min_length[7]|is_natural',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'numeric' => 'El %s debe ser unicamente numerico.',
                    'min_length' => 'El %s ingresado no es valido.',
                    'is_natural' => 'Ingrese el %s sin puntos ni comas.'
                )
            ),
            array(
                'field' => 'correo',
                'label' => 'correo',
                'rules' => 'required|valid_email|is_unique[proveedores.correo_proveedor]',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'valid_email' => 'Debe ingresar un %s valido.',
                    'is_unique' => 'El %s ya existe en el sistema.'
                )
            ),
            array(
                'field' => 'url',
                'label' => 'url',
                'rules' => 'required|valid_url|is_unique[proveedores.url]',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'valid_url' => 'Debe ingresar una %s valida.',
                    'is_unique' => 'La %s ya existe en el sistema'
                )
            )
        );

        return rules_body($config);
    }

    function rules_editProveedores(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'proveedor',
                'label' => 'proveedor',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres especiales.'
                )
            ),
            array(
                'field' => 'telefono',
                'label' => 'telefono',
                'rules' => 'required|numeric|min_length[7]|is_natural',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'numeric' => 'El %s debe ser unicamente numerico.',
                    'min_length' => 'El %s ingresado no es valido.',
                    'is_natural' => 'Ingrese el %s sin puntos ni comas.'
                )
            )
        );

        return rules_body($config);
    }

    function rules_regCuenta(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'cedula',
                'label' => 'documento',
                'rules' => 'required|is_unique[personas.documento_persona]',
                'errors' => array(
                    'required' => 'El %s contiene caracteres especiales o es vacio.',
                    'is_unique' => 'El %s ya se encuentra registrado.'
                )
            ),
            array(
                'field' => 'nombre',
                'label' => 'nombre',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'apellido',
                'label' => 'apellido',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'celular',
                'label' => 'telefono',
                'rules' => 'required|numeric|min_length[7]|is_natural',
                'errors' => array(
                    'required' => 'El %s contiene caracteres especiales o es vacio.',
                    'numeric' => 'El %s debe ser unicamente numerico.',
                    'min_length' => 'El %s ingresado no es valido.',
                    'is_natural' => 'Ingrese el %s sin puntos ni comas.'
                )
            ),
            array(
                'field' => 'correo',
                'label' => 'correo',
                'rules' => 'required|valid_email|is_unique[usuarios.usuario]',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'valid_email' => 'Debe ingresar un %s valido.',
                    'is_unique' => 'El %s ya posee una cuenta activa en el sistema.'
                )
            ),
            array(
                'field' => 'confirm_correo',
                'label' => 'correo',
                'rules' => 'required|valid_email|matches[correo]',
                'errors' => array(
                    'required' => 'El %s de confimación no puede ser vacio.',
                    'valid_email' => 'Debe ingresar un %s valido.',
                    'matches' => 'Los correos no coinciden'
                )
            ),
            array(
                'field' => 'linea',
                'label' => 'linea',
                'rules' => 'required|in_list[1,2,3,4,5,6,7]',
                'errors' => array(
                    'required' => 'Seleccione una %s.',
                    'in_list' => 'Han modificado el codigo de forma insegura refresca la pagina para restaurar todo.'
                )
            )
        );

        return rules_body($config);
    }

    function rules_editCuenta(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'nombre',
                'label' => 'nombre',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'apellido',
                'label' => 'apellido',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'telefono',
                'label' => 'telefono',
                'rules' => 'required|numeric|min_length[7]|is_natural',
                'errors' => array(
                    'required' => 'El %s contiene caracteres especiales o es vacio.',
                    'numeric' => 'El %s debe ser unicamente numerico.',
                    'min_length' => 'El %s ingresado no es valido.',
                    'is_natural' => 'Ingrese el %s sin puntos ni comas.'
                )
            ),
            array(
                'field' => 'correo',
                'label' => 'correo',
                'rules' => 'valid_email',
                'errors' => array(
                    'valid_email' => 'Debe ingresar un %s valido.'
                )
            ),
            array(
                'field' => 'confirmar_correo',
                'label' => 'correo',
                'rules' => 'required|valid_email|matches[correo]',
                'errors' => array(
                    'required' => 'El %s de confimación no puede ser vacio.',
                    'valid_email' => 'Debe ingresar un %s valido.',
                    'matches' => 'Los correos no coinciden'
                )
            )
        );

        return rules_body($config);
    }

    function rules_editPass(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'actual_pass',
                'label' => 'contraseña actual',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'La %s no puede ser vacia.'
                )
            ),
            array(
                'field' => 'new_pass',
                'label' => 'nueva contraseña',
                'rules' => 'required|min_length[8]|max_length[16]',
                'errors' => array(
                    'required' => 'La %s no puede ser vacia.',
                    'min_length' => 'La %s debe tener minimo 8 caracteres.',
                    'max_length' => 'La %s debe tener maximo 16 caracteres.'
                )
            ),
            array(
                'field' => 'confirm_new_pass',
                'label' => 'confirmar contraseña',
                'rules' => 'required|matches[new_pass]|min_length[8]|max_length[16]',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'matches' => 'La contraseña no coincide',
                    'min_length' => 'El %s debe tener minimo 8 caracteres.',
                    'max_length' => 'El %s debe tener maximo 16 caracteres.'
                )
            )
        );

        return rules_body($config);
    }

    function rules_regMaterial(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'nombre',
                'label' => 'nombre del material',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'cantidad',
                'label' => 'cantidad del material',
                'rules' => 'required|is_natural_no_zero',
                'errors' => array(
                    'required' => 'La %s no puede ser vacia.',
                    'is_natural_no_zero' => 'La %s debe ser numerico mayor a 0, sin puntos ni comas.'
                )
            ),
            array(
                'field' => 'categoria',
                'label' => 'categoria del material',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Seleccione la %s.'
                )
            ),
            array(
                'field' => 'unidad',
                'label' => 'unidad del material',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Seleccione la %s.'
                )
            ),
            array(
                'field' => 'ubicacion',
                'label' => 'ubicación del material',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Seleccione la %s.'
                )
            ),
            array(
                'field' => 'descripcion',
                'label' => 'descripcion del material',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'La %s no puede ser vacia.'
                )
            )
        );

        return rules_body($config);
    }

    function rules_editMaterial(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'edit_nombre',
                'label' => 'nombre del material',
                'rules' => 'required|alpha_numeric_spaces',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'alpha_numeric_spaces' => 'El %s contiene caracteres invalidos.'
                )
            ),
            array(
                'field' => 'edit_cantidad',
                'label' => 'cantidad del material',
                'rules' => 'required|is_natural_no_zero',
                'errors' => array(
                    'required' => 'La %s no puede ser vacia.',
                    'is_natural_no_zero' => 'La %s debe ser numerico mayor a 0, sin puntos ni comas.'
                )
            ),
            array(
                'field' => 'edit_categoria',
                'label' => 'categoria del material',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Seleccione la %s.'
                )
            ),
            array(
                'field' => 'edit_unidad',
                'label' => 'unidad del material',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Seleccione la %s.'
                )
            ),
            array(
                'field' => 'edit_ubicacion',
                'label' => 'ubicación del material',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Seleccione la %s.'
                )
            ),
            array(
                'field' => 'edit_descripcion',
                'label' => 'descripcion del material',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'La %s no puede ser vacia.'
                )
            )
        );

        return rules_body($config);
    }   

    function rules_regUnidad(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'nombre_unidad',
                'label' => 'nombre',
                'rules' => 'required|is_unique[unidad_medida.nombre_unidad]',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'is_unique' => 'El %s ya se encuentra registrado.'
                )
            )
        );

        return rules_body($config);
    }

    function rules_regImagen(){
        //genero un array con las validaciones necesarias
        $config = array(
            /*
                genero un array para cada input que va contener:
                field: nombre del input a validar.
                label: referencia de %s para utilizar en los errores (opcional) en caso de no
                    estar seleccionara a field como referencia si utiliza %s.
                rules: validaciones que requiero en el input separadas por |.
                errors: genero los errores en caso de que no cumpla alguna validacion,
                    %s se refiere a label o field (ya antes mensionado).
            */
            array(
                'field' => 'nombre',
                'label' => 'nombre',
                'rules' => 'required|is_unique[galeria_productos.nombre]',
                'errors' => array(
                    'required' => 'El %s no puede ser vacio.',
                    'is_unique' => 'El %s ya se encuentra registrado.'
                )
            )
        );

        return rules_body($config);
    }
    