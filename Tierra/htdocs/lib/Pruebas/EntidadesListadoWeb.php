<?php
/**
 * GenesisPHP - EntidadesListadoWeb
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

namespace Pruebas;

/**
 * Clase EntidadesListadoWeb
 */
class EntidadesListadoWeb extends EntidadesListado implements \Base2\SalidaWeb {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    protected $listado_controlado;

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar ListadoControladoHTML
        $this->listado_controlado = new \Base2\ListadoWebControlado();
        // Cargar la estructura
        $this->listado_controlado->estructura = array(
            'nombre'    => array('enca' => 'Estado'),
            'capital'   => array('enca' => 'Capital'),
            'poblacion' => array('enca' => 'Población'),
            'fundacion' => array('enca' => 'Fecha de fundación'));
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

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
        // Cargar Listado Controlado
        $this->listado_controlado->encabezado = $this->encabezado();
        $this->listado_controlado->icono      = $this->sesion->menu->icono_en('tierra_prueba_listado');
        $this->listado_controlado->listado    = $this->listado;
        // Entregar
        return $this->listado_controlado->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->listado_controlado->javascript();
    } // javascript

} // Clase EntidadesListadoWeb

?>
