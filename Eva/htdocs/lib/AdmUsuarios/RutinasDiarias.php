<?php
/**
 * GenesisPHP - AdmUsuarios RutinasDiarias
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
 * Clase RutinasDiarias
 */
class RutinasDiarias {

    protected $sesion;

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        $this->sesion = $in_sesion;
    } // constructor

    /**
     * Ejecutar rutina diaria
     *
     * Pone en cero los contadores de sesiones de los usuarios
     * y bloquea los usuarios con contraseña caducas.
     *
     * @return string Mensajes
     */
    public function ejecutar() {
        // Iniciar arreglo para los mensajes
        $m = array();
        // Poner en cero todas los contadores de sesiones
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $base_datos->comando("UPDATE adm_usuarios SET sesiones_contador = 0 WHERE estatus = 'A'");
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al poner en cero los contadores de sesiones de los usuarios. ', $e->getMessage());
        }
        // Agregar a la bitácora
        $msg      = 'Se pusieron los contadores de sesiones de los usuarios a cero.';
        $m[]      = $msg;
        $bitacora = new \AdmBitacora\Registro($this->sesion);
        $bitacora->agregar_sistema($msg);
        // Bloquear las contraseñas caducas
        try {
            $base_datos->comando(sprintf("UPDATE adm_usuarios SET contrasena_fallas = 254 WHERE contrasena_expira <= '%s'", date('Y-m-d')));
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al bloquear las contraseñas caducas. ', $e->getMessage());
        }
        // Agregar a la bitacora
        $msg = 'Se bloquearon las contraseñas caducas.';
        $m[] = $msg;
        $bitacora->agregar_sistema($msg);
        // Entregar mensajes
        return implode("\n", $m)."\n";
    } // ejecutar

} // Clase RutinasDiarias

?>
