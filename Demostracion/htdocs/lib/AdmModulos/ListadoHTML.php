<?php
/**
 * GenesisPHP - AdmModulos ListadoHTML
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
 * Clase ListadoHTML
 */
class ListadoHTML extends Listado {

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
    public $viene_listado;         // Es verdadero si en el URL vienen filtros
    protected $listado_controlado; // Instancia de ListadoControladoHTML
    protected $estructura;         // Arreglo asociativo con datos de las columnas

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el url
        $this->nombre         = $_GET[parent::$param_nombre];
        $this->clave          = $_GET[parent::$param_clave];
        $this->permiso_maximo = $_GET[parent::$param_permiso_maximo];
        $this->poder_minimo   = $_GET[parent::$param_poder_minimo];
        $this->estatus        = $_GET[parent::$param_estatus];
        // Estructura
        $this->estructura = array(
            'orden' => array(
                'enca'    => 'Orden'),
            'icono'  => array(
                'enca'    => 'Ícono',
                'sprintf' => '<img src="imagenes/16x16/%s">'),
            'nombre'  => array(
                'enca'    => 'Nombre',
                'pag'     => 'modulos.php'),
            'clave' => array(
                'enca'    => 'Clave'),
            'permiso_maximo'  => array(
                'enca'    => 'Permiso máximo',
                'cambiar' => Registro::$permiso_maximo_descripciones,
                'color'   => 'permiso_maximo',
                'colores' => Registro::$permiso_maximo_colores),
            'poder_minimo'  => array(
                'enca'    => 'Poder mínimo',
                'cambiar' => Registro::$poder_minimo_descripciones,
                'color'   => 'poder_minimo',
                'colores' => Registro::$poder_minimo_colores),
            'estatus' => array(
                'enca'    => 'Estatus',
                'cambiar' => Registro::$estatus_descripciones,
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores));
        // Iniciar listado controlado html
        $this->listado_controlado = new \Base\ListadoControladoHTML();
        // Su constructor toma estos parametros por url
        $this->limit              = $this->listado_controlado->limit;
        $this->offset             = $this->listado_controlado->offset;
        $this->cantidad_registros = $this->listado_controlado->cantidad_registros;
        // Si cualquiera de los filtros tiene valor, entonces viene listado sera verdadero
        if ($this->listado_controlado->viene_listado) {
            $this->viene_listado = true;
        } else {
            $this->viene_listado = ($this->nombre != '') || ($this->clave != '') || ($this->permiso_maximo != '') || ($this->poder_minimo != '') || ($this->estatus != '');
        }
        // Ejecutar el constructor del padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * Barra
     *
     * @param  string Encabezado opcional
     * @return mixed  Instancia de BarraHTML
     */
    public function barra($in_encabezado='') {
    } // barra

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function html($in_encabezado='') {
        // Consultar
        try {
            $this->consultar();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html($in_encabezado);
        }
        // Eliminar columnas de la estructura que sean filtros aplicados
        if ($this->permiso_maximo != '') {
            unset($this->estructura['permiso_maximo']);
        }
        if ($this->poder_minimo != '') {
            unset($this->estructura['poder_minimo']);
        }
        if ($this->estatus != '') {
            unset($this->estructura['estatus']);
        }
        // Pasamos al listado controlado html
        $this->listado_controlado->estructura         = $this->estructura;
        $this->listado_controlado->listado            = $this->listado;
        $this->listado_controlado->cantidad_registros = $this->cantidad_registros;
        $this->listado_controlado->variables          = $this->filtros_param;
    //  $this->listado_controlado->limit              = $this->limit;
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->encabezado();
        }
        // Entregar
        return $this->listado_controlado->html($encabezado, $this->sesion->menu->icono_en('modulos'));
    } // html

} // Clase ListadoHTML

?>
