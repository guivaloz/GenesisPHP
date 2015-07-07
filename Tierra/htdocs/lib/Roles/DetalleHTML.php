<?php
/**
 * GenesisPHP - Roles DetalleHTML
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

namespace Roles;

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
    protected $detalle;
    static public $accion_modificar = 'rolModificar';
    static public $accion_eliminar  = 'rolEliminar';
    static public $accion_recuperar = 'rolRecuperar';

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar DetalleHTML
        $this->detalle = new \Base\DetalleHTML();
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Debe estar consultado, de lo contrario se consulta y si falla se muestra mensaje
        if (!$this->consultado) {
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        }
        // Elaborar Barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = sprintf('%s en %s', $this->departamento_nombre, $this->modulo_nombre);
        $barra->icono      = $this->sesion->menu->icono_en('roles');
        if (($this->estatus != 'B') && $this->sesion->puede_modificar('roles')) {
            $barra->boton_modificar(sprintf('roles.php?id=%d&accion=%s', $this->id, self::$accion_modificar));
        }
        if (($this->estatus != 'B') && $this->sesion->puede_eliminar('roles')) {
            $barra->boton_eliminar_confirmacion(sprintf('roles.php?id=%d&accion=%s', $this->id, self::$accion_eliminar),
                "¿Está seguro de querer <strong>eliminar</strong> al rol {$barra->encabezado}?");
        }
        if (($this->estatus == 'B') && $this->sesion->puede_recuperar('roles')) {
            $barra->boton_recuperar_confirmacion(sprintf('roles.php?id=%d&accion=%s', $this->id, self::$accion_recuperar),
                "¿Está seguro de querer <strong>recuperar</strong> al rol {$barra->encabezado}?");
        }
        // Cargar Detalle
        $this->detalle->barra = $barra;
        $this->detalle->seccion('Rol');
        $this->detalle->dato('Departamento',   $this->departamento_nombre);
        $this->detalle->dato('Módulo',         $this->modulo_nombre);
        $this->detalle->dato('Permiso máximo', $this->permiso_maximo_descrito);
        $this->detalle->seccion('Registro');
        $this->detalle->dato('Estatus', $this->estatus_descrito);
        // Entregar
        return $this->detalle->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->detalle->javascript();
    } // javascript

} // Clase DetalleHTML

?>
