<?php
/**
 * GenesisPHP - Departamentos DetalleHTML
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

namespace Departamentos;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $nombre;
    // public $clave;
    // public $notas;
    // public $estatus;
    // public $estatus_descrito;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    protected $detalle;
    static public $accion_modificar = 'departamentoModificar';
    static public $accion_eliminar  = 'departamentoEliminar';
    static public $accion_recuperar = 'departamentoRecuperar';

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
     * @return string HTML
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
        // Detalle
        $this->detalle->seccion('Departamento');
        $this->detalle->dato('Nombre', $this->nombre);
        $this->detalle->dato('Clave',  $this->clave);
        $this->detalle->seccion('Registro');
        $this->detalle->dato('Notas',  $this->notas);
        if ($this->sesion->puede_eliminar('departamentos')) {
            $this->detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
        }
        // Encabezado/Barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->nombre;
        $barra->icono      = $this->sesion->menu->icono_en('departamentos');
        if (($this->estatus != 'B') && $this->sesion->puede_modificar('departamentos')) {
            $barra->boton_modificar(sprintf('departamentos.php?id=%d&accion=%s', $this->id, self::$accion_modificar));
        }
        if (($this->estatus != 'B') && $this->sesion->puede_eliminar('departamentos')) {
            $barra->boton_eliminar_confirmacion(sprintf('departamentos.php?id=%d&accion=%s', $this->id, self::$accion_eliminar),
                "¿Está seguro de querer <strong>eliminar</strong> a el departamento {$this->nombre}?");
        }
        if (($this->estatus == 'B') && $this->sesion->puede_recuperar('departamentos')) {
            $barra->boton_recuperar_confirmacion(sprintf('departamentos.php?id=%d&accion=%s', $this->id, self::$accion_recuperar),
                "¿Está seguro de querer <strong>recuperar</strong> a el departamento {$this->nombre}?");
        }
        $this->detalle->barra = $barra;
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
