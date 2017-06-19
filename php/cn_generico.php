<?php
class cn_generico extends toba_cn
{
	// Nombre del dr por defecto
	protected $nombre_dr_defecto = 'dr_defecto';
	// Nombres de los dt por defecto, el índice 0 es el dt padre
	protected $nombres_dt_defecto = [0 => 'dt_padre'];
	// Cache de datos del cn
	protected $s__datos = [];

	/**
	* Guarda la variable $datos en la posición $clave.
	*/
	public function cachear_datos($clave, $datos)
	{
		$this->s__datos[$clave] = $datos;
	}

	/**
	* Establece los nombres por defecto del datos relación y  de los datos tabla.
	*
	* @param string $nombre_dr  Nombre del datos relación a usar por defecto.
	* @param array  $nombres_dt Array numerado tipo [0 => 'dt_padre1', ]. Define
	*                           los nombres de los datos tabla padres.
	*/
	public function set_nombres_defecto($nombre_dr, array $nombres_dt)
	{
		// Si nos pasaron un nombre de DR
		if (isset($nombre_dr)) {
			// Establecemos el dr por defecto
			$this->nombre_dr_defecto = $nombre_dr;
		}

		// Reemplazamos la lista de DTs si es que nos la pasaron
		if (isset($nombres_dt)) {
			$this->nombres_dt_defecto = $nombres_dt;
		}
	}

	/**
	* Carga el "dr por defecto". Si recibe un $nombre_dr, entonces carga ese.
	* Si se indica, luego de cargar, se setea el cursor.
	* Todo lo anterior usando las claves en $seleccion.
	*
	* @param array  $seleccion        Array asociativo tipo ['nombre_columna_id' => valor_id, ]
	*                                 para setear el cursor de la(s) tabla(s) padre.
	* @param bool   $setCursor        En caso de ser true se setea el cursor usando la
	*                                 información de $seleccion
	* @param string $nombre_dr        Nombre del datos relación a usar, si no se
	*                                 determina, se usará $this->nombre_dr_defecto
	* @param array  $nombres_dt       Array numerado tipo [0 => 'dt_padre1', ]. Define
	*                                 los nombres de los dts padre. Si se omite este argumento, entonces se
	*                                 usará dr->cargar($selección).
	* @param array  $nombres_dt_hijos Array numerado tipo [0 => 'dt_hijo1', ]. Define
	*                                 los nombres de los dts hijos. Si se omite este argumento, entonces se
	*                                 omite la carga de dt hijos. Estos dt se cargarán sin argumentos luego de los padres.
	*/
	public function cargar(array $seleccion, $setCursor = false, $nombre_dr = null, array $nombres_dt = null, array $nombres_dt_hijos = null)
	{
		$nom_dr = (isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto);
		$dr = $this->dep($nom_dr);

		if (!$dr->esta_cargada()) {
			if (isset($seleccion)) {
				if (isset($nombres_dt)) {
					foreach ($nombres_dt as $key => $value) {
						$dr->tabla($value)->cargar($seleccion);
					}
					if (isset($nombres_dt_hijos)) {
						foreach ($nombres_dt_hijos as $key => $value) {
							$dr->tabla($value)->cargar();
						}
					}
				} else {
					$dr->cargar($seleccion);
				}
			} else {
				$dr->cargar();
			}
		}

		if (isset($setCursor) && $setCursor) {
			$this->set_cursor($seleccion, $nombre_dr, $nombres_dt);
		}
	}

	/**
	* Carga los dt pasados en nombres_dt sin pasar ningún $seleccion.
	*
	* @param string $nombre_dr  Nombre del datos relación a usar, si no se
	*                           determina, se usará $this->nombre_dr_defecto
	* @param array  $nombres_dt Array numerado tipo [0 => 'dt_hijo1', ]. Define
	*                           los nombres de los dts hijo. Si se omite este argumento, entonces se
	*                           usará this->nombres_dt_defecto.
	*/
	public function cargar_dts($nombre_dr = null, array $nombres_dt = null)
	{
		$nom_dr = (isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto);
		$dr = $this->dep($nom_dr);
		$dts = isset($nombres_dt) ? $nombres_dt : $this->nombres_dt_defecto;

		if (isset($dts)) {
			foreach ($dts as $key => $dt) {
				$dr->tabla($dt)->cargar();
			}
		}
	}

	/**
	* Carga un datos tabla con datos ya cacheados.
	*
	* @param array  $datos     Array asociativo tipo ['nombre_columna' => valor, ]
	*                          con los datos cacheados para setear al dt.
	* @param string $nombre_dr Nombre del datos relación a usar, si no se
	*                          determina, se usará $this->nombre_dr_defecto
	* @param string $nombre_dt Nombre del datos tabla al cual cargar los datos.
	*                          Si se omite, se usará this->nombres_dt_defecto[0].
	*/
	public function cargar_con_datos(array $datos, $nombre_dr = null, $nombre_dt = null)
	{
		$nom_dr = (isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto);
		$nom_dt = (isset($nombre_dt) ? $nombre_dt : $this->nombres_dt_defecto[0]);

		$dr = $this->dep($nom_dr);
		$dt = $dr->tabla($nom_dt);

		$dt->cargar_con_datos($datos);
	}

	/**
	* Setea el cursor de la(s) tabla(s) padre de la relación.
	*
	* @param array  $seleccion  Array asociativo tipo ['nombre_columna_id' => valor_id, ]
	*                           para setear el cursor la(s) tabla(s) padre.
	* @param string $nombre_dr  Nombre del datos relación a usar, si no se
	*                           determina, se usará $this->nombre_dr_defecto
	* @param array  $nombres_dt Array numerado tipo [0 => 'dt_padre1', ]. Define
	*                           los nombres de los dts padre. Si se omite este argumento entonces se usará
	*                           this->nombres_dt_defecto.
	*/
	public function set_cursor($seleccion, $nombre_dr = null, array $nombres_dt = null)
	{
		$dr = $this->dep((isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto));
		$dts = (isset($nombres_dt) ? $nombres_dt : $this->nombres_dt_defecto);

		foreach ($dts as $nombre_dt) {
			$dt = $dr->tabla($nombre_dt);
			if ($dt->esta_cargada()) { // Si no se hace esta consulta puede fallar el set_cursor cuando el procedimiento cargar no trajo datos.
				$dt->set_cursor($dt->get_id_fila_condicion($seleccion)[0]);
			}
		}
	}

	/**
	* Asocia los $datos al dt. Si hay cursor, entonces se agrega como una
	* modificacion. De lo contrario se trata como una fila nueva.
	*
	* @param array() $datos      Array asociativo tipo ['nombre_columna' => valor_fila, ].
	*                            Determina los datos de la tabla a sociar para, más adelante, registrar los
	*                            datos en la base de datos.
	* @param bool    $es_ml      Determina si el dt se asocia a un $datos proviniente de
	*                            un frm_ml. True = proviene de un ml.
	* @param string  $nombre_dr  Nombre del datos relación a usar, si no se
	*                            determina, se usará $this->nombre_dr_defecto
	* @param string  $nombre_dt  Nombre del datos tabla del cual obtener los datos.
	*                            Si se omite, se usará this->nombres_dt_defecto[0].
	* @param array() $ids_padres Array asociativo del tipo ['nombre_columna_id' => valor_fila_id, ].
	*                            Si se provee este parámetro, los valores de $datos son asociados a estos
	*                            ids del dt padre.
	*
	* @return toba_cursor (solo para el caso de que se agregue nueva fila)
	*/
	public function set_datos_dt($datos, $es_ml=false, $nombre_dr=null, $nombre_dt=null, $ids_padres=null, $reiniciarDt=false)
	{
		$nom_dr = (isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto);
		$nom_dt = (isset($nombre_dt) ? $nombre_dt : $this->nombres_dt_defecto[0]);
		$dr = $this->dep($nom_dr);
		$dt = $dr->tabla($nom_dt);

		if ($reiniciarDt) {
			$dt->resetear();
		}

		if ($es_ml) { // Si es ml
			$dt->procesar_filas($datos, $ids_padres); // Procesamos las filas
		} else { // Si no es ml
			if ($this->hay_cursor_dt($nombre_dr, $nombre_dt)) { // Si es una modificación
				if ($dt->esta_cargada()) {
					$dt->set($datos); // Ejecutamos modificación
				}
			} else { // Si no es modificacion
				return $dt->set_cursor($dt->nueva_fila($datos)); // Ejecutamos nueva fila
			}
		}
	}

	/**
	* Asocia los $datos blob al dt.
	*
	* @param string $nombre_dr    Nombre del datos relación a usar, si no se
	*                             determina, se usará $this->nombre_dr_defecto
	* @param string $nombre_dt    Nombre del datos tabla del cual obtener los datos.
	*                             Si se omite, se usará this->nombres_dt_defecto[0].
	* @param array  $datos        Array asociativo tipo ['nombre_columna' => valor_fila, ].
	*                             Determina los datos de la tabla a sociar para, más adelante, registrar los
	*                             datos en la base de datos.
	* @param string $nombre_campo Determina el nombre del campo blob.
	* @param bool   $es_ml        Determina si el dt se asocia a un $datos proviniente de
	*                             un frm_ml. True = proviene de un ml.
	*/
	public function set_blob_dt($nombre_dr, $nombre_dt, $datos, $nombre_campo, $es_ml = false)
	{
		$nom_dr = (isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto);
		$dr = $this->dep($nom_dr);
		$nom_dt = (isset($nombre_dt) ? $nombre_dt : $this->nombres_dt_defecto[0]);
		$dt = $dr->tabla($nom_dt);

		// Si es ml, procesamos las filas
		if ($es_ml) {
			foreach ($datos as $key => $value) {
				$this->set_blob($dt, $nombre_campo, $datos[$key], $key);
			}
		} else { // Si no es ml
			$this->set_blob($dt, $nombre_campo, $datos, null);
		}
	}

	/**
	* Asocia los $datos blob al dt.
	*
	* @param toba_datos_tabla $dt           Datos tabla instanciado.
	* @param string           $nombre_campo Determina el nombre del campo blob.
	* @param array            $datos        Array asociativo tipo ['nombre_columna' => valor_fila, ].
	*                                       Determina los datos de la tabla a sociar para, más adelante, registrar los
	*                                       datos en la base de datos.
	* @param array            $id_fila      Array asociativo tipo ['nombre_columna' => valor_fila, ].
	*                                       Determina el id explícito de la fila a usar.
	*/
	public function set_blob($dt, $nombre_campo, $datos, $id_fila = null)
	{
		if (is_array($datos[$nombre_campo])) {
			$s__temp_archivo = $datos[$nombre_campo]['tmp_name'];
			$imagen = fopen($s__temp_archivo, 'rb');

			$dt->set_blob($nombre_campo, $imagen, $id_fila);
		}
	}

	/**
	* Obtiene los datos de la base de datos para el datos tabla $nombre_dt.
	*
	* @param bool   $es_ml            Indica si los datos son para un frm_ml.
	* @param bool   $set_fuentebd Indica si se debe "marcar" cada fila,
	*                                 agregando la columna "fuentebd" con el valor True para cada una.
	* @param string $nombre_dr        nombre del datos relación a usar, si no se
	*                                 determina, se usará $this->nombre_dr_defecto
	* @param string $nombre_dt        nombre del datos tabla del cual obtener los datos.
	*                                 Si se omite, se usará this->nombres_dt_defecto[0].
	*
	* @return array $datos Array asociativo tipo ['nombre_columna' => valor_fila]
	*/
	public function get_datos_dt($es_ml = false, $set_fuentebd = false, $nombre_dr = null, $nombre_dt = null, $condiciones_get_filas = null)
	{
		$nom_dr = (isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto);
		$dr = $this->dep($nom_dr);
		$nom_dt = (isset($nombre_dt) ? $nombre_dt : $this->nombres_dt_defecto[0]);
		$dt = $dr->tabla($nom_dt);

		if ($dt->esta_cargada()) {
			if ($es_ml) {
				$datos = $dt->get_filas($condiciones_get_filas);

				if ($set_fuentebd) {
					foreach ($datos as $key => $value) {
						$datos[$key]['fuentebd'] = true;
					}
				}
			} else {
				$datos = $dt->get();
			}

			return $datos;
		} else {
			return [];
		}
	}

	/**
	* Obtiene los $datos blob del dt $nombre_dt.
	*
	* @param string $nombre_dr    nombre del datos relación a usar, si no se
	*                             determina, se usará $this->nombre_dr_defecto
	* @param string $nombre_dt    nombre del datos tabla del cual obtener los datos.
	*                             Si se omite, se usará this->nombres_dt_defecto[0].
	* @param array  $datos        Array asociativo tipo ['nombre_columna' => valor_fila, ].
	*                             Determina los datos de la tabla a sociar para, más adelante, registrar los
	*                             datos en la base de datos.
	* @param string $nombre_campo Determina el nombre del campo blob.
	* @param bool   $es_ml        Determina si el dt se asocia a un $datos proviniente de
	*                             un frm_ml. True = proviene de un ml.
	*/
	public function get_blobs($nombre_dr, $nombre_dt, $datos, $nombre_campo)
	{
		$datos_r = array();
		foreach ($datos as $key => $value) {
			$datos_r[$key] = $this->get_blob($nombre_dr, $nombre_dt, $datos[$key], $nombre_campo, $key);
		}

		return $datos_r;
	}

	public function get_blob($nombre_dr, $nombre_dt, $datos, $nombre_campo, $id_fila)
	{
		$dr = $this->dep((isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto));
		$dt = $dr->tabla((isset($nombre_dt) ? $nombre_dt : $this->nombres_dt_defecto[0]));

		$html_imagen = null;

		$imagen = $dt->get_blob($nombre_campo, $id_fila);
		if (isset($imagen)) {
			$temp_nombre = md5(uniqid(time()));
			$s__temp_archivo = toba::proyecto()->get_www_temp($temp_nombre);
			$temp_imagen = fopen($s__temp_archivo['path'], 'w');
			stream_copy_to_stream($imagen, $temp_imagen);
			fclose($temp_imagen);
			$tamano = round(filesize($s__temp_archivo['path']) / 1024);
			$html_imagen =
			"<img src='{$s__temp_archivo['url']}' alt='' />";
			$datos[$nombre_campo] = 'Tamaño foto actual: '.$tamano.' kb';
		} else {
			$datos[$nombre_campo] = null;
		}

		return array('datos' => $datos, 'html_imagen' => $html_imagen);
	}

	/**
	* Determina si existe cursor para el datos tabla cuyo nombre es $nombre_dt,
	*  dentro del datos relacion cuyo nombre es $nombre_dr.
	*
	* @param string $nombre_dr nombre del datos relación a usar, si no se
	*                          determina, se usará $this->nombre_dr_defecto
	* @param string $nombre_dt String con el nombre del dt a usar. Si se omite
	*                          este argumento, entonces se usará this->nombres_dt_defecto[0].
	*/
	public function hay_cursor_dt($nombre_dr = null, $nombre_dt = null)
	{
		$dr = $this->dep((isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto));
		$dt = $dr->tabla((isset($nombre_dt) ? $nombre_dt : $this->nombres_dt_defecto[0]));

		if ($dt->esta_cargada()) {
			return $dt->hay_cursor();
		}
	}

	/**
	* Reinicia el CN. Si se desea se reinicia un DR, o en su defecto, si se desea, un DT.
	*
	* @param string $nombre_dr Nombre del datos relación a usar, si no se
	*                          determina, se usará $this->nombre_dr_defecto
	* @param string $nombre_dt String con el nombre del dt a resetear. Si se
	*                          omite este argumento, entonces se reseteará el dr.
	*/
	public function reiniciar($nombre_dr = null, $nombre_dt = null)
	{
		if ((!isset($nombre_dr)) && (!isset($nombre_dt))) {
			// Reseteamos todo el CN
			foreach ($this->_lista_dependencias as $key => $nombre_dr) {
				if (substr($nombre_dr, 0, 3) == 'dr_') { // Solo reseteamos los dr
					$this->dep($nombre_dr)->resetear();
				} elseif (substr($nombre_dr, 0, 3) == 'cn_') {
					$this->dep($nombre_dr)->reiniciar();
				}
			}
			// Reseteamos el caché local
			unset($this->s__datos);
			$this->s__datos = [];
		} else {
			// Obtenemos el DR
			$dr = $this->dep((isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto));

			// Reseteamos el DR o el DT según nos hayan pasado un nombre de dt o no
			if (isset($nombre_dt)) {
				$dt = $dr->tabla($nombre_dt);
				$dt->resetear();
			} else {
				$dr->resetear();
			}
		}
	}

	/**
	* Sincroniza el datos relación y lo resetea.
	*
	* @param string $nombre_dr Nombre del datos relación a usar, si no se
	*                          determina, se usará $this->nombre_dr_defecto
	* @param bool   $resetear  Determina si se debe resetear el dr luego de sincronizar
	* @param bool   $todos     Determina si se deben sincronizar todos los DR del CN.
	*/
	public function guardar($nombre_dr = null, $resetear = true, $todos = false)
	{
		if ($todos) {
			foreach ($this->_lista_dependencias as $key => $nombre_dr) {
				if (substr($nombre_dr, 0, 3) == 'dr_') { // Solo sincronizamos los dr
					$dr = $this->dep($nombre_dr);

					$dr->sincronizar();
					if ($resetear) {
						$dr->resetear();
					}
				}
			}
		} else {
			$dr = $this->dep((isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto));
			$dr->sincronizar();
			if ($resetear) {
				$dr->resetear();
			}
		}
	}

	/**
	* Elimina de la base de datos el registro con id $id.
	*
	* @param array  $id        Array asociativo del tipo ['nombre_columna' => valor_fila]
	*                          que determina la clave del registro a eliminar.
	* @param string $nombre_dr Nombre del datos relación a usar, si no se
	*                          determina, se usará $this->nombre_dr_defecto
	* @param string $nombre_dt String con el nombre del dt a resetear. Si se
	*                          omite este argumento, entonces se usará $this->nombres_dt_defecto[0].
	*/
	public function eliminar($id, $nombre_dr = null, $nombre_dt = null)
	{
		$dr = $this->dep((isset($nombre_dr) ? $nombre_dr : $this->nombre_dr_defecto));
		$dt = $dr->tabla((isset($nombre_dt) ? $nombre_dt : $this->nombres_dt_defecto[0]));

		if ($dt->esta_cargada()) {
			$dt->eliminar_fila($dt->get_id_fila_condicion($id)[0]);
		}
	}

	/**
	* Elimina de la base de datos el registro con id $id, cargando el dt $nombre_dt como dt raiz.
	*
	* @param array  $id        Array asociativo del tipo ['nombre_columna' => valor_fila]
	*                          que determina la clave del registro a eliminar.
	* @param string $nombre_dr Nombre del datos relación a usar, si no se
	*                          determina, se usará $this->nombre_dr_defecto
	* @param string $nombre_dt String con el nombre del dt a resetear. Si se
	*                          omite este argumento, entonces se usará $this->nombres_dt_defecto[0].
	*/
	public function eliminar_fila_dtRaiz($id, $nombre_dr = null, $nombre_dt = null)
	{
		$setCursor = true;
		$nombres_dt = (isset($nombre_dt) ? [$nombre_dt] : $nombre_dt);
		$this->cargar($id, $setCursor, $nombre_dr, $nombres_dt);
		$this->eliminar($id, $nombre_dr, $nombre_dt);
	}

	// Coding test: áéíóú ñ ç ö ôü
}
