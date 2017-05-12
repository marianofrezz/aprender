<?php
class dt_tipos_documentos extends aprender_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_td.id_tipodocumento,
			t_td.nombre
		FROM
			tipos_documentos as t_td
		ORDER BY nombre";
		return toba::db('aprender')->consultar($sql);
	}



}
?>