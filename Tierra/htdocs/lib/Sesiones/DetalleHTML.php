<?php
/**
 * GenesisPHP - Sesiones DetalleHTML
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

namespace Sesiones;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $usuario;
    // public $nombre;
    // public $nom_corto;
    // public $tipo;
    // public $ingreso;
    // public $listado_renglones;
    protected $detalle;

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
        // Cargar Detalle
        $this->detalle->encabezado = $this->nombre;
        $this->detalle->icono      = $this->sesion->menu->icono_en('sesiones');
        $this->detalle->seccion('Sesión');
        $this->detalle->dato('Nombre',                $this->nombre);
        $this->detalle->dato('Nombre corto',          $this->nom_corto);
        $this->detalle->dato('Tipo',                  $this->tipo);
        $this->detalle->dato('Ingreso',               $this->ingreso);
        $this->detalle->dato('Renglones en listados', $this->listado_renglones);
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
