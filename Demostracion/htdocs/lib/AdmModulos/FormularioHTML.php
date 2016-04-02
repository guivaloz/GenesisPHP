<?php
/**
 * GenesisPHP - AdmModulos FormularioHTML
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

namespace AdmModulos;

/**
 * Clase FormularioHTML
 */
class FormularioHTML extends DetalleHTML {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $orden;
    // public $clave;
    // public $nombre;
    // public $pagina;
    // public $icono;
    // public $padre;
    // public $padre_nombre;
    // public $permiso_maximo;
    // public $permiso_maximo_descrito;
    // public $poder_minimo;
    // public $poder_minimo_descrito;
    // public $estatus;
    // public $estatus_descrito;
    // static public $permiso_maximo_descripciones;
    // static public $permiso_maximo_colores;
    // static public $poder_minimo_descripciones;
    // static public $poder_minimo_colores;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    // static public $accion_modificar;
    // static public $accion_eliminar;
    // static public $accion_recuperar;
    protected $es_nuevo;
    static public $form_name = 'modulo';

    /**
     * Formulario
     *
     * @param  string  Encabezado opcional
     * @return string  HTML del Formulario
     */
    protected function formulario($in_encabezado='') {
        // Opciones para escoger el padre de este modulo
        $modulos = new OpcionesSelect();
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
        // Sección modulo
        $f->texto_nombre('nombre',            'Nombre *',         $this->nombre, 48, 'Texto como aparecerá en el menú.');
        $f->texto_entero('orden',             'Orden *',          $this->orden,   6, 'Número entero, determina su posición.');
        $f->texto_nombre('clave',             'Clave *',          $this->clave,  48, 'Texto identificador, debe ser único.'); // en minúsculas y sin espacios; puede usar guiones
        $f->texto_nombre('pagina',            'Página *',         $this->pagina, 48, 'Nombre del archivo PHP con la página.'); // Use minúsculas y guiones, sin espacios.
        $f->texto_nombre('icono',             'Ícono *',          $this->icono,  48, 'Nombre del archivo PNG.'); // Use minúsculas y guiones, sin espacios.
        $f->select_con_nulo('padre',          'Padre',            $modulos->opciones_padre(),            $this->padre,          1, 'Deje en blanco para ser padre.'); // Será una rama de este menú.
        $f->select_con_nulo('permiso_maximo', 'Permiso máximo *', parent::$permiso_maximo_descripciones, $this->permiso_maximo, 1, 'Máximo permiso de este módulo.');
        $f->select_con_nulo('poder_minimo',   'Poder mínimo *',   parent::$poder_minimo_descripciones,   $this->poder_minimo,   1, 'Aparecerá a los que tengan igual o mayor a éste.');
        // Botones
        $f->boton_guardar();
        if (!$this->es_nuevo) {
            $f->boton_cancelar(sprintf('modulos.php?id=%d', $this->id));
        }
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } elseif ($this->es_nuevo) {
            $encabezado = "Nuevo módulo";
        } else {
            $encabezado = $this->nombre;
        }
        // Entregar
        return $f->html($encabezado, $this->sesion->menu->icono_en('modulos'));
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
        // Definir propiedades
        $this->nombre         = post_texto($_POST['nombre']);
        $this->orden          = $_POST['orden'];
        $this->clave          = post_texto($_POST['clave']);
        $this->pagina         = post_texto($_POST['pagina']);
        $this->icono          = post_texto($_POST['icono']);
        $this->padre          = post_select($_POST['padre']);
        $this->permiso_maximo = $_POST['permiso_maximo'];
        $this->poder_minimo   = $_POST['poder_minimo'];
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
            if (!$this->sesion->puede_modificar('modulos')) {
                $mensaje = new \Base\MensajeHTML('Aviso: No tiene permiso para modificar módulos.');
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
