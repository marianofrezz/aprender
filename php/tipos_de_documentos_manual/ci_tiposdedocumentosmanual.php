<?php
require_once('tipos_de_documentos_manual/dao_tiposdedocumentosmanual.php');
require_once('adebug.php');
class ci_tiposdedocumentosmanual extends aprender_ci
{

  function conf__cuadro($cuadro)
  {
    $datos = dao_tiposdedocumentosmanual::get_datosInventado();
    $cuadro->set_datos($datos);
  }

  function conf__form($form)
  {
    if ($this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->hay_cursor()) {
      $datos = $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->get();
      $form->set_datos($datos);
    }
  }

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Atrapa la interacción del usuario a través del botón asociado. El método no recibe parámetros
	 */
	function evt__nuevo()
	{
    $this->set_pantalla('pant_edicion');
	}

	function evt__procesar()
	{
    try {
      $this->cn()->dep('dr_tiposdocumentos')->sincronizar();
      $this->cn()->dep('dr_tiposdocumentos')->resetear();
      $this->set_pantalla('pant_inicial');
    } catch (toba_error_db $e) {
      if (adebug::$debug) {
        throw $e;
      } else {
        $this->cn()->dep('dr_tiposdocumentos')->resetear();
        toba::notificacion()->agregar('No se guardó. Intente neuvamente mas tarde', 'error');
      }
    }
	}

	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuración
	 */
	function evt__form__modificacion($datos)
	{
    $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__seleccion($seleccion)
	{
    $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->cargar($seleccion);
    $id_fila = $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->get_id_fila_condicion($seleccion)[0];
    $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->set_cursor($id_fila);
    $this->set_pantalla('pant_edicion');
	}

  function evt__cuadro__borrar($seleccion)
  {
    $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->cargar($seleccion);
    $id_fila = $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->get_id_fila_condicion($seleccion)[0];
    $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->set_cursor($id_fila);
    $this->cn()->dep('dr_tiposdocumentos')->tabla('dt_tipos_documentos')->eliminar_fila($id_fila);
    $this->evt__procesar();
  }

}
?>
