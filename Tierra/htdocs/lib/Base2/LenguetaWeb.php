<?php
/**
 * GenesisPHP - LenguetaWeb
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
 * Clase LenguetaWeb
 */
class LenguetaWeb {

    public $clave;                      // Texto que identifica a la lengüeta
    public $padre_identificador;        // Texto, es el identificador único del juego de lengüetas, desde LenguetasWeb se define
    public $etiqueta;                   // Texto que va a aparecer en la lengüeta
    public $contenido;                  // Mixto, puede ser texto, un objeto o un arreglo de objetos
    public $javascript;                 // Texto, Javascript
    public $es_activa        = false;   // Booleano, verdadero si es la lengüeta activa
    protected $digerido_di   = array(); // Si contenido es o son objetos, acumularemos los identificadores
    protected $digerido_html = array(); // Si contenido es o son objetos, acumularemos el HTML
    protected $digerido_js   = array(); // Si contenido es o son objetos, acumularemos el Javascript
    protected $he_digerido   = false;   // Bandera

    /**
     * Constructor
     *
     * @param string Clave
     * @param string Etiqueta
     * @param mixed  Opcional, contenido como texto, objeto o arreglo de objetos
     * @param string Opcional, Javascript
     */
    public function __construct($in_clave, $in_etiqueta, $in_contenido='', $in_javascript='') {
        $this->clave      = $in_clave;
        $this->etiqueta   = $in_etiqueta;
        $this->contenido  = $in_contenido;
        $this->javascript = $in_js;
    } // constructor

    /**
     * Digerir
     */
    protected function digerir() {
        // Si ya ha digerido, no hace nada
        if ($this->he_digerido) {
            return;
        }
        // Si es un arreglo
        if (is_array($this->contenido) && (count($this->contenido) > 0)) {
            // Es un arreglo de instancias
            foreach ($this->contenido as $item) {
                if (is_object($item)) {
                    if (method_exists($item, 'html')) {
                        $this->digerido_html[] = $item->html();
                    }
                    if (method_exists($item, 'javascript')) {
                        $this->digerido_js[]   = $item->javascript();
                    }
                } elseif (is_string($item)) {
                    $this->digerido_html[] = $item;
                }
                if ($item->identificador != '') {
                    $this->digerido_di[]   = $item->identificador;
                }
            }
        } elseif (is_object($this->contenido)) {
            // Es una instancia
            if (method_exists($this->contenido, 'html')) {
                $this->digerido_html[] = $this->contenido->html();
            }
            if (method_exists($this->contenido, 'javascript')) {
                $this->digerido_js[]   = $this->contenido->javascript();
            }
            if ($this->contenido->identificador != '') {
                $this->digerido_di[]   = $this->contenido->identificador;
            }
        } elseif (is_string($this->contenido)) {
            $this->digerido_html[] = $this->contenido;
        }
        // Cambiar bandera
        $this->he_digerido = true;
    } // digerir

    /**
     * Pestaña HTML
     *
     * @return string HTML
     */
    public function pestana_html() {
        // Digerir
        $this->digerir();
        // Validar clave
        if (!is_string($this->clave) || ($this->clave == '')) {
            throw new \Exception("Error en LenguetaWeb: La clave es incorrecta.");
        }
        // Validar etiqueta
        if (!is_string($this->etiqueta) || ($this->etiqueta == '')) {
            throw new \Exception("Error en LenguetaWeb: La etiqueta es incorrecta.");
        }
        // Inicia pestaña (no se usa class="active" por que es el Javascript en LenguetasHTML quien la activa)
        $li_tag = '<li>';
        // Si hay identificadores
        if (count($this->digerido_di) > 0) {
            $data_identifier = sprintf(' data-identifier="%s"', implode(',', $this->digerido_di));
        } else {
            $data_identifier = '';
        }
        // Entregar
        return "    $li_tag<a href=\"#{$this->clave}\" data-toggle=\"tab\"{$data_identifier}>{$this->etiqueta}</a></li>";
    } // pestana_html

    /**
     * Interior HTML
     *
     * @return string HTML
     */
    public function interior_html() {
        // Digerir
        $this->digerir();
        // En este arreglo acumularemos la entrega
        $a = array();
        // Inicia div
        $a[] = sprintf('    <div class="tab-pane" id="%s">', $this->clave);
        // Si hay contenido digerido, contenido o nada
        if (count($this->digerido_html) > 0) {
            $a[] = '      '.implode("\n      ", $this->digerido_html);
        } elseif (is_string($this->contenido) && ($this->contenido != '')) {
            $a[] = $this->contenido;
        } else {
            $a[] = '      <p><b>Aviso:</b> Esta lengüeta NO tiene contenido.</p>';
        }
        $a[] = '    </div>';
        // Entregar
        return implode("\n", $a);
    } // interior_html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        // Acumularemos el javascript en este arreglo
        $a = array();
        // Si hay Javascript en el arreglo digerido
        foreach ($this->digerido_js as $js) {
            if ($js !== false) {
                $a[] = $js;
            }
        }
        // Si hay Javascript
        if (is_string($this->javascript) && ($this->javascript != '')) {
            $a[] = $this->javascript;
        }
        // Entregar
        if (count($a) > 0) {
            $todo = implode("\n", $a);
            return <<<FINAL
// LENGUETA {$this->clave}
$('#{$this->padre_identificador} a[href="#{$this->clave}"]').on('shown.bs.tab', function(e){
$todo
});
FINAL;
        } else {
            return '';
        }
    } // javascript

} // Clase LenguetaWeb

?>
