<?php
require_once ('personas_tab/dao_personas.php');
require_once('adebug.php');

class ci_personas_tab extends aprender_ci
{
  protected $s__datos;

  //-----------------------------------------------------------------------------------
  //---- Eventos ----------------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function evt__nuevo()
  {
    $this->cn()->reiniciar_persona();
    $this->set_pantalla('pant_edicion');
  }

  function evt__cancelar()
  {
    unset($this->s__datos);
    $this->dep('ci_modificarpersona_tab')->disparar_limpieza_memoria();
    $this->cn()->reiniciar_persona();
    $this->set_pantalla('pant_inicial');
  }

  function evt__procesar()
  {
    try {
      $this->cn()->guardar_persona();
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
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuración del cuadro previo a la generación de la salida
	 * El formato de carga es de tipo recordset: array( array('columna' => valor, ...), ...)
	 */
	function conf__cuadro(aprender_ei_cuadro $cuadro)
	{
    $datos = dao_personas::get_personas();
    $cuadro->set_datos($datos);
	}

  /**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__seleccion($seleccion)
	{
    $this->cn()->cargar_persona($seleccion);
    $this->cn()->set_cursor_persona($seleccion);
    $this->set_pantalla('pant_edicion');
	}




}
?>
