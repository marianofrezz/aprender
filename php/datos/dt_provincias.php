<?php
class dt_provincias extends aprender_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_p.id_provincia,
			t_p1.nombre as id_pais_nombre,
			t_p.nombre
		FROM
			provincias as t_p,
			paises as t_p1
		WHERE
				t_p.id_pais = t_p1.id_pais
		ORDER BY nombre";
		return toba::db('aprender')->consultar($sql);
	}


}
?>