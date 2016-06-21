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

    public $identificador;             // Texto identificador a cada grupo de lengüetas
    public $activa          = false;   // Texto, clave de la lengüeta activa
    protected $lenguetas    = array(); // Arreglo con los contenidos
    protected $clave_ultima = false;   // Última clave agregada, le sirve a agregar_javascript

    /**
     * Constructor
     *
     * @param string Opcional, identificador único para este juego de lengüetas en la página web, por defecto caracteres al azar
     */
    public function __construct($in_id=false) {
        if (is_string($in_id) && (trim($in_id) != '')) {
            $this->identificador = $in_id;
        } else {
            $this->identificador = "lenguetas-".strtoupper($this->caracteres_azar());
        }
    } // constructor

    /**
     * Caracteres al azar
     *
     * @param  integer Cantidad de caracteres, por defecto 8
     * @return string  Caracteres al azar
     */
    protected function caracteres_azar($in_cantidad=8) {
        $primera = ord('a');
        $ultima  = ord('z');
        $c       = array();
        for ($i=0; $i<$in_cantidad; $i++) {
            $c[] = chr(rand($primera, $ultima));
        }
        return implode('', $c);
    } // caracteres_azar

    /**
     * Agregar
     *
     * @param string Clave. Si contiene el texto 'Mapa' se agregará javascript para tal.
     * @param string Etiqueta
     * @param mixed  Contenido, puede ser texto HTML, una instancia con métodos html y javascript o un arreglo de instancias
     */
    public function agregar($in_clave, $in_etiqueta, $in_contenido) {
        // Definir la última clave como la dada
        $this->clave_ultima = $in_clave;
        // Agregar la lengüeta
        $this->lenguetas[$in_clave] = new LenguetaWeb($in_clave, $in_etiqueta, $in_contenido);
        // Se pasa el identificador a la lengüeta
        $this->lenguetas[$in_clave]->padre_identificador = $this->identificador;
    } // agregar

    /**
     * Agregar Javascript
     *
     * @param string Javascript
     */
    public function agregar_javascript($in_js) {
        // A la última lengüeta se le pasa el Javascript
        if (is_string($in_js) && ($in_js != '')) {
            $this->lenguetas[$this->clave_ultima]->js = $in_js;
        }
    } // agregar_javascript

    /**
     * Definir activa
     *
     * @param string Clave de la lengüeta activa. Si es nulo, se establece como la última lengüeta agregada.
     */
    public function definir_activa($in_clave=false) {
        // Si no se dio clave
        if (($in_clave === false) && ($this->clave_ultima !== false)) {
            // Se toma la ultima que se haya agregado
            $this->activa = $this->clave_ultima;
        } elseif (is_string($in_clave) && array_key_exists($in_clave, $this->lenguetas)) {
            // Se cambia sólo si ha sido agregada
            $this->activa = $in_clave;
        } else {
            return;
        }
        // Luego poner todas las lengüetas como inactivas
        foreach ($this->lenguetas as $lengueta) {
            $lengueta->es_activa = false;
        }
        // Después poner como activa a la lengüeta que le corresponde
        $this->lenguetas[$this->activa]->es_activa = true;
    } // definir_activa

    /**
     * Agregar activa
     */
    public function agregar_activa($in_clave, $in_etiqueta, $in_contenido) {
        $this->agregar($in_clave, $in_etiqueta, $in_contenido);
        $this->definir_activa();
    } // agregar_activa

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Si no hay lengüetas, no entrega nada
        if (count($this->lenguetas) == 0) {
            return '';
        }
        // Si viene en el URL accion y es una lengüeta, se define como activa
        if (($_GET['accion'] != '') && array_key_exists($_GET['accion'], $this->lenguetas)) {
            $this->definir_activa($_GET['accion']);
        }
        // Si no hay lengüeta activa, se define como activa la primera
        if ($this->activa === false) {
            $claves       = array_keys($this->lenguetas);
            $this->definir_activa($claves[0]);
        }
        // En este arreglo juntaremos el HTML
        $a = array();
        // Acumular pestañas
        $a[] = "  <ul class=\"nav nav-tabs lenguetas\" id=\"{$this->identificador}\">";
        foreach ($this->lenguetas as $lengueta) {
            $a[] = $lengueta->pestana_html();
        }
        $a[] = '  </ul>';
        // Acumular interiores
        $a[] = '  <div class="tab-content lengueta-contenido">';
        foreach ($this->lenguetas as $lengueta) {
            $a[] = $lengueta->interior_html();
        }
        $a[] = '  </div>';
        // Entregar
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * Entregar el javascript. Si no lo hay, entrega falso.
     *
     * @return string Javascript
     */
    public function javascript() {
        // Si no hay lengüetas, no entrega nada
        if (count($this->lenguetas) == 0) {
            return false;
        }
        // En este arreglo juntaremos el javascript
        $a = array();
        // Bucle por las lengüetas
        foreach ($this->lenguetas as $lengueta) {
            // Acumular el Javascript que viene en cada lengüeta
            $js = $lengueta->javascript();
            if (is_string($js) && ($js != '')) {
                $a[] = $js;
            } elseif (is_array($js) && (count($js) > 0)) {
                foreach ($js as $j) {
                    if ($j != '') {
                        $a[] = $j;
                    }
                }
            }
        }
        // Javascript de Twitter Bootstrap Tabs
        if ($this->activa === false) {
            $a[] = <<<FINAL
// TWITTER BOOTSTRAP TABS, ESTABLECER QUE LA PRIMER LENGÜETA ES LA ACTIVA
$(document).ready(function(){
  $('#{$this->identificador} a:first').tab('show')
});
FINAL;
        } else {
            $a[] = <<<FINAL
// TWITTER BOOTSTRAP TABS, ESTABLECER QUE LA LENGÜETA ACTIVA ES {$this->activa}
$(document).ready(function(){
  $('#{$this->identificador} a[href="#{$this->activa}"]').tab('show')
});
FINAL;
        }
        // Entregar
        return implode("\n", $a);
    } // javascript

} // Clase LenguetasWeb

?>
