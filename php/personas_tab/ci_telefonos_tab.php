<?php
require_once('adebug.php');

class ci_telefonos_tab extends aprender_ci
{
  //TODO: Reemplazar las referencias a getters y setters de otros ml aparte de form_ml_telefonos (que ya está arreglado)
  //TODO: Corregir la lógica para que funcione al agregar, quitar y modificar líneas
  protected $sql_state;
  protected $s__datos_telefono;

  //-----------------------------------------------------------------------------------
  //---- setters y getters ------------------------------------------------------------
  //-----------------------------------------------------------------------------------

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
    $datos = $form_ml->get_cache();

    if (!$datos) { // Si no hay datos
      if ($this->cn()->hay_cursor_persona()) {
        $datos = $this->cn()->get_telefonos();
        $form_ml->set_cache($datos);
      }
    }
    $form_ml->set_datos($datos);
    $form_ml->set_ml_procesado();
  }

  function evt__form_ml_detalle__modificacion($datos)
  {
    $this->s__datos_telefono['form__ml_detalle'] = $datos;
    $this->cn()->procesar_filas_detalle($datos);
  }

  function evt__form_ml_telefonos__ver_detalle($seleccion)
  {
    $datos_fila = $this->dep('form_ml_telefonos')->get_cache_fila($seleccion);
    $this->set_cache_form_telefono($datos_fila);

    if ($this->cn()->existe_fila_telefono($seleccion)) {
      $this->cn()->set_cursor_telefono($seleccion);
      $datos_lineas = $this->cn()->get_lineas();
      $this->dep('form_ml_lineas')->set_cache($datos_lineas);
    }

    $this->set_pantalla('pant_un_tel');
    $this->controlador()->controlador()->eliminar_evento('procesar');
  }

  function evt__form_ml_telefonos__pedido_registro_nuevo()
  {
    $this->dep('form_ml_telefonos')->set_pedido_registro_nuevo(true);
    $this->unset_datos_form_telefono();
    $this->set_pantalla('pant_un_tel');
  }

  function evt__form_ml_telefonos__modificacion($datos)
  {
    $this->cn()->procesar_filas_telefono($datos);
    $datos = $this->cn()->get_telefonos(); // Con esto se obtienen todos los registros que no son de baja
    $this->dep('form_ml_telefonos')->set_cache($datos);
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
      $this->dep('form_ml_telefonos')->set_registro_nuevo($datos);
    } else {
      $this->set_cache_form_telefono($datos);
      if ($this->hay_cursor_ml_telefonos()) {
        $id_fila = $this->get_cursor_ml_telefonos();
        $this->dep('form_ml_telefonos')->set_cache_fila($id_fila, $datos);
      }
    }
  }

	//-----------------------------------------------------------------------------------
	//---- form_ml_lineas ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_lineas(aprender_ei_formulario_ml $form_ml)
	{
    $datos = $this->dep('form_ml_lineas')->get_cache();
    if (!$datos) { // Si no hay datos
      if ($this->cn()->hay_cursor_telefono()) {
        $datos = $this->cn()->get_lineas();
        $this->dep('form_ml_lineas')->set_cache($datos);
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
