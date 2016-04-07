<?php
/**
 * GenesisPHP - Personalizar Contraseña FormularioHTML
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

namespace Personalizar;

/**
 * Clase ContrasenaFormularioHTML
 */
class ContrasenaFormularioHTML extends DetalleHTML {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $nom_corto;
    // public $nombre;
    // public $tipo;
    // public $tipo_descrito;
    // public $email;
    // public $listado_renglones;
    // public $contrasena_descrito;
    // public $contrasena_alerta = false;
    // public $sesiones_maximas;
    // public $sesiones_contador;
    // public $sesiones_descrito;
    // public $sesiones_alerta = false;
    // public $estatus;
    // public $estatus_descrito;
    // static public $dias_expira_contrasena_aviso;
    // protected $contrasena;
    // protected $contrasena_encriptada;
    protected $formulario_contrasena_actual;
    protected $formulario_contrasena_nueva;
    protected $formulario_contrasena_confirmar;
    static public $form_name = 'personalizar_contrasena';

    /**
     * Elaborar formulario
     *
     * @param  string  Encabezado opcional
     * @return string  HTML del Formulario de Búsqueda Avanzada
     */
    protected function elaborar_formulario($in_encabezado='') {
        // Formulario
        $f = new \Base\FormularioHTML(self::$form_name);
        $f->mensaje = '(*) Campos obligatorios.';
        // Campos ocultos
        $cadenero = new \Base\Cadenero($this->sesion);
        $f->oculto('cadenero', $cadenero->crear_clave(self::$form_name));
        // Seccion principal
        $f->password('actual',    'Contraseña actual');
        $f->password('nueva',     'Contraseña nueva');
        $f->password('confirmar', 'Confirme la contraseña nueva');
        // Botones
        $f->boton_guardar();
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = 'Cambiar contraseña';
        }
        // Entregar
        return $f->html($encabezado, $this->sesion->menu->icono_en('personalizar'));
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
        // Cadenero
        $cadenero = new \Base\Cadenero($this->sesion);
        $cadenero->validar_recepcion(self::$form_name, $_POST['cadenero']);
        // Recibir los valores del formulario
        $this->formulario_contrasena_actual    = $this->post_texto($_POST['actual']);
        $this->formulario_contrasena_nueva     = $this->post_texto($_POST['nueva']);
        $this->formulario_contrasena_confirmar = $this->post_texto($_POST['confirmar']);
    } // recibir_formulario

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
        // Si viene el formulario
        if ($_POST['formulario'] == self::$form_name) {
            try {
                // Recibir el formulario y cambiar la contraseña
                $this->recibir_formulario();
                $msg = $this->cambiar_contrasena($this->formulario_contrasena_actual, $this->formulario_contrasena_nueva, $this->formulario_contrasena_confirmar);
                // Mostrar mensaje de exito
                $mensaje = new \Base\MensajeHTML($msg);
                return $mensaje->html($in_encabezado);
            } catch (\Base\RegistroExceptionValidacion $e) {
                // Fallo la validacion, se muestra el mensaje y formulario de nuevo
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Validación')."\n".$this->elaborar_formulario($in_encabezado);
            } catch (\Exception $e) {
                // Error fatal
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        }
        // Mostrar formulario, como cadenero puede provovar una excepcion se encierra en try-catch
        try {
            return $this->elaborar_formulario($in_encabezado);
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html();
        }
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return false;
    } // javascript

} // Clase ContrasenaFormularioHTML

?>
