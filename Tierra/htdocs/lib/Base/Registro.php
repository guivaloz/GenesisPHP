<?php
/**
 * GenesisPHP - Registro
 *
 * Copyright 2015 Guillermo Valdés Lozano <guivaloz@movimientolibre.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @package GenesisPHP
 */

namespace Base;

/**
 * Clase Registro
 */
abstract class Registro {

    protected $sesion;             // Instancia de \Inicio\Sesion
    protected $consultado = false; // Verdadero si ya fue consultado

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        $this->sesion = $in_sesion;
    } // constructor

    /**
     * Consultar
     */
    abstract function consultar();

    /**
     *
     * Consulta exitosa
     *
     * @return boolean Verdadero si ha sido consultado con éxito
     */
    public function consulta_exitosa() {
        return $this->consultado;
    } // consulta_exitosa

} // Clase Registro

?>
