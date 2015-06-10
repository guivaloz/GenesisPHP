<?php
/**
 * GenesisPHP - Sesion
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

namespace Inicio;

/**
 * Clase Sesión
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
     */
    public function __construct($in_sistema='') {
        // Es sistema cuando se ejecutan scripts en la terminal
        if ($in_sistema == 'sistema') {
            // Sesion para el usuario sistema
            $this->usuario        = 1; // El usario sistema debe ser el id uno en la tabla de usuarios
            $this->nombre         = 'Sistema';
            $this->nom_corto      = 'sistema';
            $this->tipo           = 'O'; // Es administrador
            $this->pagina         = '';
            $this->pagina_permiso = 0; // Porque el usuario sistema no es para paginas web
            // Menu para el usuario sistema
            $this->menu     = new Menu($this);
            $this->menu->consultar(); // Puede provocar una excepcion
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
        // PARAMETRO CLAVE DE LA PAGINA
        $this->pagina = $in_pagina;
        // SI LA COOKIE NO ES VALIDA, ABORTAR AL USUARIO
        $this->validar(); // PUEDE PROVOCAR UNA EXCEPCION
        // VALIDAR ID DEL USUARIO
        if (!validar_entero($this->usuario)) {
            throw new \Exception('Error: Por ID del usuario incorrecto.');
        }
        // CONSULTAR REGISTRO EN LA TABLA DE SESIONES
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("
                SELECT
                    nombre, nom_corto, tipo, listado_renglones
                FROM
                    sesiones
                WHERE
                    usuario = {$this->usuario}");
        } catch (\Exception $e) {
            throw new \Exception('Error: En la consulta de la sesion.');
        }
        // SI LA CONSULTA NO ARROJO REGISTROS
        if ($consulta->cantidad_registros() == 0) {
            $this->eliminar();
            throw new SesionException($this->usuario, '', 'sesión no existe', 'Aviso: Su sesión ha caducado.');
        }
        // TOMAR VALORES DE LA CONSULTA
        $a = $consulta->obtener_registro();
        $this->nombre            = $a['nombre'];
        $this->nom_corto         = $a['nom_corto'];
        $this->tipo              = $a['tipo'];
        $this->listado_renglones = intval($a['listado_renglones']);
        // DE MENU OBTENEMOS EL PERMISO DE LA PAGINA Y TODOS LOS PERMISOS
        $this->menu           = new Menu($this);
        $this->menu->consultar(); // PUEDE PROVOCAR UNA EXCEPCION
        $this->pagina_permiso = $this->menu->permiso_en_pagina($this->pagina);
        $this->permisos       = $this->menu->permisos;
        // SI NO TIENE EL PERMISO PARA ESA PAGINA
        if ($this->pagina_permiso < 1) {
            $this->eliminar();
            throw new SesionException($this->usuario, $this->nom_corto, 'no tiene permiso', "No tiene permiso para esa página.");
        }
        // CAMBIAR LA CANTIDAD DE RENGLONES EN LOS LISTADOS CONTROLADOS
        \Base\ControladoHTML::$limit_por_defecto = $this->listado_renglones;
        // ENTREGAR EL PERMISO DE LA PAGINA
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
