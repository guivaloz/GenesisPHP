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
     * Procesar el contenido, javascript o pie; trabaja con textos o arreglos de textos y quita renglones en blanco
     *
     * @param  mixed  Arreglo o texto con el contenido
     * @param  string Opcional, tag a poner antes y después del contenido; por ejemplo use 'script' con javascript
     * @return string Código HTML
     */
    protected function bloque_html($in_contenido, $in_tag='') {
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
                if ($in_tag != '') {
                    return "<$in_tag>\n".implode("\n", $a)."\n</$in_tag>";
                } else {
                    return implode("\n", $a);
                }
            } else {
                return '<!-- Bloque sin contenido -->';
            }
        } elseif (is_string($in_contenido) && ($in_contenido != '')) {
            if ($in_tag != '') {
                return "<$in_tag>\n$in_contenido\n</$in_tag>";
            } else {
                return $in_contenido;
            }
        } else {
            return '<!-- Bloque sin contenido -->';
        }
    } // bloque_html

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
