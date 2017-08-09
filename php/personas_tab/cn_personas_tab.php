<?php
class cn_personas_tab extends aprender_cn
{
  protected $s__datos;

	//--------------------------------------------------------------------------------------
	//---- dr_personas --------------------------------------------------------------------
	//--------------------------------------------------------------------------------------

	function reiniciar_persona()
	{
		$this->dep('dr_personas')->resetear();
	}

	function guardar_persona()
	{
		$this->dep('dr_personas')->sincronizar();
		$this->dep('dr_personas')->resetear();
	}

	function cargar_persona($seleccion)
	{
		$this->dep('dr_personas')->cargar($seleccion);
	}

  //----------------------------------------------------------------------------------------
	//---- dt_personas ----------------------------------------------------------------------
	//----------------------------------------------------------------------------------------

  function set_cursor_persona($seleccion)
	{
		$id_fila = $this->dep('dr_personas')->tabla('dt_personas')->get_id_fila_condicion($seleccion)[0];
		$this->dep('dr_personas')->tabla('dt_personas')->set_cursor($id_fila);
	}

  function hay_cursor_persona()
  {
    if ($this->dep('dr_personas')->tabla('dt_personas')->esta_cargada()) {
      return $this->dep('dr_personas')->tabla('dt_personas')->hay_cursor();
    }
  }

  function get_personas()
  {
    $datos = $this->dep('dr_personas')->tabla('dt_personas')->get();
    return $datos;
  }

  function set_personas($datos)
  {
    $this->dep('dr_personas')->tabla('dt_personas')->set($datos);
  }

  //-----------------------------------------------------------------------------------
	//---- dt_telefonos ---------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

  function get_telefonos()
  {
    $datos = $this->dep('dr_personas')->tabla('dt_telefonos')->get_filas();
    return $datos;
  }

  function procesar_filas_telefono($datos)
  {
    $this->dep('dr_personas')->tabla('dt_telefonos')->procesar_filas($datos);
  }

  function hay_cursor_telefono()
  {
    if ($this->dep('dr_personas')->tabla('dt_telefonos')->esta_cargada()) {
      return $this->dep('dr_personas')->tabla('dt_telefonos')->hay_cursor();
    }
  }

  function resetear_cursor_telefono()
  {
    return $this->dep('dr_personas')->tabla('dt_telefonos')->resetear_cursor();
  }

  function existe_fila_telefono($id_interno)
  {
    return $this->dep('dr_personas')->tabla('dt_telefonos')->existe_fila($id_interno);
  }

  function set_cursor_telefono($seleccion)
  {
    $this->dep('dr_personas')->tabla('dt_telefonos')->set_cursor($seleccion);
  }

  function eliminar_fila_cursor_telefono()
  {
    $id_interno = $this->dep('dr_personas')->tabla('dt_telefonos')->get_cursor();
    $this->dep('dr_personas')->tabla('dt_telefonos')->eliminar_fila($id_interno);
  }

  function set_telefonos($datos)
  {
    $this->dep('dr_personas')->tabla('dt_telefonos')->set($datos);
  }

  function nueva_fila_telefono($datos_fila)
  {
    return $this->dep('dr_personas')->tabla('dt_telefonos')->nueva_fila($datos_fila);
  }

  function get_unTelefono()
  {
    if ($this->dep('dr_personas')->tabla('dt_telefonos')->esta_cargada()) {
      $array = $this->dep('dr_personas')->tabla('dt_telefonos')->get();
      return $array;
    }
  }

  function setDatos_nuevoTelefono(array $datos)
  {
    $this->dep('dr_personas')->tabla('dt_telefonos')->nueva_fila($datos);
    $id_fila_condicion = $this->dep('dr_personas')->tabla('dt_telefonos')->get_filas()[0]['x_dbr_clave'];
    $this->dep('dr_personas')->tabla('dt_telefonos')->set_cursor($id_fila_condicion);
  }

  //-----------------------------------------------------------------------------------
  //---- dt_cambio_linea --------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function get_lineas()
  {
    $datos = $this->dep('dr_personas')->tabla('dt_cambio_linea')->get_filas();
    return $datos;
  }

  function procesar_filas_linea($datos)
  {
    $this->dep('dr_personas')->tabla('dt_cambio_linea')->procesar_filas($datos);
  }

  //-----------------------------------------------------------------------------------
  //---- dt_cambio_linea --------------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function get_actividades()
  {
    $datos = $this->dep('dr_personas')->tabla('dt_actividades')->get_filas();
    return $datos;
  }

  function procesar_filas_actividades($datos)
  {
    $this->dep('dr_personas')->tabla('dt_actividades')->procesar_filas($datos);
  }

  function set_cursor_actividades($seleccion)
  {
    $id_fila = $this->dep('dr_personas')->tabla('dt_actividades')->get_id_fila_condicion($seleccion)[0];
    $this->dep('dr_personas')->tabla('dt_actividades')->set_cursor($id_fila);
  }
}

?>
