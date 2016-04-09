<?php
/**
 * GenesisPHP - Registro Consultar
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
 * Clase Consultar
 */
class Consultar extends \Base\Plantilla {

    protected $hay_mas_tablas = false; // Cambia a verdadero si hay que consultar dos o más tablas

    /**
     * Elaborar Consultar Select Tablas
     *
     * @return string Tablas
     */
    protected function elaborar_consultar_select_tablas() {
        // Juntaremos las tablas en este arreglo
        $tablas = array($this->tabla_nombre);
        // Si hay tablas impuestas, como texto
        if (is_string($this->adan->tabla_impuesto_sql) && ($this->adan->tabla_impuesto_sql != '')) {
            if (!in_array($this->adan->tabla_impuesto_sql, $tablas)) {
                $tablas[]             = $this->adan->tabla_impuesto_sql;
                $this->hay_mas_tablas = true; // Levantamos la bandera
            }
        // Si hay tablas impuestas, como arreglo
        } elseif (is_array($this->adan->tabla_impuesto_sql) && (count($this->adan->tabla_impuesto_sql) > 0)) {
            foreach ($this->adan->tabla_impuesto_sql as $t) {
                // Para asegurar que cada tabla aparezca solo una vez
                if (!in_array($t, $tablas)) {
                    $tablas[]             = $t;
                    $this->hay_mas_tablas = true; // Levantamos la bandera
                }
            }
        }
        // Entregar tablas
        return implode(",\n                    ", $tablas);
    } // elaborar_consultar_select_tablas

    /**
     * Elaborar Consultar Select Columnas
     *
     * @return string Columnas
     */
    protected function elaborar_consultar_select_columnas() {
        // Juntaremos las columnas en este arreglo
        $columnas = array();
        // Para cada columna de la propiedad tabla
        foreach ($this->tabla as $columna => $datos) {
            // Si hay mas de una tabla, tendremos que escribir de la forma tabla.columna
            if ($this->hay_mas_tablas) {
                $origen = "{$this->tabla_nombre}.$columna";
            } else {
                $origen = $columna;
            }
            // De acuerdo al tipo
            if ($datos['tipo'] == 'fecha_hora') {
                // Es fecha
                $columnas[] = "to_char($origen, 'YYYY-MM-DD HH24:MI') AS $columna";
            } elseif ($datos['tipo'] == 'geopunto') {
                // Es geopunto
                $columnas[] = "ST_AsText($origen) AS $columna";
                $columnas[] = "ST_AsGeoJSON($origen) as {$columna}_geojson";
            } else {
                // Cualquier otro tipo
                $columnas[] = $origen;
            }
        }
        // Entregar
        return implode(",\n                    ", $columnas);
    } // elaborar_consultar_select_columnas

    /**
     * Elaborar Consultar Select Filtros
     *
     * @return string Filtros
     */
    protected function elaborar_consultar_select_filtros() {
        // Juntaremos los filtros en este arreglo
        $filtros = array();
        // El id del registro
        if ($this->hay_mas_tablas) {
            $filtros[] = $this->tabla_nombre.'.id = {$this->id}';
        } else {
            $filtros[] = 'id = {$this->id}';
        }
        // Si hay filtros impuestos, como texto
        if (is_string($this->adan->filtro_impuesto_sql) && ($this->adan->filtro_impuesto_sql != '')) {
            if (!in_array($this->adan->filtro_impuesto_sql, $filtros)) {
                $filtros[] = $this->adan->filtro_impuesto_sql;
            }
        // Si hay filtros impuestos, como arreglo
        } elseif (is_array($this->adan->filtro_impuesto_sql) && (count($this->adan->filtro_impuesto_sql) > 0)) {
            foreach ($this->adan->filtro_impuesto_sql as $f) {
                if (!in_array($f, $filtros)) {
                    $filtros[] = $f;
                }
            }
        }
        // Entregar
        return implode("\n                    AND ", $filtros);
    } // elaborar_consultar_select_filtros

    /**
     * Elaborar Consultar Select
     *
     * @return string Comando SQL
     */
    protected function elaborar_consultar_select() {
        // EJECUTAR LOS METODOS
        $tablas   = $this->elaborar_consultar_select_tablas();   // Puede levantar la bandera hay_mas_tablas, por eso va primero
        $columnas = $this->elaborar_consultar_select_columnas(); // Debe ir despues de elaborar_consultar_select_tablas
        $filtros  = $this->elaborar_consultar_select_filtros();  // Debe ir despues de elaborar_consultar_select_tablas
        // ENTREGAR
        return <<<FIN
            \$consulta = \$base_datos->comando("
                SELECT
                    $columnas
                FROM
                    $tablas
                WHERE
                    $filtros");
FIN;
    } // elaborar_consultar_select

    /**
     * Elaborar Consultar Propiedades
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_propiedades() {
        // En este arreglo juntaremos el código
        $a = array();
        // Vaciar el resultado de la consulta a la variable
        $a[] = "        // Resultado de la consulta";
        $a[] = "        \$a = \$consulta->obtener_registro();";
        // Insertar código PHP escrito en la semilla, para tronar si no cumple una condicion
        if (is_string($this->adan->registro_consultar_php) && ($this->adan->registro_consultar_php != '')) {
            $a[] = $this->adan->registro_consultar_php;
        }
        // Si se maneja estatus
        if (is_array($this->estatus)) {
            $a[] = "        // Validar que si esta eliminado tenga permiso para consultarlo";
            $a[] = "        if ((\$a['estatus'] == '{$this->estatus['eliminado']}') && !\$this->sesion->puede_recuperar('SED_CLAVE')) {";
            $a[] = "            throw new \Base\RegistroExceptionValidacion('Aviso: No tiene permiso de consultar un registro eliminado.');";
            $a[] = "        }";
        }
        // Definir propiedades
        $a[] = "        // Definir propiedades";
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['tipo'] == 'geopunto') {
                // Es de tipo geopunto
                $a[] = "        preg_match('/(\\-?[0-9]*\\.?[0-9]+) (\\-?[0-9]*\\.?[0-9]+)/', \$a['$columna'], \$resultados);";
                $a[] = "        \$this->{$columna}_longitud = \$resultados[1];";
                $a[] = "        \$this->{$columna}_latitud  = \$resultados[2];";
                $a[] = "        \$this->{$columna}_geojson  = \$a['{$columna}_geojson'];";
            } else {
                // Es de cualquier otro tipo
                $a[] = "        \$this->$columna = \$a['$columna'];";
                // Si es de tipo caracter
                if (($datos['tipo'] == 'caracter') || ($datos['tipo'] == 'nivel')) {
                    // Se agrega el descrito
                    $a[] = "        \$this->{$columna}_descrito = self::\${$columna}_descripciones[\$this->{$columna}];";
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_propiedades

    /**
     * Elaborar Consultar Propiedades Relacionadas
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_propiedades_relacionadas() {
        // En este arreglo juntaremos el código
        $a = array();
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Solo las columnas que sean relaciones
            if ($datos['tipo'] == 'relacion') {
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion  = $this->relaciones[$columna];
                    $instancia = $relacion['instancia_singular'];
                } else {
                    die("Error en Registro Consultar: Falta obtener datos de Serpiente para la relación $columna.");
                }
                // Comenzamos
                $a[] = "        // Asignar valores de la relación $columna";
                // Si validacion es dos (mayor a uno) es obligatorio
                if ($datos['validacion'] > 1) {
                    $a[] = "        \${$instancia} = new \\{$relacion['clase_plural']}\\Registro(\$this->sesion);";
                    $a[] = "        \${$instancia}->consultar(\$this->{$columna});";
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $a[] = "        \$this->{$columna}_{$relacion['vip']} = \${$instancia}->{$relacion['vip']};";
                    } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                        // Vip es un arreglo
                        foreach ($relacion['vip'] as $vip => $vip_datos) {
                            // Si es un arreglo
                            if (is_array($vip_datos)) {
                                // Si es una relacion
                                if ($vip_datos['tipo'] == 'relacion') {
                                    // Debe de existir en reptil esa relacion
                                    if (is_array($this->relaciones[$vip])) {
                                        // Si el vip es un arreglo
                                        if (is_array($this->relaciones[$vip]['vip'])) {
                                            // Ese vip es un arreglo
                                            foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                                // Para cualquier tipo copiamos
                                                $a[] = "        \$this->{$vip}_{$v} = \${$instancia}->{$vip}_{$v};";
                                                if ($vd['tipo'] == 'caracter') {
                                                    // Ese vip de la relacion es de tipo caracter
                                                    $a[] = "        \$this->{$vip}_{$v}_descrito = \${$instancia}->{$vip}_{$v}_descrito;";
                                                }
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $a[] = "        \$this->{$vip}_{$this->relaciones[$vip]['vip']} = \${$instancia}->{$vip}_{$this->relaciones[$vip]['vip']};";
                                        }
                                    } else {
                                        die("Error en Registro Consultar: No está definido el VIP en Serpiente para $vip.");
                                    }
                                } elseif ($vip_datos['tipo'] == 'caracter') {
                                    // Es caracter
                                    $a[] = "        \$this->{$columna}_{$vip}          = \${$instancia}->{$vip};";
                                    $a[] = "        \$this->{$columna}_{$vip}_descrito = \${$instancia}->{$vip}_descrito;";
                                } else {
                                    // Es cualquier otro tipo
                                    $a[] = "        \$this->{$columna}_{$vip} = \${$instancia}->{$vip};";
                                }
                            } else {
                                // Vip datos es un texto
                                $a[] = "        \$this->{$columna}_{$vip_datos} = \${$instancia}->{$vip_datos};";
                            }
                        }
                    }
                } else {
                    // Validacion es uno, es opcional, puede tener valor nulo
                    $a[] = "        if (\$this->{$columna} != '') {";
                    $a[] = "            \${$instancia} = new \\{$relacion['clase_plural']}\\Registro(\$this->sesion);";
                    $a[] = "            \${$instancia}->consultar(\$this->{$columna});";
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $a[] = "            \$this->{$columna}_{$relacion['vip']} = \${$instancia}->{$relacion['vip']};";
                    } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                        // Vip es un arreglo
                        foreach ($relacion['vip'] as $vip => $vip_datos) {
                            // Si es un arreglo
                            if (is_array($vip_datos)) {
                                // Si es una relacion
                                if ($vip_datos['tipo'] == 'relacion') {
                                    // Debe de existir en reptil esa relacion
                                    if (is_array($this->relaciones[$vip])) {
                                        // Si el vip es un arreglo
                                        if (is_array($this->relaciones[$vip]['vip'])) {
                                            // Ese vip es un arreglo
                                            foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                                // Para cualquier tipo copiamos
                                                $a[] = "            \$this->{$vip}_{$v} = \${$instancia}->{$vip}_{$v};";
                                                if ($vd['tipo'] == 'caracter') {
                                                    // Ese vip de la relacion es de tipo caracter
                                                    $a[] = "            \$this->{$vip}_{$v}_descrito = \${$instancia}->{$vip}_{$v}_descrito;";
                                                }
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $a[] = "            \$this->{$vip}_{$this->relaciones[$vip]['vip']} = \${$instancia}->{$this->relaciones[$vip]['vip']};";
                                        }
                                    } else {
                                        die("Error en Registro Consultar: No está definido el VIP en Serpiente para $vip.");
                                    }
                                } elseif ($vip_datos['tipo'] == 'caracter') {
                                    // Es caracter
                                    $a[] = "            \$this->{$columna}_{$vip}          = \${$instancia}->{$vip};";
                                    $a[] = "            \$this->{$columna}_{$vip}_descrito = \${$instancia}->{$vip}_descrito;";
                                } else {
                                    // Es cualquier otro tipo
                                    $a[] = "            \$this->{$columna}_{$vip} = \${$instancia}->{$vip};";
                                }
                            } else {
                                // Vip datos es un texto
                                $a[] = "            \$this->{$columna}_{$vip_datos} = \${$instancia}->{$vip_datos};";
                            }
                        }
                    }
                    // En el else, cuando la relacion no tenga valor, ponemos valores vacios
                    $a[] = "        } else {";
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $a[] = "            \$this->{$columna}_{$relacion['vip']} = '';";
                    } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                        // Vip es un arreglo
                        foreach ($relacion['vip'] as $vip => $vip_datos) {
                            // Si es un arreglo
                            if (is_array($vip_datos)) {
                                // Si es una relacion
                                if ($vip_datos['tipo'] == 'relacion') {
                                    // Debe de existir en reptil esa relacion
                                    if (is_array($this->relaciones[$vip])) {
                                        // Si el vip es un arreglo
                                        if (is_array($this->relaciones[$vip]['vip'])) {
                                            // Ese vip es un arreglo
                                            foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                                // Para cualquier tipo
                                                $a[] = "            \$this->{$vip}_{$v} = '';";
                                                if ($vd['tipo'] == 'caracter') {
                                                    // Ese vip de la relacion es de tipo caracter
                                                    $a[] = "            \$this->{$vip}_{$v}_descrito = '';";
                                                }
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $a[] = "            \$this->{$vip}_{$this->relaciones[$vip]['vip']} = '';";
                                        }
                                    } else {
                                        die("Error en Registro Consultar: No está definido el VIP en Serpiente para $vip.");
                                    }
                                } elseif ($vip_datos['tipo'] == 'caracter') {
                                    // Es caracter
                                    $a[] = "            \$this->{$columna}_{$vip}          = '';";
                                    $a[] = "            \$this->{$columna}_{$vip}_descrito = '';";
                                } else {
                                    // Es cualquier otro tipo
                                    $a[] = "            \$this->{$columna}_{$vip} = '';";
                                }
                            } else {
                                // Vip datos es un texto
                                $a[] = "            \$this->{$columna}_{$vip_datos} = '';";
                            }
                        }
                    }
                    // Termina if-else
                    $a[] = "        }";
                }
            }
        }
        // Entregar
        if (count($a) == 0) {
            return "        // Informo que no hay propiedades relacionadas";
        } else {
            return implode("\n", $a);
        }
    } // elaborar_consultar_propiedades_relacionadas

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FIN
    /**
     * Consultar
     *
     * @param integer ID del registro
     */
    public function consultar(\$in_id=false) {
        // Que tenga permiso para consultar
        if (!\$this->sesion->puede_ver('SED_CLAVE')) {
            throw new \Exception('Aviso: No tiene permiso para consultar SED_MENSAJE_PLURAL.');
        }
        // Parámetros
        if (\$in_id !== false) {
            \$this->id = \$in_id;
        }
        // Validar
        if (!\$this->validar_entero(\$this->id)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar SED_MENSAJE_SINGULAR por ID incorrecto.');
        }
        // Consultar
        \$base_datos = new \Base\BaseDatosMotor();
        try {
{$this->elaborar_consultar_select()}
        } catch (\Exception \$e) {
            throw new \Base\BaseDatosExceptionSQLError(\$this->sesion, 'Error SQL: Al consultar SED_MENSAJE_SINGULAR.', \$e->getMessage());
        }
        // Si la consulta no entrego nada
        if (\$consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró SED_MENSAJE_SINGULAR.');
        }
{$this->elaborar_consultar_propiedades()}
{$this->elaborar_consultar_propiedades_relacionadas()}
        // Ponemos como verdadero el flag de consultado
        \$this->consultado = true;
    } // consultar

FIN;
    } // php

} // Clase Consultar

?>
