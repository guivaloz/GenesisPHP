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

    protected $adan; // Instancia con la Semilla, es heredera de Adan

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
    public function probar($libreria) {
        // De acuerdo a lo pedido
        switch (strtolower($libreria)) {
            case 'registro':
                $registro = new Registro($this->adan);
                return $registro->php();
            case 'detallehtml':
                $detalle_html = new DetalleHTML($this->adan);
                return $detalle_html->php();
            case 'formulariohtml':
                $formulario_html = new FormularioHTML($this->adan);
                return $formulario_html->php();
            case 'listado':
                $listado = new Listado($this->adan);
                return $listado->php();
            case 'listadohtml':
                $listado_html = new ListadoHTML($this->adan);
                return $listado_html->php();
            case 'trenhtml':
                $tren_html = new TrenHTML($this->adan);
                return $tren_html->php();
            case 'busquedahtml':
                $busqueda_html = new BusquedaHTML($this->adan);
                return $busqueda_html->php();
            case 'opcionesselect':
                $opciones_select = new OpcionesSelect($this->adan);
                return $opciones_select->php();
            case 'paginahtml':
                $pagina_html = new PaginaHTML($this->adan);
                return $pagina_html->php();
            case 'raiz':
                $raiz = new Raiz($this->adan);
                return $raiz->php();
            default:
                return "ERROR en Probador: No esta definida la librería $libreria";
        }
    } // probar

} // Clase Probador

?>
