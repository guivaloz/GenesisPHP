<?php
/**
 * Icono HTML
 *
 * @package Tierra
 */

// NAMESPACE
namespace Base;

/**
 * Clase IconoHTML
 */
class IconoHTML {

    public $icono;                        // NOMBRE DEL ARCHIVO DEL ICONO SIN EXTENSION
    public $pie;                          // TEXTO A MOSTRAR DEBAJO DEL ICONO
    public $url          = '#';           // VINCULO A CREAR
    public $a_class      = 'thumbnail';   // CLASE CSS PARA EL TAG a
    public $img_class    = '';            // CLASE CSS PARA EL TAG img
    public $p_class      = 'text-center'; // CLASE CSS PARA EL TAG p
    public $icono_tamano = '48x48';       // TAMAÑO DEL ICONO

    /**
     * Constructor
     *
     * @param string Nombre del archivo del icono sin extensión
     * @param string Opcional, texto para poner debajo del icono
     * @param string Opcional, URL para crear un vínculo
     */
    public function __construct($in_icono, $in_pie=false, $in_url=false) {
        $this->icono = $in_icono;
        $this->pie   = $in_pie;
        $this->url   = $in_url;
    } // constructor

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // VALIDAR, NO ENTREGA NADA SI NO SE DEFINE EL ICONO
        if (!is_string($this->icono) || ($this->icono == '')) {
            return '';
        }
        // TAG IMAGEN
        $imagen_url = sprintf('imagenes/%s/%s.png', $this->icono_tamano, $this->icono);
        if ($this->img_class != '') {
            $imagen_tag = sprintf('<img class="%s" src="%s">', $this->img_class, $imagen_url);
        } else {
            $imagen_tag = sprintf('<img src="%s">', $imagen_url);
        }
        // PIE
        if (is_string($this->pie) && ($this->pie != '')) {
            if ($this->p_class != '') {
                if (is_string($this->url) && ($this->url != '')) {
                    $pie = sprintf('<p class="%s"><a href="%s">%s</a></p>', $this->p_class, $this->url, $this->pie);
                } else {
                    $pie = sprintf('<p class="%s">%s</p>', $this->p_class, $this->pie);
                }
            } else {
                $pie = sprintf('<p>%s</p>', $this->pie);
            }
        } else {
            $pie = '';
        }
        // TAG VINCULO
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
        // ENTREGAR
        return $vinculo_tag;
    } // html

    /**
     * JavaScript
     *
     * @return string HTML con el código JavaScript
     */
    public function javascript() {
        return false;
    } // javascript

} // Clase IconoHTML

?>
