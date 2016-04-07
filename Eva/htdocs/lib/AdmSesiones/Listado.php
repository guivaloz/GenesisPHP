<?php
/**
 * GenesisPHP - AdmSesiones Listado
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

namespace AdmSesiones;

/**
 * Clase Listado
 */
class Listado extends \Base\Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    public $nombre;                     // Filtro, fragmento de texto
    public $tipo;                       // Filtro, caracter
    static public $param_nombre = 'un';
    static public $param_tipo   = 'st';
    public $filtros_param;

    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        if (!$this->sesion->puede_ver('sesiones')) {
            throw new \Exception('Aviso: No tiene permiso para ver las sesiones.');
        }
        // Validar filtros
        if (($this->nombre != '') && !$this->validar_nombre($this->nombre)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Nombre incorrecto.');
        }
        if (($this->tipo != '') && !array_key_exists($this->tipo, Registro::$tipo_descripciones)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Tipo incorrecto.');
        }
        // Reseteamos el arreglo asociativo
        $this->filtros_param = array();
        if ($this->nombre != '') {
            $this->filtros_param[self::$param_nombre] = $this->nombre;
        }
        if ($this->tipo != '') {
            $this->filtros_param[self::$param_tipo] = $this->tipo;
        }
        // Ejecutar padre
        parent::validar();
    } // validar

    /**
     * Encabezado
     *
     * @return string Texto del encabezado
     */
    public function encabezado() {
        // En este arreglo juntaremos los elementos del encabezado
        $e = array();
        // Juntar los elementos del encabezado
        if ($this->nombre != '') {
            $e[] = "nombre {$this->nombre}";
        }
        if ($this->tipo != '') {
            $e[] = "tipo {$this->tipo}";
        }
        // Definimos el encabezado
        if (count($e) > 0) {
            if ($this->cantidad_registros > 0) {
                $encabezado = sprintf('%d Sesiones con %s', $this->cantidad_registros, implode(", ", $e));
            } else {
                $encabezado = sprintf('Sesiones con %s', implode(", ", $e));
            }
        } else {
            $encabezado = 'Sesiones';
        }
        // Entregamos
        return $encabezado;
    } // encabezado

    /**
     * Consultar
     */
    public function consultar() {
        // Validar
        $this->validar();
        // Filtros
        $filtros = array();
        if ($this->nombre != '') {
            $filtros[] = "nombre ILIKE '%{$this->nombre}%'";
        }
        if ($this->tipo != '') {
            $filtros[] = "tipo = '{$this->tipo}'";
        }
        if (count($filtros) > 0) {
            $filtros_sql = 'WHERE '.implode(' AND ', $filtros);
        } else {
            $filtros_sql = '';
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    usuario,
                    to_char(ingreso, 'YYYY-MM-DD, HH24:MI') as ingreso,
                    nombre, nom_corto, tipo, listado_renglones
                FROM
                    adm_sesiones
                ORDER BY
                    nombre ASC
                %s",
                $this->limit_offset_sql()));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar sesiones para hacer listado.', $e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base\ListadoExceptionVacio('Aviso: No se encontraron registros en sesiones.');
        }
        // Pasamos la consulta a la propiedad listado
        $this->listado = $consulta->obtener_todos_los_registros();
        // Consultar la cantidad de registros
        if (($this->limit > 0) && ($this->cantidad_registros == 0)) {
            try {
                $consulta = $base_datos->comando("
                    SELECT
                        COUNT(*) AS cantidad
                    FROM
                        adm_sesiones");
            } catch (\Exception $e) {
                throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar las sesiones para determinar la cantidad de registros.', $e->getMessage());
            }
            $a = $consulta->obtener_registro();
            $this->cantidad_registros = intval($a['cantidad']);
        }
    } // consultar

} // Clase Listado

?>
