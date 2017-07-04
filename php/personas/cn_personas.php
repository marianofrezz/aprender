<?php
class cn_personas extends aprender_cn
{
  function get_unTelefonoPersonaCargada()
  {
    $datos = [];
    if ($this->hay_cursor_dt()) {
      $hay_datos = true;
      if (!$this->hay_cursor_dt(null, 'dt_telefonos')) {
        $datos_ml = $this->get_datos_dt(true, null, null, 'dt_telefonos');
        if ($datos_ml) {
          $id_telefono = $this->extraerIdTelefono($datos_ml[0]);
          $this->set_cursor($id_telefono, null, ['dt_telefonos']);
        } else {
          $hay_datos = false;
        }
      }
      if ($hay_datos) {
        $datos = $this->get_datos_dt(false, null, null, 'dt_telefonos');
        $datos = $this->get_blob(null, 'dt_telefonos', $datos, 'imagen', $datos['x_dbr_clave']);
      }
      return $datos;
    }
    return [];
  }

  function extraerIdTelefono(array $datos)
  {
    if (count($datos) > 0) {
      return ['id_telefono' => $datos['id_telefono']];
    } else {
      return ['id_telefono' => null];
    }
  }
}
?>
