<?php
/**
 * GenesisPHP - OpcionesSelect Opciones
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

namespace OpcionesSelect;

/**
 * Clase Opciones
 */
class Opciones extends \Base\Plantilla {

    /**
     * Obtener columnas VIP
     *
     * @return array Arreglo con los nombres de las columnas VIP
     */
    protected function obtener_columnas_vip() {
        $vips = array();
        foreach ($this->tabla as $columna => $datos) {
            $n = $datos['vip'];
            if ($n > 0) {
                if ($datos['tipo'] != 'relacion') {
                    $vips[$n][] = $columna;
                }
                if ($n > $mayor) {
                    $mayor = $n;
                }
            }
        }
        if (count($vips) == 0) {
            die('Error en OpcionesSelect, Opciones: No hay columnas con VIP con valor mayor a cero.');
        }
        $a = array();
        for ($n=$mayor; $n>0; $n--) {
            if (count($vips[$n]) > 0) {
                foreach ($vips[$n] as $columna) {
                    $a[] = $columna;
                }
            }
        }
        return $a;
    } // obtener_columnas_vip

    /**
     * Elaborar Consultar Columnas
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_columnas() {
        $columnas_separadas_por_comas = implode(', ', $this->obtener_columnas_vip());
        return "        \$columnas_sql = \"SELECT id, $columnas_separadas_por_comas\";";
    } // elaborar_consultar_columnas

    /**
     * Elaborar Consultar Tablas
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_tablas() {
        return "        \$tablas_sql   = \"FROM {$this->tabla_nombre}\";";
    }

    /**
     * Elaborar Consultar Filtros
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_filtros() {
        if (count($this->tabla['estatus']) > 0) {
            if ($this->tabla['estatus']['descripciones']['A'] != '') {
                return "        \$filtros_sql  = \"WHERE estatus = 'A'\";";
            } else {
                return "        \$filtros_sql  = ''; //  Sin filtro, porque estatus no usa 'A'";
            }
        } else {
            return "        \$filtros_sql  = ''; // Sin filtro, no usa la columna estatus";
        }
    } // elaborar_consultar_filtros

    /**
     * Elaborar Consultar Orden
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_orden() {
        $columnas_separadas_por_comas = implode(', ', $this->obtener_columnas_vip());
        return "        \$orden_sql    = \"ORDER BY $columnas_separadas_por_comas ASC\";";
    } // elaborar_consultar_orden

    /*
     * Elaborar asignaciones de opciones
     *
     * @return string Código PHP
     */
    protected function elaborar_opciones_asignacion() {
        $a = array();
        foreach ($this->obtener_columnas_vip() as $columna) {
            $a[] = "\$item['$columna']";
        }
        $juntos = implode(".', '.", $a);
        return "            \$a[\$item['id']] = $juntos;";
    } // elaborar_opciones_asignacion

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FIN
    /**
     * Opciones para Select
     *
     * @return array Arreglo asociativo, id => descripcion
     */
    public function opciones() {
        // Definir comando SQL
{$this->elaborar_consultar_columnas()}
{$this->elaborar_consultar_tablas()}
{$this->elaborar_consultar_filtros()}
{$this->elaborar_consultar_orden()}
        // Consultar
        \$base_datos = new \\Base2\\BaseDatosMotor();
        try {
            \$consulta = \$base_datos->comando("\$columnas_sql \$tablas_sql \$filtros_sql \$orden_sql");
        } catch (Exception \$e) {
            throw new \\AdmBitacora\\BaseDatosExceptionSQLError(\$this->sesion, 'Error: Al consultar SED_MENSAJE_PLURAL para hacer opciones.', \$e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if (\$consulta->cantidad_registros() == 0) {
            throw new \\Base2\\ListadoExceptionVacio('Aviso: No se encontraron SED_MENSAJE_PLURAL para hacer opciones.');
        }
        // Juntar como arreglo asociativo
        \$a = array();
        foreach (\$consulta->obtener_todos_los_registros() as \$item) {
{$this->elaborar_opciones_asignacion()}
        }
        // Poner en verdadero el flag consultado
        \$this->consultado = true;
        // Entregar
        return \$a;
    } // opciones

FIN;
    } // php

} // Clase Opciones

?>
