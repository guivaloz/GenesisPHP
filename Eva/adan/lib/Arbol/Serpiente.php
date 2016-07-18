<?php
/**
 * GenesisPHP - Árbol Serpiente
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

namespace Arbol;

/**
 * Clase Serpiente
 */
class Serpiente {

    /**
     * Un valor de reptil es contenido, para validar se enlistan aquí los permitidos
     */
    static public $contenido_validos = array('datos', 'imagenes', 'impresiones', 'gráficas');

    /**
     * Nombre del sistema
     */
    protected $sistema_nombre;

    /**
     * Siglas del sistema que se usan para el package
     */
    protected $sistema_siglas;

    /**
     * Reptil es un arreglo asociativo con los datos de cada módulo
     *
     * Estos parametros estan siendo usados...
     * 'etiqueta_singular'  => Texto descriptivo singular para el título
     * 'etiqueta_plural'    => Texto descriptivo plural para el título
     * 'nom_corto_singular' => nombre en singular y en minúsculas
     * 'nom_corto_plural'   => nombre en plural y en minúsculas
     * 'mensaje_singular'   => Pronombre y sustantivo en singular para los mensajes
     * 'mensaje_plural'     => Pronombre y sustantivo en plural para los mensajes
     * 'clave'              => Clave del módulo que le corresponde en la tabla de módulos
     * 'clase_singular'     => Nombre de la clase en singular
     * 'clase_plural'       => Nombre de la clase en plural
     * 'instancia_singular' => Nombre de la clase en singular
     * 'instancia_plural'   => Nombre de la clase en plural
     * 'archivo_singular'   => Nombre del archivo en singular
     * 'archivo_plural'     => Nombre del archivo en plural
     * 'tabla'              => Nombre de la tabla en la base de datos, puede que consulte una tabla distinta a la clave
     * 'vip'                => Información de las columnas más importantes de esta tabla para usar en otros módulos
     * 'listados'           => Opcional, si vale 'trenes' usará TrenHTML en lugar de ListadoHTML, úselo en módulos de imágenes
     * 'contenido'          => Opcional, por defecto 'datos', use 'impresiones' para poner botón para imprimir
     */
    protected $reptil;

    /**
     * Obtener Instancia Singular
     *
     * Es el nombre de la variable para una instancia singular
     *
     * @param  string Nombre del módulo
     * @return string Nombre de la instancia en singular
     */
    public function obtener_instancia_singular($in_modulo) {
        if (is_array($this->reptil) && ($this->reptil[$in_modulo]['instancia_singular'] != '')) {
            return $this->reptil[$in_modulo]['instancia_singular'];
        } else {
            die("ERROR en Serpiente: No existe instancia_singular para $in_modulo");
        }
    } // obtener_instancia_singular

    /**
     * Obtener estatus
     *
     * @param  string   Nombre del módulo
     * @return caracter Arreglo asociativo
     */
    public function obtener_estatus($in_modulo) {
        if (is_bool($this->reptil[$in_modulo]['estatus'])) {
            // Entregar booleano
            return $this->reptil[$in_modulo]['estatus'];
        } elseif (is_array($this->reptil[$in_modulo]['estatus'])) {
            // Entregar arreglo
            return $this->reptil[$in_modulo]['estatus'];
        } else {
            // Por defecto, el estatus es A o B
            return array('enuso' => 'A', 'eliminado' => 'B');
        }
    } // obtener_estatus

    /**
     * Obtener Datos del Módulo
     *
     * Para alimentar las propiedades relaciones, hijos y padre
     *
     * @param  string Nombre del módulo
     * @return array  Arreglo asociativo con los datos del módulo
     */
    public function obtener_datos_del_modulo($in_modulo) {
        // Validar que se haya programado los datos para ese modulo
        if (is_array($this->reptil[$in_modulo]) && (count($this->reptil[$in_modulo]) > 0)) {
            // Estatus es opcional, si no esta se le asigna los valores por defecto
            if (!isset($this->reptil[$in_modulo]['estatus'])) {
                $this->reptil[$in_modulo]['estatus'] = array('enuso' => 'A', 'eliminado' => 'B');
            }
            // Contenido es opcional, por defecto es 'datos', debe ser uno de contenido_validos
            if (!isset($this->reptil[$in_modulo]['contenido'])) {
                $this->reptil[$in_modulo]['contenido'] = 'datos';
            } elseif (!is_string($this->reptil[$in_modulo]['contenido']) || !in_array($this->reptil[$in_modulo]['contenido'], self::$contenido_validos)) {
                die("ERROR en Serpiente: El valor de 'contenido' es incorrecto.");
            }
            // Entregar
            return $this->reptil[$in_modulo];
        } else {
            die("ERROR en Serpiente: No existen datos para $in_modulo");
        }
    } // obtener_datos_del_modulo

    /**
     * Obtener Sustituciones
     *
     * Crea el arreglo asociativo que debe cargarse en la propiedad sustituciones
     *
     * @param  string Nombre del módulo
     * @return array  Arreglo asociativo con los datos
     */
    public function obtener_sustituciones($in_modulo) {
        if (is_array($this->reptil[$in_modulo]) && (count($this->reptil[$in_modulo]) > 0)) {
            if ($this->sistema_nombre == '') {
                die('ERROR en Serpiente: No está definida la propiedad sistema_nombre');
            }
            if ($this->sistema_siglas == '') {
                die('ERROR en Serpiente: No está definida la propiedad sistema_siglas');
            }
            if ($this->reptil[$in_modulo]['etiqueta_singular'] == '') {
                die('ERROR en Serpiente: No está definida en reptil etiqueta_singular');
            }
            if ($this->reptil[$in_modulo]['etiqueta_plural'] == '') {
                die('ERROR en Serpiente: No está definida en reptil etiqueta_plural');
            }
            if ($this->reptil[$in_modulo]['nom_corto_singular'] == '') {
                die('ERROR en Serpiente: No está definida en reptil nom_corto_singular');
            }
            if ($this->reptil[$in_modulo]['nom_corto_plural'] == '') {
                die('ERROR en Serpiente: No está definida en reptil nom_corto_plural');
            }
            if ($this->reptil[$in_modulo]['clave'] == '') {
                die('ERROR en Serpiente: No está definida en reptil clave');
            }
            if ($this->reptil[$in_modulo]['archivo_singular'] == '') {
                die('ERROR en Serpiente: No está definida en reptil archivo_singular');
            }
            if ($this->reptil[$in_modulo]['archivo_plural'] == '') {
                die('ERROR en Serpiente: No está definida en reptil archivo_plural');
            }
            if ($this->reptil[$in_modulo]['clase_singular'] == '') {
                die('ERROR en Serpiente: No está definida en reptil clase_singular');
            }
            if ($this->reptil[$in_modulo]['clase_plural'] == '') {
                die('ERROR en Serpiente: No está definida en reptil clase_plural');
            }
            if ($this->reptil[$in_modulo]['mensaje_singular'] == '') {
                die('ERROR en Serpiente: No está definida en reptil mensaje_singular');
            }
            if ($this->reptil[$in_modulo]['mensaje_plural'] == '') {
                die('ERROR en Serpiente: No está definida en reptil mensaje_plural');
            }
            return array(
                'SED_SISTEMA'            => $this->sistema_nombre,
                'SED_PAQUETE'            => $this->sistema_siglas,
                'SED_TITULO_SINGULAR'    => $this->reptil[$in_modulo]['etiqueta_singular'],
                'SED_TITULO_PLURAL'      => $this->reptil[$in_modulo]['etiqueta_plural'],
                'SED_SUBTITULO_SINGULAR' => $this->reptil[$in_modulo]['nom_corto_singular'],
                'SED_SUBTITULO_PLURAL'   => $this->reptil[$in_modulo]['nom_corto_plural'],
                'SED_DIRECTORIO'         => $this->reptil[$in_modulo]['clave'],
                'SED_ARCHIVO_SINGULAR'   => $this->reptil[$in_modulo]['archivo_singular'],
                'SED_ARCHIVO_PLURAL'     => $this->reptil[$in_modulo]['archivo_plural'],
                'SED_CLASE_SINGULAR'     => $this->reptil[$in_modulo]['clase_singular'],
                'SED_CLASE_PLURAL'       => $this->reptil[$in_modulo]['clase_plural'],
                'SED_INSTANCIA_SINGULAR' => $this->reptil[$in_modulo]['instancia_singular'],
                'SED_INSTANCIA_PLURAL'   => $this->reptil[$in_modulo]['instancia_plural'],
                'SED_CLAVE'              => $this->reptil[$in_modulo]['clave'],
                'SED_TABLA'              => $this->reptil[$in_modulo]['clave'],
                'SED_MENSAJE_SINGULAR'   => $this->reptil[$in_modulo]['mensaje_singular'],
                'SED_MENSAJE_PLURAL'     => $this->reptil[$in_modulo]['mensaje_plural']
            );
        } else {
            die("ERROR en Serpiente: No existen datos para $in_modulo");
        }
    } // obtener_datos_del_modulo

} // Clase Serpiente

?>
