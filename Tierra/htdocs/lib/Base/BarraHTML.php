<?php
/**
 * GenesisPHP - BarraHTML
 *
 * Copyright 2015 Guillermo Valdés Lozano <guivaloz@movimientolibre.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @package GenesisPHP
 */

namespace Base;

/**
 * Clase BarraHTML
 *
 * Crea una barra (navbar) con Twitter Bootstrap
 * Se usa en DetalleHTML y ListadoHTML para mostrar el encabezado y
 * los botones con las acciones, por ejemplo, Agregar.
 */
class BarraHTML {

    public $encabezado;
    public $icono;
    protected $botones            = array();
    protected $confirmaciones     = array();
    protected $opciones           = array();
    protected $javascript         = array();
    static public $botones_clases = array(
        'default' => '',             // BLANCO
        'primary' => 'btn-primary',  // AZUL FUERTE
        'info'    => 'btn-info',     // AZUL CLARO
        'success' => 'btn-success',  // VERDE
        'warning' => 'btn-warning',  // AMARILLO
        'danger'  => 'btn-danger',   // ROJO
        'inverse' => 'btn-inverse'); // NEGRO

    /**
     * Botón
     *
     * @param string URL de destino
     * @param string Etiqueta
     * @param string Clase CSS
     */
    public function boton($in_url, $in_etiqueta, $in_clase) {
        $this->botones[$in_etiqueta] = array(
            'url'      => $in_url,
            'clase'    => $in_clase);
    } // boton

    /**
     * Botón Agregar
     *
     * @param string URL de destino
     * @param string Opcional etiqueta
     */
    public function boton_agregar($in_url, $in_etiqueta='Agregar') {
        $this->boton($in_url, $in_etiqueta, 'success'); // BOTON VERDE
    } // boton_agregar

    /**
     * Botón Modificar
     *
     * @param string URL de destino
     * @param string Opcional etiqueta
     */
    public function boton_modificar($in_url, $in_etiqueta='Modificar') {
        $this->boton($in_url, $in_etiqueta, 'warning'); // BOTON AMARILLO
    } // boton_modificar

    /**
     * Botón Descargar
     *
     * @param string URL de destino
     * @param array  Arreglo asociativo con los filtros
     * @param string Opcional etiqueta
     */
    public function boton_descargar($in_url, $filtros_param='', $in_etiqueta='') {
        // ETIQUETA POR DEFECTO
        if ($in_etiqueta == '') {
            $in_etiqueta = '<span class="glyphicon glyphicon-floppy-save"></span> Descargar';
        }
        // SI ES UN ARREGLO
        if (is_array($filtros_param)) {
            // JUNTAR EN UN ARREGLO VARIABLE=VALOR
            $a = array();
            foreach ($filtros_param as $var => $valor) {
                if ($valor != '') {
                    $a[] = $var.'='.urlencode($valor);
                }
            }
            // ANEXAR AL FINAL DEL URL
            if (strpos($in_url, '?') === false) {
                $url = $in_url.'?'.implode('&', $a);
            } else {
                $url = $in_url.'&'.implode('&', $a);
            }
        } else {
            $url = $in_url;
        }
        // BOTON
        $this->boton($url, $in_etiqueta, 'info'); // BOTON AZUL CLARO
    } // boton_descargar

    /**
     * Botón Descargar CSV
     *
     * @param string URL de destino
     * @param array  Arreglo asociativo con los filtros
     */
    public function boton_descargar_csv($in_url, $filtros_param='') {
        $this->boton_descargar($in_url, $filtros_param, '<span class="glyphicon glyphicon-floppy-save"></span> CSV');
    } // boton_descargar

    /**
     * Botón Imprimir
     *
     * @param string URL de destino
     * @param string Opcional etiqueta
     */
    public function boton_imprimir($in_url, $in_etiqueta='Modificar') {
        $this->boton($in_url, $in_etiqueta, 'success'); // BOTON VERDE
    } // boton_imprimir

    /**
     * Botón con Confirmación
     *
     * @param string URL de destino
     * @param string Etiqueta
     * @param string Clase CSS
     * @param string Accion única
     * @param string Pregunta de confirmación
     */
    public function boton_confirmacion($in_url, $in_etiqueta, $in_clase, $in_accion, $in_pregunta) {
        $this->botones[$in_etiqueta] = array(
            'url'      => $in_url,
            'clase'    => $in_clase,
            'accion'   => $in_accion);
        $this->confirmaciones[$in_etiqueta] = array(
            'accion'   => $in_accion,
            'pregunta' => $in_pregunta);
    } // boton_confirmacion

    /**
     * Botón Eliminar con confimación
     *
     * @param string URL de destino
     * @param string Pregunta de confirmación
     * @param string Opcional etiqueta
     */
    public function boton_eliminar_confirmacion($in_url, $in_pregunta, $in_etiqueta='Eliminar') {
        $this->boton_confirmacion($in_url, $in_etiqueta, 'danger', 'eliminarRegistro', $in_pregunta); // BOTON ROJO
    } // boton_eliminar_confirmacion

    /**
     * Botón Recuperar con confirmación
     *
     * @param string URL de destino
     * @param string Pregunta de confirmación
     * @param string Opcional etiqueta
     */
    public function boton_recuperar_confirmacion($in_url, $in_pregunta, $in_etiqueta='Recuperar') {
        $this->boton_confirmacion($in_url, $in_etiqueta, 'info', 'recuperarRegistro', $in_pregunta); // BOTON AZUL CLARO
    } // boton_recuperar_confirmacion

    /**
     * Botón Imprimir con confirmación
     *
     * @param string URL de destino
     * @param string Pregunta de confirmación
     * @param string Opcional etiqueta
     * @param string Opcional clave de la confirmacion, ya que puede haber más de una forma de imprimir por módulo
     */
    public function boton_imprimir_confirmacion($in_url, $in_pregunta, $in_etiqueta='Imprimir', $in_impresion='impresionRegistro') {
        $this->boton_confirmacion($in_url, $in_etiqueta, 'success', $in_impresion, $in_pregunta); // BOTON VERDE
    } // boton_imprimir_confirmacion

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @param  string Icono opcional
     * @return string HTML
     */
    public function html($in_encabezado='', $in_icono='') {
        // PARAMETROS
        if (is_string($in_encabezado) && (trim($in_encabezado) != '')) {
            $this->encabezado = $in_encabezado;
        }
        if (is_string($in_icono) && (trim($in_icono) != '')) {
            $this->icono = $in_icono;
        }
        // VALIDAR
        if ($this->encabezado == '') {
            $this->encabezado = 'Falta el encabezado';
        }
        // NAVBAR INICIA
        $a   = array();
        $a[] = '  <div class="navbar navbar-default barra">';
        $a[] = '    <div class="navbar-header">';
        if ($this->icono != '') {
            if (strpos($this->icono, 'glyphicon') === 0) {
                $icono = "<span class=\"{$this->icono}\"></span>";
            } else {
                $icono = "<img src=\"imagenes/24x24/{$this->icono}\">";
            }
            $a[] = "      <a class=\"navbar-brand\" href=\"#\">$icono {$this->encabezado}</a>";
        } else {
            $a[] = "      <a class=\"navbar-brand\" href=\"#\">{$this->encabezado}</a>";
        }
        $a[] = '    </div>'; // navbar-header
        // BOTONES
        $a[] = '    <div class="navbar-right">';
        foreach ($this->botones as $etiqueta => $b) {
            $clase = 'btn '.self::$botones_clases[$b['clase']].' navbar-btn';
            if (is_array($this->confirmaciones[$etiqueta])) {
                $a[] = sprintf('      <button class="%s" data-toggle="modal" data-target="#%s">%s</button>', $clase, $this->confirmaciones[$etiqueta]['accion'], $etiqueta);
            } else {
                $a[] = sprintf('      <button class="%s" onclick="location.href=\'%s\'">%s</button>', $clase, $b['url'], $etiqueta);
            }
        }
        $a[] = '    </div>'; // navbar-header
        $a[] = '  </div>'; // navbar
        // NAVBAR TERMINA
        // DIALOGOS DE CONFIRMACION
        foreach ($this->botones as $etiqueta => $b) {
            if (is_array($this->confirmaciones[$etiqueta])) {
                // PARA QUE SE VEA BONITO
                $accion   = $this->confirmaciones[$etiqueta]['accion'];
                $pregunta = $this->confirmaciones[$etiqueta]['pregunta'];
                $clase    = 'btn '.self::$botones_clases[$b['clase']];
                $url      = $b['url'];
                // TWITTER BOOTSTRAP MODAL
                $a[] = "  <div class=\"modal fade\" id=\"$accion\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"{$accion}Label\" aria-hidden=\"true\">";
                $a[] = '    <div class="modal-dialog">';
                $a[] = '      <div class="modal-content">';
                $a[] = '        <div class="modal-header">';
                $a[] = '          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                $a[] = "          <h4 class=\"modal-title\" id=\"{$accion}Label\">$etiqueta</h4>";
                $a[] = '        </div>';
                $a[] = '        <div class="modal-body">';
                $a[] = "          <p>$pregunta</p>";
                $a[] = '        </div>';
                $a[] = '        <div class="modal-footer">';
                $a[] = '          <button class="btn" data-dismiss="modal">Cancelar</button>'; // aria-hidden="true"
                $a[] = "          <button class=\"$clase\" onClick=\"location.href='$url'\">$etiqueta</button>";
                $a[] = '        </div>';
                $a[] = '      </div>';
                $a[] = '    </div>';
                $a[] = '  </div>';
            }
        }
        // ENTREGAR
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Javascript, si no hay entrega falso
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
                return false;
            }
        } else {
            return false;
        }
    } // javascript

} // Clase BarraHTML

?>
