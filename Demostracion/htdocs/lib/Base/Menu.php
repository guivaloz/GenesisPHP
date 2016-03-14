<?php
/**
 * GenesisPHP - Menu
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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
 * Clase Menu
 */
class Menu {

    public $clave;                         // Texto, clave de la página en uso, sirve para saber cuál es la opción activa
    public $permisos            = array(); // Arreglo, clave => permiso
    protected $principal_actual = null;    // Texto, mantiene la clave última del menú principal
    protected $estructura       = array(); // Arreglo asociativo, clave principal => claves de secundarios
    protected $datos            = array(); // Arreglo asociativo, clave => arreglo asociativo con los datos

    /**
     * Agregar Principal
     *
     * Agrega una opción al menú principal (a la izquierda)
     *
     * @param string  Clave, texto único que identifica a la página y al módulo
     * @param string  Etiqueta
     * @param string  URL para hacer el vínculo
     * @param string  Opcional, nombre del archivo con el icono, por defecto es folder.png o nombre del glyphicon
     * @param integer Opcional, permiso, por defecto es uno
     */
    public function agregar_principal($in_clave, $in_etiqueta, $in_url, $in_icono='folder.png', $in_permiso=1) {
        // Si el primer caracter de la etiqueta es un guión
        if (strpos($in_etiqueta, '-') === 0) {
            // Es una opción oculta
            $etiqueta = substr($in_etiqueta, 1);
            $oculto   = true;
        } else {
            // Va a ser visible
            $etiqueta = $in_etiqueta;
            $oculto   = false;
        }
        // Acumular
        $this->principal_actual                    = $in_clave;
        $this->estructura[$this->principal_actual] = array();
        $this->datos[$this->principal_actual]      = array(
            'etiqueta' => $etiqueta,
            'url'      => $in_url,
            'icono'    => $in_icono,
            'oculto'   => $oculto,
            'posicion' => 'izquierda');
        $this->permisos[$this->principal_actual]   = $in_permiso;
    } // agregar_principal

    /**
     * Agregar Principal Derecha
     *
     * Agrega una opción al menú principal del lado derecho
     *
     * @param string  Clave, texto único que identifica a la página y al módulo
     * @param string  Etiqueta
     * @param string  URL a la página
     * @param string  Opcional, nombre del archivo con el icono, por defecto es folder.png
     * @param integer Opcional, permiso, por defecto es uno
     */
    public function agregar_principal_derecha($in_clave, $in_etiqueta, $in_url, $in_icono='folder.png', $in_permiso=1) {
        // Si el primer caracter de la etiqueta es un guión
        if (strpos($in_etiqueta, '-') === 0) {
            // Es una opción oculta
            $etiqueta = substr($in_etiqueta, 1);
            $oculto   = true;
        } else {
            // Va a ser visible
            $etiqueta = $in_etiqueta;
            $oculto   = false;
        }
        // Acumular
        $this->principal_actual                    = $in_clave;
        $this->estructura[$this->principal_actual] = array();
        $this->datos[$this->principal_actual]      = array(
            'etiqueta' => $etiqueta,
            'url'      => $in_url,
            'icono'    => $in_icono,
            'oculto'   => $oculto,
            'posicion' => 'derecha');
        $this->permisos[$this->principal_actual]   = $in_permiso;
    } // agregar_principal_derecha

    /**
     * Agregar
     *
     * @param string  Clave, texto único que identifica a la página y al módulo
     * @param string  Etiqueta
     * @param string  URL a la página
     * @param string  Opcional, nombre del archivo con el icono, por defecto es folder.png
     * @param integer Opcional, permiso, por defecto es uno
     */
    public function agregar($in_clave, $in_etiqueta, $in_url, $in_icono='folder.png', $in_permiso=1) {
        // Validar que se haya agregado un menú principal
        if (!is_string($this->principal_actual) || (trim($this->principal_actual) == '')) {
            throw new \Exception('Error en Menú: No se ha agregado la opción para el menú principal.');
        }
        // Acumular
        $this->estructura[$this->principal_actual][] = $in_clave;
        $this->datos[$in_clave]                     = array(
            'etiqueta' => $in_etiqueta,
            'url'      => $in_url,
            'icono'    => $in_icono,
            'oculto'   => false);
        $this->permisos[$in_clave]                  = $in_permiso;
    } // agregar

    /**
     * Opciones Menu Principal
     *
     * Lo usa las plantillas.
     *
     * @return array Arreglo de arreglos asociativos
     */
    public function opciones_menu_principal() {
        // Validar clave
        if (!is_string($this->clave) || ($this->clave == '')) {
            throw new \Exception("Error en Menú: No está definida la clave de la página en uso.");
        }
        // En este arreglo se acumulará
        $a = array();
        // Bucle en estructura
        foreach ($this->estructura as $principal_clave => $arreglo) {
            // Si la clave de la página en uso es ésta clave o alguna del submenú
            if (($this->clave == $principal_clave) || in_array($this->clave, $arreglo)) {
                $activo = true;
            } else {
                $activo = false;
            }
            // Acumular
            $a[] = array(
                'posicion' => $this->datos[$principal_clave]['posicion'],
                'icono'    => $this->datos[$principal_clave]['icono'],
                'etiqueta' => $this->datos[$principal_clave]['etiqueta'],
                'url'      => $this->datos[$principal_clave]['url'],
                'oculto'   => $this->datos[$principal_clave]['oculto'],
                'activo'   => $activo);
        }
        // Entregar
        return $a;
    } // opciones_menu_principal

    /**
     * Opciones Menu Secundario
     *
     * Lo usa las plantillas.
     *
     * @return array Arreglo de arreglos asociativos
     */
    public function opciones_menu_secundario() {
        // Validar clave
        if (!is_string($this->clave) || ($this->clave == '')) {
            throw new \Exception("Error en Menú: No está definida la clave de la página en uso.");
        }
        // En este arreglo se acumulará
        $a = array();
        // Bucle en estructura, para determinar la opción del menú principal
        foreach ($this->estructura as $principal_clave => $arreglo) {
            if ($this->clave == $principal_clave) {
                break;
            }
            if (in_array($this->clave, $arreglo)) {
                break;
            }
        }
        // Si no está oculto, aparece la opción del menú principal en el secundario
        if ($this->datos[$principal_clave]['oculto'] == false) {
            $activo = ($this->clave == $principal_clave);
            $a[] = array(
                'icono'    => $this->datos[$principal_clave]['icono'],
                'etiqueta' => $this->datos[$principal_clave]['etiqueta'],
                'url'      => $this->datos[$principal_clave]['url'],
                'activo'   => $activo);
        }
        // Bucle en el menú secundario
        foreach ($this->estructura[$principal_clave] as $secundario_clave) {
            if ($this->datos[$secundario_clave]['oculto'] == true) {
                continue;
            }
            $activo = ($this->clave == $secundario_clave);
            $a[] = array(
                'icono'    => $this->datos[$secundario_clave]['icono'],
                'etiqueta' => $this->datos[$secundario_clave]['etiqueta'],
                'url'      => $this->datos[$secundario_clave]['url'],
                'activo'   => $activo);
        }
        // Entregar
        return $a;
    } // opciones_menu_secundario

    /**
     * Titulo en
     *
     * @param  string Opcional, clave de la página
     * @return string Título
     */
    public function titulo_en($in_clave='') {
        if ($in_clave != '') {
            $clave = $in_clave;
        } else {
            $clave = $this->clave;
        }
        if (array_key_exists($clave, $this->datos)) {
            return $this->datos[$clave]['etiqueta'];
        } else {
            return '';
        }
    } // titulo_en

    /**
     * Icono en
     *
     * @param  string Opcional, clave de la página
     * @return string Archivo del icono
     */
    public function icono_en($in_clave='') {
        if ($in_clave != '') {
            $clave = $in_clave;
        } else {
            $clave = $this->clave;
        }
        if (array_key_exists($clave, $this->datos)) {
            return $this->datos[$clave]['icono'];
        } else {
            return '';
        }
    } // icono_en

    /**
     * Permiso en Página
     *
     * @param  string  Opcional, clave de la página
     * @return integer Permiso
     */
    public function permiso_en_pagina($in_clave='') {
        if ($in_clave != '') {
            $clave = $in_clave;
        } else {
            $clave = $this->clave;
        }
        if (array_key_exists($clave, $this->permisos)) {
            return $this->permisos[$clave];
        } else {
            return 0;
        }
    } // permiso_en_pagina

} // Clase Menu

?>
