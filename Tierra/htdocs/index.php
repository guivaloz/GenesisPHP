<?php
/**
 * GenesisPHP - Página Inicial
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

require_once('autocargadorclases.php');

/**
 * Clase IndexHTML
 */
class IndexHTML extends \Base\PlantillaHTML {

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

    /**
     * Constructor
     */
    public function __construct() {
        // MENU
        $this->menu = new \Pruebas\Menu();
        $this->menu->alimentar_menu_pruebas();
        $this->menu->clave = 'tierra_pruebas_detalles';
    } // constructor

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // MENSAJE DE BIENVENIDA
        $mensaje           = new \Base\MensajeHTML('Esta página realiza una serie de pruebas a DetalleHTML.');
        $mensaje->tipo     = 'tip';
        $this->contenido[] = $mensaje->html('Acerca de esta página');
        //
        // PRUEBA DETALLE
        //
        $detalle = new \Base\DetalleHTML();
        $detalle->seccion('Datos generales');
        $detalle->dato('Nombre', 'Fulano de Tal');
        $detalle->dato('Sexo', 'Masculino', 'rojo');
        $detalle->seccion('Institucionales');
        $detalle->dato('Número de nómina', '33000');
        $detalle->dato('Fecha de ingreso', '2012-12-15');
        $this->contenido[] = $detalle->html('Prueba secciones y datos');
        unset($detalle);
        //
        // PRUEBA DETALLE CON BOTONES
        //
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = 'Detalle con botones';
        $barra->icono      = 'folder.png';
        $barra->boton_modificar('index.php?accion=modificar', 'Modificar');
        $barra->boton_eliminar_confirmacion('index.php?accion=eliminar', '¿Está seguro que quiere eliminar este registro?');
        $detalle        = new \Base\DetalleHTML();
        $detalle->barra = $barra;
        $detalle->seccion('Datos generales');
        $detalle->dato('Nombre', 'Fulano de Tal');
        $detalle->dato('Sexo', 'Masculino', 'rojo');
        $this->contenido[] = $detalle->html('Esto no debe salir');
        unset($detalle);
        // ENTREGAMOS
        return parent::html();
    } // html

} // Clase IndexHTML

// EJECUTAR Y MOSTRAR
$pagina = new IndexHTML();
echo $pagina->html();

?>
