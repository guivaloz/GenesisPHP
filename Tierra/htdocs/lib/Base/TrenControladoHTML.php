<?php
/**
 * GenesisPHP - TrenControladoHTML
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

namespace Base;

/**
 * Clase TrenControladoHTML
 */
class TrenControladoHTML extends TrenHTML {

    // public $encabezado;
    // public $icono;
    // public $barra;
    // public $vagones;
    // public $div_class;
    // public $columnas;
    // protected $cabeza;
    // protected $pie;
    // protected $javascript;
    public $limit;
    public $offset;
    public $cantidad_registros;
    public $variables;
    public $viene_tren; // Se usa en la pagina, si es verdadero debe mostrar el tren
    protected $controlado_html;

    /**
     * Constructor
     *
     * @param array Opcional, vagones es un arreglo de objetos con un método html
     */
    public function __construct($in_vagones=false) {
        // Iniciamos controlado html
        $this->controlado_html = new ControladoHTML();
        // Tomamos estos valores que pueden venir por el url
        $this->limit              = $this->controlado_html->limit;
        $this->offset             = $this->controlado_html->offset;
        $this->cantidad_registros = $this->controlado_html->cantidad_registros;
        $this->viene_tren         = $this->controlado_html->viene_listado;
        // Ejecutamos al padre
        parent::__construct($in_vagones);
    } // constructor

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @param  string Icono opcional
     * @return string HTML
     */
    public function html($in_encabezado='', $in_icono='') {
        // Le entregamos las variables
        $this->controlado_html->cantidad_registros = $this->cantidad_registros;
        $this->controlado_html->variables          = $this->variables;
        $this->controlado_html->limit              = $this->limit; // PUEDE PONERSE EN CERO PARA QUE NO TENGA BOTONES
        // El pie del tren son los botones de control
        $this->pie = $this->controlado_html->html();
        // Ejecutar padre y entregar
        return parent::html($in_encabezado, $in_icono);
    } // html

} // Clase TrenControladoHTML

?>
