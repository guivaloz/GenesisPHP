<?php
/**
 * GenesisPHP - Registro Agregar
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
 * Clase Agregar
 */
class Agregar extends \Base\Plantilla {

    /**
     * Elaborar Asignaciones Previas
     *
     * Cuando en la semilla se definen instrucciones PHP en agregar, se van a juntar en este método
     *
     * @return string Código PHP
     */
    protected function elaborar_asignaciones_previas() {
        // El los datos de la tabla, en agregar, pueden especificarse instrucciones de php
        $a = array();
        foreach ($this->tabla as $columna => $datos) {
            if (is_string($datos['agregar']) && ($datos['agregar'] != '')) {
                $a[] = "        // El valor de $columna será por una fórmula programada en la semilla";
                $a[] = "        \$this->validar('relaciones'); // Podría necesitarse el valor de una relación";
                $a[] = "        \$this->{$columna} = {$datos['agregar']};";
            }
        }
        // Entregar si las hubiera
        if (count($a) > 0) {
            return "\n".implode("\n", $a);
        } else {
            return '';
        }
    } // elaborar_asignaciones_previas

    /**
     * Elaborar Insert SQL
     *
     * @return string Comando SQL
     */
    protected function elaborar_insert_sql() {
        // En estos arreglos juntaremos las columnas, los valores (con susticiociones sprintf) y los parametros
        $c = array();
        $v = array();
        $p = array();
        // Para cada columna de la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Vamos a agregar las columnas que tengan configurado agregar en la tabla
            if ((is_int($datos['agregar']) && ($datos['agregar'] > 0)) || (is_string($datos['agregar']) && ($datos['agregar'] != ''))) {
                // Segun el tipo
                switch ($datos['tipo']) {
                    case 'caracter':
                    case 'nombre':
                    case 'notas':
                    case 'nom_corto':
                    case 'contraseña':
                    case 'email':
                    case 'clave':
                    case 'telefono':
                    case 'frase':
                    case 'variable':
                        $parametro = "                \$this->sql_texto(\$this->{$columna})";
                        break;
                    case 'mayusculas':
                    case 'cuip':
                    case 'curp':
                    case 'rfc':
                        $parametro = "                \$this->sql_texto_mayusculas(\$this->{$columna})";
                        break;
                    case 'entero':
                    case 'relacion':
                        $parametro = "                \$this->sql_entero(\$this->{$columna})";
                        break;
                    case 'flotante':
                    case 'dinero':
                    case 'porcentaje':
                    case 'peso':
                    case 'estatura':
                        $parametro = "                \$this->sql_flotante(\$this->{$columna})";
                        break;
                    case 'fecha':
                    case 'fecha_hora':
                        $parametro = "                \$this->sql_tiempo(\$this->{$columna})";
                        break;
                    case 'geopunto':
                        $parametro = "                \$this->sql_geopunto(\$this->{$columna}_longitud, \$this->{$columna}_latitud)";
                        break;
                    default:
                        die("Error en Registro Agregar: Tipo de dato {$datos['tipo']} no programado en elaborar_agregar_sql");
                }
                // AGREGAR A LOS ARREGLOS
                if ($parametro != '') {
                    $c[] = $columna;
                    $v[] = '%s';
                    $p[] = $parametro;
                }
            }
        }
        // JUNTAR LOS ARREGLOS
        $columnas   = implode(", \n                    ", $c);
        $valores    = implode(", ", $v);
        $parametros = implode(",\n", $p);
        // ENTREGAR COMANDO SQL
        return <<<FIN
sprintf("
                INSERT INTO
                    {$this->tabla_nombre}
                    ($columnas)
                VALUES
                    ($valores)",
$parametros)
FIN;
    } // elaborar_insert_sql

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // No entregar nada si no hay que crear formulario
        if ($this->adan->si_hay_que_crear('formulario')) {
        return <<<FIN
    /**
     * Agregar
     *
     * @return string Mensaje
     */
    public function agregar() {
        // Que tenga permiso para agregar
        if (!\$this->sesion->puede_agregar('SED_CLAVE')) {
            throw new \Exception('Aviso: No tiene permiso para agregar SED_MENSAJE_SINGULAR.');
        }
        // Verificar que no haya sido consultado
        if (\$this->consultado == true) {
            throw new \Exception('Error: Ha sido consultado(a) SED_MENSAJE_SINGULAR, no debe estarlo.');
        }{$this->elaborar_asignaciones_previas()}
        // Validar
        \$this->validar();
        // Insertar en la base de datos
        \$base_datos = new \Base\BaseDatosMotor();
        try {
            \$base_datos->comando({$this->elaborar_insert_sql()});
        } catch (\Exception \$e) {
            throw new \Base\BaseDatosExceptionSQLError(\$this->sesion, 'Error: Al insertar SED_MENSAJE_SINGULAR. ', \$e->getMessage());
        }
        // Obtener el id de lo recién insertado
        try {
            \$consulta = \$base_datos->comando("SELECT last_value AS id FROM {$this->tabla_nombre}_id_seq");
        } catch (\Exception \$e) {
            throw new \Base\BaseDatosExceptionSQLError(\$this->sesion, 'Error: Al obtener el ID de SED_MENSAJE_SINGULAR. ', \$e->getMessage());
        }
        \$a        = \$consulta->obtener_registro();
        \$this->id = intval(\$a['id']);
        // Elaborar mensaje
        \$msg = "Agregó SED_SUBTITULO_SINGULAR {$this->columnas_vip_para_mensaje()}";
        // Agregar a la bitácora que hay un nuevo registro
        \$bitacora = new \AdmBitacora\Registro(\$this->sesion);
        \$bitacora->agregar_nuevo(\$this->id, \$msg);
        // Entregar mensaje
        return \$msg;
    } // agregar


FIN;
        } else {
            return '';
        }
    } // php

} // Clase Agregar

?>
