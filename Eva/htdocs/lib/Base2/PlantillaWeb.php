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
    // protected $css_comun;
    // protected $favicon;
    // protected $modelo;
    // protected $menu_principal_logo;
    // protected $modelo_ingreso_logos;
    // protected $modelo_fluido_logos;
    // protected $pie;
    // protected $css_comun;
    // protected $javascript_comun;
    public $clave;                 // Clave única de la página
    public $menu;                  // Instancia de menú
    public $contenido  = array();  // Arreglo o texto con el contenido HTML
    public $javascript = array();  // Arreglo o texto con el código Javascript

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
        // Pasar parámetros comunes a todas las plantillas
        $plantilla->sistema         = $this->sistema;
        $plantilla->descripcion      = $this->descripcion;
        $plantilla->autor            = $this->autor;
        $plantilla->css_comun        = $this->css_comun;
        $plantilla->favicon          = $this->favicon;
        $plantilla->pie              = $this->pie;
        $plantilla->javascript_comun = $this->javascript_comun;
        // Procesar CSS
        if (is_array($this->css) && (count($this->css) > 0)) {
            $a = array();
            foreach ($this->css as $c) {
                if ($c != '') {
                    $a[] = $c;
                }
            }
            $plantilla->css = implode("\n", $a);
        } elseif (is_string($this->css) && ($this->css != '')) {
            $plantilla->css = "  <link href=\"{$this->css}\" rel=\"stylesheet\" type=\"text/css\">";
        } else {
            $plantilla->css = "  <!-- Pagina sin CSS adicional. -->";
        }
        // Procesar contenido
        if (is_array($this->contenido) && (count($this->contenido) > 0)) {
            $a = array();
            foreach ($this->contenido as $c) {
                if ($c != '') {
                    $a[] = $c;
                }
            }
            $plantilla->contenido = implode("\n", $a);
        } elseif (is_string($this->contenido) && ($this->contenido != '')) {
            $plantilla->contenido = $this->contenido;
        } else {
            $plantilla->contenido = "  <b>Pagina sin contenido.</b>";
        }
        // Procesar Javascript
        if (is_array($this->javascript) && (count($this->javascript) > 0)) {
            $a = array();
            foreach ($this->javascript as $js) {
                if ($js != '') {
                    $a[] = "  <script>$js</script>";
                }
            }
            $plantilla->javascript = implode("\n", $a);
        } elseif (is_string($this->javascript) && ($this->javascript != '')) {
            $plantilla->javascript = $this->javascript;
        } else {
            $plantilla->javascript = '  <!-- Pagina sin Javascipt adicional. -->';
        }
        // Evitar que se guarde en el cache del navegador
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // Entregar
        return $plantilla->html();
    } // html

} // Clase abstracta PlantillaWeb

?>
