<?php
/**
 * GenesisPHP - Registro Recuperar
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

namespace Registro;

/**
 * Clase Recuperar
 */
class Recuperar extends \Base\Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // Verificar que se use estatus
        if (is_array($this->estatus) && $this->adan->si_hay_que_crear('recuperar')) {
            // Entregar
            return <<<FIN
    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
        // Que tenga permiso para recuperar
        if (!\$this->sesion->puede_recuperar('SED_CLAVE')) {
            throw new \\Exception('Aviso: No tiene permiso para recuperar SED_MENSAJE_SINGULAR.');
        }
        // Consultar si no lo esta
        if (\$this->consultado == false) {
            \$this->consultar();
        }
        // Validar que esté eliminado
        if (\$this->estatus != '{$this->estatus['eliminado']}') {
            throw new \\Base2\\RegistroExceptionValidacion('Aviso: No puede recuperarse SED_MENSAJE_SINGULAR porque ya lo está.');
        }
        // Cambiar el estatus
        \$this->estatus = '{$this->estatus['enuso']}';
        // Validar
        \$this->validar();
        // Actualizar la base de datos
        \$base_datos = new \\Base2\\BaseDatosMotor();
        try {
            \$base_datos->comando(sprintf("
                UPDATE
                    {$this->tabla_nombre}
                SET
                    estatus = '%s'
                WHERE
                    id = %d",
                \$this->estatus,
                \$this->id));
        } catch (\\Exception \$e) {
            throw new \\Base2\\BaseDatosExceptionSQLError(\$this->sesion, 'Error: Al recuperar SED_MENSAJE_SINGULAR. ', \$e->getMessage());
        }
        // Elaborar mensaje
        \$msg = "Recuperó SED_SUBTITULO_SINGULAR {$this->columnas_vip_para_mensaje()}";
        // Agregar a la bitácora que se eliminó un registro
        \$bitacora = new \\AdmBitacora\\Registro(\$this->sesion);
        \$bitacora->agregar_recupero(\$this->id, \$msg);
        // Entregar mensaje
        return \$msg;
    } // recuperar

FIN;
        }
    } // php

} // Clase Recuperar

?>
