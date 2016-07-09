<?php
/**
 * GenesisPHP - TemaImpresora
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

namespace Base2;

/**
 * Clase abtracta TemaImpresora
 */
abstract class TemaImpresora {

    public $sistema;               // Nombre del sistema
    public $titulo;                // Título de la página
    public $css;                   // Ruta al archivo CSS
    public $contenido  = array();  // Arreglo o texto con el contenido
    public $javascript = array();  // Arreglo o texto con Javascript

    /**
     * Cabecera HTML
     */
    abstract protected function cabecera_html();

    /**
     * Final HTML
     */
    abstract protected function final_html();

    /**
     * HTML
     */
    abstract public function html();

} // Clase abtracta TemaImpresora

?>
