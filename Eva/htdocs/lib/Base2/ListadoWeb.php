<?php
/**
 * GenesisPHP - ListadoWeb
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
 * Clase ListadoWeb
 */
class ListadoWeb implements SalidaWeb {

    public $encabezado;              // Opcional, texto para el encabezado
    public $icono;                   // Opcional, URL al icono
    public $barra;                   // Opcional, instancia de BarraWeb
    public $estructura;              // Arreglo asociativo con la estructura del listado
    public $listado       = array(); // Arreglo, resultado de la consulta
    public $panal         = array();
    protected $cabeza     = array(); // Arreglo con instancias o de códigos HTML a agregar al principio con el metodo al_principio
    protected $pie        = array(); // Arreglo con instancias o de codigos HTML a agregar al final     con el metodo al_final

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
            throw new ListadoExceptionValidacion('Error en ListadoWeb: La estructura es incorrecta.');
        }
        if (count($this->estructura) == 0) {
            throw new ListadoExceptionValidacion('Error en ListadoWeb: La estructura está vacía.');
        }
        if (!is_array($this->listado)) {
            throw new ListadoExceptionValidacion('Error en ListadoWeb: El listado es incorrecto.');
        }
        if (!is_array($this->panal)) {
            throw new ListadoExceptionValidacion('Error en ListadoWeb: El panal es incorrecto.');
        }
        if ((count($this->listado) == 0) && (count($this->panal) == 0)) {
            throw new ListadoExceptionValidacion('Aviso en ListadoWeb: No hay registros a mostrar.');
        }
    } // validar

    /**
     * Elaborar Tabla Inicio
     *
     * @return string HTML
     */
    protected function elaborar_tabla_inicio() {
        // Acumularemos la entrega en este arreglo
        $a   = array();
        $a[] = '<div class="listado">';
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
        // Tabla inicia
        $a[] = '<table class="table table-hover table-bordered">';
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
     *
     * @return string HTML
     */
    protected function elaborar_tabla_contenido_con_listado() {
        // Acumularemos la entrega en este arreglo
        $a = array();
        // Bucle por filas
        foreach ($this->listado as $fila) {
            $a[] = '    <tr>';
            // Bucle por estructura
            foreach ($this->estructura as $columna => $parametros) {
                // Clase CSS
                $c = array();
                if ($parametros['clase'] != '') {
                    $c[] = $parametros['clase']; // Defina el estilo CSS en plantilla-fluida.css
                }
                if ($parametros['formato'] != '') {
                    $c[] = $parametros['formato']; // Defina el estilo CSS en plantilla-fluida.css
                }
                if (is_array($parametros['colores']) && array_key_exists($parametros['color'], $fila)) {
                    $c[] = $parametros['colores'][$fila[$parametros['color']]]; // O es el color o el formato
                }
                if (count($c) > 0) {
                    $celda_clase = sprintf(' class="%s"', implode(' ', $c));
                } else {
                    $celda_clase = '';
                }
                // Valor de la celda, si usa un sprintf
                if (($parametros['sprintf'] != '') && ($fila[$columna] !== '')) {
                    $valor = sprintf($parametros['sprintf'], $fila[$columna]);
                } elseif ($parametros['formato'] != '') {
                    // Si usa un formato
                    switch ($parametros['formato']) {
                        case 'fecha':
                            $valor = UtileriasParaFormatos::formato_fecha($fila[$columna]);
                            break;
                        case 'entero':
                            $valor = UtileriasParaFormatos::formato_entero($fila[$columna]);
                            break;
                        case 'dinero':
                            $valor = UtileriasParaFormatos::formato_dinero($fila[$columna]);
                            break;
                        case 'flotante':
                            $valor = UtileriasParaFormatos::formato_flotante($fila[$columna]);
                            break;
                        case 'porcentaje':
                            $valor = UtileriasParaFormatos::formato_porcentaje($fila[$columna]);
                            break;
                        default:
                            $valor = $fila[$columna]; // Pasa igual
                    }
                } else {
                    // Pasa igual
                    $valor = $fila[$columna];
                }
                // Cambiar un carácter por una descripción o cortar la cadena de texto
                if (is_array($parametros['cambiar']) && ($valor != '')) {
                    $mostrar = $parametros['cambiar'][$valor];
                } elseif (($parametros['cortar'] > 0) && (strlen($valor) > $parametros['cortar'])) {
                    $mostrar = substr($valor, 0, $parametros['cortar']).'...';
                } else {
                    $mostrar = $valor;
                }
                // Agregamos la celda de acuerdo a...
                if ($mostrar == '') {
                    // Si hay que a elaborar un vínculo pero sin contenido
                    if ($parametros['pag'] != '') {
                        // Si se uso param
                        if (is_array($parametros['param']) && (count($parametros['param']) > 0)) {
                            // Vínculo con muchos parametros, ejemplo pagina.php?param=123&var=567
                            $b = array();
                            foreach ($parametros['param'] as $param_var => $param_col) {
                                $b[] = sprintf('%s=%s', $param_var, urlencode($fila[$param_col]));
                            }
                            $a[] = sprintf('      <td%s><a href="%s?%s">VACIO</a></td>', $celda_clase, $parametros['pag'], implode('&', $b));
                        } elseif (($parametros['id'] != '') && array_key_exists($parametros['id'], $fila)) {
                            // Vínculo con un id, ejemplo pagina.php?usuario=123
                            $a[] = sprintf('      <td%s><a href="%s?id=%s">VACIO</a></td>', $celda_clase, $parametros['pag'], $fila[$parametros['id']]);
                        } else {
                            // Faltaron los datos para hacer el vínculo
                            $a[] = "      <td{$celda_clase}>FALTO</td>";
                        }
                    } else {
                        // No hay que mostrar, sera una celda vacia
                        $a[] = "      <td{$celda_clase}>&nbsp;</td>";
                    }
                } elseif ($parametros['pag'] != '') {
                    // Hay que a elaborar un vinculo
                    if (is_array($parametros['param']) && (count($parametros['param']) > 0)) {
                        // Vínculo con muchos parametros, ejemplo pagina.php?param=123&var=567
                        $b = array();
                        foreach ($parametros['param'] as $param_var => $param_col) {
                            $b[] = sprintf('%s=%s', $param_var, urlencode($fila[$param_col]));
                        }
                        $a[] = sprintf('      <td%s><a href="%s?%s">%s</a></td>', $celda_clase, $parametros['pag'], implode('&', $b), $mostrar);
                    } elseif (($parametros['id'] != '') && array_key_exists($parametros['id'], $fila)) {
                        // Vínculo con un id, ejemplo pagina.php?usuario=123
                        $a[] = sprintf('      <td%s><a href="%s?id=%s">%s</a></td>', $celda_clase, $parametros['pag'], $fila[$parametros['id']], $mostrar);
                    } elseif (array_key_exists('id', $fila)) {
                        // Vínculo con id, ejemplo pagina.php?id=123
                        $a[] = sprintf('      <td%s><a href="%s?id=%s">%s</a></td>', $celda_clase, $parametros['pag'], $fila['id'], $mostrar);
                    } else {
                        // No esta definido el id, sale sin vínculo
                        $a[] = "      <td{$celda_clase}>{$mostrar}</td>";
                    }
                } else {
                    // La celda sólo muestra el contenido
                    $a[] = "      <td{$celda_clase}>{$mostrar}</td>";
                }
            }
            $a[] = '    </tr>';
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_tabla_contenido_con_listado

    /**
     * Elaborar Tabla Contenido con Panal
     *
     * @return string HTML
     */
    protected function elaborar_tabla_contenido_con_panal() {
        // Acumularemos la entrega en este arreglo
        $a = array();
        // Bucle por filas
        foreach ($this->panal as $fila) {
            $a[] = '    <tr>';
            // Bucle por estructura
            $fila_celdas = array();
            foreach ($this->estructura as $clave => $parametros) {
                // Si es intancia de Celda
                if (is_object($fila[$clave]) && ($fila[$clave] instanceof Celda)) {
                    // Pasa igual
                    $fila_celdas[$clave] = $fila[$clave];
                } else {
                    // Convertir a instancia de Celda
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
            // Bucle por estructura
            foreach ($this->estructura as $clave => $parametros) {
                // Celda en uso
                $celda = $fila_celdas[$clave];
                // Si hay página de destino en la estructura
                if ($parametros['pag'] != '') {
                    // Si hay parámetros
                    if (is_array($parametros['param']) && (count($parametros['param']) > 0)) {
                        // Vínculo como pagina.php?param1=col1&param2=col2
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
                        // Vínculo con un id, ejemplo pagina.php?id=456 donde 456 no es la columna 'id'
                        if (isset($fila_celdas[$parametros['id']])) {
                            $valor = $fila_celdas[$parametros['id']]->valor;
                        } else {
                            $valor = $fila[$parametros['id']];
                        }
                        $vinculo = sprintf('%s?id=%s', $parametros['pag'], $valor);
                    } elseif (array_key_exists('id', $fila)) {
                        // Vínculo con un id, ejemplo pagina.php?id=123
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
                // Lo que se va a mostrar
                if ($vinculo != '') {
                    $mostrar = sprintf('<a href="%s">%s</a>', $vinculo, $celda->formatear());
                } else {
                    $mostrar = $celda->formatear();
                }
                // Acumularemos las clases CSS en este arreglo
                $clases = array();
                // El formato es una clase CSS
                if ($celda->formato != '') {
                    $clases[] = $celda->formato;
                }
                // Si hay otro formato en la estructura, se acumula
                if (($parametros['formato'] != '') && !in_array($parametros['formato'], $clases)) {
                    $clases[] = $parametros['formato'];
                }
                // Juntar clases CSS
                if (count($clases) > 0) {
                    $td_clase = sprintf(' class="%s"', implode(' ', $clases));
                } else {
                    $td_clase = '';
                }
                // Acumular código HTML
                $a[] = sprintf('      <td%s>%s</td>', $td_clase, $mostrar);
            }
            $a[] = '    </tr>';
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_tabla_contenido_con_panal

    /**
     * Elaborar Tabla Final
     *
     * @return string HTML
     */
    protected function elaborar_tabla_final() {
        // Acumularemos la entrega en este arreglo
        $a   = array();
        $a[] = '  </tbody>';
        $a[] = '</table>';
        // Si hay algo en el pie se agregará al contenido
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
        // Cierre
        $a[] = '</div>';
        // Entregar
        return implode("\n", $a);
    } // elaborar_tabla_final

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
        $a[] = $this->elaborar_tabla_inicio();
        // Preferir panal sobre listado
        if (count($this->panal) > 0) {
            $a[] = $this->elaborar_tabla_contenido_con_panal();
        } else {
            $a[] = $this->elaborar_tabla_contenido_con_listado();
        }
        $a[] = $this->elaborar_tabla_final();
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

} // Clase ListadoWeb

?>
