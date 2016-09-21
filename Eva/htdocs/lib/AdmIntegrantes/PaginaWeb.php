<?php
/**
 * GenesisPHP - AdmIntegrantes PaginaWeb
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

namespace AdmIntegrantes;

/**
 * Clase PaginaWeb
 */
class PaginaWeb extends \Base2\PaginaWeb {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('adm_integrantes');
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
            $lenguetas = new \Base2\LenguetasWeb('lenguetasIntegrantes');
            // Acciones para un registro
            if (($_GET['id'] != '') && ($_GET['accion'] == DetalleWeb::$accion_modificar)) {
                $formulario     = new FormularioWeb($this->sesion);
                $formulario->id = $_GET['id'];
                $lenguetas->agregar('Modificar', $formulario, TRUE);
            } elseif (($_GET['id'] != '') && ($_GET['accion'] == DetalleWeb::$accion_eliminar)) {
                $eliminar     = new EliminarWeb($this->sesion);
                $eliminar->id = $_GET['id'];
                $lenguetas->agregar('Eliminar', $eliminar, TRUE);
            } elseif (($_GET['id'] != '') && ($_GET['accion'] == DetalleWeb::$accion_recuperar)) {
                $recuperar     = new RecuperarWeb($this->sesion);
                $recuperar->id = $_GET['id'];
                $lenguetas->agregar('Recuperar', $recuperar, TRUE);
            } elseif ($_GET['id'] != '') {
                $detalle     = new DetalleWeb($this->sesion);
                $detalle->id = $_GET['id'];
                $lenguetas->agregar('Detalle', $detalle, TRUE);
            } elseif ($_POST['formulario'] == FormularioWeb::$form_name) {
                $formulario = new FormularioWeb($this->sesion);
                $lenguetas->agregar('Formulario', $formulario, TRUE);
            }
            // Busqueda
            $busqueda  = new BusquedaWeb($this->sesion);
            $resultado = $busqueda->html(); // TODO: Ejecuto el método para consultar las banderas, mejorar
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
                // En uso
                $listado->estatus = 'A';
                $lenguetas->agregar('En uso', $listado, FALSE, TRUE); // Lengüeta activa por defecto
                // Eliminados
                if ($this->sesion->puede_recuperar()) {
                    $listado = new ListadoWeb($this->sesion);
                    $listado->estatus = 'B';
                    $lenguetas->agregar('Eliminados', $listado);
                }
            }
            // Nuevo
            if ($this->sesion->puede_agregar()) {
                $formulario     = new FormularioWeb($this->sesion);
                $formulario->id = 'agregar';
                if ($_GET['accion'] == 'agregar') {
                    $lenguetas->agregar('Nuevo', $formulario, TRUE);
                } else {
                    $lenguetas->agregar('Nuevo', $formulario);
                }
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
