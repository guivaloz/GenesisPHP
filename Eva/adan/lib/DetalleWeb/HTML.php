<?php
/**
 * GenesisPHP - DetalleWeb HTML
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

namespace DetalleWeb;

/**
 * Clase HTML
 */
class HTML extends \Base\Plantilla {

    /**
     * Elaborar HTML Detalle Propiedades Relacionadas
     *
     * @param  string Columna
     * @param  array  Datos
     * @return string Código PHP
     */
    protected function elaborar_html_detalle_propiedades_relacionadas($columna, $datos) {
        // Se va usar mucho la relacion, asi que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion = $this->relaciones[$columna];
        } else {
            die("Error en DetalleWeb: Falta obtener datos de Serpiente para la relación $columna.");
        }
        // Si vip es texto
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            // Solo un vip
            $eses  = '%s';
            $param = "\$this->{$columna}_{$relacion['vip']}";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            // Vip es un arreglo
            $s = array();
            $p = array();
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                // Si es un arreglo
                if (is_array($vip_datos)) {
                    // Ese vip es un arreglo
                    if ($vip_datos['tipo'] == 'relacion') {
                        // Es una relacion y debe de existir en reptil
                        if (is_array($this->relaciones[$vip])) {
                            // Si el vip es un arreglo
                            $relacion2 = $this->relaciones[$vip];
                            if (is_array($relacion2['vip'])) {
                                // Ese vip es un arreglo
                                foreach ($relacion2['vip'] as $v => $vd) {
                                    if ($vd['tipo'] == 'relacion') {
                                        // Ese vip de la relacion es otra relacion, se omite
                                    } elseif ($vd['tipo'] == 'caracter') {
                                        // Ese vip de la relacion es de tipo caracter
                                        $s[] = "{$vd['etiqueta']} %s";
                                        $p[] = "\$this->{$vip}_{$v}_descrito";
                                    } else {
                                        // Ese vip de la relacion es de otro tipo
                                        $s[] = "{$vd['etiqueta']} %s";
                                        $p[] = "\$this->{$vip}_{$v}";
                                    }
                                }
                            } else {
                                // Ese vip es texto
                                $s[] = "%s";
                                $p[] = "\$this->{$vip}_{$relacion2['vip']}";
                            }
                        } else {
                            die("Error en DetalleWeb: No está definido el VIP en Serpiente para $vip.");
                        }
                    } elseif ($vip_datos['tipo'] == 'caracter') {
                        // Es caracter, mostraremos su descrito
                        $s[] = "{$vip_datos['etiqueta']} %s";
                        $p[] = "\$this->{$columna}_{$vip}_descrito";
                    } else {
                        // Es cualquier otro tipo
                        $s[] = "{$vip_datos['etiqueta']} %s";
                        $p[] = "\$this->{$columna}_{$vip}";
                    }
                } else {
                    // Ese vip es texto
                    $s[] = '%s';
                    $p[] = "\$this->{$columna}_{$vip_datos}";
                }
            }
            $eses  = implode('<br> ', $s);
            $param = implode(', ', $p);
        }
        // Entregar
        return "(\$this->{$columna} != '') ? sprintf('<a href=\"{$this->relaciones[$columna]['archivo_plural']}.php?id=%s\">{$eses}</a>', \$this->{$columna}, {$param}) : ''";
    } // elaborar_html_detalle_propiedades_relacionadas

    /**
     * Elaborar HTML Detalle Propiedades
     *
     * @return string Código PHP
     */
    protected function elaborar_html_detalle_propiedades() {
        // En este arreglo juntaremos el codigo
        $a = array();
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            // Omitir las columnas sin etiqueta
            if ($datos['etiqueta'] == '') {
                continue;
            }
            // Omitir las columnas estatus y notas. se elaboran en otro metodo
            if ($columna == 'estatus') {
                continue;
            } elseif ($columna == 'notas') {
                continue;
            }
            // Si es relacion
            if ($datos['tipo'] == 'relacion') {
                // Las relaciones no hacen vinculo con vip
                $valor = $this->elaborar_html_detalle_propiedades_relacionadas($columna, $datos);
            } elseif ($datos['detalle'] != '') {
                // Va a hacer un injerto de codigo declarado en la semilla
                $valor = $datos['detalle'];
            } else {
                // De acuerdo al tipo
                switch ($datos['tipo']) {
                    case 'caracter':
                        $valor = "\$this->{$columna}_descrito";
                        break;
                    case 'dinero':
                        $valor = "\\Base2\\UtileriasParaFormatos::formato_dinero(\$this->{$columna})";
                        break;
                    case 'email':
                        $valor = "(\$this->{$columna} != '') ? sprintf('<a href=\"mailto:%s\">%s</a>', \$this->{$columna}, \$this->{$columna}) : ''";
                        break;
                    case 'geopunto':
                        $valor = "\"Latitud {\$this->{$columna}_latitud}, Longitud {\$this->{$columna}_longitud}\""; // <br>GeoJSON {\$this->{$columna}_geojson}
                        break;
                    case 'porcentaje':
                        $valor = "(\$this->{$columna} != '') ? \"{\$this->{$columna}} %\" : ''";
                        break;
                    default:
                        $valor = "\$this->{$columna}";
                }
                // Si el vip es mayor a uno, elaborar un vinculo al detalle
                if ($datos['vip'] > 1) {
                    $valor = sprintf('"<a href=\"SED_ARCHIVO_PLURAL.php?id={%s}\">{%s}</a>"', '$this->id', $valor);
                }
            }
            // Agregar al arreglo
            $a[] = "        \$this->detalle->dato('{$datos['etiqueta']}', {$valor});";
        }
        // Entregar
        if (count($a) > 0) {
            return implode("\n", $a);
        } else {
            die('Error en DetalleWeb: No hay columnas con etiqueta en la tabla para mostrar.');
        }
    } // elaborar_html_detalle_propiedades

    /**
     * Elaborar HTML Detalle Sección Registro
     *
     * @return string Código PHP
     */
    protected function elaborar_html_detalle_seccion_registro() {
        // Banderas
        $bandera_estatus = false;
        $bandera_notas   = false;
        // Bucle cada columna en la tabla
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['etiqueta'] != '') {
                if ($columna == 'estatus') {
                    $bandera_estatus = true;
                } elseif ($columna == 'notas') {
                    $bandera_notas = true;
                }
            }
        }
        // Seccion registro
        if ($bandera_notas && !$bandera_estatus) {
            return <<<FINAL
        // Seccion registro
        \$this->detalle->seccion('Registro');
        \$this->detalle->dato('Notas', \$this->notas);
FINAL;
        } elseif ($bandera_estatus && !$bandera_notas) {
            return <<<FINAL
        // Seccion registro
        if (\$this->sesion->puede_recuperar('SED_CLAVE')) {
            \$this->detalle->seccion('Registro');
            \$this->detalle->dato('Estatus', \$this->estatus_descrito, parent::\$estatus_colores[\$this->estatus]);
        }
FINAL;
        } elseif ($bandera_estatus && $bandera_notas) {
            return <<<FINAL
        // Seccion registro
        \$this->detalle->seccion('Registro');
        \$this->detalle->dato('Notas', \$this->notas);
        if (\$this->sesion->puede_recuperar('SED_CLAVE')) {
            \$this->detalle->dato('Estatus', \$this->estatus_descrito, parent::\$estatus_colores[\$this->estatus]);
        }
FINAL;
        } else {
            return false;
        }
    } // elaborar_html_detalle_seccion_registro

    /**
     * Elaborar HTML Detalle Sección Imagen
     *
     * @return string Código PHP
     */
    protected function elaborar_html_detalle_seccion_imagen() {
        // Si no hay imagen no se entrega nada
        if (!is_array($this->imagen)) {
            return false;
        }
        // Validar
        $caracteres = $this->imagen['caracteres'];
        if ($caracteres == '') {
            die('Error en DetalleWeb: Al arreglo imagen le falta el valor para "caracteres".');
        }
        if ($this->imagen['tamaños']['middle'] == '') {
            die('Error en DetalleWeb: Al arreglo imagen le falta el tamaño "middle".');
        }
        // ENTREGAR
        return <<<FINAL
        // Agregar imagen al detalle
        \$imagen = new \\Base2\\ImagenWeb(parent::\$imagen_almacen_ruta, parent::\$imagen_tamanos);
        \$imagen->cargar(\$this->id, \$this->{$caracteres}, 'middle'); // TODO: que este tamaño se pueda controlar desde la semilla
        \$imagen->vincular('big');
        \$this->detalle->imagen(\$imagen);
FINAL;
    } // elaborar_html_detalle_seccion_imagen

    /**
     * Elaborar HTML Detalle Sección Mapa
     *
     * @return string Código PHP
     */
    protected function elaborar_html_detalle_seccion_mapa() {
        // Si no hay mapa no se entrega nada
        if (!is_array($this->mapa)) {
            return false;
        }
        // En este arreglo juntaremos el codigo php
        $a = array();
        // Puede haber más de una columna con información para mapas
        foreach ($this->mapa as $columna => $datos) {
            // Si la columna es geopunto
            if ($this->tabla[$columna]['tipo'] == 'geopunto') {
                // Validar que esten los datos necesarios
                if ($datos['categoria'] == '') {
                    die("Error en DetalleWeb: Al arreglo mapa, para la columna $columna le falta el dato 'categoria'.");
                }
                if ($datos['categorias_colores'] == '') {
                    die("Error en DetalleWeb: Al arreglo mapa, para la columna $columna le falta el dato 'categorias_colores'.");
                }
                if ($datos['categorias_descripciones'] == '') {
                    die("Error en DetalleWeb: Al arreglo mapa, para la columna $columna le falta el dato 'categorias_descripciones'.");
                }
                if ($datos['descripcion'] == '') {
                    die("Error en DetalleWeb: Al arreglo mapa, para la columna $columna le falta el dato 'descripcion'.");
                }
                // Para que se vea bonito
                $geojson   = "\$this->{$columna}_geojson";
                $categoria = '$this->'.$datos['categoria'];
                $color     = "{$datos['categorias_colores']}[$categoria]";
                $popup     = '$this->'.$datos['descripcion'];
                // Agregar código
                $a[] = "        // Agregar mapa al detalle";
                $a[] = "        \$mapa = new \\Base2\\MapaWeb('detalle');";
                $a[] = "        \$mapa->agregar_categoria($categoria, $color);";
                $a[] = "        \$mapa->agregar_geopunto(\$this->id, $geojson, $categoria, $popup);";
                $a[] = "        \$this->detalle->al_final(\$mapa);";
            }
        }
        // Entregar
        if (count($a) > 0) {
            return implode("\n", $a);
        } else {
            return false;
        }
    } // elaborar_html_detalle_seccion_mapa

    /**
     * Elaborar HTML ImagenWebUltima
     *
     * @return string Código PHP
     */
    protected function elaborar_html_imagen_web_ultima() {
        // Si no tiene hijos, no hace nada
        if (!is_array($this->hijos) || (count($this->hijos) == 0)) {
            return false;
        }
        // Bucle para encotrar que un hijo sea de imágenes
        $encontrado = false;
        foreach ($this->hijos as $identificador => $reptil) {
            if ($reptil['contenido'] == 'imagenes') {
                $encontrado = true;
                break;
            }
        }
        // Si ningún hijo tiene imágenes, no hace nada
        if (!$encontrado) {
            return false;
        }
        // Entregar
        $modulo = $reptil['clase_plural'];
        return <<<FINAL
        // Cargar la última imagen
        \$imagen = new \\{$modulo}\\ImagenWebUltima(\$this->sesion);
        \$imagen->configurar_para_detalle();
        try {
            \$imagen->consultar(\$this->id);
            \$imagen->usar_tamano('middle');
            \$imagen->vincular('big');
            \$this->detalle->imagen(\$imagen);
        } catch (\\Base2\\ListadoExceptionVacio \$e) {
            // No tiene imágenes, por lo tanto no se carga nada
        }
FINAL;
    } // elaborar_html_imagen_web_ultima

    /**
     * Elaborar HTML Detalle
     *
     * @return string Código PHP
     */
    protected function elaborar_html_detalle() {
        // En este arreglo juntaremos el codigo
        $a = array();
        // Seccion datos generales
        $a[] = "        // Sección datos generales";
        $a[] = "        \$this->detalle->seccion('Datos Generales');";
        // Detalle de cada columna y las relaciones
        $a[] = $this->elaborar_html_detalle_propiedades();
        // Acumular secciones
        if ($seccion = $this->elaborar_html_detalle_seccion_registro()) {
            $a[] = $seccion;
        }
        if ($seccion = $this->elaborar_html_detalle_seccion_imagen()) {
            $a[] = $seccion;
        }
        if ($seccion = $this->elaborar_html_imagen_web_ultima()) {
            $a[] = $seccion;
        }
        if ($seccion = $this->elaborar_html_detalle_seccion_mapa()) {
            $a[] = $seccion;
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_html_detalle

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
        // Debe estar consultado, de lo contrario se consulta y si falla se muestra mensaje
        if (!\$this->consultado) {
            try {
                \$this->consultar();
            } catch (\\Exception \$e) {
                \$mensaje = new \\Base2\\MensajeWeb(\$e->getMessage());
                return \$mensaje->html(\$in_encabezado);
            }
        }
        // Iniciar detalle
        \$this->detalle = new \\Base2\\DetalleWeb();
{$this->elaborar_html_detalle()}
        // Pasar la barra
        \$this->detalle->barra = \$this->barra(\$in_encabezado);
        // Entregar
        return \$this->detalle->html();
    } // html

FINAL;
    } // php

} // Clase HTML

?>
