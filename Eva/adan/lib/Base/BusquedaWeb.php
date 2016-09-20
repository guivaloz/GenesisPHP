<?php
/**
 * GenesisPHP - Base BusquedaWeb
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
 * Clase BusquedaWeb
 */
class BusquedaWeb extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con las partes
        $propiedades                = new \BusquedaWeb\Propiedades($this->adan);
        $metodo_validar             = new \BusquedaWeb\Validar($this->adan);
        $metodo_elaborar_formulario = new \BusquedaWeb\ElaborarFormulario($this->adan);
        $metodo_recibir_formulario  = new \BusquedaWeb\RecibirFormulario($this->adan);
        $metodo_consultar           = new \BusquedaWeb\Consultar($this->adan);
        // Armar el contenido con las partes
        $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - SED_TITULO_SINGULAR BusquedaWeb
 *
 * @package SED_PAQUETE
 */

namespace SED_CLASE_PLURAL;

/**
 * Clase BusquedaWeb
 */
class BusquedaWeb extends \\Base2\\BusquedaWeb implements \\Base2\\SalidaWeb {

{$propiedades->php()}
{$metodo_validar->php()}
{$metodo_elaborar_formulario->php()}
{$metodo_recibir_formulario->php()}
{$metodo_consultar->php()}
} // Clase BusquedaWeb

?>

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase BusquedaWeb

?>
