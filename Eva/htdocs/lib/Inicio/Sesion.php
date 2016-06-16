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
     * Cargar
     *
     * @param  string  Clave de la pagina
     * @return integer Permiso de la pagina
     */
    public function cargar($in_pagina) {
        // Parámetro con la clave de la pagina
        $this->pagina = $in_pagina;
        // Validar la cookie
        $this->validar(); // Si no hay cookie o ha caducado, provoca una excepción
        // Validar ID del usuario
        if (!\Base2\UtileriasParaValidar::validar_entero($this->usuario)) {
            throw new \Exception('Error: Por ID del usuario incorrecto.');
        }
        // Consultar registro en la tabla de sesiones
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    nombre, nom_corto, tipo, listado_renglones
                FROM
                    adm_sesiones
                WHERE
                    usuario = %d",
                $this->usuario));
        } catch (\Exception $e) {
            throw new \Exception('Error: En la consulta de la sesión.');
        }
        // Si la consulta no arrojó registros
        if ($consulta->cantidad_registros() == 0) {
            $this->eliminar();
            throw new SesionException($this->usuario, '', 'sesión no existe', 'Aviso: Su sesión ha caducado.');
        }
        // Tomar valores de la consulta
        $a = $consulta->obtener_registro();
        $this->nombre            = $a['nombre'];
        $this->nom_corto         = $a['nom_corto'];
        $this->tipo              = $a['tipo'];
        $this->listado_renglones = intval($a['listado_renglones']);
        // De menu obtenemos el permiso de la página y todos los permisos
        $this->menu           = new Menu($this);
        $this->menu->consultar(); // Puede provocar una excepción
        $this->pagina_permiso = $this->menu->permiso_en_pagina($this->pagina);
        $this->permisos       = $this->menu->permisos;
        // Si no tiene el permiso para esa página
        if ($this->pagina_permiso < 1) {
            $this->eliminar();
            throw new SesionException($this->usuario, $this->nom_corto, 'no tiene permiso', "No tiene permiso para esa página.");
        }
        // Cambiar la cantidad de renglones en los listados controlados
        \Base2\ControladoWeb::$limit_por_defecto = $this->listado_renglones;
        // Entregar el permiso de la página
        return $this->pagina_permiso;
    } // cargar

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
