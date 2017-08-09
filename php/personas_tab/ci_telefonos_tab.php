<?php
require_once('adebug.php');

class ci_telefonos_tab extends aprender_ci
{
  protected $sql_state;
  protected $s__datos_telefono;

  //-----------------------------------------------------------------------------------
  //---- setters y getters ------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  // setters form_ml_telefono

  function set_pedidoRegistroNuevo($si=true)
  {
    $this->s__datos_telefono['pedido_nuevo?'] = !!$si;
  }

  function b_hayPeiddoRegistroNuevo()
  {
    if (isset($this->s__datos_telefono['pedido_nuevo?'])) {
      return !!$this->s__datos_telefono['pedido_nuevo?'];
    } else {
      return false;
    }
  }

  function set_cursor_ml_telefonos($id_fila)
  {
    $this->s__datos_telefono['form_ml_telefonos.cursor'] = $id_fila;
  }

  function unset_cursor_ml_telefonos()
  {
    unset($this->s__datos_telefono['form_ml_telefonos.cursor']);
  }

  function get_cursor_ml_telefonos()
  {
    return $this->s__datos_telefono['form_ml_telefonos.cursor'];
  }

  function hay_cursor_ml_telefonos()
  {
    return isset($this->s__datos_telefono['form_ml_telefonos.cursor']);
  }

  function set_ml_telefonos_procesado()
  {
    if ($this->b_hayPeiddoRegistroNuevo()) {
      // Apagamos el pedido de registro nuevo
      $this->set_pedidoRegistroNuevo(false);
    }
    if ($this->hay_cursor_ml_telefonos()) {
      // Quitamos el cursor en ml_telefonos
      $this->unset_cursor_ml_telefonos();
      $this->unset_cursor_telefonos();
      $this->unset_cursor_ml_lineas();
    }
  }

  function set_cache_form_ml_telefonos(array $datos)
  {
    $this->s__datos_telefono['form_ml_telefonos'] = $datos;
  }

  function get_cache_form_ml_telefonos()
  {
    $datos = [];
    if (isset($this->s__datos_telefono['form_ml_telefonos'])) {
      $datos = $this->s__datos_telefono['form_ml_telefonos'];
    }
    return $datos;
  }

  function set_cache_fila_form_ml_telefonos($id_fila, $datos)
  {
    $datos_ml = $this->get_cache_form_ml_telefonos();

    foreach ($datos_ml as $key => $value) {
      if (isset($value['x_dbr_clave'])) {
        if ($value['x_dbr_clave'] == $id_fila) {
          $datos_ml[$key] = array_merge($value, $datos);
        }
      }
    }

    $this->set_cache_form_ml_telefonos($datos_ml);
  }

  function get_cache_fila_form_ml_telefonos($id_fila)
  {
    $resultado = [];
    $datos = $this->get_cache_form_ml_telefonos();
    foreach ($datos as $key => $value) {
      if (isset($value['x_dbr_clave'])) {
        if ($value['x_dbr_clave'] == $id_fila) {
          $resultado = $value;
        }
      }
    }
    $this->set_cursor_ml_telefonos($id_fila);
    return $resultado;
  }

  // setters form_telefono

  function get_cache_form_telefono()
  {
    $datos = [];
    if (isset($this->s__datos_telefono['form_telefono'])) {
      $datos = $this->s__datos_telefono['form_telefono'];
    }
    return $datos;
  }

  function set_cache_form_telefono(array $datos)
  {
    $this->s__datos_telefono['form_telefono'] = $datos;
  }

  function unset_datos_form_telefono()
  {
    $datos = $this->get_cache_form_telefono();
    unset($this->s__datos_telefono['form_telefono']);
  }

  function set_cursor_telefonos($id_fila)
  {
    $this->s__datos_telefono['form_telefono.cursor'] = $id_fila;
  }

  function unset_cursor_telefonos()
  {
    unset($this->s__datos_telefono['form_telefono.cursor']);
  }

  function get_cursor_telefonos()
  {
    return $this->s__datos_telefono['form_telefono.cursor'];
  }

  function hay_cursor_telefonos()
  {
    return isset($this->s__datos_telefono['form_telefono.cursor']);
  }

  // setters form_ml_lineas

  function set_cursor_ml_lineas($id_fila)
  {
    $this->s__datos_telefono['form_ml_lineas.cursor'] = $id_fila;
  }

   function unset_cursor_ml_lineas()
  {
     unset($this->s__datos_telefono['form_ml_lineas.cursor']);
   }

   function get_cursor_ml_lineas()
  {
   return $this->s__datos_telefono['form_ml_lineas.cursor'];
   }

   function hay_cursor_ml_lineas()
   {
     return isset($this->s__datos_telefono['form_ml_lineas.cursor']);
   }


  function get_cache_form_ml_lineas()
  {
    $datos = [];
    if (isset($this->s__datos_telefono['form_ml_lineas'])) {
      $datos = $this->s__datos_telefono['form_ml_lineas'];
    }
    return $datos;
  }

  function set_cache_form_ml_lineas(array $datos)
  {
    $this->s__datos_telefono['form_ml_lineas'] = $datos;
  }

  function set_cache_fila_form_ml_lineas($id_fila, $datos)
  {
    $datos_ml = $this->get_cache_form_ml_lineas();

    foreach ($datos_ml as $key => $value) {
      if (isset($value['x_dbr_clave'])) {
        if ($value['x_dbr_clave'] == $id_fila) {
          $datos_ml[$key] = array_merge($value, $datos);
        }
      }
    }

    $this->set_cache_form_ml_lineas($datos_ml);
  }

  function get_cache_fila_form_ml_lineas($id_fila)
  {
    $resultado = [];
    $datos = $this->get_cache_form_ml_lineas();
    foreach ($datos as $key => $value) {
      if (isset($value['x_dbr_clave'])) {
        if ($value['x_dbr_clave'] == $id_fila) {
          $resultado = $value;
        }
      }
    }
    $this->set_cursor_ml_lineas($id_fila);
    return $resultado;
  }

  //-----------------------------------------------------------------------------------
  //---- Eventos ----------------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function evt__cancelar()
  {
    $this->borrar_memoria();
    unset($this->s__datos_telefono);
    $this->set_pantalla('pant_ml_tel');
  }

  function evt__procesar()
  {
    $this->set_pantalla('pant_ml_tel');
  }

  //-----------------------------------------------------------------------------------
  //---- form_ml_telefono -------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function conf__form_ml_telefonos($form_ml)
  {
    $datos = $this->get_cache_form_ml_telefonos();
    if (!$datos) { // Si no hay datos
      if ($this->cn()->hay_cursor_persona()) {
        $datos = $this->cn()->get_telefonos();
        $this->set_cache_form_ml_telefonos($datos);
      }
    }
    $form_ml->set_datos($datos);
    $this->set_ml_telefonos_procesado();
  }

  function evt__form_ml_detalle__modificacion($datos)
  {
    $this->s__datos_telefono['form__ml_detalle'] = $datos;
    $this->cn()->procesar_filas_detalle($datos);
  }

  function evt__form_ml_telefonos__ver_detalle($seleccion)
  {
    $datos_telefonos = $this->get_cache_fila_form_ml_telefonos($seleccion);
    $datos_lineas = $this->get_cache_fila_form_ml_lineas($seleccion);
    $this->set_cache_form_telefono($datos_telefonos);
    $this->set_cache_form_ml_lineas($datos_lineas);
    $this->set_pantalla('pant_un_tel');
    $this->controlador()->controlador()->eliminar_evento('procesar');
  }

  function evt__form_ml_telefonos__pedido_registro_nuevo()
  {
    $this->set_pedidoRegistroNuevo(true);
    $this->unset_datos_form_telefono();
    $this->set_pantalla('pant_un_tel');
  }

  function evt__form_ml_telefonos__modificacion($datos)
  {
    $this->cn()->procesar_filas_telefono($datos);
    $datos = $this->cn()->get_telefonos(); // Con esto se obtienen todos los registros que no son de baja
    $this->set_cache_form_ml_telefonos($datos);
  }

  //-----------------------------------------------------------------------------------
  //---- form_telefono ----------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function conf__form_telefono(aprender_ei_formulario $form)
  {
    if (!$this->b_hayPeiddoRegistroNuevo()) { // Hay pedido de registro nuevo
      $datos = $this->get_cache_form_telefono();
      $form->set_datos($datos);
    }
  }

  function evt__form_telefono__modificacion($datos)
  {
    if ($this->b_hayPeiddoRegistroNuevo()) {
      $form_ml_telefonos = $this->dep('form_ml_telefonos');
      $form_ml_telefonos->set_registro_nuevo($datos);
    } else {
      $this->set_cache_form_telefono($datos);
      if ($this->hay_cursor_ml_telefonos()) {
        $id_fila = $this->get_cursor_ml_telefonos();
        $this->set_cache_fila_form_ml_telefonos($id_fila, $datos);
      }
    }
  }

	//-----------------------------------------------------------------------------------
	//---- form_ml_lineas ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_lineas(aprender_ei_formulario_ml $form_ml)
	{
    $datos = $this->get_cache_form_ml_lineas();
    if (!$datos) { // Si no hay datos
      if ($this->cn()->hay_cursor_telefono()) {
        $datos = $this->cn()->get_lineas();
        $this->set_cache_form_ml_lineas($datos);
      }
    }
    $form_ml->set_datos($datos);
	}

	function evt__form_ml_lineas__modificacion($datos)
	{

    if ($this->b_hayPeiddoRegistroNuevo()) {
      $form_ml_lineas = $this->dep('form_ml_lineas');
      $form_ml_lineas->set_registro_nuevo($datos);
    } else {
      $this->set_cache_form_ml_lineas($datos);
      if ($this->hay_cursor_telefonos()) {
        $id_fila = $this->get_cursor_telefonos();
        $this->set_cache_fila_form_ml_lineas($id_fila, $datos);
      }
    }
	}

  function evt__form_ml_lineas__ver_actividad($seleccion)
  {
	   $this->cn()->set_cursor_actividades($seleccion);
  }

	//-----------------------------------------------------------------------------------
	//---- form_ml_actividades ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_actividades(aprender_ei_formulario_ml $form_ml)
	{
		if ($this->hay_cursor_ml_lineas()) {
			$datos = $this->cn()->get_actividades();
			$this->s__datos_telefono['form_ml_actividades'] = $datos;
		    $form_ml->set_datos($datos);
		} else {
			$form_ml->desactivar_agregado_filas();
		}
	}

	function evt__form_ml_actividades__modificacion($datos)
	{
    $this->s__datos_telefono['form_ml_actividades'] = $datos;
		$this->cn()->procesar_filas_actividades($datos);
	}

  //-----------------------------------------------------------------------------------
  //---- Configuraciones --------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function conf__pant_un_tel(toba_ei_pantalla $pantalla)
  {
    $this->controlador()->controlador()->pantalla()->eliminar_evento('procesar');
    $this->controlador()->controlador()->pantalla()->eliminar_evento('cancelar');
  }

}
?>
