<?php
/**
 * GenesisPHP - PaginaWeb
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

namespace Base2;

/**
 * Clase abstracta PaginaWeb
 */
abstract class PaginaWeb extends PlantillaWeb {

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
    protected $sesion;          // Instancia de Sesion
    protected $sesion_exitosa;  // Boleano, verdadero si se cargó con éxito la sesión
    protected $usuario;         // Entero, ID del usuario
    protected $usuario_nombre;  // Texto, nombre del usuario

    /**
     * Constructor
     *
     * @param string Clave de la página
     */
    public function __construct($in_clave) {
        // Parametro clave de la página
        $this->clave = $in_clave;
        // Definir la sesión
        $this->sesion = new \Inicio\Sesion();
        try {
            // Cargar la sesión
            $this->sesion->cargar($this->clave);
            // La sesión se ha cargado con exito
            $this->sesion_exitosa = true;
            // Pasar datos del usuario
            $this->usuario        = $this->sesion->usuario;
            $this->usuario_nombre = $this->sesion->nombre;
        } catch (\Exception $e) {
            // Ha fallado la sesión, se mostrará el mensaje en la pantalla de ingreso
            $this->sesion_exitosa = false;
            $this->contenido      = $e->getMessage();
            $this->modelo         = 'ingreso';
        }
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Si la sesión es exitosa
        if ($this->sesion_exitosa) {
            // Definir el menu
            $this->menu = new \Inicio\Menu($this->sesion);
            // Pasar la clave de la página actual al menu
            $this->menu->clave = $this->clave;
            try {
                $this->menu->consultar($this->usuario);
            } catch (\Exception $e) {
                $this->contenido = $e->getMessage();
            }
            // Título e ícono de la página
            $this->titulo = $this->menu->titulo_en($this->clave);
            $this->icono  = $this->menu->icono_en($this->clave);
        }
        // Se ejecuta el padre y se entrega su resultado
        return parent::html();
    } // html

} // Clase abstracta PaginaWeb

?>
