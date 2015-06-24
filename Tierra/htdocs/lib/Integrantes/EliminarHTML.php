<?php
/**
 * GenesisPHP - Integrantes EliminarHTML
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

namespace Integrantes;

/**
 * Clase EliminarHTML
 */
class EliminarHTML extends DetalleHTML {

    /**
     * HTML
     *
     * Debe definir el ID o haberlo consultado antes de ejecutar
     *
     * @return string Código HTML
     */
    public function html() {
        // Eliminar, si tiene éxito se muestra el mensaje y el detalle
        try {
            $msg     = $this->eliminar();
            $mensaje = new \Base\MensajeHTML($msg);
            return $mensaje->html()."\n".parent::html();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html('Error');
        }
    } // html

} // Clase EliminarHTML

?>
