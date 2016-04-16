#!/usr/bin/env php
<?php
/**
 * GenesisPHP - Crear GénesisPHP
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
$soy = '[Crear GenesisPHP]';

// Constantes que definen los tipos de errores
$EXITO=0;
$E_FATAL=99;

// Cambiarse al directorio donde se encuentran las semillas
chdir(realpath(dirname(__FILE__)));
if (is_dir('../lib/Semillas')) {
    chdir('../lib/Semillas');
} else {
    echo "$soy ERROR: No existe el directorio para las semillas.\n";
    exit($E_FATAL);
}

// Obtener archivos de las semillas
echo "$soy Recolectando semillas...\n";
$semillas = array();
if ($manipulador = opendir('.')) {
    while (false !== ($archivo = readdir($manipulador))) {
    //  echo "  Archivo $archivo \n";
        if (($archivo != ".") && ($archivo != "..") && is_file($archivo)) {
            $pos       = strrpos($archivo, '.');
            $extension = (false === $pos) ? '' : substr($archivo, $pos + 1);
            $nombre    = (false === $pos) ? $archivo : substr($archivo, 0, strlen($archivo) - strlen($extension) - 1);
        //  echo "  Procesando [$nombre].[$extension] \n";
            if (($extension == 'php') && ($nombre != 'Serpiente')) {
                $semillas[] = $nombre;
            //  echo "  $nombre \n";
            }
        }
    }
    closedir($manipulador);
} else {
    echo "$soy ERROR: Fatal al tratar de obtener las Semillas.\n";
    exit($E_FATAL);
}
if (count($semillas) == 0) {
    echo "$soy ERROR: No hay Semillas.\n";
    exit($E_FATAL);
}
sort($semillas);
echo "$soy Semillas recolectadas...\n";
foreach ($semillas as $s) {
    echo "  $s\n";
}
exit($EXITO);

// Cambiarse al directorio por debajo de donde se encuentra este programa
chdir(realpath(dirname(__FILE__))."/..");

// Cargar auto-cargador de clases
require_once('lib/Base/AutocargadorClases.php');

// Crear
try {
    foreach ($semillas as $s) {
        $clase   = "\\Semillas\\$s";
        $semilla = new $clase();
        $creador = new \Base\Creador($semilla);
        echo $creador->crear()."\n";
    }
} catch (\Exception $e) {
    echo "ERROR: ".$e->getMessage()."\n";
    exit($E_FATAL);
}

// Mensaje de término
echo "$soy Script terminado.\n";
exit($EXITO);

?>
