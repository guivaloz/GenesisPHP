<?php
/**
 * GenesisPHP - ImagenWebUltima Constructor
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

namespace ImagenWebUltima;

/**
 * Clase Constructor
 */
class Constructor extends \Base\Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\\Inicio\\Sesion \$in_sesion) {
        \$this->sesion = \$in_sesion;
        parent::__construct(Registro::\$imagen_almacen_ruta, Registro::\$imagen_tamanos);
    } // constructor

FINAL;
    } // php

} // Clase Constructor

?>
