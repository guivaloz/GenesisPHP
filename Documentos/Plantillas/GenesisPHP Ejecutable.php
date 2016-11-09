#!/usr/bin/env php
<?php
/**
 * {project} - AlimentarPublicaciones
 *
 * Copyright (C) {year} {developer} {mail}
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
 * @package {project}
 */

// Soy
$soy = '[YO SOY]';

// Valores de salida
$EXITO=0;
$E_FATAL=99;

// Ayuda
function ayuda() {ob}
    echo "\n";
    echo "Objetivo:\n";
    echo "  OBJETIVO.\n";
    echo "\n";
    echo "Sintaxis:\n";
    echo "  ARCHIVO.php\n";
    echo "\n";
{cb}

// Si el parámetro es para mostrar la ayuda
if (($argc == 2) && (($argv[1] == '-h') || ($argv[1] == '--help'))) {ob}
    ayuda();
    exit($EXITO);
{cb}

// Cambiarse al directorio donde se encuentra éste archivo
chdir(realpath(dirname(__FILE__)));

// Cargar autocargador de clases
require_once('lib/Base2/AutocargadorClases.php');

// Cargar la sesión
$sesion = new \Inicio\Sesion('sistema');

// Proceso principal
echo "$soy Inicia.\n";
$ejecutor = new \DIRECTORIO\Ejecutar($sesion);
try {ob}
    echo $ejecutor->ejecutar()."\n";
{cb} catch (\Exception $e) {ob}
    echo "$soy ".$e->getMessage()."\n";
    exit($E_FATAL);
{cb}

// Mensaje de término
echo "$soy Programa terminado.\n";
exit($EXITO);

?>
