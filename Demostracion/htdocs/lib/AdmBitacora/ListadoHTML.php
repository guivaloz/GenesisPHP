<?php
/**
 * GenesisPHP - AdmBitacora ListadoHTML
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

namespace AdmBitacora;

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
    // public $tipo;
    // public $fecha_desde;
    // public $fecha_hasta;
    // static public $param_usuario;
    // static public $param_tipo;
    // static public $param_fecha_desde;
    // static public $param_fecha_hasta;
    // public $filtros_param;
    protected $estructura;
    protected $listado_html;

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el url
        $this->usuario     = $_GET[parent::$param_usuario];
        $this->tipo        = $_GET[parent::$param_tipo];
        $this->fecha_desde = $_GET[parent::$param_fecha_desde];
        $this->fecha_hasta = $_GET[parent::$param_fecha_hasta];
        // Estructura
        $this->estructura = array(
            'fecha' => array(
                'enca'    => 'Fecha',
                'pag'     => 'bitacora.php',
                'id'      => 'id'),
            'usuario_nom_corto' => array(
                'enca'    => 'Usuario',
                'pag'     => 'usuarios.php',
                'id'      => 'usuario'),
            'pagina' => array(
                'enca'    => 'Página',
                'pag'     => 'bitacora.php',
                'param'   => array('pagina' => 'pagina', 'id' => 'pagina_id')),
            'tipo' => array(
                'enca'    => 'Tipo',
                'cambiar' => Registro::$tipo_descripciones,
                'color'   => 'tipo',
                'colores' => Registro::$tipo_colores),
            'notas' => array(
                'enca'    => 'Notas'));
        // Iniciar listado controlado html
        $this->listado_html = new \Base\ListadoControladoHTML();
        // Su constructor toma estos parametros por url
        $this->limit              = $this->listado_html->limit;
        $this->offset             = $this->listado_html->offset;
        $this->cantidad_registros = $this->listado_html->cantidad_registros;
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
        if ($this->usuario != '') {
            unset($this->estructura['usuario_nom_corto']);
        }
        if ($this->tipo != '') {
            unset($this->estructura['tipo']);
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->encabezado();
        }
        // Barra html que incluye el boton para descargar el archivo csv
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $encabezado;
        $barra->icono      = $this->sesion->menu->icono_en('bitacora');
        $barra->boton_descargar('bitacora.csv', $this->filtros_param);
        // Pasamos al listado controlado html
        $this->listado_html->estructura         = $this->estructura;
        $this->listado_html->listado            = $this->listado;
        $this->listado_html->cantidad_registros = $this->cantidad_registros;
        $this->listado_html->variables          = $this->filtros_param;
    //  $this->listado_html->limit              = $this->limit;
        $this->listado_html->barra              = $barra;
        // Entregar
        return $this->listado_html->html();
    } // html

} // Clase ListadoHTML

?>
