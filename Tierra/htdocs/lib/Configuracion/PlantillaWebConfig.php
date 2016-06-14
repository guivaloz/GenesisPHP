<?php
/**
 * GenesisPHP - PlantillaWebConfig
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

    protected $sistema              = 'GenesisPHP Tierra';
    protected $titulo               = '';
    protected $descripcion          = 'GenesisPHP es un framework hecho con PHP';
    protected $autor                = 'guivaloz';
    protected $css                  = 'css/tierra.css';
    protected $favicon              = 'imagenes/favicon.png';
    protected $modelo               = 'sbadmin2';
    protected $menu_principal_logo  = '';
    protected $modelo_ingreso_logos = array(
        array('url' => 'imagenes/generic_company.png', 'class' => 'img-responsive', 'style' => 'margin:10px;', 'pos' => 'izquierda'),
        array('url' => 'imagenes/generic_company.png', 'class' => 'img-responsive', 'style' => 'margin:10px;', 'pos' => 'derecha'));
    protected $modelo_fluido_logos  = array(
        array('url' => 'imagenes/generic_company.png', 'style' => 'position:fixed; bottom:10px; left:10px;'));
    protected $pie                  = 'Hecho con <a href="https://github.com/guivaloz/GenesisPHP" target="_blank">GenesisPHP</a>';

} // Clase abstracta PlantillaWebConfig

?>
