<?php
/**
 * GenesisPHP - Registro Validar
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
 * Clase Validar
 */
class Validar extends \Base\Plantilla {

    /**
     * Elaborar Validar Validaciones
     *
     * @return string Código PHP
     */
    protected function elaborar_validar_validaciones() {
        // En este arreglo juntaremos el código
        $a   = array();
        $a[] = "        // Validamos las propiedades";
        $a[] = "        if ((\$in_particular == '') || (\$in_particular == 'propiedades')) {";
        // Para cada columna
        foreach ($this->tabla as $columna => $datos) {
            // Se validan si tenen la propiedad en uno o mayor
            if (is_int($datos['validacion']) && ($datos['validacion'] > 0)) {
                // Se va a validar
                if ($datos['tipo'] == 'relacion') {
                    continue; // Las relaciones se validan más adelante
                } else {
                    // Para todo lo demás
                    switch ($datos['tipo']) {
                        case 'boleano':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_boleano(\$this->{$columna})";
                            break;
                        case 'caracter':
                            $funcion = "!array_key_exists(\$this->{$columna}, self::\${$columna}_descripciones)";
                            break;
                        case 'clave':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_clave(\$this->{$columna})";
                            break;
                        case 'contraseña':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_contrasena(\$this->{$columna})";
                            break;
                        case 'cuip':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_cuip(\$this->{$columna})";
                            break;
                        case 'curp':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_curp(\$this->{$columna})";
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
                        case 'variable':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_variable(\$this->{$columna})";
                            break;
                        case 'entero':
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
                        case 'rfc':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_rfc(\$this->{$columna})";
                            break;
                        case 'telefono':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_telefono(\$this->{$columna})";
                            break;
                        case 'geopunto':
                            $funcion = "!\\Base2\\UtileriasParaValidar::validar_geopunto(\$this->{$columna}_longitud, \$this->{$columna}_latitud)";
                            break;
                        default:
                            die("Error en Registro Validar: Tipo de dato {$datos['tipo']} no programado en elaborar_validaciones_php");
                    }
                    // Se agrega si es opcional o es obligatoria
                    if (is_int($datos['validacion']) && ($datos['validacion'] == 1)) {
                        // Si es geopunto
                        if ($datos['tipo'] == 'geopunto') {
                            // Es opcional y es geopunto
                            $a[] = "            if ((\$this->{$columna}_longitud != '') || (\$this->{$columna}_latitud != '')) {";
                            $a[] = "                if ({$funcion}) {";
                            $a[] = "                    throw new \\Base2\\RegistroExceptionValidacion('Aviso: {$datos['etiqueta']} incorrecto(a).');";
                            $a[] = "                }";
                            $a[] = "            }";
                        } else {
                            // Es opcional y de cualquier otro tipo
                            $a[] = "            if ((\$this->{$columna} != '') && {$funcion}) {";
                            $a[] = "                throw new \\Base2\\RegistroExceptionValidacion('Aviso: {$datos['etiqueta']} incorrecto(a).');";
                            $a[] = "            }";
                        }
                    } elseif (is_int($datos['validacion']) && ($datos['validacion'] == 2)) {
                        // Es obligatoria
                        $a[] = "            if ({$funcion}) {";
                        $a[] = "                throw new \\Base2\\RegistroExceptionValidacion('Aviso: {$datos['etiqueta']} incorrecto(a).');";
                        $a[] = "            }";
                    }
                }
            } elseif (is_string($datos['validacion']) && ($datos['validacion'] != '')) {
                // Es texto, debe contener una operacion
                $a[] = "            \$this->{$columna} = {$datos['validacion']}; // Operacion definida en la semilla";
            }
        }
        $a[] = "        }"; // Termina if propiedades
        // Entregar
        return implode("\n", $a);
    } // elaborar_validar_validaciones

    /**
     * Elaborar Validar Relaciones
     *
     * @return string Código PHP
     */
    protected function elaborar_validar_relaciones() {
        $contador = 0;
        // En este arreglo juntaremos el código
        $a   = array();
        $a[] = "        // Validamos las relaciones";
        $a[] = "        if ((\$in_particular == '') || (\$in_particular == 'relaciones')) {";
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Solo las relaciones y si se validan
            if (is_int($datos['validacion']) && ($datos['validacion'] > 0) && ($datos['tipo'] == 'relacion')) {
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion  = $this->relaciones[$columna];
                    $instancia = $relacion['instancia_singular'];
                } else {
                    die("Error en Registro Validar: Falta obtener datos de Serpiente para la relación $columna.");
                }
                // Si es obligatoria
                if ($datos['validacion'] > 1) {
                    // Comenzamos a escribir la validación
                    $a[] = "            // Validar la relación $columna";
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
                                                // Es cualquier otro tipo
                                                $a[] = "            \$this->{$vip}_{$v} = \${$instancia}->{$vip}_{$v};";
                                                if ($vd['tipo'] == 'caracter') {
                                                    // Es caracter, se usa el descrito
                                                    $a[] = "            \$this->{$vip}_{$v}_descrito = \${$instancia}->{$vip}_{$v}_descrito;";
                                                }
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $a[] = "            \$this->{$vip}_{$this->relaciones[$vip]['vip']} = \${$instancia}->{$vip}_{$this->relaciones[$vip]['vip']};";
                                        }
                                    } else {
                                        die("Error en Registro Validar: No está definido el VIP en Serpiente para $vip.");
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
                    $contador++;
                } else {
                    // Es opcional
                    $a[] = "            // Validar la relación $columna";
                    $a[] = "            if (\$this->{$columna} != '') {";
                    $a[] = "                \${$instancia} = new \\{$relacion['clase_plural']}\\Registro(\$this->sesion);";
                    $a[] = "                \${$instancia}->consultar(\$this->{$columna});";
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $a[] = "                \$this->{$columna}_{$relacion['vip']} = \${$instancia}->{$relacion['vip']};";
                    } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                        // Vip es un arreglo
                        foreach ($relacion['vip'] as $vip => $vip_datos) {
                            // Si es un arreglo
                            if (is_array($vip_datos)) {
                                // Si es una relación
                                if ($vip_datos['tipo'] == 'relacion') {
                                    // Es una relación y debe de existir en reptil
                                    if (is_array($this->relaciones[$vip])) {
                                        // Si el vip es un arreglo
                                        if (is_array($this->relaciones[$vip]['vip'])) {
                                            // Ese vip es un arreglo
                                            foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                                // Es cualquier otro tipo
                                                $a[] = "            \$this->{$vip}_{$v} = \${$instancia}->{$vip}_{$v};";
                                                if ($vd['tipo'] == 'caracter') {
                                                    // Es caracter, se usa el descrito
                                                    $a[] = "            \$this->{$vip}_{$v}_descrito = \${$instancia}->{$vip}_{$v}_descrito;";
                                                }
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $a[] = "            \$this->{$vip}_{$this->relaciones[$vip]['vip']} = \${$instancia}->{$vip}_{$this->relaciones[$vip]['vip']};";
                                        }
                                    } else {
                                        die("Error en Registro Validar: No está definido el VIP en Serpiente para $vip.");
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
                    $a[] = "            } else {";
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $a[] = "                \$this->{$columna}_{$relacion['vip']} = '';";
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
                                                // Es cualquier otro tipo
                                                $a[] = "            \$this->{$vip}_{$v} = '';";
                                                if ($vd['tipo'] == 'caracter') {
                                                    // Es caracter, se usa el descrito
                                                    $a[] = "            \$this->{$vip}_{$v}_descrito = '';";
                                                }
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $a[] = "            \$this->{$vip}_{$this->relaciones[$vip]['vip']} = '';";
                                        }
                                    } else {
                                        die("Error en Registro Validar: No está definido el VIP en Serpiente para $vip.");
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
                                // Agregar propiedad que jala esta relacion
                                $a[] = "                \$this->{$columna}_{$vip} = '';";
                            }
                        }
                    }
                    // Termina if-else
                    $a[] = "            }";
                    $contador++;
                }
            }
        }
        $a[] = "        }"; // Termina if relaciones
        // Entregar solo si hay contenido
        if ($contador > 0) {
            return implode("\n", $a);
        }
    } // elaborar_validar_relaciones

    /**
     * Elaborar Validar Descritos
     *
     * @return string Código PHP
     */
    protected function elaborar_validar_descritos() {
        $contador = 0;
        // En este arreglo juntaremos el código
        $a   = array();
        $a[] = "        // Definimos los descritos";
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['tipo'] == 'caracter') {
                $a[] = "        \$this->".str_pad("{$columna}_descrito", $this->columnas_caracteres_maximo)." = self::\${$columna}_descripciones[\$this->{$columna}];";
                $contador++;
            }
        }
        // Entregar solo si hay contenido
        if ($contador > 0) {
            return implode("\n", $a);
        }
    } // elaborar_validar_descritos

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // No hacer nada si no hay que crear formulario
        if ($this->adan->si_hay_que_crear('formulario')) {
            // En este arreglo juntaremos el php
            $a = array();
            // Ejecutar cada metodo que hace su parte de validar
            $validaciones_php = $this->elaborar_validar_validaciones();
            $descritos_php    = $this->elaborar_validar_descritos();
            $relaciones_php   = $this->elaborar_validar_relaciones();
            // Agregar solo si tiene contenido
            if ($relaciones_php != '')   $a[] = $relaciones_php;
            if ($validaciones_php != '') $a[] = $validaciones_php;
            if ($descritos_php != '')    $a[] = $descritos_php;
            // Entregar
            $validaciones = implode("\n", $a);
            return <<<FIN
    /**
     * Validar
     *
     * @param string Opcional, use 'relaciones' para validar solo las relaciones; necesario cuando haya fórmulas
     */
    public function validar(\$in_particular='') {
$validaciones
    } // validar

FIN;
        } else {
            return '';
        }
    } // php

} // Clase Validar

?>
