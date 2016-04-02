<?php
/**
 * GenesisPHP - AdmUsuarios FormularioHTML
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
 * Clase FormularioHTML
 */
class FormularioHTML extends DetalleHTML {

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
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    // static public $accion_desbloquear;

    /**
     * Formulario
     *
     * @param  string  Encabezado opcional
     * @return string  HTML del Formulario
     */
    protected function formulario($in_encabezado='') {
        // Formulario
        $f = new \Base\FormularioHTML(self::$form_name);
        $f->mensaje = '(*) Campos obligatorios.';
        // Campos ocultos
        $cadenero = new \Base\Cadenero($this->sesion);
        $f->oculto('cadenero', $cadenero->crear_clave(self::$form_name)); // CADENERO CREAR CLAVE PUEDE PROVOCAR UNA EXCEPCION
        if ($this->es_nuevo) {
            $f->oculto('accion', 'agregar');
        } else {
            $f->oculto('id', $this->id);
        }
        // Sección general
        $f->seccion('general', 'Datos Generales');
        $f->texto_nom_corto('nom_corto', 'Nombre corto *', $this->nom_corto, 16);
        $f->texto_nombre('nombre',       'Nombre *',       $this->nombre,    64);
        $f->texto_nombre('puesto',       'Puesto',       $this->puesto,    64);
        $f->select('tipo',               'Tipo *',         self::$tipo_descripciones, $this->tipo);
        $f->texto('email',               'e-mail *',       $this->email,     64);
        // Sección contraseña
        $f->seccion('password',    'Contraseña');
        $f->password('contrasena', 'Contraseña nueva');
        // Sección sesion
        $f->seccion('sesion', 'Sesión');
        $f->texto_entero('sesiones_maximas',  'Máximo de ingresos por día *', $this->sesiones_maximas,  8);
        $f->texto_entero('listado_renglones', 'Listados',                   $this->listado_renglones, 8);
        // Sección registro
        $f->seccion('registro', 'Registro');
        $f->area_texto('notas', 'Notas', $this->notas, 64, 4);
        // Botones
        $f->boton_guardar();
        if (!$this->es_nuevo) {
            $f->boton_cancelar(sprintf('usuarios.php?id=%d', $this->id));
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } elseif ($this->es_nuevo) {
            $encabezado = "Nuevo usuario";
        } else {
            $encabezado = $this->nombre;
        }
        // Entregar
        return $f->html($encabezado, $this->sesion->menu->icono_en('usuarios'));
    } // formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
        // Cadenero
        $cadenero = new \Base\Cadenero($this->sesion);
        $cadenero->validar_recepcion(self::$form_name, $_POST['cadenero']);
        // Si la accion es agregar el estatus es "en uso"
        if ($_POST['accion'] == 'agregar') {
            $this->estatus = 'A';
        } else {
            $this->id = $_POST['id'];
        }
        // Definir propiedades
        $this->nom_corto         = $_POST['nom_corto'];
        $this->nombre            = $_POST['nombre'];
        $this->puesto            = $_POST['puesto'];
        $this->tipo              = $_POST['tipo'];
        $this->email             = $_POST['email'];
        $this->contrasena        = $_POST['contrasena'];
        $this->sesiones_maximas  = $_POST['sesiones_maximas'];
        $this->listado_renglones = $_POST['listado_renglones'];
        $this->notas             = $_POST['notas'];
    } // recibir_formulario

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function html($in_encabezado='') {
        // En este arreglo juntaremos la salida
        $a = array();
        // Si se va a agregar un nuevo registro
        if ($this->id == 'agregar') {
            try {
                $this->nuevo();
                $this->es_nuevo = true;
            } catch (\Exception $e) {
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        } elseif ($_POST['formulario'] == self::$form_name) {
            // Viene el formulario
            try {
                $this->es_nuevo = ($_POST['accion'] == 'agregar');
                // Se modifica o se agrega
                if ($this->es_nuevo) {
                    $this->recibir_formulario();    // Recibir
                    $msg = $this->agregar();        // Agregar
                } else {
                    $this->consultar($_POST['id']); // Hay campos en el registro que no se muestran en el formulario
                    $this->recibir_formulario();    // Por eso consultamos antes de recibir
                    $msg = $this->modificar();      // Modificar
                }
                // Se muestra el detalle
                $a[] = parent::html();
                // Y el mensaje
                $mensaje = new \Base\MensajeHTML($msg);
                $a[]     = $mensaje->html('Acción exitosa');
                return implode("\n", $a);
            } catch (\Base\RegistroExceptionValidacion $e) {
                // Fallo la validacion, se muestra mensaje y formulario de nuevo
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                $a[]     = $mensaje->html('Validación');
            } catch (\Exception $e) {
                // Error fatal
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        } else {
            // Mostrar el formulario para modificar
            $this->es_nuevo = false;
            // Validar que tenga permiso para modificar
            if (!$this->sesion->puede_modificar('usuarios')) {
                $mensaje = new \Base\MensajeHTML('Aviso: No tiene permiso para modificar usuarios.');
                return $mensaje->html('Error');
            }
            // Consultamos
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        }
        // Mostrar formulario, como cadenero puede provocar una excepcion se encierra en try-catch
        try {
            $a[] = $this->formulario($in_encabezado);
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            $a[]     = $mensaje->html();
        }
        // Entregar
        return implode("\n", $a);
    } // html

} // Clase FormularioHTML

?>
