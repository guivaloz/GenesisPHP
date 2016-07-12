<?php
/**
 * GenesisPHP - DetalleWeb Barra
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

namespace DetalleWeb;

/**
 * Clase Barra
 */
class Barra extends \Base\Plantilla {

    /**
     * Botón modificar
     *
     * @return string Código PHP
     */
    protected function boton_modificar() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Boton modificar
        if ($this->adan->si_hay_que_crear('formulario')) {
            $a[] = "            if ((\$this->estatus != '{$this->estatus['eliminado']}') && \$this->sesion->puede_modificar('SED_CLAVE')) {";
            $a[] = "                \$barra->boton_modificar(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, \$this->id, self::\$accion_modificar));";
            $a[] = "            }";
        }
        // Entregar
        if (count($a) > 0) {
            return implode("\n", $a)."\n";
        } else {
            return '';
        }
    } // boton_modificar

    /**
     * Botón eliminar
     *
     * @return string Código PHP
     */
    protected function boton_eliminar() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Boton eliminar
        if (is_array($this->estatus) && $this->adan->si_hay_que_crear('eliminar')) {
            $a[] = "            if ((\$this->estatus != '{$this->estatus['eliminado']}') && \$this->sesion->puede_eliminar('SED_CLAVE')) {";
            $a[] = "                \$barra->boton_eliminar_confirmacion(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, \$this->id, self::\$accion_eliminar),";
            $a[] = "                    \"¿Está seguro de querer <strong>eliminar</strong> a SED_MENSAJE_SINGULAR?\");";
            $a[] = "            }";
        }
        // Entregar
        if (count($a) > 0) {
            return implode("\n", $a)."\n";
        } else {
            return '';
        }
    } // boton_eliminar

    /**
     * Botón recuperar
     *
     * @return string Código PHP
     */
    protected function boton_recuperar() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Boton recuperar
        if (is_array($this->estatus) && $this->adan->si_hay_que_crear('recuperar')) {
            $a[] = "            if ((\$this->estatus == '{$this->estatus['eliminado']}') && \$this->sesion->puede_recuperar('SED_CLAVE')) {";
            $a[] = "                \$barra->boton_recuperar_confirmacion(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, \$this->id, self::\$accion_recuperar),";
            $a[] = "                    \"¿Está seguro de querer <strong>recuperar</strong> a SED_MENSAJE_SINGULAR?\");";
            $a[] = "            }";
        }
        // Entregar
        if (count($a) > 0) {
            return implode("\n", $a)."\n";
        } else {
            return '';
        }
    } // boton_recuperar

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Barra
     *
     * @param  string Encabezado opcional
     * @return mixed  Instancia de \Base2\BarraWeb
     */
    protected function barra(\$in_encabezado='') {
        // Si viene el encabezado como parametro se usa, de lo contrario se ejecuta el método encabezado
        if (\$in_encabezado !== '') {
            \$encabezado = \$in_encabezado;
        } else {
            \$encabezado = \$this->encabezado();
        }
        // Crear la barra
        \$barra             = new \Base2\BarraWeb();
        \$barra->encabezado = \$encabezado;
        \$barra->icono      = \$this->sesion->menu->icono_en('SED_CLAVE');
{$this->boton_modificar()}{$this->boton_eliminar()}{$this->boton_recuperar()}        // Entregar
        return \$barra;
    } // barra

FINAL;
    } // php

} // Clase Barra

/*
        // Botones impresiones
        if (is_array($this->hijos)) {
            foreach ($this->hijos as $hijo) {
                if ($hijo['contenido'] == 'impresiones') {
                    $a[] = "            if (\$this->estatus != '{$this->estatus['eliminado']}') {";
                    $a[] = "                \$barra->boton_imprimir_confirmacion(sprintf('{$hijo['archivo_plural']}.php?{$this->reptil['instancia_singular']}=%s&caracteresazar=%s', \$this->id, caracteres_azar()),";
                    $a[] = "                    \"Confirme que va a imprimir {$hijo['mensaje_singular']}, de clic en el botón...\",";
                    $a[] = "                    \"{$hijo['etiqueta_singular']}\",";
                    $a[] = "                    \"{$hijo['instancia_singular']}\");";
                    $a[] = '            }';
                }
            }
        }
*/

?>
