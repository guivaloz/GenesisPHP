<?php
/**
 * GenesisPHP - AdmUsuarios DetalleWeb
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
 * Clase DetalleWeb
 */
class DetalleWeb extends Registro {

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
    protected $detalle; // Instancia de \Base2\DetalleWeb
    static public $accion_modificar   = 'usuarioModificar';
    static public $accion_eliminar    = 'usuarioEliminar';
    static public $accion_recuperar   = 'usuarioRecuperar';
    static public $accion_desbloquear = 'usuarioDesbloquear';
    const RAIZ_PHP_ARCHIVO            = 'admusuarios.php';

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
        // Crear la barra
        $barra             = new \Base2\BarraWeb();
        $barra->encabezado = $encabezado;
        $barra->icono      = $this->sesion->menu->icono_en('adm_usuarios');
        // Definir botones
        if (($this->estatus == 'A') && $this->sesion->puede_modificar('adm_usuarios')) {
            $barra->boton_modificar(
                sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_modificar));
        }
        if (($this->estatus == 'A') && $this->sesion->puede_eliminar('adm_usuarios')) {
            $barra->boton_eliminar_confirmacion(
                sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_eliminar),
                "¿Está seguro de querer <strong>eliminar</strong> a el usuario {$this->nombre}?");
        }
        if (($this->estatus == 'B') && $this->sesion->puede_recuperar('adm_usuarios')) {
            $barra->boton_recuperar_confirmacion(
                sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_recuperar),
                "¿Está seguro de querer <strong>recuperar</strong> a el usuario {$this->nombre}?");
        }
        if ($this->esta_bloqueada && ($this->estatus == 'A') && $this->sesion->puede_modificar('adm_usuarios')) {
            $barra->boton_confirmacion(
                sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_desbloquear),
                'Desbloquear',
                'primary',
                'desbloquearRegistro',
                "¿Está seguro de querer <strong>desbloquear</strong> a el usuario {$this->nombre}?");
        }
        // Entregar
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
        // Iniciar detalle
        $this->detalle = new \Base2\DetalleWeb();
        // Seccion general
        $this->detalle->seccion('General');
        $this->detalle->dato('Nombre corto', $this->nom_corto);
        $this->detalle->dato('Nombre',       sprintf('<a href="%s?id=%d">%s</a>', self::RAIZ_PHP_ARCHIVO, $this->id, $this->nombre));
        $this->detalle->dato('Puesto',       $this->puesto);
        $this->detalle->dato('Tipo',         $this->tipo_descrito);
        $this->detalle->dato('e-mail', ($this->email != '') ? sprintf('<a href="mailto:%s">%s</a>', $this->email, $this->email) : '');
        // Seccion contraseña
        $this->detalle->seccion('Contraseña');
        $this->detalle->dato('Estado',                  $this->contrasena_descrito);
        $this->detalle->dato('No cifrada',              $this->contrasena_no_cifrada_descrito,   'amarillo');
        $this->detalle->dato('Bloqueada porque expiró', $this->bloqueada_porque_expiro_descrito, 'rojo');
        $this->detalle->dato('Bloqueada por fallas',    $this->bloqueada_porque_fallas_descrito, 'rojo');
        // Seccion sesion
        $this->detalle->seccion('Sesión');
        $this->detalle->dato('Estado', $this->sesiones_descrito);
        $this->detalle->dato('Bloqueada por sesiones', $this->bloqueada_porque_sesiones_descrito, 'rojo');
        $this->detalle->dato('Último ingreso',         $this->sesiones_ultima);
        $this->detalle->dato('Listados',               "Con {$this->listado_renglones} renglones.");
        // Seccion registro
        $this->detalle->seccion('Registro');
        $this->detalle->dato('Notas',   $this->notas);
        if ($this->sesion->puede_eliminar('adm_usuarios')) {
            $this->detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
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
            return $this->detalle->javascript();
        }
    } // javascript

} // Clase DetalleWeb

?>
