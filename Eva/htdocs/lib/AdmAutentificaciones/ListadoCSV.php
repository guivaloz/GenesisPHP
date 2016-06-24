<?php
/**
 * GenesisPHP - AdmAutentificaciones ListadoCSV
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

namespace AdmAutentificaciones;

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
    // public $usuario;
    // public $usuario_nombre;
    // public $tipo;
    // static public $param_usuario;
    // static public $param_tipo;
    // public $filtros_param;
    protected $estructura;
    const RAIZ_CSV_ARCHIVO = 'admautentificaciones.csv';

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el URL
        $this->usuario = $_GET[parent::$param_usuario];
        $this->tipo    = $_GET[parent::$param_tipo];
        // Estructura
        $this->estructura = array(
            'fecha'             => array('enca' => 'Fecha'),
            'tipo'              => array('enca' => 'Tipo', 'cambiar' => Registro::$tipo_descripciones),
            'nom_corto'         => array('enca' => 'Login'),
            'usuario_nom_corto' => array('enca' => 'Usuario'),
            'ip'                => array('enca' => 'Dirección IP'));
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
