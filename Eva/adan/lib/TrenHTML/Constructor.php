<?php
/**
 * GenesisPHP - TrenHTML Constructor
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

namespace TrenHTML;

/**
 * Clase Constructor
 */
class Constructor extends \Base\Plantilla {

    /**
     * Elaborar recepcion de filtros por URL
     *
     * @return string Código PHP
     */
    protected function elaborar_constructor_recibir_filtros() {
        $a   = array();
        $a[] = '        // Filtros que puede recibir por el url';
        // Trabajar solo con los campos que se usen como filtros
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['filtro'] > 1) {
                // Rango (desde-hasta)
                $a[] = sprintf('        $this->%s = $_GET[parent::$param_%s_desde];', str_pad($columna.'_desde', $this->columnas_caracteres_maximo), $columna);
                $a[] = sprintf('        $this->%s = $_GET[parent::$param_%s_hasta];', str_pad($columna.'_hasta', $this->columnas_caracteres_maximo), $columna);
            } elseif ($datos['filtro'] > 0) {
                // Normal
                $a[] = sprintf('        $this->%s = $_GET[parent::$param_%s];', str_pad($columna, $this->columnas_caracteres_maximo), $columna);
            }
        }
        // ENTREGAR
        return implode("\n", $a);
    } // elaborar_constructor_recibir_filtros

    /**
     * Elaborar Constructor Viene Tren
     *
     * @return string Código PHP
     */
    protected function elaborar_constructor_viene_tren() {
        $a   = array();
        $a[] = '        // Si cualquiera de los filtros tiene valor, entonces viene listado sera verdadero';
        $a[] = '        if ($this->tren_controlado->viene_tren) {';
        $a[] = '            $this->viene_tren = true;';
        $a[] = '        } else {';
        // Trabajar solo con los campos que se usen como filtros
        $b = array();
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['filtro'] > 1) {
                // Rango (desde-hasta)
                $b[] = "(\$this->{$columna}_desde != '')";
                $b[] = "(\$this->{$columna}_hasta != '')";
            } elseif ($datos['filtro'] > 0) {
                // Normal
                $b[] = "(\$this->$columna != '')";
            }
        }
        // Validar que haya por lo menos un filtro
        if (count($b) == 0) {
            die('Error en TrenHTML, constructor, elaborar_constructor_viene_tren: Es necesario que haya por lo menos una columna como filtro.');
        }
        $a[] = '            $this->viene_tren = '.implode(' || ', $b).';';
        $a[] = '        }';
        // Entregar
        return implode("\n", $a);
    } // elaborar_constructor_viene_tren

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion \$in_sesion) {
{$this->elaborar_constructor_recibir_filtros()}
        // Iniciar tren controlado
        \$this->tren_controlado = new \Base\TrenControladoHTML();
        // Su constructor toma estos parametros por url
        \$this->limit              = \$this->tren_controlado->limit;
        \$this->offset             = \$this->tren_controlado->offset;
        \$this->cantidad_registros = \$this->tren_controlado->cantidad_registros;
{$this->elaborar_constructor_viene_tren()}
        // Ejecutar el constructor del padre
        parent::__construct(\$in_sesion);
    } // constructor

FINAL;
    } // php

} // Clase Constructor

?>
