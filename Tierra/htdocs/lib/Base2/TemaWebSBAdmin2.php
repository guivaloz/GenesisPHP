<?php
/**
 * GenesisPHP - TemaWebSBAdmin2
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
 * Clase TemaWebSBAdmin2
 */
class TemaWebSBAdmin2 extends TemaWeb {

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
     * Navegacion vinculo HTML
     *
     * @param  array  Arreglo asociativo con los datos
     * @return string Código HTML
     */
    protected function navegacion_vinculo_html($datos) {
        // Definir el icono
        if ($datos['icono'] != '') {
            if (strpos($datos['icono'], 'glyphicon') === 0) {
                $icono = sprintf('<span class="navegacion-icono"><i class="%s"></i></span>', $datos['icono']);
            } else {
                $icono = sprintf('<span class="navegacion-icono"><img src="imagenes/16x16/%s"></span>', $datos['icono']);
            }
        } else {
            $icono = '<span class="glyphicon glyphicon-folder-close"></span>';
        }
        // Definir lo que se va a mostrar
        $mostrar = sprintf('%s %s', $icono, UtileriasParaFormatos::formato_contenido($datos['etiqueta']));
        // Entregar
        return sprintf('<a href="%s">%s</a>', $datos['url'], $mostrar);
    } // navegacion_vinculo_html

    /**
     * Navegación HTML
     *
     * @return string Código HTML
     */
    protected function navegacion_html() {
        // Validar Menu
        if (!($this->menu instanceof \Inicio\Menu)) {
            die('Error en TemaSBAdmin2: La propiedad menu no es instancia de Inicio Menu.');
        }
        // En este arreglo acumularemos la entrega
        $a = array();
        // Acumular
        $a[] = '  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">';
        $a[] = '    <div class="navbar-header">';
        // Acumular menu hamburguesa
        $a[] = '      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">';
        $a[] = '        <span class="sr-only">Toggle navigation</span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '      </button>';
        // Acumular branding
        if (($this->menu_principal_logo != '') && ($this->sistema != '')) {
            $a[] = sprintf('      <a class="navbar-brand" href="index.php"><img class="navbar-brand-img" src="%s" alt="%s"></a>', $this->menu_principal_logo, UtileriasParaFormatos::formato_contenido($this->sistema));
        } elseif ($this->sistema != '') {
            $a[] = sprintf('      <a class="navbar-brand" href="index.php">%s</a>', $this->sistema);
        } else {
            $a[] = '      <a class="navbar-brand" href="index.html">GenesisPHP</a>';
        }
        $a[] = '    </div>'; // navbar-header
        // En este arreglo acumularemos las opciones del menu principal
        $principal = array();
        // Acumular menu secundario
        $a[] = '    <ul class="nav navbar-top-links navbar-right">';
        foreach ($this->menu->opciones_menu() as $primero_clave => $primero_datos) {
            // Si es para el menu principal
            if ($primero_datos['posicion'] == 'izquierda') {
                $principal[$primero_clave] = $primero_datos;
                continue;
            }
            // Sin segundo nivel
            if ($primero_datos['activo']) {
                $a[] = sprintf('      <li class="active">%s</li>', $this->navegacion_vinculo_html($primero_datos));
            } else {
                $a[] = sprintf('      <li>%s</li>', $this->navegacion_vinculo_html($primero_datos));
            }
        }
        $a[] = '    </ul>'; // nav navbar-top-links navbar-right
        // Acumular menú izquierdo
        $a[] = '    <div class="navbar-default sidebar" role="navigation">';
        $a[] = '      <div class="sidebar-nav navbar-collapse">';
        $a[] = '        <ul class="nav" id="side-menu">';
        // Acumular buscador
        //~ $a[] = '        <li class="sidebar-search">';
        //~ $a[] = '          <div class="input-group custom-search-form">';
        //~ $a[] = '            <input type="text" class="form-control" placeholder="Buscar...">';
        //~ $a[] = '              <span class="input-group-btn">';
        //~ $a[] = '                <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>';
        //~ $a[] = '              </span>';
        //~ $a[] = '          </div>';
        //~ $a[] = '        </li>';
        // En este arreglo acumularemos las opciones para el otro menu
        $derecha = array();
        // Bucle por el primer nivel del menu
        foreach ($principal as $primero_clave => $primero_datos) {
            // Si los datos es un arreglo
            if (is_array($primero_datos['segundo']) && (count($primero_datos['segundo']) > 0)) {
                // Inicio tag del primero
                if ($primero_datos['activo']) {
                    $a[] = sprintf('          <li class="active">');
                } else {
                    $a[] = sprintf('          <li>');
                }
                // Icono y etiqueta del primero
                if (strpos($primero_datos['icono'], 'glyphicon') === 0) {
                    $icono = sprintf('<span class="navegacion-icono"><i class="%s"></i></span>', $primero_datos['icono']);
                } else {
                    $icono = sprintf('<span class="navegacion-icono"><img src="imagenes/16x16/%s"></span>', $primero_datos['icono']);
                }
                $a[] = sprintf('            <a href="#">%s %s<span class="fa arrow"></span></a>', $icono, UtileriasParaFormatos::formato_contenido($primero_datos['etiqueta']));
                $a[] = '            <ul class="nav nav-second-level">';
                // Bucle por el segundo nivel del menu
                foreach ($primero_datos['segundo'] as $segundo_clave => $segundo_datos) {
                    if ($segundo_datos['activo']) {
                        $a[] = sprintf('              <li class="active">%s</li>', $this->navegacion_vinculo_html($segundo_datos));
                    } else {
                        $a[] = sprintf('              <li>%s</li>', $this->navegacion_vinculo_html($segundo_datos));
                    }
                }
                // Termina tag del primero
                $a[] = '            </ul>';
                $a[] = '          </li>';
            } else {
                // No tiene segundo nivel
                if ($primero_datos['activo']) {
                    $a[] = sprintf('          <li class="active">%s</li>', $this->navegacion_vinculo_html($primero_datos));
                } else {
                    $a[] = sprintf('          <li>%s</li>', $this->navegacion_vinculo_html($primero_datos));
                }
            }
        }
        // Cerrar
        $a[] = '        </ul>'; // class nav
        $a[] = '      </div>';  // class sidebar-nav navbar-collapse
        $a[] = '    </div>';    // class navbar-default sidebar
        $a[] = '  </nav>';      // nav role navigation
        // Entregar
        return implode("\n", $a);
    } // navegacion_html

    /**
     * Titulo HTML
     *
     * @return string Código HTML
     */
    protected function titulo_html() {
        // Si esta definido el menu, se toman el título e ícono de éste
        if ($this->menu instanceof \Inicio\Menu) {
            if ($this->titulo == '') {
                $this->titulo = $this->menu->titulo_en();
            }
            if ($this->icono == '') {
                $this->icono = $this->menu->icono_en();
            }
        }
        // Si hay icono
        if ($this->icono != '') {
            if (strpos($this->icono, 'glyphicon') === 0) {
                $icono = "<span class=\"{$this->icono}\"></span>";
            } else {
                $icono = "<img src=\"imagenes/48x48/{$this->icono}\">";
            }
            return "    <h1 class=\"page-header titulo\">$icono {$this->titulo}</h1>";
        } else {
            // Sin icono
            return "    <h1 class=\"page-header titulo\">{$this->titulo}</h1>";
        }
    } // titulo_html

    /**
     * Cabecera HTML
     *
     * @return string Código HTML
     */
    protected function cabecera_html() {
        // En este arreglo acumularemos la entrega
        $a = array();
        // Acumular
        $a[] = '<head>';
        $a[] = '  <meta charset="utf-8">';
        $a[] = '  <meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $a[] = '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        if ($this->descripcion != '') {
            $a[] = sprintf('  <meta name="description" content="%s">', UtileriasParaFormatos::formato_contenido($this->descripcion));
        }
        if ($this->autor != '') {
            $a[] = sprintf('  <meta name="author" content="%s">', UtileriasParaFormatos::formato_contenido($this->autor));
        }
        if (($this->titulo != '') && ($this->sistema != '')) {
            $a[] = sprintf('  <title>%s - %s</title>', UtileriasParaFormatos::formato_contenido($this->titulo), UtileriasParaFormatos::formato_contenido($this->sistema));
        } elseif ($this->titulo != '') {
            $a[] = sprintf('  <title>%s</title>', UtileriasParaFormatos::formato_contenido($this->titulo));
        } elseif ($this->sistema != '') {
            $a[] = sprintf('  <title>Sin título - %s</title>', UtileriasParaFormatos::formato_contenido($this->sistema));
        } else {
            $a[] = '  <title>GenesisPHP</title>';
        }
        if ($this->favicon != '') {
            $a[] = "  <link rel=\"shortcut icon\" href=\"{$this->favicon}\">";
        } else {
            $a[] = '  <link rel="shortcut icon" href="favicon.ico">';
        }
        // Acumular CSS común definido en /Configuracion/PlantillaWebConfig
        if (is_array($this->css_comun) && (count($this->css_comun) > 0)) {
            $a[] = implode("\n", $this->css_comun);
        }
        // Acumular CSS requerido por GenesisPHP
        $a[] = '  <link href="css/datepicker.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">';
        // Acumular CSS propio de SBAdmin2
        $a[] = '  <link href="css/metisMenu.min.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/sb-admin-2.css" rel="stylesheet" type="text/css">';
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
        // Acumular Javascript propio de SBAdmin2
        $a[] = '  <script src="js/metisMenu.min.js"></script>';
        $a[] = '  <script src="js/sb-admin-2.js"></script>';
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
        // En este arreglo acumularemos la entrega
        $a = array();
        // Acumular
        $a[] = '<!DOCTYPE html>';
        $a[] = '<html lang="es">';
        $a[] = $this->cabecera_html();
        $a[] = '<body>';
        $a[] = '<div id="wrapper">';        // wrapper
        $a[] = $this->navegacion_html();
        $a[] = '  <div id="page-wrapper">'; // page-wrapper
        $a[] = $this->titulo_html();
        $a[] = $this->contenido;
        $a[] = '  </div>';                  // page-wrapper
        $a[] = '</div>';                    // wrapper
        $a[] = $this->final_html();
        $a[] = '</body>';
        $a[] = '</html>';
        // Entregar
        return implode("\n", $a)."\n";
    } // html

} // Clase TemaWebSBAdmin2

?>
