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
class PaginaWeb extends \Base\PaginaWeb {

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
            $lenguetas = new \Base\LenguetasHTML('lenguetasBitacora');
            // Acciones para un registro
            if ($_GET['id'] != '') {
                // Detalle
                $detalle = new DetalleHTML($this->sesion);
                $detalle->id = $_GET['id'];
                $lenguetas->agregar_activa('bitacoraDetalle', 'Detalle', $detalle);
            }
            // Busqueda, crea dos lenguetas si hay resultados
            $busqueda        = new BusquedaHTML($this->sesion);
            $resultados_html = $busqueda->html();
            if ($busqueda->hay_resultados) {
                $lenguetas->agregar('bitacoraBuscar', 'Buscar', $busqueda->formulario_html());
                $lenguetas->agregar_activa('bitacoraResultado',  'Resultado',  $resultados_html);
            } elseif ($busqueda->hay_mensaje) {
                $lenguetas->agregar_activa('bitacoraBuscar', 'Buscar', $resultados_html);
            } else {
                $lenguetas->agregar('bitacoraBuscar', 'Buscar', $resultados_html);
            }
            $lenguetas->agregar_javascript($busqueda->javascript());
            // Listados
            $listado = new ListadoHTML($this->sesion);
            if ($listado->viene_listado) {
                // Viene un listado previo
                $lenguetas->agregar_activa('bitacoraListado', 'Listado', $listado);
            } else {
                // Listado bitacora
                $bitacora = new ListadoHTML($this->sesion);
                $lenguetas->agregar('bitacoraListado', 'Listado', $bitacora);
                if ($lenguetas->activa == '') {
                    $lenguetas->definir_activa();
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
