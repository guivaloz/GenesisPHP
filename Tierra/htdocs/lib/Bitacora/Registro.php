<?php
/**
 * GenesisPHP - Bitacora Registro
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

namespace Bitacora;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;

    /**
     * Consultar
     *
     * @param integer ID del registro
     */
    public function consultar($in_id=false) {
        // Que tenga permiso para consultar
        // Parámetro ID
        if ($in_id !== false) {
            $this->id = $in_id;
        }
        // Validar
        // Consultar
        // Si la consulta no entregó nada
        // Obtener resultado de la consulta
        $a = $consulta->obtener_registro();
        // Si esta eliminado, debe tener permiso para consultarlo
        // Definir propiedades
        // Poner como verdadero el flag de consultado
        $this->consultado = true;
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
        $this->consultado = true;
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
        $this->validar();
        // Insertar registro en la base de datos
        // Obtener el ID del registro recién insertado
        // Después de insertar se considera como consultado
        $this->consultado = true;
        // Agregar a la bitácora que hay un nuevo registro
        // Entregar mensaje
        return $msg;
    } // agregar

    /**
     * Modificar
     *
     * @return string Mensaje
     */
    public function modificar() {
        // Que tenga permiso para modificar
        if (!$this->sesion->puede_modificar('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para modificar departamentos.');
        }
        // Verificar que haya sido consultado
        if ($this->consultado == false) {
            throw new \Exception('Error: No ha sido consultado el departamento para modificarlo.');
        }
        // Validar
        $this->validar();
        // Hay que determinar que va cambiar, para armar el mensaje
        $original = new Registro($this->sesion);
        try {
            $original->consultar($this->id);
        } catch (\Exception $e) {
            die('Esto no debería pasar. Error al consultar registro original del departamento.');
        }
        $a = array();
        if ($this->nombre != $original->nombre) {
            $a[] = "nombre \"{$this->nombre}\"";
        }
        if ($this->clave != $original->clave) {
            $a[] = "clave \"{$this->clave}\"";
        }
        if ($this->notas != $original->notas) {
            $a[] = "notas \"{$this->notas}\"";
        }
        if ($this->estatus != $original->estatus) {
            $a[] = "estatus \"{$this->estatus_descrito}\"";
        }
        // Si no hay cambios, provoca excepcion de validacion
        if (count($a) == 0) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No hay cambios.');
        } else {
            $msg = "Modificado el departamento {$this->nombre} con ".implode(', ', $a);
        }
        // Actualizar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    departamentos
                SET
                    nombre=%s, clave=%s, notas=%s, estatus=%s
                WHERE
                    id=%u",
                $this->sql_texto($this->nombre),
                $this->sql_texto($this->clave),
                $this->sql_texto($this->notas),
                $this->sql_texto($this->estatus),
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al actualizar el departamento. ', $e->getMessage());
        }
        // Agregar a la bitácora que se modificó el registro
        $bitacora = new \Bitacora\Registro($this->sesion);
        $bitacora->agregar_modificado($this->id, $msg);
        // Entregar mensaje
        return $msg;
    } // modificar

    /**
     * Eliminar
     *
     * @return string Mensaje
     */
    public function eliminar() {
        // Que tenga permiso para eliminar
        if (!$this->sesion->puede_eliminar('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para eliminar departamentos.');
        }
        // Consultar si no lo esta
        if (!$this->consultado) {
            $this->consultar(); // PUEDE PROVOCAR UNA EXCEPCION
        }
        // Validar el estatus
        if ($this->estatus == 'B') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede eliminarse el departamento porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'B';
        $this->modificar();
        // Entregar mensaje
        return "Se ha eliminado el departamento {$this->nombre}";
    } // eliminar

    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
        // Que tenga permiso para recuperar
        if (!$this->sesion->puede_recuperar('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para recuperar departamentos.');
        }
        // Consultar si no lo esta
        if (!$this->consultado) {
            $this->consultar(); // PUEDE PROVOCAR UNA EXCEPCION
        }
        // Validar el estatus
        if ($this->estatus == 'A') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede recuperarse el departamento porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'A';
        $this->modificar();
        // Entregar mensaje
        return "Se ha recuperado el departamento {$this->nombre}";
    } // recuperar

} // Clase Registro

?>
