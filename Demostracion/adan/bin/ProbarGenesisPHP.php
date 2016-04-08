#!/usr/bin/env php
<?php
/**
 * GenesisPHP - Probar GénesisPHP
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

// Soy
$soy = '[Probar GenesisPHP]';

// Constantes que definen los tipos de errores
$EXITO    = 0;
$E_NOARGS = 65;
$E_FATAL  = 99;

// Ayuda
function ayuda() {
    echo "\n";
    echo "Objetivo:\n";
    echo "  Probar las librerías de GenesisPHP.\n";
    echo "\n";
    echo "Sintaxis:\n";
    echo "  ProbarGenesisPHP.php <semilla> <librería>\n";
    echo "\n";
    echo "Ejemplo:\n";
    echo "  ProbarGenesisPHP.php '\Semillas\Adan0111CatDepartamentos' Registro\n";
    echo "\n";
}

// Si se ejecutó sin parámetros
if ($argc == 1) {
    echo "$soy Error: Faltan los parámetros.\n";
    ayuda();
    exit($E_NOARGS);
}
// Si el parámetro es para mostrar la ayuda
if (($argc == 2) && (($argv[1] == '-h') || ($argv[1] == '--help'))) {
    ayuda();
    exit($EXITO);
}
// Si faltan parámetros
if ($argc < 3) {
    echo "$soy Error: Faltan los parámetros.\n";
    ayuda();
    exit($E_NOARGS);
}

// Cambiarse al directorio por debajo de donde se encuentra este programa
chdir(realpath(dirname(__FILE__))."/..");

// Cargar auto-cargador de clases
require_once('lib/Base/AutocargadorClases.php');

// Probar
$semilla  = new $argv[1]();
$probador = new \Base\Probador($semilla);
echo $probador->crear($argv[2])."\n";

// Mensaje de término
echo "$soy Script terminado.\n";
exit($EXITO);

?>
