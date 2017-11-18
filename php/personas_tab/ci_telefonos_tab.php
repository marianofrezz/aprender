<?php
require_once('adebug.php');
require_once('comunes/oc_form_ml.php');
class ci_telefonos_tab extends aprender_ci
{
  protected $sql_state;
  protected $s__datos;

  //-----------------------------------------------------------------------------------
  //---- setters y getters ------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  // getter form_ml_cache

  function obj_cache($nombre_ml)
  {
    if (!isset($this->s__datos[$nombre_ml])) {
      $this->s__datos[$nombre_ml] = new oc_form_ml();
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
    // $datos = $this->get_cache_form_telefono(); esta línea no hace falta aparentemente
    unset($this->s__datos['form_telefono']);
  }

  function unset_datos_form_lineas()
  {
    // $datos = $this->get_cache('form_ml_lineas'); esta línea no hace falta aparentemente
    unset($this->s__datos['form_ml_lineas']);
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
    $oc_ml_tel = $this->obj_cache('form_ml_telefonos');
    if ($oc_ml_tel->hay_pedido_registro_nuevo()) {
      $oc_ml_tel->set_pedido_registro_nuevo(false);
      if ($this->cn()->hay_cursor_telefono()) {
        if ($cancelar) {
          $this->cn()->eliminar_fila_cursor_telefono();
        } else {
          $this->cn()->resetear_cursor_telefono();
          $oc_ml_tel->unset_cache();
        }
      } else {
        toba::logger()->notice('ADVERTENCIA-ATENCION: Hay un pedido de registro nuevo pero no hay cursor seteado para el nuevo telefono. No se puede procesar el pedido de registro nuevo si no se tiene seteado el cursor en el nuevo registro. Asumimos que algo se hizo mal y por lo tanto el caché de oc ya no es confiable. Lo borramos. Ver en ci_telefonos_tab.php=>procesar_pedido_registro_nuevo_telefono('.($cancelar?'true':'false').') [ref_0x0]');
        $oc_ml_tel->unset_cache();
        $oc_ml_tel->set_cache($this->cn()->get_telefonos());
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
    $oc_ml = $this->obj_cache('form_ml_telefonos');
    $datos = $oc_ml->get_cache();

    if (!$datos) { // Si no hay datos
      if ($this->cn()->hay_cursor_persona()) {
        $datos = $this->cn()->get_telefonos();
        $oc_ml->set_cache($datos);
      }
    }
    $form_ml->set_datos($datos);
    $oc_ml->set_ml_procesado();
    $this->cn()->resetear_cursor_telefono();
  }

  function evt__form_ml_telefonos__ver_detalle($seleccion)
  {
    $datos_fila = $this->obj_cache('form_ml_telefonos')->get_cache_fila($seleccion);
    $this->set_cache_form_telefono($datos_fila);

    if ($this->cn()->existe_fila_telefono($seleccion)) {
      $this->cn()->set_cursor_telefono($seleccion);
      $datos_lineas = $this->cn()->get_lineas();
      $this->obj_cache('form_ml_lineas')->set_cache($datos_lineas);
    }

    $this->set_pantalla('pant_un_tel');
    $this->controlador()->controlador()->eliminar_evento('procesar');
  }

  function evt__form_ml_telefonos__pedido_registro_nuevo()
  {
    $this->obj_cache('form_ml_telefonos')->set_pedido_registro_nuevo(true);
    $this->unset_datos_form_telefono();
    $this->unset_datos_form_lineas();
    $this->set_pantalla('pant_un_tel');
  }

  function evt__form_ml_telefonos__modificacion($datos)
  {
    $this->cn()->procesar_filas_telefono($datos);
    $datos = $this->cn()->get_telefonos(); // Con esto se obtienen todos los registros que no son de baja
    $this->obj_cache('form_ml_telefonos')->set_cache($datos);
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
    $oc_ml_tels = $this->obj_cache('form_ml_telefonos');

    if ($oc_ml_tels->hay_pedido_registro_nuevo()) {
      if (!$this->cn()->hay_cursor_telefono()) {
        $id_interno_fila = $this->cn()->nueva_fila_telefono($datos);
        $this->cn()->set_cursor_telefono($id_interno_fila);
      } else {
        toba::logger()->notice('ADVERTENCIA-ATENCION: Hay un pedido de registro nuevo y sin embargo existe cursor seteado en el ml_telefonos. No se puede procesar el pedido de nuevo teléfono si todavía tiene seteado un cursor para modificar un registro, no se sabe si en realidad desea registrar un teléfono nuevo o si desea modificar uno existente. No se procesan los datos recibidos. Ver en ci_telefonos_tab.php=>evt__form_telefono__modificacion($datos) [ref_1x0]');
      }
    } else {
      $this->set_cache_form_telefono($datos);
      if ($oc_ml_tels->hay_cursor_oc()) {
        $id_fila = $oc_ml_tels->get_cursor_oc();
        $oc_ml_tels->set_cache_fila($id_fila, $datos);
      } else {
        toba::logger()->notice('ADVERTENCIA-ATENCION: No hay pedido de registro nuevo y tampoco existe cursor seteado en el oc_ml_tels. No se pueden procesar los datos recibidos del teléfono al no ser para registro nuevo y tampoco se sabe cuál registro existente se desea modificar. No se procesan los datos recibidos. Ver en ci_telefonos_tab.php=>evt__form_telefono__modificacion($datos) [ref_1x1]');
      }
    }
  }

	//-----------------------------------------------------------------------------------
	//---- form_ml_lineas ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_lineas(aprender_ei_formulario_ml $form_ml)
	{
    $oc_ml_lineas = $this->obj_cache('form_ml_lineas');

    $datos = $oc_ml_lineas->get_cache();
    if (!$datos) { // Si no hay datos
      if ($this->cn()->hay_cursor_telefono()) {
        $datos = $this->cn()->get_lineas();
        $oc_ml_lineas->set_cache($datos);
      }
    }
    $form_ml->set_datos($datos);
	}

	function evt__form_ml_lineas__modificacion($datos)
	{
    $this->cn()->procesar_filas_linea($datos);
    $datos = $this->cn()->get_lineas(); // Con esto se obtienen todos los registros que no son de baja
    $this->obj_cache('form_ml_lineas')->set_cache($datos);
	}

  function evt__form_ml_lineas__ver_actividad($seleccion)
  {
    $this->obj_cache('form_ml_lineas')->set_cursor_oc($seleccion);
  }

	//-----------------------------------------------------------------------------------
	//---- form_ml_actividades ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_actividades(aprender_ei_formulario_ml $form_ml)
	{
		if ($this->cn()->hay_cursor_lineas()) {
			$datos = $this->cn()->get_actividades();
		  $form_ml->set_datos($datos);
		} else {
			$form_ml->desactivar_agregado_filas();
		}
	}

	function evt__form_ml_actividades__modificacion($datos)
	{
      $this->cn()->procesar_filas_actividades($datos);
      $this->cn()->resetear_cursor_lineas();
	}

  //-----------------------------------------------------------------------------------
  //---- Configuraciones --------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function conf__pant_un_tel(toba_ei_pantalla $pantalla)
  {
    $this->controlador()->controlador()->pantalla()->eliminar_evento('procesar');
    $this->controlador()->controlador()->pantalla()->eliminar_evento('cancelar');
  }

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function post_eventos()
	{
    // Debemos usar este evento para setear el cursor del dt de cambio_lineas porque de lo contrario el cursor se setea muy temprano y los registros se vinculan incorrectamente
    $oc_frm_lineas = $this->obj_cache('form_ml_lineas');
    if ($oc_frm_lineas->hay_cursor_oc()) {
      $cursor = $oc_frm_lineas->get_cursor_oc();
      $oc_frm_lineas->unset_cursor_oc();
      $this->cn()->set_cursor_lineas($cursor);
    }
	}

	//-----------------------------------------------------------------------------------
	//---- form_ml_fotos ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_fotos(aprender_ei_formulario_ml $form_ml)
	{
      if ($this->cn()->hay_cursor_telefono()) {
        $datos = $this->cn()->get_fotos_telefonos();
        $datos = $this->cn()->get_blobs_fotos($datos);
        $form_ml->set_datos($datos);
      }
	}

	function evt__form_ml_fotos__modificacion($datos)
	{

    	$anterior = $this->obj_cache('form_ml_fotos');
    	foreach ($anterior as $keya => $valuea) {
    		foreach ($datos as $keyd => $valued) {
    			if (isset($valuea['id_fototel'])){
    				if (isset($valued['id_fototel'])){
    					if ($valuea['id_fototel']=$valued['id_fototel']){
    						if (isset($valuea['imagen']) && !isset($valued['imagen'])){
    							$datos[$keyd]['imagen'] = $valuea['imagen'];
    							$datos[$keyd]['imagen?html'] = $valuea['imagen?html'];
    							$datos[$keyd]['imagen?url'] = $valuea['imagen?url'];
    						}
    					}
    				}
    			}
    		}
    	}

    	if ($datos){
        $this->cn()->procesar_filas_fotos_telefonos($datos);
        $this->cn()->set_blobs_fotos($datos);
        $this->obj_cache('form_ml_fotos')->set_cache($datos);
    		}

    // $this->cn()->procesar_filas_fotos_telefonos($datos);
    // $this->cn()->set_blobs_fotos($datos);

    $this->cn()->resetear_cursor_telefono();

	}

}
?>
