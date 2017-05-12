<?php
class dao_tiposdedocumentosmanual
{
  static function get_datosInventado()
  {
    $sql = "SELECT * FROM eaprender.tipos_documentos";
    $datos = consultar_fuente($sql);
    return $datos;
  }
}
?>
