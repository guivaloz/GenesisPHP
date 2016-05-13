<?php
/**
 * GenesisPHP - TemaIngresoHTML
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

namespace Base;

/**
 * Clase TemaIngresoHTML
 */
class TemaIngresoHTML extends Tema {

    // public $sistema;
    // public $titulo;
    // public $descripcion;
    // public $autor;
    // public $css;
    // public $favicon;
    // public $menu_principal_logo;
    // public $icono;
    // public $contenido;
    // public $javascript;
    // public $pie;
    // public $menu;
    public $modelo_ingreso_logos;

    /**
     * Header HTML
     *
     * @return string HTML
     */
    protected function header_html() {
        // En este arreglo acumulamos
        $a = array();
        // Acumular
        $a[] = '<!DOCTYPE html>';
        $a[] = '<html lang="es">';
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
        // Acumular Twitter Bootstrap
        $a[] = '  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">';
        // Archivo CSS propio de esta plantilla
        $a[] = '  <link href="css/plantilla-ingreso.css" rel="stylesheet" type="text/css">';
        if ($this->css != '') {
            $a[] = "  <link href=\"{$this->css}\" rel=\"stylesheet\" type=\"text/css\">";
        }
        $a[] = '</head>';
        // Inicia body
        $a[] = '<body>';
        // Entregar
        return implode("\n", $a);
    } // header_html

    /**
     * Footer HTML
     *
     * @return string HTML
     */
    protected function footer_html() {
        // En este arreglo acumulamos
        $a = array();
        // Acumular
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            $a[] = '  <!-- PIE -->';
            $a[] = '  <footer>';
            $a[] = implode("\n", $this->pie);
            $a[] = '  </footer>';
        } elseif (is_string($this->pie) && ($this->pie != '')) {
            $a[] = '  <!-- PIE -->';
            $a[] = '  <footer>';
            $a[] = $this->pie;
            $a[] = '  </footer>';
        }
        // Acumular JQuery
        $a[] = '  <script src="js/jquery.min.js"></script>';
        // Acumular Twitter Bootstrap
        $a[] = '  <script src="js/bootstrap.min.js"></script>';
        // Acumular Javascript que se haya agregado desde fuera
        if (is_array($this->javascript) && (count($this->javascript) > 0)) {
            $a[] = '<script>';
            $a[] = implode("\n", $this->javascript);
            $a[] = '</script>';
        } elseif (is_string($this->javascript) && ($this->javascript != '')) {
            $a[] = '<script>';
            $a[] = $this->javascript;
            $a[] = '</script>';
        }
        // Acumular cierre de body y html
        $a[] = '</body>';
        $a[] = '</html>';
        // Entregar
        return implode("\n", $a);
    } // footer_html

    /**
     * HTML
     *
     * @return string HTML con la pagina web
     */
    public function html() {
        // En este arreglo acumulamos
        $a = array();
        // Acumular header
        $a[] = $this->header_html();
        // Acumular interior
        $a[] = '<!-- CONTENIDO PLANTILLA INGRESO INICIA -->';
        $a[] = '  <div class="container">';
        $a[] = '    <div class="row">';
        // Primer columna
        $a[] = '      <div class="col-md-4">';
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
        $a[] = '      </div>';
        // Segunda columna
        $a[] = '      <div class="col-md-4">';
        $a[] = '        <form name="form" class="form-signin" method="post" action="index.php">';
        $a[] = "          <h2 class=\"form-signin-heading\">{$this->sistema}</h2>";
        $a[] = '          <input name="nom_corto" type="text" class="input-block-level" placeholder="nombre de usuario">';
        $a[] = '          <input name="contrasena" type="password" class="input-block-level" placeholder="contraseña">';
        if ((is_array($this->contenido)) && (count($this->contenido) > 0)) {
            foreach ($this->contenido as $c) {
                $a[] = "          <p>$c</p>";
            }
        } elseif (is_string($this->contenido)) {
            $a[] = "          <p>{$this->contenido}</p>";
        }
        $a[] = '          <button class="btn btn-large btn-primary" type="submit">Iniciar sesión</button>';
        $a[] = '        </form>';
        $a[] = '      </div>';
        // Tercer columna
        $a[] = '      <div class="col-md-4">';
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
        $a[] = '      </div>';
        // Cerrar columnas
        $a[] = '    </div>';
        $a[] = '  </div>';
        $a[] = '<!-- CONTENIDO PLANTILLA INGRESO TERMINA -->';
        // Acumular footer
        $a[] = $this->footer_html();
        // Entregar
        return implode("\n", $a);
    } // html

} // Clase TemaIngresoHTML

?>
