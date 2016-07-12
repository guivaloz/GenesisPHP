<?php
/**
 * GenesisPHP - ListadoWeb Constructor
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

namespace ListadoWeb;

/**
 * Clase Constructor
 */
class Constructor extends \Base\Plantilla {

    /**
     * Formatos
     *
     * De acuerdo al tipo definido en la semilla se declara este formato en la estructura
     */
    static public $formatos = array(
        'fecha'      => 'fecha',
        'entero'     => 'entero',
        'dinero'     => 'dinero',
        'flotante'   => 'flotante',
        'porcentaje' => 'porcentaje');

    /**
     * Elaborar Recibir Filtros por URL Declaracion
     *
     * @param  string Columna de la tabla
     * @param  array  Opcional. Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_constructor_recibir_filtros_declaracion($columna, $datos=false) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Si no vienen los datos, por defecto el filtro es uno
        if ($datos === false) {
            $datos = array('filtro' => 1);
        }
        // Agregar
        if ($datos['filtro'] > 1) {
            // Rango (desde-hasta)
            $a[] = "        \$this->{$columna}_desde = \$_GET[parent::\$param_{$columna}_desde];";
            $a[] = "        \$this->{$columna}_hasta = \$_GET[parent::\$param_{$columna}_desde];";
        } elseif ($datos['filtro'] > 0) {
            // Normal
            $a[] = "        \$this->{$columna} = \$_GET[parent::\$param_{$columna}];";
        } else {
            die("Error en ListadoWeb, Constructor, elaborar_constructor_recibir_filtros_declaracion: No hay valor en filtro para $columna.");
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_constructor_recibir_filtros_declaracion

    /**
     * Elaborar recepcion de filtros por URL
     *
     * @return string Código PHP
     */
    protected function elaborar_constructor_recibir_filtros() {
        // Lo que se va a entregar se juntara en este arreglo
        $a   = array();
        $a[] = "        // Recibir filtros enviados por el url";
        // Bucle para cada columna de la tabla
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no aparece en el listado
            } elseif ($datos['tipo'] == 'relacion') {
                // Agregamos la relacion misma
                $a[] = $this->elaborar_constructor_recibir_filtros_declaracion($columna, $datos);
                // Se va usar mucho la relacion, asi que para simplificar
                if (is_array($this->relaciones[$columna])) {
                    $relacion = $this->relaciones[$columna];
                } else {
                    die("Error en ListadoWeb, Constructor: Falta obtener datos de Serpiente para la relación $columna.");
                }
                // A continuacion, la parte complicada que viaja a traves de las relaciones
                if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                    $a[] = $this->elaborar_constructor_recibir_filtros_declaracion("{$columna}_{$relacion['vip']}");
                } elseif (is_array($relacion['vip'])) {
                    foreach ($relacion['vip'] as $vip => $vip_datos) {
                        if (is_array($vip_datos)) {
                            if ($vip_datos['tipo'] == 'relacion') {
                                if (is_array($this->relaciones[$vip])) {
                                    if (is_array($this->relaciones[$vip]['vip'])) {
                                        $a[] = $this->elaborar_constructor_recibir_filtros_declaracion($vip, $vip_datos);
                                        foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                            $a[] = $this->elaborar_constructor_recibir_filtros_declaracion("{$vip}_{$v}", $vd);
                                        }
                                    } else {
                                        $a[] = $this->elaborar_constructor_recibir_filtros_declaracion("{$vip}_{$this->relaciones[$vip]['vip']}");
                                    }
                                } else {
                                    die("Error en ListadoWeb, Constructor: No está definido el VIP en Serpiente para $vip.");
                                }
                            } else {
                                $a[] = $this->elaborar_constructor_recibir_filtros_declaracion("{$columna}_{$vip}", $vip_datos);
                            }
                        } else {
                            $a[] = $this->elaborar_constructor_recibir_filtros_declaracion("{$columna}_{$vip_datos}");
                        }
                    }
                }
            } else {
                // No es relacion, es cualquier otro tipo
                $a[] = $this->elaborar_constructor_recibir_filtros_declaracion($columna, $datos);
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_constructor_recibir_filtros

    /**
     * Elaborar Constructor Estructura
     *
     * @return string Código PHP
     */
    protected function elaborar_constructor_estructura() {
        // Lo que se va a entregar se juntara en este arreglo
        $a   = array();
        $a[] = '        // Estructura';
        $a[] = '        $this->estructura = array(';
        // Juntaremos todos los datos de la estructura en este arreglo
        $elementos = array();
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Aparecen en el listado los que tengan uno o mas
            if ($datos['listado'] > 0) {
                // Si la columna es una relacion
                if ($datos['tipo'] == 'relacion') {
                    // Se va usar mucho la relacion, asi que para simplificar
                    if (is_array($this->relaciones[$columna])) {
                        $relacion = $this->relaciones[$columna];
                    } else {
                        die("Error en ListadoWeb, Constructor: Falta obtener datos de Serpiente para la relación $columna.");
                    }
                    // Si vip es texto
                    if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                        // Solo un vip
                        $elementos[$datos['listado']] = array(
                            'columna' => "{$columna}_{$relacion['vip']}",
                            'enca'    => $relacion['etiqueta_singular']);
                        // En caso de vip mayor a uno, sera vinculo al detalle
                        if ($datos['vip'] > 1) {
                            $elementos[$datos['listado']]['pag'] = 'SED_ARCHIVO_PLURAL.php';
                            $elementos[$datos['listado']]['id']  = 'id';
                        }
                    } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                        // Vip es un arreglo
                        $secuencia = $datos['listado'];
                        foreach ($relacion['vip'] as $vip => $vip_datos) {
                            // Si es un arreglo
                            if (is_array($vip_datos)) {
                                if ($vip_datos['tipo'] == 'relacion') {
                                    // Es una relacion
                                    // Debe de existir en reptil esa relacion
                                    if (is_array($this->relaciones[$vip])) {
                                        // Si el vip es un arreglo
                                        if (is_array($this->relaciones[$vip]['vip'])) {
                                            // Ese vip es un arreglo
                                            foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                                if ($vd['tipo'] == 'relacion') {
                                                    // Ese vip de la relacion es otra relacion, se omite
                                                } elseif ($vd['tipo'] == 'caracter') {
                                                    // Ese vip de la relacion es de tipo caracter
                                                    $elementos[$secuencia] = array(
                                                        'columna' => "{$vip}_{$v}",
                                                        'enca'    => $vd['etiqueta'],
                                                        'cambiar' => "\\{$this->relaciones[$vip]['clase_plural']}\\Registro::\${$v}_descripciones",
                                                        'color'   => "{$vip}_{$v}",
                                                        'colores' => "\\{$this->relaciones[$vip]['clase_plural']}\\Registro::\${$v}_colores");
                                                    $secuencia++;
                                                } elseif (array_key_exists($vd['tipo'], self::$formatos)) {
                                                    // Es un tipo que tiene formato
                                                    $elementos[$secuencia] = array(
                                                        'columna' => "{$vip}_{$v}",
                                                        'enca'    => $vd['etiqueta'],
                                                        'formato' => self::$formatos[$vd['tipo']]);
                                                    $secuencia++;
                                                } else {
                                                    // Ese vip de la relacion es de otro tipo
                                                    $elementos[$secuencia] = array(
                                                        'columna' => "{$vip}_{$v}",
                                                        'enca'    => $vd['etiqueta']);
                                                    $secuencia++;
                                                }
                                            }
                                        } else {
                                            // Ese vip es texto
                                            $elementos[$secuencia] = array(
                                                'columna' => "{$columna}_{$vip}",
                                                'enca'    => $this->relaciones[$vip]['etiqueta_singular']);
                                            $secuencia++;
                                        }
                                    } else {
                                        die("Error en ListadoWeb, Constructor: No está definido el VIP en Serpiente para $vip.");
                                    }
                                } elseif ($vip_datos['tipo'] == 'caracter') {
                                    // Es caracter, se muestra el descrito
                                    $elementos[$secuencia] = array(
                                        'columna' => "{$columna}_{$vip}",
                                        'enca'    => $vip_datos['etiqueta'],
                                        'cambiar' => "\\{$relacion['clase_plural']}\\Registro::\${$vip}_descripciones",
                                        'color'   => "{$columna}_{$vip}",
                                        'colores' => "\\{$relacion['clase_plural']}\\Registro::\${$vip}_colores");
                                    $secuencia++;
                                } elseif (array_key_exists($vip_datos['tipo'], self::$formatos)) {
                                    // Es un tipo que tiene formato
                                    $elementos[$secuencia] = array(
                                        'columna' => "{$columna}_{$vip}",
                                        'enca'    => $vip_datos['etiqueta'],
                                        'formato' => self::$formatos[$vip_datos['tipo']]);
                                    $secuencia++;
                                } else {
                                    // Es cualquier otro tipo
                                    $elementos[$secuencia] = array(
                                        'columna' => "{$columna}_{$vip}",
                                        'enca'    => $vip_datos['etiqueta']);
                                    $secuencia++;
                                }
                            } else {
                                // Vip datos es un texto
                                $elementos[$secuencia] = array(
                                    'columna' => "{$columna}_{$vip_datos}",
                                    'enca'    => "{$relacion['etiqueta_singular']} $vip_datos");
                                $secuencia++;
                            }
                        }
                    } else {
                        die("Error en ListadoWeb, Constructor: Falta el 'vip' en Serpiente para la relación $columna.");
                    }
                } elseif ($datos['tipo'] == 'caracter') {
                    // La columna es un caracter, sera sustituido por su descripcion
                    $e            = array();
                    $e['columna'] = $columna;
                    $e['enca']    = $datos['etiqueta'];
                    $e['cambiar'] = "Registro::\${$columna}_descripciones";
                    $e['color']   = $columna;
                    $e['colores'] = "Registro::\${$columna}_colores";
                    // En caso de vip mayor a uno, sera vinculo al detalle
                    if ($datos['vip'] > 1) {
                        $e['pag'] = 'SED_ARCHIVO_PLURAL.php';
                        $e['id']  = 'id';
                    }
                    // Agregar al arreglo
                    $elementos[$datos['listado']] = $e;
                } elseif ($datos['tipo'] == 'geopunto') {
                    // La columna es geopunto
                    $elementos[$datos['listado']] = array(
                        'columna' => $columna,
                        'enca'    => $datos['etiqueta']);
                } else {
                    // La columna no es relacion ni caracter
                    $e            = array();
                    $e['columna'] = $columna;
                    $e['enca']    = $datos['etiqueta'];
                    // En caso de tener formato
                    if (array_key_exists($datos['tipo'], self::$formatos)) {
                        $e['formato'] = self::$formatos[$datos['tipo']];
                    }
                    // En caso de vip mayor a uno, sera vinculo al detalle
                    if ($datos['vip'] > 1) {
                        $e['pag'] = 'SED_ARCHIVO_PLURAL.php';
                        $e['id']  = 'id';
                    }
                    // Agregar al arreglo
                    $elementos[$datos['listado']] = $e;
                }
            }
        }
        // Los ordenamos por ese numero
        ksort($elementos);
        // Damos forma a la seccion de estructura
        $c = array();
        foreach ($elementos as $orden => $e) {
            $b   = array();
            $b[] = "                'enca'    => '{$e['enca']}'";
            if ($e['pag'] != '') {
                $b[] = "                'pag'     => '{$e['pag']}'";
                $b[] = "                'id'      => '{$e['id']}'";
            }
            if ($e['cambiar'] != '') {
                $b[] = "                'cambiar' => {$e['cambiar']}";
            }
            if ($e['color'] != '') {
                $b[] = "                'color'   => '{$e['color']}'";
                $b[] = "                'colores' => {$e['colores']}";
            }
            if ($e['formato'] != '') {
                $b[] = "                'formato' => '{$e['formato']}'";
            }
            $c[] = "            '{$e['columna']}' => array(\n".implode(",\n", $b).")";
        }
        $a[] = implode(",\n", $c);
        $a[] = '        );';
        // Entregar
        return implode("\n", $a);
    } // elaborar_constructor_estructura

    /**
     * Elaborar Constructor Viene Listado
     *
     * @return string Código PHP
     */
    protected function elaborar_constructor_viene_listado() {
        // Lo que se va a entregar se juntara en este arreglo
        $a   = array();
        $a[] = '        // Si cualquiera de los filtros tiene valor, entonces viene listado sera verdadero';
        $a[] = '        if ($this->listado_controlado->viene_listado) {';
        $a[] = '            $this->viene_listado = true;';
        $a[] = '        } else {';
        // Trabajar solo con los campos que se usen como filtros
        $b = array();
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['filtro'] > 1) {
                // Rango (desde-hasta)
                $b[] = "(\$this->{$columna}_desde != '')";
                $b[] = "(\$this->{$columna}_hasta != '')";
            } elseif ($datos['filtro'] > 0) {
                // Normal
                $b[] = "(\$this->$columna != '')";
            }
        }
        // Validar que haya por lo menos un filtro
        if (count($b) == 0) {
            die('Error en ListadoWeb: Es necesario que haya por lo menos una columna como filtro.');
        }
        $a[] = '            $this->viene_listado = '.implode(' || ', $b).';';
        $a[] = '        }';
        // Entregar
        return implode("\n", $a);
    } // elaborar_constructor_viene_listado

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\\Inicio\\Sesion \$in_sesion) {
{$this->elaborar_constructor_recibir_filtros()}
{$this->elaborar_constructor_estructura()}
        // Iniciar listado controlado
        \$this->listado_controlado = new \\Base2\\ListadoWebControlado();
        // Su constructor toma estos parametros por url
        \$this->limit              = \$this->listado_controlado->limit;
        \$this->offset             = \$this->listado_controlado->offset;
        \$this->cantidad_registros = \$this->listado_controlado->cantidad_registros;
{$this->elaborar_constructor_viene_listado()}
        // Ejecutar el constructor del padre
        parent::__construct(\$in_sesion);
    } // constructor

FINAL;
    } // php

} // Clase Constructor

?>
