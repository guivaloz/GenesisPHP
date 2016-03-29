<?php
/**
 * GenesisPHP - Inicio Autentificar Exception
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
 * Clase AutentificarException
 */
class AutentificarException extends \Exception {

    /**
     * Constructor
     *
     * @param integer ID del usuario, FALSO si no representa a ninguno
     * @param string  Nombre corto
     * @param string  Tipo descrito de la falla de autentificación
     * @param string  Mensaje
     */
    public function __construct($in_usuario, $in_nom_corto, $in_tipo_descrito, $in_mensaje) {
        // Convertir el tipo descrito al caracter del tipo
        switch ($in_tipo_descrito) {
            case 'datos incorrectos':
                $tipo = 'I';
                break;
            case 'usuario no encontrado':
                $tipo = 'N';
                break;
            case 'usuario inactivo':
                $tipo = 'X';
                break;
            case 'contrasena bloqueada':
                $tipo = 'B';
                break;
            case 'contrasena equivocada':
                $tipo = 'E';
                break;
            case 'sesiones maximo':
                $tipo = 'S';
                break;
            case 'no tiene permiso':
                $tipo = 'P';
                break;
            default:
                die("FATAL: El error descrito '$in_tipo_descrito' no está definido.");
        }
        // Insertar registro en la tabla de autentificaciones
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    adm_autentificaciones (usuario, nom_corto, tipo, ip)
                VALUES
                    (%s, %s, %s, %s)",
                ($in_usuario == false) ? 'NULL' : $in_usuario,
                sql_texto($in_nom_corto),
                sql_texto($tipo),
                sql_texto($_SERVER['REMOTE_ADDR'])), true); // Tiene el true para tronar en caso de error
        } catch (\Exception $e) {
            die("Error fatal: Al tratar de insertar evento en autentificaciones.");
        }
        // Ejecutar constructor del padre, se pasa in_mensaje para que pueda obtenerse por getmessage
        parent::__construct($in_mensaje);
    } // constructor

} // Clase AutentificarException

?>
