<?php
/**
 * GenesisPHP - Usuarios FormularioHTML
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
    // protected $detalle;
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    // static public $accion_desbloquear;
    protected $formulario;                // Instancia de FormularioHTML
    protected $es_nuevo;                  // Bandera, si es verdadero es para agregar, falso es para modificar
    static public $form_name = 'usuario'; // Name del formulario

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar FormularioHTML
        $this->formulario = new \Base\FormularioHTML(self::$form_name);
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * Elaborar formulario
     *
     * @return string HTML del Formulario
     */
    protected function elaborar_formulario() {
        // Elaborar formulario
        $this->formulario->mensaje = '(*) Campos obligatorios.';
        $this->formulario->seccion('general', 'Datos Generales');
        $this->formulario->texto_nom_corto('nom_corto',      'Nombre corto *', $this->nom_corto, 16);
        $this->formulario->texto_nombre('nombre',            'Nombre *',       $this->nombre,    64);
        $this->formulario->texto_nombre('puesto',            'Puesto',         $this->puesto,    64);
        $this->formulario->select('tipo',                    'Tipo *',         self::$tipo_descripciones, $this->tipo);
        $this->formulario->texto('email',                    'e-mail *',       $this->email,     64);
        $this->formulario->seccion('password',               'Contraseña');
        $this->formulario->password('contrasena',            'Contraseña nueva');
        $this->formulario->seccion('sesion', 'Sesión');
        $this->formulario->texto_entero('sesiones_maximas',  'Máximo de ingresos por día *', $this->sesiones_maximas,  8);
        $this->formulario->texto_entero('listado_renglones', 'Listados',                     $this->listado_renglones, 8);
        $this->formulario->seccion('registro', 'Registro');
        $this->formulario->area_texto('notas',               'Notas', $this->notas, 64, 4);
        $this->formulario->boton_guardar();
        if ($this->es_nuevo) {
            $this->formulario->oculto('accion', 'agregar');
            $this->formulario->boton_cancelar(sprintf('usuarios.php?id=%d', $this->id));
        } else {
            $this->formulario->oculto('id', $this->id);
        }
        // Agregar cadenero
    //~ $cadenero = new \Base\Cadenero($this->sesion);
    //~ $this->formulario->oculto('cadenero', $cadenero->crear_clave(self::$form_name));
        // Elaborar encabezado
        if ($this->es_nuevo) {
            $encabezado = "Nuevo usuario";
        } else {
            $encabezado = $this->nombre;
        }
        // Entregar
        return $this->formulario->html();
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
        // Recibir y validar cadenero
    //~ $cadenero = new \Base\Cadenero($this->sesion);
    //~ $cadenero->validar_recepcion(self::$form_name, $_POST['cadenero']);
        // Si la acción es agregar, el estatus es 'A', de lo contrario tomar el ID
        if ($_POST['accion'] == 'agregar') {
            $this->estatus = 'A';
        } else {
            $this->id = $_POST['id'];
        }
        // Recibir valores
        $this->nom_corto         = \Base\FormularioHTML::post_texto($_POST['nom_corto']);
        $this->nombre            = \Base\FormularioHTML::post_texto($_POST['nombre']);
        $this->puesto            = \Base\FormularioHTML::post_texto($_POST['puesto']);
        $this->tipo              = \Base\FormularioHTML::post_select($_POST['tipo']);
        $this->email             = \Base\FormularioHTML::post_texto($_POST['email']);
        $this->contrasena        = \Base\FormularioHTML::post_texto($_POST['contrasena']);
        $this->sesiones_maximas  = $_POST['sesiones_maximas'];
        $this->listado_renglones = $_POST['listado_renglones'];
        $this->notas             = \Base\FormularioHTML::post_texto($_POST['notas']);
    } // recibir_formulario

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // En este arreglo juntaremos la salida
        $a = array();
        // Si va a agregar uno nuevo
        if ($this->id == 'agregar') {
            try {
                $this->nuevo();
                $this->es_nuevo = true;
            } catch (\Exception $e) {
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        // Si viene el formulario
        } elseif ($_POST['formulario'] == self::$form_name) {
            $this->es_nuevo = ($_POST['accion'] == 'agregar');
            try {
                // Si es nuevo, se recibe y agrega
                if ($this->es_nuevo) {
                    $this->recibir_formulario();
                    $msg = $this->agregar();
                } else {
                    $this->consultar($_POST['id']);
                    $this->recibir_formulario();
                    $msg = $this->modificar();
                }
                // Mostrar el detalle y el mensaje
                $a[]     = parent::html();
                $mensaje = new \Base\MensajeHTML($msg);
                $a[]     = $mensaje->html('Éxito');
                // Entregar
                return implode("\n", $a);
            } catch (\Base\RegistroExceptionValidacion $e) {
                // Falló la validación, mostrar mensaje y el formulario de nuevo
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                $a[]     = $mensaje->html('Validación');
            } catch (\Exception $e) {
                // Error fatal
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        } else {
            // Va a modificar
            $this->es_nuevo = false;
            if (!$this->sesion->puede_modificar('usuarios')) {
                $mensaje = new \Base\MensajeHTML('Aviso: No tiene permiso para modificar usuarios.');
                return $mensaje->html('Error');
            }
            // Consultar
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        }
        // Mostrar el formulario, Cadenero puede causar una excepción cuando se intenta enviar el mismo formulario otra vez
        try {
            $a[] = $this->elaborar_formulario();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            $a[]     = $mensaje->html('Error');
        }
        // Entregar
        return implode("\n", $a);
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return implode("\n", array(
            $this->detalle->javascript(),
            $this->formulario->javascript()));
    } // javascript

} // Clase FormularioHTML

?>
