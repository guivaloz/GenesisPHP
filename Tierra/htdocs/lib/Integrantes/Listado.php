<?php
/**
 * GenesisPHP - Integrantes Listado
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

namespace Integrantes;

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
    public $usuario;                     // Filtro, entero
    public $usuario_nombre;
    public $departamento;                // Filtro, entero
    public $departamento_nombre;
    public $estatus;                     // Filtro, carácter
    public $estatus_descrito;
    static public $param_usuario      = 'nu';
    static public $param_departamento = 'nd';
    static public $param_estatus      = 'nt';
    public $filtros_param;               // Arreglo asociativo, filtros para pasar por el URL

    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        if (!$this->sesion->puede_ver('integrantes')) {
            throw new \Exception('Aviso: No tiene permiso para ver la bitácora.');
        }
        // Validar usuario
        if ($this->usuario != '') {
            $usuario = new \Usuarios\Registro($this->sesion);
            try {
                $usuario->consultar($this->usuario);
            } catch (\Exception $e) {
                throw new \Base\ListadoExceptionValidacion('Aviso: Usuario incorrecto.');
            }
            $this->usuario_nombre = $usuario->nombre;
        } else {
            $this->usuario_nombre = '';
        }
        // Validar departamento
        if ($this->departamento != '') {
            $departamento = new \Departamentos\Registro($this->sesion);
            try {
                $departamento->consultar($this->departamento);
            } catch (\Exception $e) {
                throw new \Base\ListadoExceptionValidacion('Aviso: Departamento incorrecto.');
            }
            $this->departamento_nombre = $departamento->nombre;
        } else {
            $this->departamento_nombre = '';
        }
        // Validar estatus
        if ($this->estatus != '') {
            if (!array_key_exists($this->estatus, Registro::$estatus_descripciones)) {
                throw new \Base\ListadoExceptionValidacion('Aviso: Estatus incorrecto.');
            } else {
                $this->estatus_descrito = Registro::$estatus_descripciones[$this->estatus];
            }
        } else {
            $this->estatus_descrito = '';
        }
        // Iniciamos el arreglo para los filtros
        $this->filtros_param = array();
        // Pasar los filtros como parámetros de los botones
        if ($this->usuario != '') {
            $this->filtros_param[self::$param_usuario] = $this->usuario;
        }
        if ($this->departamento != '') {
            $this->filtros_param[self::$param_departamento] = $this->departamento;
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
        if ($this->usuario != '') {
            $e[] = "usuario {$this->usuario_nombre}";
        }
        if ($this->departamento != '') {
            $e[] = "departamento {$this->departamento_nombre}";
        }
        if ($this->estatus != '') {
            $e[] = "estatus {$this->estatus_descrito}";
        }
        if (count($e) > 0) {
            if ($this->cantidad_registros > 0) {
                $encabezado = sprintf('%d Integrantes con %s', $this->cantidad_registros, implode(", ", $e));
            } else {
                $encabezado = sprintf('Integrantes con %s', implode(", ", $e));
            }
        } else {
            $encabezado = 'Integrantes';
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
        if ($this->usuario != '') {
            $filtros[] = "i.usuario = {$this->usuario}";
        }
        if ($this->departamento != '') {
            $filtros[] = "i.departamento = '{$this->departamento}'";
        }
        if ($this->estatus != '') {
            $filtros[] = "i.estatus = '{$this->estatus}'";
        }
        if (count($filtros) > 0) {
            $filtros_sql = 'AND '.implode(' AND ', $filtros);
        } else {
            $filtros_sql = '';
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    i.id,
                    i.usuario, u.nom_corto AS usuario_nom_corto, u.nombre AS usuario_nombre,
                    i.departamento, d.nombre AS departamento_nombre,
                    i.poder,
                    i.estatus
                FROM
                    integrantes i,
                    departamentos d,
                    usuarios u
                WHERE
                    i.departamento = d.id
                    AND i.usuario = u.id
                    %s
                ORDER BY
                    usuario_nombre, departamento_nombre ASC
                %s",
                $filtros_sql,
                $this->limit_offset_sql()));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar integrantes para hacer listado.', $e->getMessage());
        }
        // Provocar excepción si no hay resultados
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base\ListadoExceptionVacio('Aviso: No se encontraron registros en integrantes.');
        }
        // Pasar la consulta a la propiedad listado
        $this->listado = $consulta->obtener_todos_los_registros();
        // Consultar la cantidad de registros
        if (($this->limit > 0) && ($this->cantidad_registros == 0)) {
            try {
                $consulta = $base_datos->comando(sprintf("
                    SELECT
                        COUNT(*) AS cantidad
                    FROM
                        integrantes i,
                        departamentos d,
                        usuarios u
                    WHERE
                        i.departamento = d.id
                        AND i.usuario = u.id
                        %s",
                    $filtros_sql));
            } catch (\Exception $e) {
                throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar los integrantes para determinar la cantidad de registros.', $e->getMessage());
            }
            $a = $consulta->obtener_registro();
            $this->cantidad_registros = intval($a['cantidad']);
        }
        // Poner como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

} // Clase Listado

?>
