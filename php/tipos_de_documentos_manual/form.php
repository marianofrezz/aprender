<?php
class form extends aprender_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------

		/**
		 * Método que se invoca al cambiar el valor del ef en el cliente
		 * Se dispara inicialmente al graficar la pantalla, enviando en true el primer parámetro
		 */
		{$this->objeto_js}.evt__nombre__procesar = function(es_inicial)
		{
			var ef = this.ef('nombre');
			if (ef.tiene_estado()) {
				ef.set_estado(ef.get_estado().toUpperCase());
			}
		}
		";
	}

}
?>
