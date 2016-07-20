<?php
/**
 * GenesisPHP - Configuración PlantillaWeb
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

namespace Configuracion;

/*
 * Clase abstracta PlantillaWebConfig
 */
abstract class PlantillaWebConfig {

    protected $sistema              = 'GenesisPHP Demostración';
    protected $titulo               = '';
    protected $descripcion          = 'GenesisPHP es un framework hecho con PHP';
    protected $autor                = 'guivaloz';
    protected $css                  = 'css/demostracion.css';
    protected $favicon              = 'imagenes/favicon.png';
    protected $modelo               = 'sbadmin2';
    protected $menu_principal_logo  = '';
    protected $modelo_ingreso_logos = array(
        array('url' => 'imagenes/generic_company.png', 'class' => 'img-responsive', 'style' => 'margin:10px;', 'pos' => 'izquierda'),
        array('url' => 'imagenes/generic_company.png', 'class' => 'img-responsive', 'style' => 'margin:10px;', 'pos' => 'derecha'));
    protected $modelo_fluido_logos  = array(
        array('url' => 'imagenes/generic_company.png', 'style' => 'position:fixed; bottom:10px; left:10px;'));
    protected $pie                  = 'Hecho con <a href="https://github.com/guivaloz/GenesisPHP" target="_blank">GenesisPHP</a>';

    /**
     * CSS común con vínculos locales, para redes locales sin internet
     */
    protected $css_comun            = array(
        '  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">',
        '  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">');

    /**
     * Javascript común con vínculos locales, para redes locales sin internet
     */
    protected $javascript_comun     = array(
        '  <script src="js/jquery.min.js"></script>',
        '  <script src="js/bootstrap.min.js"></script>');

    /**
     * CSS común con vínculos remotos, ideal para sistemas en internet
     */
    //~ protected $css_comun            = array(
        //~ '  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">',
        //~ '  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">');

    /**
     * Javascript común con vínculos remotos, ideal para sistemas en internet
     */
    //~ protected $javascript_comun     = array(
        //~ '  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>',
        //~ '  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>');

} // Clase abstracta PlantillaWebConfig

?>
