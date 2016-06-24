<?php
/**
 * GenesisPHP - AdmRoles FormularioWeb
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

namespace AdmRoles;

/**
 * Clase FormularioWeb
 */
class FormularioWeb extends DetalleWeb {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $departamento;
    // public $departamento_nombre;
    // public $modulo;
    // public $modulo_nombre;
    // public $permiso_maximo;
    // public $permiso_maximo_descrito;
    // public $estatus;
    // public $estatus_descrito;
    // static public $permiso_maximo_descripciones;
    // static public $permiso_maximo_colores;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    // protected $detalle;   // Instancia de \Base2\DetalleWeb
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    protected $es_nuevo;
    protected $formulario;   // Instancia de \Base2\FormularioWeb
    static public $form_name = 'adm_rol';

    /**
     * Elaborar formulario
     *
     * @param  string  Encabezado opcional
     * @return string  HTML del Formulario
     */
    protected function elaborar_formulario($in_encabezado='') {
        // Opciones para escoger al departamento y al modulo
        $departamentos = new \AdmDepartamentos\OpcionesSelect($this->sesion);
        $modulos       = new \AdmModulos\OpcionesSelect($this->sesion);
        // Formulario
        $this->formulario = new \Base2\FormularioWeb(self::$form_name);
        $this->formulario->mensaje = '(*) Campos obligatorios.';
        // Campos ocultos
        $cadenero = new \Base2\Cadenero($this->sesion);
        $this->formulario->oculto('cadenero', $cadenero->crear_clave(self::$form_name));
        if ($this->es_nuevo) {
            $this->formulario->oculto('accion', 'agregar');
        } else {
            $this->formulario->oculto('id', $this->id);
        }
        // Seccion rol
        $this->formulario->select_con_nulo('departamento',   'Departamento *',   $departamentos->opciones(),            $this->departamento,   1);
        $this->formulario->select_con_nulo('modulo',         'Módulo *',         $modulos->opciones(),                  $this->modulo,         1);
        $this->formulario->select_con_nulo('permiso_maximo', 'Permiso máximo *', parent::$permiso_maximo_descripciones, $this->permiso_maximo, 1, 'Ponga límite al permiso.');
        // Botones
        $this->formulario->boton_guardar();
        if (!$this->es_nuevo) {
            $this->formulario->boton_cancelar(sprintf('%s?id=%d', DetalleWeb::RAIZ_PHP_ARCHIVO, $this->id));
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } elseif ($this->es_nuevo) {
            $encabezado = "Nuevo rol";
        } else {
            $encabezado = $this->nombre;
        }
        // Entregar
        return $this->formulario->html($encabezado);
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
        // Cadenero
        $cadenero = new \Base2\Cadenero($this->sesion);
        $cadenero->validar_recepcion(self::$form_name, $_POST['cadenero']);
        // Si la accion es agregar el estatus es "en uso"
        if ($_POST['accion'] == 'agregar') {
            $this->estatus = 'A';
        } else {
            $this->id = $_POST['id'];
        }
        // Seccion rol
        $this->departamento   = \Base2\UtileriasParaSQL::post_select($_POST['departamento']);
        $this->modulo         = \Base2\UtileriasParaSQL::post_select($_POST['modulo']);
        $this->permiso_maximo = \Base2\UtileriasParaSQL::post_select($_POST['permiso_maximo']);
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
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
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
                $mensaje = new \Base2\MensajeWeb($msg);
                $a[]     = $mensaje->html('Acción exitosa');
                return implode("\n", $a);
            } catch (\Base2\RegistroExceptionValidacion $e) {
                // Fallo la validacion, se muestra mensaje y formulario de nuevo
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                $a[]     = $mensaje->html('Validación');
            } catch (\Exception $e) {
                // Error fatal
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                return $mensaje->html('Error');
            }
        } else {
            // Mostrar el formulario para modificar
            $this->es_nuevo = false;
            // Validar que tenga permiso para modificar
            if (!$this->sesion->puede_modificar('adm_roles')) {
                $mensaje = new \Base2\MensajeWeb('Aviso: No tiene permiso para modificar roles.');
                return $mensaje->html('Error');
            }
            // Consultamos
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                return $mensaje->html('Error');
            }
        }
        // Mostrar formulario
        try {
            $a[] = $this->elaborar_formulario($in_encabezado);
        } catch (\Exception $e) {
            $mensaje = new \Base2\MensajeWeb($e->getMessage());
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
        $a = array();
        if ($this->detalle instanceof \Base2\DetalleWeb) {
            $a[] = $this->detalle->javascript();
        }
        if ($this->formulario instanceof \Base2\FormularioWeb) {
            $a[] = $this->formulario->javascript();
        }
        return implode("\n", $a);
    } // javascript

} // Clase FormularioWeb

?>
