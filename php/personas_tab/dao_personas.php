<?php
require_once 'tipos_de_documentos_manual/dao_tiposdedocumentosmanual.php';
class dao_personas
{
  static function get_personas($where='')
  {
    if ($where) {
      $where_armado = "WHERE $where";
    } else {
      $where_armado = '';
    }

    $sql = "SELECT * FROM (
            	SELECT
            	      per.id_persona,
                    tdc.id_tipodocumento,
            	      per.nro_documento,
            	      tdc.nombre || ' ' || per.nro_documento tipo_y_nrodocumento,
            	      apellidos,
            	      nombres,
                    nombres || ' ' || apellidos nomyape
            	FROM eaprender.personas per
            	JOIN eaprender.tipos_documentos tdc ON per.id_tipodocumento = tdc.id_tipodocumento
            ) ttt
            $where_armado";

    $resultado = consultar_fuente($sql);
    return $resultado;
  }

  static function get_descomboTelefono($id)
  {
    $id = quote($id);
    $sql = "SELECT ttel.nombre
            FROM eaprender.tipos_telefonos ttel
            WHERE ttel.id_tipotelefono = $id";
    $descripcion = consultar_fuente($sql);
    return $descripcion[0]['nombre'];
  }

  static function get_opcomboTelefono($consulta)
  {
    $consulta = quote($consulta);
    $sql = "SELECT
              id_tipotelefono,
              nombre,
              numero,
              interno
            FROM eaprender.tipos_telefonos
            WHERE nombre ILIKE '%' || $consulta || '%'";
    $opciones = consultar_fuente($sql);
    return $opciones;
  }

  static function get_opcionesProvincia($id_pais)
  {
    $id_pais = quote($id_pais);

    $sql = "SELECT prv.id_provincia,
                   prv.nombre
            	FROM eaprender.provincias prv
             WHERE prv.id_pais = $id_pais";

    $opciones = consultar_fuente($sql);
    return $opciones;
  }

  static function get_descPopUpPais($id_pais)
  {
    $id_pais = quote($id_pais);

    $sql = "SELECT pais.nombre
            	FROM eaprender.paises pais
             WHERE pais.id_pais = $id_pais";

    $resultado = consultar_fuente($sql);

    if (count($resultado) > 0) {
      return $resultado[0]['nombre'];
    } else {
      return 'Falló, intente nuevamente';
    }
  }

  function get_descPopUpProvincia($id_provincia)
  {
    $id_provincia = quote($id_provincia);

    $sql = "SELECT pvs.nombre
            	FROM eaprender.provincias pvs
             WHERE pvs.id_provincia = $id_provincia";

    $resultado = consultar_fuente($sql);

    if (count($resultado) > 0) {
      return $resultado[0]['nombre'];
    } else {
      return 'Falló, intente nuevamente';
    }
  }

  static function get_opcionesLocalidad($id_provincia)
  {
    $id_provincia = quote($id_provincia);

    $sql = "SELECT loc.id_localidad,
                   loc.nombre
            	FROM eaprender.localidades loc
             WHERE loc.id_provincia = $id_provincia";

    $opciones = consultar_fuente($sql);
    return $opciones;
  }

  static function get_tiposDocumentos()
  {
    return dao_tiposdedocumentosmanual::get_datosInventado();
  }

  static function get_idsExtLocalidad($id_localidad)
  {
    $id_localidad = quote($id_localidad);

    $sql = "SELECT prv.id_pais,
                   prv.id_provincia
            	FROM eaprender.provincias prv
              JOIN eaprender.localidades loc ON loc.id_provincia = prv.id_provincia
             WHERE loc.id_localidad = $id_localidad";

    $resultado = consultar_fuente($sql);

    return $resultado[0];
  }

  static function get_confTiposTelefonos($idTipoTel)
  {
    if (!$idTipoTel && $idTipoTel != 0) {
      return null;
    }

    $idTipoTel = quote($idTipoTel);

    $sql = "SELECT *
              FROM eaprender.tipos_telefonos ttel
              WHERE ttel.id_tipotelefono = $idTipoTel";

    $resultado = consultar_fuente($sql);

    return $resultado[0];
  }


}

?>
