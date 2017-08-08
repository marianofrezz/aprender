<?php
class dt_telefonos extends aprender_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_telefono, interno FROM telefonos ORDER BY interno";
		return toba::db('aprender')->consultar($sql);
	}

}

?>