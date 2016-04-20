<?php
/**
 * GenesisPHP - Base Datos Exception SQL Error
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

namespace Base;

/**
 * Clase BaseDatosExceptionSQLError
 */
class BaseDatosExceptionSQLError extends \Base\Exception {

    /**
     * Constructor
     *
     * @param mixed  Sesion
     * @param string Mensaje humano
     * @param string Mensaje del motor de la base de datos
     */
    public function __construct(\Inicio\Sesion $sesion, $mensaje_humano, $mensaje_motor) {
        // Agregar evento a la bitacora
        $base_datos = new BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("INSERT INTO adm_bitacora (usuario, pagina, tipo, url, notas) VALUES (%s, %s, %s, %s, %s)",
                $sesion->usuario,
                $this->sql_texto($sesion->pagina),
                $this->sql_texto('X'),
                $this->sql_texto($_SESION['PHP_SELF']),
                $this->sql_texto("$mensaje_humano $mensaje_motor")), true); // Tiene el true para tronar en caso de error
        } catch (\Exception $e) {
            die("Error: Al agregar a la bitácora.");
        }
        // Ejecutar constructor del padre
        parent::__construct($mensaje_humano);
    } // constructor

} // Clase BaseDatosExceptionSQLError

?>
