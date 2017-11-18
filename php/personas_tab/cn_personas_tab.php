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
    return $this->dep('dr_personas')->tabla('dt_personas')->hay_cursor();
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
    return $this->dep('dr_personas')->tabla('dt_telefonos')->hay_cursor();
  }

  function resetear_cursor_telefono()
  {
    $this->dep('dr_personas')->tabla('dt_telefonos')->resetear_cursor();
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
    $array = $this->dep('dr_personas')->tabla('dt_telefonos')->get();
    return $array;
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

  function set_cursor_lineas($id_interno)
  {
    $this->dep('dr_personas')->tabla('dt_cambio_linea')->set_cursor($id_interno);
  }

  function hay_cursor_lineas()
  {
    return $this->dep('dr_personas')->tabla('dt_cambio_linea')->hay_cursor();
  }

  function resetear_cursor_lineas()
  {
    $this->dep('dr_personas')->tabla('dt_cambio_linea')->resetear_cursor();
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

  //-----------------------------------------------------------------------------------
  //---- dt_fotos_telefonos -----------------------------------------------------------
  //-----------------------------------------------------------------------------------

  function get_fotos_telefonos()
  {
    $datos = $this->dep('dr_personas')->tabla('dt_fotos_telefonos')->get_filas();
    return $datos;
  }

    function procesar_filas_fotos_telefonos($datos)
    {
      $this->dep('dr_personas')->tabla('dt_fotos_telefonos')->procesar_filas($datos);
    }

  function get_blobs_fotos($datos)
	{
		$datos_r = array();
		foreach ($datos as $key => $value) {
      if (isset($value['x_dbr_clave'])) { // En lugar de usar $key como clave vamos a user x_dbr_clave porque en el caso de fotos_telefonos $key y x_dbr_clave no coinciden
        $datos_r[$key] = $this->get_blob_fila($value, $value['x_dbr_clave']);
      } else {
        toba::logger()->notice('ADVERTENCIA-ATENCION: get_blobs_fotos no está implementado para registros sin x_dbr_clave por el momento. Ver en cn_personas_tab.php=>get_blobs_fotos() [ref_1x0]');
        $datos_r[$key] = $value;
      }
		}
		return $datos_r;
	}

	function get_blob_fila($datos, $id_fila)
	{
		$html_imagen = null;

		$imagen = $this->dep('dr_personas')->tabla('dt_fotos_telefonos')->get_blob('imagen', $id_fila);
		if (isset($imagen)) {
			$temp_nombre = md5(uniqid(time()));
			$temp_archivo = toba::proyecto()->get_www_temp($temp_nombre);
			$temp_imagen = fopen($temp_archivo['path'], 'w');
			stream_copy_to_stream($imagen, $temp_imagen);
			fclose($temp_imagen);
			//fclose($imagen); Quito esta instrucción porque da problemas al recargar el form
			$tamano = round(filesize($temp_archivo['path']) / 1024);
			$html_imagen =
				"<img width=\"24px\" src='{$temp_archivo['url']}' alt='' />";
			$datos['imagen'] = '<a href="'.$temp_archivo['url'].'" target="_newtab">'.$html_imagen.' Tama?o de archivo actual: '.$tamano.' kb</a>';
			$datos['imagen'.'?html'] = $html_imagen;
			$datos['imagen'.'?url'] = $temp_archivo['url'];
		} else {
			$datos['imagen'] = null;
		}

		return $datos;
	}

	function set_blobs_fotos($datos)
	{
		foreach ($datos as $key => $value) {
			$this->set_blob_fila($datos[$key], $key);
		}
	}

	function set_blob_fila($datos, $id_fila)
	{
		if (isset($datos['imagen'])) {
			if (is_array($datos['imagen'])) {
				$temp_archivo = $datos['imagen']['tmp_name'];
				$imagen = fopen($temp_archivo, 'rb');
				$this->dep('dr_personas')->tabla('dt_fotos_telefonos')->set_blob('imagen', $imagen, $id_fila);
			}
		}
	}
}

?>
