<?php
/**
 * GenesisPHP - OpcionesSelect Opciones
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
 * Clase Opciones
 */
class Opciones extends \Base\Plantilla {

    /*
     * Elaborar estatus activo
     *
     * @return string Código PHP
     */
    protected function elaborar_estatus_activo() {
        if (count($this->tabla['estatus']) > 0) {
            if ($this->tabla['estatus']['descripciones']['A'] != '') {
                $a = array();
                $a[] = "        // Filtrar solo los registros con estatus en uso";
                $a[] = "        \$this->estatus = 'A';";
                return implode("\n", $a);
            } else {
                return "        // No se usa 'A' en la columna estatus, entonces no se filtra";
            }
        } else {
            return "        // Informo que no hay columna estatus, entonces no se filtra";
        }
    } // elaborar_estatus_activo

    /*
     * Elaborar asignaciones de opciones
     *
     * @return string Código PHP
     */
    protected function elaborar_opciones_asignacion() {
        // Vamos a juntar los vip para tenerlos de mayor a menor
        $vips  = array();
        $mayor = 0;
        // Bucle para cada columna
        foreach ($this->tabla as $columna => $datos) {
            // Tomamos el valor vip, si no está definido sera cero
            $n = $datos['vip'];
            // Si vip es mayor a cero
            if ($n > 0) {
                // Si es una relacion, mostrara la o las columnas vip
                if ($datos['tipo'] == 'relacion') {
                    // Se va usar mucho la relacion, asi que para simplificar
                    if (is_array($this->relaciones[$columna])) {
                        $relacion = $this->relaciones[$columna];
                    } else {
                        die("Error en Listado: Falta obtener datos de Serpiente para la relación $columna.");
                    }
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $vips[$n][] = "{$columna}_{$relacion['vip']}";
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
                                                $vips[$n][] = "{$columna}_{$vip}_{$v}";
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $vips[$n][] = "{$columna}_{$vip}";
                                        }
                                    } else {
                                        die("Error en OpcionesSelect: No está definido el VIP en Serpiente para $vip.");
                                    }
                                } elseif ($vip_datos['tipo'] == 'caracter') {
                                    // Es caracter, usaremos su descrito
                                    $vips[$n][] = "{$columna}_{$vip}"; // La consulta arroja el caracter, falta programar un bucle que los convierta a descrito
                                } else {
                                    // Es cualquier otro tipo
                                    $vips[$n][] = "{$columna}_{$vip}";
                                }
                            } else {
                                // Vip datos es un texto
                                $vips[$n][] = "{$columna}_{$vip_datos}";
                            }
                        }
                    }
                } else {
                    // No es una relacion, va directo el nombre de la columna
                    $vips[$n][] = $columna;
                }
                // Guardar el numero mayor
                if ($n > $mayor) {
                    $mayor = $n;
                }
            }
        }
        // Si no se encuentra ninguno, error
        if (count($vips) == 0) {
            die('Error en OpcionesSelect: No hay columnas con VIP con valor mayor a cero.');
        }
        // Del vip mayor (izquierda) al mayor (derecha), ir juntando
        $a = array();
        for ($n=$mayor; $n>0; $n--) {
            if (count($vips[$n]) > 0) {
                foreach ($vips[$n] as $columna) {
                    $a[] = "\$item['$columna']";
                }
            }
        }
        $juntos = implode(".', '.", $a);
        // Entregar
        return "            \$a[\$item['id']] = $juntos;";
    } // elaborar_opciones_asignacion

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FIN
    /**
     * Opciones para Select
     *
     * @return array Arreglo asociativo, id => descripcion
     */
    public function opciones() {
{$this->elaborar_estatus_activo()}
        // Consultar
        \$this->consultar();
        // Juntar como arreglo asociativo
        \$a = array();
        foreach (\$this->listado as \$item) {
{$this->elaborar_opciones_asignacion()}
        }
        // Entregar
        return \$a;
    } // opciones

FIN;
    } // php

} // Clase Opciones

?>
