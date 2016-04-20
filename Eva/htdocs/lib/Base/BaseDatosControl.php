<?php
/**
 * GenesisPHP - Base Datos Control
 *
 * Copyright (C) 2016 Guillermo Valdés Lozano
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package GenesisPHP
 */

namespace Base;

/**
 * Clase BaseDatosControl
 */
class BaseDatosControl {

    public $resultado;     // Resultado de la ejecución del comando SQL
    protected $bd_recurso; // Recurso con la conexión a la base de datos

    /**
     * Constructor
     *
     * @param mixed Recurso a la base de datos
     */
    public function __construct($in_bd_recurso) {
        if (is_resource($in_bd_recurso)) {
            $this->bd_recurso = $in_bd_recurso;
        } else {
            throw new \Exception("Error: Es incorrecto el recurso a la base de datos.");
        }
    } // constructor

    /**
     * Cantidad de registros
     *
     * @return integer Cantidad de registros que resultaron de una consulta
     */
    public function cantidad_registros() {
        return pg_num_rows($this->resultado);
    } // cantidad_registros

    /**
     * Obtener registro
     *
     * @return array Arreglo asociativo con un registro de la consulta
     */
    public function obtener_registro() {
        return pg_fetch_assoc($this->resultado);
    } // obtener_registro

    /**
     * Obtener todos los registros
     *
     * @return array Arreglo de arreglos asociativos con todos los registros de la consulta
     */
    public function obtener_todos_los_registros() {
        return pg_fetch_all($this->resultado);
    } // obtener_todos_los_registros

    /**
     * Obtener columna
     *
     * @param  string Columna de la consulta
     * @return array  Arreglo con los valores de la columna de la consulta
     */
    public function obtener_columna($in_columna) {
        $a = array();
        while ($registro = $this->obtener_registro()) {
            $a[] = $registro[$in_columna];
        }
        return $a;
    } // obtener_columna

} // Clase BaseDatosControl

?>
