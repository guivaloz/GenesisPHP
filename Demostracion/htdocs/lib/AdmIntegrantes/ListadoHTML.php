<?php
/**
 * GenesisPHP - AdmIntegrantes ListadoHTML
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

namespace AdmIntegrantes;

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
    // public $usuario;
    // public $usuario_nombre;
    // public $departamento;
    // public $departamento_nombre;
    // public $estatus;
    // static public $param_usuario;
    // static public $param_departamento;
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
        $this->usuario      = $_GET[parent::$param_usuario];
        $this->departamento = $_GET[parent::$param_departamento];
        $this->estatus      = $_GET[parent::$param_estatus];
        // Estructura
        $this->estructura = array(
            'usuario_nombre' => array(
                'enca'    => 'Usuario',
                'pag'     => \AdmUsuarios\DetalleHTML::RAIZ_PHP_ARCHIVO,
                'param'   => array(parent::$param_usuario => 'usuario')),
            'departamento_nombre' => array(
                'enca'    => 'Departamento',
                'pag'     => \AdmDepartamentos\DetalleHTML::RAIZ_PHP_ARCHIVO,
                'param'   => array(parent::$param_departamento => 'departamento')),
            'poder' => array(
                'enca'    => 'Poder',
                'pag'     => DetalleHTML::RAIZ_PHP_ARCHIVO,
                'id'      => 'id',
                'cambiar' => Registro::$poder_descripciones,
                'color'   => 'poder',
                'colores' => Registro::$poder_colores),
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
            $this->viene_listado = ($this->usuario != '') || ($this->departamento != '') || ($this->estatus != '');
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
    protected function barra($in_encabezado='') {
        // Si viene el parametro se usa, si no, el encabezado por defecto
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->encabezado();
        }
        // Crear la barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $encabezado;
        $barra->icono      = $this->sesion->menu->icono_en('adm_integrantes');
    //  $barra->boton_descargar('adm_integrantes.csv', $this->filtros_param);
        // Entregar
        return $barra;
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
        if ($this->usuario != '') {
            unset($this->estructura['usuario_nom_corto']);
        }
        if ($this->departamento != '') {
            unset($this->estructura['departamento_nombre']);
        }
        if ($this->estatus != '') {
            unset($this->estructura['estatus']);
        }
        // Pasamos al listado controlado html
        $this->listado_controlado->estructura         = $this->estructura;
        $this->listado_controlado->listado            = $this->listado;
        $this->listado_controlado->cantidad_registros = $this->cantidad_registros;
        $this->listado_controlado->variables          = $this->filtros_param;
        $this->listado_controlado->barra              = $this->barra($in_encabezado);
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
