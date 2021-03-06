<?php
/**
 * GenesisPHP - Tema
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
 * Clase Abstracta Tema
 */
abstract class Tema extends UtileriasParaDatos {

    public $sistema;
    public $titulo;
    public $descripcion;
    public $autor;
    public $css;
    public $favicon;
    public $menu_principal_logo;
    public $icono;                 // Texto, nombre del archivo con el icono de la página
    public $contenido  = array();  // Arreglo o texto con el contenido
    public $javascript = array();  // Arreglo o texto con Javascript
    public $pie        = array();  // Arreglo o texto con el pie
    public $menu;                  // Instancia de \Inicio\Menu

    /**
     * Bloque HTML
     *
     * Sirve para procesar el contenido, javascript y pie
     *
     * @param  mixed  Arreglo o texto con el contenido
     * @param  string Tag a poner antes y después del contenido
     * @return string Código HTML
     */
    protected function bloque_html($in_contenido, $in_tag) {
        // Si es arreglo o es texto
        if (is_array($in_contenido)) {
            $a = array();
            // Bucle para evitar los valores vacios
            foreach ($in_contenido as $c) {
                if (is_string($c) && ($c != '')) {
                    $a[] = $c;
                }
            }
            // Entregar
            if (count($a)) {
                return "<$in_tag>\n".implode("\n", $a)."\n</$in_tag>";
            } else {
                return '';
            }
        } elseif (is_string($in_contenido) && ($in_contenido != '')) {
            return "<$in_tag>\n$in_contenido\n</$in_tag>";
        } else {
            return '';
        }
    } // bloque_html

    /**
     * HTML
     */
    abstract public function html();

} // Clase Abstracta Tema

?>
