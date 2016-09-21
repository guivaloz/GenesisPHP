<?php
/**
 * GenesisPHP - AcordeonWeb
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
 * Clase AcordeonWeb
 */
class AcordeonWeb implements SalidaWeb {

    protected $identificador;        // Texto único que lo identifica
    protected $padre_identificador;  // Texto único que identica al padre
    protected $titulo;               // Texto que va a aparecer en la barra
    protected $contenido;            // Instancia con el contenido, debe implementar SalidaWeb
    protected $esta_abierto = FALSE; // Boleano, verdadero para que inicialmente esté abierto

    /**
     * Constructor
     *
     * @param string  Texto único que identifica al padre
     * @param string  Texto que va a aparecer en la barra
     * @param mixed   Instancia con el contenido, debe implementar SalidaWeb
     * @param boolean Boleano, verdadero para que inicialmente esté abierto
     */
    public function __construct($padre_identificador, $titulo, $contenido, $esta_abierto = FALSE) {
        $this->padre_identificador = $padre_identificador;
        $this->titulo              = $titulo;
        $this->identificador       = $this->padre_identificador.UtileriasParaFormatos::caracteres_para_clase($this->titulo);
        $this->contenido           = $contenido;
        $this->esta_abierto        = $esta_abierto;
    } // constructor

    /**
     * Validar
     */
    protected function validar() {
        if ($this->padre_identificador == NULL) {
            throw new \Exception("Error en AcordeonWeb: Falta el padre identificador.");
        }
        if ($this->identificador == NULL) {
            throw new \Exception("Error en AcordeonWeb: Falta el identificador.");
        }
        if ($this->titulo == NULL) {
            throw new \Exception("Error en AcordeonWeb: Falta el título.");
        }
        if ($this->contenido == NULL) {
            throw new \Exception("Error en AcordeonWeb: Falta el contenido.");
        } elseif (!($this->contenido instanceof SalidaWeb)) {
            throw new \Exception("Error en AcordeonWeb: Contenido no implementa SalidaWeb.");
        }
        if (!is_bool($this->esta_abierto)) {
            throw new \Exception("Error en AcordeonWeb: Bandera esta_abierto NO es boleano.");
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
        $a[] = "  <div class=\"panel panel-default\">";
        // Acumular barra
        $a[] = "    <div class=\"panel-heading acordeon-cabecera\" role=\"tab\" id=\"cabecera{$this->identificador}\">";
        $a[] = "      <h4 class=\"panel-title acordeon-titulo\">";
        if ($this->esta_abierto) {
            $a[] = "        <a class=\"acordeon-vinculo\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#{$this->padre_identificador}\" href=\"#{$this->identificador}\" aria-expanded=\"true\" aria-controls=\"{$this->identificador}\">";
        } else {
            $a[] = "        <a class=\"acordeon-vinculo collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#{$this->padre_identificador}\" href=\"#{$this->identificador}\" aria-expanded=\"false\" aria-controls=\"{$this->identificador}\">";
        }
        $a[] = "          {$this->titulo}";
        $a[] = "        </a>"; // button
        $a[] = "      </h4>"; // panel-title
        $a[] = "    </div>"; // panel-heading
        // Acumular contenido
        if ($this->esta_abierto) {
            $a[] = "    <div id=\"{$this->identificador}\" class=\"panel-collapse collapse in\" role=\"tabpanel\" aria-labelledby=\"cabecera{$this->identificador}\">";
        } else {
            $a[] = "    <div id=\"{$this->identificador}\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"cabecera{$this->identificador}\">";
        }
        $a[] = "      <div class=\"panel-body\">";
        $a[] = $this->contenido->html();
        $a[] = "      </div>"; // panel-body
        $a[] = "    </div>"; // panel-collapse collapse in
        $a[] = "  </div>"; // panel panel-default
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
        // Obtener javascript
        $js = $this->contenido->javascript();
        if (empty(trim($js))) {
            // No tiene javascript
            return NULL;
        } else {
            // Sí tiene javascript
            $a = array();
            if ($this->esta_abierto) {
                $a[] = "  // AcordeonWeb {$this->identificador} ";
                $a[] = $js;
            } else {
                $a[] = "  // AcordeonWeb {$this->identificador} ejecuta lo siguiente al mostrar";
                $a[] = "  $('#{$this->identificador}').on('shown.bs.collapse', function () {";
                $a[] = $js;
                $a[] = "  })";
            }
            return implode("\n", $a);
        }
    } // javascript

} // Clase AcordeonWeb

?>
