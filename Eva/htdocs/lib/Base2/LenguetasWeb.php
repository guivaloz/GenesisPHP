<?php
/**
 * GenesisPHP - LenguetasWeb
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
 * Clase LenguetasWeb
 */
class LenguetasWeb implements SalidaWeb {

    protected $identificador;       // Texto único que lo identifica
    protected $elementos = array(); // Arreglo asociativo con instancias de LenguetaWeb
    protected $elemento_activo;     // Etiqueta de la lengüeta activa

    /**
     * Constructor
     *
     * @param string Texto único que lo identifica
     */
    public function __construct($identificador) {
        $this->identificador = $identificador;
    } // constructor

    /**
     * Agregar
     *
     * @param string  Texto que va a aparecer en la etiqueta
     * @param mixed   Instancia con el contenido, debe implementar SalidaWeb
     * @param boolean Opcional, Verdadero si es la pestaña activa, falso si no
     */
    public function agregar($etiqueta, $contenido, $es_activa=FALSE) {
        $this->elementos[$etiqueta] = new LenguetaWeb($this->identificador, $etiqueta, $contenido);
        if ($es_activa) {
            $this->elemento_activo = $etiqueta; // Conserva sólo la última lengüeta agregada como activa
        }
    } // agregar

    /**
     * Validar
     */
    protected function validar() {
        // Validar
        if ($this->identificador == NULL) {
            throw new \Exception("Error en LenguetasWeb: Falta el identificador.");
        }
        if (!is_array($this->elementos) || (count($this->elementos) == 0)) {
            throw new \Exception("Error en LenguetasWeb: No tiene elementos.");
        }
        // Si NO está definida la lengüeta activa, entonces la primera lo será
        if ($this->elemento_activo == NULL) {
            $etiquetas             = array_keys($this->elementos);
            $this->elemento_activo = $etiquetas[0];
        }
        // Asegurarse que sólo debe haber una lengüeta activa
        foreach ($this->elementos as $etiqueta => $elemento) {
            if ($etiqueta == $this->elemento_activo) {
                $elemento->definir_activa();
            } else {
                $elemento->definir_inactiva();
            }
        }
    } // validar

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        $this->validar();
        // Acumular
        $a   = array();
        $a[] = "<div>";
        $a[] = "  <ul class=\"nav nav-tabs lenguetas\" role=\"tablist\" id=\"{$this->identificador}\">";
        foreach ($this->elementos as $elemento) {
            $a[] = $elemento->pestana_html();
        }
        $a[] = "  </ul>";
        $a[] = "  <div class=\"tab-content lengueta-contenido\">";
        foreach ($this->elementos as $elemento) {
            $a[] = $elemento->html();
        }
        $a[] = "  </div>";
        $a[] = "</div>";
        // Entregar
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        $this->validar();
        // Obtener el identificador del elemento activo
        $identificador = $this->elementos[$this->elemento_activo]->obtener_identificador();
        // Acumular
        $a = array();
        foreach ($this->elementos as $elemento) {
            $js = $elemento->javascript();
            if (is_string($js) && !empty(trim($js))) {
                $a[] = $js;
            }
        }
        // Acumular javascript propio de Twitter Bootstrap
        $a[] = "  // LenguetasWeb {$this->identificador} ordenar que {$this->elemento_activo} es la que se mostrará";
        $a[] = "  $(document).ready(function(){";
        $a[] = "    $('#{$this->identificador} a[href=\"#{$identificador}\"]').tab('show')";
        $a[] = "  });";
        // Entregar
        return implode("\n", $a);
    } // javascript

} // Clase LenguetasWeb

?>
