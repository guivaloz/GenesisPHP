<?php
/**
 * GenesisPHP - Usuarios Registro
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

namespace Usuarios;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    //

    /**
     * Consultar
     *
     * @param integer ID del registro
     */
    public function consultar($in_id=false) {
        // Que tenga permiso para consultar
        // Parámetro ID
        // Validar
        // Consultar
        // Si la consulta no entregó nada
        // Obtener resultado de la consulta
        // Si esta eliminado, debe tener permiso para consultarlo
        // Definir propiedades
        // Poner como verdadero el flag de consultado
    } // consultar

    /**
     * Validar
     */
    public function validar() {
        // Validar las propiedades
        // Definir el estatus descrito
    } // validar

    /**
     * Nuevo
     */
    public function nuevo() {
        // Que tenga permiso para agregar
        // Definir propiedades
        // Poner como verdadero el flag de consultado
    } // nuevo

    /**
     * Agregar
     *
     * @return string Mensaje
     */
    public function agregar() {
        // Que tenga permiso para agregar
        // Verificar que NO haya sido consultado
        // Validar
        // Insertar registro en la base de datos
        // Obtener el ID del registro recién insertado
        // Después de insertar se considera como consultado
        // Agregar a la bitácora que hay un nuevo registro
        // Entregar mensaje
    } // agregar

    /**
     * Modificar
     *
     * @return string Mensaje
     */
    public function modificar() {
        // Que tenga permiso para modificar
        // Verificar que haya sido consultado
        // Validar
        // Hay que determinar que va cambiar, para armar el mensaje
        // Si no hay cambios, provoca excepcion de validacion
        // Actualizar registro en la base de datos
        // Agregar a la bitácora que se modificó el registro
        // Entregar mensaje
    } // modificar

    /**
     * Eliminar
     *
     * @return string Mensaje
     */
    public function eliminar() {
        // Que tenga permiso para eliminar
        // Consultar si no lo esta
        // Validar el estatus
        // Cambiar el estatus
        // Entregar mensaje
    } // eliminar

    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
        // Que tenga permiso para recuperar
        // Consultar si no lo esta
        // Validar el estatus
        // Cambiar el estatus
        // Entregar mensaje
    } // recuperar

} // Clase Registro

?>
