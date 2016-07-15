<?php
/**
 * GenesisPHP - Semillas CatPuestos
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

namespace Semillas;

/**
 * Clase Adan0113CatPuestos
 */
class Adan0113CatPuestos extends \Arbol\Adan {

    // Nombre de este modulo
    public $nombre = 'CatPuestos';

    // Nombre de la tabla
    public $tabla_nombre = 'cat_puestos';

    // Datos de la tabla
    public $tabla = array(
        'id'      => array('tipo' => 'serial'),

        'nombre'  => array('tipo' => 'nombre',   'etiqueta' => 'Nombre',  'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 11, 'orden' => 1, 'vip' => 2),

        'notas'   => array('tipo' => 'notas',    'etiqueta' => 'Notas',   'validacion' => 1, 'agregar' => 1, 'modificar' => 1),
        'estatus' => array('tipo' => 'caracter', 'etiqueta' => 'Estatus', 'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 99,
            'descripciones' => array('A' => 'En uso',       'B' => 'Eliminado'),
            'etiquetas'     => array('A' => 'En Uso',       'B' => 'Eliminado'),
            'colores'       => array('A' => 'blanco',       'B' => 'gris'),
            'acciones'      => array('A' => 'listadoenuso', 'B' => 'listadoeliminados'))
    );

    // Reptil es leido por Serpiente
    static public $reptil = array(
        'etiqueta_singular'  => 'Puesto',
        'etiqueta_plural'    => 'Puestos',
        'nom_corto_singular' => 'puesto',
        'nom_corto_plural'   => 'puestos',
        'mensaje_singular'   => 'el puesto',
        'mensaje_plural'     => 'los puestos',
        'clave'              => 'cat_puestos',
        'clase_singular'     => 'CatPuesto',
        'clase_plural'       => 'CatPuestos',
        'instancia_singular' => 'puesto',
        'instancia_plural'   => 'puesto',
        'archivo_singular'   => 'catpuesto',
        'archivo_plural'     => 'catpuestos',
        'tabla'              => 'cat_puestos',
        'vip'                => array(
            'nombre' => array('tipo' => 'nombre', 'etiqueta' => 'Puesto', 'filtro' => 1))
    );

    /**
     * Constructor
     */
    public function __construct() {
        // Programas a escribir
        $this->modulo_completo();
        // Obtener de serpiente
        $serpiente = new Serpiente();
        // Siempre se debe de cargar de serpiente esta información
        $this->sustituciones          = $serpiente->obtener_sustituciones($this->nombre);
        $this->instancia_singular     = $serpiente->obtener_instancia_singular($this->nombre);
        $this->estatus                = $serpiente->obtener_estatus($this->nombre);
    } // constructor

} // Clase Adan0113CatPuestos

?>
