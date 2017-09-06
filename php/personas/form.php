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

		{$this->objeto_js}.evt__id_provincia__procesar = function(es_inicial)
		{
			var parametros = [];
      parametros['id_provincia'] = this.ef('id_provincia').get_estado();
			this.controlador.ajax('getLocalidades', parametros, this, this.cascada_manual_formuntelefono_idlocalidad);
		}

		{$this->objeto_js}.cascada_manual_formuntelefono_idlocalidad = function(datos)
		{
      var opciones = [];
			var opcion = [];
      datos.forEach(function(elemento)
      {
				opcion=[]
				opcion.push(elemento['id_localidad']);
				opcion.push(elemento['nombre']);
				opciones.push(opcion);
      })
			console.log(opciones);
      this.controlador.dep('form_untelefono').ef('idlocalidad_cascada_manual').set_opciones_rs(opciones);
		}
		";
	}

}

?>
