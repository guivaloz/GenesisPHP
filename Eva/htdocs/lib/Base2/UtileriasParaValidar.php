<?php
/**
 * GenesisPHP - UtileriasParaValidar
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
 * Clase abtracta UtileriasParaValidar
 */
abstract class UtileriasParaValidar {

    /**
     * Validar boleano
     *
     * @param  boolean Boleano a validar
     * @return boolean Verdadero si es boleano
     */
    public static function validar_boleano($boleano) {
        if (is_bool($boleano)) {
            return TRUE;
        } elseif (is_int($boleano)) {
            return TRUE;
        } elseif (is_string($boleano) && ((strtolower(trim($boleano)) == 't') || (strtolower(trim($boleano)) == 'true') || (strtolower(trim($boleano)) == 'f') || (strtolower(trim($boleano)) == 'false'))) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_boleano

    /**
     * Validar nombre
     *
     * @param  string  Nombre a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_nombre($nombre) {
        if (trim($nombre) == '') {
            return FALSE;
        } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ() .,_-]+$/', $nombre)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_nombre

    /**
     * Validar frase
     *
     * @param  string  Frase a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_frase($frase) {
        if (trim($frase) == '') {
            return FALSE;
        } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ#&%$@¿?¡!()<>"“” .,;:\'\/\[\]*+_-]+$/', $frase)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_frase

    /**
     * Validar notas
     *
     * @param  string  Notas a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_notas($notas) {
        if (trim($notas) == '') {
            return FALSE;
        } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ#&%$@¿?¡!()<>{}|="“” .,:;\'\/\n\r\[\]\\\\_+*-]+$/', $notas)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_notas

    /**
     * Validar nombre corto
     *
     * @param  string  Nombre corto a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_nom_corto($nom_corto) {
        if (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ_.]{2,48}$/', $nom_corto)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_nom_corto

    /**
     * Validar CURP
     *
     * @param  string  CURP a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_curp($curp) {
        if (preg_match('/^[a-zA-Z0-9]{18}$/', $curp)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_curp

    /**
     * Validar RFC
     *
     * @param  string  RFC a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_rfc($rfc) {
        if (preg_match('/^[a-zA-Z0-9]{10,13}$/', $rfc)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_rfc

    /**
     * Validar CUIP
     *
     * @param  string  CUIP a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_cuip($cuip) {
        if (preg_match('/^[a-zA-Z0-9]{1,20}$/', $cuip)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_cuip

    /**
     * Validar contraseña
     *
     * @param  string  Contraseña a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_contrasena($contrasena) {
        if (preg_match('/^[a-zA-Z0-9.,:_-]{6,24}$/', $contrasena)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_contrasena

    /**
     * Validar entero
     *
     * @param  string  Número entero a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_entero($entero) {
        if (is_string($entero)) {
            if (preg_match('/^\-?[0-9]+$/', $entero)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } elseif (is_int($entero)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_entero

    /**
     * Validar serial
     *
     * Entero positivo mayor a cero, NI CERO, NI NEGATIVO
     *
     * @param  string  Número serial a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_serial($serial) {
        if (is_string($serial)) {
            if (preg_match('/^[0-9]+$/', $serial) && (intval($serial) > 0)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } elseif (is_int($serial) && (intval($serial) > 0)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_serial

    /**
     * Validar cantidad
     *
     * Entero positivo mayor o igual a cero, NO NEGATIVO
     *
     * @param  string  Número serial a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_cantidad($cantidad) {
        if (is_string($cantidad)) {
            if (preg_match('/^[0-9]+$/', $cantidad) && (intval($cantidad) >= 0)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } elseif (is_int($cantidad) && ($cantidad >= 0)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_cantidad

    /**
     * Validar flotante
     *
     * @param  string  Número con decimales a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_flotante($flotante) {
        if (is_string($flotante)) {
            if (preg_match('/^\-?[0-9]*\.?[0-9]+$/', $flotante)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } elseif (is_float($flotante) || is_integer($flotante)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_flotante

    /**
     * Validar porcentaje
     *
     * @param  string  Porcentaje de 0 a 100 a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_porcentaje($porcentaje) {
        if (is_string($porcentaje)) {
            if (preg_match('/^(100|\d{1,4}(\.\d{1,4})?)$/', $porcentaje)) {
                $flotante = floatval($porcentaje);
                if (($flotante >= 0) && ($flotante <= 100)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } elseif (is_float($porcentaje) || is_integer($porcentaje)) {
            if (($porcentaje >= 0) && ($porcentaje <= 100)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } // validar_porcentaje

    /**
     * Validar peso
     *
     * @param  string  Peso de 0 a 400 kilogramos a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_peso($peso) {
        if (is_string($peso)) {
            if (preg_match('/^\d{1,3}(\.\d{1,2})?$/', $peso)) {
                $flotante = floatval($peso);
               if (($flotante >= 0) && ($flotante <= 400)) {
                return TRUE;
            }
            } else {
                return FALSE;
            }
        } elseif (is_float($peso) || is_integer($peso)) {
           if (($peso >= 0) && ($peso <= 400)) {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    } // validar_peso

    /**
     * Validar estatura
     *
     * @param  string  Estatura de 0.2 a 2.5 metros a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_estatura($estatura) {
        if (is_string($estatura)) {
            if (preg_match('/^\d{1,2}(\.\d{1,2})?$/', $estatura)) {
                $flotante = floatval($estatura);
                if (($flotante >= 0.2) && ($flotante <= 2.5)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } elseif (is_float($estatura) || is_integer($estatura)) {
            if (($estatura >= 0.2) && ($estatura <= 2.5)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } // validar_estatura

    /**
     * Validar fecha
     *
     * @param  string  Fecha de la forma AAAA-MM-DD a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_fecha($fecha) {
        if (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $fecha)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_fecha

    /**
     * Validar hora
     *
     * @param  string  Hora de la forma HH:MM:SS o HH:MM a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_hora($hora) {
        if ((preg_match('/^\d{1,2}:\d{1,2}:\d{1,2}$/', $hora)) || (preg_match('/^\d{1,2}:\d{1,2}$/', $hora))) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_hora

    /**
     * Validar fecha hora
     *
     * @param  string  Fecha y hora de la forma AAAA-MM-DD HH:MM a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_fecha_hora($fecha_hora) {
        if (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/', $fecha_hora)) {
            return TRUE; // AAAA-MM-DD HH:MM:SS
        } elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}T\d{1,2}:\d{1,2}:\d{1,2}$/', $fecha_hora)) {
            return TRUE; // AAAA-MM-DDTHH:MM:SS
        } elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2} \d{1,2}:\d{1,2}$/', $fecha_hora)) {
            return TRUE; // AAAA-MM-DD HH:MM
        } elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}T\d{1,2}:\d{1,2}$/', $fecha_hora)) {
            return TRUE; // AAAA-MM-DDTHH:MM
        } elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $fecha_hora)) {
            return TRUE; // AAAA-MM-DD
        } else {
            return FALSE;
        }
    } // validar_fecha_hora

    /**
     * Validar e-mail
     *
     * @param  string  e-mail a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_email($email) {
        if (preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$/', $email)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_email

    /**
     * Validar clave
     *
     * @param  string  Clave (con mayúsculas, números, espacio o guión) a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_clave($clave) {
        if (preg_match('/^[A-Z0-9 -]+$/', $clave)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_clave

    /**
     * Validar teléfono
     *
     * @param  string  Teléfono a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_telefono($telefono) {
        if (preg_match('/^[0-9()\- ]+$/', $telefono)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_telefono

    /**
     * Validar celular
     *
     * @param  string  Teléfono a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_celular($celular) {
        if (preg_match('/^[0-9]{10}$/', $celular)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // validar_telefono

    /**
     * Validar geopunto
     *
     * @param  string  Longitud
     * @param  string  Latitud
     * @return boolean Verdadero si es válido
     */
    public static function validar_geopunto($longitud, $latitud) {
        if (self::validar_flotante($longitud)) {
            if (($longitud >= -180) && ($longitud <= 180)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
        if (self::validar_flotante($latitud)) {
            if (($latitud >= 0) && ($latitud <= 90)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } // validar_geopunto

} // Clase abtracta UtileriasParaValidar

?>
