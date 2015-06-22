<?php
/**
 * GenesisPHP - Usuarios Listado
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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

namespace Usuarios;

/**
 * Clase Listado
 */
class Listado extends \Base\Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    //
    public $filtros_param;

    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        // Validar filtros
        // Iniciamos el arreglo para los filtros
        // Pasar los filtros como parámetros de los botones
        // Ejecutar validar en el padre
    } // validar

    /**
     * Encabezado
     *
     * @return string Texto del encabezado
     */
    public function encabezado() {
        // En este arreglo juntaremos lo que se va a entregar
        // Juntar elementos
        // Entregar
    } // encabezado

    /**
     * Consultar
     */
    public function consultar() {
        // Validar
        // Filtros
        // Consultar
        // Provocar excepción si no hay resultados
        // Pasar la consulta a la propiedad listado
        // Consultar la cantidad de registros
    } // consultar

} // Clase Listado

?>
