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

////
//// DATOS DE LA TABLA
////
//// Para cada columna de la tabla con la que quiera trabajar...
////  'columna' => array( Arreglo asociativo ) <- Columna debe ser igual al nombre de la columna en la tabla
//// No es necesario usar todas las columnas de la tabla.
//// Pero toda columna declarada debe de exitir en la base de datos.
////
//// Es OBLIGATORIO declarar el 'tipo' que va de acuerdo al tipo de dato en la base de datos y a su contenido
////
////  --- POSTGRESQL ---   --- GENESIS ---
////  serial o integer  => 'serial' o 'relacion'
////  character(1)      => 'caracter'
////  character varying => 'nombre',
////                       'mayusculas',
////                       'frase',
////                       'nom_corto', 'curp', 'rfc', 'cuip', 'contraseña', 'email', 'clave', 'telefono'
////  text              => 'notas'
////  integer           => 'entero', 'peso', 'estatura'
////  numeric(5,2)      => 'dinero', 'porcentaje'
////  real, double      => 'flotante'
////  date              => 'fecha'
////  timestamp         => 'fecha_hora'
////
////  --- POSTGIS ---      --- GENESIS ---
////  geography point   => 'geopunto'
////
//// Es opcional definir la etiqueta, omitirla hace que esa columna no aparezca en detalles, listados, etc.
////  'etiqueta'   => Texto descriptivo de la columna para el detalle, formularios y encabezados de los listados
////
//// Los siguientes parámetros opcionales usan números, si no aparece o vale cero NO OPERA...
////  'validacion' => 1 validar y es opcional, 2 validar y es obligatorio (o sea que no se permite nulo)
////                  Si tipo es relacion: 1 ese valor puede ser nulo y se hace la relación sólo cuando tiene valor.
////                                       2 ese valor NUNCA debe ser nulo y siempre hará la relación para consultar valores de otras tablas.
////  'vip'        => 1 es un dato que identifica al registro y se usará en los mensajes, 2 será vínculo en los listados
////  'filtro'     => 1 es un filtro para los listados y la búsqueda, 2 es un rango (desde-hasta)
////  'orden'      => 1 o mayor es orden ascendente en listados, -1 o menor es orden descendente
////  'listado'    => 1 o mayor lugar que ocupará (izquierda a derecha) en los listados
////
//// Los siguientes parámetros opcionales usan números o fragmentos de código PHP
////  'agregar'    => 1 se usará en el formulario de nuevo y al insertar el registro
////  'modificar'  => 1 se usará en el formulario para modificar y al modificar el registro
////
//// El siguiente parámetro opcional usa sólo fragmento de código PHP
////  'detalle'    => Fragmento de PHP que debe de entregar un texto a aparecer a la derecha del valor de la columna, en el DetalleHTML
////
//// Ejemplos donde se arma el nombre completo a partir de otras columnas:
////    'nombre_completo'   => array('tipo' => 'nombre', 'etiqueta' => 'Nombre completo', 'validacion' => 2, 'listado' => 11, 'orden' => 1, 'vip' => 2,
////        'agregar'   => 'sprintf("%s, %s", trim($this->apellido_paterno.\' \'.$this->apellido_materno), $this->nombres)',
////        'modificar' => 'sprintf("%s, %s", trim($this->apellido_paterno.\' \'.$this->apellido_materno), $this->nombres)'),
////    'nombres_apellidos' => array('tipo' => 'nombre', 'etiqueta' => 'Nombre y apellidos', 'validacion' => 2,
////        'agregar'   => 'sprintf("%s %s %s", $this->nombres, $this->apellido_paterno, $this->apellido_materno)',
////        'modificar' => 'sprintf("%s %s %s", $this->nombres, $this->apellido_paterno, $this->apellido_materno)'),
////
//// Ejemplo donde se va a poner la edad a la derecha de la fecha de nacimiento en el DetalleHTML
////    'nacimiento_fecha' => array('tipo' => 'fecha', 'etiqueta' => 'Nacimiento', 'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 2,
////        'detalle' => '" (".calcular_edad($this->nacimiento_fecha).")"'),
////
//// A CONTINUACION UN EJEMPLO COMPLETO QUE DEBE DE MODIFICAR...
////

    // Datos de la tabla
    public $tabla = array(
        'id'    => array('tipo' => 'serial'),
        'grupo' => array('tipo' => 'relacion', 'etiqueta' => 'Grupo', 'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 61),

        'nombre'     => array('tipo' => 'nombre',   'etiqueta' => 'Nombre',        'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 11, 'orden' => 1, 'vip' => 2),
        'sexo'       => array('tipo' => 'caracter', 'etiqueta' => 'Sexo',          'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 43,
            'descripciones' => array('M' => 'HOMBRE',  'F' => 'MUJER'),
            'etiquetas'     => array('M' => 'Hombres', 'F' => 'Mujeres'),
            'colores'       => array('M' => 'azul',    'F' => 'rosa')),
        'nacimiento' => array('tipo' => 'fecha',    'etiqueta' => 'Nacimiento',    'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 2
            'detalle' => '" (".calcular_edad($this->nacimiento).")"'),
        'estatura'   => array('tipo' => 'entero',   'etiqueta' => 'Estatura (cm)', 'validacion' => 1, 'agregar' => 1, 'modificar' => 1, 'filtro' => 2, 'listado' => 41),

        'creado'  => array('tipo' => 'fecha_hora', 'etiqueta' => 'Creado'),
        'notas'   => array('tipo' => 'notas',      'etiqueta' => 'Notas',    'validacion' => 1, 'agregar' => 1, 'modificar' => 1),
        'estatus' => array('tipo' => 'caracter',   'etiqueta' => 'Estatus',  'validacion' => 2, 'agregar' => 1, 'modificar' => 1, 'filtro' => 1, 'listado' => 99,
            'descripciones' => array('A' => 'EN USO',                'B' => 'ELIMINADO'),
            'etiquetas'     => array('A' => 'En Uso',                'B' => 'Eliminado'),
            'iconos'        => array('A' => 'x-office-document.png', 'B' => 'user-trash.png'),
            'colores'       => array('A' => 'blanco',                'B' => 'gris'),
            'acciones'      => array('A' => 'listadoenuso',          'B' => 'listadoeliminados'))
    );

////
//// REPTIL
////
//// Propiedad estática que es leía por Serpiente con información para todos los demás módulos
////    'etiqueta_singular'  => Texto descriptivo singular para el título
////    'etiqueta_plural'    => Texto descriptivo plural para el título
////    'nom_corto_singular' => nombre en singular y en minúsculas
////    'nom_corto_plural'   => nombre en plural y en minúsculas
////    'mensaje_singular'   => Pronombre y sustantivo en singular para los mensajes
////    'mensaje_plural'     => Pronombre y sustantivo en plural para los mensajes
////    'clave'              => Clave del módulo que le corresponde en la tabla de módulos
////    'clase_singular'     => Nombre de la clase en singular
////    'clase_plural'       => Nombre de la clase en plural
////    'instancia_singular' => Nombre de la clase en singular
////    'instancia_plural'   => Nombre de la clase en plural
////    'archivo_singular'   => Nombre del archivo en singular
////    'archivo_plural'     => Nombre del archivo en plural
////    'tabla'              => Nombre de la tabla en la base de datos, puede que consulte una tabla distinta a la clave
////    'vip'                => Información de las columnas más importantes de esta tabla para usar en otros módulos
////    'listados'           => Opcional, si vale 'trenes' usará TrenHTML en lugar de ListadoHTML, úselo en módulos de imágenes
////
//// VIP de Reptil es un arreglo de arreglos asociativos donde se definen el tipo, la etiqueta y filtro
//// de cada columna que quiera que aparezca en otras tablas. El orden que use será su posición de izquierda a derecha.
////    'vip'                => array(
////        'nombre'      => array('tipo' => 'nombre',   'etiqueta' => 'Colonia',     'filtro' => 1),
////        'corporacion' => array('tipo' => 'caracter', 'etiqueta' => 'Corporación', 'filtro' => 1))
////
//// EJEMPLO:
////    static public $reptil = array(
////        'etiqueta_singular'  => 'Persona',
////        'etiqueta_plural'    => 'Personas',
////        'nom_corto_singular' => 'persona',
////        'nom_corto_plural'   => 'personas',
////        'mensaje_singular'   => 'la persona',
////        'mensaje_plural'     => 'las personas',
////        'clave'              => 'coe_personas',
////        'clase_singular'     => 'CoePersona',
////        'clase_plural'       => 'CoePersonas',
////        'instancia_singular' => 'persona',
////        'instancia_plural'   => 'personas',
////        'archivo_singular'   => 'coepersona',
////        'archivo_plural'     => 'coepersonas',
////        'tabla'              => 'coe_personas',
////        'vip'                => 'nombre_completo');

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

////
//// CONSTRUCTOR - PROGRAMAS A ESCRIBIR
////
//// Cambie la ejecucion del método a SOLO UNO de ESTOS...
////    $this->modulo_completo();      // POR DEFECTO, crea TODOS los programas
////    $this->modulo_solo_consulta(); // No crea el formulario, eliminar y recuperar
////    $this->modulo_sin_busqueda();  // No crea la busqueda
////    $this->modulo_sin_herederos(); // No crea el select_opciones
////
//// CONSTRUCTOR - SERPIENTE
////
//// Para CADA RELACION debe agregar...
////    $this->relaciones['columna']       = $serpiente->obtener_datos_del_modulo('XxxModuloNombre');
//// EJEMPLOS...
////    $this->relaciones['grupo']         = $serpiente->obtener_datos_del_modulo('XxxGrupos');
////    $this->relaciones['clasificacion'] = $serpiente->obtener_datos_del_modulo('XxxClasificaciones');
////    $this->relaciones['marca']         = $serpiente->obtener_datos_del_modulo('XxxMarcas');
////
//// Las relaciones deben cubrir todas las "raices" de éste módulo.
//// Por ejemplo, si trabaja con el módulo Equipos y está relacionado con Resguardos,
//// y éste a su vez con Personas, y luego éste con Departamentos.
////    $this->relaciones['departamento'] = $serpiente->obtener_datos_del_modulo('InfDepartamentos');
////    $this->relaciones['persona']      = $serpiente->obtener_datos_del_modulo('InfPersonas');
////    $this->relaciones['resguardo']    = $serpiente->obtener_datos_del_modulo('InfResguardos');
////
//// Si el módulo tiene UN PADRE...
////    $this->padre['columna']            = $serpiente->obtener_datos_del_modulo('XxxModuloPadreNombre');
//// EJEMPLO...
////    $this->padre['proyecto']           = $serpiente->obtener_datos_del_modulo('XxxProyectos');
////
//// En cambio, si el presente módulo es PADRE y tiene HIJOS...
////    $this->hijos['identificador']      = $serpiente->obtener_datos_del_modulo('XxxModuloHijoNombre');
//// EJEMPLOS...
////    $this->hijos['direcciones']        = $serpiente->obtener_datos_del_modulo('XxxDirecciones');
////    $this->hijos['capacitaciones']     = $serpiente->obtener_datos_del_modulo('XxxCapacitaciones');
////
//// Sustituciones, Instancia singular y Estatus SIEMPRE DEBEN ESTAR PRESENTES
//// Sustituciones: Debe cargarse con el arreglo asociativo de sustituciones SED con ayuda del método obtener_sustituciones
//// Instancia singular: Es el nombre de la variable para este mismo módulo, use el método obtener_instancia_singular
//// Estatus: Por defecto A es EN USO y B es ELIMINADO; de otra forma, serpiente proporciona cual letra es para cada caso.
////
//// CONSTRUCTOR - IMPONER FILTROS A LAS CONSULTAS
////
//// El fin es limitar los registros con los que pueda trabajar el módulo.
//// Por defecto estas propiedades están vacías y no surten efecto.
////
//// EJEMPLO 1 la imposición es en el valor de una columna de la misma tabla:
////    // SQL IMPUESTO PARA WHERE, QUE SOLO TRABAJE CON etapa IGUAL A A
////    $this->filtro_impuesto_sql = "etapa = 'A'";
////
//// EJEMPLO 2 la imposición es en el valor de una columna en OTRA TABLA:
////    // SQL IMPUESTO PARA TABLE, LA TABLA xxx_personas DEBE USARSE SIEMPRE
////    $this->tabla_impuesto_sql  = "xxx_personas";
////    // SQL IMPUESTO PARA WHERE, ES LA ETAPA FIJA EN 'A' DE ASPIRANTES
////    $this->filtro_impuesto_sql = "xxx_personas.etapa = 'A'";
////

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
