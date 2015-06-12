<?php
/**
 * GenesisPHP - Disco DetalleHTML
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

namespace Pruebas;

/**
 * Clase DiscoDetalleHTML
 */
class DiscoDetalleHTML extends DiscoRegistro {

    // protected $sesion;
    // protected $consultado;
    // public $titulo;
    // public $lanzamiento;
    // public $artista;
    // public $genero;
    // public $canciones_cantidad;
    // public $origen;
    // public $origen_descrito;
    // static public $origen_descripciones;
    // static public $origen_colores;
    protected $javascript = array();

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
        // Definir la barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->nombre;
        $barra->icono      = $this->sesion->menu->icono_en('tierra_prueba_formulario');
        // Definir la instacia de DetalleHTML con los datos del registro
        $detalle = new \Base\DetalleHTML();
        $detalle->seccion('Disco');
        $detalle->dato('Título',                $this->titulo);
        $detalle->dato('Lanzamiento',           $this->lanzamiento);
        $detalle->dato('Artista',               $this->artista);
        $detalle->dato('Género',                $this->genero);
        $detalle->dato('Cantidad de canciones', $this->canciones_cantidad);
        $detalle->dato('Origen',                $this->origen_descrito);
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

} // Clase DiscoDetalleHTML

?>
