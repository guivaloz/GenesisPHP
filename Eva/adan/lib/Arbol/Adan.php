<?php
/**
 * GenesisPHP - Árbol Adán
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

namespace Arbol;

/**
 * Clase Adan
 */
class Adan {

    /**
     * ID
     *
     * Nombre de la columna primaria (PRIMARY KEY) de la tabla, por defecto es id
     */
    public $primary_key = 'id';

    /*
     * Relaciones
     *
     * Debe contener un arreglo de datos para cada columna de tipo 'relacion'.
     * Es un arreglo de arreglos asociativos. Alimente con el método obtener_datos_del_modulo de Serpiente.
     */
    public $relaciones = array();

    /*
     * Hijos
     *
     * Son los módulos con dependencia necesaria con el presente módulo. Habita los botones para agregarlos.
     * Es un arreglo de arreglos asociativos. Alimente con el método obtener_datos_del_modulo de Serpiente.
     */
    public $hijos = array();

    /*
     * Padre
     *
     * Son los datos del módulo padre cuando éste es hijo. Habilita el botón para agregar con el padre.
     * Es un arreglo de arreglos asociativos. Alimente con el método obtener_datos_del_modulo de Serpiente.
     */
    public $padre = array();

    /*
     * Sustituciones
     *
     * Contiene las sustituciones SED a realizar en el código PHP.
     * Es un arreglo asociativo. Use el método obtener_sustituciones de Serpiente.
     */
    public $sustituciones;

    /**
     * Instancia singular
     *
     * Es una cadena de texto que contiene el nombre de la variable para una instancia de este mismo módulo
     */
    public $instancia_singular;

    /**
     * Imagen
     *
     * Arreglo asociativo que contiene los datos para usar el módulo como gestor de imágenes jpg
     */
    public $imagen = array();

    /**
     * Mapa
     *
     * Arreglo de arreglos que contiene los datos para usar el módulo como creador de mapas
     */
    public $mapa = array();

    /**
     * Estatus
     *
     * Por defecto se maneja que registro está EN USO o ELIMNADO
     * array( 'enuso' => 'A', 'eliminado' => 'B')
     * Si hubiera otra forma de manejar los estatus, es necesario saber cual letra será
     * para eliminar (eliminado) y cual para recuperar (enuso)
     */
    public $estatus;

    /**
     * Registro Consultar PHP
     *
     * Fragmento de PHP para arrojar una excepción cuando se cumpla una condición, se usará en Registro
     */
    public $registro_consultar_php;

    /**
     * Listado Consultar PHP
     *
     * Fragmento de PHP para agregar un filtro en el WHERE a la consulta de Listado
     */
    public $listado_consultar_php;

    /**
     * Tabla Impuesto SQL
     *
     * Fragmento de SQL para injertar siempre en la parte del TABLE
     */
    public $tabla_impuesto_sql;

    /**
     * Filtro Impuesto SQL
     *
     * Fragmento de SQL para injertar siempre en la parte del WHERE
     */
    public $filtro_impuesto_sql;

    /*
     * Programas
     *
     * Habilita o deshabilita la creación de programas(clases) del módulo.
     * Es un arreglo asociativo donde los que valen uno son los programas que serán creados.
     */
    protected $programas = array(
        'formulario'      => 1,
        'busqueda'        => 1,
        'select_opciones' => 1,
        'eliminar'        => 1,
        'recuperar'       => 1,
        'listado'         => 1,
        'listadocsv'      => 0,
        'tren'            => 0,
        'impresiones'     => 0,
        'gráficas'        => 0,
        'mapa'            => 0);

    /**
     * Módulo completo
     */
    public function modulo_completo() {
        $this->programas['formulario']        = 1;
        $this->programas['busqueda']          = 1;
        $this->programas['select_opciones']   = 1;
        $this->programas['eliminar']          = 1;
        $this->programas['recuperar']         = 1;
        $this->programas['listado']           = 1;
        $this->programas['tren']              = 0; // Sin tren
        $this->programas['impresiones']       = 0; // Sin impresiones
        $this->programas['gráficas']          = 0; // Sin gráficas
        $this->programas['mapa']              = 0; // Sin mapa
        $this->programas['imagen_web_ultima'] = 0; // Sin imagen en detalle
    } // modulo_completo

    /**
     * Módulo para imágenes
     */
    public function modulo_imagenes() {
        $this->programas['imagen_web_ultima'] = 1; // Poner la última imagen en el detalle
        $this->programas['select_opciones']   = 0; // Sin select
        $this->programas['listado']           = 0; // Sin listado
        $this->programas['tren']              = 1; // Usa tren en lugar de listados
    } // modulo_imagenes

    /**
     * Módulo para impresiones
     */
    public function modulo_impresiones() {
        $this->programas['select_opciones'] = 0; // Sin select
        $this->programas['listado']         = 1; // Usa listado
        $this->programas['tren']            = 0; // Sin tren
        $this->programas['impresiones']     = 1; // Impresiones
        $this->programas['gráficas']        = 0; // Sin gráficas
        $this->programas['mapa']            = 0; // Sin mapa
    } // modulo_impresiones

    /**
     * Módulo para mapa
     */
    public function modulo_mapa() {
        $this->programas['select_opciones'] = 0; // Sin select
        $this->programas['listado']         = 1; // Usa listado
        $this->programas['tren']            = 0; // Sin tren
        $this->programas['impresiones']     = 0; // Sin impresiones
        $this->programas['gráficas']        = 0; // Sin gráficas
        $this->programas['mapa']            = 1; // Usa mapa
    } // modulo_mapa

    /**
     * Módulo para gráficas
     */
    public function modulo_graficas() {
        $this->programas['select_opciones'] = 0; // Sin select
        $this->programas['listado']         = 1; // Usa listado
        $this->programas['tren']            = 0; // Sin tren
        $this->programas['impresiones']     = 0; // No son impresiones
        $this->programas['gráficas']        = 1; // Son gráficas
        $this->programas['mapa']            = 0; // Sin mapa
    } // modulo_graficas

    /**
     * Módulo Sólo Consulta
     *
     * Sólo se crearán programas para consultar
     */
    public function modulo_solo_consulta() {
        $this->programas['formulario'] = 0;
        $this->programas['eliminar']   = 0;
        $this->programas['recuperar']  = 0;
    } // modulo_completo

    /**
     * Módulo sin búsqueda
     *
     * Deshabilita la búsqueda
     */
    public function modulo_sin_busqueda() {
        $this->programas['busqueda'] = 0;
    } // modulo_sin_herederos

    /**
     * Módulo sin herederos
     *
     * Los demás módulos NO usan este módulo, así que no hay programas para elegir un registro en relación
     */
    public function modulo_sin_herederos() {
        $this->programas['select_opciones'] = 0;
    } // modulo_sin_herederos

    /**
     * Si hay que crear un programa
     *
     * @returns boolean Verdadero si se va a crear
     */
    public function si_hay_que_crear($in_prog) {
        if (!array_key_exists($in_prog, $this->programas)) {
            die("ERROR en Adan: Se pregunta si hay que crear $in_prog y NO está programado.");
        }
        if ($this->programas[$in_prog] == 1) {
            return true;
        } else {
            return false;
        }
    } // si_hay_que_crear

    /**
     * Extraer código PHP de un script para crear LaTeX
     *
     * @return string Código PHP
     */
    public function extraer_php_latex_de($in_ruta) {
        // Validar parametro
        if (!is_string($in_ruta) || ($in_ruta == '')) {
            die("Error al extraer PHP para LaTeX: La ruta no es una cadena de texto válida.");
        }
        // Validar que exista el archivo
        if (!file_exists($in_ruta)) {
            die("Error al extraer PHP para LaTeX: No existe el archivo $in_ruta");
        }
        // Cargar el archivo en este arreglo
        $lineas = file($in_ruta);
        // Iniciar arreglo vacio para juntar PHP
        $extraccion = array();
        $capturando = false;
        // Para cada linea del archivo
        for ($i=0; $i<count($lineas); $i++) {
            if (strpos($lineas[$i],"// LATEX INICIA") !== false){
                $capturando = true;
                $i++; // Brinca la linea con el comentario
            }
            if (strpos($lineas[$i],"// LATEX TERMINA") !== false){
                $capturando = false;
            }
            if ($capturando) {
                $extraccion[] = $lineas[$i];
            }
        }
        // Validar
        if (count($extraccion) == 0) {
            die("Error al extraer PHP para LaTeX: No está escrito // LATEX INICIA en $in_ruta");
        }
        if ($capturando == true) {
            die("Error al extraer PHP para LaTeX: No está escrito // LATER TERMINA en $in_ruta");
        }
        // Entregar
        return implode('', $extraccion);
    } // extraer_php_latex_de

} // Clase Adan

?>
