<?php
/**
 * GenesisPHP - Base ListadoWeb
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
 * Clase ListadoWeb
 */
class ListadoWeb extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con las partes
        $propiedades        = new \ListadoWeb\Propiedades($this->adan);
        $metodo_constructor = new \ListadoWeb\Constructor($this->adan);
        $metodo_barra       = new \ListadoWeb\Barra($this->adan);
        $metodo_html        = new \ListadoWeb\HTML($this->adan);
        $metodo_javascript  = new \ListadoWeb\JavaScript($this->adan);
        // Armar el contenido con las partes
        $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - SED_TITULO_PLURAL ListadoHTML
 *
 * @package SED_PAQUETE
 */

namespace SED_CLASE_PLURAL;

/**
 * Clase ListadoWeb
 */
class ListadoWeb extends Listado implements \\Base2\\SalidaWeb {

{$propiedades->php()}
{$metodo_constructor->php()}
{$metodo_barra->php()}
{$metodo_html->php()}
{$metodo_javascript->php()}
} // Clase ListadoWeb

?>

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase ListadoWeb

?>
