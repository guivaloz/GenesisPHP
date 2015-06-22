<?php
/**
 * GenesisPHP - Modulos ListadoHTML
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

namespace Modulos;

/**
 * Clase ListadoHTML
 */
class ListadoHTML extends Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    //
    // public $filtros_param;
    public $viene_listado = false; // Sirve para que en PaginaHTML se de cuenta de que viene el listado
    protected $estructura;         // Estructura del listado
    protected $listado_controlado; // Instancia de ListadoControladoHTML

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar ListadoControladoHTML
        $this->listado_controlado = new \Base\ListadoControladoHTML();
        // Cargar la estructura
        // Tomar parámetros que pueden venir en el URL
        // Si algún filtro tiene valor, entonces viene_listado será verdadero
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Si no se ha consultado
        if (!$this->consultado) {
            $this->consultar();
        }
        // Eliminar columnas de la estructura que sean filtros aplicados
        // Cargar en listado_controlado
        // Entregar
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->listado_controlado->javascript();
    } // javascript

} // Clase ListadoHTML

?>
