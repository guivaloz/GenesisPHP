<?php
/**
 * GenesisPHP - TemaWebIngreso
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
 * Clase TemaWebIngreso
 */
class TemaWebIngreso extends TemaWeb {

    // public $sistema;
    // public $titulo;
    // public $descripcion;
    // public $autor;
    // public $css;
    // public $css_comun;
    // public $favicon;
    // public $menu_principal_logo;
    // public $icono;
    // public $contenido;
    // public $javascript_comun;
    // public $pie;
    // public $menu;
    public $modelo_ingreso_logos;

    /**
     * Imágenes Izquierda HTML
     *
     * @return string Código HTML
     */
    protected function imagenes_izquierda_html() {
        $a = array();
        if (is_array($this->modelo_ingreso_logos)) {
            foreach ($this->modelo_ingreso_logos as $datos) {
                if ($datos['pos'] == 'izquierda') {
                    $img_tag = "<img src=\"{$datos['url']}\"";
                    if ($datos['class'] != '') {
                        $img_tag .= " class=\"{$datos['class']}\"";
                    }
                    $img_tag .= '>';
                    if ($datos['style'] != '') {
                        $a[] = "      <p style=\"{$datos['style']}\">$img_tag</p>";
                    } else {
                        $a[] = "      <p>$img_tag</p>";
                    }
                }
            }
        }
        return implode("\n", $a);
    } // imagenes_izquierda_html

    /**
     * Imágenes Derecha HTML
     *
     * @return string Código HTML
     */
    protected function imagenes_derecha_html() {
        $a = array();
        if (is_array($this->modelo_ingreso_logos)) {
            foreach ($this->modelo_ingreso_logos as $datos) {
                if ($datos['pos'] == 'derecha') {
                    $img_tag = "<img src=\"{$datos['url']}\"";
                    if ($datos['class'] != '') {
                        $img_tag .= " class=\"{$datos['class']}\"";
                    }
                    $img_tag .= '>';
                    if ($datos['style'] != '') {
                        $a[] = "      <p style=\"{$datos['style']}\">$img_tag</p>";
                    } else {
                        $a[] = "      <p>$img_tag</p>";
                    }
                }
            }
        }
        return implode("\n", $a);
    } // imagenes_derecha_html

    /**
     * Cabecera HTML
     *
     * @return string Código HTML
     */
    protected function cabecera_html() {
        // En este arreglo acumulamos
        $a = array();
        // Acumular
        $a[] = '<head>';
        $a[] = '  <meta charset="utf-8">';
        $a[] = '  <meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $a[] = '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        if ($this->descripcion != '') {
            $a[] = "  <meta name=\"description\" content=\"{$this->descripcion}\">";
        }
        if ($this->autor != '') {
            $a[] = "  <meta name=\"author\" content=\"{$this->autor}\">";
        }
        if ($this->favicon != '') {
            $a[] = "  <link rel=\"shortcut icon\" href=\"{$this->favicon}\">";
        } else {
            $a[] = '  <link rel="shortcut icon" href="favicon.ico">';
        }
        $a[] = "  <title>{$this->sistema}</title>";
        // Acumular CSS común definido en /Configuracion/PlantillaWebConfig
        if (is_array($this->css_comun) && (count($this->css_comun) > 0)) {
            $a[] = implode("\n", $this->css_comun);
        }
        // Acumular CSS requerido por GenesisPHP
        $a[] = '  <link href="css/datepicker.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">';
        // Acumular CSS propio de Ingreso
        $a[] = '  <link href="css/plantilla-ingreso.css" rel="stylesheet" type="text/css">';
        // Acumular CSS de esta página
        $a[] = $this->css;
        $a[] = '</head>';
        // Entregar
        return implode("\n", $a);
    } // cabecera_html

    /**
     * Final HTML
     *
     * @return string Código HTML
     */
    protected function final_html() {
        // En este arreglo acumulamos
        $a = array();
        // Acumular pie
        if (is_string($this->pie) && ($this->pie != '')) {
            $a[] = "  <footer>{$this->pie}</footer>";
        }
        // Acumular Javascript común definido en /Configuracion/PlantillaWebConfig
        if (is_array($this->javascript_comun) && (count($this->javascript_comun) > 0)) {
            $a[] = implode("\n", $this->javascript_comun);
        }
        // Acumular Javascript requerido por GenesisPHP
        $a[] = '  <script src="js/bootstrap-datepicker.js"></script>';
        $a[] = '  <script src="js/locales/bootstrap-datepicker.es.js"></script>';
        $a[] = '  <script src="js/bootstrap-datetimepicker.min.js"></script>';
        $a[] = '  <script src="js/locales/bootstrap-datetimepicker.es.js"></script>';
        // Acumular Javascript propio de Ingreso
        // Acumular Javascript de esta página
        if (is_string($this->javascript) && ($this->javascript != '')) {
            $a[] = $this->javascript;
        }
        // Entregar
        return implode("\n", $a);
    } // final_html

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // En este arreglo acumulamos
        $a = array();
        // Acumular
        $a[] = '<!DOCTYPE html>';
        $a[] = '<html lang="es">';
        $a[] = $this->cabecera_html();
        $a[] = '<body>';
        $a[] = '  <div class="container">';
        $a[] = '    <div class="row">';
        $a[] = '      <div class="col-md-4">';
        $a[] = $this->imagenes_izquierda_html();
        $a[] = '      </div>';
        $a[] = '      <div class="col-md-4">';
        $a[] = '        <form name="form" class="form-signin" method="post" action="index.php">';
        $a[] = "          <h2 class=\"form-signin-heading\">{$this->sistema}</h2>";
        $a[] = '          <input name="nom_corto" type="text" class="input-block-level" placeholder="nombre de usuario">';
        $a[] = '          <input name="contrasena" type="password" class="input-block-level" placeholder="contraseña">';
        $a[] = $this->contenido;
        $a[] = '          <button class="btn btn-large btn-primary" type="submit">Iniciar sesión</button>';
        $a[] = '        </form>';
        $a[] = '      </div>';
        $a[] = '      <div class="col-md-4">';
        $a[] = $this->imagenes_derecha_html();
        $a[] = '      </div>';
        $a[] = '    </div>';
        $a[] = '  </div>';
        $a[] = $this->final_html();
        $a[] = '</body>';
        $a[] = '</html>';
        // Entregar
        return implode("\n", $a);
    } // html

} // Clase TemaWebIngreso

?>
