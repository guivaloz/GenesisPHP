<?php
/**
 * GenesisPHP - AdmRoles ListadoCSV
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

namespace AdmRoles;

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
    // public $departamento;
    // public $departamento_nombre;
    // public $modulo;
    // public $modulo_nombre;
    // public $estatus;
    // static public $param_departamento;
    // static public $param_modulo;
    // static public $param_estatus;
    // public $filtros_param;
    protected $estructura;
    const RAIZ_CSV_ARCHIVO = 'admroles.csv';

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el URL
        $this->departamento = $_GET[parent::$param_departamento];
        $this->modulo       = $_GET[parent::$param_modulo];
        $this->estatus      = $_GET[parent::$param_estatus];
        // Estructura
        $this->estructura = array(
            'departamento_nombre' => array('enca' => 'Departamento'),
            'modulo_nombre'       => array('enca' => 'Módulo'),
            'permiso_maximo'      => array('enca' => 'Permiso máximo'),
            'estatus'             => array('enca' => 'Estatus','cambiar' => Registro::$estatus_descripciones));
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
