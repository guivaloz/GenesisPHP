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
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_imprimir() {
        // No entregar nada si NO hay que crear impresiones
        if ($this->reptil['contenido'] != 'impresiones') {
            return FALSE;
        }
        // Validar que se haya definido el padre
        if ($this->padre === FALSE) {
            die('Error en PaginaWeb: Este módulo con contenido impresiones no tiene padre. Debe tener uno por lo menos.');
        }
        // Tomar solo el primer padre
        $padre = reset($this->padre);
        // Entregar
        return <<<FIN
if ((\$_GET['{$padre['instancia_singular']}'] != '') && (\$_GET['caracteresazar'] != '')) {
                // Impresion
                \$impresion = new ImpresionWeb(\$this->sesion);
                \$lenguetas->agregar('Impresión', \$impresion, TRUE);
            }
FIN;
    } // elaborar_html_accion_imprimir

    /**
     * Elaborar HTML Acción Modificar
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_modificar() {
        // No entregar nada si NO hay primary key
        if ($this->primary_key === FALSE) {
            return FALSE;
        }
        // No entregar nada si NO hay que crear formulario
        if (!$this->adan->si_hay_que_crear('formulario')) {
            return FALSE;
        }
        // Entregar
        return <<<FIN
if ((\$_GET['{$this->primary_key}'] != '') && (\$_GET['accion'] == DetalleWeb::\$accion_modificar)) {
                \$formulario = new FormularioWeb(\$this->sesion);
                \$formulario->{$this->primary_key} = \$_GET['id'];
                \$lenguetas->agregar('Modificar', \$formulario, TRUE);
            }
FIN;
    } // elaborar_html_accion_modificar

    /**
     * Elaborar HTML Acción Eliminar
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_eliminar() {
        // // No entregar nada si NO hay primary key
        if ($this->primary_key === FALSE) {
            return FALSE;
        }
        // No entregar nada si NO hay que crear eliminar
        if (!$this->adan->si_hay_que_crear('eliminar')) {
            return FALSE;
        }
        // Entregar
        return <<<FIN
if ((\$_GET['{$this->primary_key}'] != '') && (\$_GET['accion'] == DetalleWeb::\$accion_eliminar)) {
                \$eliminar = new EliminarWeb(\$this->sesion);
                \$eliminar->{$this->primary_key} = \$_GET['id'];
                \$lenguetas->agregar('Eliminar', \$eliminar, TRUE);
            }
FIN;
    } // elaborar_html_accion_eliminar

    /**
     * Elaborar HTML Acción Recuperar
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_recuperar() {
        // No entregar nada si NO hay primary key
        if ($this->primary_key === FALSE) {
            return FALSE;
        }
        // No entregar nada si NO hay que crear recuperar
        if (!$this->adan->si_hay_que_crear('recuperar')) {
            return FALSE;
        }
        // Entregar
        return <<<FIN
if ((\$_GET['{$this->primary_key}'] != '') && (\$_GET['accion'] == DetalleWeb::\$accion_recuperar)) {
                \$recuperar = new RecuperarWeb(\$this->sesion);
                \$recuperar->{$this->primary_key} = \$_GET['id'];
                \$lenguetas->agregar('Recuperar', \$recuperar, TRUE);
            }
FIN;
    } // elaborar_html_accion_recuperar

    /**
     * Elaborar HTML Acción Detalle
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_detalle() {
        // No entregar nada si NO hay primary key
        if ($this->primary_key === FALSE) {
            return FALSE;
        }
        // Si tiene hijos
        if (is_array($this->hijos) && (count($this->hijos) > 0)) {
            // Tiene hijos, se usará el acordeón padres e hijos
            return <<<FIN
if (\$_GET['{$this->primary_key}'] != '') {
                \$detalle = new DetalleWeb(\$this->sesion);
                \$detalle->{$this->primary_key} = \$_GET['{$this->primary_key}'];
                \$lenguetas->agregar('Detalle', \$this->crear_acordeones_padre_e_hijos(\$detalle), TRUE);
            }
FIN;
        } else {
            // No hay hijos, mostrar solo el detalle
            return <<<FIN
if (\$_GET['{$this->primary_key}'] != '') {
                \$detalle = new DetalleWeb(\$this->sesion);
                \$detalle->{$this->primary_key} = \$_GET['{$this->primary_key}'];
                \$lenguetas->agregar('Detalle', \$detalle, TRUE);
            }
FIN;
        }
    } // elaborar_html_accion_detalle

    /**
     * Elaborar HTML Acción Formulario Recibir
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_formulario_recibir() {
        // No entregar nada si NO hay que crear formulario
        if (!$this->adan->si_hay_que_crear('formulario')) {
            return FALSE;
        }
        // Si tiene hijos
        if (is_array($this->hijos) && (count($this->hijos) > 0)) {
            // Tiene hijos, se usará el acordeón padres e hijos
            return <<<FIN
if (\$_POST['formulario'] == FormularioWeb::\$form_name) {
                \$formulario = new FormularioWeb(\$this->sesion);
                \$lenguetas->agregar('Formulario', \$this->crear_acordeones_padre_e_hijos(\$formulario), TRUE);
            }
FIN;
        } else {
            // No hay hijos
            return <<<FIN
if (\$_POST['formulario'] == FormularioWeb::\$form_name) {
                \$formulario = new FormularioWeb(\$this->sesion);
                \$lenguetas->agregar('Formulario', \$formulario, TRUE);
            }
FIN;
        }
    } // elaborar_html_accion_formulario_recibir

    /**
     * Elaborar HTML Acción Búsqueda
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_busqueda() {
        // No entregar nada si NO hay que crear búsqueda
        if (!$this->adan->si_hay_que_crear('busqueda')) {
            return FALSE;
        }
        // Si tiene hijos
        if (is_array($this->hijos) && (count($this->hijos) > 0)) {
            // Tiene hijos, se usará el acordeón padres e hijos
            return <<<FIN
            // Búsqueda
            \$busqueda  = new BusquedaWeb(\$this->sesion);
            \$resultado = \$busqueda->html(); // TODO: Ejecuto el método para consultar las banderas, mejorar
            if (\$busqueda->hay_resultados) {
                \$lenguetas->agregar('Resultados', \$this->crear_acordeones_padre_e_hijos(\$busqueda), TRUE);
            } elseif (\$busqueda->hay_mensaje) {
                \$lenguetas->agregar('Buscar', \$busqueda, TRUE);
            } else {
                \$lenguetas->agregar('Buscar', \$busqueda);
            }
FIN;
        } else {
            // No hay hijos
            return <<<FIN
            // Búsqueda
            \$busqueda  = new BusquedaWeb(\$this->sesion);
            \$resultado = \$busqueda->html(); // TODO: Ejecuto el método para consultar las banderas, mejorar
            if (\$busqueda->hay_resultados) {
                \$lenguetas->agregar('Resultados', \$busqueda, TRUE);
            } elseif (\$busqueda->hay_mensaje) {
                \$lenguetas->agregar('Buscar', \$busqueda, TRUE);
            } else {
                \$lenguetas->agregar('Buscar', \$busqueda);
            }
FIN;
        }
    } // elaborar_html_accion_busqueda

    /**
     * Elaborar HTML Acción Listados
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_listados() {
        // No entregar nada si NO hay que crear listados
        if (!$this->adan->si_hay_que_crear('listado')) {
            return FALSE;
        }
        // Si hay estatus, mostrar listados para cada letra
        if (is_array($this->estatus)) {
            // Si es lo convencional con EN USO y ELIMINADO
            if ((count($this->estatus) == 2) && ($this->estatus['enuso'] = 'A') && ($this->estatus['eliminado'] == 'B')) {
                return <<<FIN
            // Listados de en uso y eliminados
            \$listado = new ListadoWeb(\$this->sesion);
            if (\$listado->viene_listado) {
                \$lenguetas->agregar('Listado', \$listado, TRUE);
            } else {
                // En uso
                \$listado->estatus = 'A';
                \$lenguetas->agregar('En uso', \$listado, FALSE, TRUE); // Lengüeta activa por defecto
                // Eliminados
                if (\$this->sesion->puede_recuperar()) {
                    \$listado = new ListadoWeb(\$this->sesion);
                    \$listado->estatus = 'B';
                    \$lenguetas->agregar('Eliminados', \$listado);
                }
            }
FIN;
            } else {
                // No es convencional, estatus tiene otras letras
                $e = $this->tabla['estatus'];
                $a = array();
                foreach ($e['etiquetas'] as $caracter => $etiqueta) {
                    $a[] = <<<FIN
                // {$e['descripciones'][$caracter]}
                \$listado = new ListadoWeb(\$this->sesion);
                \$listado->estatus = '{$caracter}';
                \$lenguetas->agregar('{$etiqueta}', \$listado);
FIN;
                }
                $listados_para_estatus = implode("\n", $a);
                return <<<FIN
            // Listados
            \$listado = new ListadoWeb(\$this->sesion);
            if (\$listado->viene_listado) {
                \$lenguetas->agregar('Listado', \$listado, TRUE);
            } else {
$listados_para_estatus
            }
FIN;
            }
        } else {
            // No hay estatus, el listado va sin filtros
            return <<<FIN
            // Listado
            \$listado = new ListadoWeb(\$this->sesion);
            if (\$listado->viene_listado) {
                \$lenguetas->agregar('Listado', \$listado, TRUE);
            } else {
                // Listado sin filtros
                \$listado = new ListadoWeb(\$this->sesion);
                \$lenguetas->agregar('Listado', \$listado);
            }
FIN;
        }
    } // elaborar_html_accion_listados

    /**
     * Elaborar HTML Acción Trenes
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_trenes() {
        // No entregar nada si NO hay que crear tren
        if (!$this->adan->si_hay_que_crear('tren')) {
            return FALSE;
        }
        // Si hay estatus, mostrar listados para cada letra
        if (is_array($this->estatus)) {
            // Si es lo convencional con a y b
            if (($this->estatus['enuso'] = 'A') && ($this->estatus['eliminado'] == 'B')) {
                return <<<FIN
            // Trenes
            \$tren = new TrenWeb(\$this->sesion);
            if (\$tren->viene_tren) {
                \$lenguetas->agregar('Imágenes', \$tren, TRUE);
            } else {
                // En uso
                \$tren->estatus = 'A';
                \$lenguetas->agregar('En uso', \$tren, FALSE, TRUE); // Lengüeta activa por defecto
                // Eliminados
                if (\$this->sesion->puede_recuperar()) {
                    \$tren = new TrenWeb(\$this->sesion);
                    \$tren->estatus = 'B';
                    \$lenguetas->agregar('Eliminados', \$tren);
                }
            }
FIN;
            } else {
                // No es convencional, estatus tiene otras letras
                $a = array();
                $e = $this->tabla['estatus'];
                foreach ($e['etiquetas'] as $caracter => $etiqueta) {
                    $a[] = <<<FIN
                // {$e['descripciones'][$caracter]}
                \$tren = new TrenWeb(\$this->sesion);
                \$tren->estatus = '{$caracter}';
                \$lenguetas->agregar('{$etiqueta}', \$tren);
FIN;
                }
                $listados_para_estatus = implode("\n", $a);
                return <<<FIN
            // Trenes
            \$tren = new TrenWeb(\$this->sesion);
            if (\$tren->viene_tren) {
                // Viene un tren previo
                \$lenguetas->agregar('Imágenes', \$tren, TRUE);
            } else {
$listados_para_estatus
            }
FIN;
            }
        } else {
            // No hay estatus, el listado va sin filtros
                return <<<FIN
            // Tren
            \$tren = new TrenWeb(\$this->sesion);
            if (\$tren->viene_tren) {
                // Viene un tren previo
                \$lenguetas->agregar('Imágenes', \$tren, TRUE);
            } else {
                // Tren sin filtros
                \$tren = new TrenWeb(\$this->sesion);
                \$lenguetas->agregar('Imágenes', \$tren);
            }
FIN;
        }
    } // elaborar_html_accion_trenes

    /**
     * Elaborar HTML Acción Nuevo
     *
     * @return mixed Código PHP o falso
     */
    protected function elaborar_html_accion_nuevo() {
        // No entregar nada si NO hay que crear formulario
        if (!$this->adan->si_hay_que_crear('formulario')) {
            return FALSE;
        }
        // Entregar
        return <<<FIN
            // Nuevo
            if (\$this->sesion->puede_agregar()) {
                \$formulario = new FormularioWeb(\$this->sesion);
                \$formulario->{$this->primary_key} = 'agregar';
                if (\$_GET['accion'] == 'agregar') {
                    \$lenguetas->agregar('Nuevo', \$formulario, TRUE);
                } else {
                    \$lenguetas->agregar('Nuevo', \$formulario);
                }
            }
FIN;
    } // elaborar_html_accion_nuevo

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Iniciar arreglo para juntar todo
        $a = array();
        // Iniciar arreglo para las acciones a un registro, que se unirán con else
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
        // Juntar todo
        $todo = implode("\n", $a);
        // Entregar
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
            \$lenguetas = new \Base2\LenguetasWeb('lenguetas');
$todo
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
