<?php
/**
 * GenesisPHP - Bitacora DetalleHTML
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

namespace Bitacora;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $usuario;
    // public $usuario_nombre;
    // public $fecha;
    // public $pagina;
    // public $pagina_id;
    // public $tipo;
    // public $tipo_descrito;
    // public $url;
    // public $notas;
    // static public $tipo_descripciones;
    // static public $tipo_colores;
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
        $this->detalle->encabezado = sprintf('%s %s', $this->fecha, $this->usuario_nombre);
        $this->detalle->icono      = $this->sesion->menu->icono_en('bitacora');
        $this->detalle->seccion('Bitácora');
        $this->detalle->dato('Usuario',   $this->usuario_nombre);
        $this->detalle->dato('Fecha',     $this->fecha);
        $this->detalle->dato('Página',    $this->pagina);
        $this->detalle->dato('Página ID', $this->pagina_id);
        $this->detalle->dato('Tipo',      $this->tipo_descrito);
        $this->detalle->dato('URL',       $this->url);
        $this->detalle->dato('Notas',     $this->notas);
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
