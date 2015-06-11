<?php
/**
 * GenesisPHP - Listado
 *
 * Copyright 2015 Guillermo Valdés Lozano <guivaloz@movimientolibre.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @package GenesisPHP
 */

namespace Base;

/**
 * Clase Listado
 */
abstract class Listado {

    protected $sesion;                  // Instancia de \Inicio\Sesion
    public $listado;                    // Arreglo de arreglos asociativos columna => valor
    public $panal;                      // Arreglo de arreglos con instancias columna => Celda
    public $cantidad_registros = 0;
    public $limit              = 0;
    public $offset             = 0;
    protected $consultado      = false; // Verdadero si ya fue consultado

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        $this->sesion = $in_sesion;
    } // constructor

    /**
     * Validar
     */
    public function validar() {
        if (!validar_entero($this->limit)) {
            throw new ListadoExceptionValidacion('Error: Valor de "limit" incorrecto.');
        }
        if (!validar_entero($this->offset)) {
            throw new ListadoExceptionValidacion('Error: Valor de "offset" incorrecto.');
        }
        if (!validar_entero($this->cantidad_registros)) {
            throw new ListadoExceptionValidacion('Error: Cantidad de registros es incorrecto.');
        }
    } // validar

    /**
     * Limit y Offset SQL
     *
     * @return string Fragmento de SQL
     */
    protected function limit_offset_sql() {
        if ($this->limit > 0) {
            $s = "LIMIT {$this->limit} ";
            if ($this->offset > 0) {
                $s .= "OFFSET {$this->offset}";
            }
        } else {
            $s = '';
        }
        return $s;
    } // limit_offset_sql

    /**
     * Encabezado
     */
    abstract function encabezado();

    /**
     * Consultar
     */
    abstract function consultar();

    /**
     * Definir Listado desde Panal
     */
    public function definir_listado_desde_panal() {
        $this->listado = array();
        foreach ($this->panal as $renglon) {
            $fila = array();
            foreach ($renglon as $clave => $celda) {
                if (is_object($celda) && ($celda instanceof Celda)) {
                    $fila[$clave] = $celda->formatear();
                } else {
                    $fila[$clave] = $celda;
                }
            }
            $this->listado[] = $fila;
        }
    } // definir_listado_desde_panal

} // Clase Listado

?>
