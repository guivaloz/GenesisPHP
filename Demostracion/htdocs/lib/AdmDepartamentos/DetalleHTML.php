<?php
/**
 * GenesisPHP - AdmDepartamentos DetalleHTML
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

namespace AdmDepartamentos;

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
    static public $accion_modificar = 'departamentoModificar';
    static public $accion_eliminar  = 'departamentoEliminar';
    static public $accion_recuperar = 'departamentoRecuperar';

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
        // Seccion departamento
        $detalle->seccion('Departamento');
        $detalle->dato('Nombre', $this->nombre);
        $detalle->dato('Clave',  $this->clave);
        // Seccion registro
        $detalle->seccion('Registro');
        $detalle->dato('Notas',  $this->notas);
        if ($this->sesion->puede_eliminar('departamentos')) {
            $detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->nombre;
        }
        // Si hay encabezado
        if ($encabezado != '') {
            // Crear la barra
            $barra             = new \Base\BarraHTML();
            $barra->encabezado = $encabezado;
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
            // Pasar la barra al detalle html
            $detalle->barra = $barra;
        }
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
            // Este metodo espera que la propiedad id este definida
            $msg = $this->eliminar();
            // Mostrar el mensaje y el detalle
            $mensaje = new \Base\MensajeHTML($msg);
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
            // Este metodo espera que la propiedad id este definida
            $msg = $this->recuperar();
            // Mostrar el mensaje y el detalle
            $mensaje = new \Base\MensajeHTML($msg);
            return $mensaje->html().$this->html();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html($in_encabezado);
        }
    } // recuperar_html

} // Clase DetalleHTML

?>
