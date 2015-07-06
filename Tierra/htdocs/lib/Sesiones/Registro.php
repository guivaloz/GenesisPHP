<?php
/**
 * GenesisPHP - Sesiones Registro
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

namespace Sesiones;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $nombre;
    public $nom_corto;
    public $tipo;
    public $ingreso;
    public $listado_renglones;

    /**
     * Consultar
     *
     * @param integer ID del usuario
     */
    public function consultar($in_usuario=false) {
        // Que tenga permiso para consultar
        if (!$this->sesion->puede_ver('sesiones')) {
            throw new \Exception('Aviso: No tiene permiso para consultar la sesión.');
        }
        // Parámetro ID
        if ($in_usuario !== false) {
            $this->usuario = $in_usuario;
        }
        // Validar
        if (!$this->validar_entero($this->usuario)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar la sesión por ID de usuario incorrecto.');
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    nombre, nom_corto, tipo,
                    to_char(ingreso, 'YYYY-MM-DD, HH24:MI') as ingreso,
                    listado_renglones
                FROM
                    sesiones
                WHERE
                    usuario = %u",
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar la sesión.', $e->getMessage());
        }
        // Si la consulta no entregó nada
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró la sesión.');
        }
        // Obtener resultado de la consulta
        $a = $consulta->obtener_registro();
        // Definir propiedades
        $this->nombre            = $a['nombre'];
        $this->nom_corto         = $a['nom_corto'];
        $this->tipo              = $a['tipo'];
        $this->ingreso           = $a['ingreso'];
        $this->listado_renglones = $a['listado_renglones'];
        // Poner como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

} // Clase Registro

?>
