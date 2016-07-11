<?php
/**
 * GenesisPHP - BusquedaWeb ElaborarFormulario
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
 * Clase ElaborarFormulario
 */
class ElaborarFormulario extends \Base\Plantilla {

    /**
     * Elaborar Formulario Campo
     *
     * Subrutina para elaborar_formulario. Elabora el campo de la columna para el formulario de búsqueda.
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @param  mixed  Opcional. Si este campo es de una relación, se debe dar la misma
     * @param  string Opcional. Columna de la tabla relacionada
     * @return string Código PHP
     */
    protected function elaborar_formulario_campo($columna, $datos, $relacion=null, $relacion_columna=null) {
        // Campo del formulario
        switch ($datos['tipo']) {
            case 'caracter':
                if (is_array($relacion)) {
                    $campo = "        \$f->select_con_nulo('$columna', '{$datos['etiqueta']}', \\{$relacion['clase_plural']}\\Registro::\${$relacion_columna}_descripciones, \$this->$columna);";
                } else {
                    $campo = "        \$f->select_con_nulo('$columna', '{$datos['etiqueta']}', Registro::\${$columna}_descripciones, \$this->$columna);";
                }
                break;
            case 'entero':
            case 'serial':
                if ($datos['filtro'] > 1) {
                    $campo = "        \$f->rango_enteros('$columna', '{$datos['etiqueta']}', \$this->{$columna}_desde, \$this->{$columna}_hasta);";
                } else {
                    $campo = "        \$f->texto_entero('$columna', '{$datos['etiqueta']}', \$this->$columna, 8);";
                }
                break;
            case 'flotante':
            case 'dinero':
            case 'peso':
            case 'estatura':
                if ($datos['filtro'] > 1) {
                    $campo = "        \$f->rango_flotantes('$columna', '{$datos['etiqueta']}', \$this->{$columna}_desde, \$this->{$columna}_hasta);";
                } else {
                    $campo = "        \$f->rango_flotantes('$columna', '{$datos['etiqueta']}', \$this->$columna, 8);";
                }
                break;
            case 'porcentaje':
                if ($datos['filtro'] > 1) {
                    $campo = "        \$f->rango_porcentajes('$columna', '{$datos['etiqueta']}', \$this->{$columna}_desde, \$this->{$columna}_hasta);";
                } else {
                    $campo = "        \$f->rango_porcentajes('$columna', '{$datos['etiqueta']}', \$this->$columna, 8);";
                }
                break;
            case 'email':
            case 'nombre':
            case 'mayusculas':
            case 'telefono':
            case 'frase':
            case 'variable':
            case 'notas':
                $campo = "        \$f->texto_nombre('$columna', '{$datos['etiqueta']}', \$this->$columna, 64);";
                break;
            case 'nom_corto':
                $campo = "        \$f->texto_nom_corto('$columna', '{$datos['etiqueta']}', \$this->$columna, 24);";
                break;
            case 'clave':
                $campo = "        \$f->texto('$columna', '{$datos['etiqueta']}', \$this->$columna, 16);";
                break;
            case 'cuip':
                $campo = "        \$f->texto('$columna', '{$datos['etiqueta']}', \$this->$columna, 20);";
                break;
            case 'curp':
                $campo = "        \$f->texto('$columna', '{$datos['etiqueta']}', \$this->$columna, 18);";
                break;
            case 'rfc':
                $campo = "        \$f->texto('$columna', '{$datos['etiqueta']}', \$this->$columna, 13);";
                break;
            case 'fecha':
                if ($datos['filtro'] > 1) {
                    $campo = "        \$f->rango_fechas('$columna', '{$datos['etiqueta']}', \$this->{$columna}_desde, \$this->{$columna}_hasta);";
                } else {
                    $campo = "        \$f->fecha('$columna', '{$datos['etiqueta']}', \$this->$columna);";
                }
                break;
            case 'fecha_hora':
                if ($datos['filtro'] > 1) {
                    $campo = "        \$f->rango_fechas_horas('$columna', '{$datos['etiqueta']}', \$this->{$columna}_desde, \$this->{$columna}_hasta);";
                } else {
                    $campo = "        \$f->fecha_hora('$columna', '{$datos['etiqueta']}', \$this->$columna);";
                }
                break;
            case 'relacion':
                $campo = false;
                break;
            default:
                die("Error en BusquedaWeb, ElaborarFormulario, elaborar_formulario_campo: Tipo {$datos['tipo']} no programado al elaborar formulario en $columna.");
        }
        // Entregar
        if ($campo === false) {
            return "        // Se omite la columna $columna porque su tipo es {$datos['tipo']}";
        } else {
            return $campo;
        }
    } // elaborar_formulario_campo

    /**
     * Elaborar Formulario Relación
     *
     * Subrutina para elaborar_formulario. Elabora el campo de búsqueda para el campo de la relación.
     *
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_formulario_relacion($columna, $datos) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Se va usar mucho la relacion, asi que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion = $this->relaciones[$columna];
        } else {
            die("Error en BusquedaWeb, ElaborarFormulario, elaborar_formulario_relacion: Falta obtener datos de Serpiente para la relación $columna.");
        }
        // A continuacion, la parte complicada que viaja a traves de las relaciones
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = "        \$f->texto('$columna', '{$datos['etiqueta']}', \$this->$columna);";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = $this->elaborar_formulario_campo("{$vip}_{$v}", $vd, $this->relaciones[$vip], $v);
                                }
                            } else {
                                $a[] = "        \$f->texto('$vip', '$vip', \$this->{$vip});";
                            }
                        } else {
                            die("Error en BusquedaWeb, ElaborarFormulario, elaborar_formulario_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } else {
                        $a[] = $this->elaborar_formulario_campo("{$columna}_{$vip}", $vip_datos, $relacion, $vip);
                    }
                } else {
                    $a[] = "        \$f->texto('{$columna}_{$vip_datos}', '{$vip_datos}', \$this->{$columna}_{$vip_datos});";
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_formulario_relacion

    /**
     * Elaborar Formulario Estatus
     *
     * Subrutina para elaborar_formulario
     *
     * @return string Código PHP
     */
    protected function elaborar_formulario_estatus() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Para buscar por estatus, se requiere tener permiso
        $a[] = "        if (\$this->sesion->puede_recuperar('SED_CLAVE')) {";
        $a[] = "            \$f->select_con_nulo('estatus', 'Estatus', Registro::\$estatus_descripciones, \$this->estatus);";
        $a[] = "        }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_formulario_estatus

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
                $a[] = $this->elaborar_formulario_estatus();
            } elseif ($datos['tipo'] == 'relacion') {
                $a[] = $this->elaborar_formulario_relacion($columna, $datos);
            } else {
                $a[] = $this->elaborar_formulario_campo($columna, $datos);
            }
        }
        // Tronar en caso de no haber filtros
        if (count($a) > 0) {
            $campos = implode("\n", $a);
        } else {
            die('Error en BusquedaWeb, ElaborarFormulario, php: No hay columnas con filtro para crear el método formulario.');
        }
        // Entregar
        return <<<FINAL
    /**
     * Elaborar Formulario
     *
     * @param  string Encabezado opcional
     * @return string HTML con el formulario
     */
    protected function elaborar_formulario(\$in_encabezado='') {
        // Formulario
        \$f = new \\Base2\\FormularioWeb(self::\$form_name);
        // Campos
{$campos}
        \$f->boton_buscar();
        // Encabezado
        if (\$in_encabezado !== '') {
            \$encabezado = \$in_encabezado;
        } else {
            \$encabezado = "Buscar SED_TITULO_PLURAL";
        }
        // Agregar javascript
        \$this->javascript[] = \$f->javascript();
        // Entregar
        return \$f->html(\$encabezado, \$this->sesion->menu->icono_en('SED_CLAVE'));
    } // elaborar_formulario

FINAL;
    } // php

} // Clase ElaborarFormulario

?>
