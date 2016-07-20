<?php
/**
 * GenesisPHP - BarraWeb
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
 * Clase BarraWeb
 */
class BarraWeb implements SalidaWeb {

    public $encabezado;                      // Texto con el encabezado
    public $icono;                           // Opcional, nombre del archivo del icono o nombre del font awsome
    protected $botones            = array();
    protected $confirmaciones     = array();
    protected $opciones           = array();
    static public $botones_clases = array(
        'default' => '',             // Blanco
        'primary' => 'btn-primary',  // Azul fuerte
        'info'    => 'btn-info',     // Azul claro
        'success' => 'btn-success',  // Verde
        'warning' => 'btn-warning',  // Amarillo
        'danger'  => 'btn-danger',   // Rojo
        'inverse' => 'btn-inverse'); // Negro

    /**
     * Botón
     *
     * @param string URL de destino
     * @param string Etiqueta
     * @param string Clase CSS
     */
    public function boton($in_url, $in_etiqueta, $in_clase) {
        $this->botones[$in_etiqueta] = array(
            'url'   => $in_url,
            'clase' => $in_clase);
    } // boton

    /**
     * Botón Agregar
     *
     * @param string URL de destino
     * @param string Opcional etiqueta
     */
    public function boton_agregar($in_url, $in_etiqueta='Agregar') {
        $this->boton($in_url, $in_etiqueta, 'success');
    } // boton_agregar

    /**
     * Botón Modificar
     *
     * @param string URL de destino
     * @param string Opcional etiqueta
     */
    public function boton_modificar($in_url, $in_etiqueta='Modificar') {
        $this->boton($in_url, $in_etiqueta, 'warning');
    } // boton_modificar

    /**
     * Botón Descargar
     *
     * @param string URL de destino
     * @param array  Arreglo asociativo con los filtros
     * @param string Opcional etiqueta
     */
    public function boton_descargar($in_url, $filtros_param='', $in_etiqueta='') {
        // Etiqueta por defecto
        if ($in_etiqueta == '') {
            $in_etiqueta = '<span class="glyphicon glyphicon-floppy-save"></span> Descargar';
        }
        // Si filtros_param es un arreglo
        if (is_array($filtros_param)) {
            // Juntar de la forma variable=valor
            $a = array();
            foreach ($filtros_param as $var => $valor) {
                if ($valor != '') {
                    $a[] = $var.'='.urlencode($valor);
                }
            }
            // Agregar al final de la URL
            if (strpos($in_url, '?') === false) {
                $url = $in_url.'?'.implode('&', $a);
            } else {
                $url = $in_url.'&'.implode('&', $a);
            }
        } else {
            $url = $in_url;
        }
        // Botón
        $this->boton($url, $in_etiqueta, 'info');
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
        $this->boton($in_url, $in_etiqueta, 'success');
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
        $this->boton_confirmacion($in_url, $in_etiqueta, 'danger', 'eliminarRegistro', $in_pregunta);
    } // boton_eliminar_confirmacion

    /**
     * Botón Recuperar con confirmación
     *
     * @param string URL de destino
     * @param string Pregunta de confirmación
     * @param string Opcional etiqueta
     */
    public function boton_recuperar_confirmacion($in_url, $in_pregunta, $in_etiqueta='Recuperar') {
        $this->boton_confirmacion($in_url, $in_etiqueta, 'info', 'recuperarRegistro', $in_pregunta);
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
        $this->boton_confirmacion($in_url, $in_etiqueta, 'success', $in_impresion, $in_pregunta);
    } // boton_imprimir_confirmacion

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
        // Navbar inicia
        $a   = array();
        $a[] = '  <div class="navbar navbar-default barra">';
        $a[] = '    <div class="navbar-header">';
        if ($this->icono != '') {
            if (strpos($this->icono, 'glyphicon') === 0) {
                $icono = "<span class=\"{$this->icono}\"></span>";
            } else {
                $icono = "<img class=\"pull-left barra-icono\" src=\"imagenes/24x24/{$this->icono}\">";
            }
            $a[] = "      <a class=\"navbar-brand barra-encabezado\" href=\"#\">$icono {$this->encabezado}</a>";
        } else {
            $a[] = "      <a class=\"navbar-brand barra-encabezado\" href=\"#\">{$this->encabezado}</a>";
        }
        $a[] = '    </div>'; // navbar-header
        // Botones
        if (count($this->botones) > 0) {
            $a[] = '    <div class="navbar-right">';
            foreach ($this->botones as $etiqueta => $b) {
                $clase = 'btn '.self::$botones_clases[$b['clase']].' navbar-btn';
                if (is_array($this->confirmaciones[$etiqueta])) {
                    $a[] = sprintf('      <button class="%s" data-toggle="modal" data-target="#%s">%s</button>', $clase, $this->confirmaciones[$etiqueta]['accion'], $etiqueta);
                } else {
                    $a[] = sprintf('      <button class="%s" onclick="location.href=\'%s\'">%s</button>', $clase, $b['url'], $etiqueta);
                }
            }
            $a[] = '    </div>'; // navbar-right
        }
        $a[] = '  </div>'; // navbar
        // Navbar termina
        // Diálogos de confirmación
        foreach ($this->botones as $etiqueta => $b) {
            if (is_array($this->confirmaciones[$etiqueta])) {
                // Variables
                $accion   = $this->confirmaciones[$etiqueta]['accion'];
                $pregunta = $this->confirmaciones[$etiqueta]['pregunta'];
                $clase    = 'btn '.self::$botones_clases[$b['clase']];
                $url      = $b['url'];
                // Twitter Bootstrap Modal
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
        // Entregar
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Código Javascript
     */
    public function javascript() {
        return ''; // BarraWeb no genera javascript
    } // javascript

} // Clase BarraWeb

?>
