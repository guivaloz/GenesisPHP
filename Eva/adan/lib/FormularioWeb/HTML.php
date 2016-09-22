<?php
/**
 * GenesisPHP - FormularioWeb HTML
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

namespace FormularioWeb;

/**
 * Clase HTML
 */
class HTML extends \Base\Plantilla {

    /**
     * Elaborar Recibir Padre por GET o POST
     *
     * @return string Código PHP
     */
    protected function elaborar_recibir_padre() {
        $a = array();
        // Solo las relaciones "padre" las puede recibir por get
        foreach ($this->tabla as $columna => $datos) {
            if (is_array($this->padre[$columna]) && (count($this->padre[$columna]) > 0)) {
                $a[] = "                // Se puede recibir $columna en el url o por post";
                $a[] = "                if (\$_GET['$columna'] != '') {";
                $a[] = "                    \$this->$columna = \$_GET['$columna'];";
                $a[] = "                } elseif ((\$_POST['formulario'] == self::\$form_name) && (\$_POST['$columna'] != '')) {";
                $a[] = "                    \$this->$columna = \$_POST['$columna'];";
                $a[] = "                }";
            }
        }
        if (count($a) == 0) {
            $a[] = "                // Informo que no hay parametros que recibir por el url";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_recibir_padre

    /**
     * Elaborar almacenar imagen
     *
     * @return string Código PHP
     */
    protected function elaborar_almacenar_imagen() {
        if (is_array($this->imagen)) {
            $a   = array();
            $a[] = "                    // Almacenar imagen";
            $a[] = "                    \$imagen = new \\Base2\\ImagenWeb(parent::\$imagen_almacen_ruta, parent::\$imagen_tamanos);";
            $a[] = "                    \$imagen->almacenar(\$this->imagen_temporal, \$this->id, \$this->{$this->imagen['caracteres']});";
            return implode("\n", $a);
        } else {
            return '                    // Informo que no hay imagen';
        }
    } // elaborar_almacenar_imagen

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function html(\$in_encabezado='') {
        // Si ya se elaboro el html, solo se entrega y se termina
        if (\$this->html_elaborado != '') {
            return \$this->html_elaborado;
        }
        // En este arreglo juntaremos la salida
        \$a = array();
        // Si se va a agregar un nuevo registro
        if (\$this->id == 'agregar') {
            try {
{$this->elaborar_recibir_padre()}
                // Nuevo registro
                \$this->nuevo();
                \$this->es_nuevo = true;
            } catch (\\Exception \$e) {
                \$mensaje = new \\Base2\\MensajeWeb(\$e->getMessage());
                return \$mensaje->html('Error');
            }
        // Si viene el formulario
        } elseif (\$_POST['formulario'] == self::\$form_name) {
            try {
                \$this->es_nuevo = (\$_POST['accion'] == 'agregar');
                // Se modifica o se agrega
                if (\$this->es_nuevo) {
                    \$this->recibir_formulario();    // Recibir
                    \$msg = \$this->agregar();        // Agregar
{$this->elaborar_almacenar_imagen()}
                } else {
                    \$this->consultar(\$_POST['id']); // Hay campos en el registro que no se muestran en el formulario
                    \$this->recibir_formulario();    // Por eso consultamos antes de recibir
                    \$msg = \$this->modificar();      // Modificar
                }
                // Se entregara el detalle
                \$a[]                   = parent::html();
                \$this->entrego_detalle = true;
                // Se pasa el javascript del detalle
                \$this->javascript[] = parent::javascript();
                // Y el mensaje
                \$mensaje = new \\Base2\\MensajeWeb(\$msg);
                \$a[]     = \$mensaje->html('Acción exitosa');
                // Conservar el HTML y entregarlo
                \$this->html_elaborado = implode("\\n", \$a);
                return implode("\\n", \$a);
            } catch (\\Base2\\RegistroExceptionValidacion \$e) {
                // Fallo la validacion, se muestra mensaje y, más adelante, el formulario de nuevo
                \$mensaje = new \\Base2\\MensajeWeb(\$e->getMessage());
                \$a[]     = \$mensaje->html('Validación');
            } catch (\\Base2\\BaseDatosExceptionSQLError \$e) {
                // Fallo el comando sql
                \$mensaje       = new \\Base2\\MensajeWeb('Error SQL: Es posible que se haya ocasionado por la validación de la base de datos.<br />'.\$e->getMessage());
                \$mensaje->tipo = 'error';
                \$a[]           = \$mensaje->html('Error SQL');
            } catch (\\Exception \$e) {
                // Error fatal
                \$mensaje = new \\Base2\\MensajeWeb(\$e->getMessage());
                return \$mensaje->html('Error');
            }
        // Debe ser modificacion
        } else {
            // Validar que tenga permiso para modificar
            if (!\$this->sesion->puede_modificar('SED_CLAVE')) {
                \$mensaje = new \\Base2\\MensajeWeb('Aviso: No tiene permiso para modificar SED_MENSAJE_SINGULAR.');
                return \$mensaje->html('Error');
            }
            // Consultamos
            try {
                \$this->consultar();
            } catch (\Exception \$e) {
                \$mensaje = new \\Base2\\MensajeWeb(\$e->getMessage());
                \$a[]     = \$mensaje->html('Error');
            }
        }
        // Mostrar formulario, como cadenero puede provovar una excepcion se encierra en try-catch
        try {
            \$a[] = \$this->elaborar_formulario(\$in_encabezado);
        } catch (\Exception \$e) {
            \$mensaje = new \\Base2\\MensajeWeb(\$e->getMessage());
            \$a[]     = \$mensaje->html('Formulario');
        }
        // Conservar el HTML y entregarlo
        \$this->html_elaborado = implode("\\n", \$a);
        return \$this->html_elaborado;
    } // html

FINAL;
    } // php

} // Clase HTML

?>
