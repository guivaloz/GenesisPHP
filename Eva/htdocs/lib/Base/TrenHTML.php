<?php
/**
 * GenesisPHP - TrenHTML
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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
 * Clase TrenHTML
 */
class TrenHTML {

    public $encabezado;              // Opcional, texto para el encabezado
    public $icono;                   // Opcional, URL al icono
    public $barra;                   // Opcional, instancia de BarraHTML
    public $vagones;                 // Arreglo de objetos que tengan el metodo html
    public $div_class     = 'tren';  // Clase css para div
    public $columnas      = 4;       // Cantidad de columnas, si es cero todo se acomodará en un renglón
    protected $cabeza     = array(); // Arreglo de objetos o de códigos HTML a agregar al principio con el metodo al_principio
    protected $pie        = array(); // Arreglo de objetos o de codigos HTML a agregar al final     con el metodo al_final
    protected $javascript = array(); // Arreglo, Javascript a colocar al final de la página

    /**
     * Constructor
     *
     * @param array Opcional, vagones es un arreglo de objetos con un método html
     */
    public function __construct($in_vagones=false) {
        if (is_array($in_vagones)) {
            $this->vagones = $in_vagones;
        }
    } // constructor

    /**
     * Al Principio
     *
     * Agregar un objeto o código HTML para ponerlo al principio
     *
     * @param mixed Objeto o Código HTML
     */
    public function al_principio($in) {
        if (is_string($in) && ($in != '')) {
            $this->cabeza[] = $in;
        } elseif (is_object($in)) {
            $this->cabeza[] = $in;
        }
    } // al_principio

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
     * Validar
     */
    protected function validar() {
        // Si vagones no es un arreglo
        if (!is_array($this->vagones)) {
            throw new ListadoExceptionValidacion('Error en TrenHTML: El parámetro "vagones" para el "tren" NO es un arreglo.');
        }
        // Si vagones es un arreglo sin elementos, mensaje de aviso
        if (count($this->vagones) == 0) {
            throw new ListadoExceptionValidacion('Error en TrenHTML: No hay registros.');
        }
        // Validar cada vagon
        foreach ($this->vagones as $objeto) {
            // Validar que sea un objeto
            if (!is_object($objeto)) {
                throw new ListadoExceptionValidacion('Error en TrenHTML: Un "vagón" no es un objeto.');
            }
            // Validar que tenga el metodo html
            if (!method_exists($objeto, 'html')) {
                throw new ListadoExceptionValidacion('Error en TrenHTML: El "vagón" no tiene el método html.');
            }
        }
    } // validar

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Si está definida la barra, se ponen en blanco las propiedades encabezado e icono
        if (is_object($this->barra) && ($this->barra instanceof BarraHTML)) {
            $this->encabezado = '';
            $this->icono      = '';
        }
        // Validar
        try {
            $this->validar();
        } catch (\Exception $e) {
            $mensaje = new MensajeHTML($e->getMessage());
            return $mensaje->html($this->encabezado);
        }
        // En este arreglo acumularemos el html
        $a   = array();
        if ($this->div_class != '') {
            $a[] = "<div class=\"{$this->div_class}\">";
        } else {
            $a[] = "<div>";
        }
        // Si la barra esta definida
        if (is_object($this->barra) && method_exists($this->barra, 'html')) {
            $a[] = $this->barra->html();
        } elseif ($this->encabezado != '') {
            // No esta definida la barra, entonces hacemos una
            $barra             = new BarraHTML();
            $barra->encabezado = $this->encabezado;
            $barra->icono      = $this->icono;
            $a[]               = $barra->html();
        }
        // Si hay algo en la cabeza se agregará al contenido
        if (is_array($this->cabeza) && (count($this->cabeza) > 0)) {
            foreach ($this->cabeza as $c) {
                if (is_object($c) && method_exists($c, 'html')) {
                    $a[] = $c->html();
                } elseif (is_string($c)) {
                    $a[] = $c;
                }
            }
        } elseif (is_string($this->cabeza) && ($this->cabeza != '')) {
            $a[] = $this->cabeza;
        }
        // Determinar clase para la columna
        switch ($this->columnas) {
            case 1:
                $columna_class  = 'col-md-12';
                break;
            case 2:
                $columna_class  = 'col-md-6';
                break;
            case 3:
                $columna_class  = 'col-md-4';
                break;
            case 4:
                $columna_class  = 'col-md-3';
                break;
            case 5:
                $this->columnas = 4;
                $columna_class  = 'col-md-3';
                break;
            case 6:
                $columna_class  = 'col-md-2';
                break;
            default:
                $this->columnas = 12;
                $columna_class  = 'col-md-1';
        }
        // Vagones, ejecutar el metodo html en cada uno
        $c = 0;
        foreach ($this->vagones as $objeto) {
            // Renglon inicia
            if ($c == 0) {
                $a[] = '  <div class="row">';
            }
            // Columna inicia
            $a[] = "    <div class=\"{$columna_class}\">";
            // Agregar
            $a[] = '      '.$objeto->html();
            // columna termina
            $a[] = '    </div>';
            $c++;
            // Renglon termina
            if (($this->columnas > 0) && ($c == $this->columnas)) {
                $a[] = '  </div>';
                $c   = 0;
            }
        }
        if ($c > 0) {
            // Renglon cerrar
            $a[] = '  </div>';
        }
        // Si hay algo en el pie se agregará al contenido
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p) && method_exists($p, 'html')) {
                    $a[] = $p->html();
                } elseif (is_string($p)) {
                    $a[] = $p;
                }
            }
        } elseif (is_string($this->pie) && ($this->pie != '')) {
            $a[] = $this->pie;
        }
        // Terminar tren
        $a[] = '</div>';
        // Entregar
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        // Si hay Javascript en los objetos de la cabeza
        if (is_array($this->cabeza) && (count($this->cabeza) > 0)) {
            foreach ($this->cabeza as $p) {
                if (is_object($p)) {
                    $this->javascript[] = $p->javascript();
                }
            }
        }
        // Si hay Javascript en los objetos del pie
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p)) {
                    $this->javascript[] = $p->javascript();
                }
            }
        }
        // Entregar
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

} // Clase TrenHTML

?>
