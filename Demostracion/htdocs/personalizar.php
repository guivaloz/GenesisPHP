<?php
/**
 * GenesisPHP - Página Inicial
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

require_once('lib/Base/AutocargadorClases.php');

/**
 * Clase PersonalizarPaginaHTML
 */
class PersonalizarPaginaHTML extends \Base\PaginaHTML {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('personalizar');
    } // constructor

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Solo si se carga con éxito la sesión
        if ($this->sesion_exitosa) {
            // Lenguetas
            $lenguetas = new \Base\LenguetasHTML();
            // Generales
            $detalle = new \Personalizar\DetalleHTML($this->sesion);
            $lenguetas->agregar('personalizarGenerales', 'Generales', $detalle->html());
            // Formulario contraseña
            $formulario_contrasena = new \Personalizar\ContrasenaFormularioHTML($this->sesion);
            $lenguetas->agregar('personalizarContraseña', 'Contraseña', $formulario_contrasena->html());
            if ($_POST['formulario'] == \Personalizar\ContrasenaFormularioHTML::$form_name) {
                $lenguetas->definir_activa();
                // Para que los cambios se vean reflejados en generales, recargamos
                $this->sesion->cargar($this->clave);
                $detalle->consultar();
                $lenguetas->agregar('personalizarGenerales', 'Generales !', $detalle->html());
            }
            // Formulario renglones
            $formulario_renglones = new \Personalizar\RenglonesFormularioHTML($this->sesion);
            $lenguetas->agregar('personalizarRenglones', 'Renglones', $formulario_renglones->html());
            if ($_POST['formulario'] == \Personalizar\RenglonesFormularioHTML::$form_name) {
                $lenguetas->definir_activa();
                // Para que los cambios se vean reflejados en generales, recargamos
                $this->sesion->cargar($this->clave);
                $detalle->consultar();
                $lenguetas->agregar('personalizarGenerales', 'Generales !', $detalle->html());
            }
            // Pasar el html de las lenguetas al contenido
            $this->contenido[] = $lenguetas->html();
        }
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase PersonalizarPaginaHTML

// Ejecutar y mostrar
$pagina = new PersonalizarPaginaHTML();
echo $pagina->html();

?>
