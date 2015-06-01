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


// NAMESPACE
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
     * Alimentar Menu Pruebas
     */
    public function alimentar_menu_pruebas() {
        // CONSTRUIR EL MENU DE PRUEBAS QUE ES FIJO
        $this->agregar_principal('tierra_pruebas_detalles', '-Tierra',  'index.php', 'preferences-desktop.png');
        $this->agregar('tierra_pruebas_listados',           'Detalles', 'index.php', 'menu-editor.png');
        $this->agregar_principal_derecha('tierra_inicio',   '',         'index.php', 'glyphicon glyphicon-off');
    } // alimentar_menu_pruebas

} // Clase Menu

?>
