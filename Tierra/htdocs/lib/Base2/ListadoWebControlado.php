<?php
/**
 * GenesisPHP - ListadoWebControlado
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

namespace Base2;

/**
 * Clase ListadoWebControlado
 */
class ListadoWebControlado extends ListadoWeb {

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
    protected $controlado_web;

    /**
     * Constructor
     */
    public function __construct() {
        // Iniciamos controlado web
        $this->controlado_web     = new ControladoWeb();
        // Tomamos estos valores que pueden venir por el url
        $this->limit              = $this->controlado_web->limit;
        $this->offset             = $this->controlado_web->offset;
        $this->cantidad_registros = $this->controlado_web->cantidad_registros;
        $this->viene_listado      = $this->controlado_web->viene_listado;
    } // constructor

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string Código HTML
     */
    public function html($in_encabezado='') {
        // Le entregamos a controlado web
        $this->controlado_web->cantidad_registros = $this->cantidad_registros;
        $this->controlado_web->variables          = $this->variables;
        $this->controlado_web->limit              = $this->limit; // Puede ponerse en cero para que no tenga botones
        // Definir el pie con los botones anterior-siguiente
        $this->pie = $this->controlado_web->html();
        // Ejecutar padre y entregar
        return parent::html($in_encabezado);
    } // html

} // Clase ListadoWebControlado

?>
