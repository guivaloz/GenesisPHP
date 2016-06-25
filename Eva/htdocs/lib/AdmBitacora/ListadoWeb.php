<?php
/**
 * GenesisPHP - AdmBitacora ListadoWeb
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
 * Clase ListadoWeb
 */
class ListadoWeb extends Listado {

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
    public $viene_listado;         // Es verdadero si en el URL vienen filtros
    protected $listado_controlado; // Instancia de \Base2\ListadoWebControlado
    protected $estructura;         // Arreglo asociativo con datos de las columnas

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el URL
        $this->usuario     = $_GET[parent::$param_usuario];
        $this->tipo        = $_GET[parent::$param_tipo];
        $this->fecha_desde = $_GET[parent::$param_fecha_desde];
        $this->fecha_hasta = $_GET[parent::$param_fecha_hasta];
        // Estructura
        $this->estructura = array(
            'fecha' => array(
                'enca'    => 'Fecha',
                'pag'     => DetalleWeb::RAIZ_PHP_ARCHIVO,
                'id'      => 'id'),
            'usuario_nom_corto' => array(
                'enca'    => 'Usuario',
                'pag'     => \AdmUsuarios\DetalleWeb::RAIZ_PHP_ARCHIVO,
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
        $this->listado_controlado = new \Base2\ListadoWebControlado();
        // Su constructor toma estos parametros por url
        $this->limit              = $this->listado_controlado->limit;
        $this->offset             = $this->listado_controlado->offset;
        $this->cantidad_registros = $this->listado_controlado->cantidad_registros;
        // Ejecutar el constructor del padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * Barra
     *
     * @param  string Encabezado opcional
     * @return mixed  Instancia de \Base2\BarraWeb
     */
    protected function barra($in_encabezado='') {
        // Si viene el parametro se usa, si no, el encabezado por defecto
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->encabezado();
        }
        // Crear la barra
        $barra             = new \Base2\BarraWeb();
        $barra->encabezado = $encabezado;
        $barra->icono      = $this->sesion->menu->icono_en('adm_bitacora');
        $barra->boton_descargar(ListadoCSV::RAIZ_CSV_ARCHIVO, $this->filtros_param);
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
        // Debe estar consultado, de lo contrario se consulta y si falla se muestra mensaje
        if (!$this->consultado) {
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                return $mensaje->html($in_encabezado);
            }
        }
        // Eliminar columnas de la estructura que sean filtros aplicados
        if ($this->usuario != '') {
            unset($this->estructura['usuario_nom_corto']);
        }
        if ($this->tipo != '') {
            unset($this->estructura['tipo']);
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

} // Clase ListadoWeb

?>
