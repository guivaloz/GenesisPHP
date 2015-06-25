<?php
/**
 * GenesisPHP - Usuarios ListadoHTML
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

namespace Usuarios;

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
    // public $nom_corto;
    // public $nombre;
    // public $tipo;
    // public $estatus;
    // static public $param_nom_corto;
    // static public $param_nombre;
    // static public $param_tipo;
    // static public $param_estatus;
    // public $filtros_param;
    public $viene_listado = false; // Sirve para que en PaginaHTML se de cuenta de que viene el listado
    protected $listado_controlado; // Instancia de ListadoControladoHTML

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar Listado Controlado
        $this->listado_controlado = new \Base\ListadoControladoHTML();
        // Cargar la estructura
        $this->listado_controlado->estructura = array(
            'nom_corto' => array(
                'enca'    => 'Nom. corto',
                'pag'     => 'usuarios.php',
                'id'      => 'id',
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores),
            'nombre' => array(
                'enca'    => 'Nombre',
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores),
            'tipo' => array(
                'enca'    => 'Tipo',
                'cambiar' => Registro::$tipo_descripciones,
                'color'   => 'tipo',
                'colores' => Registro::$tipo_colores),
            'contrasena_descrito' => array(
                'enca'    => 'Contraseña',
                'color'   => 'contrasena_descrito_color',
                'colores' => Registro::$contrasena_colores),
            'expira_en' => array(
                'enca'    => 'Expira en',
                'color'   => 'expira_en_color',
                'colores' => Registro::$expira_en_colores),
            'sesiones_contador' => array(
                'enca'    => 'Sesiones',
                'color'   => 'sesiones_contador_color',
                'colores' => Registro::$sesiones_contador_colores),
            'sesiones_ultima' => array(
                'enca'    => 'Último ingreso',
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores),
            'estatus' => array(
                'enca'    => 'Estatus',
                'cambiar' => Registro::$estatus_descripciones,
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores));
        // Tomar parámetros que pueden venir en el URL
        $this->nom_corto          = $_GET[parent::$param_nom_corto];
        $this->nombre             = $_GET[parent::$param_nombre];
        $this->tipo               = $_GET[parent::$param_tipo];
        $this->estatus            = $_GET[parent::$param_estatus];
        $this->limit              = $this->listado_controlado->limit;
        $this->offset             = $this->listado_controlado->offset;
        $this->cantidad_registros = $this->listado_controlado->cantidad_registros;
        // Si algún filtro tiene valor, entonces viene_listado será verdadero
        if ($this->listado_controlado->viene_listado) {
            $this->viene_listado = true;
        } else {
            $this->viene_listado = ($this->nom_corto != '') || ($this->nombre != '') || ($this->tipo != '') || ($this->estatus != '');
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
            return $mensaje->html('Error');
        }
        // Eliminar columnas de la estructura que sean filtros aplicados
        if ($this->tipo != '') {
            unset($this->listado_controlado->estructura['tipo']);
        }
        if ($this->estatus != '') {
            unset($this->listado_controlado->estructura['estatus']);
        }
        // Elaborar Barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->encabezado();
        $barra->icono      = $this->sesion->menu->icono_en('usuarios');
        $barra->boton_descargar('usuarios.csv', $this->filtros_param);
        // Cargar Listado Controlado
        $this->listado_controlado->barra              = $barra;
        $this->listado_controlado->listado            = $this->listado;
        $this->listado_controlado->cantidad_registros = $this->cantidad_registros;
        $this->listado_controlado->variables          = $this->filtros_param;
        // Entregar
        return $this->listado_controlado->html();
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
