<?php
/**
 * GenesisPHP - Semilla ExpPersonasDomicilios
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
 * Clase Adan0125ExpPersonasDomicilios
 */
class Adan0125ExpPersonasDomicilios extends \Arbol\Adan {

    // Nombre de este modulo
    public $nombre = 'ExpPersonasDomicilios';

    // Nombre de la tabla
    public $tabla_nombre = 'exp_personas_domicilios';

    // Datos de la tabla
    public $tabla = array(
        'id'           => array('tipo' => 'serial'),
        'persona'      => array('tipo' => 'relacion', 'etiqueta' => 'Persona',          'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 61),

        'tipo'         => array('tipo' => 'caracter', 'etiqueta' => 'Tipo',             'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 43,
            'descripciones' => array('C' => 'Casa', 'F' => 'De un familiar', 'I' => 'La de la credencial IFE', 'T' => 'Trabajo', 'O' => 'Otro'),
            'etiquetas'     => array('C' => 'Casa', 'F' => 'De un familiar', 'I' => 'La de la credencial IFE', 'T' => 'Trabajo', 'O' => 'Otro'),
            'colores'       => array('C' => 'rosa', 'F' => 'azul',           'I' => 'amarillo',                'T' => 'verde',   'O' => 'gris')),
        'calle'        => array('tipo' => 'nombre',   'etiqueta' => 'Calle',            'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 11, 'orden' => 1, 'vip' => 2),
        'numero'       => array('tipo' => 'nombre',   'etiqueta' => 'Número',           'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 12, 'orden' => 2),
        'entre_calles' => array('tipo' => 'nombre',   'etiqueta' => 'Entre calles',     'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1),
        'colonia'      => array('tipo' => 'nombre',   'etiqueta' => 'Colonia',          'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 13),
        'codigo_postal'=> array('tipo' => 'entero',   'etiqueta' => 'C.P.',             'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 2),
        'telefonos'    => array('tipo' => 'nombre',   'etiqueta' => 'Teléfonos',        'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1),

        'inicio'       => array('tipo' => 'fecha',    'etiqueta' => 'Fecha de inicio',  'validacion' => 1, 'agregar' => 1, 'modificar' => 1),
        'termino'      => array('tipo' => 'fecha',    'etiqueta' => 'Fecha de término', 'validacion' => 1, 'agregar' => 1, 'modificar' => 1),
        'estatus'      => array('tipo' => 'caracter', 'etiqueta' => 'Estatus',          'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 99,
            'descripciones' => array('A' => 'EN USO',                'B' => 'ELIMINADO'),
            'etiquetas'     => array('A' => 'En Uso',                'B' => 'Eliminado'),
            'iconos'        => array('A' => 'x-office-document.png', 'B' => 'user-trash.png'),
            'colores'       => array('A' => 'blanco',                'B' => 'gris'),
            'acciones'      => array('A' => 'listadoenuso',          'B' => 'listadoeliminados'))
    );

    // Reptil es leido por Serpiente
    static public $reptil = array(
        'etiqueta_singular'  => 'Domicilio',
        'etiqueta_plural'    => 'Domicilios',
        'nom_corto_singular' => 'domicilio',
        'nom_corto_plural'   => 'domicilios',
        'mensaje_singular'   => 'el domicilio',
        'mensaje_plural'     => 'los domicilios',
        'clave'              => 'exp_personas_domicilios',
        'clase_singular'     => 'ExpPersonaDomicilio',
        'clase_plural'       => 'ExpPersonasDomicilios',
        'instancia_singular' => 'persona_domicilio',
        'instancia_plural'   => 'personas_domicilios',
        'archivo_singular'   => 'exppersonadomicilio',
        'archivo_plural'     => 'exppersonasdomicilios',
        'tabla'              => 'exp_personas_domicilios',
        'vip'                => array(
            'calle' => array('tipo' => 'nobre', 'etiqueta' => 'Domicilio', 'filtro' => 1))
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

} // Clase Adan0125ExpPersonasDomicilios

?>
