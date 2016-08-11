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
     * @param  string Nombre del archivo
     * @param  string Codigo PHP a escribir
     * @return string Nombre del archivo creado
     */
    protected function crear_archivo_pagina($archivo, $contenido) {
        // Crear archivo con la libreria
        $pagina_arch = sprintf('%s/%s', self::RAIZ, $archivo);
        if (!($soga = fopen($pagina_arch, 'w'))) {
            throw new \Exception("ERROR en Creador: No es posible crear o escribir el archivo $pagina_arch");
        }
        if (fwrite($soga, $contenido) === false) {
            throw new \Exception("ERROR en Creador: Al escribir contenido en $pagina_arch");
        }
        return $archivo;
    } // crear_archivo_pagina

    /**
     * Crear archivo libreria
     *
     * @param  string Nombre del modulo, será el nombre del directorio
     * @param  string Nombre del archivo
     * @param  string Codigo PHP a escribir
     * @return string Nombre del archivo creado
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
        if (fwrite($soga, $contenido) === false) {
            throw new \Exception("ERROR en Creador: Al escribir contenido en $libreria_arch");
        }
        return $archivo;
    } // crear_archivo_libreria

    /**
     * Crear
     */
    public function crear() {
        // Arreglo para juntar los mensajes
        $m = array();
        $a = array();
        // Tomamos el nombre del modulo, sera el directorio de la libreria
        $modulo = $this->adan->nombre;
        $m[]    = $modulo;
        // Crear Registro
        $clase = new Registro($this->adan);
        $a[]   = $this->crear_archivo_libreria($modulo, "Registro.php", $clase->php());
        // Crear DetalleWeb
        $clase = new DetalleWeb($this->adan);
        $a[]   = $this->crear_archivo_libreria($modulo, "DetalleWeb.php", $clase->php());
        // Crear EliminarWeb
        if ($this->adan->si_hay_que_crear('eliminar')) {
            $clase = new EliminarWeb($this->adan);
            $a[]   = $this->crear_archivo_libreria($modulo, "EliminarWeb.php", $clase->php());
        }
        // Crear RecuperarWeb
        if ($this->adan->si_hay_que_crear('recuperar')) {
            $clase = new RecuperarWeb($this->adan);
            $a[]   = $this->crear_archivo_libreria($modulo, "RecuperarWeb.php", $clase->php());
        }
        // Crear FormularioWeb
        if ($this->adan->si_hay_que_crear('formulario')) {
            $clase = new FormularioWeb($this->adan);
            $a[]   = $this->crear_archivo_libreria($modulo, "FormularioWeb.php", $clase->php());
        }
        // Crear ImagenWebUltima
        if ($this->adan->si_hay_que_crear('imagen_web_ultima')) {
            $clase = new ImagenWebUltima($this->adan);
            $a[]   = $this->crear_archivo_libreria($modulo, "ImagenWebUltima.php", $clase->php());
        }
        // Crear Listado
        $clase = new Listado($this->adan);
        $a[]   = $this->crear_archivo_libreria($modulo, "Listado.php", $clase->php());
        // Crear ListadoWeb
        if ($this->adan->si_hay_que_crear('listado')) {
            $clase = new ListadoWeb($this->adan);
            $a[]   = $this->crear_archivo_libreria($modulo, "ListadoWeb.php", $clase->php());
        }
        // Crear TrenWeb
        if ($this->adan->si_hay_que_crear('tren')) {
            $clase = new TrenWeb($this->adan);
            $a[]   = $this->crear_archivo_libreria($modulo, "TrenWeb.php", $clase->php());
        }
        // Crear BusquedaWeb
        if ($this->adan->si_hay_que_crear('busqueda')) {
            $clase = new BusquedaWeb($this->adan);
            $a[]   = $this->crear_archivo_libreria($modulo, "BusquedaWeb.php", $clase->php());
        }
        // Crear OpcionesSelect
        if ($this->adan->si_hay_que_crear('select_opciones')) {
            $clase = new OpcionesSelect($this->adan);
            $a[]   = $this->crear_archivo_libreria($modulo, "OpcionesSelect.php", $clase->php());
        }
        // Crear PaginaWeb
        $clase = new PaginaWeb($this->adan);
        $a[]   = $this->crear_archivo_libreria($modulo, "PaginaWeb.php", $clase->php());
        // Crear raiz
        $raiz = new Raiz($this->adan);
        $a[]  = $this->crear_archivo_pagina($this->adan->sustituciones['SED_ARCHIVO_PLURAL'].".php", $raiz->php());
        // Entregar mensaje
        $m[] = '  '.implode(', ', $a);
        return implode("\n", $m);
    } // crear

} // Clase Creador

?>
