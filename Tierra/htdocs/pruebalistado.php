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

require_once('autocargadorclases.php');

/**
 * Clase PruebaListado
 */
class PruebaListado extends \Base\PlantillaHTML {

    /**
     * Constructor
     */
    public function __construct() {
        // MENU
        $this->menu = new \Pruebas\Menu();
        $this->menu->alimentar_menu_pruebas();
        $this->menu->clave = 'tierra_pruebas_detalles';
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        //
        // Mensaje de bienvenida
        //
        $mensaje           = new \Base\MensajeHTML('Esta página realiza una serie de pruebas a ListadoHTML.');
        $mensaje->tipo     = 'tip';
        $this->contenido[] = $mensaje->html('Acerca de esta página');
        //
        // Prueba Listado
        //
        $listado             = new \Base\ListadoHTML();
     // $listado->estructura = ;
     // $listado->listado    = ;
        $this->contenido[]   = $listado->html('Entedades Federativas de México');
        $this->javascript[]  = $listado->javascript();
        //
        // Entregar
        //
        return parent::html();
    } // html

} // Clase PruebaListado

// EJECUTAR Y MOSTRAR
$pagina = new PruebaListado();
echo $pagina->html();

?>
