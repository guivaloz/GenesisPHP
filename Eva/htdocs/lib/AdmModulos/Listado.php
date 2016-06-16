<?php
/**
 * GenesisPHP - AdmModulos Listado
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

namespace AdmModulos;

/**
 * Clase Listado
 */
class Listado extends \Base2\Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    public $nombre;                      // Filtro, texto
    public $clave;                       // Filtro, texto
    public $permiso_maximo;              // Filtro, entero
    public $poder_minimo;                // Filtro, entero
    public $estatus;                     // Filtro, caracter
    static public $param_nombre         = 'mn';
    static public $param_clave          = 'mc';
    static public $param_permiso_maximo = 'mp';
    static public $param_poder_minimo   = 'mo';
    static public $param_estatus        = 'mt';
    public $filtros_param;

    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        if (!$this->sesion->puede_ver('adm_modulos')) {
            throw new \Exception('Aviso: No tiene permiso para ver los módulos.');
        }
        // Validar filtros
        if (($this->nombre != '') && !\Base2\UtileriasParaValidar::validar_nombre($this->nombre)) {
            throw new \Base2\ListadoExceptionValidacion('Aviso: Nombre incorrecto.');
        }
        if (($this->clave != '') && !\Base2\UtileriasParaValidar::validar_nombre($this->clave)) {
            throw new \Base2\ListadoExceptionValidacion('Aviso: Clave incorrecta.');
        }
        if (($this->permiso_maximo != '') && !array_key_exists($this->permiso_maximo, Registro::$permiso_maximo_descripciones)) {
            throw new \Base2\ListadoExceptionValidacion('Aviso: Permiso máximo incorrecto.');
        }
        if (($this->poder_minimo != '') && !array_key_exists($this->poder_minimo, Registro::$poder_minimo_descripciones)) {
            throw new \Base2\ListadoExceptionValidacion('Aviso: Poder mínimo incorrecto.');
        }
        if (($this->estatus != '') && !array_key_exists($this->estatus, Registro::$estatus_descripciones)) {
            throw new \Base2\ListadoExceptionValidacion('Aviso: Estatus incorrecto.');
        }
        // Reseteamos el arreglo asociativo
        $this->filtros_param = array();
        // Pasar los filtros como parametros de los botones
        if ($this->nombre != '') {
            $this->filtros_param[self::$param_nombre] = $this->nombre;
        }
        if ($this->clave != '') {
            $this->filtros_param[self::$param_clave] = $this->clave;
        }
        if ($this->permiso_maximo != '') {
            $this->filtros_param[self::$param_permiso_maximo] = $this->permiso_maximo;
        }
        if ($this->poder_minimo != '') {
            $this->filtros_param[self::$param_poder_minimo] = $this->poder_minimo;
        }
        if ($this->estatus != '') {
            $this->filtros_param[self::$param_estatus] = $this->estatus;
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
        if ($this->clave != '') {
            $e[] = "clave {$this->clave}";
        }
        if ($this->permiso_maximo != '') {
            $e[] = "permiso máximo ".Registro::$permiso_maximo_descripciones[$this->permiso_maximo];
        }
        if ($this->poder_minimo != '') {
            $e[] = "poder mínimo ".Registro::$poder_minimo_descripciones[$this->poder_minimo];
        }
        if ($this->estatus != '') {
            $e[] = "estatus ".Registro::$estatus_descripciones[$this->estatus];
        }
        // Definimos el encabezado
        if (count($e) > 0) {
            if ($this->cantidad_registros > 0) {
                $encabezado = sprintf('%d Módulos con %s', $this->cantidad_registros, implode(", ", $e));
            } else {
                $encabezado = sprintf('Módulos con %s', implode(", ", $e));
            }
        } else {
            $encabezado = 'Módulos';
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
        if ($this->clave != '') {
            $filtros[] = "clave ILIKE '%{$this->clave}%'";
        }
        if ($this->permiso_maximo != '') {
            $filtros[] = "permiso_maximo = {$this->permiso_maximo}";
        }
        if ($this->poder_minimo != '') {
            $filtros[] = "poder_minimo = {$this->poder_minimo}";
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
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    id, orden, clave, nombre, pagina, icono, padre, permiso_maximo, poder_minimo, estatus
                FROM
                    adm_modulos
                %s
                ORDER BY
                    orden ASC
                %s",
                $filtros_sql,
                $this->limit_offset_sql()));
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar módulos para hacer listado.', $e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base2\ListadoExceptionVacio('Aviso: No se encontraron registros en módulos.');
        }
        // Pasamos la consulta a la propiedad listado
        $this->listado = $consulta->obtener_todos_los_registros();
        // Consultar la cantidad de registros
        if (($this->limit > 0) && ($this->cantidad_registros == 0)) {
            try {
                $consulta = $base_datos->comando(sprintf("
                    SELECT
                        COUNT(id) AS cantidad
                    FROM
                        adm_modulos
                    %s",
                    $filtros_sql));
            } catch (\Exception $e) {
                throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar los módulos para determinar la cantidad de registros.', $e->getMessage());
            }
            $a = $consulta->obtener_registro();
            $this->cantidad_registros = intval($a['cantidad']);
        }
    } // consultar

} // Clase Listado

?>
