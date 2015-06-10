<?php
/**
 * GenesisPHP - DESCRIPCION
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
 */

// Namespace
namespace Pruebas;

/**
 * Clase CactusDetalleHTML
 */
class CactusDetalleHTML extends CactusRegistro {

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
    protected $javascript = array();

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Definir la barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->nombre;
        $barra->icono      = $this->sesion->menu->icono_en('tierra_pruebas_detalle');
        // Definir la instacia de DetalleHTML con los datos del registro
        $detalle = new \Base\DetalleHTML();
        $detalle->seccion('Clasificación científica');
        $detalle->dato('Reino',       $this->reino);
        $detalle->dato('División',    $this->division);
        $detalle->dato('Clase',       $this->clase);
        $detalle->dato('Orden',       $this->orden);
        $detalle->dato('Familia',     $this->familia);
        $detalle->dato('Subfamilia',  $this->subfamilia);
        $detalle->dato('Tribu',       $this->tribu);
        $detalle->dato('Género',      $this->genero);
        $detalle->dato('Descripción', $this->descripcion);
        $detalle->barra = $barra;
        // Acumular código Javascript
        $this->javascript[] = $detalle->javascript();
        // Entregar código HTML
        return $detalle->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return implode('', $this->javascript);
    } // javascript

} // Clase CactusDetalleHTML

?>
