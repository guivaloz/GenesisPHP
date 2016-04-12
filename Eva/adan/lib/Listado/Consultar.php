<?php
/**
 * GenesisPHP - Listado Consultar
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
 * Clase Consultar
 */
class Consultar extends \Base\Plantilla {

    /**
     * Elaborar Consultar Columnas
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_columnas() {
        // Entregar
        $a   = array();
        $a[] = "        // COLUMNAS";
        $a[] = '        $c   = array();';
        // Bandera hay relaciones
        $hay_relaciones = false;
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Solo las columnas que sean relaciones
            if ($datos['tipo'] == 'relacion') {
                $hay_relaciones = true;
            }
        }
        // Si hay relaciones
        if ($hay_relaciones) {
            // Escribiremos las columnas de la forma tabla.columna
            foreach ($this->tabla as $columna => $datos) {
                if ($datos['tipo'] == 'relacion') {
                    // Escribimos que tome la columna de la relacion misma, dando su id
                    $a[] = sprintf("        \$c[] = '%s.%s';", $this->tabla_nombre, $columna);
                    // Se va usar mucho la relacion, asi que para simplificar
                    if (is_array($this->relaciones[$columna])) {
                        $relacion = $this->relaciones[$columna];
                    } else {
                        die("Error en Listado, Consultar, elaborar_consultar_columnas: Falta obtener datos de Serpiente para la relación $columna.");
                    }
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $a[] = "        \$c[] = '{$relacion['tabla']}.{$relacion['vip']} AS {$columna}_{$relacion['vip']}';";
                    } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                        // Vip es un arreglo
                        foreach ($relacion['vip'] as $vip => $vip_datos) {
                            // Si es un arreglo
                            if (is_array($vip_datos)) {
                                // Si es una relacion
                                if ($vip_datos['tipo'] == 'relacion') {
                                    // Es una relacion y debe de existir en reptil
                                    if (is_array($this->relaciones[$vip])) {
                                        // La relacion misma
                                        $a[] = "        \$c[] = '{$relacion['tabla']}.{$vip} AS {$vip}'; // 8-)";
                                        // Si el vip es un arreglo
                                        if (is_array($this->relaciones[$vip]['vip'])) {
                                            // Ese vip es un arreglo
                                            foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                                $a[] = "        \$c[] = '{$this->relaciones[$vip]['tabla']}.{$v} AS {$vip}_{$v}'; // 8-)";
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $a[] = "        \$c[] = '{$this->relaciones[$vip]['tabla']}.{$vip} AS {$vip}'; // 8-)";
                                        }
                                    } else {
                                        die("Error en Listado, Consultar, elaborar_consultar_columnas: No está definido el VIP en Serpiente para $vip.");
                                    }
                                } else {
                                    // No es relacion, se agrega la columna
                                    $a[] = "        \$c[] = '{$relacion['tabla']}.{$vip} AS {$columna}_{$vip}'; // XD";
                                }
                            } else {
                                // Vip datos es un texto
                                $a[] = "        \$c[] = '{$relacion['tabla']}.{$vip_datos} AS {$columna}_{$vip_datos}'; // :-)";
                            }
                        }
                    } else {
                        die("Error en Listado, Consultar, elaborar_consultar_columnas: No está definido el 'vip' en Serpiente para la relación $columna.");
                    }
                } elseif ($datos['tipo'] == 'fecha_hora') {
                    // Columna de tipo fecha-hora
                    $a[] = "        \$c[] = 'to_char({$this->tabla_nombre}.{$columna}, \'YYYY-MM-DD HH24:MI\') AS {$columna}';";
                } elseif ($datos['tipo'] == 'geopunto') {
                    // Columna de tipo geopunto
                    $a[] = "        \$c[] = 'ST_AsText({$this->tabla_nombre}.{$columna}) as $columna';";
                    $a[] = "        \$c[] = 'ST_AsGeoJSON({$this->tabla_nombre}.{$columna}) as {$columna}_geojson';";
                } else {
                    // Columna de cualquier otro tipo
                    $a[] = "        \$c[] = '{$this->tabla_nombre}.{$columna}';";
                }
            }
        } else {
            // Escribiremos las columnas, de forma normal, sin el nombre de la tabla
            foreach ($this->tabla as $columna => $datos) {
                if ($datos['tipo'] == 'fecha_hora') {
                    // Columna de tipo fecha-hora
                    $a[] = "        \$c[] = 'to_char($columna, \'YYYY-MM-DD HH24:MI\') AS $columna';";
                } elseif ($datos['tipo'] == 'geopunto') {
                    // Columna de tipo geopunto
                    $a[] = "        \$c[] = 'ST_AsText($columna) as $columna';";
                    $a[] = "        \$c[] = 'ST_AsGeoJSON($columna) as {$columna}_geojson';";
                } else {
                    // Columna de cualquier otro tipo
                    $a[] = "        \$c[] = '$columna';";
                }
            }
        }
        // Final
        $a[] = "        // Fusionar las columnas";
        $a[] = "        \$columnas_sql = 'SELECT '.implode(', ', \$c);";
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_columnas

    /**
     * Elaborar Consultar Tablas
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_tablas() {
        // En este arreglo juntaremos los nombres de las tablas
        $t = array();
        // Primero se agrega la tabla de este modulo
        $t[] = $this->tabla_nombre;
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Solo las columnas que sean relaciones
            if ($datos['tipo'] == 'relacion') {
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion = $this->relaciones[$columna];
                } else {
                    die("Error en Listado, Consultar, elaborar_consultar_tablas: Falta obtener datos de Serpiente para la relación $columna.");
                }
                // Agregamos la tabla de esta relacion
                $t[] = $relacion['tabla'];
                // Solo si vip es un arreglo vamos a buscar si alguno de estos son relaciones
                if (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                    // Vip es un arreglo
                    foreach ($relacion['vip'] as $vip => $vip_datos) {
                        // Si es un arreglo
                        if (is_array($vip_datos)) {
                            if ($vip_datos['tipo'] == 'relacion') {
                                // Es relacion
                                if (is_array($this->relaciones[$vip])) {
                                    $t[] = $this->relaciones[$vip]['tabla'];
                                } else {
                                    die("Error en Listado, Consultar, elaborar_consultar_tablas: Falta declarar la relación para la columna $vip.");
                                }
                            }
                        } else {
                            // Vip datos es un texto
                            if (is_array($this->relaciones[$relacion['vip_datos']])) {
                                $t[] = $this->relaciones[$vip_datos]['tabla'];
                            }
                        }
                    }
                }
            }
        }
        // En la semilla se puede definir un fragmento de sql, que es una o varias tablas adicionales
        if (is_string($this->adan->tabla_impuesto_sql) && ($this->adan->tabla_impuesto_sql != '')) {
            // Para evitar que se duplique el nombre de la tabla, se agrega si no ha sido añadida
            if (!in_array($this->adan->tabla_impuesto_sql, $t)) {
                $t[] = $this->adan->tabla_impuesto_sql;
            }
        // Si es un arreglo
        } elseif (is_array($this->adan->tabla_impuesto_sql) && (count($this->adan->tabla_impuesto_sql) > 0)) {
            // Para asegurar que cada tabla aparezca solo una vez
            foreach ($this->adan->tabla_impuesto_sql as $tabla) {
                if (!in_array($tabla, $t)) {
                    $t[] = $tabla;
                }
            }
        }
        // En este arreglo juntaremos la salida
        $a   = array();
        $a[] = "        // Tablas";
        $a[] = "        \$t   = array();";
        foreach ($t as $tabla) {
            $a[] = "        \$t[] = '{$tabla}';";
        }
        $a[] = "        // Fusionar las tablas";
        $a[] = "        \$tablas_sql = 'FROM '.implode(', ', \$t);";
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_tablas

    /**
     * Elaborar Consultar Filtros Lógica
     *
     * @param   string  Tipo
     * @param   string  Columna de la tabla
     * @param   string  Variable
     * @param   integer Filtro 1 o 2
     * @returns string  Código PHP
     */
    protected function elaborar_consultar_filtros_logica($tipo, $c, $columna, $filtro) {
        // Funcion de validacion
        switch ($tipo) {
            case 'caracter':
                $logica = sprintf('%s = \'{$this->%s}\'', $c, $columna);
                break;
            case 'clave':
            case 'cuip':
            case 'curp':
            case 'nombre':
            case 'mayusculas':
            case 'notas':
            case 'nom_corto':
            case 'frase':
            case 'telefono':
            case 'variable':
            case 'email':
            case 'rfc':
                $logica = sprintf('%s ILIKE \'%%{$this->%s}%%\'', $c, $columna);
                break;
            case 'entero':
            case 'serial':
                if ($filtro > 1) {
                    $logica = array(
                        'desde' => sprintf('%s >= {$this->%s_desde}', $c, $columna),
                        'hasta' => sprintf('%s <= {$this->%s_hasta}', $c, $columna));
                } else {
                    $logica = sprintf('%s = {$this->%s}', $c, $columna);
                }
                break;
            case 'flotante':
            case 'dinero':
            case 'porcentaje':
                    case 'peso':
                    case 'estatura':
                        if ($datos['filtro'] > 1) {
                    $logica = array(
                        'desde' => sprintf('%s >= \'{$this->%s_desde}\'', $c, $columna),
                        'hasta' => sprintf('%s <= \'{$this->%s_hasta}\'', $c, $columna));
                } else {
                    $logica = sprintf('%s = \'{$this->%s}\'', $c, $columna);
                }
                break;
            case 'fecha':
                if ($filtro > 1) {
                    $logica = array(
                        'desde' => sprintf('%s >= \'{$this->%s_desde}\'', $c, $columna),
                        'hasta' => sprintf('%s <= \'{$this->%s_hasta}\'', $c, $columna));
                } else {
                    $logica = sprintf('%s = \'{$this->%s}\'', $c, $columna);
                }
                break;
            case 'fecha_hora':
                if ($filtro > 1) {
                    $logica = array(
                        'desde' => sprintf('%s >= \'{$this->%s_desde}\'', $c, $columna),
                        'hasta' => sprintf('%s <= \'{$this->%s_hasta}\'', $c, $columna));
                } else {
                    $logica = sprintf('%s = \'{$this->%s}\'', $c, $columna);
                }
                break;
            case 'relacion':
                $logica = sprintf('%s = {$this->%s}', $c, $columna);
                break;
            default:
                die("Error en Listado, Consultar, elaborar_consultar_filtros_logica: El tipo $tipo para $columna no tiene programada una lógica para elaborar el filtro .");
        }
        // Si hay funcion
        $a = array();
        if (is_string($logica) && ($logica != '')) {
            $a[] = "        if (\$this->{$columna} != '') {";
            $a[] = "            \$f[] = \"{$logica}\";";
            $a[] = "        }";
        } elseif (is_array($logica)) {
            foreach ($logica as $etiqueta => $f) {
                $a[] = "        if (\$this->{$columna}_{$etiqueta} != '') {";
                $a[] = "            \$f[] = \"{$f}\";";
                $a[] = "        }";
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_filtros_logica

    /**
     * Elaborar Consultar Filtros Relaciones
     *
     * @return string  Código PHP
     */
    protected function elaborar_consultar_filtros_relaciones() {
        $a   = array();
        $a[] = "        // Relaciones";
        foreach ($this->tabla as $columna => $datos) {
            // Solo las columnas que sean relaciones
            if ($datos['tipo'] == 'relacion') {
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion = $this->relaciones[$columna];
                } else {
                    die("Error en Listado: Falta obtener datos de Serpiente para la relación $columna.");
                }
                // Se agrega la relacion
                $a[] = "        \$f[] = '{$this->tabla_nombre}.{$columna} = {$relacion['tabla']}.id'; // Relacion";
                // Solo si vip es un arreglo vamos a buscar si alguno de estos son relaciones
                if (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                    // Vip es un arreglo
                    foreach ($relacion['vip'] as $vip => $vip_datos) {
                        // Si es un arreglo
                        if (is_array($vip_datos)) {
                            if ($vip_datos['tipo'] == 'relacion') {
                                // Es relacion
                                if (is_array($this->relaciones[$vip])) {
                                    $a[] = "        \$f[] = '{$relacion['tabla']}.{$vip} = {$this->relaciones[$vip]['tabla']}.id'; // RELACION DE RELACION (ARREGLO)";
                                } else {
                                    die("Error en Listado, Consultar, elaborar_consultar_filtros_relaciones: Falta obtener datos de Serpiente para la relación $vip.");
                                }
                            }
                        }
                    }
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_filtros_relaciones

    /**
     * Elaborar Consultar Filtros Columnas
     *
     * @param  boolean Verdadero si hay relaciones
     * @return string  Código PHP
     */
    protected function elaborar_consultar_filtros_columnas($hay_relaciones) {
        $a   = array();
        $a[] = "        // Filtros de las columnas";
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Si es filtro
            if ($datos['filtro'] > 0) {
                // Si hay relaciones
                if ($hay_relaciones) {
                    // Hay relaciones, entonces las columnas son de la forma tabla.columna
                    $c = sprintf('%s.%s', $this->tabla_nombre, $columna);
                } else {
                    // No hay relaciones, entonces solo el nombre de la columna
                    $c = $columna;
                }
                // Ejecutar logica
                $a[] = $this->elaborar_consultar_filtros_logica($datos['tipo'], $c, $columna, $datos['filtro']);
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_filtros_columnas

    /**
     * Elaborar Consultar Filtros Relaciones VIP
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_filtros_relaciones_vip() {
        $a   = array();
        $a[] = "        // Filtros de las relaciones";
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Si la columna es una relacion
            if ($datos['tipo'] == 'relacion') {
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion  = $this->relaciones[$columna];
                    $instancia = $relacion['instancia_singular'];
                } else {
                    die("Error en Listado, Consultar, elaborar_consultar_filtros_relaciones_vip: Falta obtener datos de Serpiente para la relación $columna.");
                }
                // Si vip es un arreglo
                if (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                    // Vip es un arreglo
                    foreach ($relacion['vip'] as $vip => $vip_datos) {
                        // Si es un arreglo
                        if (is_array($vip_datos)) {
                            // Si es una relacion
                            if ($vip_datos['tipo'] == 'relacion') {
                                // Es una relacion y debe de existir en reptil
                                if (is_array($this->relaciones[$vip])) {
                                    foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                        // Ejecutar logica
                                        $a[] = $this->elaborar_consultar_filtros_logica($vd['tipo'], "{$this->relaciones[$vip]['tabla']}.{$v}", "{$vip}_{$v}", $vd['filtro']);
                                    }
                                } else {
                                    die("Error en Listado, Consultar, elaborar_consultar_filtros_relaciones_vip: No está definido el VIP en Serpiente para $vip.");
                                }
                            } else {
                                // Ejecutar logica
                                $a[] = $this->elaborar_consultar_filtros_logica($vip_datos['tipo'], "{$relacion['tabla']}.{$vip}", "{$columna}_{$vip}", $vip_datos['filtro']);
                            }
                        }
                    }
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_filtros_relaciones_vip

    /**
     * Elaborar Consultar Filtros
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_filtros() {
        // Bandera si hay relaciones o no
        $hay_relaciones = false;
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['tipo'] == 'relacion') {
                $hay_relaciones = true;
            }
        }
        // Juntaremos en este arreglo el codigo
        $a   = array();
        $a[] = "        // Juntaremos los filtros en este arreglo";
        $a[] = "        \$f = array();";
        // Primero las relaciones
        if ($hay_relaciones) {
            $a[] = $this->elaborar_consultar_filtros_relaciones();
        }
        // Luego los filtros de las columnas
        $a[] = $this->elaborar_consultar_filtros_columnas($hay_relaciones);
        // Luego los filtros de las relaciones
        $a[] = $this->elaborar_consultar_filtros_relaciones_vip();
        // En la semilla se puede definir un fragmento de sql, que es un filtro para el where
        if (is_string($this->adan->filtro_impuesto_sql) && ($this->adan->filtro_impuesto_sql != '')) {
            $a[] = "        // Filtro sql impuesto";
            $a[] = "        \$f[] = \"{$this->adan->filtro_impuesto_sql}\";";
        } elseif (is_array($this->adan->filtro_impuesto_sql) && (count($this->adan->filtro_impuesto_sql) > 0)) {
            $a[] = "        // Filtro sql impuesto";
            foreach ($this->adan->filtro_impuesto_sql as $f) {
                $a[] = "        \$f[] = \"$f\";";
            }
        }
        // En la semilla se puede definir un fragmento de codigo, para filtrar si cumple una condicion
        if (is_string($this->adan->listado_consultar_php) && ($this->adan->listado_consultar_php != '')) {
            $a[] = $this->adan->listado_consultar_php;
        }
        // Juntar
        $a[] = "        // Fusionar los filtros";
        // Si hay relaciones
        if ($hay_relaciones) {
            // Como hay relaciones, siempre tendra where
            $a[] = "        \$filtros_sql = 'WHERE '.implode(' AND ', arreglo_sin_valores_repetidos(\$f));";
        } else {
            // Hay posibilidad de que no se use ningun filtro, por lo que where puede usarse o no
            $a[] = "        if (count($f) > 0) {";
            $a[] = "            \$filtros_sql = 'WHERE '.implode(' AND ', arreglo_sin_valores_repetidos(\$f));";
            $a[] = "        } else {";
            $a[] = "            \$filtros_sql = '';";
            $a[] = "        }";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_filtros

    /**
     * Elaborar Consultar Orden
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_orden() {
        // Recolectar de cada dato las columnas en orden ascendente (positivas) y descendente (negativas)
        $ascendente  = array();
        $descendente = array();
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['orden'] > 0) {
                if (is_array($this->relaciones) && (count($this->relaciones) > 0)) {
                    $ascendente[$datos['orden']] = $this->tabla_nombre.'.'.$columna;
                } else {
                    $ascendente[$datos['orden']] = $columna;
                }
            } elseif ($datos['orden'] < 0) {
                if (is_array($this->relaciones) && (count($this->relaciones) > 0)) {
                    $descendente[$datos['orden']] = $this->tabla_nombre.'.'.$columna;
                } else {
                    $descendente[$datos['orden']] = $columna;
                }
            }
        }
        // Ordenarlos
        ksort($ascendente);
        ksort($descendente);
        // Iniciar arreglo para lo que se va a entregar
        $a   = array();
        $a[] = "        // Orden";
        // Si hay
        if ((count($ascendente) > 0) || (count($descendente) > 0)) {
            $o = array();
            if (count($ascendente) > 0) {
                $o[] = implode(", ", $ascendente).' ASC';
            }
            if (count($descendente) > 0) {
                $o[] = implode(", ", $descendente).' DESC';
            }
            $a[] = sprintf("        \$orden_sql = \"ORDER BY %s\"", implode(", ", $o));
        } else {
            $a[] = "        \$orden_sql = \"\"; // No fue declarado orden en la semilla";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_orden

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Consultar
     */
    public function consultar() {
        // Validar
        \$this->validar();
{$this->elaborar_consultar_columnas()}
{$this->elaborar_consultar_tablas()}
{$this->elaborar_consultar_filtros()}
{$this->elaborar_consultar_orden()}
        // Consultar
        \$base_datos = new \\Base\\BaseDatosMotor();
        try {
            \$consulta = \$base_datos->comando("\$columnas_sql \$tablas_sql \$filtros_sql \$orden_sql ".\$this->limit_offset_sql());
        } catch (Exception \$e) {
            throw new \\Base\\BaseDatosExceptionSQLError(\$this->sesion, 'Error: Al consultar SED_MENSAJE_PLURAL para hacer listado.', \$e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if (\$consulta->cantidad_registros() == 0) {
            throw new \\Base\\ListadoExceptionVacio('Aviso: No se encontraron registros en SED_MENSAJE_PLURAL.');
        }
        // Pasamos la consulta a la propiedad listado
        \$this->listado = \$consulta->obtener_todos_los_registros();
        // Consultar la cantidad de registros
        if ((\$this->limit > 0) && (\$this->cantidad_registros == 0)) {
            try {
                \$consulta = \$base_datos->comando("SELECT COUNT(*) AS cantidad \$tablas_sql \$filtros_sql");
            } catch (Exception \$e) {
                throw new \\Base\\BaseDatosExceptionSQLError(\$this->sesion, 'Error: Al consultar SED_MENSAJE_PLURAL para determinar la cantidad de registros.', \$e->getMessage());
            }
            \$a = \$consulta->obtener_registro();
            \$this->cantidad_registros = intval(\$a['cantidad']);
        }
    } // consultar

FINAL;
    } // php

} // Clase Consultar

?>
