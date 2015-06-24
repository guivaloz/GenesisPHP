<?php
/**
 * GenesisPHP - Usuarios DetalleHTML
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
    protected $detalle;
    static public $accion_modificar   = 'usuarioModificar';
    static public $accion_eliminar    = 'usuarioEliminar';
    static public $accion_recuperar   = 'usuarioRecuperar';
    static public $accion_desbloquear = 'usuarioDesbloquear';

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
        // Detalle
        $this->detalle->seccion('General');
        $this->detalle->dato('Nombre corto',            $this->nom_corto);
        $this->detalle->dato('Nombre',                  sprintf('<a href="usuarios.php?id=%d">%s</a>', $this->id, $this->nombre));
        $this->detalle->dato('Puesto',                  $this->puesto);
        $this->detalle->dato('Tipo',                    $this->tipo_descrito);
        $this->detalle->dato('e-mail',                  ($this->email != '') ? sprintf('<a href="mailto:%s">%s</a>', $this->email, $this->email) : '');
        $this->detalle->seccion('Contraseña');
        $this->detalle->dato('Estado',                  $this->contrasena_descrito);
        $this->detalle->dato('No cifrada',              $this->contrasena_no_cifrada_descrito,   'amarillo');
        $this->detalle->dato('Bloqueada porque expiró', $this->bloqueada_porque_expiro_descrito, 'rojo');
        $this->detalle->dato('Bloqueada por fallas',    $this->bloqueada_porque_fallas_descrito, 'rojo');
        $this->detalle->seccion('Sesión');
        $this->detalle->dato('Estado',                  $this->sesiones_descrito);
        $this->detalle->dato('Bloqueada por sesiones',  $this->bloqueada_porque_sesiones_descrito, 'rojo');
        $this->detalle->dato('Último ingreso',          $this->sesiones_ultima);
        $this->detalle->dato('Listados',                "Con {$this->listado_renglones} renglones.");
        $this->detalle->seccion('Registro');
        $this->detalle->dato('Notas',                   $this->notas);
        if ($this->sesion->puede_eliminar('usuarios')) {
            $this->detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
        }
        // Encabezado/Barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->nombre;
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
