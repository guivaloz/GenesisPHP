<?php
/**
 * GenesisPHP - AdmRoles Registro
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

namespace AdmRoles;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $id;
    public $departamento;
    public $departamento_nombre;
    public $modulo;
    public $modulo_nombre;
    public $permiso_maximo;
    public $permiso_maximo_descrito;
    public $estatus;
    public $estatus_descrito;
    static public $permiso_maximo_descripciones = array(
        '1' => '1) VER',
        '2' => '2) MODIFICAR',
        '3' => '3) AGREGAR',
        '4' => '4) ELIMINAR',
        '5' => '5) RECUPERAR');
    static public $permiso_maximo_colores = array(
        '1' => 'nivel1',
        '2' => 'nivel2',
        '3' => 'nivel3',
        '4' => 'nivel4',
        '5' => 'nivel5');
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
        if (!$this->sesion->puede_ver('roles')) {
            throw new \Exception('Aviso: No tiene permiso para consultar los roles.');
        }
        // Parametros
        if ($in_id !== false) {
            $this->id = $in_id;
        }
        // Validar
        if (!validar_entero($this->id)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar el rol por ID incorrecto.');
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("
                SELECT
                    r.departamento, d.nombre AS departamento_nombre,
                    r.modulo, m.nombre AS modulo_nombre,
                    r.permiso_maximo,
                    r.estatus
                FROM
                    adm_roles r,
                    adm_modulos m,
                    adm_departamentos d
                WHERE
                    r.departamento = d.id
                    AND r.modulo = m.id
                    AND r.id = {$this->id}");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar el rol.', $e->getMessage());
        }
        // Si la consulta no entrego registros
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró a el rol.');
        }
        // Resultado de la consulta
        $a = $consulta->obtener_registro();
        // Validar que si esta eliminado tenga permiso para consultarlo
        if (($a['estatus'] == 'B') && !$this->sesion->puede_recuperar('roles')) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No tiene permiso de consultar un registro eliminado.');
        }
        // Definir propiedades
        $this->departamento            = intval($a['departamento']);
        $this->departamento_nombre     = $a['departamento_nombre'];
        $this->modulo                  = intval($a['modulo']);
        $this->modulo_nombre           = $a['modulo_nombre'];
        $this->permiso_maximo          = intval($a['permiso_maximo']);
        $this->permiso_maximo_descrito = self::$permiso_maximo_descripciones[$this->permiso_maximo];
        $this->estatus                 = $a['estatus'];
        $this->estatus_descrito        = self::$estatus_descripciones[$this->estatus];
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

    /**
     * Validar
     */
    public function validar() {
        // Validar departamento
        $departamento = new \Departamentos\Registro($this->sesion);
        try {
            $departamento->consultar($this->departamento);
        } catch (\Exception $e) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Departamento incorrecto. '.$e->getMessage());
        }
        $this->departamento_nombre = $departamento->nombre;
        // Validar modulo
        $modulo = new \Modulos\Registro($this->sesion);
        try {
            $modulo->consultar($this->modulo);
        } catch (\Exception $e) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Módulo incorrecto. '.$e->getMessage());
        }
        $this->modulo_nombre = $modulo->nombre;
        // Validar permiso maximo
        if (!validar_entero($this->permiso_maximo)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Permiso máximo incorrecto.');
        }
        // Validar estatus
        if (!array_key_exists($this->estatus, self::$estatus_descripciones)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Estatus incorrecto.');
        }
        // Definimos los descritos
        $this->permiso_maximo_descrito = self::$permiso_maximo_descripciones[$this->permiso_maximo];
        $this->estatus_descrito        = self::$estatus_descripciones[$this->estatus];
    } // validar

    /**
     * Nuevo
     */
    public function nuevo() {
        // Que tenga permiso para agregar
        if (!$this->sesion->puede_agregar('roles')) {
            throw new \Exception('Aviso: No tiene permiso para agregar departamentos.');
        }
        // Definir propiedades
        $this->id                      = 'agregar';
        $this->departamento            = '';
        $this->departamento_nombre     = '';
        $this->modulo                  = '';
        $this->modulo_nombre           = '';
        $this->permiso_maximo          = '';
        $this->permiso_maximo_descrito = '';
        $this->estatus                 = 'A';
        $this->estatus_descrito        = self::$estatus_descripciones[$this->estatus];
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
        if (!$this->sesion->puede_agregar('roles')) {
            throw new \Exception('Aviso: No tiene permiso para agregar roles.');
        }
        // Verificar que no haya sido consultado
        if ($this->consultado == true) {
            throw new \Exception('Error: Ha sido consultado el rol, no debe estarlo.');
        }
        // Validar
        $this->validar();
        // Insertar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    adm_roles (departamento, modulo, permiso_maximo)
                VALUES
                    (%s, %s, %s)",
                sql_entero($this->departamento),
                sql_entero($this->modulo),
                sql_entero($this->permiso_maximo)));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al insertar el rol. ', $e->getMessage());
        }
        // Obtener el id del registro recién insertado
        try {
            $consulta = $base_datos->comando("SELECT last_value AS id FROM roles_id_seq");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al obtener el ID del rol. ', $e->getMessage());
        }
        $a        = $consulta->obtener_registro();
        $this->id = intval($a['id']);
        // Despues de insertar se considera como consultado
        $this->consultado = true;
        // Elborar mensaje
        $msg = "Nuevo rol para {$this->departamento_nombre} en {$this->modulo_nombre} con {$this->permiso_maximo_descrito}.";
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
        if (!$this->sesion->puede_modificar('roles')) {
            throw new \Exception('Aviso: No tiene permiso para modificar roles.');
        }
        // Verificar que haya sido consultado
        if ($this->consultado == false) {
            throw new \Exception('Error: No ha sido consultado el rol para modificarlo.');
        }
        // Validar
        $this->validar();
        // Hay que determinar que va cambiar, para armar el mensaje
        $original = new Registro($this->sesion);
        try {
            $original->consultar($this->id);
        } catch (\Exception $e) {
            die('Esto no debería pasar. Error al consultar registro original del rol.');
        }
        $a = array();
        if ($this->departamento != $original->departamento) {
            $a[] = "departamento {$this->departamento_nombre}";
        }
        if ($this->modulo != $original->modulo_nombre) {
            $a[] = "modulo {$this->modulo_nombre}";
        }
        if ($this->permiso_maximo != $original->permiso_maximo) {
            $a[] = "permiso máximo {$this->permiso_maximo}";
        }
        if ($this->estatus != $original->estatus) {
            $a[] = "estatus {$this->estatus_descrito}";
        }
        // Si no hay cambios, provoca excepcion de validacion
        if (count($a) == 0) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No hay cambios.');
        } else {
            $msg = "Modificado el rol para {$this->departamento_nombre} en {$this->modulo_nombre} con ".implode(', ', $a);
        }
        // Actualizar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_roles
                SET
                    departamento=%s, modulo=%s, permiso_maximo=%s, estatus=%s
                WHERE
                    id=%d",
                sql_entero($this->departamento),
                sql_entero($this->modulo),
                sql_entero($this->permiso_maximo),
                sql_texto($this->estatus),
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al actualizar el rol. ', $e->getMessage());
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
        if (!$this->sesion->puede_eliminar('roles')) {
            throw new \Exception('Aviso: No tiene permiso para eliminar roles.');
        }
        // Consultar si no lo esta
        if ($this->consultado == false) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'B') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede eliminarse el rol porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'B';
        $this->modificar();
        // Entregar mensaje
        return "Se ha eliminado el rol para {$this->departamento_nombre} en {$this->modulo_nombre}";
    } // eliminar

    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
        // Que tenga permiso para recuperar
        if (!$this->sesion->puede_recuperar('roles')) {
            throw new \Exception('Aviso: No tiene permiso para recuperar roles.');
        }
        // Consultar si no lo esta
        if ($this->consultado == false) {
            $this->consultar(); // PUEDE PROVOCAR UNA EXCEPCION
        }
        // Validar el estatus
        if ($this->estatus == 'A') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede recuperarse el rol porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'A';
        $this->modificar();
        // Entregar mensaje
        return "Se ha recuperado el rol para {$this->departamento_nombre} en {$this->modulo_nombre}";
    } // recuperar

} // Clase Registro

?>
