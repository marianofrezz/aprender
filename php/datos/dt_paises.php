<?php
class dt_paises extends aprender_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_p.id_pais,
			t_p.nombre
		FROM
			paises as t_p
		ORDER BY nombre";
		return toba::db('aprender')->consultar($sql);
	}

	function get_descripciones()
	{
		$sql = "SELECT id_pais, nombre FROM paises ORDER BY nombre";
		return toba::db('aprender')->consultar($sql);
	}


}
?>