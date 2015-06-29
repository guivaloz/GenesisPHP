<?php
/**
 * GenesisPHP - Usuarios EliminarHTML
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

namespace Usuarios;

/**
 * Clase EliminarHTML
 */
class EliminarHTML extends DetalleHTML {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $nom_corto;
    // public $nombre;
    // public $puesto;
    // public $tipo;
    // public $tipo_descrito;
    // public $email;
    // public $contrasena_fallas;
    // public $contrasena_expira;
    // public $contrasena_descrito;
    // public $sesiones_maximas;
    // public $sesiones_contador;
    // public $sesiones_ultima;
    // public $sesiones_descrito;
    // public $listado_renglones;
    // public $notas;
    // public $estatus;
    // public $estatus_descrito;
    // public $contrasena_no_cifrada;
    // public $esta_bloqueada;
    // public $bloqueada_porque_fallas;
    // public $bloqueada_porque_expiro;
    // public $bloqueada_porque_sesiones;
    // public $contrasena_no_cifrada_descrito;
    // public $bloqueada_porque_fallas_descrito;
    // public $bloqueada_porque_expiro_descrito;
    // public $bloqueada_porque_sesiones_descrito;
    // protected $contrasena;
    // static public $contrasena_colores;
    // static public $expira_en_colores;
    // static public $sesiones_contador_colores;
    // static public $tipo_descripciones;
    // static public $tipo_colores;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    // static public $dias_expira_contrasena;
    // protected $detalle;
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    // static public $accion_desbloquear;

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        try {
            $msg     = $this->eliminar();
            $mensaje = new \Base\MensajeHTML($msg);
            return $mensaje->html()."\n".parent::html();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html('Error');
        }
    } // html

} // Clase EliminarHTML

?>
