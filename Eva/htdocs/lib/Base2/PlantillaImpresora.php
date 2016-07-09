<?php
/**
 * GenesisPHP - PlantillaImpresora
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
 * Clase abstracta PlantillaImpresora
 */
abstract class PlantillaImpresora extends \Configuracion\PlantillaImpresoraConfig {

    // protected $sistema;
    // protected $titulo;
    // protected $css;
    public $contenido  = array();  // Arreglo con el contenido
    public $javascript = array();  // Arreglo con el Javascript

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // La única plantilla
        $plantilla             = new TemaImpresoraBlancoNegro();
        $plantilla->sistema    = $this->sistema;
        $plantilla->titulo     = $this->titulo;
        $plantilla->css        = $this->css;
        $plantilla->contenido  = $this->contenido;
        $plantilla->javascript = $this->javascript;
        // Evitar que se guarde en el cache del navegador
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // Entregar
        return $plantilla->html();
    } // html

} // Clase abstracta PlantillaImpresora

?>
