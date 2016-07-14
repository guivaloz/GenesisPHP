<?php
/**
 * GenesisPHP - BusquedaWeb Validar
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

namespace BusquedaWeb;

/**
 * Clase Validar
 */
class Validar extends \Base\Plantilla {

    /**
     * Elaborar Validar Campo
     *
     * Subrutina para elaborar_validar. Trabaja todos los tipos excepto las relaciones y el estatus, los cuales tienen sus propios métodos.
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @param  mixed  Opcional. Si este campo es de una relación, se debe dar la misma
     * @param  string Opcional. Columna de la tabla relacionada
     * @return string Código PHP
     */
    protected function elaborar_validar_campo($columna, $datos, $relacion=null, $relacion_columna=null) {
        // De acuerdo al tipo se elije la funcion de validacion
        switch ($datos['tipo']) {
            case 'caracter':
                if (is_array($relacion)) {
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
                if ($datos['filtro'] > 1) {
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_entero(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_entero(\$this->{$columna}_hasta)");
                } else {
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_entero(\$this->{$columna})";
                }
                break;
            case 'flotante':
            case 'dinero':
                if ($datos['filtro'] > 1) {
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_flotante(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_flotante(\$this->{$columna}_hasta)");
                } else {
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_flotante(\$this->{$columna})";
                }
                break;
            case 'porcentaje':
                if ($datos['filtro'] > 1) {
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_porcentaje(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_porcentaje(\$this->{$columna}_hasta)");
                } else {
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_porcentaje(\$this->{$columna})";
                }
                break;
            case 'peso':
                if ($datos['filtro'] > 1) {
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_peso(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_peso(\$this->{$columna}_hasta)");
                } else {
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_peso(\$this->{$columna})";
                }
                break;
            case 'estatura':
                if ($datos['filtro'] > 1) {
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_estatura(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_estatura(\$this->{$columna}_hasta)");
                } else {
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_estatura(\$this->{$columna})";
                }
                break;
            case 'fecha':
                if ($datos['filtro'] > 1) {
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_fecha(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_fecha(\$this->{$columna}_hasta)");
                } else {
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_fecha(\$this->{$columna})";
                }
                break;
            case 'fecha_hora':
                if ($datos['filtro'] > 1) {
                    $funcion = array(
                        'desde' => "!\\Base2\\UtileriasParaValidar::validar_fecha_hora(\$this->{$columna}_desde)",
                        'hasta' => "!\\Base2\\UtileriasParaValidar::validar_fecha_hora(\$this->{$columna}_hasta)");
                } else {
                    $funcion = "!\\Base2\\UtileriasParaValidar::validar_fecha_hora(\$this->{$columna})";
                }
                break;
            case 'relacion':
                $funcion = false;
                break;
            default:
                die("Error en BusquedaWeb, Validar, elaborar_validar_campo: No hay función para validar el tipo {$datos['tipo']} en $columna.");
        }
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Si hay funcion, agregar la validacion
        if (is_string($funcion) && ($funcion != '')) {
            // Normal, solo uno
            $a[] = "        if ((\$this->{$columna} != '') && $funcion) {";
            $a[] = "            throw new \\Base2\\BusquedaExceptionValidacion('Aviso: {$datos['etiqueta']} incorrecto(a).');";
            $a[] = "        }";
        } elseif (is_array($funcion)) {
            // Es un rango, desde y hasta
            foreach ($funcion as $c => $f) {
                $a[] = "        if ((\$this->{$columna}_{$c} != '') && $f) {";
                $a[] = "            throw new \\Base2\\BusquedaExceptionValidacion('Aviso: {$datos['etiqueta']} $c incorrecto(a).');";
                $a[] = "        }";
            }
        } else {
            $a[] = "        // Se omite la columna $columna porque su tipo es {$datos['tipo']}";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_validar_campo

    /**
     * Elaborar Validar Relación
     *
     * Subrutina para elaborar_validar. Para validar carga la propiedad en una instancia de la clase relacionada y ejecuta su validar.
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
            die("Error en BusquedaWeb, Validar, elaborar_validar_relacion: Falta obtener datos de Serpiente para la relación $columna.");
        }
        // Inicia
        $a[] = "        // Validar la relacion $columna, inicia";
        // A continuacion, la parte complicada que viaja a traves de las relaciones
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = "        if (\$this->$columna != '') {";
            $a[] = "            // Pendiente validar la relacion cuando el vip es solo texto";
            $a[] = "        }";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = $this->elaborar_validar_campo("{$vip}_{$v}", $vd, $this->relaciones[$vip], $v);
                                }
                            } else {
                                $a[] = "        if (\$this->{$vip} != '') {";
                                $a[] = "            // Pendiente validar la relacion";
                                $a[] = "        }";
                            }
                        } else {
                            die("Error en BusquedaWeb, Validar, elaborar_validar_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } else {
                        $a[] = $this->elaborar_validar_campo("{$columna}_{$vip}", $vip_datos, $relacion, $vip);
                    }
                } else {
                    $a[] = "        if (\$this->{$columna}_{$vip_datos} != '') {";
                    $a[] = "            // Pendiente validar la relacion";
                    $a[] = "        }";
                }
            }
        }
        // Termina
        $a[] = "        // Validar la relacion $columna, termina";
        // Entregar
        return implode("\n", $a);
    } // elaborar_validar_relacion

    /**
     * Elaborar Validar Estatus
     *
     * Subrutina para elaborar_validar. Si hay la columna estatus agrega su validación.
     *
     * @return string Código PHP
     */
    protected function elaborar_validar_estatus() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Si existe estatus y tiene filtro mayor a cero
        if (($this->tabla['estatus'] != '') && ($this->tabla['estatus']['filtro'] > 0)) {
            // Al buscar por estatus, se requiere tener permiso para buscar los eliminados
            $a[] = "        if (\$this->estatus != '') {";
            $a[] = "            if ((\$this->estatus == '{$this->estatus['eliminado']}') && !\$this->sesion->puede_recuperar('SED_CLAVE')) {";
            $a[] = "                throw new \\Base2\\BusquedaExceptionValidacion('Aviso: No tiene permiso para ver los registros eliminados.');";
            $a[] = "            } elseif (!array_key_exists(\$this->estatus, Registro::\$estatus_descripciones)) {";
            $a[] = "                throw new \\Base2\\BusquedaExceptionValidacion('Aviso: Estatus incorrecto.');";
            $a[] = "            }";
            $a[] = "        }";
        }
        // Entregar
        if (count($a) > 0) {
            return implode("\n", $a);
        } else {
            return "        // Informo que no hay estatus a validar";
        }
    } // elaborar_validar_estatus

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Código PHP se juntará en este arreglo
        $a = array();
        // Bucle a través todas las columnas de tabla
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no aparece en el formulario
            } elseif ($columna == 'estatus') {
                $a[] = $this->elaborar_validar_estatus();
            } elseif ($datos['tipo'] == 'relacion') {
                $a[] = $this->elaborar_validar_relacion($columna, $datos);
            } else {
                $a[] = $this->elaborar_validar_campo($columna, $datos);
            }
        }
        // Tronar en caso de no haber filtros
        if (count($a) > 0) {
            $validaciones = implode("\n", $a);
        } else {
            die('Error en BusquedaWeb, Validar, php: No hay columnas para crear el método validar.');
        }
        // Entregar
        return <<<FINAL
    /**
     * Validar
     */
    public function validar() {
        // Validar filtros
{$validaciones}
    } // validar

FINAL;
    } // php

} // Clase Validar

?>
