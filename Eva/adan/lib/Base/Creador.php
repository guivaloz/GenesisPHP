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

    protected $adan;          // Instancia con la Semilla, es heredera de Adan
    const RAIZ = '../htdocs'; // Texto con la ruta al directorio raiz para el sistema

    /**
     * Constructor
     *
     * @param mixed Instancia con la Semilla, que es heredera de Adan
     */
    public function __construct(\Arbol\Adan $semilla) {
        $this->adan = $semilla;
    } // constructor

    /**
     * Crear archivo pagina
     *
     * @param string Nombre del archivo
     * @param string Codigo PHP a escribir
     */
    protected function crear_archivo_pagina($archivo, $contenido) {
        // Crear archivo con la libreria
        $pagina_arch = sprintf('%s/%s', self::RAIZ, $archivo);
        if (!($soga = fopen($pagina_arch, 'w'))) {
            throw new \Exception("ERROR en Creador: No es posible crear o escribir el archivo $pagina_arch");
        }
        if (fwrite($soga, $contenido) === FALSE) {
            throw new \Exception("ERROR en Creador: Al escribir contenido en $pagina_arch");
        }
        return ":-) $archivo";
    } // crear_archivo_pagina

    /**
     * Crear archivo libreria
     *
     * @param string Nombre del modulo, será el nombre del directorio
     * @param string Nombre del archivo
     * @param string Codigo PHP a escribir
     */
    protected function crear_archivo_libreria($modulo, $archivo, $contenido) {
        // Crear directorio lib
        $lib_dir = sprintf('%s/lib', self::RAIZ);
        if (!is_dir($lib_dir)) {
            throw new \Exception("ERROR en Creador: No existe el directorio $lib_dir, debería de existir.");
        }
        // Crear directorio del modulo
        $modulo_dir = "$lib_dir/$modulo";
        if (!is_dir($modulo_dir)) {
            if (mkdir($modulo_dir) === false) {
                throw new \Exception("ERROR en Creador: Al tratar de crear el directorio lib/$modulo");
            }
        }
        // Crear archivo con la libreria
        $libreria_arch = "$modulo_dir/$archivo";
        if (!($soga = fopen($libreria_arch, 'w'))) {
            throw new \Exception("ERROR en Creador: No es posible crear o escribir el archivo $libreria_arch");
        }
        if (fwrite($soga, $contenido) === FALSE) {
            throw new \Exception("ERROR en Creador: Al escribir contenido en $libreria_arch");
        }
        return ":-)   $archivo";
    } // crear_archivo_libreria

    /**
     * Crear
     */
    public function crear() {
        // Arreglo para juntar los mensajes
        $m = array();
        // Tomamos el nombre del modulo, sera el directorio de la libreria
        $modulo = $this->adan->nombre;
        $m[]    = $modulo;
        // Crear Registro
        $registro = new Registro($this->adan);
        $m[]      = $this->crear_archivo_libreria($modulo, "Registro.php", $registro->php());
        // Crear DetalleWeb
        $detalle_web = new DetalleWeb($this->adan);
        $m[]         = $this->crear_archivo_libreria($modulo, "DetalleWeb.php", $detalle_web->php());
        // Crear EliminarWeb
        if ($this->adan->si_hay_que_crear('eliminar')) {
            $eliminar_web = new EliminarWeb($this->adan);
            $m[]          = $this->crear_archivo_libreria($modulo, "EliminarWeb.php", $eliminar_web->php());
        }
        // Crear RecuperarWeb
        if ($this->adan->si_hay_que_crear('recuperar')) {
            $recuperar_web = new RecuperarWeb($this->adan);
            $m[]           = $this->crear_archivo_libreria($modulo, "RecuperarWeb.php", $recuperar_web->php());
        }
        // Crear FormularioWeb
        if ($this->adan->si_hay_que_crear('formulario')) {
            $formulario_web = new FormularioWeb($this->adan);
            $m[]            = $this->crear_archivo_libreria($modulo, "FormularioWeb.php", $formulario_web->php());
        }
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
        // Crear ListadoWeb
        if ($this->adan->si_hay_que_crear('listado')) {
            $listado_web = new ListadoWeb($this->adan);
            $m[]         = $this->crear_archivo_libreria($modulo, "ListadoWeb.php", $listado_web->php());
        }
        // Crear TrenWeb
        if ($this->adan->si_hay_que_crear('tren')) {
            $tren_web = new TrenWeb($this->adan);
            $m[]      = $this->crear_archivo_libreria($modulo, "TrenWeb.php", $tren_web->php());
        }
        // Crear BusquedaWeb
        if ($this->adan->si_hay_que_crear('busqueda')) {
            $busqueda_web = new BusquedaWeb($this->adan);
            $m[]          = $this->crear_archivo_libreria($modulo, "BusquedaWeb.php", $busqueda_web->php());
        }
        // Crear Select Opciones
        if ($this->adan->si_hay_que_crear('select_opciones')) {
            $opciones_select = new OpcionesSelect($this->adan);
            $m[]             = $this->crear_archivo_libreria($modulo, "OpcionesSelect.php", $opciones_select->php());
        }
        // Crear Impresion
        //~ if ($this->adan->si_hay_que_crear('impresiones')) {
            //~ $impresion     = new Impresion($this->adan);
            //~ $m[]           = $this->crear_archivo_libreria($modulo, "Impresion.php", $impresion->php());
            //~ $impresion_web = new ImpresionWeb($this->adan);
            //~ $m[]           = $this->crear_archivo_libreria($modulo, "ImpresionWeb.php", $impresion_web->php());
        //~ }
        // Crear PaginaWeb
        $pagina_web = new PaginaWeb($this->adan);
        $m[]        = $this->crear_archivo_libreria($modulo, "PaginaWeb.php", $pagina_web->php());
        // Crear raiz
        $raiz = new Raiz($this->adan);
        $m[]  = $this->crear_archivo_pagina($this->adan->sustituciones['SED_ARCHIVO_PLURAL'].".php", $raiz->php());
        // Entregar mensaje
        return implode("\n", $m);
    } // crear

} // Clase Creador

?>
