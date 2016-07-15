<?php
/**
 * GenesisPHP - CollapseWeb
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
 * Clase CollapseWeb
 */
class CollapseWeb implements SalidaWeb {

    public $encabezado;                // Opcional, texto del encabezado
    public $icono;                     // Opcional, url al icono
    public $hay_resultados  = false;   // Verdadero si la busqueda arrojo resultados, se usa en la pagina
    public $hay_mensaje     = false;   // Verdarero si hay mensaje de error o validacion, se usa en la pagina para activar la lengüeta
    protected $collapse_id;            // Identificador único de este collapse
    protected $secciones    = array(); // Secciones es un arreglo asociativo con las etiquetas y contenidos
    protected $activa;                 // Clave de la seccion activa
    protected $javascript   = array(); // Arreglo, javascript a colocar al final de la pagina

    /**
     * Constructor
     *
     * @param string Identificador único de este Collapse
     */
    public function __construct($in_collapse_id) {
        $this->collapse_id = $in_collapse_id;
    } // constructor

    /**
     * Agregar
     *
     * @param string Texto identificador único
     * @param string Etiqueta, será el encabezado de la ventana y la etiqueta del botón
     * @param string Contenido, haga la pregunta al usuario de forma amable
     */
    public function agregar($in_identificador, $in_etiqueta, $in_contenido) {
        // Validar
        if (!is_string($in_identificador) || (trim($in_identificador) == '')) {
            return;
        } else {
            $identificador = $in_identificador;
        }
        if (!is_string($in_etiqueta) || (trim($in_etiqueta) == '')) {
            $etiqueta = 'SIN ETIQUETA';
        } else {
            $etiqueta = $in_etiqueta;
        }
        if (!is_string($in_contenido) || (trim($in_contenido) == '')) {
            $contenido = 'SIN CONTENIDO';
        } else {
            $contenido = $in_contenido;
        }
        // Agregar seccion
        $this->secciones[$in_identificador] = array(
            'etiqueta'  => $etiqueta,
            'contenido' => $contenido);
        // Si es la primera, es la activa por defecto
        if ($this->activa == '') {
            $this->activa = $identificador;
        }
    } // agregar

    /**
     * Agregar Javascript
     *
     * @param string Código Javascript
     */
    public function agregar_javascript($in_js) {
        // Si el javascript es texto, se agrega al arreglo
        if (is_string($in_js) && ($in_js != '')) {
            // Es texto
            $this->javascript[] = $in_js;
        } elseif (is_array($in_js) && (count($in_js) > 1)) {
            // Es un arreglo, revisar que cada dato sea un texto valido
            foreach ($in_js as $js) {
                if (is_string($js) && ($js != '')) {
                    $this->javascript[] = $js;
                }
            }
        }
    } // agregar_javascript

    /**
     * Definir activa
     *
     * @param string Identificador de la sección activa, si no se dá se toma la última agregada
     */
    public function definir_activa($in_identificador='') {
        if ($in_identificador === '') {
            // No hay clave, se toma la ultima que se haya agregado
            $identificadores = array_keys($this->secciones);
            $this->activa    = end($identificadores);
        } elseif (is_string($in_identificador) && array_key_exists($in_identificador, $this->secciones)) {
            // La clave dada si ha sido agregada
            $this->activa = $in_identificador;
        }
    } // definir_activa

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string Código HTML
     */
    public function html($in_encabezado='') {
        // Si viene el encabezado como parámetro
        if ($in_encabezado != '') {
            $this->encabezado = $in_encabezado;
        }
        // Validar que se hayan agregado secciones
        if (count($this->secciones) == 0) {
            $mensaje = new MensajeWeb('Error: No se agregaron secciones con contenido para mostrar.');
            return $mensaje->html('Collapse');
        }
        // Acumularemos el html en este arreglo
        $a = array();
        // Elaborar Barra
        if (is_object($this->barra) && ($this->barra instanceof BarraWeb)) {
            $a[]                = $this->barra->html();
            $this->javascript[] = $this->barra->javascript();
        } elseif ($this->encabezado != '') {
            $this->barra             = new BarraWeb();
            $this->barra->encabezado = $this->encabezado;
            $this->barra->icono      = $this->icono;
            $a[]                     = $this->barra->html();
            $this->javascript[]      = $this->barra->javascript();
        }
        // Elaborar contenido
        $a[] = "<div class=\"panel-group\" id=\"{$this->collapse_id}\">";
        foreach ($this->secciones as $identificador => $seccion) {
            $a[] = '  <div class="panel panel-default">';
            $a[] = '    <div class="panel-heading">';
            $a[] = '      <h4 class="panel-title">';
            $a[] = "        <a data-toggle=\"collapse\" data-parent=\"#{$this->collapse_id}\" href=\"#{$identificador}\">{$seccion['etiqueta']}</a>";
            $a[] = '      </h4>';
            $a[] = '    </div>';
            if ($identificador == $this->activa) {
                $a[] = "    <div id=\"{$identificador}\" class=\"panel-collapse collapse in\">";
            } else {
                $a[] = "    <div id=\"{$identificador}\" class=\"panel-collapse collapse\">";
            }
            $a[] = '        <div class="panel-body">';
            $a[] = $seccion['contenido'];
            $a[] = '        </div>';
            $a[] = '    </div>';
            $a[] = '  </div>';
        }
        $a[] = '</div>';
        // Entregar
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Código Javascript
     */
    public function javascript() {
        if (is_array($this->javascript) && (count($this->javascript) > 0)) {
            $a = array();
            foreach ($this->javascript as $js) {
                if (is_string($js) && ($js != '')) {
                    $a[] = $js;
                }
            }
            if (count($a) > 0) {
                return implode("\n", $a);
            } else {
                return '';
            }
        } else {
            return '';
        }
    } // javascript

} // Clase CollapseWeb

?>
