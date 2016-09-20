<?php
/**
 * GenesisPHP - Prueba Lenguetas
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

require_once('lib/Base2/AutocargadorClases.php');

/**
 * Clase PaginaPruebaLenguetas
 */
class PaginaPruebaLenguetas extends \Base2\PlantillaWeb {

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
        $this->clave = 'tierra_prueba_lenguetas';
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
        // Lenguetas
        $lenguetas = new \Base2\LenguetasWeb('pruebaLenguetas');
        $lenguetas->agregar('Detalle',    new \Pruebas\CactusDetalleWeb($this->sesion), TRUE);
        $lenguetas->agregar('Con Foto',   new \Pruebas\CelebridadDetalleWeb($this->sesion));
        $lenguetas->agregar('Listado',    new \Pruebas\EntidadesListadoWeb($this->sesion));
        $lenguetas->agregar('Formulario', new \Pruebas\DiscoFormularioWeb($this->sesion));
        $this->contenido[]  = $lenguetas->html();
        $this->javascript[] = $lenguetas->javascript();
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase PaginaPruebaLenguetas

// Ejecutar y mostrar
$pagina_web = new PaginaPruebaLenguetas();
echo $pagina_web->html();

?>
