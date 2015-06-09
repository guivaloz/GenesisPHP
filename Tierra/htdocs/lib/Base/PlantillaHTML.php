<?php
/**
 * GenesisPHP - PlantillaHTML
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
 * Clase PlantillaHTML
 */
class PlantillaHTML extends \Configuracion\PlantillaHTMLConfig {

    // protected $sistema;
    // protected $titulo;
    // protected $descripcion;
    // protected $autor;
    // protected $css;
    // protected $favicon;
    // protected $modelo;
    // protected $menu_principal_logo;
    // protected $modelo_ingreso_logos;
    // protected $modelo_fluido_logos;
    // protected $pie;
    public $clave;                 // Clave única de la página
    public $menu;                  // Instancia de menú
    public $contenido  = array();  // Arreglo con el contenido
    public $javascript = array();  // Arreglo con el Javascript

    /**
     * HTML
     *
     * @return string HTML con la pagina web
     */
    public function html() {
        // Definir la plantilla según el modelo de diseño
        switch ($this->modelo) {
            case 'ingreso':
                $plantilla = new \Base\TemaIngresoHTML();
                $plantilla->modelo_ingreso_logos = $this->modelo_ingreso_logos;
                break;
            case 'simple':
                $plantilla = new \Base\TemaSimpleHTML();
                $plantilla->titulo              = $this->titulo;
                $plantilla->menu_principal_logo = $this->menu_principal_logo;
                $plantilla->icono               = $this->icono;
                $plantilla->menu                = $this->menu;
                break;
            case 'dashboard':
                $plantilla = new \Base\TemaDashboardHTML();
                $plantilla->titulo              = $this->titulo;
                $plantilla->menu_principal_logo = $this->menu_principal_logo;
                $plantilla->icono               = $this->icono;
                $plantilla->menu                = $this->menu;
                break;
            case 'fluida':
            case 'fluido':
            default:
                $plantilla = new \Base\TemaFluidoHTML();
                $plantilla->titulo              = $this->titulo;
                $plantilla->menu_principal_logo = $this->menu_principal_logo;
                $plantilla->modelo_fluido_logos = $this->modelo_fluido_logos;
                $plantilla->icono               = $this->icono;
                $plantilla->menu                = $this->menu;
        }
        // Pasar parámetros a la plantilla
        $plantilla->sistema     = $this->sistema;
        $plantilla->descripcion = $this->descripcion;
        $plantilla->autor       = $this->autor;
        $plantilla->css         = $this->css;
        $plantilla->favicon     = $this->favicon;
        $plantilla->contenido   = $this->contenido;
        $plantilla->javascript  = $this->javascript;
        $plantilla->pie         = $this->pie;
        // Entregar
        return $plantilla->html();
    } // html

} // Clase PlantillaHTML

?>
