<?php
/**
 * GenesisPHP - PlantillaHTMLConfig
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

namespace Configuracion;

/*
 * Clase PlantillaHTMLConfig
 */
class PlantillaHTMLConfig {

    protected $sistema              = 'Tierra';
    protected $titulo               = '';
    protected $descripcion          = 'Tierra es un framework hecho con PHP';
    protected $autor                = 'Guillermo Valdés Lozano';
    protected $favicon              = 'imagenes/favicon.png';
 // protected $modelo               = 'fluida';
    protected $modelo               = 'dashboard';
 // protected $menu_principal_logo  = 'imagenes/logo.png';
    protected $modelo_ingreso_logos = array(
        array('url' => 'imagenes/generic_company.png', 'class' => 'img-responsive', 'style' => 'margin:10px;', 'pos' => 'izquierda'),
        array('url' => 'imagenes/generic_company.png', 'class' => 'img-responsive', 'style' => 'margin:10px;', 'pos' => 'derecha'));
    protected $modelo_fluido_logos  = array(
        array('url' => 'imagenes/generic_company.png', 'style' => 'position:fixed; bottom:10px; left:10px;'));
    protected $pie                  = 'Hecho con <a href="http://www.movimientolibre.com/" target="_blank">Génesis</a>';

} // Clase PlantillaHTMLConfig

?>
