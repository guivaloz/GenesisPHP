<?php
/**
 * GenesisPHP - TemaImpresoraBlancoNegro
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
 * Clase TemaImpresoraBlancoNegro
 */
class TemaImpresoraBlancoNegro extends TemaImpresora {

    // public $sistema;
    // public $titulo;
    // public $css;
    // public $contenido;
    // public $javascript;

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
        if (($this->titulo != '') && ($this->sistema != '')) {
            $a[] = sprintf('  <title>%s - %s</title>', UtileriasParaFormatos::formato_contenido($this->titulo), UtileriasParaFormatos::formato_contenido($this->sistema));
        } elseif ($this->titulo != '') {
            $a[] = sprintf('  <title>%s</title>', UtileriasParaFormatos::formato_contenido($this->titulo));
        } elseif ($this->sistema != '') {
            $a[] = sprintf('  <title>Sin título - %s</title>', UtileriasParaFormatos::formato_contenido($this->sistema));
        } else {
            $a[] = '  <title>GenesisPHP</title>';
        }
        // Acumular CSS propio
        if ($this->css != '') {
            $a[] = "  <link href=\"{$this->css}\" rel=\"stylesheet\" type=\"text/css\" media=\"print\">";
        }
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
        // En este arreglo acumularemos la entrega
        $a = array();
        // Acumular JQuery
        $a[] = '<script src="js/jquery.min.js"></script>';
        // Acumular Javascript
        if (is_array($this->javascript) && (count($this->javascript) > 0)) {
            $b = array();
            foreach ($this->javascript as $js) {
                if (is_string($js) && (trim($js) != '')) {
                    $b[] = $js;
                }
            }
            if (count($b) > 0) {
                $a[] = "<script>\n".implode("\n", $b)."\n</script>";
            }
        } elseif (is_string($this->javascript) && (trim($this->javascript) != '')) {
            $a[] = "<script>\n{$this->javascript}\n</script>";
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
        if (is_array($this->contenido) && (count($this->contenido) > 0)) {
            $b = array();
            foreach ($this->contenido as $js) {
                if (is_string($js) && (trim($js) != '')) {
                    $b[] = $js;
                }
            }
            if (count($b) > 0) {
                $a[] = implode("\n", $b);
            }
        } elseif (is_string($this->contenido) && (trim($this->contenido) != '')) {
            $a[] = $this->contenido;
        }
        $a[] = $this->final_html();
        $a[] = '</body>';
        $a[] = '</html>';
        // Entregar
        return implode("\n", $a)."\n";
    } // html

} // Clase TemaImpresoraBlancoNegro

?>
