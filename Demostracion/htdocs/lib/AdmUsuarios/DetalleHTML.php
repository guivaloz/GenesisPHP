<?php
/**
 * GenesisPHP - AdmUsuarios DetalleHTML
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

namespace AdmUsuarios;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

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
    static public $accion_modificar   = 'usuarioModificar';
    static public $accion_eliminar    = 'usuarioEliminar';
    static public $accion_recuperar   = 'usuarioRecuperar';
    static public $accion_desbloquear = 'usuarioDesbloquear';

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
        $detalle->dato('Nombre corto', $this->nom_corto);
        $detalle->dato('Nombre',       sprintf('<a href="usuarios.php?id=%d">%s</a>', $this->id, $this->nombre));
        $detalle->dato('Puesto',       $this->puesto);
        $detalle->dato('Tipo',         $this->tipo_descrito);
        $detalle->dato('e-mail', ($this->email != '') ? sprintf('<a href="mailto:%s">%s</a>', $this->email, $this->email) : '');
        // Seccion contraseña
        $detalle->seccion('Contraseña');
        $detalle->dato('Estado',                  $this->contrasena_descrito);
        $detalle->dato('No cifrada',              $this->contrasena_no_cifrada_descrito,   'amarillo');
        $detalle->dato('Bloqueada porque expiró', $this->bloqueada_porque_expiro_descrito, 'rojo');
        $detalle->dato('Bloqueada por fallas',    $this->bloqueada_porque_fallas_descrito, 'rojo');
        // Seccion sesion
        $detalle->seccion('Sesión');
        $detalle->dato('Estado', $this->sesiones_descrito);
        $detalle->dato('Bloqueada por sesiones', $this->bloqueada_porque_sesiones_descrito, 'rojo');
        $detalle->dato('Último ingreso',         $this->sesiones_ultima);
        $detalle->dato('Listados',               "Con {$this->listado_renglones} renglones.");
        // Seccion registro
        $detalle->seccion('Registro');
        $detalle->dato('Notas',   $this->notas);
        if ($this->sesion->puede_eliminar('usuarios')) {
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
            $barra->icono      = $this->sesion->menu->icono_en('usuarios');
            if (($this->estatus == 'A') && $this->sesion->puede_modificar('usuarios')) {
                $barra->boton_modificar(
                    sprintf('usuarios.php?id=%d&accion=%s', $this->id, self::$accion_modificar));
            }
            if (($this->estatus == 'A') && $this->sesion->puede_eliminar('usuarios')) {
                $barra->boton_eliminar_confirmacion(
                    sprintf('usuarios.php?id=%d&accion=%s', $this->id, self::$accion_eliminar),
                    "¿Está seguro de querer <strong>eliminar</strong> a el usuario {$this->nombre}?");
            }
            if (($this->estatus == 'B') && $this->sesion->puede_recuperar('usuarios')) {
                $barra->boton_recuperar_confirmacion(
                    sprintf('usuarios.php?id=%d&accion=%s', $this->id, self::$accion_recuperar),
                    "¿Está seguro de querer <strong>recuperar</strong> a el usuario {$this->nombre}?");
            }
            if ($this->esta_bloqueada && ($this->estatus == 'A') && $this->sesion->puede_modificar('usuarios')) {
                $barra->boton_confirmacion(
                    sprintf('usuarios.php?id=%d&accion=%s', $this->id, self::$accion_desbloquear),
                    'Desbloquear',
                    'primary',
                    'desbloquearRegistro',
                    "¿Está seguro de querer <strong>desbloquear</strong> a el usuario {$this->nombre}?");
            }
            // Pasar la barra al detalle html
            $detalle->barra = $barra;
        }
        // Entregar HTML
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

    /**
     * Desbloquear HTML
     *
     * @return string HTML con el detalle y el mensaje
     */
    public function desbloquear_html() {
        try {
            // Este metodo espera que la propiedad id este definida
            $msg = $this->desbloquear();
            // Mostrar el mensaje y el detalle
            $mensaje = new \Base\MensajeHTML($msg);
            return $mensaje->html().$this->html();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html($in_encabezado);
        }
    } // desbloquear_html

} // Clase DetalleHTML

?>
