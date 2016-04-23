<?php
/**
 * GenesisPHP - AdmIntegrantes FormularioHTML
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
 * Clase FormularioHTML
 */
class FormularioHTML extends DetalleHTML {

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
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    protected $es_nuevo;
    static public $form_name = 'admintegrante';

    /**
     * Elaborar formulario
     *
     * @param  string  Encabezado opcional
     * @return string  HTML del Formulario
     */
    protected function elaborar_formulario($in_encabezado='') {
        // Opciones para escoger al usuario y al departamento
        $usuarios      = new \AdmUsuarios\OpcionesSelect($this->sesion);
        $departamentos = new \AdmDepartamentos\OpcionesSelect($this->sesion);
        // Formulario
        $f = new \Base\FormularioHTML(self::$form_name);
        $f->mensaje = '(*) Campos obligatorios.';
        // Campos ocultos
        $cadenero = new \Base\Cadenero($this->sesion);
        $f->oculto('cadenero', $cadenero->crear_clave(self::$form_name));
        if ($this->es_nuevo) {
            $f->oculto('accion', 'agregar');
        } else {
            $f->oculto('id', $this->id);
        }
        // Seccion integrante
        $f->select_con_nulo('usuario',      'Usuario *',      $usuarios->opciones(),        $this->usuario);
        $f->select_con_nulo('departamento', 'Departamento *', $departamentos->opciones(),   $this->departamento);
        $f->select_con_nulo('poder',        'Poder *',        parent::$poder_descripciones, $this->poder, 1, 'Un Director puede administrar su Departamento. El Webmaster puede administrar todo.');
        // Botones
        $f->boton_guardar();
        if (!$this->es_nuevo) {
            $f->boton_cancelar(sprintf('%s?id=%d', DetalleHTML::RAIZ_PHP_ARCHIVO, $this->id));
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } elseif ($this->es_nuevo) {
            $encabezado = "Nuevo integrante";
        } else {
            $encabezado = $this->nombre;
        }
        // Entregar
        return $f->html($encabezado, $this->sesion->menu->icono_en('adm_integrantes'));
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
        // Cadenero
        $cadenero = new \Base\Cadenero($this->sesion);
        $cadenero->validar_recepcion(self::$form_name, $_POST['cadenero']);
        // Si la accion es agregar el estatus es "en uso" (a)
        if ($_POST['accion'] == 'agregar') {
            $this->estatus = 'A';
        } else {
            $this->id = $_POST['id'];
        }
        // Seccion integrante
        $this->usuario      = $this->post_select($_POST['usuario']);
        $this->departamento = $this->post_select($_POST['departamento']);
        $this->poder        = $this->post_select($_POST['poder']);
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
            if (!$this->sesion->puede_modificar('adm_integrantes')) {
                $mensaje = new \Base\MensajeHTML('Aviso: No tiene permiso para modificar integrantes.');
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
            $a[] = $this->elaborar_formulario($in_encabezado);
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
        return false;
    } // javascript

} // Clase FormularioHTML

?>
