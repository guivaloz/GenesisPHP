<?php
/**
 * GenesisPHP - Autentificaciones Página HTML
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

namespace Autentificaciones;

/**
 * Clase PaginaHTML
 */
class PaginaHTML extends \Base\PaginaHTML {

    // protected $sistema;
    // protected $titulo;
    // protected $descripcion;
    // protected $autor;
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
    // protected $sesion;
    // protected $sesion_exitosa;
    // protected $usuario;
    // protected $usuario_nombre;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('autentificaciones');
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Sólo si se carga con éxito la sesión
        if ($this->sesion_exitosa) {
            // Lengüetas
            $lenguetas = new \Base\LenguetasHTML('lenguetasAutentificaciones');
            // Listados
            $listado = new ListadoHTML($this->sesion);
            $lenguetas->agregar('autentificacionesListado', 'Listado', $listado);
            // Pasar el HTML y el Javascript de las lengüetas al contenido
            $this->contenido[]  = $lenguetas->html();
            $this->javascript[] = $lenguetas->javascript();
        }
        // Ejecutar este método en el padre
        return parent::html();
    } // html

} // Clase PaginaHTML

?>
