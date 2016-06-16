<?php
/**
 * GenesisPHP - AdmModulos OpcionesSelect
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
 * Clase OpcionesSelect
 */
class OpcionesSelect extends \Base2\OpcionesSelect {

    // protected $sesion;
    // protected $consultado;

    /**
     * Opciones Padre
     *
     * @return array Arreglo asociativo, id => nombre
     */
    public function opciones_padre() {
        // Consultar
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("
                SELECT
                    id, nombre
                FROM
                    adm_modulos
                WHERE
                    padre IS null
                ORDER BY
                    orden ASC");
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar módulos padre para hacer opciones.', $e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base2\ListadoExceptionVacio('Aviso: No se encontraron módulos padre.');
        }
        // Juntar como arreglo asociativo
        $a = array();
        foreach ($consulta->obtener_todos_los_registros() as $item) {
            $a[$item['id']] = $item['nombre'];
        }
        // Entregar
        return $a;
    } // opciones_padre

    /**
     * Opciones para Select
     *
     * @return array Arreglo asociativo, id => descripcion
     */
    public function opciones() {
        // Consultar padres
        $padres = $this->opciones_padre();
        // Consultar
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("
                SELECT
                    id, nombre, padre
                FROM
                    adm_modulos
                WHERE
                    estatus = 'A'
                ORDER BY
                    orden ASC");
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar módulos para hacer opciones.', $e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base2\ListadoExceptionVacio('Aviso: No se encontraron módulos.');
        }
        // Juntar como arreglo asociativo
        $a = array();
        foreach ($consulta->obtener_todos_los_registros() as $item) {
            if ($item['padre'] != '') {
                $a[$item['id']] = $padres[$item['padre']].' > '.$item['nombre'];
            } else {
                $a[$item['id']] = $item['nombre'];
            }
        }
        // Poner en verdadero el flag consultado
        $this->consultado = true;
        // Entregar
        return $a;
    } // opciones

} // Clase OpcionesSelect

?>
