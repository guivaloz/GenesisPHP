<?php
/**
 * GenesisPHP - Base Probador
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

namespace Base;

/**
 * Clase Probador
 */
class Probador {

    public $adan; // Instancia con la Semilla, es heredera de Adan

    /**
     * Constructor
     *
     * @param mixed Instancia con la Semilla, que es heredera de Adan
     */
    public function __construct(\Arbol\Adan $semilla) {
        $this->adan = $semilla;
    } // constructor

    /**
     * Probar
     *
     * @param  string Texto que identifica a la librería a probar
     * @return string Codigo PHP
     */
    public function crear($libreria) {
        // De acuerdo a lo pedido
        switch (strtolower($libreria)) {
            case 'registro':
                $registro = new \Base\Registro($this->adan);
                return $registro->php();
            case 'detallehtml':
                $detalle_html = new \Base\DetalleHTML($this->adan);
                return $detalle_html->php();
            //~ case 'formulariohtml':
                //~ $libreria = new \Base\FormularioHTML($this->adan);
                //~ return $libreria->php();
            case 'listado':
                $listado = new \Base\Listado($this->adan);
                return $listado->php();
            case 'listadohtml':
                $listado_html = new \Base\ListadoHTML($this->adan);
                return $listado_html->php();
            //~ case 'busquedahtml':
                //~ $libreria = new \Base\BusquedaHTML($this->adan);
                //~ return $libreria->php();
            //~ case 'trenhtml':
                //~ $libreria = new \Base\TrenHTML($this->adan);
                //~ return $libreria->php();
            //~ case 'mapahtml':
                //~ $libreria = new \Base\MapaHTML($this->adan);
                //~ return $libreria->php();
            case 'opcionesselect':
                $opciones_select = new \Base\OpcionesSelect($this->adan);
                return $opciones_select->php();
            //~ case 'pagina':
                //~ $pagina = new \Base\Pagina($this->adan);
                //~ return $pagina->php();
            case 'raiz':
                $raiz = new \Base\Pagina($this->adan);
                return $raiz->php();
            default:
                return "ERROR en Probador: No esta definida la librería $libreria";
        }
    } // crear

} // Clase Probador

?>
