<?php
/**
 * GenesisPHP - TemaWebSimple
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
 * Clase TemaWebSimple
 */
class TemaWebSimple extends TemaWeb {

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

    /**
     * Menu Principal HTML
     *
     * @return string Código HTML
     */
    protected function menu_principal_html() {
        // De inicio no hay opciones del lado derecho
        $hay_en_la_derecha = false;
        // En este arreglo acumulamos
        $a = array();
        // Acumular
        $a[] = '<!-- MENU PRINCIPAL INICIA -->';
        $a[] = '<nav class="navbar navbar-static-top" role="navigation" id="menu-principal">';
        $a[] = '  <div class="container-fluid">';
        // Navbar-header / brand / collapse
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
        // Menu
        $a[] = '    <div class="navbar-collapse collapse" id="menu-principal-collapse">';
        $a[] = '      <ul class="nav navbar-nav">';
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
        // Cerrar tags
        $a[] = '  </div>';
        $a[] = '</nav>';
        $a[] = '<!-- MENU PRINCIPAL TERMINA -->';
        // Entregar
        return implode("\n", $a);
    } // menu_principal_html

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
        if ($this->titulo != '') {
            $a[] = "  <title>{$this->sistema} | {$this->titulo}</title>";
        } else {
            $a[] = "  <title>{$this->sistema}</title>";
        }
        // Acumular CSS común definido en /Configuracion/PlantillaWebConfig
        if (is_array($this->css_comun) && (count($this->css_comun) > 0)) {
            $a[] = implode("\n", $this->css_comun);
        }
        // Acumular CSS requerido por GenesisPHP
        $a[] = '  <link href="css/datepicker.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">';
        // Acumular CSS propio de Simple
        $a[] = '  <link href="css/plantilla-simple.css" rel="stylesheet" type="text/css">';
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
        // Acumular Javascript propio de Simple
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
        // Si esta definido el menu, se toma el título y el ícono de éste
        if (is_object($this->menu)) {
            if ($this->titulo == '') {
                $this->titulo = $this->menu->titulo_en();
            }
            if ($this->icono == '') {
                $this->icono = $this->menu->icono_en();
            }
        }
        // En este arreglo acumulamos
        $a = array();
        // Acumular
        $a[] = '<!DOCTYPE html>';
        $a[] = '<html lang="es">';
        $a[] = $this->cabecera_html();
        $a[] = '<body>';
        $a[] = $this->menu_principal_html();
        $a[] = '  <div class="container">';
        $a[] = $this->contenido;
        $a[] = '  </div>';
        $a[] = $this->final_html();
        $a[] = '</body>';
        $a[] = '</html>';
        // Entregar
        return implode("\n", $a);
    } // html

} // Clase TemaWebSimple

?>
