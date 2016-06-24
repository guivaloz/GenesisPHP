<?php
/**
 * GenesisPHP - AdmUsuarios ListadoCSV
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

namespace AdmUsuarios;

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
    // public $nom_corto;
    // public $nombre;
    // public $tipo;
    // public $estatus;
    // static public $param_nom_corto;
    // static public $param_nombre;
    // static public $param_tipo;
    // static public $param_estatus;
    // public $filtros_param;
    protected $estructura;
    const RAIZ_CSV_ARCHIVO = 'admusuarios.csv';

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el URL
        $this->nom_corto = $_GET[parent::$param_nom_corto];
        $this->nombre    = $_GET[parent::$param_nombre];
        $this->tipo      = $_GET[parent::$param_tipo];
        $this->estatus   = $_GET[parent::$param_estatus];
        // Estructura
        $this->estructura = array(
            'nom_corto'           => array('enca' => 'Nom. corto'),
            'nombre'              => array('enca' => 'Nombre'),
            'tipo'                => array('enca' => 'Tipo', 'cambiar' => Registro::$tipo_descripciones),
            'contrasena_descrito' => array('enca' => 'Contraseña'),
            'expira_en'           => array('enca' => 'Expira en'),
            'sesiones_contador'   => array('enca' => 'Sesiones'),
            'sesiones_ultima'     => array('enca' => 'Último ingreso'),
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
