<?php
/**
 * GenesisPHP - Autocargador de Clases
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

namespace Base2;

// Todos los caracteres serán UTF-8
mb_internal_encoding('utf-8');

// Autocargador de Clases
spl_autoload_register(
    /**
     * Auto-cargador de Clases
     *
     * @param string Creación de la instancia
     */
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
            die("Autocargador de Clases: Error, no existe $file\n");
        }
    } // auto-cargador de clases
);

?>
