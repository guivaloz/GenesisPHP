<?php
/**
 * GenesisPHP - PaginaWeb CollapsePadreEHijos
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

namespace PaginaWeb;

/**
 * Clase CollapsePadreEHijos
 */
class CollapsePadreEHijos extends \Base\Plantilla {

    /**
     * Elaborar
     *
     * @return string Código PHP
     */
    protected function elaborar_html_hijos() {
        // Si no tiene hijos, no entrega nada
        if (!is_array($this->hijos) || (count($this->hijos) == 0)) {
            return '';
        }
        // Si no hay columna primary key, no hay detalle
        if ($this->primary_key === false) {
            return '';
        }
        // En este arreglo juntaremos las lineas de los hijos
        $h = array();
        // Bucle para cada hijo
        foreach ($this->hijos as $hijo) {
            // Para que se vea bonito
            $clave     = $hijo['clave'];
            $instancia = "\${$hijo['instancia_plural']}";
            $clase     = $hijo['clase_plural'];
            $filtro    = $this->instancia_singular;
            $etiqueta  = $hijo['etiqueta_plural'];
            $enuso     = $hijo['estatus']['enuso'];
            // Agregar enseguida del detalle
            $h[] = "            if (\$this->sesion->permisos['$clave']) {";
            if ($hijo['listados'] == 'trenes') {
                $h[] = "                {$instancia} = new \\{$clase}\\TrenWeb(\$this->sesion);";
            } else {
                $h[] = "                {$instancia} = new \\{$clase}\\ListadoWeb(\$this->sesion);";
            }
            $h[] = "                {$instancia}->$filtro = \$detalle->{$this->primary_key};";
            $h[] = "                {$instancia}->estatus = '$enuso';";
            $h[] = "                {$instancia}->limit = 0;";
            $h[] = "                \$collapse->agregar('$clase', '$etiqueta', {$instancia}->html());";
            $h[] = "                \$collapse->agregar_javascript({$instancia}->javascript());";
            $h[] = "            }";
        }
        // Entregar
        return implode("\n", $h);
    } // elaborar_html_hijos

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Elaborar Detalle Padre Listados Hijos
     *
     * @param  mixed Instancia de DetalleWeb, BusquedaWeb con el ID cargado
     * @return mixed Instancia de collapse o el detalle/formulario fallido
     */
    protected function crear_collapse_padre_hijos(\$in_instancia) {
        // Si es una busqueda
        if (\$in_instancia instanceof \\Base2\\BusquedaWeb) {
            // Ejecutar metodo html de la busqueda
            \$html = \$in_instancia->html();
            if (\$in_instancia->hay_resultados && (\$in_instancia->resultado instanceof DetalleWeb)) {
                // Tiene un DetalleWeb que procesaremos
                \$detalle = \$in_instancia->resultado;
            } else {
                // Es un listado o formulario, se regresa
                return \$in_instancia;
            }
        } elseif (\$in_instancia instanceof DetalleWeb) {
            // Es un DetalleWeb
            \$detalle = \$in_instancia;
        } else {
            // No es ni busqueda ni detalle, se regresa
            return \$in_instancia;
        }
        // A partir de aqui se tiene DetalleWeb, ejecutar su metodo html
        \$html = \$detalle->html();
        // Si fue consultado
        if (\$detalle->consultado == true) {
            // Elaborar collapse
            \$collapse = new \\Base2\\CollapseWeb('pinpon');
            \$collapse->hay_resultados = (\$in_instancia->hay_resultados == true);
            \$collapse->agregar('detalle', 'Detalle', \$html);
            \$collapse->agregar_javascript(\$detalle->javascript());
{$this->elaborar_html_hijos()}
            // Entregar
            return \$collapse;
        } else {
            // No se levantó la bandera consultado, tal vez tenga un mensaje
            return \$in_instancia;
        }
    } // crear_collapse_padre_hijos

FINAL;
    } // php

} // Clase CollapsePadreEHijos

?>
