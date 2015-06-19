<?php
/**
 * GenesisPHP - Disco FormularioHTML
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

namespace Pruebas;

/**
 * Clase DiscoFormularioHTML
 */
class DiscoFormularioHTML extends DiscoDetalleHTML {

    // protected $sesion;
    // protected $consultado;
    // public $titulo;
    // public $lanzamiento;
    // public $artista;
    // public $genero;
    // public $canciones_cantidad;
    // public $origen;
    // public $origen_descrito;
    // static public $origen_descripciones;
    // static public $origen_colores;
    // protected $detalle;
    protected $formulario;                        // Instancia de FormularioHTML
    static public $form_name = 'discoformulario'; // Name del formulario

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
        $this->formulario->texto_nombre('titulo', 'Título', $this->titulo);
        $this->formulario->fecha('lanzamiento', 'Lanzamiento', $this->lanzamiento);
        $this->formulario->texto_nombre('artista', 'Artista', $this->artista);
        $this->formulario->texto_nombre('genero', 'Género', $this->genero);
        $this->formulario->texto_entero('canciones_cantidad', 'Cantidad de canciones', $this->canciones_cantidad);
        $this->formulario->select('origen', 'Origen', parent::$origen_descripciones, $this->origen);
        $this->formulario->boton_guardar();
        // Entregar
        return $this->formulario->html($this->titulo, $this->sesion->menu->icono_en('tierra_prueba_formulario'));
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
        $this->titulo             = \Base\FormularioHTML::post_texto($_POST['titulo']);
        $this->lanzamiento        = \Base\FormularioHTML::post_texto($_POST['lanzamiento']);
        $this->artista            = \Base\FormularioHTML::post_texto($_POST['artista']);
        $this->genero             = \Base\FormularioHTML::post_texto($_POST['genero']);
        $this->canciones_cantidad = \Base\FormularioHTML::post_texto($_POST['canciones_cantidad']);
        $this->origen             = \Base\FormularioHTML::post_select($_POST['origen']);
    } // recibir_formulario

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // En este arreglo juntaremos la salida
        $a = array();
        // Si viene el formulario
        if ($_POST['formulario'] == self::$form_name) {
            // Consultar, porque podría existir una propiedad que NO se use en el formulario
            $this->consultar();
            // Recibir
            $this->recibir_formulario();
            // Validar
            try {
                $this->validar();
                // Acumular detalle
                $a[] = parent::html();
                // Acumular mensaje
                $mensaje = new \Base\MensajeHTML("Se recibió el formulario satisfactoriamente");
                $a[]     = $mensaje->html('Respuesta del formulario');
                // Entregar detalle y mensaje
                return implode("\n", $a);
            } catch (\Base\RegistroExceptionValidacion $e) {
                // Falló la validación, mostrar mensaje y el formulario de nuevo
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                $a[]     = $mensaje->html('Respuesta del formulario');
            }
        } else {
            // Consultar porque se va a modificar
            $this->consultar();
        }
        // Acumular el formulario
        $a[] = $this->elaborar_formulario();
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

} // Clase DiscoFormularioHTML

?>
