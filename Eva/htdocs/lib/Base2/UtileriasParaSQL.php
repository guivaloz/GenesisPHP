<?php
/**
 * GenesisPHP - UtileriasParaSQL
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
 * Clase abstracta UtileriasParaSQL
 */
abstract class UtileriasParaSQL {

    /**
     * SQL Texto
     *
     * @param  string Texto
     * @return string Texto fragmento de SQL
     */
    public static function sql_texto($texto) {
        if (trim($texto) == '') {
            return 'NULL';
        } else {
            return "'".pg_escape_string(trim($texto))."'";
        }
    } // sql_texto

    /**
     * SQL Texto en mayúsculas
     *
     * @param  string Texto
     * @return string Texto fragmento de SQL
     */
    public static function sql_texto_mayusculas($texto) {
        $normalizar = array(
            'à' => 'A', 'è' => 'E', 'ì' => 'I', 'ò' => 'O', 'ù' => 'U',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
            'á' => 'A', 'é' => 'E', 'í' => 'I', 'ó' => 'O', 'ú' => 'U',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'Ñ',
            'ü' => 'Ü');
        $normalizado = strtr($texto, $normalizar);
        return "'".pg_escape_string(trim(strtoupper($normalizado)))."'";
    } // sql_texto_mayusculas

    /**
     * SQL Entero
     *
     * @param  mixed  Texto o número entero
     * @return string Texto fragmento de SQL
     */
    public static function sql_entero($dato) {
        if (is_string($dato)) {
            if (trim($dato) == '') {
                return 'NULL';
            } elseif (trim($dato) == '0') {
                return '0';
            } else {
                return $dato;
            }
        } elseif (is_int($dato)) {
            if ($dato == 0) {
                return 0;
            } else {
                return $dato;
            }
        } else {
            return intval($dato);
        }
    } // sql_entero

    /**
     * SQL Tiempo
     *
     * @param  mixed  Texto o número entero
     * @return string Texto fragmento de SQL
     */
    public static function sql_tiempo($dato) {
        if (is_string($dato)) {
            if ($dato == '') {
                return 'NULL';
            } elseif (preg_match('/^[0-9]+$/', $dato)) {
                return "'".date('Y-m-d H:i:s', $dato)."'";
            } else {
                return "'$dato'";
            }
        } elseif (is_integer($dato)) {
            if ($dato == 0) {
                return 'NULL';
            } else {
                return "'".date('Y-m-d H:i:s', $dato)."'";
            }
        } else {
            return 'NULL';
        }
    } // sql_tiempo

    /**
     * SQL filtros sin valores repetidos
     *
     * @param  array  Arreglo con los filtros SQL de la forma columna = valor
     * @return string Texto fragmento de SQL
     */
    public static function sql_filtros_sin_valores_repetidos($in_arreglo) {
        if (!is_array($in_arreglo) || (count($in_arreglo) == 0)) {
            return array();
        }
        $sin_valores_repetidos = array();
        foreach ($in_arreglo as $a) {
            if (!in_array($a, $sin_valores_repetidos)) {
                $sin_valores_repetidos[] = $a;
            }
        }
        return implode(' AND ', $sin_valores_repetidos);
    } // sql_filtros_sin_valores_repetidos

} // Clase abstracta UtileriasParaSQL

?>
