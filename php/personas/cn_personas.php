<?php
class cn_personas extends aprender_cn
{
  function get_unTelefonoPersonaCargada() // <- Función para obtener el registro cargado en la tabla hijo (dt_telefonos)
  {
    $datos = []; // <- Si hay un registro lo vamos a guardar en esta variable

    if ($this->hay_cursor_dt()) { // <- Hay un cursor seteado en la tabla padre

      $hay_datos = true; // <- Vamos a suponer que hay datos en la tabla
                         //    es solo una suposición, más adelante nos vamos a corregir si la supocición es incorrecta

      if (!$this->hay_cursor_dt(null, 'dt_telefonos')) { // <- dt_telefonos NO tiene cursor seteado

        $datos_ml = $this->get_datos_dt(true, null, null, 'dt_telefonos'); // Obtenemos todas las filas de dt_telefonos
        if ($datos_ml) { // <- Obtuvimos por lo menos una fila

          $id_telefono = $this->extraerIdTelefono($datos_ml[0]); // Obtenemos el id_telefono de la primer fila (fila 0), ver la función abajo
          $this->set_cursor($id_telefono, null, ['dt_telefonos']); // <-- Seteamos el cursor en dt_telefonos para la primera fila

        } else { // <- No hay ni siquiera una fila, nuestra suposición estaba mal
          $hay_datos = false; // Nos corregimos, ahora sabemos que no había ni siquiera una fila en dt_telefonos
        } // Fin if hay por lo menos una fila

      } // Fin if hay cursor dt_telefonos

      if ($hay_datos) { // <- Hay por lo menos una fila
        $datos = $this->get_datos_dt(false, null, null, 'dt_telefonos'); // Obtenemos los datos de la fila seteada
        $datos = $this->get_blob(null, 'dt_telefonos', $datos, 'imagen', $datos['x_dbr_clave']); // Obtenemos la imagen de la fila seteada
      }
    } // <- Fin if hay cursor en dt padre

    return $datos;
  }

  function extraerIdTelefono(array $datos)
  {
    if (count($datos) > 0) {
      return ['id_telefono' => $datos['id_telefono']];
    } else {
      return ['id_telefono' => null];
    }
  }

  function setDatos_nuevoTelefono(array $datos) //
  {
    // Creamos la fila nueva en memoria temporal en el Datos Tabla
    $this->dep($this->nombre_dr_defecto)->tabla('dt_telefonos')->nueva_fila($datos);

    //Obtenemos el id_fila_condicion de la nueva fila
    $id_fila_condicion = $this->dep($this->nombre_dr_defecto)->tabla('dt_telefonos')->get_filas()[0]['x_dbr_clave'];

    // Seteamos el cursor de dt_telefonos en el nuevo registro
    $this->dep($this->nombre_dr_defecto)->tabla('dt_telefonos')->set_cursor($id_fila_condicion);

    // Seteamos la imagen blob como si se tratara de una tabla padre ya que está seteado el cursor
    $this->set_blob_dt(null, 'dt_telefonos', $datos, 'imagen', /*es_ml?*/false);
  }
}
?>
