<?php
/**
 * GenesisPHP - Página Inicial
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

require_once('autocargadorclases.php');

/**
 * Clase IndexHTML
 */
class IndexHTML extends \Base\PlantillaHTML {

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
     * @return string HTML
     */
    public function html() {
        // Mensaje de bienvenida
        $mensaje           = new \Base\MensajeHTML('Esta página realiza una serie de pruebas.');
        $mensaje->tipo     = 'tip';
        $this->contenido[] = $mensaje->html('Acerca de esta página');
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase IndexHTML

// Ejecutar y mostrar
$pagina = new IndexHTML();
echo $pagina->html();

?>
