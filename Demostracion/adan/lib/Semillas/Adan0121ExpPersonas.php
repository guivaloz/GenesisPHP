<?php
/**
 * GenesisPHP - Semilla ExpPersonas
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
 * Clase Adan0121ExpPersonas
 */
class Adan0121ExpPersonas extends \Arbol\Adan {

    // Nombre de este modulo
    public $nombre = 'ExpPersonas';

    // Nombre de la tabla
    public $tabla_nombre = 'exp_personas';

    // Datos de la tabla
    public $tabla = array(
        'id'               => array('tipo' => 'serial'),
        'area'             => array('tipo' => 'relacion',   'etiqueta' => 'Área',                'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 61),
        'puesto'           => array('tipo' => 'relacion',   'etiqueta' => 'Puesto',              'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 71),

        'nombres'          => array('tipo' => 'nombre',     'etiqueta' => 'Nombres',             'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1),
        'apellido_paterno' => array('tipo' => 'nombre',     'etiqueta' => 'Apellido paterno',    'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1),
        'apellido_materno' => array('tipo' => 'nombre',     'etiqueta' => 'Apellido materno',    'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1),
        'nombre_completo'  => array('tipo' => 'nombre',     'etiqueta' => 'Nombre completo',     'validacion' => 2,                                   'filtro' => 1, 'listado' => 21, 'orden' => 1, 'vip' => 2,
            'agregar'   => 'sprintf("%s, %s", trim($this->apellido_paterno.\' \'.$this->apellido_materno), $this->nombres)',
            'modificar' => 'sprintf("%s, %s", trim($this->apellido_paterno.\' \'.$this->apellido_materno), $this->nombres)'),

        'nacimiento_fecha' => array('tipo' => 'fecha',      'etiqueta' => 'Fecha de nacimiento', 'validacion' => 1, 'agregar' => 1, 'modificar' => 1),
        'sexo'             => array('tipo' => 'caracter',   'etiqueta' => 'Sexo',                'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1,
            'descripciones' => array('M' => 'Hombre',  'F' => 'Mujer'),
            'etiquetas'     => array('M' => 'Hombres', 'F' => 'Mujeres'),
            'colores'       => array('M' => 'azul',    'F' => 'rosa')),
        'estado_civil'     => array('tipo' => 'caracter',   'etiqueta' => 'Estado civil',        'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1,
            'descripciones' => array('S' => 'Soltero', 'C' => 'Casado', 'D' => 'Divorciado', 'U' => 'Unión libre', 'V' => 'Viudo'),
            'etiquetas'     => array('S' => 'Soltero', 'C' => 'Casado', 'D' => 'Divorciado', 'U' => 'Unión libre', 'V' => 'Viudo'),
            'colores'       => array('S' => 'verde',   'C' => 'rojo',   'D' => 'amarillo',   'U' => 'rosa',        'V' => 'naranja')),
        'curp'             => array('tipo' => 'curp',     'etiqueta' => 'CURP',                  'validacion' => 2, 'agregar' => 1, 'modificar' => 1),

        'nomina'           => array('tipo' => 'entero',     'etiqueta' => 'Nómina',              'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 11),
        'ingreso_fecha'    => array('tipo' => 'fecha',      'etiqueta' => 'Fecha de ingreso',    'validacion' => 1, 'agregar' => 1, 'modificar' => 1),

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
        'etiqueta_singular'  => 'Persona',
        'etiqueta_plural'    => 'Personas',
        'nom_corto_singular' => 'persona',
        'nom_corto_plural'   => 'personas',
        'mensaje_singular'   => 'la persona',
        'mensaje_plural'     => 'las personas',
        'clave'              => 'exp_personas',
        'clase_singular'     => 'ExpPersona',
        'clase_plural'       => 'ExpPersonas',
        'instancia_singular' => 'persona',
        'instancia_plural'   => 'personas',
        'archivo_singular'   => 'exppersona',
        'archivo_plural'     => 'exppersonas',
        'tabla'              => 'exp_personas',
        'vip'                => array(
            'nombre_completo' => array('tipo' => 'nombre', 'etiqueta' => 'Persona', 'filtro' => 1))
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
        $this->relaciones['area']   = $serpiente->obtener_datos_del_modulo('CatAreas');
        $this->relaciones['puesto'] = $serpiente->obtener_datos_del_modulo('CatPuestos');
        // Hijos, los módulos que se mostrarán debajo del detalle como listados
        $this->hijos['fotos']       = $serpiente->obtener_datos_del_modulo('ExpPersonasFotos');
        $this->hijos['domicilios']  = $serpiente->obtener_datos_del_modulo('ExpPersonasDomicilios');
        $this->hijos['familiares']  = $serpiente->obtener_datos_del_modulo('ExpPersonasFamiliares');
        // Siempre se debe de cargar de serpiente esta informacion
        $this->sustituciones        = $serpiente->obtener_sustituciones($this->nombre);
        $this->instancia_singular   = $serpiente->obtener_instancia_singular($this->nombre);
        $this->estatus              = $serpiente->obtener_estatus($this->nombre);
    } // constructor

} // Clase Adan0121ExpPersonas

?>
