<?php
/**
 * GenesisPHP - MensajeHTML
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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

namespace Base;

/**
 * Clase MensajeHTML
 */
class MensajeHTML {

    public $encabezado;                    // Opcional, texto para el encabezado
    public $icono;                         // Opcional, URL al icono
    public $contenido;                     // Texto del mensaje
    public $tipo;                          // Opcional, texto con el tipo de mensaje, puede ser aviso, error, info o tip
    protected $pie = array();
    static protected $tipos_colores = array(
        'aviso' => 'amarillo',
        'error' => 'rojo',
        'info'  => 'verde',
        'tip'   => 'blanco');
    static protected $tipos_iconos = array(
        'aviso' => 'dialog-warning.png',
        'error' => 'dialog-error.png',
        'info'  => 'face-smile.png',
        'tip'   => 'dialog-information.png');
    static public $icono_tamano = '24x24';
    static public $botones_clases = array(
        'default' => 'btn',              // Gris
        'primary' => 'btn btn-primary',  // Azul fuerte
        'info'    => 'btn btn-info',     // Azul claro
        'success' => 'btn btn-success',  // Verde
        'warning' => 'btn btn-warning',  // Amarillo
        'danger'  => 'btn btn-danger',   // Rojo
        'inverse' => 'btn btn-inverse'); // Negro

    /**
     * Constructor
     *
     * @param string Contenido del mensaje
     */
    public function __construct($in_contenido='') {
        $this->contenido = $in_contenido;
    } // constructor

    /**
     * Boton URL
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string URL de destino
     * @param string Clase CSS, color del boton, opcional
     */
    public function boton_url($in_identificador, $in_etiqueta, $in_url, $in_clase='') {
        // Validar identificador
        if (!is_string($in_identificador) || (trim($in_identificador) == '')) {
            return;
        } else {
            $identificador = $in_identificador;
        }
        // Validar etiqueta
        if (!is_string($in_etiqueta) || (trim($in_etiqueta) == '')) {
            return;
        } else {
            $etiqueta = $in_etiqueta;
        }
        // Validar URL
        if (!is_string($in_url) || (trim($in_url) == '')) {
            $url = '#';
        } else {
            $url = $in_url;
        }
        // Validar estilo CSS
        if (($in_clase == '') || !array_key_exists($in_clase, self::$botones_clases)) {
            $clase = 'default';
        } else  {
            $clase = self::$botones_clases[$in_clase];
        }
        // Agrgar a pie
        $this->pie[$identificador] = sprintf('<button class="%s" type="button" onclick="location.href=\'%s\'">%s</button>', $clase, $url, $etiqueta);
    } // boton_url

    /**
     * Botón Agregar
     *
     * @param string Etiqueta
     * @param string URL de destino
     */
    public function boton_agregar($in_etiqueta, $in_url) {
        $this->boton_url('agregar', $in_etiqueta, $in_url, 'success');
    } // boton_agregar

    /**
     * HTML
     *
     * @param  string Titulo opcional
     * @param  string Tipo opcional
     * @return string HTML con el mensaje
     */
    public function html($in_encabezado='', $in_tipo='') {
        // Si no hay contenido, no se entrega nada
        if (is_array($this->contenido) && (count($this->contenido) == 0)) {
            return;
        } elseif (is_string($this->contenido) && (trim($this->contenido) == '')) {
            return;
        } elseif (!is_array($this->contenido) && !is_string($this->contenido)) {
            return;
        }
        // Si viene el encabezado como parámetro
        if ($in_encabezado != '') {
            $this->encabezado = $in_encabezado;
        } else {
            $this->encabezado = 'Mensaje';
        }
        // Si viene el tipo como parámetro
        if (($in_tipo != '') && array_key_exists($in_tipo, self::$tipos_colores)) {
            $this->tipo = $in_tipo;
        } elseif ($this->tipo == '') {
            // No está definido el tipo, se va a definir
            if (is_array($this->contenido)) {
                $muestra = current($this->contenido);
            } else {
                $muestra = $this->contenido;
            }
            // Si el contenido empieza con error o con aviso
            if (stripos($muestra, 'Error') === 0) {
                $this->tipo = 'error';
            } elseif (stripos($muestra, 'Aviso') === 0) {
                $this->tipo = 'aviso';
            } else {
                $this->tipo = 'info';
            }
        }
        // Definir icono
        if (array_key_exists($this->tipo, self::$tipos_iconos)) {
            $this->icono = self::$tipos_iconos[$this->tipo];
        } else {
            $this->icono = 'start-here.png'; // ICONO POR DEFECTO
        }
        // En este arreglo se acumulará el código HTML
        $a = array();
        // Twitter Bootstrap Alert inicia
        switch ($this->tipo) {
            case 'error':
                $a[] = '<div class="alert alert-danger mensaje">';
                break;
            case 'aviso':
                $a[] = '<div class="alert alert-warning mensaje">';
                break;
            case 'info':
                $a[] = '<div class="alert alert-success mensaje">';
                break;
            case 'tip':
                $a[] = '<div class="alert alert-info mensaje">';
        }
        // Acumular encabezado
        if ($this->encabezado != '') {
            if ($this->icono != '') {
                $a[] = sprintf('  <h4><img src="imagenes/%s/%s"> %s</h4>', self::$icono_tamano, $this->icono, $this->encabezado);
            } else {
                $a[] = "  <h4>{$this->encabezado} {$this->icono}</h4>";
            }
        }
        // Acumular contenido
        if (is_array($this->contenido)) {
            foreach ($this->contenido as $item) {
                $a[] = "  <p>$item</p>";
            }
        } else {
            $a[] = "  <p>{$this->contenido}</p>";
        }
        // Acumular pie
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            $a[] = '  '.implode(' ', $this->pie);
        }
        // Twitter Bootstrap Alert termnina
        $a[] = '</div>';
        // Entregar HTML
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return false;
    } // javascript

} // Clase MensajeHTML

?>
