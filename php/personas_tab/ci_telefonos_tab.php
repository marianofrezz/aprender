<?php
require_once('adebug.php');

class ci_telefonos_tab extends aprender_ci
{

  protected $sql_state;
  protected $s__datos_telefono;

  //-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__cancelar()
	{
		$this->borrar_memoria();
		unset($this->s__datos_telefono['form_telefono']);
		unset($this->s__datos_telefono['form_ml_telefono']);
		unset($this->s__datos_telefono);
		$this->set_pantalla('pant_ml_tel');
	}

	function evt__procesar()
	{
		try {
			$this->evt__cancelar();

		} catch (toba_error_db $e) {
			if (adebug::$debug) {
				throw $e;
			} else {
				$this->cn()->reiniciar_persona();
        toba::notificacion()->agregar('No se guardó. Intente neuvamente mas tarde', 'error');
			}
		}
	}

  //-----------------------------------------------------------------------------------
	//---- form_ml_telefono -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_telefonos($form_ml)
	{
			if ($this->cn()->hay_cursor_persona()) {
				$datos = $this->cn()->get_telefonos();
				$this->s__datos_telefono['form_ml_telefonos'] = $datos;
				$form_ml->set_datos($datos);
			}
	}

	function evt__form_ml_detalle__modificacion($datos)
	{
		$this->s__datos_telefono['form__ml_detalle'] = $datos;
		$this->cn()->procesar_filas_detalle($datos);
	}

  function evt__form_ml_telefonos__ver_detalle($seleccion)
  {
    unset($this->s__datos_telefono['form_telefono']);
    $this->cn()->set_cursor_telefono($seleccion);
    $this->set_pantalla('pant_un_tel');
    $this->controlador()->controlador()->eliminar_evento('procesar');
  }

  function evt__form_ml_telefonos__registro_alta($datos, $id_fila)
  {
  $this->cn()->reiniciar_persona();
    $this->set_pantalla('pant_un_tel');
  }

  function evt__form_ml_telefonos__registro_modificacion($datos, $id_fila)
  {
    // if ($this->cn()->hay_cursor_telefono()) {
    //   $this->cn()->set_telefonos($datos);
    // } else {
    //   $this->cn()->setDatos_nuevoTelefono($datos);
    // }
    $this->s__datos_telefono['form_detalle'] = $datos;
    $this->set_pantalla('pant_un_tel');
  }

  //-----------------------------------------------------------------------------------
  //---- form_telefono ----------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function conf__form_telefono(aprender_ei_formulario $form)
  {
    if (isset($this->s__datos_telefono['form_telefono'])) {
			$form->set_datos($this->s__datos_telefono['form_telefono']);
		} else {
			if ($this->cn()->hay_cursor_telefono()) {
				$datos = $this->cn()->get_unTelefono();
				//$this->s__datos_telefono['form_telefono'] = $datos;
				$form->set_datos($datos);
			} else {
				ei_arbol('sin datos');
			}
		}
  }

  function evt__form_telefono__modificacion($datos)
  {
    $this->s__datos_telefono['form_telefono'] = $datos;
    $this->cn()->set_telefonos($datos);
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
