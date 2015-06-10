<?php
/**
 * GenesisPHP - ListadoHTML
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

namespace Base;

/**
 * Clase ListadoHTML
 */
class ListadoHTML {

    public $encabezado          = false;   // Si es falso no habra encabezado
    public $icono;                         // Opcional, archivo de icono en el directorio imagenes
    public $barra;                         // Opcional, instacia de BarraHTML
    public $estructura;                    // Arreglo asociativo con la estructura del listado
    public $listado             = array(); // Arreglo, resultado de la consulta
    public $panal               = array();
    protected $cabeza           = array(); // Arreglo de objetos o de códigos HTML a agregar al principio con el metodo al_principio
    protected $pie              = array(); // Arreglo de objetos o de codigos HTML a agregar al final     con el metodo al_final
    protected $javascript       = array(); // Arreglo, Javascript a colocar al final de la página
    static public $icono_tamano = '24x24';

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
        if (!is_array($this->estructura)) {
            throw new ListadoExceptionValidacion('Error en ListadoHTML: La estructura es incorrecta.');
        }
        if (count($this->estructura) == 0) {
            throw new ListadoExceptionValidacion('Error en ListadoHTML: La estructura está vacía.');
        }
        if (!is_array($this->listado)) {
            throw new ListadoExceptionValidacion('Error en ListadoHTML: El listado es incorrecto.');
        }
        if (!is_array($this->panal)) {
            throw new ListadoExceptionValidacion('Error en ListadoHTML: El panal es incorrecto.');
        }
        if ((count($this->listado) == 0) && (count($this->panal) == 0)) {
            throw new ListadoExceptionValidacion('Aviso en ListadoHTML: No hay registros a mostrar.');
        }
    } // validar

    /**
     * Elaborar Tabla Inicio
     */
    protected function elaborar_tabla_inicio() {
        // Acumularemos la entrega en este arreglo
        $a   = array();
        $a[] = '<!-- LISTADO INICIA -->';
        $a[] = '<div class="listado">';
        // Si la barra esta definida
        if (is_object($this->barra)) {
            $a[]                = $this->barra->html();
            $this->javascript[] = $this->barra->javascript();
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
                if (is_object($c)) {
                    $a[] = $c->html();
                } elseif (is_string($c)) {
                    $a[] = $c;
                }
            }
        } elseif (is_string($this->cabeza) && ($this->cabeza != '')) {
            $a[] = $this->cabeza;
        }
        // Tabla inicia
        $a[] = '<table class="table table-hover table-bordered listado-tabla">';
        // Tabla thead
        $a[] = '  <thead>';
        $a[] = '    <tr>';
        foreach ($this->estructura as $parametros) {
            if ($parametros['enca'] != '') {
                $a[] = sprintf('      <th>%s</th>', $parametros['enca']);
            } else {
                $a[] = '      <th>&nbsp;</th>';
            }
        }
        $a[] = '    </tr>';
        $a[] = '  </thead>';
        // Tabla tbody
        $a[] = '  <tbody>';
        // Entregar
        return implode("\n", $a);
    } // elaborar_tabla_inicio

    /**
     * Elaborar Tabla Contenido con Listado
     */
    protected function elaborar_tabla_contenido_con_listado() {
        // ACUMULAREMOS LA ENTREGA EN ESTE ARREGLO
        $a = array();
        // BUCLE POR FILAS
        foreach ($this->listado as $fila) {
            $a[] = '    <tr>';
            // BUCLE POR ESTRUCTURA
            foreach ($this->estructura as $columna => $parametros) {
                // CLASE CSS
                $c = array();
                if ($parametros['clase'] != '') {
                    $c[] = $parametros['clase']; // DEFINA EL ESTILO CSS EN plantilla-fluida.css
                }
                if ($parametros['formato'] != '') {
                    $c[] = $parametros['formato']; // DEFINA EL ESTILO CSS EN plantilla-fluida.css
                }
                if (is_array($parametros['colores']) && array_key_exists($parametros['color'], $fila)) {
                    $c[] = $parametros['colores'][$fila[$parametros['color']]]; // O ES EL COLOR O EL FORMATO
                }
                if (count($c) > 0) {
                    $celda_clase = sprintf(' class="%s"', implode(' ', $c));
                } else {
                    $celda_clase = '';
                }
                // VALOR DE LA CELDA, SI USA UN sprintf
                if (($parametros['sprintf'] != '') && ($fila[$columna] !== '')) {
                    $valor = sprintf($parametros['sprintf'], $fila[$columna]); // EJECUTA UN sprintf
                } elseif ($parametros['formato'] != '') {
                    // SI USA UN FORMATO
                    switch ($parametros['formato']) {
                        case 'fecha':
                            $valor = formato_fecha($fila[$columna]);
                            break;
                        case 'entero':
                            $valor = formato_entero($fila[$columna]);
                            break;
                        case 'dinero':
                            $valor = formato_dinero($fila[$columna]);
                            break;
                        case 'flotante':
                            $valor = formato_flotante($fila[$columna]);
                            break;
                        case 'porcentaje':
                            $valor = formato_porcentaje($fila[$columna]);
                            break;
                        default:
                            $valor = $fila[$columna]; // PASA IGUAL
                    }
                } else {
                    // PASA IGUAL
                    $valor = $fila[$columna];
                }
                // CAMBIAR UN CARACTER POR UNA DESCRIPCION O CORTAR LA CADENA DE TEXTO
                if (is_array($parametros['cambiar']) && ($valor != '')) {
                    $mostrar = $parametros['cambiar'][$valor];
                } elseif (($parametros['cortar'] > 0) && (strlen($valor) > $parametros['cortar'])) {
                    $mostrar = substr($valor, 0, $parametros['cortar']).'...';
                } else {
                    $mostrar = $valor;
                }
                // AGREGAMOS LA CELDA DE ACUERDO A...
                if ($mostrar == '') {
                    // SI HAY QUE A ELABORAR UN VINCULO PERO SIN CONTENIDO
                    if ($parametros['pag'] != '') {
                        // SI SE USO PARAM
                        if (is_array($parametros['param']) && (count($parametros['param']) > 0)) {
                            // VINCULO CON MUCHOS PARAMETROS, EJEMPLO pagina.php?param=123&var=567
                            $b = array();
                            foreach ($parametros['param'] as $param_var => $param_col) {
                                $b[] = sprintf('%s=%s', $param_var, urlencode($fila[$param_col]));
                            }
                            $a[] = sprintf('      <td%s><a href="%s?%s">VACIO</a></td>', $celda_clase, $parametros['pag'], implode('&', $b));
                        } elseif (($parametros['id'] != '') && array_key_exists($parametros['id'], $fila)) {
                            // VINCULO CON UN ID, EJEMPLO pagina.php?usuario=123
                            $a[] = sprintf('      <td%s><a href="%s?id=%s">VACIO</a></td>', $celda_clase, $parametros['pag'], $fila[$parametros['id']]);
                        } else {
                            // FALTARON LOS DATOS PARA HACER EL VINCULO
                            $a[] = "      <td{$celda_clase}>FALTO</td>";
                        }
                    } else {
                        // NO HAY QUE MOSTRAR, SERA UNA CELDA VACIA
                        $a[] = "      <td{$celda_clase}>&nbsp;</td>";
                    }
                } elseif ($parametros['pag'] != '') {
                    // HAY QUE A ELABORAR UN VINCULO
                    if (is_array($parametros['param']) && (count($parametros['param']) > 0)) {
                        // VINCULO CON MUCHOS PARAMETROS, EJEMLO pagina.php?param=123&var=567
                        $b = array();
                        foreach ($parametros['param'] as $param_var => $param_col) {
                            $b[] = sprintf('%s=%s', $param_var, urlencode($fila[$param_col]));
                        }
                        $a[] = sprintf('      <td%s><a href="%s?%s">%s</a></td>', $celda_clase, $parametros['pag'], implode('&', $b), $mostrar);
                    } elseif (($parametros['id'] != '') && array_key_exists($parametros['id'], $fila)) {
                        // VINCULO CON UN ID, EJEMPLO pagina.php?usuario=123
                        $a[] = sprintf('      <td%s><a href="%s?id=%s">%s</a></td>', $celda_clase, $parametros['pag'], $fila[$parametros['id']], $mostrar);
                    } elseif (array_key_exists('id', $fila)) {
                        // VINCULO CON ID, EJEMPLO pagina.php?id=123
                        $a[] = sprintf('      <td%s><a href="%s?id=%s">%s</a></td>', $celda_clase, $parametros['pag'], $fila['id'], $mostrar);
                    } else {
                        // NO ESTA DEFINIDO EL ID, SALE SIN VINCULO
                        $a[] = "      <td{$celda_clase}>{$mostrar}</td>";
                    }
                } else {
                    // LA CELDA SOLO MUESTRA EL CONTENIDO
                    $a[] = "      <td{$celda_clase}>{$mostrar}</td>";
                }
            }
            $a[] = '    </tr>';
        }
        // ENTREGAR
        return implode("\n", $a);
    } // elaborar_tabla_contenido_con_listado

    /**
     * Elaborar Tabla Contenido con Panal
     */
    protected function elaborar_tabla_contenido_con_panal() {
        // ACUMULAREMOS LA ENTREGA EN ESTE ARREGLO
        $a = array();
        // BUCLE POR FILAS
        foreach ($this->panal as $fila) {
            $a[] = '    <tr>';
            // BUCLE POR ESTRUCTURA
            $fila_celdas = array();
            foreach ($this->estructura as $clave => $parametros) {
                // SI ES INTANCIA DE Celda
                if (is_object($fila[$clave]) && ($fila[$clave] instanceof Celda)) {
                    // PASA IGUAL
                    $fila_celdas[$clave] = $fila[$clave];
                } else {
                    // CONVERTIR A INSTANCIA DE Celda
                    $celda = new Celda($fila[$clave]);
                    switch ($parametros['formato']) {
                        case 'cantidad':
                        case 'entero':
                            $celda->formatear_cantidad();
                            break;
                        case 'caracter':
                            $celda->formatear_caracter();
                            break;
                        case 'decimal':
                        case 'flotante':
                            $celda->formatear_decimal($parametros['decimales']);
                            break;
                        case 'dinero':
                            $celda->formatear_dinero();
                            break;
                        case 'fecha':
                            $celda->formatear_fecha();
                            break;
                        case 'porcentaje':
                            $celda->formatear_porcentaje($parametros['decimales']);
                    }
                    $fila_celdas[$clave] = $celda;
                }
            }
            // BUCLE POR ESTRUCTURA
            foreach ($this->estructura as $clave => $parametros) {
                // CELDA EN USO
                $celda = $fila_celdas[$clave];
                // SI HAY PAGINA DE DESTINO EN LA ESTRUCTURA
                if ($parametros['pag'] != '') {
                    // SI HAY PARAMETROS
                    if (is_array($parametros['param']) && (count($parametros['param']) > 0)) {
                        // VINCULO COMO pagina.php?param1=col1&param2=col2
                        $b = array();
                        foreach ($parametros['param'] as $param_var => $param_col) {
                            if (isset($fila_celdas[$param_col])) {
                                $valor = urlencode($fila_celdas[$param_col]->valor);
                            } else {
                                $valor = urlencode($fila[$param_col]);
                            }
                            $b[] = sprintf('%s=%s', $param_var, $valor);
                        }
                        $vinculo = sprintf('%s?%s', $parametros['pag'], implode('&', $b));
                    } elseif (($parametros['id'] != '') && array_key_exists($parametros['id'], $fila)) {
                        // VINCULO CON UN ID, EJEMPLO pagina.php?id=456 DONDE 456 NO ES LA COLUMNA 'id'
                        if (isset($fila_celdas[$parametros['id']])) {
                            $valor = $fila_celdas[$parametros['id']]->valor;
                        } else {
                            $valor = $fila[$parametros['id']];
                        }
                        $vinculo = sprintf('%s?id=%s', $parametros['pag'], $valor);
                    } elseif (array_key_exists('id', $fila)) {
                        // VINCULO CON UN ID, EJEMPLO pagina.php?id=123
                        if (isset($fila_celdas['id'])) {
                            $valor = $fila_celdas['id']->valor;
                        } else {
                            $valor = $fila['id'];
                        }
                        $vinculo = sprintf('%s?id=%s', $parametros['pag'], $valor);
                    } else {
                        $vinculo = '';
                    }
                } else {
                    $vinculo = '';
                }
                // LO QUE SE VA A MOSTRAR
                if ($vinculo != '') {
                    $mostrar = sprintf('<a href="%s">%s</a>', $vinculo, $celda->formatear());
                } else {
                    $mostrar = $celda->formatear();
                }
                // ACUMULAREMOS LAS CLASES CSS EN ESTE ARREGLO
                $clases = array();
                // EL FORMATO ES UNA CLASE CSS
                if ($celda->formato != '') {
                    $clases[] = $celda->formato;
                }
                // SI HAY OTRO FORMATO EN LA ESTRUCTURA, SE ACUMULA
                if (($parametros['formato'] != '') && !in_array($parametros['formato'], $clases)) {
                    $clases[] = $parametros['formato'];
                }
                // JUNTAR CLASES CSS
                if (count($clases) > 0) {
                    $td_clase = sprintf(' class="%s"', implode(' ', $clases));
                } else {
                    $td_clase = '';
                }
                // ACUMULAR CODIGO HTML
                $a[] = sprintf('      <td%s>%s</td>', $td_clase, $mostrar);
            }
            $a[] = '    </tr>';
        }
        // ENTREGAR
        return implode("\n", $a);
    } // elaborar_tabla_contenido_con_panal

    /**
     * Elaborar Tabla Final
     */
    protected function elaborar_tabla_final() {
        // ACUMULAREMOS LA ENTREGA EN ESTE ARREGLO
        $a   = array();
        $a[] = '  </tbody>';
        $a[] = '</table>';
        // SI HAY ALGO EN EL PIE SE AGREGARÁ AL CONTENIDO
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p)) {
                    $a[] = $p->html();
                } elseif (is_string($p)) {
                    $a[] = $p;
                }
            }
        } elseif (is_string($this->pie) && ($this->pie != '')) {
            $a[] = $this->pie;
        }
        // CIERRE
        $a[] = '</div>';
        $a[] = '<!-- LISTADO TERMINA -->';
        // ENTREGAR
        return implode("\n", $a);
    } // elaborar_tabla_final

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @param  string Icono opcional
     * @return string HTML
     */
    public function html($in_encabezado='', $in_icono='') {
        // PARAMETROS
        if ($in_encabezado != '') {
            $this->encabezado = $in_encabezado;
        }
        if ($in_icono != '') {
            $this->icono = $in_icono;
        }
        // VALIDAR
        try {
            $this->validar();
        } catch (\Exception $e) {
            $mensaje = new MensajeHTML($e->getMessage());
            return $mensaje->html($this->encabezado);
        }
        // ACUMULAREMOS LA ENTREGA EN ESTE ARREGLO
        $a   = array();
        $a[] = $this->elaborar_tabla_inicio();
        // PREFERIR PANAL SOBRE LISTADO
        if (count($this->panal) > 0) {
            $a[] = $this->elaborar_tabla_contenido_con_panal();
        } else {
            $a[] = $this->elaborar_tabla_contenido_con_listado();
        }
        $a[] = $this->elaborar_tabla_final();
        // ENTREGAR
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Javascript, si no hay entrega falso
     */
    public function javascript() {
        // SI HAY CODIGO JAVASCRIPT EN LOS OBJETOS DE LA CABEZA
        if (is_array($this->cabeza) && (count($this->cabeza) > 0)) {
            foreach ($this->cabeza as $p) {
                if (is_object($p)) {
                    $this->javascript[] = $p->javascript();
                }
            }
        }
        // SI HAY CODIGO JAVASCRIPT EN LOS OBJETOS DEL PIE
        if (is_array($this->pie) && (count($this->pie) > 0)) {
            foreach ($this->pie as $p) {
                if (is_object($p)) {
                    $this->javascript[] = $p->javascript();
                }
            }
        }
        // ENTREGAR
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

} // Clase ListadoHTML

?>
