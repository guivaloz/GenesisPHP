<?php
/**
 * GenesisPHP - PaginaPruebaDetalleFoto
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

require_once('lib/Base/AutocargadorClases.php');

/**
 * Clase PaginaPruebaDetalleFoto
 */
class PaginaPruebaDetalleFoto extends \Base2\PlantillaWeb {

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
    // public $clave;
    // public $menu;
    // public $contenido;
    // public $javascript;
    protected $sesion; // Instancia de \Inicio\Sesion

    /**
     * Constructor
     */
    public function __construct() {
        // Definir la clave de esta página
        $this->clave = 'tierra_prueba_detalle_foto';
        // Definir el menú
        $this->menu  = new \Inicio\Menu();
        $this->menu->consultar();
        $this->menu->clave = $this->clave;
        // Definir la sesión
        $this->sesion = new \Inicio\Sesion('sistema', $this->menu);
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Detalle del cactus
        $celebridad         = new \Pruebas\CelebridadDetalleWeb($this->sesion);
        $this->contenido[]  = $celebridad->html();
        $this->javascript[] = $celebridad->javascript();
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase PaginaPruebaDetalleFoto

// Ejecutar y mostrar
$pagina = new PaginaPruebaDetalleFoto();
echo $pagina->html();

?>
