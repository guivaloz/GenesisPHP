<?php
/**
 * GenesisPHP - PaginaWeb AcordeonesPadreEHijos
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
 * Clase AcordeonesPadreEHijos
 */
class AcordeonesPadreEHijos extends \Base\Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP o falso
     */
    public function php() {
        // No entregar nada si NO hay primary key
        if ($this->primary_key === FALSE) {
            return FALSE;
        }
        // No entregar nada si NO tiene hijos
        if (!is_array($this->hijos) || (count($this->hijos) == 0)) {
            return FALSE;
        }
        // Iniciar arreglo donde juntar código PHP sobre los hijos
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
            // Acumular
            $h[] = "            if (\$this->sesion->permisos['$clave']) {";
            if ($hijo['listados'] == 'trenes') {
                $h[] = "                {$instancia} = new \\{$clase}\\TrenWeb(\$this->sesion);";
            } else {
                $h[] = "                {$instancia} = new \\{$clase}\\ListadoWeb(\$this->sesion);";
            }
            $h[] = "                {$instancia}->$filtro = \$detalle->{$this->primary_key};";
            $h[] = "                {$instancia}->estatus = '$enuso';";
            $h[] = "                {$instancia}->limit = 0;";
            $h[] = "                \$acordeones->agregar('$etiqueta', {$instancia});";
            $h[] = "            }";
        }
        // Juntar todo
        $todo = implode("\n", $h);
        // Entregar
        return <<<FINAL
    /**
     * Crear acordeones padre e hijos
     *
     * Elabora un conjunto de acordeones, el primero con el detalle, luego los listados o trenes de sus relaciones
     *
     * @param  mixed Instancia de DetalleWeb, BusquedaWeb con el ID cargado
     * @return mixed Instancia de collapse o el detalle/formulario fallido
     */
    protected function crear_acordeones_padre_e_hijos(\$in_instancia) {
        // Si es una busqueda
        if (\$in_instancia instanceof \\Base2\\BusquedaWeb) {
            // Ejecutar su método HTML
            \$html = \$in_instancia->html();
            if (\$in_instancia->hay_resultados && (\$in_instancia->resultado instanceof DetalleWeb)) {
                // El resultado de la búsqueda es un detalle que usaremos más adelante
                \$detalle = \$in_instancia->resultado;
            } else {
                // Es un listado o formulario, se entrega
                return \$in_instancia;
            }
        } elseif (\$in_instancia instanceof DetalleWeb) {
            // Es un detalle que usaremos más adelante
            \$detalle = \$in_instancia;
        } else {
            // No hay detalle, se entrega
            return \$in_instancia;
        }
        // Ya tenemos el detalle, ejecutar su método HTML
        \$html = \$detalle->html();
        // Si se levantó la bandera consultado
        if (\$detalle->consultado == true) {
            // Elaborar acordeones, primero el detalle, luego los listados o trenes de los hijos
            \$acordeones = new \\Base2\\AcordeonesWeb('acordeones');
            \$acordeones->agregar('Detalle', \$detalle, TRUE);
$todo
            // Entregar
            return \$acordeones;
        } else {
            // No se levantó la bandera consultado, tal vez tenga un mensaje que mostrar, se entrega
            return \$in_instancia;
        }
    } // crear_acordeones_padre_e_hijos

FINAL;
    } // php

} // Clase AcordeonesPadreEHijos

?>
