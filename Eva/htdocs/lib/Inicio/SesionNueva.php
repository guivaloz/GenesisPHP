<?php
/**
 * GenesisPHP - Inicio Sesión Nueva
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
 * Clase SesionNueva
 */
class SesionNueva extends Sesion {

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
     * Consultar usuario
     */
    private function consultar_usuario() {
        // Validar id del usuario
        if (!\Base2\UtileriasParaValidar::validar_entero($this->usuario)) {
            throw new \Exception('Error: ID del usuario incorrecto.');
        }
        // Consultar usuario
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    nombre, nom_corto, tipo, listado_renglones
                FROM
                    adm_usuarios
                WHERE
                    id = %d",
                $this->usuario));
        } catch (\Exception $e) {
            throw new \Exception('Error: Al tratar de consultar el usuario.');
        }
        if ($consulta->cantidad_registros() == 0) {
            throw new \Exception('Aviso: Usuario no encontrado.');
        }
        $a = $consulta->obtener_registro();
        // Propiedades
        $this->nombre            = $a['nombre'];
        $this->nom_corto         = $a['nom_corto'];
        $this->tipo              = $a['tipo'];
        $this->listado_renglones = intval($a['listado_renglones']);
        // Cambiar la cantidad de renglones en los listados controlados
        \Base2\ControladoWeb::$limit_por_defecto = $this->listado_renglones;
    } // consultar_usuario

    /**
     * Eliminar registros relacionados con la sesion en cadenero
     */
    private function eliminar_en_cadenero() {
        // Validar id del usuario
        if (!\Base2\UtileriasParaValidar::validar_entero($this->usuario)) {
            throw new \Exception('Error: ID del usuario incorrecto.');
        }
        // Eliminar la sesion existente
        $base_datos = new \Base2\BaseDatosMotor();
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
        if (!\Base2\UtileriasParaValidar::validar_entero($this->usuario)) {
            throw new \Exception('Error: ID del usuario incorrecto.');
        }
        // Eliminar la sesion existente
        $base_datos = new \Base2\BaseDatosMotor();
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
     * Insertar sesion
     */
    private function insertar_sesion() {
        // Validar id del usuario
        if (!\Base2\UtileriasParaValidar::validar_entero($this->usuario)) {
            throw new \Exception('Error: ID del usuario incorrecto.');
        }
        // Insertar sesion
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    adm_sesiones
                    (usuario, ingreso, nombre, nom_corto, tipo, listado_renglones)
                VALUES
                    (%d, %s, %s, %s, %s, %d)",
                $this->usuario,
                \Base2\UtileriasParaSQL::sql_tiempo($this->ingreso),
                \Base2\UtileriasParaSQL::sql_texto($this->nombre),
                \Base2\UtileriasParaSQL::sql_texto($this->nom_corto),
                \Base2\UtileriasParaSQL::sql_texto($this->tipo),
                $this->listado_renglones));
        } catch (\Exception $e) {
            throw new \Exception('Error: Al tratar de insertar la sesión.');
        }
    } // insertar_sesion

    /**
     * Registrar entrada
     */
    private function registrar_entrada() {
        // Insertar en autentificaciones
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    adm_autentificaciones
                    (usuario, nom_corto, tipo, ip)
                VALUES
                    (%d, %s, %s, %s)",
                $this->usuario,
                \Base2\UtileriasParaSQL::sql_texto($this->nom_corto),
                \Base2\UtileriasParaSQL::sql_texto('A'),
                \Base2\UtileriasParaSQL::sql_texto($_SERVER['REMOTE_ADDR'])));
        } catch (\Exception $e) {
            throw new \Exception('Error: Al tratar de insertar en autentificaciones el registro de la entrada.');
        }
    } // registrar_entrada

    /**
     * Nueva
     */
    public function nueva() {
        // Ejecutar los metodos para crear una nueva sesion
        $this->validar();              // Validar la cookie
        $this->consultar_usuario();    // Consultar usuario
        $this->eliminar_en_cadenero(); // Eliminar registros en cadenero relacionados con la sesion anterior
        $this->eliminar_sesion();      // Eliminar sesion existente
        $this->insertar_sesion();      // Insertar sesion
        $this->registrar_entrada();    // Registrar entrada al sistema
        // Menu
        $this->menu           = new Menu($this);
        $this->pagina_permiso = $this->menu->permiso_en_pagina($this->pagina);
        $this->permisos       = $this->menu->permisos;
    } // nueva

} // Clase SesionNueva

?>
