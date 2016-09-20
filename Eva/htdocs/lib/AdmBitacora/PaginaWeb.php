<?php
/**
 * GenesisPHP - AdmBitacora PaginaWeb
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

namespace AdmBitacora;

/**
 * Clase PaginaWeb
 */
class PaginaWeb extends \Base2\PaginaWeb {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('adm_bitacora');
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Solo si se carga con éxito la sesión
        if ($this->sesion_exitosa) {
            // Lenguetas
            $lenguetas = new \Base2\LenguetasWeb('lenguetasBitacora');
            // Acciones para un registro
            if ($_GET['id'] != '') {
                $detalle     = new DetalleWeb($this->sesion);
                $detalle->id = $_GET['id'];
                $lenguetas->agregar('Detalle', $detalle, TRUE);
            }
            // Busqueda, crea dos lenguetas si hay resultados
            $busqueda = new BusquedaWeb($this->sesion);
            if ($busqueda->hay_resultados) {
                $lenguetas->agregar('Resultados', $busqueda, TRUE);
            } elseif ($busqueda->hay_mensaje) {
                $lenguetas->agregar('Buscar', $busqueda, TRUE);
            } else {
                $lenguetas->agregar('Buscar', $busqueda);
            }
            // Listados
            $listado = new ListadoWeb($this->sesion);
            if ($listado->viene_listado) {
                $lenguetas->agregar('Listado', $listado, TRUE);
            } else {
                $lenguetas->agregar('Listado', $listado);
            }
            // Pasar el html y el javascript de las lenguetas al contenido
            $this->contenido[]  = $lenguetas->html();
            $this->javascript[] = $lenguetas->javascript();
        }
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase PaginaWeb

?>
