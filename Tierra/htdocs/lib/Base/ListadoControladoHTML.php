<?php
/**
 * GenesisPHP - ListadoControladoHTML
 *
 * Copyright 2015 Guillermo Valdés Lozano <guivaloz@movimientolibre.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @package GenesisPHP
 */

namespace Base;

/**
 * Clase ListadoControladoHTML
 */
class ListadoControladoHTML extends ListadoHTML {

    // public $encabezado;
    // public $icono;
    // public $barra;
    // public $estructura;
    // public $listado;
    // public $panal;
    // protected $cabeza;
    // protected $pie;
    // static public $icono_tamano;
    public $limit;
    public $offset;
    public $cantidad_registros;
    public $variables;
    public $viene_listado;       // Se usa en la página, si es verdadero debe mostrar el listado
    protected $controlado_html;

    /**
     * Constructor
     */
    public function __construct() {
        // Iniciamos controlado html
        $this->controlado_html = new ControladoHTML();
        // Tomamos estos valores que pueden venir por el url
        $this->limit              = $this->controlado_html->limit;
        $this->offset             = $this->controlado_html->offset;
        $this->cantidad_registros = $this->controlado_html->cantidad_registros;
        $this->viene_listado      = $this->controlado_html->viene_listado;
    } // constructor

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @param  string Icono opcional
     * @return string HTML
     */
    public function html($in_encabezado='', $in_icono='') {
        // Le entregamos a controlado HTML
        $this->controlado_html->cantidad_registros = $this->cantidad_registros;
        $this->controlado_html->variables          = $this->variables;
        $this->controlado_html->limit              = $this->limit; // PUEDE PONERSE EN CERO PARA QUE NO TENGA BOTONES
        // Definimos el pie de la lista
        $this->pie = $this->controlado_html->html();
        // Ejecutar padre
        return parent::html($in_encabezado, $in_icono);
    } // html

} // Clase ListadoControladoHTML

?>
