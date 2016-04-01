<?php
/**
 * GenesisPHP - Usuarios Listado
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

namespace AdmBitacora;

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
    public $usuario;        // Filtro, entero
    public $usuario_nombre;
    public $tipo;           // Filtro, caracter
    public $fecha_desde;    // Filtro, fecha
    public $fecha_hasta;    // Filtro, fecha
    static public $param_usuario     = 'bu';
    static public $param_tipo        = 'bt';
    static public $param_fecha_desde = 'bfd';
    static public $param_fecha_hasta = 'bfh';
    public $filtros_param;

    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        if (!$this->sesion->puede_ver('bitacora')) {
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
        // Validar tipo
        if (($this->tipo != '') && !array_key_exists($this->tipo, Registro::$tipo_descripciones)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Tipo incorrecto.');
        }
        // Validar fechas
        if (($this->fecha_desde != '') && !validar_fecha($this->fecha_desde)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Fecha desde incorrecta.');
        }
        if (($this->fecha_hasta != '') && !validar_fecha($this->fecha_hasta)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Fecha hasta incorrecta.');
        }
        // Reseteamos el arreglo asociativo
        $this->filtros_param = array();
        // Pasar los filtros como parametros de los botones
        if ($this->usuario != '') {
            $this->filtros_param[self::$param_usuario] = $this->usuario;
        }
        if ($this->tipo != '') {
            $this->filtros_param[self::$param_tipo] = $this->tipo;
        }
        if ($this->fecha_desde != '') {
            $this->filtros_param[self::$param_fecha_desde] = $this->fecha_desde;
        }
        if ($this->fecha_hasta != '') {
            $this->filtros_param[self::$param_fecha_hasta] = $this->fecha_hasta;
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
        if ($this->usuario != '') {
            $e[] = "usuario {$this->usuario_nombre}";
        }
        if ($this->tipo != '') {
            $e[] = "tipo ".Registro::$tipo_descripciones[$this->tipo];
        }
        if ($this->fecha_desde != '') {
            $e[] = "desde {$this->fecha_desde}";
        }
        if ($this->fecha_hasta != '') {
            $e[] = "hasta {$this->fecha_hasta}";
        }
        // Definimos el encabezado
        if (count($e) > 0) {
            if ($this->cantidad_registros > 0) {
                $encabezado = sprintf('%d Bitácora con %s', $this->cantidad_registros, implode(", ", $e));
            } else {
                $encabezado = sprintf('Bitácora con %s', implode(", ", $e));
            }
        } else {
            $encabezado = 'Bitácora';
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
        if ($this->usuario != '') {
            $filtros[] = "b.usuario = {$this->usuario}";
        }
        if ($this->tipo != '') {
            $filtros[] = "b.tipo = '{$this->tipo}'";
        }
        if ($this->fecha_desde != '') {
            $filtros[] = "b.fecha >= '{$this->fecha_desde} 00:00:00'";
        }
        if ($this->fecha_hasta != '') {
            $filtros[] = "b.fecha <= '{$this->fecha_hasta} 23:59:59'";
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
                    b.id,
                    b.usuario, u.nom_corto AS usuario_nom_corto,
                    to_char(b.fecha, 'YYYY-MM-DD HH24:MI:SS') as fecha,
                    b.pagina, b.pagina_id,
                    b.tipo, b.url, b.notas
                FROM
                    adm_bitacora AS b,
                    adm_usuarios AS u
                WHERE
                    b.usuario = u.id
                    %s
                ORDER BY
                    id DESC
                %s",
                $filtros_sql,
                $this->limit_offset_sql()));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar bitácora para hacer listado.', $e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base\ListadoExceptionVacio('Aviso: No se encontraron registros en la bitácora.');
        }
        // Pasamos la consulta a la propiedad listado
        $this->listado = $consulta->obtener_todos_los_registros();
        // Consultar la cantidad de registros
        if (($this->limit > 0) && ($this->cantidad_registros == 0)) {
            try {
                $consulta = $base_datos->comando(sprintf("
                    SELECT
                        COUNT(b.id) AS cantidad
                    FROM
                        adm_bitacora AS b,
                        adm_usuarios AS u
                    WHERE
                        b.usuario = u.id
                        %s",
                    $filtros_sql));
            } catch (\Exception $e) {
                throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar la bitácora para determinar la cantidad de registros.', $e->getMessage());
            }
            $a = $consulta->obtener_registro();
            $this->cantidad_registros = intval($a['cantidad']);
        }
    } // consultar

} // Clase Listado

?>
