<?php
/**
 * GenesisPHP - DetalleWeb
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
 * Clase DetalleWeb
 */
class DetalleWeb implements SalidaWeb {

    public $encabezado;                    // Opcional, texto para el encabezado
    public $icono;                         // Opcional, URL al icono
    public $barra;                         // Opcional, instancia de BarraWeb
    protected $secciones      = array();
    protected $seccion_actual = 'Datos';
    protected $imagenes       = array();
    protected $pie            = array(); // Arreglo de objetos o códigos HTML para poner al final con al_final
    protected $javascript     = array(); // Arreglo con Javascript

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
        // Si es texto, entero o boleano
        if (is_string($in_dato)) {
            $sin_espacios = trim($in_dato);
            if ($sin_espacios !== '') {
                $dato = $sin_espacios;
            }
        } elseif (is_int($in_dato) || is_float($in_dato)) {
            $dato = $in_dato;
        } elseif (is_bool($in_dato)) {
            if ($in_dato) {
                $dato = 'VERDADERO';
            } else {
                $dato = 'FALSO';
            }
        } else {
            return;
        }
        // Si viene el color se guarda en arreglo asociativo
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
    public function imagen(ImagenWeb $in_imagen) {
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
     * @return string Código HTML
     */
    public function html($in_encabezado='') {
        // Si viene el encabezado como parámetro
        if ($in_encabezado != '') {
            $this->encabezado = $in_encabezado;
        }
        // Si está definida la barra, se ponen en blanco las propiedades encabezado e icono
        if (is_object($this->barra) && ($this->barra instanceof BarraWeb)) {
            $this->encabezado = '';
            $this->icono      = '';
        }
        // En este arreglo se acumulará
        $a = array();
        // Acumular contenido
        $a[] = '  <dl class="dl-horizontal">';
        foreach ($this->secciones as $seccion_etiqueta => $seccion_datos) {
            foreach ($seccion_datos as $dato_etiqueta => $dato_valor) {
                if (is_array($dato_valor)) {
                    $valor = $dato_valor['dato']; // Falta usar el $dato_valor['color']
                } else {
                    $valor = $dato_valor;
                }
                if (trim($valor) != '') {
                    $a[] = "    <dt>$dato_etiqueta</dt><dd>$valor</dd>";
                }
            }
        }
        $a[] = '  </dl>';
        // Acumular pie
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p)) {
                    $a[]                = $p->html();
                    $this->javascript[] = $p->javascript();
                } elseif (is_string($p)) {
                    $a[] = $p;
                }
            }
        } elseif (is_string($this->pie) && ($this->pie != '')) {
            $a[] = $this->pie;
        }
        // Definir contenido
        $contenido = implode("\n", $a);
        // Elaborar Barra
        if (is_object($this->barra) && ($this->barra instanceof BarraWeb)) {
            $barra_html         = $this->barra->html();
            $this->javascript[] = $this->barra->javascript();
        } elseif ($this->encabezado != '') {
            $barra              = new BarraWeb();
            $barra->encabezado  = $this->encabezado;
            $barra->icono       = $this->icono;
            $barra_html         = $barra->html();
            $this->javascript[] = $barra->javascript();
        }
        // Si tiene imágenes
        if (count($this->imagenes) > 0) {
            // Se toma la primera
            reset($this->imagenes);
            $imagen = current($this->imagenes);
            $imagen->configurar_para_detalle();
            try {
                $imagen_html = $imagen->html();
            } catch (\Exception $e) {
                $mensaje = new MensajeWeb($e->getMessage());
                return $mensaje->html('Error');
            }
            // Entregar Twitter Bootstrap Media Object
            return <<<FIN
<div class="media detalle">
  {$imagen_html}
  <div class="media-body">
{$barra_html}
{$contenido}
  </div>
</div>
FIN;
        } else {
            // Entregar sin imágenes
            return <<<FIN
<div class="detalle">
{$barra_html}
{$contenido}
</div>
FIN;
        }
    } // html

    /**
     * Javascript
     *
     * @return string Código Javascript
     */
    public function javascript() {
        return false;
    } // javascript

} // Clase DetalleWeb

?>
