#!/usr/bin/env php
<?php
/**
 * GenesisPHP
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
 * @package
 */

// Soy
$soy = '[NOMBRE]';

// Valores de salida
$EXITO=0;
$E_FATAL=99;

// Cargar funciones, éste conteniene el autocargador de clases
require_once('lib/Base/Funciones.php');

// Cargar la página
$pagina = new \DIRECTORIO\Pagina();
// Cargar el impresor
$impresor            = new \Base\Imprenta();
$impresor->plantilla = $pagina;
// Imprimir
try {
    echo $impresor->imprimir()."\n";
} catch (\Exception $e) {
    echo implode("\n", $impresor->mensajes)."\n";
    echo "$soy ".$e->getMessage()."\n";
    exit($E_FATAL);
}

// Mensaje de término
echo "$soy Programa terminado.\n";
exit($EXITO);

?>
