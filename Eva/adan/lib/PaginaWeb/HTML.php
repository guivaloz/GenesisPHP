<?php
/**
 * GenesisPHP - PaginaWeb HTML
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
 * Clase HTML
 */
class HTML extends \Base\Plantilla {

    /**
     * Elaborar HTML Acción Imprimir
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_imprimir() {
        // Condicion
        if ($this->reptil['contenido'] != 'impresiones') {
            return false;
        }
        // Validar que se haya definido el padre
        if ($this->padre === false) {
            die('Error en PaginaWeb: Este módulo con contenido impresiones no tiene padre. Debe tener uno por lo menos.');
        }
        // Tomar solo el primer padre
        $padre = reset($this->padre);
        // Entregar
        return <<<FIN
if ((\$_GET['{$padre['instancia_singular']}'] != '') && (\$_GET['caracteresazar'] != '')) {
                // Impresion
                \$impresion = new ImpresionWeb(\$this->sesion);
                \$lenguetas->agregar('SED_CLASE_SINGULARImprimir', 'Impresión', \$impresion->html(\$_GET['{$padre['instancia_singular']}'], \$_GET['caracteresazar']));
                \$lenguetas->agregar_javascript(\$impresion->javascript());
                \$lenguetas->definir_activa();
            }
FIN;
    } // elaborar_html_accion_imprimir

    /**
     * Elaborar HTML Acción Modificar
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_modificar() {
        // Si no hay columna primary key, no hay modificar
        if ($this->primary_key === false) {
            return false;
        }
        // Condicion
        if (!$this->adan->si_hay_que_crear('formulario')) {
            return false;
        }
        // Entregar
        return <<<FIN
if ((\$_GET['{$this->primary_key}'] != '') && (\$_GET['accion'] == DetalleWeb::\$accion_modificar)) {
                // Modificar
                \$formulario     = new FormularioWeb(\$this->sesion);
                \$formulario->id = \$_GET['id'];
                \$lenguetas->agregar('SED_CLASE_SINGULARModificar', 'Modificar', \$formulario->html());
                \$lenguetas->agregar_javascript(\$formulario->javascript());
                \$lenguetas->definir_activa();
            }
FIN;
    } // elaborar_html_accion_modificar

    /**
     * Elaborar HTML Acción Eliminar
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_eliminar() {
        // Si no hay columna primary key, no hay eliminar
        if ($this->primary_key === false) {
            return false;
        }
        // Condicion
        if (!$this->adan->si_hay_que_crear('eliminar')) {
            return false;
        }
        // Entregar
        return <<<FIN
if ((\$_GET['{$this->primary_key}'] != '') && (\$_GET['accion'] == DetalleWeb::\$accion_eliminar)) {
                // Eliminar
                \$eliminar     = new DetalleWeb(\$this->sesion);
                \$eliminar->id = \$_GET['id'];
                \$lenguetas->agregar('SED_CLASE_SINGULAREliminar', 'Eliminar', \$eliminar->eliminar_html());
                \$lenguetas->agregar_javascript(\$eliminar->javascript());
                \$lenguetas->definir_activa();
            }
FIN;
    } // elaborar_html_accion_eliminar

    /**
     * Elaborar HTML Acción Recuperar
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_recuperar() {
        // Si no hay columna primary key, no hay recuperar
        if ($this->primary_key === false) {
            return false;
        }
        // Condicion
        if (!$this->adan->si_hay_que_crear('recuperar')) {
            return false;
        }
        // Entregar
        return <<<FIN
if ((\$_GET['{$this->primary_key}'] != '') && (\$_GET['accion'] == DetalleWeb::\$accion_recuperar)) {
                // Recuperar
                \$recuperar     = new DetalleWeb(\$this->sesion);
                \$recuperar->id = \$_GET['id'];
                \$lenguetas->agregar('SED_CLASE_SINGULARRecuperar', 'Recuperar', \$recuperar->recuperar_html());
                \$lenguetas->agregar_javascript(\$recuperar->javascript());
                \$lenguetas->definir_activa();
            }
FIN;
    } // elaborar_html_accion_recuperar

    /**
     * Elaborar HTML Acción Detalle
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_detalle() {
        // Si no hay columna primary key, no hay detalle
        if ($this->primary_key === false) {
            return false;
        }
        // Juntaremos el codigo en este arreglo
        $d   = array();
        $d[] = "if (\$_GET['{$this->primary_key}'] != '') {";
        // Si tiene hijos
        if (is_array($this->hijos) && (count($this->hijos) > 0)) {
            // Tiene hijos, se usara collapse para mostrar el detalle y sus hijos
            $d[] = "                // Detalle e hijos";
            $d[] = "                \$detalle = new DetalleWeb(\$this->sesion);";
            $d[] = "                \$detalle->{$this->primary_key} = \$_GET['{$this->primary_key}'];";
            $d[] = "                \$regalo = \$this->crear_collapse_padre_hijos(\$detalle);";
            $d[] = "                \$lenguetas->agregar('SED_CLASE_SINGULARDetalle', 'Detalle', \$regalo->html());";
            $d[] = "                \$lenguetas->agregar_javascript(\$regalo->javascript());";
            $d[] = "                \$lenguetas->definir_activa();";
        } else {
            // No hay hijos, mostrar solo el detalle
            $d[] = "                // Detalle";
            $d[] = "                \$detalle = new DetalleWeb(\$this->sesion);";
            $d[] = "                \$detalle->{$this->primary_key} = \$_GET['{$this->primary_key}'];";
            $d[] = "                \$lenguetas->agregar('SED_CLASE_SINGULARDetalle', 'Detalle', \$detalle->html());";
            $d[] = "                \$lenguetas->agregar_javascript(\$detalle->javascript());";
            $d[] = "                \$lenguetas->definir_activa();";
        }
        $d[] = "            }";
        // Entregar
        return implode("\n", $d);
    } // elaborar_html_accion_detalle

    /**
     * Elaborar HTML Acción Formulario Recibir
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_formulario_recibir() {
        // Condicion
        if (!$this->adan->si_hay_que_crear('formulario')) {
            return false;
        }
        // Juntaremos el codigo en este arreglo
        $a = array();
        $a[] = "if (\$_POST['formulario'] == FormularioWeb::\$form_name) {";
        $a[] = "                // Viene el formulario";
        $a[] = "                \$formulario = new FormularioWeb(\$this->sesion);";
        // Si tiene hijos
        if (is_array($this->hijos) && (count($this->hijos) > 0)) {
            // Tiene hijos
            $a[] = "                \$regalo = \$this->crear_collapse_padre_hijos(\$formulario);";
            $a[] = "                \$lenguetas->agregar('SED_CLASE_PLURALFormulario', 'Formulario', \$regalo->html());";
            $a[] = "                \$lenguetas->agregar_javascript(\$regalo->javascript());";
        } else {
            // No hay hijos
            $a[] = "                \$lenguetas->agregar('SED_CLASE_SINGULARFormulario', 'Formulario', \$formulario->html());";
            $a[] = "                \$lenguetas->agregar_javascript(\$formulario->javascript());";
        }
        $a[] = "                \$lenguetas->definir_activa();";
        $a[] = "            }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_html_accion_formulario_recibir

    /**
     * Elaborar HTML Acción Búsqueda
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_busqueda() {
        // Condicion
        if (!$this->adan->si_hay_que_crear('busqueda')) {
            return false;
        }
        // Si tiene hijos
        if (is_array($this->hijos) && (count($this->hijos) > 0)) {
            // Tiene hijos
            return <<<FIN
            // Búsqueda, crea dos lengüetas si hay resultados
            \$busqueda = new BusquedaWeb(\$this->sesion);
            \$regalo   = \$this->crear_collapse_padre_hijos(\$busqueda);
            if (\$regalo->hay_resultados) {
                \$lenguetas->agregar('SED_CLASE_SINGULARBuscar',    'Buscar',    \$busqueda->formulario_html());
                \$lenguetas->agregar('SED_CLASE_SINGULARResultado', 'Resultado', \$regalo->html());
                \$lenguetas->definir_activa();
            } elseif (\$busqueda->hay_mensaje) {
                \$lenguetas->agregar_activa('SED_CLASE_SINGULARBuscar', 'Buscar', \$regalo->html());
            } else {
                \$lenguetas->agregar('SED_CLASE_SINGULARBuscar',    'Buscar',    \$regalo->html());
            }
            \$lenguetas->agregar_javascript(\$regalo->javascript());
FIN;
        } else {
            // No hay hijos
            return <<<FIN
            // Búsqueda, crea dos lengüetas si hay resultados
            \$busqueda  = new BusquedaWeb(\$this->sesion);
            \$resultado = \$busqueda->html();
            if (\$busqueda->hay_resultados) {
                \$lenguetas->agregar('SED_CLASE_SINGULARBuscar',    'Buscar',    \$busqueda->formulario_html());
                \$lenguetas->agregar('SED_CLASE_SINGULARResultado', 'Resultado', \$resultado);
                \$lenguetas->definir_activa();
            } elseif (\$busqueda->hay_mensaje) {
                \$lenguetas->agregar_activa('SED_CLASE_SINGULARBuscar', 'Buscar', \$resultado);
            } else {
                \$lenguetas->agregar('SED_CLASE_SINGULARBuscar',    'Buscar',    \$resultado);
            }
            \$lenguetas->agregar_javascript(\$busqueda->javascript());
FIN;
        }
    } // elaborar_html_accion_busqueda

    /**
     * Elaborar HTML Acción Listados
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_listados() {
        // No entregar nada si no hay que hacer los listados
        if (!$this->adan->si_hay_que_crear('listado')) {
            return false;
        }
        // Juntaremos en este arreglo
        $l = array();
        // Si hay estatus, mostrar listados para cada letra
        if (is_array($this->estatus)) {
            // Si es lo convencional con a y b
            if (($this->estatus['enuso'] = 'A') && ($this->estatus['eliminado'] == 'B')) {
                $l[] = "            // Listados";
                $l[] = "            \$listado = new ListadoWeb(\$this->sesion);";
                $l[] = "            if (\$listado->viene_listado) {";
                $l[] = "                // Viene un listado previo";
                $l[] = "                \$lenguetas->agregar('SED_CLASE_PLURALListado', 'Listado', \$listado->html());";
                $l[] = "                \$lenguetas->agregar_javascript(\$listado->javascript());";
                $l[] = "                \$lenguetas->definir_activa();";
                $l[] = "            } else {";
                $l[] = "                // En uso";
                $l[] = "                \$listado->estatus = 'A';";
                $l[] = "                \$lenguetas->agregar('SED_CLASE_PLURALEnUso', 'En uso', \$listado->html());";
                $l[] = "                \$lenguetas->agregar_javascript(\$listado->javascript());";
                $l[] = "                if (\$lenguetas->activa == '') {";
                $l[] = "                    \$lenguetas->definir_activa();";
                $l[] = "                }";
                $l[] = "                // Eliminados";
                $l[] = "                if (\$this->sesion->puede_recuperar('SED_CLAVE')) {";
                $l[] = "                    \$listado = new ListadoWeb(\$this->sesion);";
                $l[] = "                    \$listado->estatus = 'B';";
                $l[] = "                    \$lenguetas->agregar('SED_CLASE_PLURALEliminados', 'Eliminados', \$listado->html());";
                $l[] = "                    \$lenguetas->agregar_javascript(\$listado->javascript());";
                $l[] = "                }";
                $l[] = "            }";
            } else {
                // No es convencional, estatus tiene otras letras
                $l[] = "            // Listados";
                $l[] = "            \$listado = new ListadoWeb(\$this->sesion);";
                $l[] = "            if (\$listado->viene_listado) {";
                $l[] = "                // Viene un listado previo";
                $l[] = "                \$lenguetas->agregar('SED_CLASE_PLURALListado', 'Listado', \$listado->html());";
                $l[] = "                \$lenguetas->agregar_javascript(\$listado->javascript());";
                $l[] = "                \$lenguetas->definir_activa();";
                $l[] = "            } else {";
                $e = $this->tabla['estatus'];
                $c = 0;
                foreach ($e['etiquetas'] as $caracter => $etiqueta) {
                    $c++;
                    $l[] = "                // {$e['descripciones'][$caracter]}";
                    $l[] = "                \$listado = new ListadoWeb(\$this->sesion);";
                    $l[] = "                \$listado->estatus = '{$caracter}';";
                    $l[] = "                \$lenguetas->agregar('SED_CLASE_PLURAL{$e['acciones'][$caracter]}', '{$etiqueta}', \$listado->html(false));";
                    $l[] = "                \$lenguetas->agregar_javascript(\$listado->javascript());";
                    // La primer letra puede ser la lengüeta activa
                    if ($c == 1) {
                        $l[] = "                if (\$lenguetas->activa == '') {";
                        $l[] = "                    \$lenguetas->definir_activa();";
                        $l[] = "                }";
                    }
                }
                $l[] = "            }";
            }
        } else {
            // No hay estatus, el listado va sin filtros
            $l[] = "            // Listado";
            $l[] = "            \$listado = new ListadoWeb(\$this->sesion);";
            $l[] = "            if (\$listado->viene_listado) {";
            $l[] = "                // Viene un listado previo";
            $l[] = "                \$lenguetas->agregar('SED_CLASE_PLURALListado', 'Listado', \$listado->html());";
            $l[] = "                \$lenguetas->agregar_javascript(\$listado->javascript());";
            $l[] = "                \$lenguetas->definir_activa();";
            $l[] = "            } else {";
            $l[] = "                // Listado sin filtros";
            $l[] = "                \$listado = new ListadoWeb(\$this->sesion);";
            $l[] = "                \$lenguetas->agregar('SED_CLASE_PLURALListado', 'Listado', \$listado->html());";
            $l[] = "                \$lenguetas->agregar_javascript(\$listado->javascript());";
            $l[] = "                if (\$lenguetas->activa == '') {";
            $l[] = "                    \$lenguetas->definir_activa();";
            $l[] = "                }";
            $l[] = "            }";
        }
        // Entregar
        return implode("\n", $l);
    } // elaborar_html_accion_listados

    /**
     * Elaborar HTML Acción Trenes
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_trenes() {
        // No entregar nada si no hay que hacer los trenes
        if (!$this->adan->si_hay_que_crear('tren')) {
            return false;
        }
        // Juntaremos en este arreglo
        $t = array();
        // Si hay estatus, mostrar listados para cada letra
        if (is_array($this->estatus)) {
            // Si es lo convencional con a y b
            if (($this->estatus['enuso'] = 'A') && ($this->estatus['eliminado'] == 'B')) {
                $t[] = "            // Trenes";
                $t[] = "            \$tren = new TrenWeb(\$this->sesion);";
                $t[] = "            if (\$tren->viene_tren) {";
                $t[] = "                // Viene un tren previo";
                $t[] = "                \$lenguetas->agregar('SED_CLASE_PLURALTren', 'Imágenes', \$tren->html());";
                $t[] = "                \$lenguetas->definir_activa();";
                $t[] = "            } else {";
                $t[] = "                // En uso";
                $t[] = "                \$tren->estatus = 'A';";
                $t[] = "                \$lenguetas->agregar('SED_CLASE_PLURALEnUso', 'En uso', \$tren->html());";
                $l[] = "                if (\$lenguetas->activa == '') {";
                $l[] = "                    \$lenguetas->definir_activa();";
                $l[] = "                }";
                $t[] = "                // Eliminados";
                $t[] = "                if (\$this->sesion->puede_recuperar('SED_CLAVE')) {";
                $t[] = "                    \$tren = new TrenWeb(\$this->sesion);";
                $t[] = "                    \$tren->estatus = 'B';";
                $t[] = "                    \$lenguetas->agregar('SED_CLASE_PLURALEliminados', 'Eliminados', \$tren->html());";
                $t[] = "                }";
                $t[] = "            }";
            } else {
                // No es convencional, estatus tiene otras letras
                $t[] = "            // Trenes";
                $t[] = "            \$tren = new TrenWeb(\$this->sesion);";
                $t[] = "            if (\$tren->viene_tren) {";
                $t[] = "                // Viene un tren previo";
                $t[] = "                \$lenguetas->agregar('SED_CLASE_PLURALTren', 'Imágenes', \$tren->html());";
                $t[] = "                \$lenguetas->definir_activa();";
                $t[] = "            } else {";
                $e = $this->tabla['estatus'];
                foreach ($e['etiquetas'] as $caracter => $etiqueta) {
                    $t[] = "                // {$e['descripciones'][$caracter]}";
                    $t[] = "                \$tren = new TrenWeb(\$this->sesion);";
                    $t[] = "                \$tren->estatus = '{$caracter}';";
                    $t[] = "                \$lenguetas->agregar('SED_CLASE_PLURAL{$e['acciones'][$caracter]}', '{$etiqueta}', \$tren->html(false));";
                }
                $t[] = "            }";
            }
        } else {
            // No hay estatus, el listado va sin filtros
            $t[] = "            // Tren";
            $t[] = "            \$tren = new TrenWeb(\$this->sesion);";
            $t[] = "            if (\$tren->viene_tren) {";
            $t[] = "                // Viene un listado previo";
            $t[] = "                \$lenguetas->agregar('SED_CLASE_PLURALTren', 'Imágenes', \$tren->html());";
            $t[] = "                \$lenguetas->definir_activa();";
            $t[] = "            } else {";
            $t[] = "                // Tren sin filtros";
            $t[] = "                \$tren = new TrenWeb(\$this->sesion);";
            $t[] = "                \$lenguetas->agregar('SED_CLASE_PLURALTren', 'Imágenes', \$tren->html());";
            $t[] = "            }";
        }
        // Entregar
        return implode("\n", $t);
    } // elaborar_html_accion_trenes

    /**
     * Elaborar HTML Acción Nuevo
     *
     * @return string Código PHP
     */
    protected function elaborar_html_accion_nuevo() {
        if ($this->adan->si_hay_que_crear('formulario')) {
            return <<<FIN
            // Nuevo
            if (\$this->sesion->puede_agregar('SED_CLAVE')) {
                \$formulario     = new FormularioWeb(\$this->sesion);
                \$formulario->{$this->primary_key} = 'agregar';
                \$lenguetas->agregar('SED_CLASE_PLURALNuevo', 'Nuevo', \$formulario->html());
                \$lenguetas->agregar_javascript(\$formulario->javascript());
                if (\$_GET['accion'] == 'agregar') {
                    \$lenguetas->definir_activa();
                }
            }
FIN;
        } else {
            return false;
        }
    } // elaborar_html_accion_nuevo

    /**
     * Elaborar HTML Acciones
     *
     * @return string Código PHP
     */
    protected function elaborar_html_acciones() {
        // En este arreglo juntaremos el html
        $a = array();
        // Los siguientes fragmentos, guardados en r, se uniran con else
        $r = array();
        if ($imprimir  = $this->elaborar_html_accion_imprimir())           $r[] = $imprimir;
        if ($modificar = $this->elaborar_html_accion_modificar())          $r[] = $modificar;
        if ($eliminar  = $this->elaborar_html_accion_eliminar())           $r[] = $eliminar;
        if ($recuperar = $this->elaborar_html_accion_recuperar())          $r[] = $recuperar;
        if ($detalle   = $this->elaborar_html_accion_detalle())            $r[] = $detalle;
        if ($recibir   = $this->elaborar_html_accion_formulario_recibir()) $r[] = $recibir;
        if (count($r) > 0) {
            $a[] = "            // Acciones para un registro\n            ".implode(" else", $r);
        }
        // Busqueda
        if ($busqueda = $this->elaborar_html_accion_busqueda()) $a[] = $busqueda;
        // Listado o trenes
        if ($listados = $this->elaborar_html_accion_listados()) $a[] = $listados;
        elseif ($trenes = $this->elaborar_html_accion_trenes()) $a[] = $trenes;
        // Nuevo
        if ($nuevo = $this->elaborar_html_accion_nuevo())       $a[] = $nuevo;
        // Entregar
        return implode("\n", $a);
    } // elaborar_html_acciones

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
     * @return string HTML
     */
    public function html() {
        // Solo si se carga con éxito la sesión
        if (\$this->sesion_exitosa) {
            // Lengüetas
            \$lenguetas = new \Base\LenguetasWeb();
{$this->elaborar_html_acciones()}
            // Pasar el html y el javascript de las lengüetas al contenido
            \$this->contenido[]  = \$lenguetas->html();
            \$this->javascript[] = \$lenguetas->javascript();
        }
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

FINAL;
    } // php

} // Clase HTML

?>
