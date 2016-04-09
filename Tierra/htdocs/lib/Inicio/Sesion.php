<?php
/**
 * GenesisPHP - Inicio Sesión
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

namespace Inicio;

/**
 * Clase Sesion
 */
class Sesion extends Cookie {

    // protected $nom_cookie;
    // protected $version_actual;
    // protected $tiempo_expirar;
    // protected $tiempo_renovar;
    // protected $key;
    // public $usuario;
    // public $ingreso;
    public $nombre;            // Texto
    public $nom_corto;         // Texto
    public $tipo;              // Caracter, tipo de usuario
    public $pagina;            // Clave de la página
    public $pagina_permiso;    // Permiso de la página en uso
    public $permisos;          // Arreglo asociativo con todos los permisos
    public $listado_renglones; // Cantidad de renglones en los listados
    public $menu;              // Instancia de Menu

    /**
     * Constructor
     *
     * @param string Opcional, no lo use para paginas, para los Bash Scripts use el texto 'sistema'
     * @param mixed  Opcional, instancia de un Menú diferente a \Inicio\Menu
     */
    public function __construct($in_sistema='', $in_menu='') {
        // Es sistema cuando se ejecutan scripts en la terminal
        if ($in_sistema == 'sistema') {
            // Sesion para el usuario sistema
            $this->usuario        = 1; // El usario sistema debe ser el id uno en la tabla de usuarios
            $this->nombre         = 'Sistema';
            $this->nom_corto      = 'sistema';
            $this->tipo           = 'O'; // Es administrador
            $this->pagina         = '';
            $this->pagina_permiso = 0; // Porque el usuario sistema no es para paginas web
            // Si por parámetro se entrega un menú, se usa, de lo contrario es \Inicio\Menu
            if (is_object($in_menu)) {
                $this->menu = $in_menu;
            } else {
                $this->menu = new Menu($this);
            }
            $this->menu->consultar();
            $this->permisos = $this->menu->permisos;
        } else {
            // Solo ejecutamos el constructor del padre cuando no es 'sistema'
            parent::__construct();
        }
    } // constructor

    /**
     * Puede ver
     *
     * @param  string  Clave del módulo
     * @return boolean Verdadero si SI tiene permiso
     */
    public function puede_ver($in_clave) {
        if ($this->permisos[$in_clave] >= Menu::$permiso_ver) {
            return true;
        } else {
            return false;
        }
    } // puede_ver

    /**
     * Puede modificar
     *
     * @param  string  Clave del módulo
     * @return boolean Verdadero si SI tiene permiso
     */
    public function puede_modificar($in_clave) {
        if ($this->permisos[$in_clave] >= Menu::$permiso_modificar) {
            return true;
        } else {
            return false;
        }
    } // puede_modificar

    /**
     * Puede agregar
     *
     * @param  string  Clave del módulo
     * @return boolean Verdadero si SI tiene permiso
     */
    public function puede_agregar($in_clave) {
        if ($this->permisos[$in_clave] >= Menu::$permiso_agregar) {
            return true;
        } else {
            return false;
        }
    } // puede_agregar

    /**
     * Puede eliminar
     *
     * @param  string  Clave del módulo
     * @return boolean Verdadero si SI tiene permiso
     */
    public function puede_eliminar($in_clave) {
        if ($this->permisos[$in_clave] >= Menu::$permiso_eliminar) {
            return true;
        } else {
            return false;
        }
    } // puede_eliminar

    /**
     * Puede recuperar
     *
     * @param  string  Clave del módulo
     * @return boolean Verdadero si SI tiene permiso
     */
    public function puede_recuperar($in_clave) {
        if ($this->permisos[$in_clave] >= Menu::$permiso_recuperar) {
            return true;
        } else {
            return false;
        }
    } // puede_recuperar

} // Clase Sesión

?>
