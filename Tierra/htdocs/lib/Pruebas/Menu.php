<?php
/**
 * GenesisPHP - Menu
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

namespace Pruebas;

/**
 * Clase Menu
 */
class Menu extends \Base\Menu {

    // public $clave;
    // public $permisos;
    // protected $principal_actual;
    // protected $estructura;
    // protected $datos;

    /**
     * Consultar
     */
    public function consultar() {
        // Opciones para el menú de pruebas de Tierra
        $this->agregar_principal('tierra_prueba', '-Pruebas',  'index.php',         'preferences-desktop.png');
        $this->agregar('tierra_prueba_detalle',   'Detalle',   'pruebadetalle.php', 'menu-editor.png');
        $this->agregar('tierra_prueba_listado',   'Listado',   'pruebalistado.php', 'folder.png');
        // Arriba a la derecha
        $this->agregar_principal_derecha('tierra_inicio',  '', 'index.php',         'glyphicon glyphicon-off');
    } // consultar

} // Clase Menu

?>
