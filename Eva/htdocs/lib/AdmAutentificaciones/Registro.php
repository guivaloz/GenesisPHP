<?php
/**
 * GenesisPHP - AdmAutentificaciones Registro
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

namespace AdmAutentificaciones;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    static public $tipo_descripciones = array(
        'I' => 'Datos incorrectos',
        'N' => 'Usuario no encontrado',
        'X' => 'Usuario inactivo',
        'B' => 'Contraseña bloqueda',
        'E' => 'Contraseña equivocada',
        'S' => 'Sesiones máximo',
        'P' => 'No tiene permiso',
        'A' => 'Ingresó',
        'T' => 'Salió');
    static public $tipo_colores = array(
        'I' => 'blanco',
        'N' => 'blanco',
        'X' => 'oscuro',
        'B' => 'naranja',
        'E' => 'rojo',
        'S' => 'amarillo',
        'P' => 'gris',
        'A' => 'verde',
        'T' => 'azul');

    /**
     * Consultar
     *
     * @param integer ID del registro
     */
    public function consultar($in_id=false) {
        // Que tenga permiso para consultar
        if (!$this->sesion->puede_ver('adm_autentificaciones')) {
            throw new \Exception('Aviso: No tiene permiso para consultar autentificaciones.');
        }
        // Parametros
        if ($in_id !== false) {
            $this->id = $in_id;
        }
    } // consultar

} // Clase Registro

?>
