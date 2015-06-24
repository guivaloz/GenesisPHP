<?php
/**
 * GenesisPHP - Módulos DetalleHTML
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
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $orden;
    // public $clave;
    // public $nombre;
    // public $pagina;
    // public $icono;
    // public $padre;
    // public $padre_nombre;
    // public $permiso_maximo;
    // public $permiso_maximo_descrito;
    // public $poder_minimo;
    // public $poder_minimo_descrito;
    // public $estatus;
    // public $estatus_descrito;
    // static public $permiso_maximo_descripciones;
    // static public $permiso_maximo_colores;
    // static public $poder_minimo_descripciones;
    // static public $poder_minimo_colores;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    protected $detalle;
    static public $accion_modificar = 'moduloModificar';
    static public $accion_eliminar  = 'moduloEliminar';
    static public $accion_recuperar = 'moduloRecuperar';

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
                return $mensaje->html($in_encabezado);
            }
        }
        // Detalle
        $this->detalle->seccion('Módulo');
        $this->detalle->dato('Ícono',          sprintf('<img src="imagenes/32x32/%s" />', $this->icono));
        $this->detalle->dato('Nombre',         $this->nombre);
        $this->detalle->dato('Orden',          $this->orden);
        $this->detalle->dato('Clave',          $this->clave);
        $this->detalle->dato('Página',         sprintf('<a href="%s">%s</a>', $this->pagina, $this->pagina));
        $this->detalle->dato('Padre',          $this->padre_nombre);
        $this->detalle->dato('Permiso máximo', $this->permiso_maximo_descrito, parent::$permiso_maximo_colores[$this->permiso_maximo]);
        $this->detalle->dato('Poder mínimo',   $this->poder_minimo_descrito, parent::$poder_minimo_colores[$this->poder_minimo]);
        if ($this->sesion->puede_eliminar('modulos')) {
            $this->detalle->seccion('Registro');
            $this->detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
        }
        // Encabezado/Barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $encabezado;
        $barra->icono      = $this->sesion->menu->icono_en('modulos');
        if (($this->estatus != 'B') && $this->sesion->puede_modificar('modulos')) {
            $barra->boton_modificar(sprintf('modulos.php?id=%d&accion=%s', $this->id, self::$accion_modificar));
        }
        if (($this->estatus != 'B') && $this->sesion->puede_eliminar('modulos')) {
            $barra->boton_eliminar_confirmacion(sprintf('modulos.php?id=%d&accion=%s', $this->id, self::$accion_eliminar),
                "¿Está seguro de querer <strong>eliminar</strong> al módulo {$this->nombre}?");
        }
        if (($this->estatus == 'B') && $this->sesion->puede_recuperar('departamentos')) {
            $barra->boton_recuperar_confirmacion(sprintf('modulos.php?id=%d&accion=%s', $this->id, self::$accion_recuperar),
                "¿Está seguro de querer <strong>recuperar</strong> al módulo {$this->nombre}?");
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
