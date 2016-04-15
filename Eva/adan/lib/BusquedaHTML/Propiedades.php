<?php
/**
 * GenesisPHP - BusquedaHTML Propiedades
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

namespace BusquedaHTML;

/**
 * Clase Propiedades
 */
class Propiedades extends \Base\Plantilla {

    /**
     * Elaborar Propiedades Declaración
     *
     * @param  string Columna de la tabla
     * @param  array  Opcional. Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_propiedades_declaracion($columna, $datos=false) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Si no vienen los datos, por defecto el filtro es uno
        if ($datos === false) {
            $datos = array('filtro' => 1);
        }
        // Si filtro es mayor a uno es un rango desde-hasta
        if ($datos['filtro'] > 1) {
            // Si el tipo es geopunto
            if ($datos['tipo'] == 'geopunto') {
                // El filtro para geopunto son dos puntos
                $a[] = "    public \${$columna}_desde_longitud; // Filtro geopunto";
                $a[] = "    public \${$columna}_hasta_longitud; // Filtro geopunto";
                $a[] = "    public \${$columna}_desde_latitud; // Filtro geopunto";
                $a[] = "    public \${$columna}_hasta_latitud; // Filtro geopunto";
            } else {
                // Cualquier otro tipo de rango desde-hasta
                $a[] = "    public \${$columna}_desde; // Filtro ({$datos['tipo']})";
                $a[] = "    public \${$columna}_hasta; // Filtro ({$datos['tipo']})";
            }
        // Si hay filtro
        } elseif ($datos['filtro'] > 0) {
            // Si el tipo es geopunto
            if ($datos['tipo'] == 'geopunto') {
                // Es geopunto
                $a[] = "    public \${$columna}_longitud; // Filtro geopunto";
                $a[] = "    public \${$columna}_latitud; // Filtro geopunto";
            } elseif ($datos['tipo'] == 'relacion') {
                // Es relacion
                $a[] = "    // {$columna} no se usa por ser relación (id entero)";
            } else {
                // Cualquier otro tipo
                $a[] = "    public \${$columna}; // Filtro ({$datos['tipo']})";
            }
        } else {
            die("Error en BusquedaHTML, Propiedades, elaborar_propiedades_declaracion: No hay valor en filtro para $columna.");
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedades_declaracion

    /**
     * Elaborar Propiedades
     *
     * @return string Código PHP
     */
    protected function elaborar_propiedades() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Bucle a traves todas las columnas de tabla
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                // Si no hay etiqueta o valor en filtro, no aparece en la busqueda
                continue;
            } elseif ($datos['tipo'] == 'relacion') {
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion = $this->relaciones[$columna];
                } else {
                    die("Error en BusquedaHTML, Propiedades, elaborar_propiedades: Falta obtener datos de Serpiente para la relación $columna.");
                }
                // A continuacion, la parte complicada que viaja a traves de las relaciones
                if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                    $a[] = $this->elaborar_propiedades_declaracion("{$columna}_{$relacion['vip']}");
                } elseif (is_array($relacion['vip'])) {
                    foreach ($relacion['vip'] as $vip => $vip_datos) {
                        if (is_array($vip_datos)) {
                            if ($vip_datos['tipo'] == 'relacion') {
                                if (is_array($this->relaciones[$vip])) {
                                    if (is_array($this->relaciones[$vip]['vip'])) {
                                        $a[] = $this->elaborar_propiedades_declaracion($vip, $vip_datos);
                                        foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                            $a[] = $this->elaborar_propiedades_declaracion("{$vip}_{$v}", $vd);
                                        }
                                    } else {
                                        $a[] = $this->elaborar_propiedades_declaracion("{$vip}_{$this->relaciones[$vip]['vip']}");
                                    }
                                } else {
                                    die("Error en BusquedaHTML, Propiedades, elaborar_propiedades: No está definido el VIP en Serpiente para $vip.");
                                }
                            } else {
                                $a[] = $this->elaborar_propiedades_declaracion("{$columna}_{$vip}", $vip_datos);
                            }
                        } else {
                            $a[] = $this->elaborar_propiedades_declaracion("{$columna}_{$vip_datos}");
                        }
                    }
                }
            } else {
                // Es cualquier otro tipo
                $a[] = $this->elaborar_propiedades_declaracion($columna, $datos);
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedades

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    // public    \$hay_resultados;
    // protected \$sesion;
    // protected \$consultado;
{$this->elaborar_propiedades()}
    protected \$javascript = array();
    static public \$form_name = 'SED_INSTANCIA_PLURAL_busqueda';

FINAL;
    } // php

} // Clase Propiedades

?>
