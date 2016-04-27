<?php
/**
 * GenesisPHP - Semilla
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
 * Clase Adan0127ExpPersonasFamiliares
 */
class Adan0127ExpPersonasFamiliares extends \Arbol\Adan {

    // Nombre de este modulo
    public $nombre = 'ExpPersonasFamiliares';

    // Nombre de la tabla
    public $tabla_nombre = 'exp_personas_familiares';

    // Datos de la tabla
    public $tabla = array(
        'id'               => array('tipo' => 'serial'),
        'persona'          => array('tipo' => 'relacion',   'etiqueta' => 'Persona',             'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 61),

        'nombre'           => array('tipo' => 'nombre',     'etiqueta' => 'Nombre',              'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 11, 'orden' => 1, 'vip' => 2),
        'parentesco'       => array('tipo' => 'caracter',   'etiqueta' => 'Parentezco',          'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 43,
            'descripciones' => array('C' => 'Cosanguíneo', 'A' => 'Por afinidad'),
            'etiquetas'     => array('C' => 'Cosanguíneo', 'A' => 'Por afinidad'),
            'colores'       => array('C' => 'rojo',        'A' => 'azul')),
        'sexo'             => array('tipo' => 'caracter',   'etiqueta' => 'Sexo',                'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1,
            'descripciones' => array('M' => 'Hombre',  'F' => 'Mujer'),
            'etiquetas'     => array('M' => 'Hombres', 'F' => 'Mujeres'),
            'colores'       => array('M' => 'azul',    'F' => 'rosa')),
        'nacimiento_fecha' => array('tipo' => 'fecha',      'etiqueta' => 'Fecha de nacimiento', 'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 2),

        'notas'            => array('tipo' => 'notas',      'etiqueta' => 'Notas',               'validacion' => 1, 'agregar' => 1, 'modificar' => 1),
        'estatus'          => array('tipo' => 'caracter',   'etiqueta' => 'Estatus',             'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 99,
            'descripciones' => array('A' => 'EN USO',                'B' => 'ELIMINADO'),
            'etiquetas'     => array('A' => 'En Uso',                'B' => 'Eliminado'),
            'iconos'        => array('A' => 'x-office-document.png', 'B' => 'user-trash.png'),
            'colores'       => array('A' => 'blanco',                'B' => 'gris'),
            'acciones'      => array('A' => 'listadoenuso',          'B' => 'listadoeliminados'))
    );

    // Reptil es leido por Serpiente
    static public $reptil = array(
        'etiqueta_singular'  => 'Familiar',
        'etiqueta_plural'    => 'Familiares',
        'nom_corto_singular' => 'familiar',
        'nom_corto_plural'   => 'familiares',
        'mensaje_singular'   => 'el familiar',
        'mensaje_plural'     => 'los familiares',
        'clave'              => 'exp_personas_familiares',
        'clase_singular'     => 'ExpPersonaFamiliar',
        'clase_plural'       => 'ExpPersonasFamiliares',
        'instancia_singular' => 'persona_familiar',
        'instancia_plural'   => 'personas_familiares',
        'archivo_singular'   => 'exppersonafamiliar',
        'archivo_plural'     => 'exppersonasfamiliares',
        'tabla'              => 'exp_personas_familiares',
        'vip'                => array(
            'nombre' => array('tipo' => 'nombre', 'etiqueta' => 'Familiar', 'filtro' => 1))
    );

    /**
     * Constructor
     */
    public function __construct() {
        // Programas a escribir
        $this->modulo_completo();
        // Obtener de serpiente
        $serpiente = new Serpiente();
        // Relaciones, cada modulo con el que está relacionado sin incluir a los hijos
        $this->relaciones['area']    = $serpiente->obtener_datos_del_modulo('CatAreas');
        $this->relaciones['puesto']  = $serpiente->obtener_datos_del_modulo('CatPuestos');
        $this->relaciones['persona'] = $serpiente->obtener_datos_del_modulo('ExpPersonas');
        // Padre, el módulo que mostrará a éste como un listado debajo de aquel
        $this->padre['persona']      = $serpiente->obtener_datos_del_modulo('ExpPersonas');
        // Siempre se debe de cargar de serpiente esta informacion
        $this->sustituciones         = $serpiente->obtener_sustituciones($this->nombre);
        $this->instancia_singular    = $serpiente->obtener_instancia_singular($this->nombre);
        $this->estatus               = $serpiente->obtener_estatus($this->nombre);
    } // constructor

} // Clase Adan0127ExpPersonasFamiliares

?>
