<?php
/**
 * GenesisPHP - AdmRoles DetalleHTML
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
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $departamento;
    // public $departamento_nombre;
    // public $modulo;
    // public $modulo_nombre;
    // public $permiso_maximo;
    // public $permiso_maximo_descrito;
    // public $estatus;
    // public $estatus_descrito;
    // static public $permiso_maximo_descripciones;
    // static public $permiso_maximo_colores;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    static public $accion_modificar = 'rolModificar';
    static public $accion_eliminar  = 'rolEliminar';
    static public $accion_recuperar = 'rolRecuperar';
    const RAIZ_PHP_ARCHIVO          = 'admroles.php';

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
            $encabezado = "Rol de {$this->departamento_nombre} en {$this->modulo_nombre}";
        }
        // Crear la barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $encabezado;
        $barra->icono      = $this->sesion->menu->icono_en('adm_roles');
        // Definir botones
        if (($this->estatus != 'B') && $this->sesion->puede_modificar('roles')) {
            $barra->boton_modificar(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_modificar));
        }
        if (($this->estatus != 'B') && $this->sesion->puede_eliminar('roles')) {
            $barra->boton_eliminar_confirmacion(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_eliminar),
                "¿Está seguro de querer <strong>eliminar</strong> el rol de {$this->departamento_nombre} en {$this->modulo_nombre}?");
        }
        if (($this->estatus == 'B') && $this->sesion->puede_recuperar('roles')) {
            $barra->boton_recuperar_confirmacion(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_recuperar),
                "¿Está seguro de querer <strong>recuperar</strong> el rol de {$this->departamento_nombre} en {$this->modulo_nombre}?");
        }
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
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html($in_encabezado);
            }
        }
        // Detalle
        $detalle = new \Base\DetalleHTML();
        // Seccion rol
        $detalle->seccion('Rol');
        $detalle->dato('Departamento',   sprintf('<a href="%s?%s=%d">%s</a>', self::RAIZ_PHP_ARCHIVO, ListadoHTML::$param_departamento, $this->departamento, $this->departamento_nombre));
        $detalle->dato('Módulo',         sprintf('<a href="%s?%s=%d">%s</a>', self::RAIZ_PHP_ARCHIVO, ListadoHTML::$param_modulo, $this->modulo, $this->modulo_nombre));
        $detalle->dato('Permiso máximo', $this->permiso_maximo_descrito, parent::$permiso_maximo_colores[$this->permiso_maximo]);
        // Seccion registro
        if ($this->sesion->puede_eliminar('roles')) {
            $detalle->seccion('Registro');
            $detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
        }
        // Pasar la barra
        $detalle->barra = $this->barra();
        // Entregar
        return $detalle->html();
    } // html

    /**
     * Eliminar HTML
     *
     * @return string HTML con el detalle y el mensaje
     */
    public function eliminar_html() {
        try {
            $mensaje = new \Base\MensajeHTML($this->eliminar());
            return $mensaje->html().$this->html();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html($in_encabezado);
        }
    } // eliminar_html

    /**
     * Recuperar HTML
     *
     * @return string HTML con el detalle y el mensaje
     */
    public function recuperar_html() {
        try {
            $mensaje = new \Base\MensajeHTML($this->recuperar());
            return $mensaje->html().$this->html();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html($in_encabezado);
        }
    } // recuperar_html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return false;
    } // javascript

} // Clase DetalleHTML

?>
