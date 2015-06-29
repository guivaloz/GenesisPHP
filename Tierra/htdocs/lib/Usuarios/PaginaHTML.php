<?php
/**
 * GenesisPHP - Usuarios Página HTML
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

namespace Usuarios;

/**
 * Clase PaginaHTML
 */
class PaginaHTML extends \Base\PaginaHTML {

    // protected $sistema;
    // protected $titulo;
    // protected $descripcion;
    // protected $autor;
    // protected $favicon;
    // protected $modelo;
    // protected $menu_principal_logo;
    // protected $modelo_ingreso_logos;
    // protected $modelo_fluido_logos;
    // protected $pie;
    // public $clave;
    // public $menu;
    // public $contenido;
    // public $javascript;
    // protected $sesion;
    // protected $sesion_exitosa;
    // protected $usuario;
    // protected $usuario_nombre;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('usuarios');
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Sólo si se carga con éxito la sesión
        if ($this->sesion_exitosa) {
            // Lengüetas
            $lenguetas = new \Base\LenguetasHTML('lenguetasUsuarios');
            // Acciones para un registro
            if (($_GET['id'] != '') && ($_GET['accion'] == DetalleHTML::$accion_modificar)) {
                // Modificar
                $formulario     = new FormularioHTML($this->sesion);
                $formulario->id = $_GET['id'];
                $lenguetas->agregar_activa('usuariosModificar', 'Modificar', $formulario);
            } elseif (($_GET['id'] != '') && ($_GET['accion'] == DetalleHTML::$accion_eliminar)) {
                // Eliminar
                $eliminar     = new EliminarHTML($this->sesion);
                $eliminar->id = $_GET['id'];
                $lenguetas->agregar_activa('usuariosEliminar', 'Eliminar', $eliminar);
            } elseif (($_GET['id'] != '') && ($_GET['accion'] == DetalleHTML::$accion_recuperar)) {
                // Recuperar
                $recuperar     = new RecuperarHTML($this->sesion);
                $recuperar->id = $_GET['id'];
                $lenguetas->agregar_activa('usuariosRecuperar', 'Recuperar', $recuperar);
            } elseif (($_GET['id'] != '') && ($_GET['accion'] == DetalleHTML::$accion_desbloquear)) {
                // Desbloquear
                $desbloquear     = new DesbloquearHTML($this->sesion);
                $desbloquear->id = $_GET['id'];
                $lenguetas->agregar_activa('usuariosDesbloquear', 'Desbloquear', $desbloquear);
            } elseif ($_GET['id'] != '') {
                // Detalle
                $detalle = new DetalleHTML($this->sesion);
                $detalle->id = $_GET['id'];
                $lenguetas->agregar_activa('usuariosDetalle', 'Detalle', $detalle);
            } elseif ($_POST['formulario'] == FormularioHTML::$form_name) {
                // Viene el formulario
                $formulario = new FormularioHTML($this->sesion);
                $lenguetas->agregar_activa('usuariosFormulario', 'Formulario', $formulario);
            }
            // Búsqueda, crea dos lengüetas si hay resultados
            $busqueda        = new BusquedaHTML($this->sesion);
            $resultados_html = $busqueda->html();
            if ($busqueda->hay_resultados) {
                if ($busqueda->entrego_detalle) {
                    $lenguetas->agregar_activa('usuariosResultado',  'Resultado',  $resultados_html);
                } else {
                    $lenguetas->agregar_activa('usuariosResultados', 'Resultados', $resultados_html);
                }
                $lenguetas->agregar('usuariosBuscar', 'Buscar', $busqueda->formulario_html());
            } elseif ($busqueda->hay_mensaje) {
                $lenguetas->agregar_activa('usuariosBuscar', 'Buscar', $resultados_html);
            } else {
                $lenguetas->agregar('usuariosBuscar', 'Buscar', $resultados_html);
            }
            $lenguetas->agregar_javascript($busqueda->javascript());
            // Listados
            $listado = new ListadoHTML($this->sesion);
            if ($listado->viene_listado) {
                // Viene un listado previo
                $lenguetas->agregar_activa('usuariosListado', 'Listado', $listado);
            } else {
                // Mostrar en uso
                $listado->estatus = 'A';
                $lenguetas->agregar('usuariosEnUso', 'En uso', $listado);
                if ($lenguetas->activa == '') {
                    $lenguetas->definir_activa();
                }
                // Mostrar eliminados
                if ($this->sesion->puede_recuperar('usuarios')) {
                    $listado = new ListadoHTML($this->sesion);
                    $listado->estatus = 'B';
                    $lenguetas->agregar('usuariosEliminados', 'Eliminados', $listado);
                }
            }
            // Nuevo
            if ($this->sesion->puede_agregar('usuarios')) {
                $formulario     = new FormularioHTML($this->sesion);
                $formulario->id = 'agregar';
                $lenguetas->agregar('usuariosNuevo', 'Nuevo', $formulario);
                if ($_GET['accion'] == 'agregar') {
                    $lenguetas->definir_activa();
                }
            }
            // Pasar el HTML y el Javascript de las lengüetas al contenido
            $this->contenido[]  = $lenguetas->html();
            $this->javascript[] = $lenguetas->javascript();
        }
        // Ejecutar este método en el padre
        return parent::html();
    } // html

} // Clase PaginaHTML

?>
