<?php
/**
 * GenesisPHP - MensajeHTML
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
 * Clase MensajeHTML
 */
class MensajeHTML {

    public $encabezado;                    // OPCIONAL, TEXTO DEL ENCABEZADO
    public $icono;                         // OPCIONAL, URL AL ICONO
    public $contenido;                     // TEXTO, CONTENIDO QUE MOSTRAR EN EL MENSAJE
    public $tipo;                          // CARACTER, ES EL TIPO DE MENSAJE
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
        'default' => 'btn',              // GRIS
        'primary' => 'btn btn-primary',  // AZUL FUERTE
        'info'    => 'btn btn-info',     // AZUL CLARO
        'success' => 'btn btn-success',  // VERDE
        'warning' => 'btn btn-warning',  // AMARILLO
        'danger'  => 'btn btn-danger',   // ROJO
        'inverse' => 'btn btn-inverse'); // NEGRO

    /**
     * Constructor
     *
     * @param string Contenido del mensaje
     */
    public function __construct($in_contenido='') {
        // EL CONTENIDO SE VALIDARA EN EL METODO HTML
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
        // VALIDAR IDENTIFICADOR
        if (!is_string($in_identificador) || (trim($in_identificador) == '')) {
            return;
        } else {
            $identificador = $in_identificador;
        }
        // VALIDAR ETIQUETA
        if (!is_string($in_etiqueta) || (trim($in_etiqueta) == '')) {
            return;
        } else {
            $etiqueta = $in_etiqueta;
        }
        // VALIDAR URL
        if (!is_string($in_url) || (trim($in_url) == '')) {
            $url = '#';
        } else {
            $url = $in_url;
        }
        // VALIDAR CLASE (ESTILO CSS)
        if (($in_clase == '') || !array_key_exists($in_clase, self::$botones_clases)) {
            $clase = 'default';
        } else  {
            $clase = self::$botones_clases[$in_clase];
        }
        // AGREGAR A PIE
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
        // SI NO HAY CONTENIDO, NO SE ENTREGA NADA
        if (is_array($this->contenido) && (count($this->contenido) == 0)) {
            return;
        } elseif (is_string($this->contenido) && (trim($this->contenido) == '')) {
            return;
        } elseif (!is_array($this->contenido) && !is_string($this->contenido)) {
            return;
        }
        // SI VIENE EL ENCABEZADO COMO PARAMETRO
        if ($in_encabezado != '') {
            $this->encabezado = $in_encabezado;
        } else {
            $this->encabezado = 'Mensaje';
        }
        // SI VIENE EL TIPO COMO PARAMETRO
        if (($in_tipo != '') && array_key_exists($in_tipo, self::$tipos_colores)) {
            $this->tipo = $in_tipo;
        // SI NO SE HA DEFINIDO EL TIPO
        } elseif ($this->tipo == '') {
            // SI EL CONTENIDO ES ARREGLO
            if (is_array($this->contenido)) {
                $muestra = current($this->contenido);
            } else {
                $muestra = $this->contenido;
            }
            // DETERMINAMOS EL TIPO CON EL CONTENIDO, SI EMPIEZA CON ERROR O CON AVISO
            if (stripos($muestra, 'Error') === 0) {
                $this->tipo = 'error';
            } elseif (stripos($muestra, 'Aviso') === 0) {
                $this->tipo = 'aviso';
            } else {
                $this->tipo = 'info';
            }
        }
        // ICONO
        if (array_key_exists($this->tipo, self::$tipos_iconos)) {
            $this->icono = self::$tipos_iconos[$this->tipo];
        } else {
            $this->icono = 'start-here.png'; // ICONO POR DEFECTO
        }
        // ACUMULAREMOS EL HTML EN ESTE ARREGLO
        $a = array();
        // TWITTER BOOTSTRAP ALERT INICIA
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
        // ENCEBEZADO E ICONO
        if ($this->encabezado != '') {
            if ($this->icono != '') {
                $a[] = sprintf('  <h4><img src="imagenes/%s/%s"> %s</h4>', self::$icono_tamano, $this->icono, $this->encabezado);
            } else {
                $a[] = "  <h4>{$this->encabezado} {$this->icono}</h4>";
            }
        }
        // CONTENIDO
        if (is_array($this->contenido)) {
            // ES ARREGLO
            foreach ($this->contenido as $item) {
                $a[] = "  <p>$item</p>";
            }
        } else {
            // ES TEXTO
            $a[] = "  <p>{$this->contenido}</p>";
        }
        // PIE
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            $a[] = '  '.implode(' ', $this->pie);
        }
        // TWITTER BOOTSTRAP ALERT TERMINA
        $a[] = '</div>';
        // ENTREGAR
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
