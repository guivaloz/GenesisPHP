<?php
/**
 * GenesisPHP - Listado Encabezado
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
 * Clase Encabezado
 */
class Encabezado extends \Base\Plantilla {

    /**
     * Elaborar Encabezado Estatus
     *
     * @return string Código PHP
     */
    protected function elaborar_encabezado_estatus() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Al filtrar por estatus, se requiere tener permiso para eliminar, y asi ver el estatus
        $a[] = "        if ((\$this->estatus != '') && \$this->sesion->puede_recuperar('SED_CLAVE')) {";
        $a[] = "            \$e[] = Registro::\$estatus_descripciones[\$this->estatus];";
        $a[] = "        }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_encabezado_estatus

    /**
     * Elaborar Encabezado Relación
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_encabezado_relacion($columna, $datos) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Se va usar mucho la relacion, asi que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion = $this->relaciones[$columna];
        } else {
            die("Error en Listado, encabezado, elaborar_encabezado_relacion: Falta obtener datos de Serpiente para la relación $columna.");
        }
        // Programar que si viene la relacion, en el encabezado se muestran sus vip
        $a[] = "        if (\$this->{$columna} != '') {";
        // A continuacion, la parte complicada que viaja a traves de las relaciones
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = "            \$e[] = \" {\$this->{$columna}_{$relacion['vip']}}\"; // Segundo nivel";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    if ($vd['tipo'] == 'caracter') {
                                        $a[] = "            \$e[] = \"{$vd['etiqueta']} {\$this->{$vip}_{$v}_descrito}\"; // Tercer nivel";
                                    } else {
                                        $a[] = "            \$e[] = \"{$vd['etiqueta']} {\$this->{$vip}_{$v}}\"; // Tercer nivel";
                                    }
                                }
                            } else {
                                $a[] = "            \$e[] = \"{$this->relaciones[$vip]['etiqueta']} {\$this->{$vip}_{$this->relaciones[$vip]['vip']}}\"; // Tercer nivel";
                            }
                        } else {
                            die("Error en Listado, encabezado, elaborar_encabezado_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } elseif ($vip_datos['tipo'] == 'caracter') {
                        $a[] = "            \$e[] = \"{$vip_datos['etiqueta']} {\$this->{$columna}_{$vip}_descrito}\"; // Segundo nivel";
                    } else {
                        $a[] = "            \$e[] = \"{$vip_datos['etiqueta']} {\$this->{$columna}_{$vip}}\"; // Segundo nivel";
                    }
                } else {
                    $a[] = "            \$e[] = \"{$datos['etiqueta']} {\$this->{$columna}_{$vip_datos}}\"; // Segundo nivel";
                }
            }
        }
        $a[] = "        } else {";
        // A continuacion, la parte complicada que viaja a traves de las relaciones
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = $this->elaborar_encabezado_campo("{$columna}_{$relacion['vip']}", $datos, 2);
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                $a[] = "            // Relacion segundo nivel";
                                $a[] = "            if (\$this->{$vip} != '') {";
                                $a[] = "                \$e[] = \"{$this->relaciones[$vip]['etiqueta_singular']} ID:{\$this->{$vip}}\";";
                                $a[] = "            } else {";
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = "                // Relacion tercer nivel";
                                    $a[] = $this->elaborar_encabezado_campo("{$vip}_{$v}", $vd, 3, $this->relaciones[$vip], $v);
                                }
                                $a[] = "            }";
                            } else {
                                $a[] = "            // Pendiente la validacion de {$vip}_{$this->relaciones[$vip]['vip']}, segundo nivel";
                            }
                        } else {
                            die("Error en Listado, elaborar_encabezado_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } else {
                        $a[] = "            // Relacion segundo nivel";
                        $a[] = $this->elaborar_encabezado_campo("{$columna}_{$vip}", $vip_datos, 2, $this->relaciones[$columna], $vip);
                    }
                } else {
                    $a[] = "            // Pendiente la validacion de {$columna}_{$vip_datos}, segundo nivel";
                }
            }
        }
        $a[] = "        }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_encabezado_relacion

    /**
     * Elaborar Encabezado Campo
     *
     * @param  string  Columna de la tabla.
     * @param  array   Datos declarados para esa columna en la semilla.
     * @param  integer Opcional. Número de nivel, use 2 o 3, por defecto 1. Agrega espacios al inicio de cada línea.
     * @param  mixed   Opcional. Si este campo es de una relación, se debe dar la misma.
     * @param  string  Opcional. Columna de la tabla relacionada.
     * @return string  Código PHP
     */
    protected function elaborar_encabezado_campo($columna, $datos, $nivel=1, $relacion=null, $relacion_columna=null) {
        // Espacios a poner al principio
        $espacios = str_repeat(' ', $nivel*4);
        // Se omite el tipo relacion
        if ($datos['tipo'] == 'relacion') {
            return "$espacios    // Se omite $columna porque es de tipo relacion";
        }
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // De acuerdo al tipo y al filtro
        if ($datos['tipo'] == 'boleano') {
            $a[] = "$espacios    if (is_bool(\$this->{$columna})) {";
            $a[] = "$espacios        if (\$this->{$columna} == true) {";
            $a[] = "$espacios            \$e[] = \"{$datos['etiqueta']} es Verdadero\";";
            $a[] = "$espacios        } else {";
            $a[] = "$espacios            \$e[] = \"{$datos['etiqueta']} es Falso\";";
            $a[] = "$espacios        }";
            $a[] = "$espacios    }";
        } elseif ($datos['filtro'] > 1) {
            // Es un rango, desde-hasta
            $a[] = "$espacios    if (\$this->{$columna}_desde != '') {";
            $a[] = "$espacios        \$e[] = \"{$datos['etiqueta']} desde {\$this->{$columna}_desde}\";";
            $a[] = "$espacios    }";
            $a[] = "$espacios    if (\$this->{$columna}_hasta != '') {";
            $a[] = "$espacios        \$e[] = \"{$datos['etiqueta']} hasta {\$this->{$columna}_hasta}\";";
            $a[] = "$espacios    }";
        } elseif ($datos['filtro'] > 0) {
            // Es un filtro normal
            $a[] = "$espacios    if (\$this->{$columna} != '') {";
            // Si es caracter
            if ($datos['tipo'] == 'caracter') {
                if ($nivel == 1) {
                    // En el mismo namespace
                    $a[] = "$espacios        \$e[] = Registro::\${$columna}_descripciones[\$this->{$columna}];";
                } else {
                    // En otro namespace
                    if (is_array($relacion) && ($relacion['clase_plural'] != '')) {
                        $a[] = "$espacios        \$e[] = \\{$relacion['clase_plural']}\\Registro::\${$relacion_columna}_descripciones[\$this->{$columna}];";
                    } else {
                        $a[] = "$espacios        // Informo que en la columna de tipo caracter $columna no se pudo poner el descrito";
                    }
                }
            } else {
                // No es caracter
                $a[] = "$espacios        \$e[] = \"{$datos['etiqueta']} {\$this->{$columna}}\";";
            }
            $a[] = "$espacios    }";
        } else {
            $a[] = "$espacios    // Informo que la columna $columna no tiene filtro";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_encabezado_campo

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Bucle a traves todas las columnas de tabla
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no aparece en el listado
            } elseif ($columna == 'estatus') {
                $a[] = $this->elaborar_encabezado_estatus();
            } elseif ($datos['tipo'] == 'relacion') {
                $a[] = $this->elaborar_encabezado_relacion($columna, $datos);
            } else {
                $a[] = $this->elaborar_encabezado_campo($columna, $datos);
            }
        }
        // Juntar elementos
        $encabezado_elementos = implode("\n", $a);
        // Entregar
        return <<<FINAL
    /**
     * Encabezado
     *
     * @return string Texto con el encabezado
     */
    public function encabezado() {
        // En este arreglo juntaremos los elementos del encabezado
        \$e = array();
        // Elementos para el encabezado
{$encabezado_elementos}
        // Definimos el encabezado
        if (count(\$e) > 0) {
            if (\$this->cantidad_registros > 0) {
                \$this->encabezado = sprintf('%d SED_TITULO_PLURAL con %s', \$this->cantidad_registros, implode(", ", \$e));
            } else {
                \$this->encabezado = sprintf('SED_TITULO_PLURAL con %s', implode(", ", \$e));
            }
        } else {
            \$this->encabezado = 'SED_TITULO_PLURAL';
        }
        // Entregamos
        return \$this->encabezado;
    } // encabezado

FINAL;
    } // php

} // Clase Encabezado

?>
