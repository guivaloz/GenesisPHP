<?php
/**
 * GenesisPHP - Registro Propiedades
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

namespace Registro;

/**
 * Clase Propiedades
 */
class Propiedades extends \Base\Plantilla {

    /**
     * Elaborar Propiedad Relación
     *
     * @param  string Columna
     * @param  array  Datos
     * @return string Código PHP
     */
    protected function elaborar_propiedad_relacion($columna, $datos) {
        // Lo que se va a entregar se juntará en este arreglo
        $a = array();
        // Se va usar mucho la relación, así que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion = $this->relaciones[$columna];
        } else {
            die("Error en Registro Propiedades: Falta obtener datos de Serpiente para la relación $columna.");
        }
        // Si vip es texto
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            // Solo un vip
            $a[] = "    public \${$columna}_{$relacion['vip']};";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
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
                                    // Para cualquier tipo
                                    $a[] = "    public \${$vip}_{$v};";
                                    if ($vd['tipo'] == 'caracter') {
                                        // Es de tipo caracter, se usara un descrito
                                        $a[] = "    public \${$vip}_{$v}_descrito;";
                                    }
                                }
                            } else {
                                // Ese vip es texto
                                $a[] = "    public \${$vip}_{$this->relaciones[$vip]['vip']};";
                            }
                        } else {
                            die("Error en Registro Propiedades: No está definido el VIP en Serpiente para $vip.");
                        }
                    } elseif ($vip_datos['tipo'] == 'caracter') {
                        // Es caracter, habra el caracter y su descrito
                        $a[] = "    public \${$columna}_{$vip};";
                        $a[] = "    public \${$columna}_{$vip}_descrito;";
                    } else {
                        // Es cualquier otro tipo
                        $a[] = "    public \${$columna}_{$vip};";
                    }
                } else {
                    // Vip datos es un texto
                    $a[] = "    public \${$columna}_{$vip_datos};";
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedad_relacion

    /**
     * Elaborar Propiedades
     *
     * @return string Código PHP
     */
    protected function elaborar_propiedades() {
        // Lo que se va a entregar se juntará en este arreglo
        $a = array();
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Si es del tipo geopunto
            if ($datos['tipo'] == 'geopunto') {
                // Columna de tipo geopunto
                $a[] = "    public \${$columna}_longitud;";
                $a[] = "    public \${$columna}_latitud;";
                $a[] = "    public \${$columna}_geojson;";
            } else {
                // Columna de cualquier otro tipo
                $a[] = "    public \${$columna};";
            }
            // Si la columna es de tipo caracter, se agrega propiedad con descrito
            if ($datos['tipo'] == 'caracter') {
                $a[] = "    public \${$columna}_descrito;";
            }
            // Si la columna es una relacion
            if ($datos['tipo'] == 'relacion') {
                $a[] = $this->elaborar_propiedad_relacion($columna, $datos);
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedades

    /**
     * Elaborar Propiedades Estaticas
     *
     * @return string Código PHP
     */
    protected function elaborar_propiedades_estaticas() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Propiedades de tipo caracter
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['tipo'] == 'caracter') {
                // Validar
                if (!is_array($datos['descripciones']) || (count($datos['descripciones']) == 0)) {
                    die("Error en Registro Propiedades: La columna $columna no tiene un arreglo de descripciones.");
                }
                // Descripciones
                $a[] = "    static public \${$columna}_descripciones = array(";
                $b   = array();
                foreach ($datos['descripciones'] as $letra => $descripcion) {
                    $b[] = "        '$letra' => '$descripcion'";
                }
                $a[] = implode(",\n", $b).");";
                // Colores
                $a[] = "    static public \${$columna}_colores = array(";
                $b   = array();
                foreach ($datos['colores'] as $letra => $color) {
                    $b[] = "        '$letra' => '$color'";
                }
                $a[] = implode(",\n", $b).");";
            }
        }
        // Imagen
        if (is_array($this->imagen)) {
            if (is_string($this->imagen['almacen_ruta']) && ($this->imagen['almacen_ruta'] != '')) {
                $a[] = "    static public \$imagen_almacen_ruta = '{$this->imagen['almacen_ruta']}';";
            } else {
                die("Error en Registro Propiedades: No está definida la ruta al almacen de imágenes.");
            }
            if (is_array($this->imagen['tamaños']) && (count($this->imagen['tamaños']) > 0)) {
                $a[] = "    static public \$imagen_tamanos = array(";
                $b   = array();
                foreach ($this->imagen['tamaños'] as $tamano => $dimensiones) {
                    if (is_int($dimensiones)) {
                        $b[] = "        '$tamano' => $dimensiones";
                    } elseif(is_string($dimensiones)) {
                        $b[] = "        '$tamano' => '$dimensiones'";
                    } else {
                        die("Error en Registro Propiedades: La dimensión de imagen es incorrecta.");
                    }
                }
                $a[] = implode(",\n", $b).");";
            } else {
                die("Error en Registro Propiedades: No están definidos los tamaños de las imágenes.");
            }
        }
        // Impresion
        if (is_array($this->impresion)) {
            if (is_string($this->impresion['almacen_ruta']) && ($this->impresion['almacen_ruta'] != '')) {
                $a[] = "    static public \$impresion_almacen_ruta = '{$this->impresion['almacen_ruta']}';";
            } else {
                die("Error en Registro Propiedades: No está definida la ruta al almacen de impresiones.");
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
