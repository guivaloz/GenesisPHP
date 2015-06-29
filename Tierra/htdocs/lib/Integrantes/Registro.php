<?php
/**
 * GenesisPHP - Integrantes Registro
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

namespace Integrantes;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $id;
    public $usuario;
    public $usuario_nombre;
    public $usuario_nom_corto;
    public $departamento;
    public $departamento_nombre;
    public $poder;
    public $poder_descrito;
    public $estatus;
    public $estatus_descrito;
    static public $poder_descripciones = array(
        '1' => '1) Ver',
        '2' => '2) Modificar',
        '3' => '3) Agregar',
        '4' => '4) Eliminar',
        '5' => '5) Recuperar',
        '6' => '6) Director',
        '7' => '7) Webmaster');
    static public $poder_colores = array(
        '1' => 'nivel1',
        '2' => 'nivel2',
        '3' => 'nivel3',
        '4' => 'nivel4',
        '5' => 'nivel5',
        '6' => 'nivel6',
        '7' => 'nivel7');
    static public $estatus_descripciones = array(
        'A' => 'En uso',
        'B' => 'Eliminado');
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
        if (!$this->sesion->puede_ver('integrantes')) {
            throw new \Exception('Aviso: No tiene permiso para consultar los integrantes.');
        }
        // Parámetro ID
        if ($in_id !== false) {
            $this->id = $in_id;
        }
        // Validar
        if (!$this->validar_entero($this->id)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar el integrante por ID incorrecto.');
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf('
                SELECT
                    i.usuario, u.nombre AS usuario_nombre, u.nom_corto AS usuario_nom_corto,
                    i.departamento, d.nombre AS departamento_nombre,
                    i.poder,
                    i.estatus
                FROM
                    integrantes i,
                    departamentos d,
                    usuarios u
                WHERE
                    i.usuario = u.id
                    AND i.departamento = d.id
                    AND i.id = %u', $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar el integrante.', $e->getMessage());
        }
        // Si la consulta no entregó nada
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró al integrante.');
        }
        // Obtener resultado de la consulta
        $a = $consulta->obtener_registro();
        // Si esta eliminado, debe tener permiso para consultarlo
        if (($a['estatus'] == 'B') && !$this->sesion->puede_recuperar('integrantes')) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No tiene permiso de consultar un registro eliminado.');
        }
        // Definir propiedades
        $this->usuario             = intval($a['usuario']);
        $this->usuario_nombre      = $a['usuario_nombre'];
        $this->usuario_nom_corto   = $a['usuario_nom_corto'];
        $this->departamento        = intval($a['departamento']);
        $this->departamento_nombre = $a['departamento_nombre'];
        $this->poder               = $a['poder'];
        $this->poder_descrito      = self::$poder_descripciones[$this->poder];
        $this->estatus             = $a['estatus'];
        $this->estatus_descrito    = self::$estatus_descripciones[$this->estatus];
        // Poner como verdadero el flag de consultado
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
        // Validar usuario
        $usuario = new \Usuarios\Registro($this->sesion);
        try {
            $usuario->consultar($this->usuario);
        } catch (\Exception $e) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Usuario incorrecto. '.$e->getMessage());
        }
        $this->usuario_nom_corto = $usuario->nom_corto;
        $this->usuario_nombre    = $usuario->nombre;
        // Validar poder
        if (!array_key_exists($this->poder, self::$poder_descripciones)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Poder incorrecto.');
        }
        // Validar estatus
        if (!array_key_exists($this->estatus, self::$estatus_descripciones)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Estatus incorrecto.');
        }
        // Definimos los descritos
        $this->poder_descrito   = self::$poder_descripciones[$this->poder];
        $this->estatus_descrito = self::$estatus_descripciones[$this->estatus];
    } // validar

    /**
     * Nuevo
     */
    public function nuevo() {
        // Que tenga permiso para agregar
        if (!$this->sesion->puede_agregar('integrantes')) {
            throw new \Exception('Aviso: No tiene permiso para agregar integrantes.');
        }
        // Definir propiedades
        $this->id                  = 'agregar';
        $this->usuario             = '';
        $this->usuario_nombre      = '';
        $this->usuario_nom_corto   = '';
        $this->departamento        = '';
        $this->departamento_nombre = '';
        $this->poder               = '';
        $this->poder_descrito      = '';
        $this->estatus             = 'A';
        $this->estatus_descrito    = self::$estatus_descripciones[$this->estatus];
        // Poner como verdadero el flag de consultado
        $this->consultado = true;
    } // nuevo

    /**
     * Agregar
     *
     * @return string Mensaje
     */
    public function agregar() {
        // Que tenga permiso para agregar
        if (!$this->sesion->puede_agregar('integrantes')) {
            throw new \Exception('Aviso: No tiene permiso para agregar integrantes.');
        }
        // Verificar que NO haya sido consultado
        if ($this->consultado == true) {
            throw new \Exception('Error: Ha sido consultado el integrante, no debe estarlo.');
        }
        // Validar
        $this->validar();
        // Insertar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    integrantes (usuario, departamento, poder)
                VALUES
                    (%u, %u, %u)",
                $this->sql_entero($this->usuario),
                $this->sql_entero($this->departamento),
                $this->sql_entero($this->poder)));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al insertar el integrante. ', $e->getMessage());
        }
        // Obtener el ID del registro recién insertado
        try {
            $consulta = $base_datos->comando("SELECT last_value AS id FROM integrantes_id_seq");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al obtener el ID del integrante. ', $e->getMessage());
        }
        $a        = $consulta->obtener_registro();
        $this->id = intval($a['id']);
        // Después de insertar se considera como consultado
        $this->consultado = true;
        // Agregar a la bitácora que hay un nuevo registro
        $msg      = "Nuevo integrante {$this->usuario_nombre} en {$this->departamento_nombre} con {$this->poder_descrito}.";
        $bitacora = new \Bitacora\Registro($this->sesion);
        $bitacora->agregar_nuevo($this->id, $msg);
        // Entregar mensaje
        return $msg;
    } // agregar

    /**
     * Modificar
     *
     * @return string Mensaje
     */
    public function modificar() {
        // Que tenga permiso para modificar
        if (!$this->sesion->puede_modificar('integrantes')) {
            throw new \Exception('Aviso: No tiene permiso para modificar integrantes.');
        }
        // Verificar que haya sido consultado
        if ($this->consultado == false) {
            throw new \Exception('Error: No ha sido consultado el integrante para modificarlo.');
        }
        // Validar
        $this->validar();
        // Hay que determinar que va cambiar, para armar el mensaje
        $original = new Registro($this->sesion);
        try {
            $original->consultar($this->id);
        } catch (\Exception $e) {
            die('Esto no debería pasar. Error al consultar registro original del integrante.');
        }
        $a = array();
        if ($this->usuario != $original->usuario) {
            $a[] = "usuario {$this->usuario_nombre}";
        }
        if ($this->departamento != $original->departamento) {
            $a[] = "departamento {$this->departamento_nombre}";
        }
        if ($this->poder != $original->poder) {
            $a[] = "poder {$this->poder_descrito}";
        }
        if ($this->estatus != $original->estatus) {
            $a[] = "estatus {$this->estatus_descrito}";
        }
        // Si no hay cambios, provoca excepcion de validacion
        if (count($a) == 0) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No hay cambios.');
        } else {
            $msg = "Modificado el integrante {$this->nombre} con ".implode(', ', $a);
        }
        // Actualizar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    integrantes
                SET
                    usuario = %u, departamento = %u, poder = %u, estatus = %s
                WHERE
                    id = %u",
                $this->sql_entero($this->usuario),
                $this->sql_entero($this->departamento),
                $this->sql_entero($this->poder),
                $this->sql_texto($this->estatus),
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al actualizar el integrante. ', $e->getMessage());
        }
        // Agregar a la bitácora que se modificó el registro
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
        if (!$this->sesion->puede_eliminar('integrantes')) {
            throw new \Exception('Aviso: No tiene permiso para eliminar integrantes.');
        }
        // Consultar si no lo esta
        if (!$this->consultado) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'B') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede eliminarse el integrante porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'B';
        $this->modificar();
        // Entregar mensaje
        return "Se ha eliminado el integrante {$this->usuario_nombre} de {$this->departamento_nombre}";
    } // eliminar

    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
        // Que tenga permiso para recuperar
        if (!$this->sesion->puede_recuperar('integrantes')) {
            throw new \Exception('Aviso: No tiene permiso para recuperar integrantes.');
        }
        // Consultar si no lo esta
        if (!$this->consultado) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'A') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede recuperarse el integrante porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'A';
        $this->modificar();
        // Entregar mensaje
        return "Se ha recuperado el integrante {$this->usuario_nombre} de {$this->departamento_nombre}";
    } // recuperar

} // Clase Registro

?>
