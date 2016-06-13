<?php
/**
 * GenesisPHP - BaseDatosMotor
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

namespace Base2;

/**
 * Clase BaseDatosMotor
 */
class BaseDatosMotor extends \Configuracion\BaseDatosConfig {

    // protected $bd_nombre;
    // protected $servidor;
    // protected $usuario;
    // protected $password;
    protected $bd_recurso;

    /**
     * Conectar
     *
     * @return boolean Verdadero si tiene éxito
     */
    protected function conectar() {
        // Validar
        if (($this->servidor == '') || ($this->bd_nombre == '') || ($this->usuario == '')) {
            throw new \Exception("Error: Faltan datos en la configuración para usar la base de datos.");
        }
        // Puede tener contraseña o no
        if ($this->password != '') {
            $conexion = "host='{$this->servidor}' port=5432 dbname='{$this->bd_nombre}' user='{$this->usuario}' password='{$this->password}'";
        } else {
            $conexion = "host='{$this->servidor}' port=5432 dbname='{$this->bd_nombre}' user='{$this->usuario}'";
        }
        // Conectarse
        $bdr = @pg_connect($conexion);
        if (($bdr === false) || !is_resource($bdr)) {
            throw new \Exception("Error: No se pudo conectar a la base de datos.");
        }
        // Definir recurso a la base de datos
        $this->bd_recurso = $bdr;
        // Entregar verdadero
        return true;
    } // conectar

    /**
     * Ejecutar comando SQL
     *
     * @param  string  Comando SQL
     * @param  boolean Opcional, en caso de error ejecuta un die PHP, por defecto es falso
     * @return mixed   Entrega un objeto para control o verdadero al ejecutar INSERT, UPDATE o DELETE
     */
    public function comando($comando_sql, $tronar_en_error=false) {
        // Si no hay recurso a la base de datos, crearlo
        if (!$this->bd_recurso) {
            $this->conectar();
        }
        // Ejecutar comando sql
        $resultado = @pg_query($this->bd_recurso, $comando_sql);
        // De acuerdo al resultado
        if ($resultado === false) {
            // Error en el comando SQL
            if ($tronar_en_error) {
                die("Error en BaseDatosMotor: ".$comando_sql);
            } else {
                throw new \Exception('Comando: '.$comando_sql.' Error: '.pg_last_error($this->bd_recurso));
            }
        } elseif ($resultado === true) {
            // Los comandos INSERT, UPDATE y DELETE entregan verdadero
            return true;
        } else {
            // El comando SELECT entrega un recurso
            $control            = new BaseDatosControl($this->bd_recurso);
            $control->resultado = $resultado;
            return $control;
        }
    } // comando

    /**
     * SQL Texto
     *
     * @parem  string Texto
     * @return string Texto para usarse dentro de un comando SQL
     */
    static public function sql_texto($texto) {
        if (trim($texto) == '') {
            return 'NULL';
        } else {
            return "'".pg_escape_string(trim($texto))."'";
        }
    } // sql_texto

} // Clase BaseDatosMotor

?>
