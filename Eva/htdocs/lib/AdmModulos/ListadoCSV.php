<?php
/**
 * GenesisPHP - AdmModulos ListadoCSV
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

namespace AdmModulos;

/**
 * Clase ListadoCSV
 */
class ListadoCSV extends Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    // public $nombre;
    // public $clave;
    // public $permiso_maximo;
    // public $poder_minimo;
    // public $estatus;
    // static public $param_nombre;
    // static public $param_clave;
    // static public $param_permiso_maximo;
    // static public $param_poder_minimo;
    // static public $param_estatus;
    // public $filtros_param;
    protected $estructura;
    const RAIZ_CSV_ARCHIVO = 'admmodulos.csv';

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el URL
        $this->nombre         = $_GET[parent::$param_nombre];
        $this->clave          = $_GET[parent::$param_clave];
        $this->permiso_maximo = $_GET[parent::$param_permiso_maximo];
        $this->poder_minimo   = $_GET[parent::$param_poder_minimo];
        $this->estatus        = $_GET[parent::$param_estatus];
        // Estructura
        $this->estructura = array(
            'orden'          => array('enca' => 'Orden', 'formato' => 'entero'),
            'nombre'         => array('enca' => 'Nombre'),
            'clave'          => array('enca' => 'Clave'),
            'permiso_maximo' => array('enca' => 'Permiso máximo', 'cambiar' => Registro::$permiso_maximo_descripciones),
            'poder_minimo'   => array('enca' => 'Poder mínimo',   'cambiar' => Registro::$poder_minimo_descripciones),
            'estatus'        => array('enca' => 'Estatus',        'cambiar' => Registro::$estatus_descripciones));
        // Ejecutar el constructor del padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * CSV
     *
     * @return string CSV
     */
    public function csv() {
        // Consultar si no se ha hecho
        if (!$this->consultado) {
            $this->consultar();
        }
        // Iniciar listado csvniciar listado csv
        $listado_csv             = new \Base2\ListadoCSV();
        $listado_csv->estructura = $this->estructura;
        $listado_csv->listado    = $this->listado;
        // Entregar
        return $listado_csv->csv();
    } // csv

} // Clase ListadoCSV

?>
