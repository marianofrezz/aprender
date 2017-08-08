<?php
class ci_modificarpersona_tab extends aprender_ci
{
	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(aprender_ei_formulario $form)
	{
		if (isset($this->s__datos['form'])) {
			$form->set_datos($this->s__datos['form']);
		} else {

			if ($this->cn()->hay_cursor_persona()) {
				$datos = $this->cn()->get_personas();
				$this->s__datos['form'] = $datos;
				$form->set_datos($datos);
			}
		}
	}

	function evt__form__modificacion($datos)
	{
		$this->s__datos['form'] = $datos;
		$this->cn()->set_personas($datos);
	}

}
?>
