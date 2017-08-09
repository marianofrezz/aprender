<?php
class dt_tipos_acciones extends aprender_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_ta.id_tipoaccion,
			t_ta.nombre_accion
		FROM
			tipos_acciones as t_ta
		ORDER BY nombre_accion";
		return toba::db('aprender')->consultar($sql);
	}

	function get_descripciones()
	{
		$sql = "SELECT id_tipoaccion, nombre_accion FROM tipos_acciones ORDER BY nombre_accion";
		return toba::db('aprender')->consultar($sql);
	}

}
?>