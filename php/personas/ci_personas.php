<?php
require_once 'personas/dao_personas.php';
require_once('adebug.php');
class ci_personas extends aprender_ci
{
	protected $s__datos;
	protected $s__datos_filtro;
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuración del cuadro previo a la generación de la salida
	 * El formato de carga es de tipo recordset: array( array('columna' => valor, ...), ...)
	 */
	function conf__cuadro(aprender_ei_cuadro $cuadro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro = $this->dep('filtro');
			$filtro->set_datos($this->s__datos_filtro);
			$sql_where = $filtro->get_sql_where();

			$datos = dao_personas::get_personas($sql_where);
			$cuadro->set_datos($datos);
		}
	}

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__seleccion($seleccion)
	{
    $this->cn()->dep('dr_personas')->tabla('dt_personas')->cargar($seleccion);

		$id_fila = $this->cn()->dep('dr_personas')->tabla('dt_personas')->get_id_fila_condicion($seleccion)[0];
		$this->cn()->dep('dr_personas')->tabla('dt_personas')->set_cursor($id_fila);

		$this->cn()->dep('dr_personas')->tabla('dt_telefonos')->cargar();
    $this->set_pantalla('pant_edicion');
	}

	function conf__filtro($filtro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro->set_datos($this->s__datos_filtro);
		}
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__datos_filtro);
	}

	function conf__form($form)
	{
		if (isset($this->s__datos['form'])) {
			$form->set_datos($this->s__datos['form']);
		} else {
			if ($this->cn()->dep('dr_personas')->tabla('dt_personas')->hay_cursor()) {
	      $datos = $this->cn()->dep('dr_personas')->tabla('dt_personas')->get();
				$this->s__datos['form'] = $datos;
	      $form->set_datos($datos);
	    }
		}
	}

	function conf__form_ml_telefonos($form)
	{
		if (isset($this->s__datos['formmltel'])) {
			$form->set_datos($this->s__datos['formmltel']);
		} else {
			if ($this->cn()->dep('dr_personas')->tabla('dt_personas')->hay_cursor()) {
				$datos = $this->cn()->dep('dr_personas')->tabla('dt_telefonos')->get_filas();
				$this->s__datos['formmltel'] = $datos;
				$form->set_datos($datos);
			}
		}
	}

	function evt__nuevo()
	{
		$this->set_pantalla('pant_edicion');
	}

	function evt__procesar()
	{
    try {
      $this->cn()->dep('dr_personas')->sincronizar();
      $this->cn()->dep('dr_personas')->resetear();
      $this->evt__cancelar();
    } catch (toba_error_db $e) {
			$this->cn()->dep('dr_personas')->resetear();
      if (adebug::$debug) {
        throw $e;
      } else {
        toba::notificacion()->agregar('No se guardó. Intente nuevamente mas tarde', 'error');
      }
    }
	}

	function evt__cancelar()
	{
		unset($this->s__datos);
		$this->set_pantalla('pant_inicial');
	}

	function evt__form__modificacion($datos)
	{
		$this->s__datos['form'] = $datos;
		$this->cn()->dep('dr_personas')->tabla('dt_personas')->set($datos);
	}

	function evt__form_ml_telefonos__modificacion($datos)
	{
		$this->s__datos['formmltel'] = $datos;
		$this->cn()->dep('dr_personas')->tabla('dt_telefonos')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuración
	 */
	function evt__filtro__filtrar($datos)
	{
		$this->s__datos_filtro = $datos;
	}

}
?>
