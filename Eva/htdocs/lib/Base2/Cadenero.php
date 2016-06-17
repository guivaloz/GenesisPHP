<?php
/**
 * GenesisPHP - Cadenero
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

namespace Base2;

/**
 * Clase Cadenero
 */
class Cadenero extends Registro {

    // public $consultado;
    // protected $sesion;
    protected $usuario;
    protected $form_name;
    protected $creado;
    protected $clave;
    protected $recibido;

    /**
     * Consultar
     *
     * @param string Clave única que identifica al formulario
     */
    public function consultar($in_clave='') {
        // Parametro clave
        if ($in_clave != '') {
            $this->clave = $in_clave;
        }
        // Validar clave
        if (!is_string($this->clave) || ($this->clave == '')) {
            throw new \Exception('Error en cadenero: Falta la clave o es incorrecta.');
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->clave)) {
            throw new \Exception('Error en cadenero: Clave incorrecta.');
        }
        // Consultar
        $base_datos = new BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    usuario, form_name, recibido,
                    to_char(creado, 'YYYY-MM-DD HH24:MI:SS') as creado
                FROM
                    adm_cadenero
                WHERE
                    clave = %s",
                UtileriasParaSQL::sql_texto($this->clave)));
        } catch (\Exception $e) {
            throw new BaseDatosExceptionSQLError('Error en cadenero: Al consultar en cadenero.');
        }
        // Si no se encuentra, provoca excepcion
        if ($consulta->cantidad_registros() < 1) {
            throw new \Exception('Error en cadenero: No se encontró el formulario.');
        }
        // Definir propiedades
        $a               = $consulta->obtener_registro();
        $this->usuario   = intval($a['usuario']);
        $this->form_name = $a['form_name'];
        $this->recibido  = ($a['recibido'] == 't'); // La columna es de tipo boolean, por lo tanto entrega 't' o 'f'
        $this->creado    = $a['creado'];
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

    /**
     * Encabezado
     *
     * @return string Encabezado
     */
    public function encabezado() {
        return 'Cadenero';
    } // encabezado

    /**
     * Crear clave
     *
     * @param  string Nombre del formulario para saber en donde ocurre
     * @return string Clave unica para poner como campo oculto en el formulario
     */
    public function crear_clave($in_form_name) {
        // Validar que no se haya consultado
        if ($this->consultado) {
            throw new \Exception('Error en cadenero: No se puede crear si ya se ha consultado.');
        }
        // Parametro nombre del formulario
        $this->form_name = $in_form_name;
        // Validar nombre del formulario
        if (!is_string($this->form_name) || ($this->form_name == '')) {
            throw new \Exception('Error en cadenero: Falta el nombre del formulario o es incorrecto.');
        }
        if (!is_string($this->form_name) || !preg_match('/^[a-zA-Z0-9_]+$/', $this->form_name)) {
            throw new \Exception('Error en cadenero: Nombre del formulario incorrecto.');
        }
        // Tomar el usuario de la sesion
        $this->usuario = $this->sesion->usuario;
        // Determinar la clave unica
        $this->clave = uniqid();
        // Agregar a la base de datos
        $base_datos = new BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    adm_cadenero (usuario, form_name, clave)
                VALUES
                    (%s, %s, %s)",
                UtileriasParaSQL::sql_entero($this->usuario),
                UtileriasParaSQL::sql_texto($this->form_name),
                UtileriasParaSQL::sql_texto($this->clave)));
        } catch (\Exception $e) {
            throw new BaseDatosExceptionSQLError($this->sesion, 'Error en cadenero: Al insertar registro en cadenero. ', $e->getMessage());
        }
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
        // Entregar clave unica
        return $this->clave;
    } // crear_clave

    /**
     * Validar recepcion
     *
     * @param  string  Nombre del formulario para saber en donde ocurre
     * @param  string  Clave unica que se haya recibido en el formulario
     * @return boolean Verdadero
     */
    public function validar_recepcion($in_form_name, $in_clave) {
        // Validar que no se haya consultado
        if ($this->consultado) {
            throw new \Exception('Error en cadenero: No se puede validar si ya se ha consultado.');
        }
        // Validar nombre del formulario
        if (!is_string($in_form_name) || ($in_form_name == '')) {
            throw new \Exception('Error en cadenero: Falta el nombre del formulario o es incorrecto.');
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $in_form_name)) {
            throw new \Exception('Error en cadenero: Nombre del formulario incorrecto.');
        }
        // Validar clave
        if (!is_string($in_clave) || ($in_clave == '')) {
            throw new \Exception('Error en cadenero: Falta recibir la clave o es incorrecta.');
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $in_clave)) {
            throw new \Exception('Error en cadenero: Clave incorrecta.');
        }
        // Consultar
        $this->consultar($in_clave);
        // Si el nombre del formulario de cadenero no coince con el nombre del formulario dado, es un error
        if ($in_form_name !== $this->form_name) {
            throw new \Exception('No coincide el formulario.');
        }
        // Si el usuario del registro de cadenero no coincide con el usuario de la sesion, es un error
        if ($this->usuario != $this->sesion->usuario) {
            throw new \Exception('No coincide el usuario.');
        }
        // Si ya fue recibida, provoca excepcion
        if ($this->recibido == true) {
            throw new \Exception('Ya fue recibido este formulario.');
        }
        // Actualizar cambiando recibido a verdadero
        $base_datos = new BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_cadenero
                SET
                    recibido = TRUE
                WHERE
                    clave = %s",
                UtileriasParaSQL::sql_texto($this->clave)));
        } catch (Exception $e) {
            throw new BaseDatosExceptionSQLError('Error en cadenero: Al actualizar registro para cambiar recibido a verdadero.');
        }
        // Entregar verdadero
        return true;
    } // validar_recepcion

} // Clase Cadenero

?>
