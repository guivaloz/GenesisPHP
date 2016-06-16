<?php
/**
 * GenesisPHP - Eva PlantillaWebConfig
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

    protected $sistema              = 'GenesisPHP Eva';
    protected $titulo               = '';
    protected $descripcion          = 'GenesisPHP es un framework hecho con PHP';
    protected $autor                = 'Guillermo Valdés Lozano';
    protected $css;
    protected $favicon              = 'imagenes/favicon.png';
    protected $modelo               = 'dashboard';
    protected $menu_principal_logo  = '';
    protected $modelo_ingreso_logos;
    protected $modelo_fluido_logos;
    protected $pie                  = 'Hecho con <a href="https://github.com/guivaloz/GenesisPHP" target="_blank">GenesisPHP</a>';

} // Clase abstracta PlantillaWebConfig

?>
