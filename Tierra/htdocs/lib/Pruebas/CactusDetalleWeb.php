<?php
/**
 * GenesisPHP - Pruebas CactusDetalleWeb
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
 * Clase CactusDetalleWeb
 */
class CactusDetalleWeb extends CactusRegistro {

    // protected $sesion;
    // protected $consultado;
    // public $nombre;
    // public $reino;
    // public $division;
    // public $clase;
    // public $orden;
    // public $familia;
    // public $subfamilia;
    // public $tribu;
    // public $genero;
    // public $descripcion;
    protected $detalle;

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar DetalleWeb
        $this->detalle = new \Base2\DetalleWeb();
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Si no se ha consultado
        if (!$this->consultado) {
            $this->consultar();
        }
        // Cargar Detalle
        $this->detalle->encabezado = $this->nombre;
        $this->detalle->icono      = $this->sesion->menu->icono_en('tierra_prueba_detalle');
        $this->detalle->seccion('Clasificación científica');
        $this->detalle->dato('Reino',       $this->reino);
        $this->detalle->dato('División',    $this->division);
        $this->detalle->dato('Clase',       $this->clase);
        $this->detalle->dato('Orden',       $this->orden);
        $this->detalle->dato('Familia',     $this->familia);
        $this->detalle->dato('Subfamilia',  $this->subfamilia);
        $this->detalle->dato('Tribu',       $this->tribu);
        $this->detalle->dato('Género',      $this->genero);
        $this->detalle->dato('Descripción', $this->descripcion);
        // Entregar
        return $this->detalle->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->detalle->javascript();
    } // javascript

} // Clase CactusDetalleWeb

?>
