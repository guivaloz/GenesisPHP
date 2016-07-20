<?php
/**
 * GenesisPHP - TemaWeb
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
 * Clase abtracta TemaWeb
 */
abstract class TemaWeb {

    public $sistema;               // Nombre del sistema
    public $titulo;                // Título de la página
    public $descripcion;
    public $autor;
    public $css;                   // Texto con la ruta a un archivo CSS
    public $css_comun;             // Arreglo con códigos HTML que cargan archivos CSS
    public $favicon;
    public $menu_principal_logo;
    public $icono;                 // Texto, nombre del archivo con el icono de la página
    public $contenido  = array();  // Texto con código HTML
    public $javascript = array();  // Texto con código Javascript
    public $javascript_comun;      // Arreglo con códigos Javascript
    public $pie        = array();  // Texto con código HTML
    public $menu;                  // Instancia de \Inicio\Menu

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

} // Clase abtracta TemaWeb

?>
