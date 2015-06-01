<?php
/**
 * GenesisPHP - TemaDashboardHTML
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

// NAMESPACE
namespace Base;

/**
 * Clase TemaDashboardHTML
 */
class TemaDashboardHTML {

    public $sistema;
    public $titulo;
    public $descripcion;
    public $autor;
    public $css;
    public $favicon;
    public $menu_principal_logo;
    public $icono;                 // TEXTO, NOMBRE DEL ARCHIVO CON EL ICONO DE LA PAGINA
    public $contenido  = array();  // ARREGLO CON EL CONTENIDO
    public $javascript = array();  // ARREGLO, CODIGO JAVASCRIPT A AGREGAR AL FINAL DE LA PAGINA
    public $pie;
    public $menu;                  // INSTACIA DE MENU

    /**
     * Bloque HTML
     *
     * @param  mixed  Arreglo o texto con el contenido
     * @param  string Tag a poner antes y después del contenido
     * @return string Código HTML
     */
    protected function bloque_html($in_contenido, $in_tag) {
        // SI ES ARREGLO
        if (is_array($in_contenido)) {
            $a = array();
            // BUCLE PARA EVITAR LOS VALORES VACIOS
            foreach ($in_contenido as $c) {
                if (is_string($c) && ($c != '')) {
                    $a[] = $c;
                }
            }
            // ENTREGAR
            if (count($a)) {
                return "<$in_tag>\n".implode("\n", $a)."\n</$in_tag>";
            } else {
                return '';
            }
        } elseif (is_string($in_contenido) && ($in_contenido != '')) {
            return "<$in_tag>\n$in_contenido\n</$in_tag>";
        } else {
            return '';
        }
    } // bloque_html

    /**
     * Header HTML
     *
     * @return string HTML
     */
    protected function header_html() {
        // EN ESTE ARREGLO ACUMULAMOS
        $a = array();
        // ACUMULAR
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
        if ($this->titulo != '') {
            $a[] = "  <title>{$this->sistema} | {$this->titulo}</title>";
        } else {
            $a[] = "  <title>{$this->sistema}</title>";
        }
        // ARCHIVOS CSS COMUNES
        $a[] = '  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/datepicker.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/leaflet.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/leaflet.draw.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/morris.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/li-scroller.css" rel="stylesheet" type="text/css">';
        // ARCHIVO CSS PROPIO DE ESTA PLANTILLA
        $a[] = '  <link href="css/plantilla-dashboard.css" rel="stylesheet" type="text/css">';
        if ($this->css != '') {
            $a[] = "  <link href=\"{$this->css}\" rel=\"stylesheet\" type=\"text/css\">";
        }
        $a[] = '</head>';
        // INICIA BODY
        $a[] = '<body>';
        // ENTREGAR
        return implode("\n", $a);
    } // header_html

    /**
     * Footer HTML
     *
     * @return string HTML
     */
    protected function footer_html() {
        // EN ESTE ARREGLO ACUMULAMOS
        $a = array();
        // ACUMULAR PIE
        $a[] = $this->bloque_html($this->pie, 'footer');
        // ARCHIVOS EXTERNOS DE JAVASCRIPT
        $a[] = '  <script src="js/jquery.min.js"></script>';
        $a[] = '  <script src="js/bootstrap.min.js"></script>';
        $a[] = '  <script src="js/bootstrap-datepicker.js"></script>';
        $a[] = '  <script src="js/locales/bootstrap-datepicker.es.js"></script>';
        $a[] = '  <script src="js/bootstrap-datetimepicker.min.js"></script>';
        $a[] = '  <script src="js/locales/bootstrap-datetimepicker.es.js"></script>';
    //  $a[] = '  <script src="js/leaflet.js"></script>';
    //  $a[] = '  <script src="js/leaflet.draw.js"></script>';
        $a[] = '  <script src="js/raphael-min.js"></script>';
        $a[] = '  <script src="js/morris.min.js"></script>';
    //  $a[] = '  <script src="js/jquery.li-scroller.1.0.js"></script>';
        // AGREGAR JS QUE SE HAYA AGREGADO DESDE FUERA
        $a[] = $this->bloque_html($this->javascript, 'script');
        // CIERRA BODY Y HTML
        $a[] = '</body>';
        $a[] = '</html>';
        // ENTREGAR
        return implode("\n", $a);
    } // footer_html

    /**
     * Menu Principal HTML
     *
     * @return string HTML
     */
    protected function menu_principal_html() {
        // DE INICIO NO HAY OPCIONES DEL LADO DERECHO
        $hay_en_la_derecha = false;
        // EN ESTE ARREGLO ACUMULAMOS
        $a = array();
        // ACUMULAR
        $a[] = '<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu-principal">';
        $a[] = '  <div class="container-fluid">';
        // NAVBAR-HEADER / BRAND / COLLAPSE
        $a[] = '    <div class="navbar-header">';
        $a[] = '      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-principal-collapse">';
        $a[] = '        <span class="sr-only">Toggle navigation</span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '      </button>';
        if ($this->menu_principal_logo != '') {
            $a[] = "      <a class=\"navbar-brand\" href=\"index.php\"><img src=\"{$this->menu_principal_logo}\" alt=\"{$this->sistema}\"></a>";
        } else {
            $a[] = "      <a class=\"navbar-brand\" href=\"index.php\">{$this->sistema}</a>";
        }
        $a[] = '    </div>';
        // MENU
        $a[] = '    <div class="navbar-collapse collapse" id="menu-principal-collapse">';
        $a[] = '      <ul class="nav navbar-nav">';
        // MENU OPCIONES DEL LADO IZQUIERDO
        foreach ($this->menu->opciones_menu_principal() as $opcion) {
            if ($opcion['posicion'] == 'izquierda') {
                if (strpos($opcion['icono'], 'glyphicon') === 0) {
                    $icono = "<span class=\"{$opcion['icono']}\"></span>";
                } elseif ($opcion['icono'] != '') {
                    $icono = "<img src=\"imagenes/16x16/{$opcion['icono']}\">";
                } elseif ($opcion['etiqueta'] == '') {
                    $icono = "<span class=\"glyphicon glyphicon-folder-close\"></span>";
                }
                $mostrar = "$icono {$opcion['etiqueta']}";
                if ($opcion['activo'] == true) {
                    $a[] = "        <li class=\"active\"><a href=\"{$opcion['url']}\">$mostrar</a></li>";
                } else {
                    $a[] = "        <li><a href=\"{$opcion['url']}\">$mostrar</a></li>";
                }
            }
            if ($opcion['posicion'] == 'derecha') {
                $hay_en_la_derecha = true;
            }
        }
        $a[] = '      </ul>';
        // MENU OPCIONES DEL LADO DERECHO
        if ($hay_en_la_derecha) {
            $a[] = '      <ul class="nav navbar-nav navbar-right">';
            foreach ($this->menu->opciones_menu_principal() as $opcion) {
                if ($opcion['posicion'] == 'derecha') {
                    if (strpos($opcion['icono'], 'glyphicon') === 0) {
                        $icono = "<span class=\"{$opcion['icono']}\"></span>";
                    } elseif ($opcion['icono'] != '') {
                        $icono = "<img src=\"imagenes/16x16/{$opcion['icono']}\">";
                    } elseif ($opcion['etiqueta'] == '') {
                        $icono = "<span class=\"glyphicon glyphicon-folder-close\"></span>";
                    }
                    $mostrar = "$icono {$opcion['etiqueta']}";
                    if ($opcion['activo'] == true) {
                        $a[] = "        <li class=\"active\"><a href=\"{$opcion['url']}\">$mostrar</a></li>";
                    } else {
                        $a[] = "        <li><a href=\"{$opcion['url']}\">$mostrar</a></li>";
                    }
                }
            }
            $a[] = '      </ul>';
        }
        $a[] = '    </div>';
        // CERRAR TAGS
        $a[] = '  </div>';
        $a[] = '</nav>';
        // ENTREGAR
        return implode("\n", $a);
    } // menu_principal_html

    /**
     * Menu Secundario HTML
     *
     * @return string HTML
     */
    protected function menu_secundario_html() {
        // EN ESTE ARREGLO ACUMULAMOS
        $a = array();
        $a[] = '<ul class="nav nav-menu-secundario">';
        foreach ($this->menu->opciones_menu_secundario() as $opcion) {
            if (strpos($opcion['icono'], 'glyphicon') === 0) {
                $icono = "<span class=\"{$opcion['icono']}\"></span>";
            } elseif ($opcion['icono'] != '') {
                $icono = "<img src=\"imagenes/24x24/{$opcion['icono']}\">";
            } elseif ($opcion['etiqueta'] == '') {
                $icono = "<span class=\"glyphicon glyphicon-folder-close\"></span>";
            }
            $mostrar = "$icono {$opcion['etiqueta']}";
            if ($opcion['url'] != '') {
                $vinculo = "<a href=\"{$opcion['url']}\">$mostrar</a>";
            } else {
                $vinculo = $mostrar;
            }
            if ($opcion['activo'] == true) {
                $a[] = "  <li class=\"active\">$vinculo</li>";
            } else {
                $a[] = "  <li>$vinculo</li>";
            }
        }
        $a[] = '</ul>';
        // ENTREGAR
        return implode("\n", $a);
    } // menu_secundario_html

    /**
     * HTML
     *
     * @return string HTML con la pagina web
     */
    public function html() {
        // SI ESTA DEFINIDO EL MENU
        if (is_object($this->menu)) {
            // SI EL TITULO NO ESTA DEFINIDO SE TOMA DEL MENU
            if ($this->titulo == '') {
                $this->titulo = $this->menu->titulo_en();
            }
            // SI EL ICONO NO ESTA DEFINIDO SE TOMA DEL MENU
            if ($this->icono == '') {
                $this->icono = $this->menu->icono_en();
            }
        }
        // EVITAR QUE SE GUARDE EN EL CACHE DEL NAVEGADOR
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // EN ESTE ARREGLO ACUMULAMOS
        $a = array();
        // ACUMULAR
        $a[] = $this->header_html();
        $a[] = $this->menu_principal_html();
        $a[] = '  <div class="container-fluid">';
        $a[] = '    <div class="row">';
        $a[] = '      <div class="col-sm-3 col-md-2 menu-secundario">';
        $a[] = $this->menu_secundario_html();
        $a[] = '      </div>';
        $a[] = '      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 contenido">';
        if ($this->icono != '') {
            if (strpos($this->icono, 'glyphicon') === 0) {
                $icono = "<span class=\"{$this->icono}\"></span>";
            } else {
                $icono = "<img src=\"imagenes/48x48/{$this->icono}\">";
            }
            $a[] = "        <h1 class=\"page-header titulo\">$icono {$this->titulo}</h1>";
        } else {
            $a[] = "        <h1 class=\"page-header titulo\">{$this->titulo}</h1>";
        }
        $a[] = $this->bloque_html($this->contenido, 'div');
        $a[] = '      </div>';
        $a[] = '    </div>'; // row
        $a[] = '  </div>'; // container-fluid
        $a[] = $this->footer_html();
        // ENTREGAR
        return implode("\n", $a);
    } // html

} // Clase TemaDashboardHTML

?>
