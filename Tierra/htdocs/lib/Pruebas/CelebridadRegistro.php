<?php
/**
 * GenesisPHP - Pruebas CelebridadRegistro
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

namespace Pruebas;

/**
 * Clase CelebridadRegistro
 */
class CelebridadRegistro extends \Base2\Registro {

    // protected $sesion;
    // protected $consultado;
    public $nombre;
    public $sexo;
    public $sexo_descrito;
    public $nacimiento_fecha;
    public $nacimiento_lugar;
    public $nacionalidad;
    static public $sexo_descripciones = array(
        'M' => 'Masculino',
        'F' => 'Femenino');
    static public $sexo_colores = array(
        'M' => 'azul',
        'F' => 'rosa');

    /**
     * Consultar
     */
    public function consultar() {
        // Definir datos del registro
        $this->nombre           = 'Richard M. Stallman';
        $this->sexo             = 'M';
        $this->nacimiento_fecha = '1953-03-16';
        $this->nacimiento_lugar = 'Nueva York, E.E.U.U.';
        $this->nacionalidad     = 'Norteamericano';
        $this->sexo_descrito    = self::$sexo_descripciones[$this->sexo];
        // Ya fue consultado
        $this->consultado       = true;
    } // consultar

    /**
     * Encabezado
     *
     * @return string Encabezado
     */
    public function encabezado() {
        return $this->nombre;
    } // encabezado

} // Clase CelebridadRegistro

?>
