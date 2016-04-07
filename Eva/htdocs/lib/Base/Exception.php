<?php
/**
 * GenesisPHP - Exception
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

namespace Base;

/**
 * Clase Exception
 */
class Exception extends \Exception {

    /**
     * SQL Texto
     *
     * @parem string Texto
     */
    function sql_texto($texto) {
        if (trim($texto) == '') {
            return 'NULL';
        } else {
            return "'".pg_escape_string(trim($texto))."'";
        }
    } // sql_texto

} // Clase Exception

?>
