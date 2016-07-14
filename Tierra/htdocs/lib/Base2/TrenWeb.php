<?php
/**
 * GenesisPHP - TrenWeb
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
 * Clase TrenWeb
 */
class TrenWeb implements SalidaWeb {

    public $encabezado;              // Opcional, texto para el encabezado
    public $icono;                   // Opcional, URL al icono
    public $barra;                   // Opcional, instancia de BarraWeb
    public $vagones       = array(); // Arreglo con instancias, que tengan el método html
    public $div_class     = 'tren';  // Clase CSS para div
    public $columnas      = 4;       // Cantidad de columnas, si es cero todo se acomoda en un sólo renglón
    protected $cabeza     = array(); // Arreglo con instancias o de códigos HTML a agregar al principio con el metodo al_principio
    protected $pie        = array(); // Arreglo con instancias o de codigos HTML a agregar al final     con el metodo al_final

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
        if (!is_array($this->vagones)) {
            throw new \Exception("Error: El parámetro vagones para el tren NO es un arreglo.");
        }
        if (count($this->vagones) == 0) {
            throw new \Exception("Aviso: No hay registros.");
        }
        foreach ($this->vagones as $objeto) {
            if (!is_object($objeto)) {
                throw new \Exception("Error: Un vagón no es un objeto.");
            }
            if (!method_exists($objeto, 'html')) {
                throw new \Exception("Error: El vagón no tiene el método html.");
            }
        }
    } // validar

    /**
     * Elaborar parte inicial
     *
     * @return string HTML
     */
    protected function elaborar_parte_inicial() {
        // Acumularemos la entrega en este arreglo
        $a = array();
        if ($this->div_class != '') {
            $a[] = "<div class=\"{$this->div_class}\">";
        } else {
            $a[] = "<div>";
        }
        // Si la barra está definida
        if (is_object($this->barra) && ($this->barra instanceof BarraWeb)) {
            $a[] = $this->barra->html();
        } elseif ($this->encabezado != '') {
            $this->barra             = new BarraWeb();
            $this->barra->encabezado = $this->encabezado;
            $this->barra->icono      = $this->icono;
            $a[]                     = $this->barra->html();
        }
        // Si hay algo en la cabeza se agregará al contenido
        if (is_array($this->cabeza) && (count($this->cabeza) > 0)) {
            foreach ($this->cabeza as $c) {
                if (is_object($c) && ($c instanceof SalidaWeb)) {
                    $a[] = $c->html();
                } elseif (is_string($c)) {
                    $a[] = $c;
                }
            }
        } elseif (is_string($this->cabeza) && ($this->cabeza != '')) {
            $a[] = $this->cabeza;
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_parte_inicial

    /**
     * Elaborar parte contenido
     *
     * @return string HTML
     */
    protected function elaborar_parte_contenido() {
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
        // Acumularemos la entrega en este arreglo
        $a = array();
        $c = 0;
        // Acumular el código HTML de los vagones
        foreach ($this->vagones as $objeto) {
            if ($c == 0) {
                $a[] = '  <div class="row">'; // Renglón inicia
            }
            $a[] = "    <div class=\"{$columna_class}\">"; // Columna inicia
            $a[] = '      '.$objeto->html(); // Agregar vagón
            $a[] = '    </div>'; // Columna termina
            $c++;
            if (($this->columnas > 0) && ($c == $this->columnas)) {
                $a[] = '  </div>'; // Renglón termina
                $c   = 0;
            }
        }
        if ($c > 0) {
            $a[] = '  </div>'; // Renglón cerrar
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_parte_contenido

    /**
     * Elaborar parte final
     *
     * @return string HTML
     */
    protected function elaborar_parte_final() {
        // Acumularemos la entrega en este arreglo
        $a = array();
        // Si hay algo en el pie se agregará al contenido
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p) && ($p instanceof SalidaWeb)) {
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
    } // elaborar_parte_final

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
        // Validar
        try {
            $this->validar();
        } catch (\Exception $e) {
            $mensaje = new MensajeWeb($e->getMessage());
            return $mensaje->html($this->encabezado);
        }
        // Acumularemos la entrega en este arreglo
        $a   = array();
        $a[] = $this->elaborar_parte_inicial();
        $a[] = $this->elaborar_parte_contenido();
        $a[] = $this->elaborar_parte_final();
        // Entregar
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Código Javascript
     */
    public function javascript() {
        // En este arreglo acumularemos lo que se va a entregar
        $a = array();
        // Si hay javascript en la BarraWeb
        if (is_object($this->barra) && ($this->barra instanceof BarraWeb)) {
            $a[] = $this->barra->javascript();
        }
        // Si hay Javascript en los objetos de la cabeza
        if (is_array($this->cabeza) && (count($this->cabeza) > 0)) {
            foreach ($this->cabeza as $c) {
                if (is_object($c) && ($c instanceof SalidaWeb)) {
                    $a[] = $c->javascript();
                }
            }
        }
        // Si hay Javascript en los objetos del pie
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p) && ($p instanceof SalidaWeb)) {
                    $a[] = $p->javascript();
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // javascript

} // Clase TrenWeb

?>
