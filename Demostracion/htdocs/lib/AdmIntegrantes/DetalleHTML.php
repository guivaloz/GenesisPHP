<?php
/**
 * GenesisPHP - Usuarios DetalleHTML
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

namespace AdmIntegrantes;

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
    static public $accion_modificar = 'integranteModificar';
    static public $accion_eliminar  = 'integranteEliminar';
    static public $accion_recuperar = 'integranteRecuperar';

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
        // Seccion integrante
        $detalle->seccion('Integrante');
        $detalle->dato('Usuario',      sprintf('<a href="integrantes.php?%s=%d">%s (%s)</a>', Listado::$param_usuario, $this->usuario, $this->usuario_nombre, $this->usuario_nom_corto));
        $detalle->dato('Departamento', sprintf('<a href="integrantes.php?%s=%d">%s</a>', Listado::$param_departamento, $this->departamento, $this->departamento_nombre));
        $detalle->dato('Poder',        $this->poder_descrito, parent::$poder_colores[$this->poder]);
        // Seccion registro
        if ($this->sesion->puede_eliminar('integrantes')) {
            $detalle->seccion('Registro');
            $detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = "{$this->usuario_nombre} en {$this->departamento_nombre}";
        }
        // Si hay encabezado
        if ($encabezado != '') {
            // Crear la barra
            $barra             = new \Base\BarraHTML();
            $barra->encabezado = $encabezado;
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
