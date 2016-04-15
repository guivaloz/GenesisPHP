<?php
/**
 * GenesisPHP - Base PaginaHTML
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
 * Clase PaginaHTML
 */
class PaginaHTML extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con las partes
        $propiedades                   = new \PaginaHTML\Propiedades($this->adan);
        $metodo_constructor            = new \PaginaHTML\Constructor($this->adan);
        $metodo_collapse_padre_e_hijos = new \PaginaHTML\CollapsePadreEHijos($this->adan);
        $metodo_html                   = new \PaginaHTML\HTML($this->adan);
        // Armar el contenido con las partes
        $contenido = <<<FINAL
/**
 * SED_SISTEMA - SED_TITULO_PLURAL PaginaHTML
 *
 * @package SED_PAQUETE
 */

namespace SED_CLASE_PLURAL;

/**
 * Clase PaginaHTML
 */
class PaginaHTML extends \\Base\\PaginaHTML {

{$propiedades->php()}
{$metodo_constructor->php()}
{$metodo_collapse_padre_e_hijos->php()}
{$metodo_html->php()}
} // Clase PaginaHTML

?>

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase PaginaHTML

?>
