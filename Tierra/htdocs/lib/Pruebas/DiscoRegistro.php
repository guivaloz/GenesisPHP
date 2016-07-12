<?php
/**
 * GenesisPHP - Pruebas DiscoRegistro
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
 * Clase DiscoRegistro
 */
class DiscoRegistro extends \Base2\Registro {

    // protected $sesion;
    // protected $consultado;
    public $titulo;
    public $lanzamiento;
    public $artista;
    public $genero;
    public $canciones_cantidad;
    public $origen;
    public $origen_descrito;
    static public $origen_descripciones = array(
        'N' => 'Nacional',
        'E' => 'Extranjero');
    static public $origen_colores = array(
        'N' => 'verde',
        'E' => 'azul');

    /**
     * Consultar
     */
    public function consultar() {
        // Definir datos del registro
        $this->titulo             = 'Rubber Soul';
        $this->lanzamiento        = '1966-08-05';
        $this->artista            = 'The Beatles';
        $this->genero             = 'Pop Rock';
        $this->canciones_cantidad = 12;
        $this->origen             = 'E';
        $this->origen_descrito    = self::$origen_descripciones[$this->origen];
        // Ya fue consultado
        $this->consultado         = true;
    } // consultar

    /**
     * Encabezado
     *
     * @return string Encabezado
     */
    public function encabezado() {
        return $this->titulo;
    } // encabezado

    /**
     * Validar
     */
    public function validar() {
        // Validar
        if (!\Base2\UtileriasParaValidar::validar_nombre($this->titulo)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Título incorrecto.');
        }
        if (!\Base2\UtileriasParaValidar::validar_fecha($this->lanzamiento)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Lanzamiento incorrecto.');
        }
        if (!\Base2\UtileriasParaValidar::validar_nombre($this->artista)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Artista incorrecto.');
        }
        if (!\Base2\UtileriasParaValidar::validar_nombre($this->genero)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Género incorrecto.');
        }
        if (!\Base2\UtileriasParaValidar::validar_entero($this->canciones_cantidad)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Cantidad de canciones incorrecto.');
        }
        if (!array_key_exists($this->origen, self::$origen_descripciones)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Origen incorrecto.');
        }
        // Definir los descritos
        $this->origen_descrito = self::$origen_descripciones[$this->origen];
    } // validar

} // Clase DiscoRegistro

?>
