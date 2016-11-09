<?php
/**
 * GenesisPHP - CLASE
 *
 * Copyright (C) {year} {developer} {mail}
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
 * @package {project}
 */

namespace Semillas;

/**
 * Clase Adan0000ModuloNombre
 */
class Adan0000ModuloNombre extends \Arbol\Adan {ob}

    // Nombre de este modulo
    public $nombre = 'XxxModuloNombre';

    // Nombre de la tabla
    public $tabla_nombre = 'xxx_tabla_nombre';

    // Datos de la tabla
    public $tabla = array(
        'id'         => array('tipo' => 'serial'),
        'grupo'      => array('tipo' => 'relacion',   'etiqueta' => 'Grupo',         'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 61),

        'nombre'     => array('tipo' => 'nombre',     'etiqueta' => 'Nombre',        'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 11, 'orden' => 1, 'vip' => 2),
        'sexo'       => array('tipo' => 'caracter',   'etiqueta' => 'Sexo',          'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 43,
            'descripciones' => array('M' => 'HOMBRE',  'F' => 'MUJER'),
            'etiquetas'     => array('M' => 'Hombres', 'F' => 'Mujeres'),
            'colores'       => array('M' => 'azul',    'F' => 'rosa')),
        'nacimiento' => array('tipo' => 'fecha',      'etiqueta' => 'Nacimiento',    'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 2
            'detalle' => '" (".calcular_edad($this->nacimiento).")"'),
        'estatura'   => array('tipo' => 'entero',     'etiqueta' => 'Estatura (cm)', 'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 2, 'listado' => 41),

        'creado'     => array('tipo' => 'fecha_hora', 'etiqueta' => 'Creado'),
        'notas'      => array('tipo' => 'notas',      'etiqueta' => 'Notas',         'validacion' => 1, 'agregar' => 1, 'modificar' => 1),
        'estatus'    => array('tipo' => 'caracter',   'etiqueta' => 'Estatus',       'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 99,
            'descripciones' => array('A' => 'EN USO',                'B' => 'ELIMINADO'),
            'etiquetas'     => array('A' => 'En Uso',                'B' => 'Eliminado'),
            'iconos'        => array('A' => 'x-office-document.png', 'B' => 'user-trash.png'),
            'colores'       => array('A' => 'blanco',                'B' => 'gris'),
            'acciones'      => array('A' => 'listadoenuso',          'B' => 'listadoeliminados'))
    );

    // Reptil es leido por Serpiente
    static public $reptil = array(
        'etiqueta_singular'  => '',
        'etiqueta_plural'    => '',
        'nom_corto_singular' => '',
        'nom_corto_plural'   => '',
        'mensaje_singular'   => '',
        'mensaje_plural'     => '',
        'clave'              => '',
        'clase_singular'     => '',
        'clase_plural'       => '',
        'instancia_singular' => '',
        'instancia_plural'   => '',
        'archivo_singular'   => '',
        'archivo_plural'     => '',
        'tabla'              => '',
        'vip'                => array(
            '' => array('tipo' => '', 'etiqueta' => '', 'filtro' => 1))
    );

    /**
     * Constructor
     */
    public function __construct() {ob}
        // Programas a escribir
        $this->modulo_completo();
        // Obtener de serpiente
        $serpiente = new Serpiente();
        // Relaciones, cada modulo con el que está relacionado sin incluir a los hijos
        $this->relaciones['columna']  = $serpiente->obtener_datos_del_modulo('XxxModuloNombre');
        // Padre, el módulo que mostrará a éste como un listado debajo de aquel
        $this->padre['columna']       = $serpiente->obtener_datos_del_modulo('XxxModuloNombre');
        // Hijos, los módulos que se mostrarán debajo del detalle como listados
        $this->hijos['identificador'] = $serpiente->obtener_datos_del_modulo('XxxModuloNombre');
        // Siempre se debe de cargar de serpiente esta informacion
        $this->sustituciones          = $serpiente->obtener_sustituciones($this->nombre);
        $this->instancia_singular     = $serpiente->obtener_instancia_singular($this->nombre);
        $this->estatus                = $serpiente->obtener_estatus($this->nombre);
    {cb} // constructor

{cb} // Clase Adan0000ModuloNombre

?>
