<?php
/**
 * GenesisPHP - Menu
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
        $this->agregar_principal('tierra_prueba',  '-Pruebas',   'index.php',            'preferences-desktop.png');
        $this->agregar('tierra_prueba_detalle',    'Detalle',    'pruebadetalle.php',    'supertux.png');
        $this->agregar('tierra_prueba_listado',    'Listado',    'pruebalistado.php',    'accessories-dictionary.png');
        $this->agregar('tierra_prueba_formulario', 'Formulario', 'pruebaformulario.php', 'menu-editor.png');
        // Arriba a la derecha
        $this->agregar_principal_derecha('tierra_inicio',  '', 'index.php',         'glyphicon glyphicon-off');
    } // consultar

} // Clase Menu

?>
