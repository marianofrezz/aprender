<?php
class dt_tipos_lineas extends aprender_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_tl.id_tipolinea,
			t_tl.nombre_linea
		FROM
			tipos_lineas as t_tl
		ORDER BY nombre_linea";
		return toba::db('aprender')->consultar($sql);
	}

	function get_descripciones()
	{
		$sql = "SELECT id_tipolinea, nombre_linea FROM tipos_lineas ORDER BY nombre_linea";
		return toba::db('aprender')->consultar($sql);
	}

}
?>