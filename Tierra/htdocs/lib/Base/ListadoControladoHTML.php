<?php
/**
 * GenesisPHP - ListadoControladoHTML
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
    public $limit;
    public $offset;
    public $cantidad_registros;
    public $variables;
    public $viene_listado; // Se usa en la página, si es verdadero debe mostrar el listado
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
     * @return string HTML
     */
    public function html() {
        // Le entregamos a controlado HTML
        $this->controlado_html->cantidad_registros = $this->cantidad_registros;
        $this->controlado_html->variables          = $this->variables;
        $this->controlado_html->limit              = $this->limit; // Puede ponerse en cero para que no tenga botones
        // Definimos el pie de la lista
        $this->pie = $this->controlado_html->html();
        // Ejecutar padre y entregar
        return parent::html();
    } // html

} // Clase ListadoControladoHTML

?>
