<?php
/**
 * GenesisPHP - AdmUsuarios ListadoHTML
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

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el url
        $this->nom_corto = $_GET[parent::$param_nom_corto];
        $this->nombre    = $_GET[parent::$param_nombre];
        $this->tipo      = $_GET[parent::$param_tipo];
        $this->estatus   = $_GET[parent::$param_estatus];
        // Estructura
        $this->estructura = array(
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
        if ($this->tipo != '') {
            unset($this->estructura['tipo']);
        }
        if ($this->estatus != '') {
            unset($this->estructura['estatus']);
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
        $barra->icono      = $this->sesion->menu->icono_en('usuarios');
        $barra->boton_descargar('usuarios.csv', $this->filtros_param);
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
