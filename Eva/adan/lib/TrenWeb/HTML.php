<?php
/**
 * GenesisPHP - TrenWeb HTML
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

namespace TrenWeb;

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
     * Elaborar HTML Alimentar Vagones
     *
     * @return string Código PHP
     */
    protected function elaborar_html_alimentar_vagones() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Validaciones
        if (is_array($this->imagen)) {
            $caracteres = $this->imagen['caracteres'];
            if ($caracteres == '') {
                die('Error en TrenWeb, HTML: Al arreglo imagen le falta el valor para "caracteres".');
            }
            if ($this->imagen['tamaños']['small'] == '') {
                die('Error en TrenWeb, HTML: Al arreglo imagen le falta el tamaño "small".');
            }
        } else {
            die('Error en TrenWeb, HTML: Propiedad imagen no está definida.');
        }
        // Alimentar vagones
        $a[] = "        // Alimentar los vagones con instancias de cada imagen";
        $a[] = "        \$imagen = new \\Base2\\ImagenWeb(Registro::\$imagen_almacen_ruta, Registro::\$imagen_tamanos);";
        $a[] = "        foreach (\$this->listado as \$a) {";
        $a[] = "            \$imagen->cargar(\$a['id'], \$a['$caracteres'], 'small');";
        $a[] = "            \$imagen->vincular(sprintf('SED_ARCHIVO_PLURAL.php?id=%d', \$a['id']));";
        $a[] = "            \$imagen->pie = \"{$this->columnas_vip_de_listado()}\";";
        $a[] = "            \$this->tren_controlado->vagones[] = clone \$imagen;";
        $a[] = "        }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_html_alimentar_vagones

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
{$this->elaborar_html_alimentar_vagones()}
        // Pasar al tren controlado
        \$this->tren_controlado->cantidad_registros = \$this->cantidad_registros;
        \$this->tren_controlado->variables          = \$this->filtros_param;
        \$this->tren_controlado->limit              = \$this->limit;
        \$this->tren_controlado->barra              = \$this->barra(\$in_encabezado);
        // Entregar
        return \$this->tren_controlado->html();
    } // html

FINAL;
    } // php

} // Clase HTML

?>
