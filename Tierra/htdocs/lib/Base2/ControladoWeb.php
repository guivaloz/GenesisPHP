<?php
/**
 * GenesisPHP - ControladoWeb
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
 * Clase ControladoWeb
 */
class ControladoWeb implements SalidaWeb {

    public $limit;                 // Entero, cantidad de renglones
    public $offset;                // Entero, renglón en el que inicia el listado presente
    public $cantidad_registros;    // Entero, cantidad total de registros de la consulta
    public $variables = array();   // Arreglo asociativo, variables adicionales para los vínculos
    public $viene_listado = false; // Se usa en la página, si es verdadero debe mostrar el listado
    static public $limit_por_defecto        = 10;
    static public $pagina_url;
    static public $param_limit              = 'li';
    static public $param_offset             = 'of';
    static public $param_cantidad_registros = 'cr';

    /**
     * Constructor
     */
    public function __construct() {
        // Parametros que se reciben por el url
        // Limit
        if ($_GET[self::$param_limit] != '') {
            $this->limit         = $_GET[self::$param_limit];
            $this->viene_listado = true;
        } else {
            $this->limit = self::$limit_por_defecto;
        }
        // Offset
        if ($_GET[self::$param_offset] != '') {
            $this->offset        = $_GET[self::$param_offset];
            $this->viene_listado = true;
        } else {
            $this->offset = 0;
        }
        // Cantidad de registros
        if ($_GET[self::$param_cantidad_registros] != '') {
            $this->cantidad_registros = $_GET[self::$param_cantidad_registros];
        } else {
            $this->cantidad_registros = 0;
        }
    } // constructor

    /**
     * URL Anterior
     *
     * @return string URL anterior
     */
    protected function url_anterior() {
        // En este arreglo juntamos los parametros
        $a = array();
        // Limit
        if (($this->limit != self::$limit_por_defecto) && ($this->limit > 0) && ($this->offset > 0)) {
            $a[] = self::$param_limit.'='.$this->limit;
        }
        // Offset, para el anterior restamos (offset menos limit)
        if (($this->limit > 0) && ($this->offset > 0)) {
            $n = $this->offset - $this->limit;
            if ($n >= 0) {
                $a[] = self::$param_offset.'='.$n;
                if ($this->cantidad_registros > 0) {
                    $a[] = self::$param_cantidad_registros.'='.$this->cantidad_registros;
                }
            }
        }
        // Si hay algo
        if (count($a) > 0) {
            // Si hay variables, se agregan
            if (count($this->variables) > 0) {
                foreach ($this->variables as $var => $valor) {
                    if ($valor != '') {
                        $a[] = $var.'='.urlencode($valor);
                    }
                }
            }
            // Juntar con la página
            $url = self::$pagina_url;
            if (strpos($url, '?') === false) {
                $url .= '?'.implode('&', $a);
            } else {
                $url .= '&'.implode('&', $a);
            }
            // Entregar URL
            return $url;
        } else {
            return '';
        }
        // Entregar
        return $html;
    } // url_anterior

    /**
     * URL Siguiente
     *
     * @return string URL siguiente
     */
    protected function url_siguiente() {
        // Si no hay limit, no hay URL
        if ($this->limit == 0) {
            return '';
        }
        // Calculamos el offset para el botón siguiente
        $offset_siguiente = $this->limit + $this->offset;
        // Si hay cantidad de registros y el offset siguiente sobrepasa esta cantidad, no hay URL
        if (($this->cantidad_registros > 0) && ($offset_siguiente >= $this->cantidad_registros)) {
            return '';
        }
        // En este arreglo juntamos los parámetros
        $a = array();
        // Si el limit es distinto al valor por defecto, se usará
        if ($this->limit != self::$limit_por_defecto) {
            $a[] = self::$param_limit.'='.$this->limit;
        }
        // Parámetro offset
        $a[] = self::$param_offset.'='.$offset_siguiente;
        // Si hay cantidad de registros, se usará
        if ($this->cantidad_registros > 0) {
            $a[] = self::$param_cantidad_registros.'='.$this->cantidad_registros;
        }
        // Agregamos las variables extras
        if (count($this->variables) > 0) {
            foreach ($this->variables as $var => $valor) {
                if ($valor != '') {
                    $a[] = $var.'='.urlencode($valor);
                }
            }
        }
        // Juntamos el URL de la pagina con todos los parámetros
        $url = self::$pagina_url;
        if (strpos($url, '?') === false) {
            $url .= '?'.implode('&', $a);
        } else {
            $url .= '&'.implode('&', $a);
        }
        // Entregar
        return $url;
    } // url_siguiente

    /**
     * Botón anterior
     *
     * @return string HTML con el botón
     */
    protected function boton_anterior() {
        $url = $this->url_anterior();
        if ($url != '') {
            return sprintf('<button type="button" class="btn btn-default" onClick="location.href=\'%s\'">Anterior</button>', $url);
        } else {
            return '';
        }
    } // boton_anterior

    /**
     * Botón siguiente
     *
     * @return string HTML con el botón
     */
    protected function boton_siguiente() {
        $url = $this->url_siguiente();
        if ($url != '') {
            return sprintf('<button type="button" class="btn btn-default pull-right" onClick="location.href=\'%s\'">Siguiente</button>', $url);
        } else {
            return '';
        }
    } // boton_anterior

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        return sprintf('%s %s', $this->boton_anterior(), $this->boton_siguiente());
    } // html

    /**
     * Javascript
     *
     * @return string Código Javascript
     */
    public function javascript() {
        return false;
    } // javascript

} // Clase ControladoWeb

?>
