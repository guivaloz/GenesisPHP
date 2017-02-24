<?php
/**
 * GenesisPHP - UtileriasParaFormatos
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
 * Clase abstracta UtileriasParaFormatos
 */
abstract class UtileriasParaFormatos {

    /**
     * Formato boleano
     *
     * @param  boolean Boleano
     * @return string  Texto 'Verdadero' o 'Falso'
     */
    public static function formato_boleano($boleano) {
        if (is_bool($boleano) && ($boleano == TRUE)) {
            return 'Verdadero';
        } elseif (is_int($boleano) && ($boleano !== 0)) {
            return 'Verdadero';
        } elseif (is_string($boleano) && ((strtolower(trim($boleano)) == 't') || (strtoupper(trim($boleano)) == 'TRUE'))) {
            return 'Verdadero';
        } else {
            return 'Falso';
        }
    } // formato_boleano

    /**
     * Formato entero
     *
     * @param  mixed  Entero
     * @return string Texto que usa comas en los miles
     */
    public static function formato_entero($entero) {
        if (UtileriasParaValidar::validar_entero($entero)) {
            return $entero; // number_format(, 0, ".", ",");
        } else {
            return '';
        }
    } // formato_entero

    /**
     * Formato flotante
     *
     * @param  mixed   Entero o flotante
     * @param  integer Opcional, cantidad de decimales, por defecto cuatro
     * @return string  Texto con comas en los miles y decimales
     */
    public static function formato_flotante($cantidad, $decimales=4) {
        if (UtileriasParaValidar::validar_entero($cantidad) || UtileriasParaValidar::validar_flotante($cantidad)) {
            return number_format($cantidad, $decimales, ".", ",");
        } else {
            return '';
        }
    } // formato_flotante

    /**
     * Formato porcentaje
     *
     * @param  mixed   Entero o flotante
     * @param  integer Opcional, cantidad de decimales, por defecto dos
     * @return string  Texto con decimales y el signo de porcentaje
     */
    public static function formato_porcentaje($cantidad, $decimales=2) {
        if (UtileriasParaValidar::validar_entero($cantidad) || UtileriasParaValidar::validar_flotante($cantidad)) {
            return number_format($cantidad, $decimales, ".", ",")." %";
        } else {
            return '';
        }
    } // formato_porcentaje

    /**
     * Formato dinero
     *
     * @param  mixed   Entero o flotante
     * @return string  Texto con signo de pesos, comas en los miles y dos decimales
     */
    public static function formato_dinero($cantidad) {
        $entrega = self::formato_flotante($cantidad, 2);
        if ($entrega !== '') {
            return '$ '.$entrega;
        } else {
            return $entrega;
        }
    } // formato_dinero

    /**
     * Formato fecha
     *
     * @param  string Fecha en el formato de la base de datos YYYY-MM-DD
     * @return string Fecha en el formato DD/MM/YYYY
     */
    public static function formato_fecha($in_fecha) {
        $a = explode('-', $in_fecha);
        return sprintf('%02d/%02d/%04d', $a[2], $a[1], $a[0]);
    } // formato_fecha

    /**
     * Formato Fecha Hora
     *
     * @param  string Fecha Hora
     * @param  string Opcional, separador 'T'
     * @return string Fecha en el formato DD/MM/YYYY hh:mm:ss o YYYY-MM-DDThh:mm:ss
     */
    public static function formato_fecha_hora($in_fecha_hora, $in_separador='') {
        $t = strtotime($in_fecha_hora);
        if ($t === FALSE) {
            return ''; // Fecha mal escrita, no se entrega nada
        } else {
            $a        = getdate($t);
            $ano      = $a['year'];
            $mes      = $a['mon'];
            $dia      = $a['mday'];
            $hora     = $a['hours'];
            $minuto   = $a['minutes'];
            $segundos = $a['seconds'];
            if (($hora > 0) || ($minuto > 00) || ($segundos > 00)) {
                if ($segundos > 00) {
                    if ($in_separador == 'T') {
                        return sprintf('%04d-%02d-%02dT%02d:%02d:%02d', $ano, $mes, $dia, $hora, $minuto, $segundos);
                    } else {
                        return sprintf('%02d/%02d/%04d %02d:%02d:%02d', $dia, $mes, $ano, $hora, $minuto, $segundos);
                    }
                } else {
                    if ($in_separador == 'T') {
                        return sprintf('%04d-%02d-%02dT%02d:%02d', $ano, $mes, $dia, $hora, $minuto);
                    } else {
                        return sprintf('%02d/%02d/%04d %02d:%02d', $dia, $mes, $ano, $hora, $minuto);
                    }
                }
            } else {
                return sprintf('%02d/%02d/%04d', $dia, $mes, $ano);
            }
        }
    } // formato_fecha_hora

    /**
     * Formato contenido
     *
     * Para el contenido que va entre tags HTML, cambia los menor que, mayor que y comilla doble
     *
     * @param  string
     * @return string
     */
    public static function formato_contenido($texto) {
        // Definir título y descripción con carecteres validos para usarse como valor en un tag
        $buscar      = array('"', '<', '>');
        $reemplazar  = array("'", '-', '-');
        $con_cambios = str_replace($buscar, $reemplazar, $texto);
        return htmlspecialchars($con_cambios);
    } // formato_contenido

    /**
     * Caracteres al azar
     *
     * @param  integer Cantidad de caracteres, por defecto 8
     * @return string  Caracteres al azar
     */
    public static function caracteres_azar($in_cantidad=8) {
        $primera = ord('a');
        $ultima  = ord('z');
        $c = array();
        for ($i=0; $i<$in_cantidad; $i++) {
            $c[] = chr(rand($primera, $ultima));
        }
        return implode('', $c);
    } // caracteres_azar

    /**
     * Caracteres para web
     *
     * @param  string  Nombre a convertir
     * @param  boolean Por defecto es falso, si es verdadero se omiten 'y', 'a', 'el', etc.
     * @param  string  Caracter para separar palabras, por defecto es un guion
     * @return string  Texto convertido a caracteres para web
     */
    public static function caracteres_para_web($in_nombre, $in_omitir_bandera=FALSE, $in_separador='-') {
        // Omitir estas palabras
        $palabras_omitir = array('y', 'a', 'el', 'la', 'los', 'las', 'de', 'del');
        // Cambiar caracteres
        $buscados        = array('ñ', 'Ñ', 'ü', 'Ü', 'á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú');
        $cambios         = array('n', 'n', 'u', 'u', 'a', 'a', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u');
        $sin_acentos     = str_replace($buscados, $cambios, $in_nombre);
        $especiales      = array('(', ')', '.', ',', ';', '_', '-');
        $minusculas      = strtolower(str_replace($especiales, ' ', $sin_acentos));
        // Revisar cada palabra
        $palabras = array();
        foreach (explode(' ', $minusculas) as $p) {
            if ($p !== '') {
                if ($in_omitir_bandera && in_array($p, $palabras_omitir)) {
                    continue;
                } else {
                    $palabras[] = $p;
                }
            }
        }
        // Entregar
        return implode($in_separador, $palabras);
    } // caracteres_para_web

    /**
     * Caracteres para método
     *
     * @param  string  Nombre a convertir
     * @return string  Texto convertido a caracteres para web
     */
    public static function caracteres_para_metodo($in_nombre) {
        return self::caracteres_para_web($in_nombre, FALSE, '_');
    } // caracteres_para_metodo

    /**
     * Caracteres para clase
     *
     * @param  string  Nombre a convertir
     * @param  boolean Por defecto es falso, si es verdadero se omiten 'y', 'a', 'el', etc.
     * @return string  Texto convertido a caracteres para web
     */
    public static function caracteres_para_clase($in_texto, $in_omitir_bandera=FALSE) {
        // Omitir estas palabras
        $palabras_omitir = array('y', 'a', 'el', 'la', 'los', 'las', 'de', 'del');
        // Cambiar caracteres
        $buscados        = array('ñ', 'Ñ', 'ü', 'Ü', 'á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú');
        $cambios         = array('n', 'n', 'u', 'u', 'a', 'a', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u');
        $sin_acentos     = str_replace($buscados, $cambios, $in_texto);
        $especiales      = array('(', ')', '.', ',', ';', '_', '-');
        $minusculas      = strtolower(str_replace($especiales, ' ', $sin_acentos));
        // Poner en mayusculas la primer letra de cada palabra
        $palabras_camel_case = array();
        foreach (explode(' ', $minusculas) as $p) {
            if ($p !== '') {
                if ($in_omitir_bandera && in_array($p, $palabras_omitir)) {
                    continue;
                } else {
                    $palabras_camel_case[] = ucfirst($p);
                }
            }
        }
        // Entregar
        return implode('', $palabras_camel_case);
    } // caracteres_para_clase

} // Clase abstracta UtileriasParaFormatos

?>
