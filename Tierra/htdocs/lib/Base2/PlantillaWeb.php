<?php
/**
 * GenesisPHP - PlantillaWeb
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
 * Clase abstracta PlantillaWeb
 */
abstract class PlantillaWeb extends \Configuracion\PlantillaWebConfig {

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
     * @return string Código HTML
     */
    public function html() {
        // Definir la plantilla según el modelo de diseño
        switch ($this->modelo) {
            case 'ingreso':
                $plantilla = new TemaWebIngreso();
                $plantilla->modelo_ingreso_logos = $this->modelo_ingreso_logos;
                break;
            case 'simple':
                $plantilla = new TemaWebSimple();
                $plantilla->titulo              = $this->titulo;
                $plantilla->menu_principal_logo = $this->menu_principal_logo;
                $plantilla->icono               = $this->icono;
                $plantilla->menu                = $this->menu;
                break;
            case 'dashboard':
                $plantilla = new TemaWebDashboard();
                $plantilla->titulo              = $this->titulo;
                $plantilla->menu_principal_logo = $this->menu_principal_logo;
                $plantilla->icono               = $this->icono;
                $plantilla->menu                = $this->menu;
                break;
            case 'fluida':
            case 'fluido':
                $plantilla = new TemaWebFluido();
                $plantilla->titulo              = $this->titulo;
                $plantilla->menu_principal_logo = $this->menu_principal_logo;
                $plantilla->modelo_fluido_logos = $this->modelo_fluido_logos;
                $plantilla->icono               = $this->icono;
                $plantilla->menu                = $this->menu;
                break;
            case 'sbadmin2':
            default:
                $plantilla = new TemaWebSBAdmin2();
                $plantilla->titulo              = $this->titulo;
                $plantilla->menu_principal_logo = $this->menu_principal_logo;
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
        // Evitar que se guarde en el cache del navegador
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // Entregar
        return $plantilla->html();
    } // html

} // Clase abstracta PlantillaWeb

?>
