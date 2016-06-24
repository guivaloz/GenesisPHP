<?php
/**
 * GenesisPHP - PlantillaCSV
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

/**
 * Clase abstracta PlantillaCSV
 */
abstract class PlantillaCSV extends \Configuracion\PlantillaCSVConfig {

    // protected $cabecera_tipo_contenido;
    // protected $recodificacion;
    protected $contenido;
    protected $csv_archivo;

    /**
     * CSV
     *
     * @return string CSV
     */
    public function csv() {
        // Validar
        if (!is_string($this->contenido) || ($this->contenido == '')) {
            die('El contenido de archivo CSV es nulo. Tal vez la combinación de parámetros no produce resultados en la consulta.');
        }
        // Cabecera
        if ($this->cabecera_tipo_contenido != '') {
            header($this->cabecera_tipo_contenido);
        } else {
            header('Content-Type: text/csv; charset=utf-8');
        }
        // Cabecera con el nombre para el archivo CSV
        if ($this->csv_archivo != '') {
            header("Content-Disposition: attachment; filename={$this->csv_archivo}");
        }
        // Entregar tal cual o recodificado
        if ($this->recodificacion != '') {
            return iconv("UTF-8", $this->recodificacion, $this->contenido);
        } else {
            return $this->contenido;
        }
    } // csv

} // Clase abstracta PlantillaCSV

?>
