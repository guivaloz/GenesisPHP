<?php
/**
 * GenesisPHP - Personalizar PaginaWeb
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
 * Clase PaginaWeb
 */
class PaginaWeb extends \Base2\PaginaWeb {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('personalizar');
    } // constructor

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Solo si se carga con éxito la sesión
        if ($this->sesion_exitosa) {
            // Iniciar lengüetas
            $lenguetas = new \Base2\LenguetasWeb();
            // Iniciar instancias para cada lengüeta
            $contrasena_form = new ContrasenaFormularioWeb($this->sesion);
            $renglones_form  = new RenglonesFormularioWeb($this->sesion);
            $generales       = new DetalleWeb($this->sesion);
            // Ejecutar método HTML para que los procedimientos se lleven a cabo
            if ($_POST['formulario'] == ContrasenaFormularioWeb::$form_name) {
                $lenguetas->agregar('personalizarContraseña', 'Contraseña', $contrasena_form->html());
                $lenguetas->agregar('personalizarRenglones',  'Renglones',  $renglones_form);
                $lenguetas->agregar('personalizarGenerales',  'Generales',  $generales);
                $lenguetas->definir_activa('personalizarContraseña');
            } elseif ($_POST['formulario'] == RenglonesFormularioWeb::$form_name) {
                $lenguetas->agregar('personalizarContraseña', 'Contraseña', $contrasena_form);
                $lenguetas->agregar('personalizarRenglones',  'Renglones',  $renglones_form->html());
                $lenguetas->agregar('personalizarGenerales',  'Generales',  $generales);
                $lenguetas->definir_activa('personalizarRenglones');
            } else {
                $lenguetas->agregar('personalizarContraseña', 'Contraseña', $contrasena_form);
                $lenguetas->agregar('personalizarRenglones',  'Renglones',  $renglones_form);
                $lenguetas->agregar('personalizarGenerales',  'Generales',  $generales);
                $lenguetas->definir_activa('personalizarGenerales');
            }
            // Pasar el html y el javascript de las lenguetas al contenido
            $this->contenido[]  = $lenguetas->html();
            $this->javascript[] = $lenguetas->javascript();
        }
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase PaginaWeb

?>
