<?php
/**
 * GenesisPHP - DetalleHTML Propiedades
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

namespace DetalleHTML;

/**
 * Clase Propiedades
 */
class Propiedades extends \Base\Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Juntaremos el código en este arreglo
        $a = array();
        // Propiedades comentadas
        $seccion_propiedades = new \Registro\Propiedades($this->adan);
        $a[]                 = $seccion_propiedades->php_comentado();
        // Javascript
        $a[] = "    protected \$javascript = array();";
        // Acciones
        if ($this->adan->si_hay_que_crear('eliminar')) {
            $a[] = "    static public \$accion_eliminar  = 'SED_CLASE_SINGULAREliminar';";
        }
        if ($this->adan->si_hay_que_crear('formulario')) {
            $a[] = "    static public \$accion_modificar = 'SED_CLASE_SINGULARModificar';";
        }
        if ($this->adan->si_hay_que_crear('recuperar')) {
            $a[] = "    static public \$accion_recuperar = 'SED_CLASE_SINGULARRecuperar';";
        }
        // Entregar
        return implode("\n", $a)."\n";
    } // php

} // Clase Propiedades

?>
