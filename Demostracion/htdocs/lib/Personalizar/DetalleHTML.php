<?php
/**
 * GenesisPHP - Personalizar DetalleHTML
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

namespace Personalizar;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $nom_corto;
    // public $nombre;
    // public $tipo;
    // public $tipo_descrito;
    // public $email;
    // public $listado_renglones;
    // public $contrasena_descrito;
    // public $contrasena_alerta = false;
    // public $sesiones_maximas;
    // public $sesiones_contador;
    // public $sesiones_descrito;
    // public $sesiones_alerta = false;
    // public $estatus;
    // public $estatus_descrito;
    // static public $dias_expira_contrasena_aviso;
    // protected $contrasena;
    // protected $contrasena_encriptada;

    /**
     * Situacion HTML
     *
     * @return string HTML
     */
    public function situacion_html() {
        // Debe estar consultado, de lo contrario se consulta y si falla se muestra mensaje
        if (!$this->consultado) {
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html($in_encabezado);
            }
        }
        // Elaborar y entregar mensaje
        if ($this->contrasena_alerta) {
            $mensaje       = new \Base\MensajeHTML($this->contrasena_descrito);
            $mensaje->tipo = 'aviso';
            return $mensaje->html('CAMBIE SU CONTRASEÑA');
        } else {
            $mensaje       = new \Base\MensajeHTML('Su cuenta está en perfecto estado.');
            $mensaje->info = 'info';
            return $mensaje->html('Su cuenta');
        }
    } // situacion_html

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
        // Seccion general
        $detalle->seccion('General');
        $detalle->dato('Nombre',       $this->nombre);
        $detalle->dato('Nombre corto', $this->nom_corto);
        $detalle->dato('Tipo',         $this->tipo_descrito);
        $detalle->dato('e-mail',       $this->email);
        $detalle->dato('Listados',     "Con {$this->listado_renglones} renglones.");
        // Seccion sesiones y contraseña
        $detalle->seccion('Sesiones y contraseña');
        if ($this->sesiones_alerta) {
            $detalle->dato('Sus sesiones', '<span class="alerta">'.str_replace('. ', '.<br />', $this->sesiones_descrito).'</span>');
        } else {
            $detalle->dato('Sus sesiones', str_replace('. ', '<br />', $this->sesiones_descrito));
        }
        if ($this->contrasena_alerta) {
            $detalle->dato('Su contraseña', '<span class="alerta">'.str_replace('. ', '.<br />', $this->contrasena_descrito).'</span>');
        } else {
            $detalle->dato('Su contraseña', str_replace('. ', '<br />', $this->contrasena_descrito));
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->nombre;
        }
        // Entregar el HTML del detalle
        return $detalle->html($encabezado, $this->sesion->menu->icono_en('personalizar'));
    } // html

} // Clase DetalleHTML

?>
