<?php
/**
 * GenesisPHP - Base PlantillaCSV
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

namespace Base;

/**
 * Clase PlantillaCSV
 */
class PlantillaCSV {

    public $contenido;
    public $csv_archivo;

    /**
     * CSV
     *
     * @return string CSV
     */
    public function csv() {
        // Validar
        if (!is_string($this->contenido) || ($this->contenido == '')) {
            // Pagina con mensaje de error
            $error_fatal_html            = new \Base\PlantillaErrorFatalHTML();
            $error_fatal_html->titulo    = 'No hay contenido en el archivo CSV';
            $error_fatal_html->contenido = '<p>El contenido de archivo CSV es nulo. Tal vez la combinación de parámetros no produce resultados en la consulta.</p>';
            // Entregar
            return $error_fatal_html->html();
        }
        // Cabeceras para CSV
        header('Content-Type: text/csv; charset=utf-8');
    //  header('Content-Type: text/csv; charset=iso-8859-1');
        if ($this->csv_archivo != '') {
            header("Content-Disposition: attachment; filename={$this->csv_archivo}");
        }
        // Entregar
         return $this->contenido;
        // Entregar con codificacion de caracteres ISO-8859-1
    //  return iconv("UTF-8", "ISO-8859-1", $this->contenido);
    } // csv

} // Clase PlantillaCSV

?>
