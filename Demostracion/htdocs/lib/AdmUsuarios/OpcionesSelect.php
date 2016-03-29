<?php
/**
 * GenesisPHP - Usuarios OpcionesSelect
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

namespace AdmUsuarios;

/**
 * Clase OpcionesSelect
 */
class OpcionesSelect {

    /**
     * Opciones para Select
     *
     * @return array Arreglo asociativo, id => descripcion
     */
    public function opciones() {
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("
                SELECT
                    id, nombre
                FROM
                    usuarios
                WHERE
                    estatus = 'A'
                ORDER BY
                    nom_corto ASC");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar usuarios para hacer opciones.', $e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base\ListadoExceptionVacio('Aviso: No se encontraron usuarios en uso.');
        }
        // Juntar como arreglo asociativo
        $a = array();
        foreach ($consulta->obtener_todos_los_registros() as $item) {
            $a[$item['id']] = $item['nombre'];
        }
        // Entregar
        return $a;
    } // opciones

} // Clase OpcionesSelect

?>
