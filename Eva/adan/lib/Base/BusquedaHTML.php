<?php
/**
 * GenesisPHP - Base BusquedaHTML
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
 * Clase BusquedaHTML
 */
class BusquedaHTML extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con las partes
        $propiedades                = new \BusquedaHTML\Propiedades($this->adan);
        $metodo_validar             = new \BusquedaHTML\Validar($this->adan);
        $metodo_elaborar_formulario = new \BusquedaHTML\ElaborarFormulario($this->adan);
        $metodo_recibir_formulario  = new \BusquedaHTML\RecibirFormulario($this->adan);
        $metodo_consultar           = new \BusquedaHTML\Consultar($this->adan);
        $metodo_javascript          = new \BusquedaHTML\JavaScript($this->adan);
        // Armar el contenido con las partes
        $contenido = <<<FINAL

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase BusquedaHTML

?>
