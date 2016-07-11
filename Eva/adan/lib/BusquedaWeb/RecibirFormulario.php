<?php
/**
 * GenesisPHP - BusquedaWeb RecibirFormulario
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
 * Clase RecibirFormulario
 */
class RecibirFormulario extends \Base\Plantilla {

    /**
     * Elaborar Recibir Formulario Campo
     *
     * Subrutina para recibir_formulario
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_recibir_formulario_campo($columna, $datos) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // De acuerdo al tipo
        switch ($datos['tipo']) {
            case 'caracter':
                $a[] = "            \$this->{$columna} = \$this->post_select(\$_POST['{$columna}']);";
                break;
            case 'clave':
            case 'cuip':
            case 'curp':
            case 'mayusculas':
            case 'rfc':
                $a[] = "            \$this->{$columna} = \$this->post_texto_mayusculas_sin_acentos(\$_POST['{$columna}']);";
                break;
            case 'nombre':
            case 'nom_corto':
            case 'notas':
            case 'telefono':
            case 'frase':
            case 'variable':
                $a[] = "            \$this->{$columna} = \$this->post_texto(\$_POST['{$columna}']);";
                break;
            case 'email':
                $a[] = "            \$this->{$columna} = \$this->post_texto_minusculas(\$_POST['{$columna}']);";
                break;
            case 'entero':
            case 'flotante':
            case 'dinero':
            case 'porcentaje':
            case 'peso':
            case 'estatura':
            case 'fecha':
            case 'fecha_hora':
            case 'serial':
                if ($datos['filtro'] > 1) {
                    $a[] = "            \$this->{$columna}_desde = \$_POST['{$columna}_desde'];";
                    $a[] = "            \$this->{$columna}_hasta = \$_POST['{$columna}_hasta'];";
                } else {
                    $a[] = "            \$this->{$columna} = \$_POST['{$columna}'];";
                }
                break;
            case 'relacion':
                $a = false;
                break;
            default:
                die("Error en BusquedaWeb, RecibirFormulario, elaborar_recibir_formulario_campo: Tipo {$datos['tipo']} no programado al recibir formulario en $columna.");
        }
        // Entregar
        if ($a === false) {
            return "        // Se omite la columna $columna porque su tipo es {$datos['tipo']}";
        } else {
            return implode("\n", $a);
        }
    } // elaborar_recibir_formulario_campo

    /**
     * Elaborar Recibir Formulario Relación
     *
     * Subrutina para recibir_formulario
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_recibir_formulario_relacion($columna, $datos) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Se va usar mucho la relacion, asi que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion = $this->relaciones[$columna];
        } else {
            die("Error en BusquedaWeb, RecibirFormulario, elaborar_recibir_formulario_relacion: Falta obtener datos de Serpiente para la relación $columna.");
        }
        //
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = "            \$this->{$columna} = post_texto(\$_POST['{$columna}']);";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = $this->elaborar_recibir_formulario_campo("{$vip}_{$v}", $vd);
                                }
                            } else {
                                $a[] = "            \$this->{$vip} = \$this->post_texto(\$_POST['{$vip}']);";
                            }
                        } else {
                            die("Error en BusquedaWeb, RecibirFormulario, elaborar_recibir_formulario_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } else {
                        $a[] = $this->elaborar_recibir_formulario_campo("{$columna}_{$vip}", $vip_datos);
                    }
                } else {
                    $a[] = "            \$this->{$columna}_{$vip_datos} = \$this->post_texto(\$_POST['{$columna}_{$vip_datos}']);";
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_recibir_formulario_relacion

    /**
     * Elaborar Recibir Formulario Estatus
     *
     * Subrutina para recibir_formulario
     *
     * @return string Código PHP
     */
    protected function elaborar_recibir_formulario_estatus() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Para buscar por estatus, se requiere tener permiso
        $a[] = "            if (\$this->sesion->puede_recuperar('SED_CLAVE')) {";
        $a[] = "                \$this->estatus = \$this->post_select(\$_POST['estatus']);";
        $a[] = "            }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_recibir_formulario_estatus

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
                continue; // Si no hay etiqueta o valor en filtro, no aparece en el formulario
            } elseif ($columna == 'estatus') {
                $a[] = $this->elaborar_recibir_formulario_estatus();
            } elseif ($datos['tipo'] == 'relacion') {
                $a[] = $this->elaborar_recibir_formulario_relacion($columna, $datos);
            } else {
                $a[] = $this->elaborar_recibir_formulario_campo($columna, $datos);
            }
        }
        // Tronar en caso de no haber filtros
        if (count($a) > 0) {
            $campos = implode("\n", $a);
        } else {
            die('Error en BusquedaWeb, RecibirFormulario, php: No hay columnas con filtro para crear el método recibir formulario.');
        }
        // Entregar
        return <<<FINAL
    /**
     * Recibir Formulario
     *
     * @return boolean Verdadero si se recibió el formulario
     */
    public function recibir_formulario() {
        // Si viene el formulario
        if (\$_POST['formulario'] == self::\$form_name) {
            // Cargar propiedades
{$campos}
            // Entregar verdadero
            return true;
        } else {
            // No viene el formulario, entregar falso
            return false;
        }
    } // recibir_formulario

FINAL;
    } // php

} // Clase RecibirFormulario

?>
