<?php
/**
 * GenesisPHP - Modulos FormularioHTML
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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

namespace Modulos;

/**
 * Clase FormularioHTML
 */
class FormularioHTML extends DetalleHTML {

    // protected $sesion;
    // protected $consultado;
    //
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    // protected $detalle;
    protected $formulario;               // Instancia de FormularioHTML
    static public $form_name = 'modulo'; // Name del formulario

    /**
     * Elaborar formulario
     *
     * @return string HTML del Formulario
     */
    protected function elaborar_formulario() {
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
    } // recibir_formulario

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return implode("\n", array(
            $this->detalle->javascript(),
            $this->formulario->javascript()));
    } // javascript

} // Clase FormularioHTML

?>
