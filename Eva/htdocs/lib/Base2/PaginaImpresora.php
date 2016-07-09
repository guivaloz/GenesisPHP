<?php
/**
 * GenesisPHP - PaginaImpresora
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

namespace Base2;

/**
 * Clase abstracta PaginaImpresora
 */
abstract class PaginaImpresora extends PlantillaImpresora {

    // public $contenido;
    // public $javascript;
    protected $clave;              // Clave única de la página
    protected $sesion;          // Instancia de \Inicio\Sesion
    protected $sesion_exitosa;  // Boleano, verdadero si se cargó con éxito la sesión
    protected $usuario;         // Entero, ID del usuario
    protected $usuario_nombre;  // Texto, nombre del usuario

    /**
     * Constructor
     *
     * @param string Clave de la página
     */
    public function __construct($in_clave) {
        // Parametro clave de la página
        $this->clave = $in_clave;
        // Definir la sesión
        $this->sesion = new \Inicio\Sesion();
        try {
            // Cargar la sesión
            $this->sesion->cargar($this->clave);
            // La sesión se ha cargado con exito
            $this->sesion_exitosa = true;
            // Pasar datos del usuario
            $this->usuario        = $this->sesion->usuario;
            $this->usuario_nombre = $this->sesion->nombre;
        } catch (\Exception $e) {
            // Ha fallado la sesión, se mostrará el mensaje en la pantalla de ingreso
            $this->sesion_exitosa = false;
            $this->contenido      = $e->getMessage();
        }
    } // constructor

} // Clase abstracta PaginaImpresora

?>
