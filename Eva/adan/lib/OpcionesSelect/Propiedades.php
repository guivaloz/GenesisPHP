<?php
/**
 * GenesisPHP - OpcionesSelect Propiedades
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

namespace OpcionesSelect;

/**
 * Clase Propiedades
 */
class Propiedades extends \Base\Plantilla {

    /**
     * Elaborar propiedades
     *
     * @return string Código PHP
     */
    protected function elaborar_propiedades() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Declarar propiedades publicas de los filtros
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['filtro'] > 1) {
                // Rango (desde-hasta)
                $a[] = "    // public \${$columna}_desde;";
                $a[] = "    // public \${$columna}_hasta;";
            } elseif ($datos['filtro'] > 0) {
                // Normal
                $a[] = "    // public \${$columna};";
                // Si es una relacion
                if ($datos['tipo'] == 'relacion') {
                    // Se va usar mucho la relacion, asi que para simplificar
                    if (is_array($this->relaciones[$columna])) {
                        $relacion = $this->relaciones[$columna];
                    } else {
                        die("Error en OpcionesSelect, Opciones: Falta obtener datos de Serpiente para la relación $columna.");
                    }
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $a[] = "    // public \${$columna}_{$relacion['vip']};";
                    } elseif (is_array($relacion['vip'])) {
                        // Vip es un arreglo
                        foreach ($relacion['vip'] as $vip => $vip_datos) {
                            // Si es un arreglo
                            if (is_array($vip_datos)) {
                                // Si es una relacion
                                if ($vip_datos['tipo'] == 'relacion') {
                                    // Es una relacion y debe de existir en reptil
                                    if (is_array($this->relaciones[$vip])) {
                                        // Si el vip es un arreglo
                                        if (is_array($this->relaciones[$vip]['vip'])) {
                                            // Ese vip es un arreglo
                                            foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                                // Es cualquier otro tipo
                                                $a[] = "    // public \${$columna}_{$vip}_{$v};";
                                                if ($vd['tipo'] == 'caracter') {
                                                    // Ese vip de la relacion es de tipo caracter, habra un descrito
                                                    $a[] = "    // public \${$columna}_{$vip}_{$v}_descrito;";
                                                }
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $a[] = "    // public \${$columna}_{$vip}_{$this->relaciones[$vip]['vip']};";
                                        }
                                    } else {
                                        die("Error en OpcionesSelect, Opciones: No está definido el VIP en Serpiente para $vip.");
                                    }
                                } elseif ($vip_datos['tipo'] == 'caracter') {
                                    // Es caracter, habra el caracter y el descrito
                                    $a[] = "    // public \${$columna}_{$vip};";
                                    $a[] = "    // public \${$columna}_{$vip}_descrito;";
                                } else {
                                    // Es cualquier otro tipo
                                    $a[] = "    // public \${$columna}_{$vip};";
                                }
                            } else {
                                // Vip datos es un texto
                                $a[] = "    // public \${$columna}_{$vip_datos};";
                            }
                        }
                    }
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedades

    /**
     * Elaborar propiedades estáticas
     *
     * @return string Código PHP
     */
    protected function elaborar_propiedades_estaticas() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Bucle por las columnas con filtro
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['filtro'] > 1) {
                // Rango (desde-hasta)
                $a[] = "    // static public \$param_{$columna}_desde;";
                $a[] = "    // static public \$param_{$columna}_hasta;";
            } elseif ($datos['filtro'] > 0) {
                // Normal
                $a[] = "    // static public \$param_{$columna};";
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedades_estaticas

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    // protected \$sesion;
    // protected \$consultado;
{$this->elaborar_propiedades()}
{$this->elaborar_propiedades_estaticas()}

FINAL;
    } // php

} // Clase Propiedades

?>
