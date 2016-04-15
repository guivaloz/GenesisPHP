<?php
/**
 * GenesisPHP - Base Registro
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
 * Clase Registro
 */
class Registro extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Definir instancias con las partes
        $propiedades        = new \Registro\Propiedades($this->adan);
        $metodo_consultar   = new \Registro\Consultar($this->adan);
        $metodo_validar     = new \Registro\Validar($this->adan);
        $metodo_nuevo       = new \Registro\Nuevo($this->adan);
        $metodo_agregar     = new \Registro\Agregar($this->adan);
        $metodo_modificar   = new \Registro\Modificar($this->adan);
        $metodo_eliminar    = new \Registro\Eliminar($this->adan);
        $metodo_recuperar   = new \Registro\Recuperar($this->adan);
        // Armar el contenido con las partes
        $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - SED_TITULO_SINGULAR Registro
 *
 * @package SED_PAQUETE
 */

namespace SED_CLASE_PLURAL;

/**
 * Clase Registro
 */
class Registro extends \\Base\\Registro {

{$propiedades->php()}
{$metodo_consultar->php()}
{$metodo_validar->php()}{$metodo_nuevo->php()}{$metodo_agregar->php()}{$metodo_modificar->php()}{$metodo_eliminar->php()}{$metodo_recuperar->php()}} // Clase Registro

?>

FINAL;
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase Registro

?>
