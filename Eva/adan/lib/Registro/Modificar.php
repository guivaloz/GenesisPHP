<?php
/**
 * GenesisPHP - Registro Modificar
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
 * Clase Modificar
 */
class Modificar extends \Base\Plantilla {

    /**
     * Elaborar Asignaciones Previas
     *
     * Cuando en la semilla se definen instrucciones PHP en modificar, se van a juntar en este método
     *
     * @return string Código PHP
     */
    protected function elaborar_asignaciones_previas() {
        // En los datos de la tabla, en modificar, pueden especificarse operaciones
        $a = array();
        foreach ($this->tabla as $columna => $datos) {
            if (is_string($datos['modificar']) && ($datos['modificar'] != '')) {
                $a[] = "        // El valor de $columna será por una fórmula programada en la semilla";
                $a[] = "        \$this->validar('relaciones'); // Podría necesitarse el valor de una relación";
                $a[] = "        \$this->{$columna} = {$datos['modificar']};";
            }
        }
        // Entregar
        if (count($a) > 0) {
            return "\n".implode("\n", $a);
        } else {
            return '';
        }
    } // elaborar_asignaciones_previas

    /**
     * Elaborar Comparaciones
     *
     * @return string Código PHP
     */
    protected function elaborar_comparaciones() {
        // Elaborar el código para comparar
        $a   = array();
        $a[] = "        // Comparar cambios con el original";
        // Para cada columna de la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Si la orden de la semilla es modificar
            if ((is_int($datos['modificar']) && ($datos['modificar'] > 0)) || (is_string($datos['modificar']) && ($datos['modificar'] != ''))) {
                // De acuerdo al tipo
                if ($datos['tipo'] == 'boleano') {
                    $a[] = "        if (\$this->{$columna} != \$original->{$columna}) {";
                    $a[] = "            if (\$this->{$columna} == true) {";
                    $a[] = "                \$a[] = \"{$datos['etiqueta']} es Verdadero\";";
                    $a[] = "            } else {";
                    $a[] = "                \$a[] = \"{$datos['etiqueta']} es Falso\";";
                    $a[] = "            }";
                    $a[] = "        }";
                } elseif ($datos['tipo'] == 'caracter') {
                    $a[] = "        if (\$this->{$columna} != \$original->{$columna}) {";
                    $a[] = "            \$a[] = \"{$datos['etiqueta']} {\$this->{$columna}_descrito}\";";
                    $a[] = "        }";
                } elseif ($datos['tipo'] == 'geopunto') {
                    $a[] = "        if ((\$this->{$columna}_longitud != \$original->{$columna}_longitud) || (\$this->{$columna}_latitud != \$original->{$columna}_latitud)) {";
                    $a[] = "            \$a[] = \"{$datos['etiqueta']} ({\$this->{$columna}_longitud}, {\$this->{$columna}_latitud})\";";
                    $a[] = "        }";
                } else {
                    $a[] = "        if (\$this->{$columna} != \$original->{$columna}) {";
                    $a[] = "            \$a[] = \"{$datos['etiqueta']} {\$this->{$columna}}\";";
                    $a[] = "        }";
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_comparaciones

    /**
     * Elaborar Update SQL
     *
     * @return string Comando SQL
     */
    protected function elaborar_update_sql() {
        // En estos arreglos juntaremos las columnas y los parámetros
        $a = array();
        $p = array();
        // Para cada columna de la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Vamos a agregar las columnas que tengan configurado modificar en la tabla
            if ((is_int($datos['modificar']) && ($datos['modificar'] > 0)) || (is_string($datos['modificar']) && ($datos['modificar'] != ''))) {
                // Según el tipo
                switch ($datos['tipo']) {
                    case 'boleano':
                        $parametro = "                \\Base2\\UtileriasParaSQL::sql_boleano(\$this->{$columna})";
                        break;
                    case 'caracter':
                    case 'clave':
                    case 'contraseña':
                    case 'email':
                    case 'frase':
                    case 'nombre':
                    case 'notas':
                    case 'nom_corto':
                    case 'telefono':
                    case 'variable':
                        $parametro = "                \\Base2\\UtileriasParaSQL::sql_texto(\$this->{$columna})";
                        break;
                    case 'cuip':
                    case 'curp':
                    case 'mayusculas':
                    case 'rfc':
                        $parametro = "                \\Base2\\UtileriasParaSQL::sql_texto_mayusculas(\$this->{$columna})";
                        break;
                    case 'entero':
                    case 'relacion':
                        $parametro = "                \\Base2\\UtileriasParaSQL::sql_entero(\$this->{$columna})";
                        break;
                    case 'estatura':
                    case 'dinero':
                    case 'flotante':
                    case 'porcentaje':
                    case 'peso':
                        $parametro = "                \\Base2\\UtileriasParaSQL::sql_flotante(\$this->{$columna})";
                        break;
                    case 'fecha':
                    case 'fecha_hora':
                        $parametro = "                \\Base2\\UtileriasParaSQL::sql_tiempo(\$this->{$columna})";
                        break;
                    case 'geopunto':
                        $parametro = "                \\Base2\\UtileriasParaSQL::sql_geopunto(\$this->{$columna}_longitud, \$this->{$columna}_latitud)";
                        break;
                    default:
                        die("Error en Registro Modificar: Tipo de dato {$datos['tipo']} no programado en elaborar_modificar_sql");
                }
                // AGREGAR A LOS ARREGLOS
                if ($parametro != '') {
                    $a[] = "{$columna}=%s";
                    $p[] = $parametro;
                }
            }
        }
        // ENTREGAR
        $asignaciones = implode(", \n                    ", $a);
        $parametros   = implode(",\n", $p);
        return <<<FIN
sprintf("
                UPDATE
                    {$this->tabla_nombre}
                SET
                    $asignaciones
                WHERE
                    id=%d",
$parametros,
                \$this->id)
FIN;
    } // elaborar_update_sql

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        if ($this->adan->si_hay_que_crear('formulario')) {
            return <<<FIN
    /**
     * Modificar
     *
     * @return string Mensaje
     */
    public function modificar() {
        // Que tenga permiso para modificar
        if (!\$this->sesion->puede_modificar('SED_CLAVE')) {
            throw new \\Exception('Aviso: No tiene permiso para modificar SED_MENSAJE_PLURAL.');
        }
        // Verificar que haya sido consultado
        if (\$this->consultado == false) {
            throw new \\Exception('Error: No ha sido consultado(a) SED_MENSAJE_SINGULAR para modificarlo.');
        }{$this->elaborar_asignaciones_previas()}
        // Validar
        \$this->validar();
        // Hay que determinar que va cambiar, para armar el mensaje
        \$original = new Registro(\$this->sesion);
        try {
            \$original->consultar(\$this->id);
        } catch (\\Exception \$e) {
            die('Esto no debería pasar. Error al consultar registro original de SED_MENSAJE_PLURAL.');
        }
        \$a = array();
{$this->elaborar_comparaciones()}
        // Si no hay cambios, provoca excepcion de validacion
        if (count(\$a) == 0) {
            throw new \\Base2\\RegistroExceptionValidacion('Aviso: No hay cambios.');
        }
        // Actualizar la base de datos
        \$base_datos = new \\Base2\\BaseDatosMotor();
        try {
            \$base_datos->comando({$this->elaborar_update_sql()});
        } catch (\\Exception \$e) {
            throw new \\AdmBitacora\\BaseDatosExceptionSQLError(\$this->sesion, 'Error: Al actualizar SED_MENSAJE_SINGULAR. ', \$e->getMessage());
        }
        // Elaborar mensaje
        \$msg = "Modificó SED_SUBTITULO_SINGULAR {$this->columnas_vip_para_mensaje()} con ".implode(', ', \$a);
        // Agregar a la bitácora que se modificó el registro
        \$bitacora = new \\AdmBitacora\\Registro(\$this->sesion);
        \$bitacora->agregar_modificado(\$this->id, \$msg);
        // Entregar mensaje
        return \$msg;
    } // modificar

FIN;
        } else {
            return '';
        }
    } // php

} // Clase Modificar

?>
