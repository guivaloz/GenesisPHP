<?php
/**
 * DetalleHTML.php
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
 * Clase DetalleHTML
 */
class DetalleHTML {

    public $encabezado;                    // OPCIONAL, TEXTO DEL ENCABEZADO
    public $icono;                         // OPCIONAL, URL AL ICONO
    public $barra;                         // OPCIONAL, PUEDE RECIBIR UNA INSTACIA DE BarraHTML
    protected $secciones        = array();
    protected $seccion_actual   = 'Datos';
    protected $imagenes         = array();
    protected $pie              = array(); // ARREGLO DE OBJETOS O DE CODIGOS HTML A AGREGAR AL FINAL CON EL METODO al_final
    protected $javascript       = array(); // ARREGLO, JAVASCRIPT A COLOCAR AL FINAL DE LA PAGINA
    static public $icono_tamano = '24x24';

    /**
     * Seccion
     *
     * @param string Nombre de la sección
     */
    public function seccion($in_texto) {
        $this->seccion_actual                   = $in_texto;
        $this->secciones[$this->seccion_actual] = array();
    } // seccion

    /**
     * Dato
     *
     * @param string Etiqueta
     * @param string Dato en HTML
     * @param string Opcional, Color
     */
    public function dato($in_etiqueta, $in_dato, $in_color='') {
        // SI ES TEXTO, ENTERO
        if (is_string($in_dato)) {
            $sin_espacios = trim($in_dato);
          //if (($sin_espacios !== '') && ($sin_espacios != '0')) {
            if ($sin_espacios !== '') {
                $dato = $sin_espacios;
            }
        } elseif (is_int($in_dato) || is_float($in_dato)) {
          //if ($in_dato != 0) {
                $dato = $in_dato;
          //}
        } elseif (is_bool($in_dato)) {
            if ($in_dato) {
                $dato = 'VERDADERO';
            } else {
                $dato = 'FALSO';
            }
        } else {
            return;
        }
        // SI VIENE EL COLOR SE GUARDA COMO ARREGLO ASOCIATIVO
        if ($in_color == '') {
            $this->secciones[$this->seccion_actual][$in_etiqueta] = $dato;
        } else {
            $this->secciones[$this->seccion_actual][$in_etiqueta] = array('dato' => $dato, 'color' => $in_color);
        }
    } // dato

    /**
     * Imagen
     *
     * @param mixed Instacia de la Clase Imagen con el ID y los caracteres al azar ya cargados
     */
    public function imagen(ImagenHTML $in_imagen) {
        $this->imagenes[] = $in_imagen;
    } // imagen

    /**
     * Al Final
     *
     * Agregar un objeto o código HTML para ponerlo al final
     *
     * @param mixed Objeto o Código HTML
     */
    public function al_final($in) {
        if (is_string($in) && ($in != '')) {
            $this->pie[] = $in;
        } elseif (is_object($in)) {
            $this->pie[] = $in;
        }
    } // al_final

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @param  string Icono opcional
     * @return string HTML
     */
    public function html($in_encabezado='', $in_icono='') {
        // SI ESTA DEFINIDA LA BARRA
        if (is_object($this->barra)) {
            // NO SE CONSIDERAN
            $this->encabezado = '';
            $this->icono      = '';
        } else {
            // NO HAY BARRA, SE TOMAN LOS PARAMETROS
            if ($in_encabezado != '') {
                $this->encabezado = $in_encabezado;
            }
            if ($in_icono != '') {
                $this->icono = $in_icono;
            }
        }
        // EN ESTE ARREGLO JUNTAREMOS EL CONTENIDO
        $a = array();
        // ELABORAR EL CONTENIDO
        $a[] = '  <dl class="dl-horizontal">';
        foreach ($this->secciones as $seccion_etiqueta => $seccion_datos) {
            foreach ($seccion_datos as $dato_etiqueta => $dato_valor) {
                if (is_array($dato_valor)) {
                    $valor = $dato_valor['dato']; // FALTA USAR EL $dato_valor['color']
                } else {
                    $valor = $dato_valor;
                }
                if (trim($valor) != '') {
                    $a[] = "    <dt>$dato_etiqueta</dt><dd>$valor</dd>";
                }
            }
        }
        $a[] = '  </dl>';
        // SI HAY ALGO EN EL PIE SE AGREGARÁ AL CONTENIDO
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p)) {
                    $a[] = $p->html();
                } elseif (is_string($p)) {
                    $a[] = $p;
                }
            }
        } elseif (is_string($this->pie) && ($this->pie != '')) {
            $a[] = $this->pie;
        }
        // LISTO EL CONTENIDO
        $contenido = implode("\n", $a);
        // ENCABEZADO
        if (is_object($this->barra)) {
            // HAY BARRA, ESE ES EL ENCABEZADO
            $encabezado = $this->barra->html()."\n";
        } elseif ($this->encabezado != '') {
            // ENCABEZADO HECHO AQUI MISMO
            $barra             = new BarraHTML();
            $barra->encabezado = $this->encabezado;
            $barra->icono      = $this->icono;
            $encabezado        = $barra->html()."\n";
        }
        // SI TIENE IMAGENES
        if (count($this->imagenes) > 0) {
            // SOLO MOSTRAREMOS LA PRIMER IMAGEN
            reset($this->imagenes);
            $imagen = current($this->imagenes);
            $imagen->configurar_para_detalle();
            // SE ESPERA QUE LA INSTANCIA DE LA IMAGEN TENGA CARGADO EL ID Y LOS CARACTERES AL AZAR
            try {
                $imagen_html = $imagen->html();
            } catch (\Exception $e) {
                $mensaje = new MensajeHTML($e->getMessage());
                return $mensaje->html($e->getMessage());
            }
            // ENTREGAR TWITTER BOOTSTRAP MEDIA OBJECT
            return <<<FIN
<div class="media detalle">
  {$imagen_html}
  <div class="media-body">
{$encabezado}{$contenido}
  </div>
</div>
FIN;
        } else {
            // NO TIENE IMAGENES, ENTREGAR CLASE DETALLE
            return <<<FIN
<div class="detalle">
{$encabezado}{$contenido}
</div>
FIN;
        }
    } // html

    /**
     * Javascript
     *
     * Entregar el javascript. Si no lo hay, entrega falso.
     *
     * @return string Javascript
     */
    public function javascript() {
        // SI HAY CODIGO JAVASCRIPT EN LOS OBJETOS DEL PIE
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p)) {
                    $this->javascript[] = $p->javascript();
                }
            }
        }
        // ENTREGAR
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

} // Clase DetalleHTML

?>
