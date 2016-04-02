<?php
/**
 * GenesisPHP - AdmDepartamentos Registro
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

namespace AdmDepartamentos;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $id;
    public $nombre;
    public $clave;
    public $notas;
    public $estatus;
    public $estatus_descrito;
    static public $estatus_descripciones = array(
        'A' => 'EN USO',
        'B' => 'ELIMINADO');
    static public $estatus_colores = array(
        'A' => 'blanco',
        'B' => 'gris');

    /**
     * Consultar
     *
     * @param integer ID del registro
     */
    public function consultar($in_id=false) {
        // Que tenga permiso para consultar
        if (!$this->sesion->puede_ver('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para consultar los departamentos.');
        }
        // Parametros
        if ($in_id !== false) {
            $this->id = $in_id;
        }
        // Validar
        if (!validar_entero($this->id)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar el departamento por ID incorrecto.');
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("
                SELECT
                    nombre, clave, notas, estatus
                FROM
                    adm_departamentos
                WHERE
                    id = {$this->id}");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar el departamento.', $e->getMessage());
        }
        // Si la consulta no entrego registros
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró al departamento.');
        }
        // Resultado de la consulta
        $a = $consulta->obtener_registro();
        // Validar que si esta eliminado tenga permiso para consultarlo
        if (($a['estatus'] == 'B') && !$this->sesion->puede_recuperar('departamentos')) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No tiene permiso de consultar un registro eliminado.');
        }
        // Definir propiedades
        $this->nombre           = $a['nombre'];
        $this->clave            = $a['clave'];
        $this->notas            = $a['notas'];
        $this->estatus          = $a['estatus'];
        $this->estatus_descrito = self::$estatus_descripciones[$this->estatus];
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

    /**
     * Validar
     */
    public function validar() {
        // Validamos las propiedades
        if (!validar_nombre($this->nombre)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: El campo nombre es incorrecto.');
        }
        if (!validar_nombre($this->clave)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: El campo clave es incorrecto.');
        }
        if (($this->notas != '') && !validar_notas($this->notas)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: El campo notas es incorrecto.');
        }
        if (!array_key_exists($this->estatus, self::$estatus_descripciones)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Estatus incorrecto.');
        }
        // Definimos el estatus descrito
        $this->estatus_descrito = self::$estatus_descripciones[$this->estatus];
    } // validar

    /**
     * Nuevo
     */
    public function nuevo() {
        // Que tenga permiso para agregar
        if (!$this->sesion->puede_agregar('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para agregar departamentos.');
        }
        // Definir propiedades
        $this->id               = 'agregar';
        $this->nombre           = '';
        $this->clave            = '';
        $this->notas            = '';
        $this->estatus          = 'A';
        $this->estatus_descrito = self::$estatus_descripciones[$this->estatus];
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
    } // nuevo

    /**
     * Agregar
     *
     * @return string Mensaje
     */
    public function agregar() {
        // Que tenga permiso para agregar
        if (!$this->sesion->puede_agregar('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para agregar departamentos.');
        }
        // Verificar que no haya sido consultado
        if ($this->consultado == true) {
            throw new \Exception('Error: Ha sido consultado el departamento, no debe estarlo.');
        }
        // Validar
        $this->validar();
        // Insertar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    adm_departamentos (nombre, clave, notas)
                VALUES
                    (%s, %s, %s)",
                sql_texto($this->nombre),
                sql_texto($this->clave),
                sql_texto($this->notas)));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al insertar el departamento. ', $e->getMessage());
        }
        // Obtener el id del registro recién insertado
        try {
            $consulta = $base_datos->comando("
                SELECT
                    last_value AS id
                FROM
                    adm_departamentos_id_seq");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al obtener el ID del departamento. ', $e->getMessage());
        }
        $a        = $consulta->obtener_registro();
        $this->id = intval($a['id']);
        // Despues de insertar se considera como consultado
        $this->consultado = true;
        // Elaborar mensaje
        $msg = "Nuevo departamento {$this->nombre}.";
        // Agregar a la bitacora que hay un nuevo registro
        $bitacora = new \Bitacora\Registro($this->sesion);
        $bitacora->agregar_nuevo($this->id, $msg);
        // Entregar mensaje
        return $msg;
    } //agregar

    /**
     * Modificar
     *
     * @return string Mensaje
     */
    public function modificar() {
        // Que tenga permiso para modificar
        if (!$this->sesion->puede_modificar('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para modificar departamentos.');
        }
        // Verificar que haya sido consultado
        if ($this->consultado == false) {
            throw new \Exception('Error: No ha sido consultado el departamento para modificarlo.');
        }
        // Validar
        $this->validar();
        // Hay que determinar que va cambiar, para armar el mensaje
        $original = new Registro($this->sesion);
        try {
            $original->consultar($this->id);
        } catch (\Exception $e) {
            die('Esto no debería pasar. Error al consultar registro original del departamento.');
        }
        $a = array();
        if ($this->nombre != $original->nombre) {
            $a[] = "nombre \"{$this->nombre}\"";
        }
        if ($this->clave != $original->clave) {
            $a[] = "clave \"{$this->clave}\"";
        }
        if ($this->notas != $original->notas) {
            $a[] = "notas \"{$this->notas}\"";
        }
        if ($this->estatus != $original->estatus) {
            $a[] = "estatus \"{$this->estatus_descrito}\"";
        }
        // Si no hay cambios, provoca excepcion de validacion
        if (count($a) == 0) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No hay cambios.');
        } else {
            $msg = "Modificado el departamento {$this->nombre} con ".implode(', ', $a);
        }
        // Actualizar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_departamentos
                SET
                    nombre=%s, clave=%s, notas=%s, estatus=%s
                WHERE
                    id=%d",
                sql_texto($this->nombre),
                sql_texto($this->clave),
                sql_texto($this->notas),
                sql_texto($this->estatus),
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al actualizar el departamento. ', $e->getMessage());
        }
        // Agregar a la bitacora que se modifico el registro
        $bitacora = new \Bitacora\Registro($this->sesion);
        $bitacora->agregar_modificado($this->id, $msg);
        // Entregar mensaje
        return $msg;
    } // modificar

    /**
     * Eliminar
     *
     * @return string Mensaje
     */
    public function eliminar() {
        // Que tenga permiso para eliminar
        if (!$this->sesion->puede_eliminar('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para eliminar departamentos.');
        }
        // Consultar si no lo esta
        if ($this->consultado == false) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'B') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede eliminarse el departamento porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'B';
        $this->modificar();
        // Entregar mensaje
        return "Se ha eliminado el departamento {$this->nombre}";
    } // eliminar

    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
        // Que tenga permiso para recuperar
        if (!$this->sesion->puede_recuperar('departamentos')) {
            throw new \Exception('Aviso: No tiene permiso para recuperar departamentos.');
        }
        // Consultar si no lo esta
        if ($this->consultado == false) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'A') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede recuperarse el departamento porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'A';
        $this->modificar();
        // Entregar mensaje
        return "Se ha recuperado el departamento {$this->nombre}";
    } // recuperar

} // Clase Registro

?>
