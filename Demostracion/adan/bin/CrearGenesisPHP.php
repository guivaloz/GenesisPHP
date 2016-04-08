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

// Autocargador de clases
spl_autoload_register(
    function ($className) {
        // Se retira la diagonal inversa al inicio si la hubiera
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        // Si hay una diagonal inversa se separan los directorios del nombre del archivo
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
        }
        // Acumular el nombre del archivo, convertir guiones bajos a diagonales como lo especifica la norma
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';
        // Como prefijo, todas las clases están dentro del directorio lib
        $file = 'lib/'.$fileName;
        // Cargar el archivo
        if (file_exists($file)) {
            require $file;
        } else {
            die("$soy ERROR: no existe $file");
            exit($E_FATAL);
        }
    } // auto-cargador de clases
);

// Cambiarse al directorio por debajo de donde se encuentra este programa
chdir(realpath(dirname(__FILE__))."/..");

// Crear
$creador = new TwitterBootstrapReproductores\Creador('../../htdocs');
try {
    foreach ($semillas as $s) {
        $clase         = "\\Semillas\\$s";
        $creador->adan = new $clase();
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
