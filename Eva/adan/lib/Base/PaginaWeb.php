<?php
/**
 * GenesisPHP - Base PaginaWeb
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
 * Clase PaginaWeb
 */
class PaginaWeb extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con los fragmentos
        $propiedades                     = new \PaginaWeb\Propiedades($this->adan);
        $metodo_constructor              = new \PaginaWeb\Constructor($this->adan);
        $metodo_acordeones_padre_e_hijos = new \PaginaWeb\AcordeonesPadreEHijos($this->adan);
        $metodo_html                     = new \PaginaWeb\HTML($this->adan);
        // Acumular fragmentos
        $f = array();
        if ($p = $propiedades->php())                     $f[] = $p;
        if ($m = $metodo_constructor->php())              $f[] = $m;
        if ($m = $metodo_acordeones_padre_e_hijos->php()) $f[] = $m;
        if ($m = $metodo_html->php())                     $f[] = $m;
        $todo = implode("\n", $f);
        // Armar el contenido
        $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - SED_TITULO_PLURAL PaginaHTML
 *
 * @package SED_PAQUETE
 */

namespace SED_CLASE_PLURAL;

/**
 * Clase PaginaWeb
 */
class PaginaWeb extends \\Base2\\PaginaWeb {

$todo
} // Clase PaginaWeb

?>

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase PaginaWeb

?>
