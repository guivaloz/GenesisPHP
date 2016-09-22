<?php
/**
 * GenesisPHP - PlantillaCSVConfig
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
 * Clase abstracta PlantillaCSVConfig
 *
 * Valores por defecto usados para la elaboración de archivos CSV
 */
abstract class PlantillaCSVConfig {

    /**
     * Cabecera tipo de contenido - Mejor opción UTF-8
     */
    protected $cabecera_tipo_contenido = 'Content-Type: text/csv; charset=utf-8';

    /**
     * Cabecera tipo de contenido - Para clientes con MS Excel en Windows es ISO-8859-1
     */
 // protected $cabecera_tipo_contenido = 'Content-Type: text/csv; charset=iso-8859-1';

    /**
     * Recodificación - Mejor opción es sin recodificación, es decir, se entrega UTF-8
     */
    protected $recodificacion = '';

    /**
     * Recodificación - Para clientes con MS Excel en Windows a ISO-8859-1
     */
 // protected $recodificacion = 'ISO-8859-1';

} // Clase abstracta PlantillaCSVConfig

?>
