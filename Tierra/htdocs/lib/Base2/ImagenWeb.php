<?php
/**
 * GenesisPHP - ImagenWeb
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
 * Clase ImagenWeb
 */
class ImagenWeb extends Imagen implements SalidaWeb {

    // public $id;
    // public $caracteres_azar;
    // protected $almacen_ruta;
    // protected $almacen_tamanos;
    // protected $tamano_en_uso;
    // protected $imagen;
    // protected $ancho;
    // protected $alto;
    // protected $ruta;
    public $pie;                       // Texto a mostrar debajo de la imagen
    public $url       = '#';           // Vinculo a crear
    public $a_class   = 'thumbnail';   // Clase CSS para el tag a
    public $img_class = '';            // Clase CSS para el tag img
    public $p_class   = 'text-center'; // Clase CSS para el tag p

    /**
     * Configurar para Detalle
     *
     * Configura las propiedades de las clases CSS para que se use en DetalleHTML
     */
    public function configurar_para_detalle() {
        $this->a_class   = 'pull-left';
        $this->img_class = 'media-object';
        $this->p_class   = '';
    } // configurar_para_detalle

    /**
     * Para Tren
     *
     * Configura las propiedades de las clases CSS para que se use en TrenHTML
     */
    public function configurar_para_tren() {
        $this->a_class   = 'thumbnail';
        $this->img_class = '';
        $this->p_class   = 'text-center';
    } // configurar_para_tren

    /**
     * Vincular
     *
     * Define el vínculo que tendrá la imagen
     *
     * @param string URL o el tamaño de imagen para vincular
     */
    public function vincular($in_url_o_tamano) {
        if (!is_string($in_url_o_tamano) || ($in_url_o_tamano == '')) {
            return;
        }
        if (array_key_exists($in_url_o_tamano, $this->almacen_tamanos)) {
            $this->url = sprintf('%s/%s/%s%s.jpg', $this->almacen_ruta, $in_url_o_tamano, $this->id, $this->caracteres_azar);
        } else {
            $this->url = $in_url_o_tamano;
        }
    } // vincular

    /**
     * Imagen Ampliable HTML
     *
     * @param  integer ID de la imagen
     * @param  string  Caracteres al azar usados en el nombre del archivo
     * @param  string  Tamaño de la imagen chica, la que se muestra
     * @param  string  Tamaño de la imagen grande, el destino del vínculo
     * @param  string  Opcional, Clase CSS para la imagen, osea, parámetro class para el img
     * @return string  Código HTML
     */
    public function imagen_ampliable_html($in_id, $in_caracteres_azar, $in_tamano_chico, $in_tamano_grande, $in_img_class='') {
        try {
            $this->usar_tamano($in_tamano_grande);
            $this->url = $this->obtener_url($in_id, $in_caracteres_azar);
            $this->usar_tamano($in_tamano_chico);
            $this->img_class = $in_img_class;
            return $this->html();
        } catch (\Exception $e) {
            $mensaje = new MensajeWeb($e->getMessage());
            return $mensaje->html('Error');
        }
    } // imagen_ampliable_html

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Obtener URL a la imagen
        try {
            $imagen_url = $this->obtener_url();
        } catch (\Exception $e) {
            $mensaje = new MensajeWeb($e->getMessage());
            return $mensaje->html('Error');
        }
        // Tag imagen
        if ($this->img_class != '') {
            $imagen_tag = sprintf('<img class="%s" src="%s">', $this->img_class, $imagen_url);
        } else {
            $imagen_tag = sprintf('<img src="%s">', $imagen_url);
        }
        // Pie
        if (is_string($this->pie) && ($this->pie != '')) {
            if ($this->p_class != '') {
                $pie = sprintf('<p class="%s">%s</p>', $this->p_class, $this->pie);
            } else {
                $pie = sprintf('<p>%s</p>', $this->pie);
            }
        } else {
            $pie = '';
        }
        // Tag vínculo
        if (is_string($this->url) && ($this->url != '')) {
            if ($this->a_class != '') {
                $vinculo_tag = sprintf('<a class="%s" href="%s">%s%s</a>', $this->a_class, $this->url, $imagen_tag, $pie);
            } else {
                $vinculo_tag = sprintf('<a href="%s">%s%s</a>', $this->url, $imagen_tag, $pie);
            }
        } else {
            if ($this->a_class != '') {
                $vinculo_tag = sprintf('<a class="%s" href="#">%s%s</a>', $this->a_class, $imagen_tag, $pie);
            } else {
                $vinculo_tag = $imagen_tag.$pie;
            }
        }
        // Entregar
        return $vinculo_tag;
    } // html

    /**
     * Javascript
     *
     * @return string Código Javascript
     */
    public function javascript() {
        return false;
    } // javascript

} // Clase ImagenWeb

?>
