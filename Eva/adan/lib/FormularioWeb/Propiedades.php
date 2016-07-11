<?php
/**
 * GenesisPHP - FormularioWeb
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

namespace FormularioWeb;

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
        $seccion_propiedades = new \DetalleWeb\Propiedades($this->adan);
        $a[]                 = $seccion_propiedades->php_comentado();
        // Imagen
        if (is_array($this->imagen)) {
            if (is_string($this->imagen['variable']) && ($this->imagen['variable'] != '')) {
                $variable = $this->imagen['variable'];
            } else {
                $variable = 'imagen';
            }
            $a[] = "    protected \$imagen_{$variable};";
            $a[] = "    protected \$imagen_temporal;";
        }
        // Propiedades
        $a[] = "    public \$entrego_detalle  = false;";
        $a[] = "    protected \$es_nuevo;";
        $a[] = "    protected \$html_elaborado; // El metodo html solo procesa una vez, despues entrega el mismo html";
        $a[] = "    static public \$form_name = 'SED_ARCHIVO_SINGULAR';";
        // Entregar
        return implode("\n", $a)."\n";
    } // php

} // Clase Propiedades

?>
