<?php
/**
 * GenesisPHP - Base Creador
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

namespace Base;

/**
 * Clase Creador
 */
class Creador {

    public    $adan;                       // Objeto Adan
    protected $base_ruta = '../../htdocs'; // Texto con la ruta al directorio base

    /**
     * Constructor
     *
     * @param string Ruta de destino, base del htdocs
     */
    public function __construct($in_base_ruta) {
        $this->base_ruta = $in_base_ruta;
    } // constructor

    /**
     * Crear archivo pagina
     *
     * @param string Nombre del archivo
     * @param string Codigo PHP a escribir
     */
    protected function crear_archivo_pagina($in_archivo, $in_contenido) {
        // Crear archivo con la libreria
        $pagina_arch = "{$this->base_ruta}/$in_archivo";
        if (!($soga = fopen($pagina_arch, 'w'))) {
            throw new \Exception("ERROR en Creador: No es posible crear o escribir el archivo $pagina_arch");
        }
        if (fwrite($soga, $in_contenido) === FALSE) {
            throw new \Exception("ERROR en Creador: Al escribir contenido en $pagina_arch");
        }
        return ":-) $in_archivo";
    } // crear_archivo_pagina

    /**
     * Crear archivo libreria
     *
     * @param string Nombre del modulo, será el nombre del directorio
     * @param string Nombre del archivo
     * @param string Codigo PHP a escribir
     */
    protected function crear_archivo_libreria($in_modulo, $in_archivo, $in_contenido) {
        // Crear directorio lib
        $lib_dir = "{$this->base_ruta}/lib";
        if (!is_dir($lib_dir)) {
            throw new \Exception("ERROR en Creador: No existe el directorio lib, debería de existir.");
        }
        // Crear directorio del modulo
        $modulo_dir = "$lib_dir/$in_modulo";
        if (!is_dir($modulo_dir)) {
            if (mkdir($modulo_dir) === false) {
                throw new \Exception("ERROR en Creador: Al tratar de crear el directorio lib/$in_modulo");
            }
        }
        // Crear archivo con la libreria
        $libreria_arch = "$modulo_dir/$in_archivo";
        if (!($soga = fopen($libreria_arch, 'w'))) {
            throw new \Exception("ERROR en Creador: No es posible crear o escribir el archivo $libreria_arch");
        }
        if (fwrite($soga, $in_contenido) === FALSE) {
            throw new \Exception("ERROR en Creador: Al escribir contenido en $libreria_arch");
        }
        return ":-)   $in_archivo";
    } // crear_archivo_libreria

    /**
     * Crear
     */
    public function crear() {
        // Validar Adan
        if (!isset($this->adan)) {
            throw new \Exception ('ERROR en Creador: No está definido Adán.');
        }
        // Arreglo para juntar los mensajes
        $m = array();
        // Tomamos el nombre del modulo, sera el directorio de la libreria
        $modulo = $this->adan->nombre;
        $m[]    = $modulo;
        // Crear Registro
        $registro = new Registro($this->adan);
        $m[]      = $this->crear_archivo_libreria($modulo, "Registro.php", $registro->php());
        // Crear DetalleHTML
        $detalle_html = new DetalleHTML($this->adan);
        $m[]          = $this->crear_archivo_libreria($modulo, "DetalleHTML.php", $detalle_html->php());
        // Crear FormularioHTML
        //~ if ($this->adan->si_hay_que_crear('formulario')) {
            //~ $formulario_html = new FormularioHTML($this->adan);
            //~ $m[]             = $this->crear_archivo_libreria($modulo, "FormularioHTML.php", $formulario_html->php());
        //~ }
        // Crear Listado
        $listado = new Listado($this->adan);
        $m[]     = $this->crear_archivo_libreria($modulo, "Listado.php", $listado->php());
        // Crear ListadoCSV
        //~ if ($this->adan->si_hay_que_crear('listadocsv')) {
            //~ $listado_csv = new ListadoCSV($this->adan);
            //~ $m[]         = $this->crear_archivo_libreria($modulo, "ListadoCSV.php", $listado_csv->php());
            //~ $pagina_csv  = new PaginaCSV($this->adan);
            //~ $m[]         = $this->crear_archivo_libreria($modulo, "PaginaCSV.php", $pagina_csv->php());
        //~ }
        // Crear ListadoHTML
        if ($this->adan->si_hay_que_crear('listado')) {
            $listado_html = new ListadoHTML($this->adan);
            $m[]          = $this->crear_archivo_libreria($modulo, "ListadoHTML.php", $listado_html->php());
        }
        // Crear TrenHTML
        if ($this->adan->si_hay_que_crear('tren')) {
            $tren_html = new TrenHTML($this->adan);
            $m[]       = $this->crear_archivo_libreria($modulo, "TrenHTML.php", $tren_html->php());
        }
        // Crear BusquedaHTML
        if ($this->adan->si_hay_que_crear('busqueda')) {
            $busqueda_html = new BusquedaHTML($this->adan);
            $m[]           = $this->crear_archivo_libreria($modulo, "BusquedaHTML.php", $busqueda_html->php());
        }
        // Crear Select Opciones
        if ($this->adan->si_hay_que_crear('select_opciones')) {
            $opciones_select = new OpcionesSelect($this->adan);
            $m[]             = $this->crear_archivo_libreria($modulo, "OpcionesSelect.php", $opciones_select->php());
        }
        // Crear Impresion
        //~ if ($this->adan->si_hay_que_crear('impresiones')) {
            //~ $impresion      = new Impresion($this->adan);
            //~ $m[]            = $this->crear_archivo_libreria($modulo, "Impresion.php", $impresion->php());
            //~ $impresion_html = new ImpresionHTML($this->adan);
            //~ $m[]            = $this->crear_archivo_libreria($modulo, "ImpresionHTML.php", $impresion_html->php());
        //~ }
        // Crear PaginaHTML
        $pagina_html = new PaginaHTML($this->adan);
        $m[]         = $this->crear_archivo_libreria($modulo, "PaginaHTML.php", $pagina_html->php());
        // Crear raiz
        $raiz = new Raiz($this->adan);
        $m[]  = $this->crear_archivo_pagina($this->adan->sustituciones['SED_ARCHIVO_PLURAL'].".php", $raiz->php());
        // Entregar mensaje
        return implode("\n", $m);
    } // crear

} // Clase Creador

?>
