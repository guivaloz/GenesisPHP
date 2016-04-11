<?php
/**
 * GenesisPHP - Listado Propiedades
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

namespace Listado;

/**
 * Clase Propiedades
 */
class Propiedades extends \Base\Plantilla {

    protected $cortos = array(); // Las letras únicas para las propiedaes estáticas de las variables URL

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
                $a[] = "    public \${$columna}_longitud; // Consulta";
                $a[] = "    public \${$columna}_latitud; // Consulta";
                $a[] = "    public \${$columna}_desde_longitud; // Filtro";
                $a[] = "    public \${$columna}_hasta_longitud; // Filtro";
                $a[] = "    public \${$columna}_desde_latitud; // Filtro";
                $a[] = "    public \${$columna}_hasta_latitud; // Filtro";
            } else {
                // Cualquier otro tipo de rango desde-hasta
                $a[] = "    public \${$columna}; // Consulta (".strtoupper($datos['tipo']).")";
                $a[] = "    public \${$columna}_desde; // Filtro (".strtoupper($datos['tipo']).")";
                $a[] = "    public \${$columna}_hasta; // Filtro (".strtoupper($datos['tipo']).")";
            }
        // Si hay filtro
        } elseif ($datos['filtro'] > 0) {
            // Si el tipo es geopunto
            if ($datos['tipo'] == 'geopunto') {
                // Es geopunto
                $a[] = "    public \${$columna}_longitud; // Filtro";
                $a[] = "    public \${$columna}_latitud; // Filtro";
            } elseif ($datos['tipo'] == 'relacion') {
                // Es relacion
                $a[] = "    public \${$columna}; // Filtro relación (id entero)";
            } else {
                // Cualquier otro tipo
                $a[] = "    public \${$columna}; // Filtro (".strtoupper($datos['tipo']).")";
            }
        } else {
            die("Error en Listado, elaborar_propiedades_declaracion: No hay valor en filtro para $columna.");
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
        // Declarar propiedades publicas de los filtros
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no se usa en el listado
            } elseif ($datos['tipo'] == 'relacion') {
                // Agregamos la relacion misma
                $a[] = $this->elaborar_propiedades_declaracion($columna, $datos);
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion = $this->relaciones[$columna];
                } else {
                    die("Error en Listado: Falta obtener datos de Serpiente para la relación $columna.");
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
                                    die("Error en Listado: No está definido el VIP en Serpiente para $vip.");
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
     * Elaborar Propiedades Estaticas Declaración
     *
     * @param  string Columna de la tabla
     * @param  array  Opcional. Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_propiedades_parametros_declaracion($columna, $datos=false) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Si no vienen los datos, por defecto el filtro es uno
        if ($datos === false) {
            $datos = array('filtro' => 1);
        }
        // Determinar la letra unica
        $cantidad = 0;
        do {
            ++$cantidad;
            $propuesta = strtolower(substr($columna, 0, $cantidad));
        } while (in_array($propuesta, $this->cortos) && ($cantidad < strlen($columna)));
        // Agregar
        if ($datos['filtro'] > 1) {
            // Rango (desde-hasta)
            $this->cortos[] = $propuesta.'d';
            $a[] = sprintf('    static public $param_%s = \'%s\';', $columna.'_desde', $propuesta.'d');
            $this->cortos[] = $propuesta.'h';
            $a[] = sprintf('    static public $param_%s = \'%s\';', $columna.'_hasta', $propuesta.'h');
        } elseif ($datos['filtro'] > 0) {
            // Normal
            $this->cortos[] = $propuesta;
            $a[] = sprintf('    static public $param_%s = \'%s\';', $columna, $propuesta);
        } else {
            die("Error en Listado, elaborar_propiedades_parametros_declaracion: No hay valor en filtro para $columna.");
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedades_parametros_declaracion

    /**
     * Elaborar Propiedades Estaticas
     *
     * @return string Código PHP
     */
    protected function elaborar_propiedades_parametros() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Declarar propiedades publicas estaticas de las variables para el url
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no se usa en el listado
            } elseif ($datos['tipo'] == 'relacion') {
                // Agregamos la relacion misma
                $a[] = $this->elaborar_propiedades_parametros_declaracion($columna, $datos);
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion = $this->relaciones[$columna];
                } else {
                    die("Error en Listado: Falta obtener datos de Serpiente para la relación $columna.");
                }
                // A continuacion, la parte complicada que viaja a traves de las relaciones
                if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                    $a[] = $this->elaborar_propiedades_parametros_declaracion("{$columna}_{$relacion['vip']}");
                } elseif (is_array($relacion['vip'])) {
                    foreach ($relacion['vip'] as $vip => $vip_datos) {
                        if (is_array($vip_datos)) {
                            if ($vip_datos['tipo'] == 'relacion') {
                                if (is_array($this->relaciones[$vip])) {
                                    if (is_array($this->relaciones[$vip]['vip'])) {
                                        $a[] = $this->elaborar_propiedades_parametros_declaracion($vip, $vip_datos);
                                        foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                            $a[] = $this->elaborar_propiedades_parametros_declaracion("{$vip}_{$v}", $vd);
                                        }
                                    } else {
                                        $a[] = $this->elaborar_propiedades_parametros_declaracion("{$vip}_{$this->relaciones[$vip]['vip']}");
                                    }
                                } else {
                                    die("Error en Listado: No está definido el VIP en Serpiente para $vip.");
                                }
                            } else {
                                $a[] = $this->elaborar_propiedades_parametros_declaracion("{$columna}_{$vip}", $vip_datos);
                            }
                        } else {
                            $a[] = $this->elaborar_propiedades_parametros_declaracion("{$columna}_{$vip_datos}");
                        }
                    }
                }
            } else {
                // Es cualquier otro tipo
                $a[] = $this->elaborar_propiedades_parametros_declaracion($columna, $datos);
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedades_parametros

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FIN
    // public \$listado;
    // public \$cantidad_registros;
    // public \$limit;
    // protected \$offset;
    // protected \$sesion;
{$this->elaborar_propiedades()}
{$this->elaborar_propiedades_parametros()}
    public \$filtros_param;

FIN;
    } // php

} // Clase Propiedades

?>
