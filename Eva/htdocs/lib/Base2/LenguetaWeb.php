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
class LenguetaWeb implements SalidaWeb {

    protected $identificador;       // Texto único que lo identifica
    protected $padre_identificador; // Texto único que identica al padre
    protected $etiqueta;            // Texto que va a aparecer en la lengüeta
    protected $contenido;           // Instancia con el contenido, debe implementar SalidaWeb
    public $es_activa = FALSE;      // Booleano, verdadero si es la lengüeta activa

    /**
     * Constructor
     *
     * @param string Texto único que identica al padre
     * @param string Texto que va a aparecer en la lengüeta
     * @param mixed  Instancia con el contenido, debe implementar SalidaWeb
     */
    public function __construct($padre_identificador, $etiqueta, $contenido) {
        $this->padre_identificador = $padre_identificador;
        $this->etiqueta            = $etiqueta;
        $this->identificador       = $this->padre_identificador.UtileriasParaFormatos::caracteres_para_clase($this->etiqueta);
        $this->contenido           = $contenido;
    } // constructor

    /**
     * Definir como lengüeta activa
     */
    public function definir_activa() {
        $this->es_activa = TRUE;
    } // definir_activa

    /**
     * Definir como lengüeta inactiva
     */
    public function definir_inactiva() {
        $this->es_activa = FALSE;
    } // definir_inactiva

    /**
     * Validar
     */
    protected function validar() {
        if ($this->padre_identificador == NULL) {
            throw new \Exception("Error en LenguetaWeb: Falta el padre identificador.");
        }
        if ($this->identificador == NULL) {
            throw new \Exception("Error en LenguetaWeb: Falta el identificador.");
        }
        if ($this->etiqueta == NULL) {
            throw new \Exception("Error en LenguetaWeb: Falta la etiqueta.");
        }
        if ($this->contenido == NULL) {
            throw new \Exception("Error en LenguetaWeb: Falta el contenido.");
        }
        if (!($this->contenido instanceof SalidaWeb)) {
            throw new \Exception("Error en LenguetaWeb: Contenido en {$this->identificador} no implementa SalidaWeb.");
        }
        if (!is_bool($this->es_activa)) {
            throw new \Exception("Error en LenguetaWeb: Bandera es_activa NO es boleano.");
        }
    } // validar

    /**
     * Obtener identificador
     *
     * @return string Identificador
     */
    public function obtener_identificador() {
        $this->validar();
        return $this->identificador;
    } // obtener_identificador

    /**
     * Pestaña HTML
     *
     * @return string HTML
     */
    public function pestana_html() {
        $this->validar();
        if ($this->es_activa) {
            return "    <li role=\"presentation\" class=\"active\"><a href=\"#{$this->identificador}\" aria-controls=\"{$this->identificador}\" role=\"tab\" data-toggle=\"tab\">{$this->etiqueta}</a></li>";
        } else {
            return "    <li role=\"presentation\"><a href=\"#{$this->identificador}\" aria-controls=\"{$this->identificador}\" role=\"tab\" data-toggle=\"tab\">{$this->etiqueta}</a></li>";
        }
    } // pestana_html

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        $this->validar();
        // Acumular
        $a = array();
        if ($this->es_activa) {
            $a[] = "  <div role=\"tabpanel\" class=\"tab-pane active\" id=\"{$this->identificador}\">";
        } else {
            $a[] = "  <div role=\"tabpanel\" class=\"tab-pane\" id=\"{$this->identificador}\">";
        }
        $a[] = $this->contenido->html();
        $a[] = "  </div>";
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
        // Acumular
        $a = array();
        if ($this->es_activa) {
            $js = $this->contenido->javascript();
            if (is_string($js) && !empty(trim($js))) {
                $a[] = "  // LenguetaWeb {$this->identificador}";
                $a[] = $js;
            }
        } else {
            $js = $this->contenido->javascript();
            if (is_string($js) && !empty(trim($js))) {
                $a[] = "  // LenguetaWeb {$this->identificador} ejecuta lo siguiente al mostrar";
                $a[] = "  $('#{$this->padre_identificador} a[href=\"#{$this->identificador}\"]').on('shown.bs.tab', function(e){";
                $a[] = $js;
                $a[] = "  })";
            }
        }
        // Entregar
        return implode("\n", $a);
    } // javascript

} // Clase LenguetaWeb

?>
