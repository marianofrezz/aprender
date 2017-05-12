<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class aprender_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'aprender_comando' => 'extension_toba/aprender_comando.php',
		'aprender_modelo' => 'extension_toba/aprender_modelo.php',
		'aprender_ci' => 'extension_toba/componentes/aprender_ci.php',
		'aprender_cn' => 'extension_toba/componentes/aprender_cn.php',
		'aprender_datos_relacion' => 'extension_toba/componentes/aprender_datos_relacion.php',
		'aprender_datos_tabla' => 'extension_toba/componentes/aprender_datos_tabla.php',
		'aprender_ei_arbol' => 'extension_toba/componentes/aprender_ei_arbol.php',
		'aprender_ei_archivos' => 'extension_toba/componentes/aprender_ei_archivos.php',
		'aprender_ei_calendario' => 'extension_toba/componentes/aprender_ei_calendario.php',
		'aprender_ei_codigo' => 'extension_toba/componentes/aprender_ei_codigo.php',
		'aprender_ei_cuadro' => 'extension_toba/componentes/aprender_ei_cuadro.php',
		'aprender_ei_esquema' => 'extension_toba/componentes/aprender_ei_esquema.php',
		'aprender_ei_filtro' => 'extension_toba/componentes/aprender_ei_filtro.php',
		'aprender_ei_firma' => 'extension_toba/componentes/aprender_ei_firma.php',
		'aprender_ei_formulario' => 'extension_toba/componentes/aprender_ei_formulario.php',
		'aprender_ei_formulario_ml' => 'extension_toba/componentes/aprender_ei_formulario_ml.php',
		'aprender_ei_grafico' => 'extension_toba/componentes/aprender_ei_grafico.php',
		'aprender_ei_mapa' => 'extension_toba/componentes/aprender_ei_mapa.php',
		'aprender_servicio_web' => 'extension_toba/componentes/aprender_servicio_web.php',
	);
}
?>