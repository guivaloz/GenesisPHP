<?php
/**
 * GenesisPHP - EntidadesListadoHTML
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

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
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
        // Definir la instancia de BarraHTML
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->encabezado();
        $barra->icono      = $this->sesion->menu->icono_en('tierra_prueba_listado');
        // Definir la instancia de ListadoHTML
        $listado             = new \Base\ListadoHTML();
        $listado->barra      = $barra;
        $listado->estructura = array(
            'nombre'    => array('enca' => 'Estado'),
            'capital'   => array('enca' => 'Capital'),
            'poblacion' => array('enca' => 'Población'),
            'fundacion' => array('enca' => 'Fecha de fundación'));
        $listado->listado    = $this->listado; // Observe que es la propiedad que trae desde EntidadesListado los datos
        // Acumular código Javascript
        $this->javascript[]  = $listado->javascript();
        // Entregar código HTML
        return $listado->html();
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
