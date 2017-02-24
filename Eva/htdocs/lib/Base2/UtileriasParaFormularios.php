<?php
/**
 * GenesisPHP - UtileriasParaFormularios
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
 * Clase abtracta UtileriasParaFormularios
 */
abstract class UtileriasParaFormularios {

    /**
     * Post Boleano
     *
     * @param  string
     * @return string
     */
    public static function post_boleano($dato) {
        if ($dato === '-') {
            return NULL;
        } elseif ($dato === 't') {
            return TRUE;
        } elseif ($dato === 'f') {
            return FALSE;
        }
    } // post_boleano

    /**
     * Post Celular
     *
     * @param  string
     * @return string
     */
    public static function post_celular($celular) {
        return preg_replace('/[()\.\- ]/', '', $celular); // Quitar paréntesis, puntos y guiones
    } // post_celular

    /**
     * Post Select
     *
     * @param  string
     * @return string
     */
    public static function post_select($dato) {
        if ($dato === '-') {
            return ''; // Cambiamos '-' por ''
        } else {
            return $dato;
        }
    } // post_select

    /**
     * Post Texto
     *
     * @param  string
     * @return string
     */
    public static function post_texto($dato) {
        $comilla_simple = str_replace("\'", "'", strval($dato));
        $comilla_doble  = str_replace('\"', '"', strval($comilla_simple));
        return preg_replace('/\h+/', ' ', trim($comilla_doble)); // Reeplazar dos o mas espacios horizontales por uno solo
    } // post_texto

    /**
     * Post texto mayúsculas
     *
     * @param  string
     * @return string
     */
    public static function post_texto_mayusculas($dato) {
        $normalizar = array(
            'à' => 'Á', 'è' => 'É', 'ì' => 'Í', 'ò' => 'Ó', 'ù' => 'Ú',
            'À' => 'Á', 'È' => 'É', 'Ì' => 'Í', 'Ò' => 'Ó', 'Ù' => 'Ú',
            'á' => 'Á', 'é' => 'É', 'í' => 'Í', 'ó' => 'Ó', 'ú' => 'Ú',
            'Á' => 'Á', 'É' => 'É', 'Í' => 'Í', 'Ó' => 'Ó', 'Ú' => 'Ú',
            'ñ' => 'Ñ',
            'ü' => 'Ü');
        $normalizado = strtr($dato, $normalizar);
        return self::post_texto(strtoupper($normalizado));
    } // post_texto_mayusculas

    /**
     * Post texto mayúsculas sin acentos
     *
     * @param  string
     * @return string
     */
    public static function post_texto_mayusculas_sin_acentos($dato) {
        $normalizar = array(
            'à' => 'A', 'è' => 'E', 'ì' => 'I', 'ò' => 'O', 'ù' => 'U',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
            'á' => 'A', 'é' => 'E', 'í' => 'I', 'ó' => 'O', 'ú' => 'U',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'Ñ',
            'ü' => 'Ü');
        $normalizado = strtr($dato, $normalizar);
        return self::post_texto(strtoupper($normalizado));
    } // post_texto_mayusculas_sin_acentos

    /**
     * Post texto minúsculas
     *
     * @param  string
     * @return string
     */
    public static function post_texto_minusculas($dato) {
        $normalizar = array(
            'à' => 'á', 'è' => 'é', 'ì' => 'í', 'ò' => 'ó', 'ù' => 'ú',
            'À' => 'á', 'È' => 'é', 'Ì' => 'í', 'Ò' => 'ó', 'Ù' => 'ú',
            'á' => 'á', 'é' => 'é', 'í' => 'í', 'ó' => 'ó', 'ú' => 'ú',
            'Á' => 'á', 'É' => 'é', 'Í' => 'í', 'Ó' => 'ó', 'Ú' => 'ú',
            'Ñ' => 'ñ',
            'Ü' => 'ü');
        $normalizado = strtr($dato, $normalizar);
        return self::post_texto(strtolower($normalizado));
    } // post_texto_minusculas

    /**
     * Post texto minúsculas sin acentos
     *
     * @param  string
     * @return string
     */
    public static function post_texto_minusculas_sin_acentos($dato) {
        $normalizar = array(
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'À' => 'a', 'È' => 'e', 'Ì' => 'i', 'Ò' => 'o', 'Ù' => 'u',
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'Ñ' => 'ñ',
            'Ü' => 'ü');
        $normalizado = strtr($dato, $normalizar);
        return self::post_texto(strtolower($normalizado));
    } // post_texto_minusculas_sin_acentos

} // Clase abtracta UtileriasParaFormularios

?>
