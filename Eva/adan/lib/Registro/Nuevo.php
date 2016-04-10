<?php
/**
 * GenesisPHP - Registro Nuevo
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
 * Clase Nuevo
 */
class Nuevo extends \Base\Plantilla {

    /**
     * Elaborar Propiedad Relacion Fijo
     *
     * Cuando 'nuevo' tiene un ID fijo
     */
    protected function elaborar_propiedad_relacion_fijo($columna, $datos) {
        // En este arreglo juntaremos el código
        $a = array();
        // Se va usar mucho la relación, asi que para simplificar
        if (is_array($this->padre[$columna])) {
            $relacion  = $this->padre[$columna];
            $instancia = $relacion['instancia_singular'];
        } else {
            die("Error en Registro Nuevo: Falta obtener datos de Serpiente para el padre $columna.");
        }
        // Si 'nuevo' no es entero o un id incorrecto
        if (!is_int($datos['nuevo']) || ($datos['nuevo'] <= 0)) {
            die("Error en Registro Nuevo: El valor de 'nuevo' es incorrecto.");
        }
        // Agregar al arreglo
        $a[] = "        // Validar el id fijo";
        $a[] = "        \$this->{$columna} = {$datos['nuevo']};";
        $a[] = "        \${$instancia} = new \\{$relacion['clase_plural']}\\Registro(\$this->sesion);";
        $a[] = "        \${$instancia}->consultar(\$this->{$columna});";
        // Si vip es texto
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            // Solo un vip
            $a[] = "        \$this->{$columna}_{$relacion['vip']} = \${$instancia}->{$relacion['vip']};";
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
                                    $a[] = "        \$this->{$vip}_{$v} = \${$instancia}->{$vip}_{$v};";
                                    if ($vd['tipo'] == 'caracter') {
                                        // Es caracter, se usa el descrito
                                        $a[] = "        \$this->{$vip}_{$v}_descrito = \${$instancia}->{$vip}_{$v}_descrito;";
                                    }
                                }
                            } else {
                                // Ese vip es texto
                                $a[] = "        \$this->{$vip}_{$this->relaciones[$vip]['vip']} = \${$instancia}->{$vip}_{$this->relaciones[$vip]['vip']};";
                            }
                        } else {
                            die("Error en Registro Nuevo: No está definido el VIP en Serpiente para $vip.");
                        }
                    } elseif ($vip_datos['tipo'] == 'caracter') {
                        // Es caracter
                        $a[] = "        \$this->{$columna}_{$vip}          = \${$instancia}->{$vip};";
                        $a[] = "        \$this->{$columna}_{$vip}_descrito = \${$columna}->{$vip}_descrito;";
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
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedad_relacion_fijo

    /**
     * Elaborar Propiedad Relacion
     *
     * @param  string Columna
     * @param  array  Datos
     * @return string Código PHP
     */
    protected function elaborar_propiedad_relacion($columna, $datos) {
        // Si viene definido 'nuevo' entonces hay id fijo
        if ($datos['nuevo'] != '') {
            return $this->elaborar_propiedad_relacion_fijo($columna, $datos);
        }
        // En este arreglo juntaremos el código
        $a = array();
        // Se va usar mucho la relación, asi que para simplificar
        if (is_array($this->padre[$columna])) {
            $relacion  = $this->padre[$columna];
            $instancia = $relacion['instancia_singular'];
        } else {
            die("Error en Registro Nuevo: Falta obtener datos de Serpiente para el padre $columna.");
        }
        // Agregar al arreglo
        $a[] = "        if (\$this->{$columna} != '') {";
        $a[] = "            // Validar padre ya que viene asignado desde la pagina";
        $a[] = "            \${$instancia} = new \\{$relacion['clase_plural']}\\Registro(\$this->sesion);";
        $a[] = "            \${$instancia}->consultar(\$this->{$columna});";
        // Si vip es texto
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            // Solo un vip
            $a[] = "            \$this->{$columna}_{$relacion['vip']} = \${$instancia}->{$relacion['vip']};";
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
                            die("Error en Registro Nuevo: No está definido el VIP en Serpiente para $vip.");
                        }
                    } elseif ($vip_datos['tipo'] == 'caracter') {
                        // Es caracter
                        $a[] = "            \$this->{$columna}_{$vip}          = \${$instancia}->{$vip};";
                        $a[] = "            \$this->{$columna}_{$vip}_descrito = \${$columna}->{$vip}_descrito;";
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
                            die("Error en Registro Nuevo: No está definido el VIP en Serpiente para $vip.");
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
        // Entregar
        return implode("\n", $a);
    } // elaborar_propiedad_relacion

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // No hacer nada si no hay que crear formulario
        if (!$this->adan->si_hay_que_crear('formulario')) {
            return '';
        }
        // En este arreglo juntaremos el codigo
        $a   = array();
        $a[] = "        // Definir valores por defecto";
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            if (is_string($datos['nuevo']) && ($datos['nuevo'] != '')) {
                // Tiene como nuevo un texto
                $a[] = "        \$this->$columna = '{$datos['nuevo']}';";
            } elseif (is_int($datos['nuevo'])) {
                // Tiene como nuevo un entero
                $a[] = "        \$this->$columna = {$datos['nuevo']};";
            } elseif ($datos['agregar'] == 0) {
                // No se va agregar, se salta
                continue;
            } elseif ($columna == 'id') {
                // Es el id
                $a[] = "        \$this->id = 'agregar';";
            } elseif ($datos['tipo'] == 'geopunto') {
                // Es un geopunto
                $a[] = "        \$this->{$columna}_longitud = '';";
                $a[] = "        \$this->{$columna}_latitud  = '';";
            } elseif (($datos['tipo'] == 'relacion') && is_array($this->padre[$columna])) {
                // Es una relacion
                $a[] = $this->elaborar_propiedad_relacion($columna, $datos);
            } elseif ($datos['tipo'] == 'caracter') {
                // Es de tipo caracter
                if ($datos['validacion'] > 1) {
                    // Es obligatorio, por defecto es el primero
                    $letras       = array_keys($datos['descripciones']);
                    $primer_letra = array_shift($letras);
                    $a[] = "        \$this->$columna = '$primer_letra';";
                    $a[] = "        \$this->{$columna}_descrito = self::\${$columna}_descripciones[\$this->{$columna}];";
                } else {
                    // No es obligatorio, entonces por defecto es nulo
                    $a[] = "        \$this->$columna = '-';";
                    $a[] = "        \$this->{$columna}_descrito = '';";
                }
            } else {
                // Cualquier otro tipo
                if (is_string($datos['nuevo']) && ($datos['nuevo'] != '')) {
                    $a[] = "        \$this->$columna = '{$datos['nuevo']}';";
                } elseif (is_int($datos['nuevo']) || is_float($datos['nuevo'])) {
                    $a[] = "        \$this->$columna = {$datos['nuevo']};";
                } else {
                    $a[] = "        \$this->$columna = '';";
                }
            }
        }
        $asigaciones_por_defecto = implode("\n", $a);
        // Entregar
        return <<<FIN
    /**
     * Nuevo
     */
    public function nuevo() {
        // Que tenga permiso para agregar
        if (!\$this->sesion->puede_agregar('SED_CLAVE')) {
            throw new \\Exception('Aviso: No tiene permiso para agregar SED_MENSAJE_SINGULAR.');
        }
{$asigaciones_por_defecto}
        // Ponemos como verdadero el flag de consultado
        \$this->consultado = true;
    } // nuevo


FIN;
    } // php

} // Clase Nuevo

?>
