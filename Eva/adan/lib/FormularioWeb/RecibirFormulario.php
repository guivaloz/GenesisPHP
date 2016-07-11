<?php
/**
 * GenesisPHP - FormularioWeb RecibirFormulario
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

namespace FormularioWeb;

/**
 * Clase RecibirFormulario
 */
class RecibirFormulario extends \Base\Plantilla {

    /**
     * Elaborar Recibir Formulario Campos
     *
     * @param  string Columna
     * @param  array  Datos
     * @return string Código PHP
     */
    protected function elaborar_recibir_formulario_campos($columna, $datos) {
        // Si la columna se agrega o se modifica
        switch ($datos['tipo']) {
            case 'caracter':
            case 'relacion':
                $campo = "\$this->$columna = \\Base2\\UtileriasParaFormularios::post_select(\$_POST['{$columna}']);";
                break;
            case 'clave':
            case 'cuip':
            case 'curp':
            case 'mayusculas':
            case 'rfc':
                $campo = "\$this->$columna = \\Base2\\UtileriasParaFormularios::post_texto_mayusculas_sin_acentos(\$_POST['{$columna}']);";
                break;
            case 'email':
                $campo = "\$this->$columna = \\Base2\\UtileriasParaFormularios::post_texto_minusculas(\$_POST['{$columna}']);";
                break;
            case 'contraseña':
            case 'dinero':
            case 'entero':
            case 'estatura':
            case 'fecha':
            case 'fecha_hora':
            case 'flotante':
            case 'frase':
            case 'nombre':
            case 'nom_corto':
            case 'notas':
            case 'peso':
            case 'porcentaje':
            case 'telefono':
            case 'variable':
                $campo = "\$this->$columna = \\Base2\\UtileriasParaFormularios::post_texto(\$_POST['{$columna}']);";
                break;
            case 'geopunto':
                $campo = "\$this->{$columna}_longitud = \\Base2\\UtileriasParaFormularios::post_texto(\$_POST['{$columna}_longitud']);\n        \$this->{$columna}_latitud = \\Base2\\UtileriasParaFormularios::post_texto(\$_POST['{$columna}_latitud']);";
                break;
            default:
                die("Error en FormularioWeb: Tipo {$datos['tipo']} no programado al recibir formulario.");
        }
        // Entregar campo
        return "        $campo";
    } // elaborar_recibir_formulario_campos

    /**
     * Elaborar Recibir Formulario
     *
     * @return string Código PHP
     */
    protected function elaborar_recibir_formulario() {
        // En este arreglo juntaremos el codigo php
        $a   = array();
        $a[] = "        // Recibir los valores del formulario";
        // Bucle para cada columna de la tabla
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['etiqueta'] == '') {
                continue; // Si no hay etiqueta, no aparece en el formulario
            } elseif (($columna == 'id') || ($columna == 'estatus')) {
                continue; // Las columnas id y estatus nunca aparecen en los formularios
            } elseif ((is_int($datos['agregar']) && ($datos['agregar'] > 0)) || (is_int($datos['modificar']) && ($datos['modificar'] > 0))) {
                $a[] = $this->elaborar_recibir_formulario_campos($columna, $datos);
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_recibir_formulario

    /**
     * Elaborar Recibir Formulario Imagen
     *
     * @return string Código PHP
     */
    protected function elaborar_recibir_formulario_imagen() {
        if (is_array($this->imagen)) {
            if (is_string($this->imagen['variable']) && ($this->imagen['variable'] != '')) {
                $variable = $this->imagen['variable'];
            } else {
                $variable = 'imagen';
            }
            return <<<FINAL
        // Recibir la imagen
        if (\$_POST['accion'] == 'agregar') {
            \$this->imagen_{$variable} = \$_FILES['{$variable}']['name'];
            \$this->imagen_temporal = \$_FILES['{$variable}']['tmp_name'];
        }
FINAL;
        } else {
            return '';
        }
    } // elaborar_recibir_formulario_imagen

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Recibir formulario
     */
    protected function recibir_formulario() {
        // Cadenero
        \$cadenero = new \\Base2\\Cadenero(\$this->sesion);
        \$cadenero->validar_recepcion(self::\$form_name, \$_POST['cadenero']);
        // Si es nuevo el estatus es en uso, de lo contrario es modificacion y debe venir el id
        if (\$_POST['accion'] == 'agregar') {
            \$this->estatus = 'A';
        } else {
            \$this->id = \$_POST['id'];
        }
{$this->elaborar_recibir_formulario()}{$this->elaborar_recibir_formulario_imagen()}
    } // recibir_formulario

FINAL;
    } // php

} // Clase RecibirFormulario

?>
