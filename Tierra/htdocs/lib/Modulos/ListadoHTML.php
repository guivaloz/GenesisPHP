<?php
/**
 * GenesisPHP - Módulos ListadoHTML
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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

namespace Modulos;

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
    public $viene_listado = false; // Sirve para que en PaginaHTML se de cuenta de que viene el listado
    protected $estructura;         // Estructura del listado
    protected $listado_controlado; // Instancia de ListadoControladoHTML

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar ListadoControladoHTML
        $this->listado_controlado = new \Base\ListadoControladoHTML();
        // Cargar la estructura
        $this->listado_controlado->estructura = array(
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
        // Tomar parámetros que pueden venir en el URL
        $this->nombre             = $_GET[parent::$param_nombre];
        $this->clave              = $_GET[parent::$param_clave];
        $this->permiso_maximo     = $_GET[parent::$param_permiso_maximo];
        $this->poder_minimo       = $_GET[parent::$param_poder_minimo];
        $this->estatus            = $_GET[parent::$param_estatus];
        $this->limit              = $this->listado_controlado->limit;
        $this->offset             = $this->listado_controlado->offset;
        $this->cantidad_registros = $this->listado_controlado->cantidad_registros;
        // Si algún filtro tiene valor, entonces viene_listado será verdadero
        if ($this->listado_controlado->viene_listado) {
            $this->viene_listado = true;
        } else {
            $this->viene_listado = ($this->nombre != '') || ($this->clave != '') || ($this->permiso_maximo != '') || ($this->poder_minimo != '') || ($this->estatus != '');
        }
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Si no se ha consultado
        try {
            if (!$this->consultado) {
                $this->consultar();
            }
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html($in_encabezado);
        }
        // Eliminar columnas de la estructura que sean filtros aplicados
        if ($this->permiso_maximo != '') {
            unset($this->listado_controlado->estructura['permiso_maximo']);
        }
        if ($this->poder_minimo != '') {
            unset($this->listado_controlado->estructura['poder_minimo']);
        }
        if ($this->estatus != '') {
            unset($this->listado_controlado->estructura['estatus']);
        }
        // Cargar en listado_controlado
        $this->listado_controlado->listado            = $this->listado;
        $this->listado_controlado->cantidad_registros = $this->cantidad_registros;
        $this->listado_controlado->variables          = $this->filtros_param;
        // Entregar
        return $this->listado_html->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->listado_controlado->javascript();
    } // javascript

} // Clase ListadoHTML

?>
