<?php
/**
 * GenesisPHP - Personalizar DetalleWeb
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
 * Clase DetalleWeb
 */
class DetalleWeb extends Registro {

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
    protected $detalle; // Instancia de \Base2\DetalleWeb

    /**
     * Barra
     *
     * @param  string Encabezado opcional
     * @return mixed  Instancia de \Base2\BarraWeb
     */
    protected function barra($in_encabezado='') {
        // Si viene el parametro se usa, si no, el encabezado por defecto
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->encabezado();
        }
        // Entregar
        $barra             = new \Base2\BarraWeb();
        $barra->encabezado = $encabezado;
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
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                return $mensaje->html($in_encabezado);
            }
        }
        // Seccion general
        $this->detalle->seccion('General');
        $this->detalle->dato('Nombre',       $this->nombre);
        $this->detalle->dato('Nombre corto', $this->nom_corto);
        $this->detalle->dato('Tipo',         $this->tipo_descrito);
        $this->detalle->dato('e-mail',       $this->email);
        $this->detalle->dato('Listados',     "Con {$this->listado_renglones} renglones.");
        // Seccion sesiones y contraseña
        $this->detalle->seccion('Sesiones y contraseña');
        if ($this->sesiones_alerta) {
            $this->detalle->dato('Sus sesiones', '<span class="alerta">'.str_replace('. ', '.<br />', $this->sesiones_descrito).'</span>');
        } else {
            $this->detalle->dato('Sus sesiones', str_replace('. ', '<br />', $this->sesiones_descrito));
        }
        if ($this->contrasena_alerta) {
            $this->detalle->dato('Su contraseña', '<span class="alerta">'.str_replace('. ', '.<br />', $this->contrasena_descrito).'</span>');
        } else {
            $this->detalle->dato('Su contraseña', str_replace('. ', '<br />', $this->contrasena_descrito));
        }
        // Pasar la barra
        $this->detalle->barra = $this->barra($in_encabezado);
        // Entregar
        return $this->detalle->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        if ($this->detalle instanceof \Base2\DetalleWeb) {
            return $this->formulario->javascript();
        }
    } // javascript

} // Clase DetalleWeb

?>
