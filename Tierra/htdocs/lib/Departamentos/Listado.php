<?php
/**
 * GenesisPHP - Departamentos Listado
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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

namespace Departamentos;

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
    public $nombre;                      // Filtro, texto
    public $estatus;                     // Filtro, caracter
    static public $param_nombre  = 'dn';
    static public $param_estatus = 'dt';
    public $filtros_param;               // Arreglo asociativo, filtros para pasar por el URL

    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        if (!$this->sesion->puede_ver('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para ver la bitácora.');
        }
        // Validar filtros
        if (($this->nombre != '') && !$this->validar_nombre($this->nombre)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Nombre incorrecto.');
        }
        if (($this->estatus != '') && !array_key_exists($this->estatus, Registro::$estatus_descripciones)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Estatus incorrecto.');
        }
        // Iniciamos el arreglo para los filtros
        $this->filtros_param = array();
        // Pasar los filtros como parámetros de los botones
        if ($this->nombre != '') {
            $this->filtros_param[self::$param_nombre] = $this->nombre;
        }
        if ($this->estatus != '') {
            $this->filtros_param[self::$param_estatus] = $this->estatus;
        }
        // Ejecutar validar en el padre
        parent::validar();
    } // validar

    /**
     * Encabezado
     *
     * @return string Texto del encabezado
     */
    public function encabezado() {
        // En este arreglo juntaremos lo que se va a entregar
        $e = array();
        // Juntar elementos
        if ($this->nombre != '') {
            $e[] = "nombre {$this->nombre}";
        }
        if ($this->estatus != '') {
            $e[] = "estatus ".Registro::$estatus_descripciones[$this->estatus];
        }
        if (count($e) > 0) {
            if ($this->cantidad_registros > 0) {
                $encabezado = sprintf('%d Departamentos con %s', $this->cantidad_registros, implode(", ", $e));
            } else {
                $encabezado = sprintf('Departamentos con %s', implode(", ", $e));
            }
        } else {
            $encabezado = 'Departamentos';
        }
        // Entregar
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
        if ($this->estatus != '') {
            $filtros[] = "estatus = '{$this->estatus}'";
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
                    id, nombre, clave, notas, estatus
                FROM
                    departamentos
                %s
                ORDER BY
                    nombre ASC
                %s",
                $filtros_sql,
                $this->limit_offset_sql()));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar departamentos para hacer listado.', $e->getMessage());
        }
        // Provocar excepción si no hay resultados
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base\ListadoExceptionVacio('Aviso: No se encontraron registros en departamentos.');
        }
        // Pasar la consulta a la propiedad listado
        $this->listado = $consulta->obtener_todos_los_registros();
        // Consultar la cantidad de registros
        if (($this->limit > 0) && ($this->cantidad_registros == 0)) {
            try {
                $consulta = $base_datos->comando(sprintf("
                    SELECT
                        COUNT(id) AS cantidad
                    FROM
                        departamentos
                    %s",
                    $filtros_sql));
            } catch (\Exception $e) {
                throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar los departamentos para determinar la cantidad de registros.', $e->getMessage());
            }
            $a = $consulta->obtener_registro();
            $this->cantidad_registros = intval($a['cantidad']);
        }
        // Poner como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

} // Clase Listado

?>
