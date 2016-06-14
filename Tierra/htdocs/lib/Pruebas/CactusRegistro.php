<?php
/**
 * GenesisPHP - Pruebas CactusRegistro
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
 * Clase CactusRegistro
 */
class CactusRegistro extends \Base2\Registro {

    // protected $sesion;
    // protected $consultado;
    public $nombre;
    public $reino;
    public $division;
    public $clase;
    public $orden;
    public $familia;
    public $subfamilia;
    public $tribu;
    public $genero;
    public $descripcion;

    /**
     * Consultar
     */
    public function consultar() {
        // Definir datos del registro
        $this->nombre      = 'Mammillaria';
        $this->reino       = 'Plantae';
        $this->division    = 'Magnoliophyta';
        $this->clase       = 'Magnoliopsida';
        $this->orden       = 'Caryophyllales';
        $this->familia     = 'Cactaceae';
        $this->subfamilia  = 'Cactoideae';
        $this->tribu       = 'Cacteae';
        $this->genero      = 'Mammillaria';
        $this->descripcion = 'Mammillaria es uno de los géneros de cactus más grandes de la familia Cactaceae, contiene más de 350 especies y variedades reconocidas. Su especie tipo fue descrita por vez primera por Carolus Linnaeus como Cactus mammillaris en 1753, nombre derivado del latín mammilla = tubérculo, en alusión a los tubérculos que son una de las características del género.';
        // Ya fue consultado
        $this->consultado = true;
    } // consultar

    /**
     * Encabezado
     */
    public function encabezado() {
        return $this->nombre;
    } // encabezado

} // Clase CactusRegistro

?>
