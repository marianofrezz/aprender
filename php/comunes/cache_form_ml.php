<?php
/**
 * Esta clase nos ayuda a manejar los caché de los distintos formularios ml con mecanismo PHP Evento que tengamos definidos
 */
class cache_form_ml
{
  //-----------------------------------------------------------------------------------
	//---- variables --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	protected $s__datos = [];

  //-----------------------------------------------------------------------------------
	//---- setters y getters ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

  function set_pedido_registro_nuevo($si=true)
  {
    $this->s__datos['pedido_nuevo?'] = !!$si;
  }

  function hay_pedido_registro_nuevo()
  {
    if (isset($this->s__datos['pedido_nuevo?'])) {
      return !!$this->s__datos['pedido_nuevo?'];
    } else {
      return false;
    }
  }

  function set_cursor_cache($id_fila)
  {
    $this->s__datos['cursor'] = $id_fila;
  }

  function unset_cursor_cache()
  {
    unset($this->s__datos['cursor']);
  }

  function get_cursor_cache()
  {
    return $this->s__datos['cursor'];
  }

  function hay_cursor_cache()
  {
    return isset($this->s__datos['cursor']);
  }

  function set_ml_procesado()
  {
    if ($this->hay_pedido_registro_nuevo()) {
      // Apagamos el pedido de registro nuevo
      $this->set_pedido_registro_nuevo(false);
    }
    if ($this->hay_cursor_cache()) {
      // Quitamos el cursor del ml
      $this->unset_cursor_cache();
    }
  }

  function set_cache(array $datos)
  {
    $this->s__datos['datos'] = $datos;
  }

  function unset_cache()
  {
    unset($this->s__datos['datos']);
  }

  function get_cache()
  {
    $datos = [];
    if (isset($this->s__datos['datos'])) {
      $datos = $this->s__datos['datos'];
    }
    return $datos;
  }

  function set_cache_fila($id_fila, $datos)
  {
    $datos_ml = $this->get_cache();

    foreach ($datos_ml as $key => $value) {
      if (isset($value['x_dbr_clave'])) {
        if ($value['x_dbr_clave'] == $id_fila) {
          $datos_ml[$key] = array_merge($value, $datos);
        }
      }
    }

    $this->set_cache($datos_ml);
  }

  function get_cache_fila($id_fila)
  {
    $resultado = [];

    $datos = $this->get_cache();
    foreach ($datos as $key => $value) {
      if (isset($value['x_dbr_clave'])) {
        if ($value['x_dbr_clave'] == $id_fila) {
          $resultado = $value;
        }
      }
    }

    $this->set_cursor_cache($id_fila);

    return $resultado;
  }
}

?>
