<?php
/**
 * GenesisPHP - Autentificaciones Listado
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

namespace Autentificaciones;

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
    public $tipo;                        // Filtro, caracter
    public $tipo_descrito;
    static public $param_usuario = 'au';
    static public $param_tipo    = 'at';
    public $filtros_param;               // Arreglo asociativo, filtros para pasar por el URL

    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        if (!$this->sesion->puede_ver('autentificaciones')) {
            throw new \Exception('Aviso: No tiene permiso para ver las autentificaciones.');
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
        if ($this->tipo != '') {
            if (!array_key_exists($this->tipo, Registro::$tipo_descripciones)) {
                throw new \Base\ListadoExceptionValidacion('Aviso: Tipo incorrecto.');
            }
            $this->tipo_descrito = Registro::$tipo_descripciones[$this->tipo];
        } else {
            $this->tipo_descrito = '';
        }
        // Iniciamos el arreglo para los filtros
        $this->filtros_param = array();
        // Pasar los filtros como parámetros de los botones
        if ($this->usuario != '') {
            $this->filtros_param[self::$param_usuario] = $this->usuario;
        }
        if ($this->tipo != '') {
            $this->filtros_param[self::$param_tipo] = $this->tipo;
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
        if ($this->tipo != '') {
            $e[] = "tipo {$this->tipo_descrito}";
        }
        if (count($e) > 0) {
            if ($this->cantidad_registros > 0) {
                $encabezado = sprintf('%d Autentificaciones con %s', $this->cantidad_registros, implode(", ", $e));
            } else {
                $encabezado = sprintf('Autentificaciones con %s', implode(", ", $e));
            }
        } else {
            $encabezado = 'Autentificaciones';
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
            $filtros[] = "a.usuario = {$this->usuario}";
        }
        if ($this->tipo != '') {
            $filtros[] = "a.tipo = '{$this->tipo}'";
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
                    a.usuario,
                    u.nom_corto AS usuario_nom_corto,
                    to_char(a.fecha, 'YYYY-MM-DD, HH24:MI') as fecha,
                    a.nom_corto,
                    a.tipo,
                    a.ip
                FROM
                    autentificaciones AS a LEFT JOIN usuarios AS u ON a.usuario = u.id
                %s
                ORDER BY
                    a.fecha DESC
                %s",
                $filtros_sql,
                $this->limit_offset_sql()));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar autentificaciones para hacer listado.', $e->getMessage());
        }
        // Provocar excepción si no hay resultados
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base\ListadoExceptionVacio('Aviso: No se encontraron autentificaciones.');
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
                        autentificaciones AS a LEFT JOIN usuarios AS u ON a.usuario = u.id
                    %s",
                    $filtros_sql));
            } catch (\Exception $e) {
                throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar las autentificaciones para determinar la cantidad de registros.', $e->getMessage());
            }
            $a = $consulta->obtener_registro();
            $this->cantidad_registros = intval($a['cantidad']);
        }
        // Poner como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

} // Clase Listado

?>
