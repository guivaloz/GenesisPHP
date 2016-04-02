<?php
/**
 * GenesisPHP - Inicio Sesión Salir
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

namespace Inicio;

/**
 * Clase SesionSalir
 */
class SesionSalir extends Sesion {

    // protected $nom_cookie;
    // protected $version_actual;
    // protected $tiempo_expirar;
    // protected $tiempo_renovar;
    // protected $key;
    // public $usuario;
    // public $ingreso;
    // public $nombre;
    // public $nom_corto;
    // public $tipo;
    // public $pagina;
    // public $pagina_permiso;
    // public $permisos;
    // public $listado_renglones;
    // public $menu;

    /**
     * Eliminar registros relacionados con la sesion en cadenero
     */
    private function eliminar_en_cadenero() {
        // Validar id del usuario
        if (!$this->validar_entero($this->usuario)) {
            throw new \Exception('Error: ID del usuario incorrecto.');
        }
        // Eliminar la sesion existente
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                DELETE FROM
                    adm_cadenero
                WHERE
                    usuario = %d",
                $this->usuario));
        } catch (\Exception $e) {
            throw new \Exception('Error: Al tratar de eliminar los registros en cadenero relacionados con la sesión.');
        }
    } // eliminar_en_cadenero

    /**
     * Eliminar sesion
     */
    private function eliminar_sesion() {
        // Validar id del usuario
        if (!$this->validar_entero($this->usuario)) {
            throw new \Exception('Error: ID del usuario incorrecto.');
        }
        // Eliminar la sesion existente
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                DELETE FROM
                    adm_sesiones
                WHERE
                    usuario = %d",
                $this->usuario));
        } catch (\Exception $e) {
            throw new \Exception('Error: Al tratar de eliminar la sesión.');
        }
    } // eliminar_sesion

    /**
     * Registrar salida
     */
    private function registrar_salida() {
        // Validar id del usuario
        if (!$this->validar_entero($this->usuario)) {
            throw new \Exception('Error: ID del usuario incorrecto.');
        }
        // Insertar registro en autentificaciones
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                INSERT INTO
                    adm_autentificaciones
                    (usuario, nom_corto, tipo, ip)
                VALUES
                    (%d, %s, %s, %s)",
                $this->usuario,
                $this->sql_texto($this->nom_corto),
                $this->sql_texto('T'),
                $this->sql_texto($_SERVER['REMOTE_ADDR'])));
        } catch (\Exception $e) {
            throw new \Exception('Error: Al tratar de insertar la autentificación.');
        }
    } // registrar_salida

    /**
     * Salir
     */
    public function salir() {
        // Ejecutar los metodos para salir de la sesion
        $this->validar();              // Validar la cookie
        $this->eliminar_en_cadenero(); // Eliminar registros relacionados en cadenero
        $this->eliminar_sesion();      // Eliminar la sesion
        $this->registrar_salida();     // Registrar la salida del sistema
        $this->eliminar();             // Eliminar la cookie del navegador
    } // salir

} // Clase SesionSalir

?>
