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

namespace Pruebas;

/**
 * Clase EntidadesListadoHTML
 */
class EntidadesListadoHTML extends EntidadesListado {

    protected $estructura;
    protected $listado_controlado;
    protected $javascript = array();

    /**
     * Constructor
     */
    public function __construct() {
        // Definir estructura
        $this->estructura = array(
            'nombre'    => array('enca' => 'Estado'),
            'capital'   => array('enca' => 'Capital'),
            'poblacion' => array('enca' => 'Población', 'formato' => 'entero'),
            'fundacion' => array('enca' => 'Fecha de fundación'));
        // Definir el listado controlado
        $this->listado_controlado = new \Base\ListadoControladoHTML();
        // Estos parámetros pueden venir por el URL
        $this->limit              = $this->listado_controlado->limit;
        $this->offset             = $this->listado_controlado->offset;
        $this->cantidad_registros = $this->listado_controlado->cantidad_registros;
        // Ejecutar este método en el padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Consultar
        $this->consultar();
        // Definir la instancia de BarraHTML
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->encabezado();
        $barra->icono      = $this->sesion->menu->icono_en('tierra_pruebas_listado');
        // Definir propiedades de listado controlado
        $this->listado_controlado->estructura = $this->estructura;
        $this->listado_controlado->listado    = $this->listado;
        $this->listado_controlado->barra      = $barra;
        // Acumular código Javascript
        $this->javascript[] = $this->listado_controlado->javascript();
        // Entregar código HTML
        return $this->listado_controlado->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return implode('', $this->javascript);
    } // javascript

} // Clase EntidadesListadoHTML

?>
