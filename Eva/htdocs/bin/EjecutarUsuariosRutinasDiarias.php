#!/usr/bin/env php
<?php
/**
 * GenesisPHP - Ejecutar Usuarios Rutina Diaria
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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
$soy = '[Usuarios Rutina Diaria]';

// Valores de salida
$EXITO=0;
$E_FATAL=99;

// Cambiarse al directorio donde se encuentra este programa
chdir(realpath(dirname(__FILE__)));

// Cargar el autocargador de clases
require_once('lib/Base2/AutocargadorClases.php');

// Sesión de sistema
$sesion = new \Inicio\Sesion('sistema');

// Ejecutar las rutinas diarias
$rutinas = new \AdmUsuarios\RutinasDiarias($sesion);
try {
    echo $rutinas->ejecutar()."\n";
} catch (\Exception $e) {
    echo "$soy ".$e->getMessage()."\n";
    exit($E_FATAL);
}

// Mensaje de término
echo "$soy Programa terminado.\n";
exit($EXITO);

?>
