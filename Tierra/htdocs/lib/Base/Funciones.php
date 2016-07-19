<?php
/**
 * Funciones
 *
 * @package Tierra
 */

// TODOS LOS CARACTERES SERAN UTF-8
mb_internal_encoding('utf-8');

// AUTOCARGADOR DE CLASES
spl_autoload_register(
    /**
     * Auto-cargador de Clases
     *
     * @param string Creación de la instancia
     */
    function ($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';
        require 'lib/'.$fileName;
    } // auto-cargador de clases
);

/**
 * Validar Nombre
 *
 * @param  string
 * @return boolean
 */
function validar_nombre($nombre) {
    if (trim($nombre) == '') {
        return false;
    } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ() .,_-]+$/', $nombre)) {
        return true;
    } else {
        return false;
    }
} // validar_nombre

/**
 * Validar Frase
 *
 * @param  string
 * @return boolean
 */
function validar_frase($frase) {
    if (trim($frase) == '') {
        return false;
    } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ#&%$@¿?()"“” .,;:\/\[\]*+_-]+$/', $frase)) {
        return true;
    } else {
        return false;
    }
} // validar_frase

/**
 * Validar Notas
 *
 * @param  string
 * @return boolean
 */
function validar_notas($notas) {
    if (trim($notas) == '') {
        return false;
    } elseif (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ#&%$@¿?()<>{}|="“” .,:;\'\/\n\r\[\]\\\\_+*-]+$/', $notas)) {
        return true;
    } else {
        return false;
    }
} // validar_notas

/**
 * Validar Nombre Corto
 *
 * @param  string
 * @return booleanean
 */
function validar_nom_corto($nom_corto) {
    if (preg_match('/^[a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ_.]{4,48}$/', $nom_corto)) {
        return true;
    } else {
        return false;
    }
} // validar_nom_corto

/**
 * Validar CURP
 *
 * @param  string
 * @return booleanean
 */
function validar_curp($curp) {
    if (preg_match('/^[a-zA-Z0-9]{18}$/', $curp)) {
        return true;
    } else {
        return false;
    }
} // validar_curp

/**
 * Validar RFC
 *
 * @param  string
 * @return booleanean
 */
function validar_rfc($rfc) {
    if (preg_match('/^[a-zA-Z0-9]{10,13}$/', $rfc)) {
        return true;
    } else {
        return false;
    }
} // validar_rfc

/**
 * Validar CUIP
 *
 * @param  string
 * @return booleanean
 */
function validar_cuip($cuip) {
    if (preg_match('/^[a-zA-Z0-9]{1,20}$/', $cuip)) {
        return true;
    } else {
        return false;
    }
} // validar_cuip

/**
 * Validar Contraseña
 *
 * @param  string
 * @return booleanean
 */
function validar_contrasena($contrasena) {
    if (preg_match('/^[a-zA-Z0-9.,:_-]{6,24}$/', $contrasena)) {
        return true;
    } else {
        return false;
    }
} // validar_contrasena

/**
 * Validar ruta
 *
 * @param  string
 * @return booleanean
 */
function validar_ruta($ruta) {
    if (preg_match('/^[a-zA-Z0-9/_\-]+$/', $ruta)) {
        return true;
    } else {
        return false;
    }
} // validar_ruta

/**
 * Validar Variable
 *
 * @param  string
 * @return boolean
 */
function validar_variable($variable) {
    if (is_string($variable)) {
        if (trim($variable) == '') {
            return false;
        } elseif (preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $variable)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
} // validar_variable

/**
 * Validar Entero
 *
 * @param  mixed
 * @return boolean
 */
function validar_entero($entero) {
    if (is_string($entero)) {
        if (preg_match('/^[0-9]+$/', $entero)) {
            return true;
        } else {
            return false;
        }
    } elseif (is_int($entero)) {
        return ($entero >= 0);
    } else {
        return false;
    }
} // validar_entero

/**
 * Validar Flotante
 *
 * @param  mixed
 * @return boolean
 */
function validar_flotante($flotante) {
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
 * Validar Porcentaje
 *
 * @param  mixed
 * @return boolean
 */
function validar_porcentaje($porcentaje) {
    if (is_string($porcentaje)) {
        // Antes preg_match('/^(100|\d{1,4}(\.\d{1,4})?)$/', $porcentaje) y entre 0 y 100
        if (preg_match('/^\-?[0-9]*\.?[0-9]+$/', $porcentaje)) {
            return true;
        } else {
            return false;
        }
    } elseif (is_float($porcentaje) || is_integer($porcentaje)) {
        return true;
    } else {
        return false;
    }
} // validar_porcentaje

/**
 * Validar Peso
 *
 * @param  mixed
 * @return boolean
 */
function validar_peso($peso) {
    if (is_string($peso)) {
        if (preg_match('/^\d{1,3}(\.\d{1,2})?$/', $peso)) {
            $flotante = floatval($peso);
           if (($flotante >= 35) && ($flotante <= 200)) {
            return true;
        }
        } else {
            return false;
        }
    } elseif (is_float($peso) || is_integer($peso)) {
       if (($peso >= 35) && ($peso <= 200)) {
            return true;
        }
    } else {
        return false;
    }
} // validar_peso

/**
 * Validar Estatura
 *
 * @param  mixed
 * @return boolean
 */
function validar_estatura($estatura) {
    if (is_string($estatura)) {
        if (preg_match('/^\d{1,2}(\.\d{1,2})?$/', $estatura)) {
            $flotante = floatval($estatura);
            if (($flotante >= 1.2) && ($flotante <= 2.5)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } elseif (is_float($estatura) || is_integer($porcentaje)) {
        if (($estatura >= 1.2) && ($estatura <= 2.5)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
} // estatura minima 1.20 mts y estatura maxima 2.50
// validar_estatura

/**
 * Validar Fecha
 *
 * @param  string
 * @return boolean
 */
function validar_fecha($fecha) {
    // VALIDA AAAA-MM-DD
    if (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $fecha)) {
        return true;
    } else {
        return false;
    }
} // validar_fecha

/**
 * Validar Hora
 *
 * @param  string
 * @return boolean
 */
function validar_hora($hora) {
    // VALIDA HH:MM:SS o HH:MM
    if ((preg_match('/^\d{1,2}:\d{1,2}:\d{1,2}$/', $hora)) || (preg_match('/^\d{1,2}:\d{1,2}$/', $hora))) {
        return true;
    } else {
        return false;
    }
} // validar_hora

/**
 * Validar Fecha Hora
 *
 * @param  string
 * @return boolean
 */
function validar_fecha_hora($fecha_hora) {
    // VALIDA
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
 * @param  string
 * @return boolean
 */
function validar_email($email) {
    if (preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$/', $email)) {
        return true;
    } else {
        return false;
    }
} // validar_email

/**
 * Validar Clave
 *
 * @param  string
 * @return booleanean
 */
function validar_clave($clave) {
    if (preg_match('/^[A-Z0-9 -]+$/', $clave)) {
        return true;
    } else {
        return false;
    }
} // validar_clave

/**
 * Validar Teléfono
 *
 * @param  string
 * @return booleanean
 */
function validar_telefono($telefono) {
    if (preg_match('/^[0-9()\- ]+$/', $telefono)) {
        return true;
    } else {
        return false;
    }
} // validar_telefono

/**
 * Validar GeoPunto
 *
 * @param  float Longitud
 * @param  float Latitud
 * @return booleanean
 */
function validar_geopunto($longitud, $latitud) {
    if (validar_flotante($longitud)) {
        if (($longitud >= -180) && ($longitud <= 180)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
    if (validar_flotante($latitud)) {
        if (($latitud >= 0) && ($latitud <= 90)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
} // validar_geopunto

/**
 * Get ID
 *
 * @param  integer
 * @return integer
 */
function get_id($dato) {
    return htmlspecialchars(addslashes(stripslashes(strip_tags(trim($dato)))));
} // post_id;

/**
 * Post Texto
 *
 * @param  string
 * @return string
 */
function post_texto($dato) {
    $comilla_simple = str_replace("\'", "'", strval($dato));
    $comilla_doble  = str_replace('\"', '"', strval($comilla_simple));
    return preg_replace('/\h+/', ' ', trim($comilla_doble)); // REEPLAZAR DOS O MAS ESPACIOS HORIZONTALES POR UNO SOLO
} // post_texto

/**
 * Post texto minúsculas
 *
 * @param string
 * @return string
 */
function post_texto_minusculas($dato) {
    $normalizar = array(
        'à' => 'á', 'è' => 'é', 'ì' => 'í', 'ò' => 'ó', 'ù' => 'ú',
        'À' => 'á', 'È' => 'é', 'Ì' => 'í', 'Ò' => 'ó', 'Ù' => 'ú',
        'á' => 'á', 'é' => 'é', 'í' => 'í', 'ó' => 'ó', 'ú' => 'ú',
        'Á' => 'á', 'É' => 'é', 'Í' => 'í', 'Ó' => 'ó', 'Ú' => 'ú',
        'Ñ' => 'ñ',
        'Ü' => 'ü');
    $normalizado = strtr($dato, $normalizar);
    return post_texto(strtolower($normalizado));
} // post_texto_minusculas

/**
 * Post texto mayúsculas
 *
 * @param string
 * @return string
 */
function post_texto_mayusculas($dato) {
    $normalizar = array(
        'à' => 'Á', 'è' => 'É', 'ì' => 'Í', 'ò' => 'Ó', 'ù' => 'Ú',
        'À' => 'Á', 'È' => 'É', 'Ì' => 'Í', 'Ò' => 'Ó', 'Ù' => 'Ú',
        'á' => 'Á', 'é' => 'É', 'í' => 'Í', 'ó' => 'Ó', 'ú' => 'Ú',
        'Á' => 'Á', 'É' => 'É', 'Í' => 'Í', 'Ó' => 'Ó', 'Ú' => 'Ú',
        'ñ' => 'Ñ',
        'ü' => 'Ü');
    $normalizado = strtr($dato, $normalizar);
    return post_texto(strtoupper($normalizado));
} // post_texto_mayusculas

/**
 * Post texto mayúsculas sin acentos
 *
 * @param string
 * @return string
 */
function post_texto_mayusculas_sin_acentos($dato) {
    $normalizar = array(
        'à' => 'A', 'è' => 'E', 'ì' => 'I', 'ò' => 'O', 'ù' => 'U',
        'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
        'á' => 'A', 'é' => 'E', 'í' => 'I', 'ó' => 'O', 'ú' => 'U',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'ñ' => 'Ñ',
        'ü' => 'Ü');
    $normalizado = strtr($dato, $normalizar);
    return post_texto(strtoupper($normalizado));
} // post_texto_mayusculas_sin_acentos

/**
 * Cuando se recibe un select cambiamos '-' por ''
 *
 * @param  string
 * @return string
 */
function post_select($dato) {
    if ($dato === '-') {
        return '';
    } else {
        return $dato;
    }
} // post_select

/**
 * SQL Texto
 *
 * @param  string
 * @return string
 */
function sql_texto($dato) {
    if (trim($dato) == '') {
        return 'NULL';
    } else {
        return "'".pg_escape_string(trim($dato))."'";
    }
} // sql_texto

/**
 * SQL Texto NO Nulo
 *
 * @param  string
 * @return string
 */
function sql_texto_no_nulo($dato) {
    if (trim($dato) == '') {
        return "''";
    } else {
        return "'".pg_escape_string(trim($dato))."'";
    }
} // sql_texto_no_nulo

/**
 * SQL Texto en mayúsculas
 *
 * @param  string
 * @return string
 */
function sql_texto_mayusculas($dato) {
    $normalizar = array(
        'à' => 'A', 'è' => 'E', 'ì' => 'I', 'ò' => 'O', 'ù' => 'U',
        'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
        'á' => 'A', 'é' => 'E', 'í' => 'I', 'ó' => 'O', 'ú' => 'U',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'ñ' => 'Ñ',
        'ü' => 'Ü');
    $normalizado = strtr($dato, $normalizar);
    return "'".pg_escape_string(trim(strtoupper($normalizado)))."'";
} // sql_texto_mayusculas

/**
 * SQL Entero
 *
 * @param  mixed
 * @return mixed
 */
function sql_entero($dato) {
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
        return 'NULL';
    }
} // sql_entero

/**
 * SQL Flotante
 *
 * @param  mixed
 * @return mixed
 */
function sql_flotante($dato) {
    //(($dato === '') || ($dato == '0') || ($dato == '0.') || ($dato == '.0') || ($dato == '0.0'))
    if (is_string($dato)) {
        $sin_espacios = trim($dato);
        if (trim($sin_espacios) === '') {
            return 'NULL';
        } elseif (preg_match('/^\-?0*\.?0+$/', $sin_espacios)) {
            return 0;
        } else {
            return floatval($sin_espacios);
        }
    } elseif (is_int($dato) || is_float($dato)) {
        if ($dato == 0) {
            return 0;
        } else {
            return floatval($dato);
        }
    } else {
        return 'NULL';
    }
} // sql_flotante

/**
 * SQL Tiempo
 *
 * @param  mixed
 * @return string
 */
function sql_tiempo($dato) {
    if (is_string($dato)) {
        if ($dato == '') {
            return 'NULL';
        } elseif (preg_match('/^[0-9]+$/', $dato)) {
            return "'".date('Y-m-d H:i:s', $dato)."'";
        } else {
            return "'$dato'";
        }
    } elseif (is_integer($dato)) {
        return "'".date('Y-m-d H:i:s', $dato)."'";
    } else {
        return 'NULL';
    }
} // sql_tiempo

/**
 * SQL Boleano
 *
 * @param  mixed
 * @return string
 */
function sql_boleano($dato) {
    if (is_bool($dato) && $dato) {
        return "TRUE";
    } elseif (is_int($dato) && ($dato > 0)) {
        return "TRUE";
    } elseif (is_string($dato) && (($dato == 'true') || ($dato == 't'))) {
        return "TRUE";
    } else {
        return "FALSE";
    }
} // sql_boleano

/**
 * SQL Arreglo numerico
 *
 * @param  array
 * @return string
 */
function sql_arreglo_numerico($dato) {
    if (!is_array($dato) || (count($dato) == 0)) {
        return 'NULL';
    }
    return sprintf("'{%s}'", implode(', ', $dato));
} // sql_arreglo_numerico

/**
 * SQL GeoPunto
 *
 * @param  float  longitud
 * @param  float  latitud
 * @return string
 */
function sql_geopunto($longitud, $latitud) {
    if (($longitud != '') && ($latitud != '')) {
        return "ST_GeomFromText('POINT($longitud $latitud)', 4326)"; // Ejemplo: ST_GeomFromText('POINT(-103.4207 25.5613)'
    } else {
        return 'NULL';
    }
} // sql_geopunto

/**
 * Arreglo sin valores repetidos
 *
 * @param  array Arreglo a procesar
 * @return array Arreglo sin valores repetidos
 */
function arreglo_sin_valores_repetidos($in_arreglo) {
    if (!is_array($in_arreglo) || (count($in_arreglo) == 0)) {
        return array();
    }
    $arreglo = array();
    foreach ($in_arreglo as $a) {
        if (!in_array($a, $arreglo)) {
            $arreglo[] = $a;
        }
    }
    return $arreglo;
} // arreglo_sin_valores_repetidos

/**
 * Arreglo SQL a arreglo numerico
 *
 * @param  string
 * @return array
 */
function arreglo_sql_a_arreglo_numerico($dato) {
    if (!is_string($dato) || (trim($dato) == '')) {
        return array();
    }
    $izq = strpos($dato, '{');
    $der = strrpos($dato, '}');
    if (($izq === false) || ($der === false)) {
        return 'No se encontro { o }';
    }
    $cuerpo = substr($dato, $izq + 1, $der - $izq - 1);
    $arr    = explode(',', $cuerpo);
    $a      = array();
    foreach ($arr as $i) {
        $a[] = intval($i);
    }
    return $a;
} // arreglo_sql_a_arreglo_numerico

/**
 * Texto a arreglo asociativo
 *
 * @param  string
 * @return array
 */
function texto_a_arreglo_asociativo($cadena) {
    if (!is_string($cadena) || (trim($cadena) == '')) {
        return array();
    }
    parse_str($cadena, $arreglo);
    return $arreglo;
} // texto_a_arreglo_asociativo

/**
 * Arreglo asociativo a texto
 *
 * @param  array
 * @param  string
 * @return string
 */
function arreglo_asociativo_a_texto($arreglo, $unir='&') {
    if (!is_array($arreglo)) {
        return false;
    }
    $arr = array();
    foreach ($arreglo as $etiqueta => $valor) {
        if (intval($valor)) {
            $arr[] = "$etiqueta=$valor";
        }
    }
    return implode($unir, $arr);
} // arreglo_asociativo_a_texto

// Texto a mayúsculas
// function texto_a_mayusculas($in_texto) {
    // LA FUNCION STRTOUPPER NO CONVIERTE LAS LETRAS ACENTUADAS
//  $resultado_funcion = strtoupper($in_texto);
    // CON PREG REPLACE CAMBIAMOS LAS MINUSCULAS ACENTUADAS POR SUS RESPECTIVAS MAYUSCULAS
//  $especiales = array('/á/', '/é/', '/í/', '/ó/', '/ú/', '/ü/', '/ñ/');
//  $reemplazos = array('Á',   'É',   'Í',   'Ó',   'Ú',   'Ü',   'Ñ');
//  return preg_replace($especiales, $reemplazos, $resultado_funcion);
//}

/**
 * Reemplazar avances de linea a parrafos
 *
 * @param  string
 * @return string
 */
function avances_de_linea_a_parrafos($in_texto) {
    $trozos = explode("\r\n", $in_texto);
    if (count($trozos) > 1) {
        $t = array();
        foreach ($trozos as $v) {
            if ($v != '') {
                $t[] = "<p>$v</p>";
            }
        }
        return implode("\n", $t);
    } else {
        return $in_texto;
    }
} // avances_de_linea_a_parrafos

/**
 * Es arreglo asociativo
 *
 * @param  mixed
 * @return boolean
 */
function is_associative($var) {
    return is_array($var) && array_diff_key($var,array_keys(array_keys($var)));
}

/**
 * Generar caracteres al azar
 *
 * @param  integer Cantidad de caracteres, por defecto 8
 * @return string  Caracteres al azar
 */
function caracteres_azar($in_cantidad=8) {
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
 * @param  string  Nombre a convertir, puede tener a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ() .,_-
 * @param  boolean Por defecto es falso, si es verdadero se omiten 'y', 'a', 'el', etc.
 * @return string  Texto convertido a caracteres para web
 */
function caracteres_para_web($in_nombre, $in_omitir_bandera=false) {
    // OMITIR ESTAS PALABRAS
    $palabras_omitir = array('y', 'a', 'el', 'la', 'los', 'las', 'de', 'del');
    // CAMBIAR CARACTERES
    $buscados        = array('ñ', 'Ñ', 'ü', 'Ü', 'á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú');
    $cambios         = array('n', 'n', 'u', 'u', 'a', 'a', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u');
    $sin_acentos     = str_replace($buscados, $cambios, $in_nombre);
    $especiales      = array(' ', '(', ')', '.', ',', '_');
    $minusculas      = strtolower(str_replace($especiales, '-', $sin_acentos));
    // REVISAR CADA ṔALABRA
    $palabras = array();
    foreach (explode('-', $minusculas) as $p) {
        if ($p !== '') {
            if ($in_omitir_bandera && in_array($p, $palabras_omitir)) {
                continue;
            } else {
                $palabras[] = $p;
            }
        }
    }
    // ENTREGAR
    return implode('-', $palabras); // Pone guiones medios entre las palabras
} // caracteres_para_web

/**
 * Caracteres para clase
 *
 * @param  string  Nombre a convertir, puede tener a-zA-Z0-9áÁéÉíÍóÓúÚüÜñÑ() .,_-
 * @param  boolean Por defecto es falso, si es verdadero se omiten 'y', 'a', 'el', etc.
 * @return string  Texto convertido a caracteres para web
 */
function caracteres_para_clase($in_texto, $in_omitir_bandera=false) {
    // OMITIR ESTAS PALABRAS
    $palabras_omitir = array('y', 'a', 'el', 'la', 'los', 'las', 'de', 'del');
    // CAMBIAR CARACTERES
    $buscados        = array('ñ', 'Ñ', 'ü', 'Ü', 'á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú');
    $cambios         = array('n', 'n', 'u', 'u', 'a', 'a', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u');
    $sin_acentos     = str_replace($buscados, $cambios, $in_texto);
    $especiales      = array('(', ')', '.', ',', '_', '-');
    $minusculas      = strtolower(str_replace($especiales, ' ', $sin_acentos));
    // PONER EN MAYUSCULAS LA PRIMER LETRA DE CADA PALABRA
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
    // ENTREGAR
    return implode('', $palabras_camel_case);
} // caracteres_para_clase

/**
 * Completar fechas de inicio
 *
 * @param  string
 * @return string
 */
function validar_fecha_inicio($in_fecha_inicio) {
    $fecha_inicio = trim($in_fecha_inicio);
    if (preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $fecha_inicio)) {
        return $fecha_inicio;    // LA FECHA ESTA COMPLETA
    } elseif (preg_match('/^[0-9]{4}-[0-9]{1,2}$/', $fecha_inicio)) {
        return $fecha_inicio."-01";  // SE LE AGREGA EL DIA PRIMERO
    } elseif (preg_match('/^[0-9]{4}$/', $fecha_inicio)) {
        return $fecha_inicio."-01-01";  // SE LE AGREGA EL MES ENERO Y EL DIA PRIMERO
    } else {
        return false;
    }
} // validar_fecha_inicio

/**
 * Completar fechas de termino
 *
 * @param  string
 * @return string
 */
function validar_fecha_termino($in_fecha_termino) {
    $fecha_termino = trim($in_fecha_termino);
    if (preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $fecha_termino)) {
        return $fecha_termino;  // LA FECHA ESTA COMPLETA
    } elseif (preg_match('/^[0-9]{4}-[0-9]{1,2}$/', $fecha_termino)) {
        return date("Y-m-d", mktime(0, 0, 0, substr($fecha_termino,5,2)+1, 0, substr($fecha_termino,0,4)));  // SE LE AGREGA EL ULTIMO DIA DEL MES
    } elseif (preg_match('/^[0-9]{4}$/', $fecha_termino)) {
        return $fecha_termino."-12-31";  // SE LE AGREGA EL MES DICIEMBRE Y EL ULTIMO DIA
    } else {
        return false;
    }
} // validar_fecha_termino

/**
 * Calculo de edad
 *
 * @param  string
 * @return string
 */
function calcular_edad($in_fecha_nacimiento) {
    // LA FECHA DE NACIMIENTO Y LA ACTUAL SE TRANSFORMAN EN OBJETOS DE INTERVALOS DE TIEMPO
    $fecha_nac = new DateTime($in_fecha_nacimiento);  // FECHA ENVIADA POR EL FORMULARIO
    $fecha_act = new DateTime("now");  // FECHA ACTUAL
    // SE CALCULA LA DIFERENCIA ENTRE LAS FECHAS
    $calculo   = date_diff($fecha_nac, $fecha_act);
    // SE EXTRAE LA CANTIDAD DE AÑOS, MESES Y DIAS DE LA DIFERENCIA
    $ano = $calculo->format('%y');
    $mes = $calculo->format('%m');
    $dia = $calculo->format('%d');
    // SI LA CANTIDAD DE AÑOS ES MAYOR A UNO EN PLURAL, SI ES UNO EN SINGULAR
    if ($ano > 1) {
        $ano_txt = sprintf('%s años', $ano);
    } elseif ($ano == 1) {
        $ano_txt = sprintf('%s año', $ano);
    }
    // SI LA CANTIDAD DE MESES ES MAYOR A UNO EN PLURAL, SI ES UNO EN SINGULAR
    if ($mes > 1) {
        $mes_txt = sprintf('%s meses', $mes);
    } elseif ($mes == 1) {
        $mes_txt = sprintf('%s mes', $mes);
    }
    // SI LA CANTIDAD DE DIAS ES MAYOR A UNO EN PLURAL, SI ES UNO EN SINGULAR
    if ($dia > 1) {
        $dia_txt = sprintf('%s días', $dia);
    } elseif ($dia == 1) {
        $dia_txt = sprintf('%s día', $dia);
    }
    // ENTREGAR CADENA DE TEXTO CON LA EDAD
    return sprintf('%s %s %s', $ano_txt, $mes_txt, $dia_txt);
} // calcular_edad

/**
 * Formato entero
 *
 * @param  mixed  Entero
 * @return string Texto con el entero con formato
 */
function formato_entero($entero) {
    if (validar_entero($entero)) {
        return number_format($entero, 0, ".", ",");
    } else {
        return '';
    }
} // formato_entero

/**
 * Formato flotante
 *
 * @param  mixed   Entero o flotante
 * @param  integer Opcional. Cantidad de decimales, por defecto cuatro
 * @return string  Texto de la cantidad con formato. Entrega texto vacío si falla al validar.
 */
function formato_flotante($cantidad, $decimales=4) {
    if (validar_entero($cantidad) || validar_flotante($cantidad)) {
        return number_format($cantidad, $decimales, ".", ",");
    } else {
        return '';
    }
} // formato_flotante

/**
 * Formato porcentaje
 *
 * @param  mixed   Entero o flotante
 * @param  integer Opcional. Cantidad de decimales, por defecto dos
 * @return string  Texto de la cantidad con formato. Entrega texto vacío si falla al validar.
 */
function formato_porcentaje($cantidad, $decimales=2) {
    if (validar_entero($cantidad) || validar_flotante($cantidad)) {
        return number_format($cantidad, $decimales, ".", ",")." %";
    } else {
        return '';
    }
} // formato_porcentaje

/**
 * Formato dinero
 *
 * @param  mixed   Entero o flotante
 * @return string  Texto de la cantidad con formato. Entrega texto vacío si falla al validar
 */
function formato_dinero($cantidad) {
    $entrega = formato_flotante($cantidad, 2);
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
function formato_fecha($in_fecha) {
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
function formato_fecha_hora($in_fecha_hora, $in_separador='') {
    $t = strtotime($in_fecha_hora);
    if ($t === false) {
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
 * Formato Arreglo PHP
 *
 * @param  string Texto separado por comas
 * @return string Texto como array de PHP, por ejemplo array('Animales', 'Aves', 'Migratorias')
 */
function formato_arreglo_php($in_texto_separado_comas) {
    $a = array();
    foreach (explode(',', $in_texto_separado_comas) as $c) {
        $a[] = "'".ucwords(strtolower(trim($c)))."'";
    }
    return 'array('.implode(', ', $a).')';
} // formato_arreglo_php

/**
 * Rellenar con espacios, funciona con Unicode. Tomado de https://stackoverflow.com/questions/14773072/php-str-pad-unicode-issue
 *
 * @param  string  Texto a mostrar
 * @param  integer Cantidad de caracteres
 * @param  string  Texto para rellenar espacio
 * @return mixed   Constante de dirección: STR_PAD_LEFT, STR_PAD_BOTH o STR_PAD_RIGHT
 */
function str_pad_unicode($str, $pad_len, $pad_str=' ', $dir=STR_PAD_RIGHT) {
    $str_len     = mb_strlen($str);
    $pad_str_len = mb_strlen($pad_str);
    if (!$str_len && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
        $str_len = 1; // @debug
    }
    if (!$pad_len || !$pad_str_len) {
        return $str;
    }
    if ($pad_len <= $str_len) {
        return mb_substr($str, 0, $pad_len);
    }
    $result = null;
    if ($dir == STR_PAD_BOTH) {
        $length = ($pad_len - $str_len) / 2;
        $repeat = ceil($length / $pad_str_len);
        $result = mb_substr(str_repeat($pad_str, $repeat), 0, floor($length)) . $str . mb_substr(str_repeat($pad_str, $repeat), 0, ceil($length));
    } else {
        $repeat = ceil($str_len - $pad_str_len + $pad_len);
        if ($dir == STR_PAD_RIGHT) {
            $result = $str . str_repeat($pad_str, $repeat);
            $result = mb_substr($result, 0, $pad_len);
        } else if ($dir == STR_PAD_LEFT) {
            $result = str_repeat($pad_str, $repeat);
            $result = mb_substr($result, 0, $pad_len - (($str_len - $pad_str_len) + $pad_str_len)) . $str;
        }
    }
    return $result;
} // str_pad_unicode

?>
