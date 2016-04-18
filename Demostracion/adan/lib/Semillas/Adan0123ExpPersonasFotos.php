<?php
/**
 * GenesisPHP - Semilla ExpPersonasFotos
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
 * Clase Adan0123ExpPersonasFotos
 */
class Adan0123ExpPersonasFotos extends \Arbol\Adan {

    // Nombre de este modulo
    public $nombre = 'ExpPersonasFotos';

    // Nombre de la tabla
    public $tabla_nombre = 'exp_personas_fotos';

    // Datos de la tabla
    public $tabla = array(
        'id'              => array('tipo' => 'serial'),
        'persona'         => array('tipo' => 'relacion',   'etiqueta' => 'Persona',            'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 61),

        'caracteres_azar' => array('tipo' => 'nombre',     'etiqueta' => 'Caracteres al azar', 'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 11, 'orden' => 1, 'vip' => 2),

        'creado'          => array('tipo' => 'fecha_hora', 'etiqueta' => 'Creado'),
        'estatus'         => array('tipo' => 'caracter',   'etiqueta' => 'Estatus',            'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 99,
            'descripciones' => array('A' => 'EN USO',                'B' => 'ELIMINADO'),
            'etiquetas'     => array('A' => 'En Uso',                'B' => 'Eliminado'),
            'iconos'        => array('A' => 'x-office-document.png', 'B' => 'user-trash.png'),
            'colores'       => array('A' => 'blanco',                'B' => 'gris'),
            'acciones'      => array('A' => 'listadoenuso',          'B' => 'listadoeliminados'))
    );

    // Reptil es leido por Serpiente
    static public $reptil = array(
        'etiqueta_singular'  => 'Foto',
        'etiqueta_plural'    => 'Fotos',
        'nom_corto_singular' => 'foto',
        'nom_corto_plural'   => 'fotos',
        'mensaje_singular'   => 'la foto',
        'mensaje_plural'     => 'las fotos',
        'clave'              => '',
        'clase_singular'     => '',
        'clase_plural'       => '',
        'instancia_singular' => '',
        'instancia_plural'   => '',
        'archivo_singular'   => '',
        'archivo_plural'     => '',
        'tabla'              => '',
        'vip'                => 'fecha',
        'listados'           => 'trenes'
    );

    /**
     * Constructor
     */
    public function __construct() {
        // Programas a escribir, este módulo gestiona imagenes
        $this->modulo_imagenes();
        // Obtener de serpiente
        $serpiente = new Serpiente();
        // Relaciones
        $this->relaciones['relacion'] = $serpiente->obtener_datos_del_modulo('XxxModuloNombre');
        // Padre
        $this->padre['relacion']      = $serpiente->obtener_datos_del_modulo('XxxModuloNombre');
        // Siempre se debe de cargar de serpiente esta informacion
        $this->sustituciones          = $serpiente->obtener_sustituciones($this->nombre);
        $this->instancia_singular     = $serpiente->obtener_instancia_singular($this->nombre);
        $this->estatus                = $serpiente->obtener_estatus($this->nombre);
        // Este módulo gestiona imagenes
        $this->imagen = array(
            'almacen_ruta' => 'imagenes/xxxrelacionesimagenes',
            'tamaños'      => array(
                'big'    => 1024,
                'middle' => 300,
                'small'  => 150),
            'caracteres'   => 'caracteres_azar',
            'etiqueta'     => 'Imagen',
            'variable'     => 'imagen'
        );
    } // constructor

} // Clase Adan0123ExpPersonasFotos

?>
