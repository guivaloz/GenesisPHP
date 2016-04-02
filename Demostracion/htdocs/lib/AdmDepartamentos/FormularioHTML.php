<?php
/**
 * GenesisPHP - AdmDepartamentos FormularioHTML
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

namespace AdmDepartamentos;

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
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    protected $es_nuevo;
    static public $form_name = 'departamento';

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
        // Seccion departamento
        $f->texto_nombre('nombre',   'Nombre *', $this->nombre, 64);
        $f->texto_nom_corto('clave', 'Clave *',  $this->clave,   4);
        // Seccion registro
        $f->seccion('seccion_registro', 'Registro');
        $f->area_texto('notas', 'Notas', $this->notas, 64, 5);
        // Botones
        $f->boton_guardar();
        if (!$this->es_nuevo) {
            $f->boton_cancelar(sprintf('departamentos.php?id=%d', $this->id));
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } elseif ($this->es_nuevo) {
            $encabezado = "Nuevo departamento";
        } else {
            $encabezado = $this->nombre;
        }
        // Entregar
        return $f->html($encabezado, $this->sesion->menu->icono_en('departamentos'));
    } // formulario

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
        // Seccion departamento
        $this->nombre = post_texto_mayusculas($_POST['nombre']);
        $this->clave  = post_texto_mayusculas($_POST['clave']);
        // Seccion registro
        $this->notas = post_texto($_POST['notas']);
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
            if (!$this->sesion->puede_modificar('departamentos')) {
                $mensaje = new \Base\MensajeHTML('Aviso: No tiene permiso para modificar departamentos.');
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
