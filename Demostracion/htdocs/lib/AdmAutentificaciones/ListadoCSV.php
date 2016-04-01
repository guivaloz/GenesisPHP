<?php
/**
 * GenesisPHP - Usuarios ListadoCSV
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

namespace AdmAutentificaciones;

/**
 * Clase ListadoCSV
 */
class ListadoCSV extends Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
    } // constructor

    /**
     * CSV
     *
     * A diferencia de ListadoHTML, esta clase puede causar una excepcion si falla la consulta
     *
     * @return string CSV
     */
    public function csv() {
    } // csv

} // Clase ListadoCSV

?>
