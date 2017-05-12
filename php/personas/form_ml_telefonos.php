<?php
class form_ml_telefonos extends aprender_ei_formulario_ml
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
		{$this->objeto_js}.evt__id_tipotelefono__procesar = function(es_inicial, fila)
		{
			var ef = this.ef('id_tipotelefono');
			for (i=0; i < this.filas().length; i++) {
				var fila_idx = this.filas()[i]
				if (typeof ef.ir_a_fila(fila_idx)._get_combo() !== 'undefined'){
					var idTipoTel = ef.ir_a_fila(fila_idx).get_estado();
					this.controlador.ajax('get_confTiposTelefonos', idTipoTel, this, this.setCampos);
				} else {
					console.log('fila:' + (i+1) + ', no tiene estado');
				}
			}
		}

		{$this->objeto_js}.setCampos = function (datos)
		{
			var ef_id = this.ef('id_tipotelefono');
			var ef = this.ef('nro_telefono');
			var ef2 = this.ef('interno');
			console.log(datos);
			for (i=0; i<this.filas().length;i++) {
				var fila_idx = this.filas()[i];
				if (typeof ef_id.ir_a_fila(fila_idx)._get_combo() !== 'undefined') {
					if (datos['id_tipotelefono'] == ef_id.ir_a_fila(fila_idx).get_estado()) {
						if (datos['numero']){
							console.log('Mostramos Numero');
							ef.ir_a_fila(fila_idx).mostrar();
						}else{
							console.log('Ocultamos Numero');
							ef.ir_a_fila(fila_idx).ocultar();
						}

						if (datos['interno']){
							console.log('Mostramos interno');
							ef2.ir_a_fila(fila_idx).mostrar();
						}else{
							console.log('Ocultamos interno');
							ef2.ir_a_fila(fila_idx).ocultar();
						}
					}
				}
			}
		}
		";
	}

}

?>
