<?php
/**
 * GenesisPHP - DESCRIPCION
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
 */

require_once('autocargadorclases.php');

/**
 * Clase PruebaDetalle
 */
class PruebaDetalle extends \Base\PaginaHTML {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('tierra_prueba_detalle');
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Acumularemos la entrega en este arreglo
        $a = array();
        // Acumular
        $detalle = new \Pruebas\CactusDetalleHTML($this->sesion);
        $a[]     = $detalle->html();
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase PruebaDetalle

// Ejecutar y mostrar
$pagina = new PruebaDetalle();
echo $pagina->html();

?>
