<?php
/**
 * GenesisPHP - ListadoWeb HTML
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
 * Clase HTML
 */
class HTML extends \Base\Plantilla {

    /**
     * Elaborar HTML Consulta
     *
     * @return string Código PHP
     */
    protected function elaborar_html_consulta() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Construir la consulta
        $a[] = "        // Consultar";
        $a[] = "        try {";
        $a[] = "            \$this->consultar();";
        // Si tiene uno o mas padres
        if (is_array($this->padre) && (count($this->padre) > 0)) {
            // Si el listado esta vacio y tiene permiso ponga el boton para agregar
            $a[] = "        } catch (\\Base2\\ListadoExceptionVacio \$e) {";
            $a[] = "            \$mensaje = new \\Base2\\MensajeWeb(\$e->getMessage());";
            foreach ($this->padre as $p) {
                // Para que se vea bonito
                $padre            = $p['instancia_singular'];
                $agregar_etiqueta = 'Agregar SED_SUBTITULO_SINGULAR';
                $agregar_url      = sprintf('SED_ARCHIVO_PLURAL.php?%s={$this->%s}&accion=agregar', $padre, $padre);
                // Agregar
                $a[] = "            if ((\$this->$padre != '') && (\$this->sesion->puede_agregar('SED_CLAVE'))) {";
                $a[] = "                \$mensaje->boton_agregar('$agregar_etiqueta', \"$agregar_url\");";
                $a[] = "            }";
            }
            $a[] = "            return \$mensaje->html(\$this->encabezado());";
        }
        $a[] = "        } catch (\\Exception \$e) {";
        $a[] = "            \$mensaje = new \\Base2\\MensajeWeb(\$e->getMessage());";
        $a[] = "            return \$mensaje->html(\$this->encabezado());";
        $a[] = "        }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_html_consulta

    /**
     * Elaborar HTML Eliminar Columnas
     *
     * @return string Código PHP
     */
    protected function elaborar_html_eliminar_columnas() {
        // Lo que se va a entregar se juntara en este arreglo
        $a   = array();
        $a[] = '        // Eliminar columnas de la estructura que sean filtros aplicados';
        foreach ($this->tabla as $columna => $datos) {
            // Solo si se va a mostrar en el listado
            // Y tambien que tenga un filtro de no rango
            if (($datos['listado'] > 0) && ($datos['filtro'] == 1)) {
                switch ($datos['tipo']) {
                    case 'relacion':
                        // Se va usar mucho la relacion, asi que para simplificar
                        if (is_array($this->relaciones[$columna])) {
                            $relacion = $this->relaciones[$columna];
                        } else {
                            die("Error en ListadoWeb, HTML: Falta obtener datos de Serpiente para la relación $columna.");
                        }
                        // Si vip es texto
                        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                            // Solo un vip
                            $a[] = "        if (\$this->{$columna}) {";
                            $a[] = "            unset(\$this->estructura['{$columna}_{$relacion['vip']}']);";
                            $a[] = "        }";
                        } elseif (is_array($relacion['vip'])) {
                            // Vip es un arreglo
                            foreach ($relacion['vip'] as $vip => $vip_datos) {
                                // Si es un arreglo
                                if (is_array($vip_datos)) {
                                    // Si es una relacion
                                    if ($vip_datos['tipo'] == 'relacion') {
                                        // Es una relacion y debe de existir en reptil
                                        if (is_array($this->relaciones[$vip])) {
                                            // Si el vip es un arreglo
                                            if (is_array($this->relaciones[$vip]['vip'])) {
                                                // Ese vip es un arreglo
                                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                                    $a[] = "        if (\$this->{$columna}) {";
                                                    $a[] = "            unset(\$this->estructura['{$vip}_{$v}']); // 8-)";
                                                    $a[] = "        }";
                                                }
                                            } else {
                                                $a[] = "        if (\$this->{$columna}) {";
                                                $a[] = "            unset(\$this->estructura['{$vip}']); // :-)";
                                                $a[] = "        }";
                                            }
                                        } else {
                                            die("Error en ListadoWeb, HTML: No está definido el VIP en Serpiente para $vip.");
                                        }
                                    } else {
                                        // No es una relacion
                                        $a[] = "        if (\$this->{$columna}) {";
                                        $a[] = "            unset(\$this->estructura['{$columna}_{$vip}']); // :)";
                                        $a[] = "        }";
                                    }
                                } else {
                                    // Vip datos es un texto
                                    $a[] = "        if (\$this->{$columna}) {";
                                    $a[] = "            unset(\$this->estructura['{$columna}_{$vip_datos}']);";
                                    $a[] = "        }";
                                }
                            }
                        }
                        break;
                    case 'entero':
                    case 'fecha':
                    case 'caracter':
                        // Es entero, fecha o caracter, lo retiramos
                        $a[] = "        if (\$this->{$columna}) {";
                        $a[] = "            unset(\$this->estructura['{$columna}']);";
                        $a[] = "        }";
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_html_eliminar_columnas

    /**
     * Elaborar HTML Mapa
     *
     * @return string Código PHP
     */
    protected function elaborar_html_mapa() {
        // Si no hay mapa no se entrega nada
        if (!is_array($this->mapa)) {
            return false;
        }
        // En este arreglo juntaremos el codigo php
        $a = array();
        // Puede haber mas de una columna con informacion para mapas
        foreach ($this->mapa as $columna => $datos) {
            // Si la columna es geopunto
            if ($this->tabla[$columna]['tipo'] == 'geopunto') {
                // Validar que esten los datos necesarios
                if ($datos['categoria'] == '') {
                    die("Error en ListadoWeb, HTML: Al arreglo mapa, para la columna $columna le falta el dato 'categoria'.");
                }
                if ($datos['categorias_colores'] == '') {
                    die("Error en ListadoWeb, HTML: Al arreglo mapa, para la columna $columna le falta el dato 'categorias_colores'.");
                }
                if ($datos['categorias_descripciones'] == '') {
                    die("Error en ListadoWeb, HTML: Al arreglo mapa, para la columna $columna le falta el dato 'categorias_descripciones'.");
                }
                if ($datos['descripcion'] == '') {
                    die("Error en ListadoWeb, HTML: Al arreglo mapa, para la columna $columna le falta el dato 'descripcion'.");
                }
                // Para que se vea bonito
                $colores    = $datos['categorias_colores'];
                $geojson    = "\$r['{$columna}_geojson']";
                $categoria  = "\$r['".$datos['categoria']."']";
                $popup      = "\$r['".$datos['descripcion']."']";
                // Agregar
                $a[] = "        // Agregar el mapa";
                $a[] = "        \$mapa = new \\Base2\\MapaWeb();";
                $a[] = "        foreach ($colores as \$letra => \$color) {";
                $a[] = "            \$mapa->agregar_categoria(\$letra, \$color);";
                $a[] = "        }";
                $a[] = "        foreach (\$this->listado as \$r) {";
                $a[] = "            \$mapa->agregar_geopunto(\$r['id'], $geojson, $categoria, $popup);";
                $a[] = "        }";
                $a[] = "        \$this->listado_controlado->al_principio(\$mapa);";
            }
        }
        // Entregar
        if (count($a) > 0) {
            return "\n".implode("\n", $a);
        } else {
            return '';
        }
    } // elaborar_html_mapa

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function html(\$in_encabezado='') {
{$this->elaborar_html_consulta()}
{$this->elaborar_html_eliminar_columnas()}{$this->elaborar_html_mapa()}
        // Pasamos al listado controlado
        \$this->listado_controlado->estructura         = \$this->estructura;
        \$this->listado_controlado->listado            = \$this->listado;
        \$this->listado_controlado->cantidad_registros = \$this->cantidad_registros;
        \$this->listado_controlado->variables          = \$this->filtros_param;
        \$this->listado_controlado->limit              = \$this->limit;
        \$this->listado_controlado->barra              = \$this->barra(\$in_encabezado);
        // Entregar
        return \$this->listado_controlado->html();
    } // html

FINAL;
    } // php

} // Clase HTML

?>
