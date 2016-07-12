<?php
/**
 * GenesisPHP - ListadoWeb Barra
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
 * Clase Barra
 */
class Barra extends \Base\Plantilla {

    /**
     * Botón Agregar Registro
     *
     * @return string Código PHP
     */
    protected function boton_agregar_registro() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Boton para agregar
        if (is_array($this->padre) && (count($this->padre) > 0)) {
            foreach ($this->padre as $p) {
                // Para que se vea bonito
                $padre            = $p['instancia_singular'];
                $agregar_etiqueta = '<span class="glyphicon glyphicon-plus"></span> SED_SUBTITULO_SINGULAR';
                $agregar_url      = sprintf('SED_ARCHIVO_PLURAL.php?%s={$this->%s}&accion=agregar', $padre, $padre);
                // Agregar
                $a[] = "        // Botón agregar";
                $a[] = "        if ((\$this->$padre != '') && (\$this->sesion->puede_agregar('SED_CLAVE'))) {";
                $a[] = "            \$barra->boton_agregar(\"{$agregar_url}\", '$agregar_etiqueta');";
                $a[] = "        }";
            }
        }
        // Entregar
        if (count($a) > 0) {
            return implode("\n", $a)."\n";
        } else {
            return '';
        }
    } // boton_agregar_registro

    /**
     * Botón para Descargar CSV
     *
     * @return string Código PHP
     */
    protected function boton_descargar_csv() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Boton para descargar csv
        if ($this->adan->si_hay_que_crear('listadocsv') > 0) {
            $a[] = "        // Botón descargar CSV";
            $a[] = "        \$barra->boton_descargar(\"SED_ARCHIVO_PLURAL.csv\", \$this->filtros_param, '<span class=\"glyphicon glyphicon-floppy-save\"></span> CSV');";
        }
        // Entregar
        if (count($a) > 0) {
            return implode("\n", $a)."\n";
        } else {
            return '';
        }
    } // boton_descargar_csv

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Barra
     *
     * @param  string Encabezado opcional
     * @return mixed  Instancia de BarraHTML
     */
    public function barra(\$in_encabezado='') {
        // Si viene el encabezado como parametro se usa, de lo contrario se ejecuta el método encabezado
        if (\$in_encabezado !== '') {
            \$encabezado = \$in_encabezado;
        } else {
            \$encabezado = \$this->encabezado();
        }
        // Crear la barra
        \$barra             = new \\Base2\\BarraWeb();
        \$barra->encabezado = \$encabezado;
        \$barra->icono      = \$this->sesion->menu->icono_en('SED_CLAVE');
{$this->boton_agregar_registro()}{$this->boton_descargar_csv()}        // Entregar
        return \$barra;
    } // barra

FINAL;
    } // php

} // Clase Barra

?>
