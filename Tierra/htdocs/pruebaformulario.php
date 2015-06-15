<?php
/**
 * GenesisPHP - Prueba Formulario
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

require_once('autocargadorclases.php');

/**
 * Clase PaginaPruebaFormulario
 */
class PaginaPruebaFormulario extends \Base\PlantillaHTML {

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
        $this->clave = 'tierra_prueba_formulario';
        // Definir el menú que es fijo
        $this->menu  = new \Pruebas\Menu();
        $this->menu->consultar();
        $this->menu->clave = $this->clave;
        // Definir la sesión, porque es requerida desde \Base\Listado; con el usuario sistema para no usar la BD y le pasamos el menu
        $this->sesion = new \Inicio\Sesion('sistema', $this->menu);
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Formulario de disco
        $disco              = new \Pruebas\DiscoFormularioHTML($this->sesion);
        $this->contenido[]  = $disco->html();
        $this->javascript[] = $disco->javascript();
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase PaginaPruebaFormulario

// Ejecutar y mostrar
$pagina = new PaginaPruebaFormulario();
echo $pagina->html();

?>
