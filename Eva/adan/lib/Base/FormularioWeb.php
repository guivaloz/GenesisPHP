<?php
/**
 * GenesisPHP - Base FormularioWeb
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
 * Clase FormularioWeb
 */
class FormularioWeb extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con las partes
        $propiedades                = new \FormularioWeb\Propiedades($this->adan);
        $metodo_elaborar_formulario = new \FormularioWeb\ElaborarFormulario($this->adan);
        $metodo_recibir_formulario  = new \FormularioWeb\RecibirFormulario($this->adan);
        $metodo_html                = new \FormularioWeb\HTML($this->adan);
        $metodo_javascript          = new \FormularioWeb\JavaScript($this->adan);
        // Armar el contenido con las partes
        $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - SED_TITULO_SINGULAR FormularioWeb
 *
 * @package SED_PAQUETE
 */

namespace SED_CLASE_PLURAL;

/**
 * Clase FormularioWeb
 */
class FormularioWeb extends DetalleWeb {

{$propiedades->php()}
{$metodo_elaborar_formulario->php()}
{$metodo_recibir_formulario->php()}
{$metodo_html->php()}
{$metodo_javascript->php()}
} // Clase FormularioWeb

?>

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase FormularioWeb

?>
