<?php
/**
 * GenesisPHP - Listado Validar
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
 * Clase Validar
 */
class Validar extends \Base\Plantilla {

    /**
     * Elaborar Validar Estatus
     *
     * @return string Código PHP
     */
    protected function elaborar_validar_estatus() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Si se usa en estatus "en uso" y "eliminado"
        if ($this->estatus['eliminado'] != '') {
            $a[] = "        if (\$this->estatus != '') {";
            $a[] = "            // Viene el filtro por estatus, si no tiene permiso para recuperar, entonces arrojar error si trata de ver eliminados";
            $a[] = "            if ((\$this->estatus == '{$this->estatus['eliminado']}') && !\$this->sesion->puede_recuperar('SED_CLAVE')) {";
            $a[] = "                throw new \\Exception('Aviso: No tiene permiso para ver los registros eliminados.');";
            $a[] = "            } elseif (!array_key_exists(\$this->estatus, Registro::\$estatus_descripciones)) {";
            $a[] = "                throw new \\Base2\\ListadoExceptionValidacion('Aviso: Estatus incorrecto.');";
            $a[] = "            }";
            if ($this->estatus['enuso'] != '') {
                $a[] = "        } elseif (!\$this->sesion->puede_recuperar('SED_CLAVE')) {";
                $a[] = "            // No viene el filtro estatus, pero no tiene permiso para recuperar, entonces se impone ver los registros en uso";
                $a[] = "            \$this->estatus = '{$this->estatus['enuso']}';";
            }
            $a[] = "        }";
        } else {
            // Estatus no es "en uso"/"eliminado", entonces se valida el caracter
            $a[] = "        if (\$this->estatus != '') {";
            $a[] = "            // Viene el filtro por estatus, no es 'en uso'/'eliminado', se valida como caracter";
            $a[] = "            if (!array_key_exists(\$this->estatus, Registro::\$estatus_descripciones)) {";
            $a[] = "                throw new \\Base2\\ListadoExceptionValidacion('Aviso: Estatus incorrecto.');";
            $a[] = "            }";
            $a[] = "        }";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_validar_estatus

    /**
     * Elaborar Validar Relacion
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_validar_relacion($columna, $datos) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Se va usar mucho la relacion, asi que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion  = $this->relaciones[$columna];
            $instancia = '$'.$relacion['instancia_singular'];
        } else {
            die("Error en Listado, validar, elaborar_validar_relacion: Falta obtener datos de Serpiente para la relación $columna.");
        }
        // Inicia
        $a[] = "        // Validar la relacion $columna, inicia";
        $a[] = "        if (\$this->{$columna} != '') {";
        $a[] = "            {$instancia} = new \\{$relacion['clase_plural']}\\Registro(\$this->sesion);";
        $a[] = "            {$instancia}->consultar(\$this->{$columna});";
        // A continuacion, la parte complicada que viaja a traves de las relaciones
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = "            \$this->{$columna}_{$relacion['vip']} = {$instancia}->{$relacion['vip']}; // Segundo nivel";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                $a[] = "            \$this->{$vip} = {$instancia}->{$vip}; // Tercer nivel";
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = "            \$this->{$vip}_{$v} = {$instancia}->{$vip}_{$v}; // Tercer nivel";
                                    if ($vd['tipo'] == 'caracter') {
                                        $a[] = "            \$this->{$vip}_{$v}_descrito = {$instancia}->{$vip}_{$v}_descrito; // Tercer nivel";
                                    }
                                }
                            } else {
                                $a[] = "            \$this->{$vip}_{$this->relaciones[$vip]['vip']} = {$instancia}->{$vip}_{$this->relaciones[$vip]['vip']}; // Tercer nivel";
                            }
                        } else {
                            die("Error en Listado, validar, elaborar_validar_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } elseif ($vip_datos['tipo'] == 'caracter') {
                        $a[] = "            \$this->{$columna}_{$vip}          = {$instancia}->{$vip}; // Segundo nivel";
                        $a[] = "            \$this->{$columna}_{$vip}_descrito = {$instancia}->{$vip}_descrito; // Segundo nivel";
                    } else {
                        $a[] = "            \$this->{$columna}_{$vip} = {$instancia}->{$vip}; // Segundo nivel";
                    }
                } else {
                    $a[] = "            \$this->{$columna}_{$vip_datos} = {$instancia}->{$vip_datos}; // Segundo nivel";
                }
            }
        }
        // La relacion debe tener un estatus que no sea eliminado, cuando el usuario no se tenga permiso de recuperar
        if (is_array($relacion['estatus'])) {
                $a[] = "            // Si el padre esta eliminado y no tiene permiso";
                $a[] = "            if (({$instancia}->estatus == '{$relacion['estatus']['eliminado']}') && !\$this->sesion->puede_recuperar('{$relacion['clave']}')) {";
                $a[] = "                throw new \\Base2\\ListadoExceptionValidacion('Aviso: El(la) {$relacion['etiqueta_singular']} está eliminado(a).');";
                $a[] = "            }";
        }
        // Si la relacion no tiene valor, entonces se validaran los otros filtros que cuelgan de esta
        $a[] = "        } else {";
        // A continuacion, la parte complicada que viaja a traves de las relaciones
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = "            // Pendiente la validacion de {$relacion['vip']}, segundo nivel";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                $relacion2  = $this->relaciones[$vip];
                                $instancia2 = '$'.$relacion2['instancia_singular'];
                                $a[] = "            // Validar {$vip}, segundo nivel";
                                $a[] = "            if (\$this->{$vip} != '') {";
                                $a[] = "                {$instancia2} = new \\{$relacion2['clase_plural']}\\Registro(\$this->sesion);";
                                $a[] = "                {$instancia2}->consultar(\$this->{$vip});";
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = "                \$this->{$vip}_{$v} = {$instancia2}->{$vip}_{$v};";
                                }
                                $a[] = "            } else {";
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $resultado = $this->elaborar_validar_declaracion("{$vip}_{$v}", $vd, 3, $this->relaciones[$vip], $v);
                                    if ($resultado !== false) {
                                        $a[] = "                // Validar {$vip}_{$v}, tercer nivel";
                                        $a[] = $resultado;
                                    }
                                }
                                $a[] = "            }";
                            } else {
                                $a[] = "            // Pendiente la validacion de {$vip}_{$this->relaciones[$vip]['vip']}, segundo nivel";
                            }
                        } else {
                            die("Error en Listado, validar, elaborar_validar_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } else {
                        $resultado = $this->elaborar_validar_declaracion("{$columna}_{$vip}", $vip_datos, 2, $relacion, $vip);
                        if ($resultado !== false) {
                            $a[] = "            // Validar {$columna}_{$vip}, segundo nivel";
                            $a[] = $resultado;
                        }
                    }
                } else {
                    $a[] = "            // Pendiente la validacion de {$columna}_{$vip_datos}, segundo nivel";
                }
            }
        }
        $a[] = "        }";
        // Termina
        $a[] = "        // Validar la relacion $columna, termina";
        // Entregar
        return implode("\n", $a);
    } // elaborar_validar_relacion

    /**
     * Elaborar Validar Declaración
     *
     * @param  string  Columna de la tabla
     * @param  array   Opcional. Datos declarados para esa columna en la semilla
     * @param  integer Opcional. Número de nivel, use 2 o 3, por defecto 1. Agrega espacios al inicio de cada línea.
     * @param  mixed   Opcional. Si este campo es de una relación, se debe dar la misma.
     * @param  string  Opcional. Columna de la tabla relacionada.
     * @return string  Código PHP
     */
    protected function elaborar_validar_declaracion($columna, $datos=false, $nivel=1, $relacion=null, $relacion_columna=null) {
        // Si no vienen los datos, por defecto el filtro es uno
        if ($datos === false) {
            $datos = array('filtro' => 1);
        }
        // Si filtro es mayor a uno es un rango desde-hasta
        if ($datos['filtro'] > 1) {
            switch ($datos['tipo']) {
                case 'boleano':
                    die("Error en Listado, validar, elaborar_validar_declaracion: El tipo boleano en $columna no puede validarse como rango.");
                    break;
                case 'entero':
                case 'serial':
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_entero(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_entero(\$this->{$columna}_hasta)");
                    break;
                case 'flotante':
                case 'dinero':
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_flotante(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_flotante(\$this->{$columna}_hasta)");
                    break;
                case 'porcentaje':
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_porcentaje(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_porcentaje(\$this->{$columna}_hasta)");
                    break;
                case 'peso':
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_peso(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_peso(\$this->{$columna}_hasta)");
                    break;
                case 'estatura':
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_estatura(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_estatura(\$this->{$columna}_hasta)");
                    break;
                case 'fecha':
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_fecha(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_fecha(\$this->{$columna}_hasta)");
                    break;
                case 'fecha_hora':
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_fecha_hora(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_fecha_hora(\$this->{$columna}_hasta)");
                    break;
                case 'geopunto':
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_geopunto(\$this->{$columna}_desde_longitud, \$this->{$columna}_desde_latitud)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_geopunto(\$this->{$columna}_hasta_longitud, \$this->{$columna}_hasta_latitud)");
                    break;
                default:
                    die("Error en Listado, validar, elaborar_validar_declaracion: El tipo {$datos['tipo']} en $columna no puede validarse como rango.");
            }
        // Si hay filtro
        } elseif ($datos['filtro'] > 0) {
            switch ($datos['tipo']) {
                case 'boleano':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_boleano(\$this->{$columna})";
                    break;
                case 'caracter':
                    if (is_array($relacion) && ($relacion['clase_plural'] != '')) {
                        $funcion = "!array_key_exists(\$this->{$columna}, \\{$relacion['clase_plural']}\\Registro::\${$relacion_columna}_descripciones)";
                    } else {
                        $funcion = "!array_key_exists(\$this->{$columna}, Registro::\${$columna}_descripciones)";
                    }
                    break;
                case 'clave':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_clave(\$this->{$columna})";
                    break;
                case 'cuip':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_cuip(\$this->{$columna})";
                    break;
                case 'curp':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_curp(\$this->{$columna})";
                    break;
                case 'rfc':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_rfc(\$this->{$columna})";
                    break;
                case 'email':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_email(\$this->{$columna})";
                    break;
                case 'nombre':
                case 'mayusculas':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_nombre(\$this->{$columna})";
                    break;
                case 'notas':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_notas(\$this->{$columna})";
                    break;
                case 'nom_corto':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_nom_corto(\$this->{$columna})";
                    break;
                case 'frase':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_frase(\$this->{$columna})";
                    break;
                case 'telefono':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_telefono(\$this->{$columna})";
                    break;
                case 'variable':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_variable(\$this->{$columna})";
                    break;
                case 'entero':
                case 'serial':
                        $funcion = "!\\Base2\\UtileriasParaValidar::validar_entero(\$this->{$columna})";
                    break;
                case 'flotante':
                case 'dinero':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_flotante(\$this->{$columna})";
                    break;
                case 'porcentaje':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_porcentaje(\$this->{$columna})";
                    break;
                case 'peso':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_peso(\$this->{$columna})";
                    break;
                case 'estatura':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_estatura(\$this->{$columna})";
                    break;
                case 'fecha':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_fecha(\$this->{$columna})";
                    break;
                case 'fecha_hora':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_fecha_hora(\$this->{$columna})";
                    break;
                case 'geopunto':
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_geopunto(\$this->{$columna}_longitud, \$this->{$columna}_latitud)";
                    break;
                case 'relacion':
                    $funcion = false;
                    break;
                default:
                    die("Error en Listado, validar, elaborar_validar_declaracion: El tipo {$datos['tipo']} en $columna no se puede validar.");
            }
        } else {
            die("Error en Listado, validar, elaborar_validar_declaracion: No hay valor en filtro para $columna.");
        }
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Espacios a poner al principio
        $espacios = str_repeat(' ', $nivel*4);
        // Entregar
        if ($funcion === false) {
            return false;
        } elseif (is_array($funcion)) {
            // Varias funciones son varias validaciones
            foreach ($funcion as $c => $f) {
                $a[] = "$espacios    if ((\$this->{$columna}_{$c} != '') && $f) {";
                $a[] = "$espacios        throw new \\Base2\\ListadoExceptionValidacion('Aviso: {$datos['etiqueta']} $c incorrecto(a).');";
                $a[] = "$espacios    }";
            }
            return implode("\n", $a);
        } elseif (is_string($funcion) && ($funcion != '')) {
            // Una funcion es una validacion
            $a[] = "$espacios    if ((\$this->{$columna} != '') && $funcion) {";
            $a[] = "$espacios        throw new \\Base2\\ListadoExceptionValidacion('Aviso: {$datos['etiqueta']} incorrecto(a).');";
            $a[] = "$espacios    }";
            return implode("\n", $a);
        }
    } // elaborar_validar_declaracion

    /**
     * Elaborar validaciones
     *
     * @return string Código PHP
     */
    protected function elaborar_validaciones() {
        // Validaciones: bucle a traves todas las columnas de tabla
        $v = array();
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no aparece en el formulario
            } elseif ($columna == 'estatus') {
                $v[] = $this->elaborar_validar_estatus();
            } elseif ($datos['tipo'] == 'relacion') {
                $v[] = $this->elaborar_validar_relacion($columna, $datos);
            } else {
                $v[] = $this->elaborar_validar_declaracion($columna, $datos);
            }
        }
        // Validaciones: tronar en caso de no haber
        if (count($v) > 0) {
            return implode("\n", $v);
        } else {
            return "// Sin columnas que validar"; // die("Error en Listado, Validar, elaborar_validaciones: No hay columnas para validar.");
        }
    } // elaborar_validaciones

    /**
     * Elaborar Validar Parámetros Estatus
     *
     * @return string Código PHP
     */
    protected function elaborar_validar_parametros_estatus() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Parametro estatus
        $a[] = "        if (\$this->estatus != '') {";
        $a[] = "            \$this->filtros_param[self::\$param_estatus] = \$this->estatus;";
        $a[] = "        }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_validar_parametros_estatus

    /**
     * Elaborar Validar Parámetros Relación
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_validar_parametros_relacion($columna, $datos) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // De inicio la relacion misma
        $a[] = "        if (\$this->{$columna} != '') {";
        $a[] = "            \$this->filtros_param[self::\$param_{$columna}] = \$this->{$columna};";
        $a[] = "        } else {";
        // Se va usar mucho la relacion, asi que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion  = $this->relaciones[$columna];
        } else {
            die("Error en Listado, validar, elaborar_validar_parametros_relacion: Falta obtener datos de Serpiente para la relación $columna.");
        }
        // A continuacion, la parte complicada que viaja a traves de las relaciones
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = $this->elaborar_validar_parametros_campo("{$columna}_{$relacion['vip']}");
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                $a[] = $this->elaborar_validar_parametros_campo($vip, $this->relaciones[$vip]['vip']);
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = $this->elaborar_validar_parametros_campo("{$vip}_{$v}", $vd);
                                }
                            } else {
                                $a[] = $this->elaborar_validar_parametros_campo("{$vip}_{$this->relaciones[$vip]['vip']}");
                            }
                        } else {
                            die("Error en Listado, validar, elaborar_validar_parametros_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } else {
                        $a[] = $this->elaborar_validar_parametros_campo("{$columna}_{$vip}", $vip_datos);
                    }
                } else {
                    $a[] = $this->elaborar_validar_parametros_campo("{$columna}_{$vip_datos}");
                }
            }
        }
        $a[] = "        }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_validar_parametros_relacion

    /**
     * Elaborar Validar Parámetros Campo
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_validar_parametros_campo($columna, $datos) {
        // Se omite el tipo relacion
        if ($datos['tipo'] == 'relacion') {
            return "        // Se omite $columna porque es de tipo relacion";
        }
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Segun el filtro
        if ($datos['filtro'] > 1) {
            $a[] = "        if (\$this->{$columna}_desde != '') {";
            $a[] = "            \$this->filtros_param[self::\$param_{$columna}_desde] = \$this->{$columna}_desde;";
            $a[] = "        }";
            $a[] = "        if (\$this->{$columna}_hasta != '') {";
            $a[] = "            \$this->filtros_param[self::\$param_{$columna}_hasta] = \$this->{$columna}_hasta;";
            $a[] = "        }";
        } elseif ($datos['filtro'] > 0) {
            $a[] = "        if (\$this->{$columna} != '') {";
            $a[] = "            \$this->filtros_param[self::\$param_{$columna}] = \$this->{$columna};";
            $a[] = "        }";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_validar_parametros_campo

    /**
     * Elaborar parametros
     *
     * @return string Código PHP
     */
    protected function elaborar_parametros() {
        // Parametros: bucle a traves todas las columnas de tabla
        $p   = array();
        $p[] = '        // Arreglo asociativo con los parametros de la forma variable => valor';
        $p[] = "        \$this->filtros_param = array();";
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no aparece en el formulario
            } elseif ($columna == 'estatus') {
                $p[] = $this->elaborar_validar_parametros_estatus();
            } elseif ($datos['tipo'] == 'relacion') {
                $p[] = $this->elaborar_validar_parametros_relacion($columna, $datos);
            } else {
                $p[] = $this->elaborar_validar_parametros_campo($columna, $datos);
            }
        }
        // Parametros: tronar en caso de no haber
        if (count($p) > 0) {
            return implode("\n", $p);
        } else {
            die("Error en Listado, validar, elaborar_parametros: No hay columnas para pasar parámetros.");
        }
    } // elaborar_parametros

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Entregar
        return <<<FINAL
    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        if (!\$this->sesion->puede_ver('SED_CLAVE')) {
            throw new \\Exception('Aviso: No tiene permiso para ver SED_MENSAJE_PLURAL.');
        }
        // Validar los filtros
{$this->elaborar_validaciones()}
{$this->elaborar_parametros()}
        // Ejecutar padre
        parent::validar();
    } // validar

FINAL;
    } // php

} // Clase Validar

?>
