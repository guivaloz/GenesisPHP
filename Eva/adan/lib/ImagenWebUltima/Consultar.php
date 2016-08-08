<?php
/**
 * GenesisPHP - ImagenWebUltima Consultar
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

namespace ImagenWebUltima;

/**
 * Clase Consultar
 */
class Consultar extends \Base\Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Tomar solo el primer padre
        $padre         = reset($this->padre);
        $padre_clase   = $padre['clase_plural'];
        $padre_columna = $padre['instancia_singular'];
        // TODO: Se espera que tenga las columnas id, caracteres_azar, creado, estatus
        // Entregar
        return <<<FINAL
    /**
     * Consultar
     *
     * @param integer NOTE que requiere el ID del padre
     */
    public function consultar(\$in_padre_id) {
        // Validar padre
        \$padre = new \\{$padre_clase}\\Registro(\$this->sesion);
        \$padre->consultar(\$in_padre_id);
        // Consultar imágenes de ese padre, ordenadas cronológicamente a partir de la más reciente
        \$base_datos = new \\Base2\\BaseDatosMotor();
        try {
            \$consulta = \$base_datos->comando(sprintf("
                SELECT
                    id,
                    caracteres_azar
                FROM
                    {$this->tabla_nombre}
                WHERE
                    {$padre_columna} = %d
                    AND estatus = 'A'
                ORDER BY
                    creado DESC",
                \$padre->id));
        } catch (\\Exception \$e) {
            throw new \\AdmBitacora\\BaseDatosExceptionSQLError(\$this->sesion, 'Error: Al consultar la última imagen. ', \$e->getMessage());
        }
        // Provoca excepción si no hay registros
        if (\$consulta->cantidad_registros() == 0) {
            throw new \\Base2\\ListadoExceptionVacio('Aviso: No se encontraron imágenes.');
        }
        // Obtener sólo la más reciente
        \$resultado = \$consulta->obtener_registro();
        // Definir los parámetros requeridos
        \$this->id              = \$resultado['id'];
        \$this->caracteres_azar = \$resultado['caracteres_azar'];
    } // consultar

FINAL;
    } // php

} // Clase Consultar

?>
