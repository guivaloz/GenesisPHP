<?php
/**
 * GenesisPHP - PanelWeb
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
 * Clase PanelWeb
 */
class PanelWeb implements SalidaWeb {

    public $encabezado;                       // Texto
    public $contenido;                        // Texto, instancia
    public $pie;                              // Texto
    public $tipo;                             // Texto
    static protected $tipos_clases = array(
        'normal'    => 'panel panel-default', // gris
        'destacado' => 'panel panel-primary', // azul
        'tip'       => 'panel panel-success', // verde
        'info'      => 'panel panel-info',    // azul claro
        'aviso'     => 'panel panel-warning', // amarillo
        'error'     => 'panel panel-danger'); // rojo

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string Código HTML
     */
    public function html($in_encabezado='') {
        // Iniciar arreglo donde acumularemos la entrega
        $a = array();
        if (is_string($this->tipo) && in_array($this->tipo, self::$tipos_clases)) {
            $a[] = sprintf('<div class="%s">', self::$tipos_clases[$this->tipo]);
        } else {
            $a[] = '<div class="panel panel-default">';
        }
        // Acumular encabezado
        $a[] = '  <div class="panel-heading">';
        if (is_string($this->encabezado) && ($this->encabezado != '')) {
            $a[] = sprintf('    <h3 class="panel-title">%s</h3>', htmlentities($this->encabezado));
        } else {
            $a[] = '    <h3 class="panel-title">Panel sin título</h3>';
        }
        $a[] = '  </div>';
        // Acumular contenido
        $a[] = '  <div class="panel-body">';
        if (is_string($this->contenido) && ($this->contenido != '')) {
            $a[] = $this->contenido;
        } elseif (is_object($this->contenido) && ($this->contenido instanceof \Base2\SalidaWeb)) {
            $a[] = $this->contenido->html();
        } elseif (is_array($this->contenido) && (count($this->contenido) > 0)) {
            foreach ($this->contenido as $c) {
                if (is_string($c) && ($c != '')) {
                    $a[] = $c;
                } elseif (is_object($c) && ($c instanceof \Base2\SalidaWeb)) {
                    $a[] = $c->html();
                }
            }
        }
        $a[] = '  </div>';
        // Acumular pie
        if (is_string($this->pie) && ($this->pie != '')) {
            $a[] = sprintf('  <div class="panel-footer">%s</div>', htmlentities($this->pie));
        }
        // Cerrar panel
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
        if (is_object($this->contenido) && ($this->contenido instanceof SalidaWeb)) {
            return $this->contenido->javascript();
        } elseif (is_array($this->contenido) && (count($this->contenido) > 0)) {
            $a = array();
            foreach ($this->contenido as $c) {
                if (is_object($c) && ($c instanceof \Base2\SalidaWeb)) {
                    $a[] = $c->javascript();
                }
            }
            return implode("\n", $a);
        } else {
            return false;
        }
    } // javascript

} // Clase PanelWeb

?>
