<?php
/**
 * GenesisPHP - ListadoCSV
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

namespace Base;

/**
 * Clase ListadoCSV
 */
class ListadoCSV {

    public $estructura; // Arreglo asociativo con la estructura del listado
    public $listado;    // Arreglo, resultado de la consulta

    /**
     * CSV
     *
     * @return string CSV
     */
    public function csv() {
        // Validar estructura
        if (!is_array($this->estructura) || (count($this->estructura) == 0)) {
            throw new ListadoExceptionValidacion('Error: No está definida la estructura.');
        }
        // Validar listado
        if (!is_array($this->listado) || (count($this->listado) == 0)) {
            throw new ListadoExceptionValidacion('Error: No está definido o está vacío el listado.');
        }
        // En este arreglo acumularemos el csv
        $a = array();
        // Primer renglon, los titulos de las columnas
        $r = array();
        $c = 0;
        foreach ($this->estructura as $columna => $parametros) {
            $c++;
            if ($parametros['enca'] != '') {
                $r[] = '"'.$parametros['enca'].'"';
            } else {
                $r[] = '"Columna'.$c.'"';
            }
        }
        $a[] = implode(',', $r);
        // Bucle por el listado
        foreach ($this->listado as $fila) {
            $r = array();
            // Bucle por la estructura
            foreach ($this->estructura as $columna => $parametros) {
                // Si hay que cambiar un caracter por su descripcion
                if (is_array($parametros['cambiar'])) {
                    $dato = $parametros['cambiar'][$fila[$columna]];
                } else {
                    $dato = $fila[$columna];
                }
                // De acuerdo al formato
                switch ($parametros['formato']) {
                    case 'fecha':
                        $r[] = formato_fecha($dato);
                        break;
                    case 'entero':
                    case 'flotante':
                    case 'dinero':
                    case 'porcentaje':
                        $r[] = $dato;
                        break;
                    default:
                        // Si hay comillas en el dato se cambia a dos comillas y poner el dato entre comillas
                        $r[] = '"'.str_replace('"', '""', $dato).'"';
                }
            }
            $a[] = implode(',', $r);
        }
        // Entregar
        return implode("\n", $a)."\n";
    } // csv

} // Clase ListadoCSV

?>
