<?php
/**
 * GenesisPHP - Pruebas DiscoFormularioWeb
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

namespace Pruebas;

/**
 * Clase DiscoFormularioWeb
 */
class DiscoFormularioWeb extends DiscoDetalleWeb {

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
    protected $formulario;                        // Instancia de FormularioWeb
    static public $form_name = 'discoformulario'; // Name del formulario

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar FormularioHTML
        $this->formulario = new \Base2\FormularioWeb(self::$form_name);
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
        $this->formulario->encabezado = 'Disco';
        $this->formulario->icono      = $this->sesion->menu->icono_en('tierra_prueba_formulario');
        $this->formulario->texto_nombre('titulo', 'Título', $this->titulo);
        $this->formulario->fecha('lanzamiento', 'Lanzamiento', $this->lanzamiento);
        $this->formulario->texto_nombre('artista', 'Artista', $this->artista);
        $this->formulario->texto_nombre('genero', 'Género', $this->genero);
        $this->formulario->texto_entero('canciones_cantidad', 'Cantidad de canciones', $this->canciones_cantidad);
        $this->formulario->select('origen', 'Origen', parent::$origen_descripciones, $this->origen);
        $this->formulario->boton_guardar();
        // Entregar $this->titulo, $this->sesion->menu->icono_en('tierra_prueba_formulario')
        return $this->formulario->html();
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
        $this->titulo             = \Base2\UtileriasParaFormularios::post_texto($_POST['titulo']);
        $this->lanzamiento        = \Base2\UtileriasParaFormularios::post_texto($_POST['lanzamiento']);
        $this->artista            = \Base2\UtileriasParaFormularios::post_texto($_POST['artista']);
        $this->genero             = \Base2\UtileriasParaFormularios::post_texto($_POST['genero']);
        $this->canciones_cantidad = \Base2\UtileriasParaFormularios::post_texto($_POST['canciones_cantidad']);
        $this->origen             = \Base2\UtileriasParaFormularios::post_select($_POST['origen']);
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
                $mensaje = new \Base2\MensajeWeb("Se recibió el formulario satisfactoriamente");
                $a[]     = $mensaje->html('Éxito');
                // Entregar detalle y mensaje
                return implode("\n", $a);
            } catch (\Base2\RegistroExceptionValidacion $e) {
                // Falló la validación, mostrar mensaje y el formulario de nuevo
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                $a[]     = $mensaje->html('Validación');
            } catch (\Exception $e) {
                // Error fatal
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                $a[]     = $mensaje->html('Error');
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

} // Clase DiscoFormularioWeb

?>
