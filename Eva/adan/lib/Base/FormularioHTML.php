<?php
/**
 * GenesisPHP - Base FormularioHTML
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
 * Clase FormularioHTML
 */
class FormularioHTML extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con las partes
        $propiedades                = new \FormularioHTML\Propiedades($this->adan);
        $metodo_elaborar_formulario = new \FormularioHTML\ElaborarFormulario($this->adan);
        $metodo_recibir_formulario  = new \FormularioHTML\RecibirFormulario($this->adan);
        $metodo_html                = new \FormularioHTML\HTML($this->adan);
        $metodo_javascript          = new \FormularioHTML\JavaScript($this->adan);
        // Armar el contenido con las partes
        $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - SED_TITULO_SINGULAR FormularioHTML
 *
 * @package SED_PAQUETE
 */

namespace SED_CLASE_PLURAL;

/**
 * Clase FormularioHTML
 */
class FormularioHTML extends DetalleHTML {

{$propiedades->php()}
{$metodo_elaborar_formulario->php()}
{$metodo_recibir_formulario->php()}
{$metodo_html->php()}
{$metodo_javascript->php()}
} // Clase FormularioHTML

?>

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase FormularioHTML

?>
