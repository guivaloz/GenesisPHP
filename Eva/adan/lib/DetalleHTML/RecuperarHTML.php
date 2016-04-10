<?php
/**
 * GenesisPHP - DetalleHTML RecuperarHTML
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

namespace DetalleHTML;

/**
 * Clase RecuperarHTML
 */
class RecuperarHTML extends \Base\Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        if ($this->adan->si_hay_que_crear('recuperar')) {
            return <<<FIN
    /**
     * Recuperar HTML
     *
     * @return string HTML con el detalle y el mensaje
     */
    public function recuperar_html() {
        try {
            \$mensaje = new \\Base\\MensajeHTML(\$this->recuperar());
            return \$mensaje->html().\$this->html();
        } catch (\\Exception \$e) {
            \$mensaje = new \\Base\\MensajeHTML(\$e->getMessage());
            return \$mensaje->html();
        }
    } // recuperar_html


FIN;
        }
    } // php

} // Clase RecuperarHTML

?>
