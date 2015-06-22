<?php
/**
 * GenesisPHP - Usuarios DetalleHTML
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

namespace Usuarios;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    //
    protected $detalle;
    static public $accion_modificar = 'moduloModificar';
    static public $accion_eliminar  = 'moduloEliminar';
    static public $accion_recuperar = 'moduloRecuperar';

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar DetalleHTML
        $this->detalle = new \Base\DetalleHTML();
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Debe estar consultado, de lo contrario se consulta y si falla se muestra mensaje
        // Detalle
        // Sección
        // Encabezado/Barra
        // Entregar
    } // html

    /**
     * Eliminar HTML
     *
     * @return string HTML con el detalle y el mensaje
     */
    public function eliminar_html() {
        // Eliminar, si tiene éxito se muestra el mensaje y el detalle
    } // eliminar_html

    /**
     * Recuperar HTML
     *
     * @return string HTML con el detalle y el mensaje
     */
    public function recuperar_html() {
        // Recuperar, si tiene éxito se muestra el mensaje y el detalle
    } // recuperar_html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->detalle->javascript();
    } // javascript

} // Clase DetalleHTML

?>
