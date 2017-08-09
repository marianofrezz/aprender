<?php
require_once('adebug.php');
require_once('comunes/cache_form_ml.php');
class ci_telefonos_tab extends aprender_ci
{
  //TODO: Reemplazar las referencias a getters y setters de todos los formularios ml de manera que ahora usen cache_form_ml.php
  //TODO: Corregir la lógica para que funcione al agregar, quitar y modificar líneas
  protected $sql_state;
  protected $s__datos;

  //-----------------------------------------------------------------------------------
  //---- setters y getters ------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  // getter form_ml_cache

  function get_cache($nombre_ml)
  {
    if (!isset($this->s__datos[$nombre_ml])) {
      $this->s__datos[$nombre_ml] = new cache_form_ml();
    }
    return $this->s__datos[$nombre_ml];
  }

  // setters form_telefono

  function get_cache_form_telefono()
  {
    $datos = [];
    if (isset($this->s__datos['form_telefono'])) {
      $datos = $this->s__datos['form_telefono'];
    }
    return $datos;
  }

  function set_cache_form_telefono(array $datos)
  {
    $this->s__datos['form_telefono'] = $datos;
  }

  function unset_datos_form_telefono()
  {
    $datos = $this->get_cache_form_telefono();
    unset($this->s__datos['form_telefono']);
  }

  function set_cursor_telefonos($id_fila)
  {
    $this->s__datos['form_telefono.cursor'] = $id_fila;
  }

  function unset_cursor_telefonos()
  {
    unset($this->s__datos['form_telefono.cursor']);
  }

  function get_cursor_telefonos()
  {
    return $this->s__datos['form_telefono.cursor'];
  }

  function hay_cursor_telefonos()
  {
    return isset($this->s__datos['form_telefono.cursor']);
  }

  //-----------------------------------------------------------------------------------
  //---- auxiliares -------------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function procesar_cacnelar_pedido_registro_nuevo_telefono()
  {
    $this->procesar_pedido_registro_nuevo_telefono(true);
  }

  function procesar_aceptar_pedido_registro_nuevo_telefono()
  {
    $this->procesar_pedido_registro_nuevo_telefono(false);
  }

  function procesar_pedido_registro_nuevo_telefono($cancelar=false)
  {
    $ml_tels = $this->get_cache('form_ml_telefonos');
    if ($ml_tels->hay_pedido_registro_nuevo()) {
      $ml_tels->set_pedido_registro_nuevo(false);
      if ($this->cn()->hay_cursor_telefono()) {
        if ($cancelar) {
          $this->cn()->eliminar_fila_cursor_telefono();
        } else {
          $this->cn()->resetear_cursor_telefono();
          $ml_tels->unset_cache();
        }
      }
    }
  }

  //-----------------------------------------------------------------------------------
  //---- Eventos ----------------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function evt__cancelar()
  {
    $this->borrar_memoria();
    unset($this->s__datos);
    $this->set_pantalla('pant_ml_tel');
  }

  function evt__procesar()
  {
    $this->procesar_aceptar_pedido_registro_nuevo_telefono();
    $this->set_pantalla('pant_ml_tel');
  }

  //-----------------------------------------------------------------------------------
  //---- form_ml_telefono -------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function conf__form_ml_telefonos($form_ml)
  {
    // Cancelamos el pedido de registro nuevo si todavía no fue aceptado
    // Esto es así para el caso de que el usuario:
    //   - Esté editando un telefono nuevo
    //   - Haga clic en la pestaña personas sin hacer clic en guardar
    //   - Al volver a la pestaña telefonos se debe procesar una cancelación del registro nuevo telefono
    //TODO:- El problema está en la siguiente situación:
    //     - El usuario hace clic en la pestaña personas y luego hace clic en guardar
    //     - El cn todavía cree que el usuario aceptó la nueva fila de telefono y se va a registrar en la base de datos
    $this->procesar_cacnelar_pedido_registro_nuevo_telefono();
    $cache_ml = $this->get_cache('form_ml_telefonos');
    $datos = $cache_ml->get_cache();

    if (!$datos) { // Si no hay datos
      if ($this->cn()->hay_cursor_persona()) {
        $datos = $this->cn()->get_telefonos();
        $cache_ml->set_cache($datos);
      }
    }
    $form_ml->set_datos($datos);
    $cache_ml->set_ml_procesado();
    $this->cn()->resetear_cursor_telefono();
  }

  function evt__form_ml_telefonos__ver_detalle($seleccion)
  {
    $datos_fila = $this->get_cache('form_ml_telefonos')->get_cache_fila($seleccion);
    $this->set_cache_form_telefono($datos_fila);

    if ($this->cn()->existe_fila_telefono($seleccion)) {
      $this->cn()->set_cursor_telefono($seleccion);
      $datos_lineas = $this->cn()->get_lineas();
      $this->get_cache('form_ml_lineas')->set_cache($datos_lineas);
    }

    $this->set_pantalla('pant_un_tel');
    $this->controlador()->controlador()->eliminar_evento('procesar');
  }

  function evt__form_ml_telefonos__pedido_registro_nuevo()
  {
    $this->get_cache('form_ml_telefonos')->set_pedido_registro_nuevo(true);
    $this->unset_datos_form_telefono();
    $this->set_pantalla('pant_un_tel');
  }

  function evt__form_ml_telefonos__modificacion($datos)
  {
    $this->cn()->procesar_filas_telefono($datos);
    $datos = $this->cn()->get_telefonos(); // Con esto se obtienen todos los registros que no son de baja
    $this->get_cache('form_ml_telefonos')->set_cache($datos);
  }

  //-----------------------------------------------------------------------------------
  //---- form_telefono ----------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function conf__form_telefono(aprender_ei_formulario $form)
  {
    if ($this->cn()->hay_cursor_telefono()) {
      $datos = $this->get_cache_form_telefono();
      if (!$datos) {
        $datos = $this->cn()->get_unTelefono();
      }
      $form->set_datos($datos);
    }
  }

  function evt__form_telefono__modificacion($datos)
  {
    $cache_ml_tels = $this->get_cache('form_ml_telefonos');

    if ($cache_ml_tels->hay_pedido_registro_nuevo()) {
      if (!$this->cn()->hay_cursor_telefono()) {
        $id_interno_fila = $this->cn()->nueva_fila_telefono($datos);
        $this->cn()->set_cursor_telefono($id_interno_fila);
      }
    } else {
      $this->set_cache_form_telefono($datos);
      if ($cache_ml_tels->hay_cursor_cache()) {
        $id_fila = $cache_ml_tels->get_cursor_cache();
        $cache_ml_tels->set_cache_fila($id_fila, $datos);
      }
    }
  }

	//-----------------------------------------------------------------------------------
	//---- form_ml_lineas ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_lineas(aprender_ei_formulario_ml $form_ml)
	{
    $cache_ml_lineas = $this->get_cache('form_ml_lineas');

    $datos = $cache_ml_lineas->get_cache();
    if (!$datos) { // Si no hay datos
      if ($this->cn()->hay_cursor_telefono()) {
        $datos = $this->cn()->get_lineas();
        $cache_ml_lineas->set_cache($datos);
      }
    }
    $form_ml->set_datos($datos);
	}

	function evt__form_ml_lineas__modificacion($datos)
	{
    $datos = $this->cn()->get_lineas(); // Con esto se obtienen todos los registros que no son de baja
    $this->get_cache('form_ml_lineas')->set_cache($datos);
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
    $cache_ml = $this->get_cache('form_ml_actividades');
		if ($this->get_cache('form_ml_lineas')->hay_cursor_cache()) {
			$datos = $this->cn()->get_actividades();
			$cache_ml->set_cache($datos);
		  $form_ml->set_datos($datos);
		} else {
			$form_ml->desactivar_agregado_filas();
		}
	}

	function evt__form_ml_actividades__modificacion($datos)
	{
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
