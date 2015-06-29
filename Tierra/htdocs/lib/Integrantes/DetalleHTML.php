<?php
/**
 * GenesisPHP - Integrantes DetalleHTML
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

namespace Integrantes;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $usuario;
    // public $usuario_nombre;
    // public $usuario_nom_corto;
    // public $departamento;
    // public $departamento_nombre;
    // public $poder;
    // public $poder_descrito;
    // public $estatus;
    // public $estatus_descrito;
    // static public $poder_descripciones;
    // static public $poder_colores;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    protected $detalle;
    static public $accion_modificar = 'integranteModificar';
    static public $accion_eliminar  = 'integranteEliminar';
    static public $accion_recuperar = 'integranteRecuperar';

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
        // Elaborar Barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->nombre;
        $barra->icono      = $this->sesion->menu->icono_en('integrantes');
        if (($this->estatus != 'B') && $this->sesion->puede_modificar('integrantes')) {
            $barra->boton_modificar(sprintf('integrantes.php?id=%d&accion=%s', $this->id, self::$accion_modificar));
        }
        if (($this->estatus != 'B') && $this->sesion->puede_eliminar('integrantes')) {
            $barra->boton_eliminar_confirmacion(sprintf('integrantes.php?id=%d&accion=%s', $this->id, self::$accion_eliminar),
                "¿Está seguro de querer <strong>eliminar</strong> a el integrante {$this->usuario_nombre} del departamento {$this->departamento_nombre}?");
        }
        if (($this->estatus == 'B') && $this->sesion->puede_recuperar('integrantes')) {
            $barra->boton_recuperar_confirmacion(sprintf('integrantes.php?id=%d&accion=%s', $this->id, self::$accion_recuperar),
                "¿Está seguro de querer <strong>recuperar</strong> a el integrante {$this->usuario_nombre} del departamento {$this->departamento_nombre}?");
        }
        // Cargar Detalle
        $this->detalle->barra = $barra;
        $this->detalle->seccion('Integrante');
        $this->detalle->dato('Usuario',      sprintf('<a href="integrantes.php?%s=%d">%s (%s)</a>', Listado::$param_usuario, $this->usuario, $this->usuario_nombre, $this->usuario_nom_corto));
        $this->detalle->dato('Departamento', sprintf('<a href="integrantes.php?%s=%d">%s</a>', Listado::$param_departamento, $this->departamento, $this->departamento_nombre));
        $this->detalle->dato('Poder',        $this->poder_descrito, parent::$poder_colores[$this->poder]);
        if ($this->sesion->puede_eliminar('integrantes')) {
            $this->detalle->seccion('Registro');
            $this->detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
        }
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
