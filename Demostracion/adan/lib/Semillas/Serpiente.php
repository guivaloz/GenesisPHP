<?php
/**
 * GenesisPHP - Semillas Serpiente
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

namespace Semillas;

/**
 * Clase Serpiente
 */
class Serpiente extends \Arbol\Serpiente {

    // protected $sistema_nombre;
    // protected $sistema_siglas;
    // protected $reptil;

    /**
     * Constructor
     */
    public function __construct() {
        // Cargar nombre y siglas del sistema
        $this->sistema_nombre = 'Demostración';
        $this->sistema_siglas = 'GenesisPHP';
        // Cargar reptil
        $this->reptil['CatAreas']   = Adan0111CatAreas::$reptil;
        $this->reptil['CatPuestos'] = Adan0113CatPuestos::$reptil;
    } // constructor

} // Clase Serpiente

?>
