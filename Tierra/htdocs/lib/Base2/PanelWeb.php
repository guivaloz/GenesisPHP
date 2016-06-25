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

    public $encabezado;
    public $contenido;
    public $pie;
    public $tipo;
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
        if (is_string($this->encabezado) && ($this->encabezado != '')) {
            $a[] = '  <div class="panel-heading">';
            $a[] = sprintf('    <h3 class="panel-title">%s</h3>', htmlentities($this->encabezado));
            $a[] = '  </div>';
        } else {
            $a[] = '  <div class="panel-heading">';
            $a[] = '    <h3 class="panel-title">Panel sin título</h3>';
            $a[] = '  </div>';
        }
        // Acumular contenido
        // Acumular pie
        $a[] = '</div>';
        // Entregar
        return implode("\n", $a);
    } // html

/*
  <div class="panel-body">
    Panel content
  </div>
  <div class="panel-footer">Panel footer</div>
</div>
*
<div class="panel panel-primary">...</div>
<div class="panel panel-success">...</div>
<div class="panel panel-info">...</div>
<div class="panel panel-warning">...</div>
<div class="panel panel-danger">...</div>
*/

    /**
     * Javascript
     *
     * @return string Código Javascript
     */
    public function javascript() {
        return false;
    } // javascript

} // Clase PanelWeb

?>
