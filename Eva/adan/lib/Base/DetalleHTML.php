<?php
/**
 * GenesisPHP - Base DetalleHTML
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
 * Clase DetalleHTML
 */
class DetalleHTML extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con las partes
        $propiedades           = new \DetalleHTML\Propiedades($this->adan);
        $metodo_html           = new \DetalleHTML\HTML($this->adan);
        $metodo_javascript     = new \DetalleHTML\JavaScript($this->adan);
        $metodo_eliminar_html  = new \DetalleHTML\EliminarHTML($this->adan);
        $metodo_recuperar_html = new \DetalleHTML\RecuperarHTML($this->adan);
        // Armar el contenido con las partes
        $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - SED_TITULO_SINGULAR DetalleHTML
 *
 * @package SED_PAQUETE
 */

namespace SED_CLASE_PLURAL;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

{$propiedades->php()}
{$metodo_html->php()}
{$metodo_javascript->php()}
{$metodo_eliminar_html->php()}{$metodo_recuperar_html->php()}} // Clase DetalleHTML

?>

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase DetalleHTML

?>
