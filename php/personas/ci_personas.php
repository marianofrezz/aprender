<?php
require_once 'personas/dao_personas.php';
require_once('adebug.php');
require_once('comunes/mensajes.php');
class ci_personas extends aprender_ci
{
	protected $s__datos;
	protected $s__datos_filtro;

	/**
	 * Se ejecuta al inicio de todos los request en donde participa el componente
	 */
	function ini()
 	{
 		$this->inicializarCN();
 	}

	function inicializarCN()
	{
		$this->cn()->set_nombres_defecto('dr_personas', ['dt_personas']);
	}

	//----------------------------------------------------------------------------
	//---- JAVASCRIPT ------------------------------------------------------------
	//----------------------------------------------------------------------------

	function extender_objeto_js()
	{
		parent::extender_objeto_js();
		$this->atenderMensajeExistoso(true);
	}

	function atenderMensajeExistoso()
	{
		if ($this->b_registroExitoso()) {
			echo mensajes::mensajeExitoso(true, 'unMensajeAprender celesteExitoso');
			$this->unset_registroExitoso();
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
    $this->cn()->cargar($seleccion, true, null, null, ['dt_telefonos']);
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
			if ($this->cn()->hay_cursor_dt()) {
	      $datos = $this->cn()->get_datos_dt();
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
			if ($this->cn()->hay_cursor_dt()) {
				$datos = $this->cn()->get_datos_dt(true, null, null, 'dt_telefonos');
				$datos = $this->cn()->get_blobs(null, 'dt_telefonos', $datos, 'imagen');
				$this->s__datos['formmltel'] = $datos;
				$form->set_datos($datos);
			}
		}
	}

	function conf__form_untelefono($form)
	{
		$datos = $this->get_datoCargadoUnTelefono();
		$form->set_datos($datos);
	}

	function get_datoCargadoUnTelefono()
	{
		$datos = [];
		if (isset($this->s__datos['formuntel'])) {
			$datos = $this->s__datos['formuntel'];
		} else {
			$datos = $this->cn()->get_unTelefonoPersonaCargada();
			$this->s__datos['formuntel'] = $datos;
		}
		return $datos;
	}

	function evt__nuevo()
	{
		$this->set_pantalla('pant_edicion');
	}

	function evt__procesar()
	{
		$registroExitoso = false;
    try {
      $this->cn()->guardar();
      $this->evt__cancelar();
			$registroExitoso = true;
    } catch (toba_error_db $e) {
			$this->cn()->reiniciar();
      if (adebug::$debug) {
        throw $e;
      } else {
        toba::notificacion()->agregar('No se guardó. Intente nuevamente mas tarde', 'error');
      }
    }
		if ($registroExitoso) {
			$this->set_registroExitoso();
		}
	}

	function set_registroExitoso()
	{
		$this->s__datos['msj_registroExitoso'] = true;
	}

	function unset_registroExitoso()
	{
		unset($this->s__datos['msj_registroExitoso']);
	}

	function b_registroExitoso()
	{
		return isset($this->s__datos['msj_registroExitoso']) ? $this->s__datos['msj_registroExitoso'] : false;
	}

	function evt__cancelar()
	{
		unset($this->s__datos);
		$this->cn()->reiniciar();
		$this->set_pantalla('pant_inicial');
	}

	function evt__form__modificacion($datos)
	{
		$this->s__datos['form'] = $datos;
		$this->cn()->set_datos_dt($datos);
	}

	function evt__form_untelefono__modificacion($datos)
	{
		if ($this->cn()->hay_cursor_dt()) { //Estamos editando un registro existente?
			$this->cn()->set_datos_dt($datos, /*es_ml?*/false, null, 'dt_telefonos');
			$this->cn()->set_blob_dt(null, 'dt_telefonos', $datos, 'imagen', /*es_ml?*/false);
		} else {
			$this->cn()->setDatos_nuevoTelefono($datos);
		}
		$this->s__datos['formmltel'] = $datos;
	}

	function evt__form_ml_telefonos__modificacion($datos)
	{
		$this->s__datos['formmltel'] = $datos;
		$this->cn()->set_datos_dt($datos, true, null, 'dt_telefonos');
		$this->cn()->set_blob_dt(null, 'dt_telefonos', $datos, 'imagen', /*es_ml?*/true);
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


	function ajax__get_confTiposTelefonos($id, toba_ajax_respuesta $respuesta)
	{
		$datos = dao_personas::get_confTiposTelefonos($id);
		$respuesta->set($datos);
	}

	function ajax__getLocalidades($parametros, toba_ajax_respuesta $respuesta)
	{
		$id_provincia = $parametros['id_provincia'];
		if (   ($id_provincia == '')
		    OR ($id_provincia == 'nopar')
				OR ($id_provincia == 'undefined')) {
			$datos = [];
		} else {
			$nopar = [['id_localidad' => 'nopar', 'nombre' => '--Seleccioná--'], ];
			$datos = dao_personas::get_opcionesLocalidad($id_provincia);
			$datos = array_merge($nopar, $datos);
		}
		$this->informar_a_toba_opciones_ef($datos, 'id_localidad', 'form_untelefono', 'idlocalidad_cascada_manual');
		$respuesta->set($datos);
	}

	function informar_a_toba_opciones_ef($datos, $nom_columnaId, $nom_form, $nom_ef)
	{
		$sesion = [];
		foreach ($datos as $key => $value) {
			$sesion[] = $value[$nom_columnaId];
		}
		$this->dep($nom_form)->ef($nom_ef)->guardar_dato_sesion($sesion, true);
	}
}
?>
