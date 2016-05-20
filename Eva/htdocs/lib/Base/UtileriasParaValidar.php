<?php
/**
 * GenesisPHP - Utilerías para Validar
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
 * Clase Utilerias para Validar
 */
abstract class UtileriasParaValidar {

    /**
     * Validar nombre
     *
     * @param  string  Nombre a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_nombre($nombre) {
        if (trim($nombre) == '') {
            return false;
        } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ() .,_-]+$/', $nombre)) {
            return true;
        } else {
            return false;
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
            return false;
        } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ#&%$@¿?()"“” .,;:\/\[\]*+_-]+$/', $frase)) {
            return true;
        } else {
            return false;
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
            return false;
        } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ#&%$@¿?()<>{}|="“” .,:;\'\/\n\r\[\]\\\\_+*-]+$/', $notas)) {
            return true;
        } else {
            return false;
        }
    } // validar_notas

    /**
     * Validar nombre corto
     *
     * @param  string  Nombre corto a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_nom_corto($nom_corto) {
        if (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ_.]{4,48}$/', $nom_corto)) {
            return true;
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
                return true;
            } else {
                return false;
            }
        } elseif (is_int($entero)) {
            return true;
        } else {
            return false;
        }
    } // validar_entero

    /**
     * Validar flotante
     *
     * @param  string  Número con decimales a validar
     * @return boolean Verdadero si es válido
     */
    public static function validar_flotante($flotante) {
        if (is_string($flotante)) {
            if (preg_match('/^\-?[0-9]*\.?[0-9]+$/', $flotante)) {
                return true;
            } else {
                return false;
            }
        } elseif (is_float($flotante) || is_integer($flotante)) {
            return true;
        } else {
            return false;
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
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } elseif (is_float($porcentaje) || is_integer($porcentaje)) {
            if (($porcentaje >= 0) && ($porcentaje <= 100)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
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
                return true;
            }
            } else {
                return false;
            }
        } elseif (is_float($peso) || is_integer($peso)) {
           if (($peso >= 0) && ($peso <= 400)) {
                return true;
            }
        } else {
            return false;
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
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } elseif (is_float($estatura) || is_integer($estatura)) {
            if (($estatura >= 0.2) && ($estatura <= 2.5)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
            return true; // AAAA-MM-DD HH:MM:SS
        } elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}T\d{1,2}:\d{1,2}:\d{1,2}$/', $fecha_hora)) {
            return true; // AAAA-MM-DDTHH:MM:SS
        } elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2} \d{1,2}:\d{1,2}$/', $fecha_hora)) {
            return true; // AAAA-MM-DD HH:MM
        } elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}T\d{1,2}:\d{1,2}$/', $fecha_hora)) {
            return true; // AAAA-MM-DDTHH:MM
        } elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $fecha_hora)) {
            return true; // AAAA-MM-DD
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
            return true;
        } else {
            return false;
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
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        if (self::validar_flotante($latitud)) {
            if (($latitud >= 0) && ($latitud <= 90)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } // validar_geopunto

} // Clase Utilerias para Validar

?>
