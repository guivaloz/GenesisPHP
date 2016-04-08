<?php
/**
 * GenesisPHP - Semillas Adan0111CatDepartamentos
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
 * Clase Adan0111CatDepartamentos
 */
class Adan0111CatDepartamentos extends \Arbol\Adan {

    // Nombre de este modulo
    public $nombre = 'CatDepartamentos';

    // Nombre de la tabla
    public $tabla_nombre = 'cat_departamentos';

    // Datos de la tabla
    public $tabla = array(
        'id'         => array('tipo' => 'serial'),

        'nombre'     => array('tipo' => 'nombre',   'etiqueta' => 'Nombre',  'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 11, 'orden' => 1, 'vip' => 2),

        'notas'      => array('tipo' => 'notas',    'etiqueta' => 'Notas',   'validacion' => 1, 'agregar' => 1, 'modificar' => 1),
        'estatus'    => array('tipo' => 'caracter', 'etiqueta' => 'Estatus', 'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 99,
            'descripciones' => array('A' => 'EN USO',                'B' => 'ELIMINADO'),
            'etiquetas'     => array('A' => 'En Uso',                'B' => 'Eliminado'),
            'iconos'        => array('A' => 'x-office-document.png', 'B' => 'user-trash.png'),
            'colores'       => array('A' => 'blanco',                'B' => 'gris'),
            'acciones'      => array('A' => 'listadoenuso',          'B' => 'listadoeliminados'))
    );

    // Reptil es leido por Serpiente
    static public $reptil = array(
        'etiqueta_singular'  => 'Departamento',
        'etiqueta_plural'    => 'Departamentos',
        'nom_corto_singular' => 'departamento',
        'nom_corto_plural'   => 'departamentos',
        'mensaje_singular'   => 'el departamento',
        'mensaje_plural'     => 'los departamentos',
        'clave'              => 'cat_departamentos',
        'clase_singular'     => 'CatDepartamento',
        'clase_plural'       => 'CatDepartamentos',
        'instancia_singular' => 'departamento',
        'instancia_plural'   => 'departamentos',
        'archivo_singular'   => 'catdepartamento',
        'archivo_plural'     => 'catdepartamentos',
        'tabla'              => 'cat_departamentos',
        'vip'                => array(
            'nombre' => array('tipo' => 'nombre', 'etiqueta' => 'Departamento', 'filtro' => 1))
    );

    /**
     * Constructor
     */
    public function __construct() {
        // Programas a escribir
        $this->modulo_completo();
        // Obtener de serpiente
        $serpiente = new Serpiente();
        // Siempre se debe de cargar de serpiente esta informacion
        $this->sustituciones      = $serpiente->obtener_sustituciones($this->nombre);
        $this->instancia_singular = $serpiente->obtener_instancia_singular($this->nombre);
        $this->estatus            = $serpiente->obtener_estatus($this->nombre);
    } // constructor

} // Clase Adan0111CatDepartamentos

?>
