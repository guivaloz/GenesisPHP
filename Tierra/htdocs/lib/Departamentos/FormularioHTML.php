<?php
/**
 * GenesisPHP - Departamentos FormularioHTML
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

namespace Departamentos;

/**
 * Clase FormularioHTML
 */
class FormularioHTML extends DetalleHTML {

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
    // protected $detalle;
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    protected $formulario;                     // Instancia de FormularioHTML
    protected $es_nuevo;                       // Bandera, si es verdadero es para agregar, falso es para modificar
    static public $form_name = 'departamento'; // Name del formulario

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
        $this->formulario->texto_nombre('nombre', 'Nombre *', $this->nombre, 64);
        $this->formulario->texto_nom_corto('clave', 'Clave *', $this->clave, 4);
        $this->formulario->seccion('seccion_registro', 'Registro');
        $this->formulario->area_texto('notas', 'Notas', $this->notas, 64, 5);
        $this->formulario->boton_guardar();
        if ($this->es_nuevo) {
            $this->formulario->boton_cancelar(sprintf('departamentos.php?id=%d', $this->id));
            $this->formulario->oculto('accion', 'agregar');
        } else {
            $this->formulario->oculto('id', $this->id);
        }
        // Agregar cadenero
    //~ $cadenero = new \Base\Cadenero($this->sesion);
    //~ $this->formulario->oculto('cadenero', $cadenero->crear_clave(self::$form_name));
        // Elaborar encabezado
        if ($this->es_nuevo) {
            $encabezado = "Nuevo departamento";
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
        $this->nombre = \Base\FormularioHTML::post_texto($_POST['nombre']);
        $this->clave  = \Base\FormularioHTML::post_texto($_POST['clave']);
        $this->notas  = \Base\FormularioHTML::post_texto($_POST['notas']);
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
            if (!$this->sesion->puede_modificar('departamentos')) {
                $mensaje = new \Base\MensajeHTML('Aviso: No tiene permiso para modificar departamentos.');
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
            $a[]     = $mensaje->html();
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
