<?php
/**
 * GenesisPHP - AdmModulos Registro
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

namespace AdmModulos;

/**
 * Clase Registro
 */
class Registro extends \Base2\Registro {

    // protected $sesion;
    // protected $consultado;
    public $id;
    public $orden;
    public $clave;
    public $nombre;
    public $pagina;
    public $icono;
    public $padre;
    public $padre_nombre;
    public $permiso_maximo;
    public $permiso_maximo_descrito;
    public $poder_minimo;
    public $poder_minimo_descrito;
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
    static public $poder_minimo_descripciones = array(
        '1' => 'DESDE 1',
        '2' => 'DESDE 2',
        '3' => 'DESDE 3',
        '4' => 'DESDE 4',
        '5' => 'DESDE 5',
        '6' => 'DIRECTORES',
        '7' => 'WEBMASTERS');
    static public $poder_minimo_colores = array(
        '1' => 'nivel1',
        '2' => 'nivel2',
        '3' => 'nivel3',
        '4' => 'nivel4',
        '5' => 'nivel5',
        '6' => 'nivel6',
        '7' => 'nivel7');
    static public $estatus_descripciones = array(
        'A' => 'EN USO',
        'B' => 'ELIMINADO');
    static public $estatus_colores = array(
        'A' => 'blanco',
        'B' => 'gris');

    /**
     * Consultar padre
     *
     * @param  integer ID del registro
     * @return string  Nombre del padre del módulo, si no lo tiene entrega un texto vacío
     */
    protected function consultar_padre($in_id) {
        // Si no tiene padre, terminar entregando texto vacio
        if ($in_id == '') {
            return '';
        }
        // Validar
        if (!\Base2\UtileriasParaValidar::validar_entero($in_id)) {
            throw new \Base2\RegistroExceptionValidacion('Error: Al consultar el padre del módulo por ID incorrecto.');
        }
        // Consultar
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    nombre
                FROM
                    adm_modulos
                WHERE
                    id = %d",
                $in_id));
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar el padre del módulo.', $e->getMessage());
        }
        // Si la consulta no entrego registros
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base2\RegistroExceptionNoEncontrado('Aviso: No se encontró al padre del módulo.');
        }
        // Definir propiedades
        $a = $consulta->obtener_registro();
        // Entregar nombre del padre
        return $a['nombre'];
    } // consultar_padre

    /**
     * Consultar
     *
     * @param integer ID del registro
     */
    public function consultar($in_id=false) {
        // Que tenga permiso para consultar
        if (!$this->sesion->puede_ver('adm_modulos')) {
            throw new \Exception('Aviso: No tiene permiso para consultar los módulos.');
        }
        // Parametros
        if ($in_id !== false) {
            $this->id = $in_id;
        }
        // Validar
        if (!\Base2\UtileriasParaValidar::validar_entero($this->id)) {
            throw new \Base2\RegistroExceptionValidacion('Error: Al consultar el módulo por ID incorrecto.');
        }
        // Consultar
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    orden, clave, nombre, pagina, icono, padre, permiso_maximo, poder_minimo, estatus
                FROM
                    adm_modulos
                WHERE
                    id = %d",
                $this->id));
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar módulo.', $e->getMessage());
        }
        // Si la consulta no entrego registros
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base2\RegistroExceptionNoEncontrado('Aviso: No se encontró al módulo.');
        }
        // Resultado de la consulta
        $a = $consulta->obtener_registro();
        // Validar que si esta eliminado tenga permiso para consultarlo
        if (($a['estatus'] == 'B') && !$this->sesion->puede_recuperar('adm_modulos')) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: No tiene permiso de consultar un registro eliminado.');
        }
        // Definir propiedades
        $this->orden                   = intval($a['orden']);
        $this->clave                   = $a['clave'];
        $this->nombre                  = $a['nombre'];
        $this->pagina                  = $a['pagina'];
        $this->icono                   = $a['icono'];
        $this->padre                   = $a['padre'];
        $this->padre_nombre            = $this->consultar_padre($this->padre); // PUEDE PROVOCAR UNA EXCEPCION
        $this->permiso_maximo          = intval($a['permiso_maximo']);
        $this->permiso_maximo_descrito = self::$permiso_maximo_descripciones[$this->permiso_maximo];
        $this->poder_minimo            = intval($a['poder_minimo']);
        $this->poder_minimo_descrito   = self::$poder_minimo_descripciones[$this->poder_minimo];
        $this->estatus                 = $a['estatus'];
        $this->estatus_descrito        = self::$estatus_descripciones[$this->estatus];
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

    /**
     * Encabezado
     *
     * @return string Encabezado
     */
    public function encabezado() {
        return "Módulo {$this->nombre}";
    } // encabezado

    /**
     * Validar
     */
    public function validar() {
        // Validamos las propiedades
        if (!\Base2\UtileriasParaValidar::validar_entero($this->orden)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Número de orden incorrecto.');
        }
        if (!\Base2\UtileriasParaValidar::validar_nombre($this->clave)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Clave incorrecta.');
        }
        if (!\Base2\UtileriasParaValidar::validar_nombre($this->nombre)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Nombre incorrecto.');
        }
        if (!\Base2\UtileriasParaValidar::validar_nombre($this->pagina)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Página incorrecta.');
        }
        if (!\Base2\UtileriasParaValidar::validar_nombre($this->icono)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Icono incorrecto.');
        }
        if ($this->padre != '') {
            $this->padre_nombre = $this->consultar_padre($this->padre);
        } else {
            $this->padre_nombre = '';
        }
        if (!array_key_exists($this->permiso_maximo, self::$permiso_maximo_descripciones)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Permiso máximo incorrecto.');
        }
        if (!array_key_exists($this->poder_minimo, self::$poder_minimo_descripciones)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Poder mínimo incorrecto.');
        }
        if (!array_key_exists($this->estatus, self::$estatus_descripciones)) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: Estatus incorrecto.');
        }
        // Definimos los descritos
        $this->permiso_maximo_descrito = self::$permiso_maximo_descripciones[$this->permiso_maximo];
        $this->poder_minimo_descrito   = self::$poder_minimo_descripciones[$this->poder_minimo];
        $this->estatus_descrito        = self::$estatus_descripciones[$this->estatus];
    } // validar

    /**
     * Nuevo
     */
    public function nuevo() {
        // Que tenga permiso para agregar
        if (!$this->sesion->puede_agregar('adm_modulos')) {
            throw new \Exception('Aviso: No tiene permiso para agregar módulos.');
        }
        // Definir propiedades
        $this->id                      = 'agregar';
        $this->orden                   = '';
        $this->clave                   = '';
        $this->nombre                  = '';
        $this->pagina                  = '';
        $this->icono                   = '';
        $this->padre                   = '';
        $this->permiso_maximo          = '';
        $this->permiso_maximo_descrito = '';
        $this->poder_minimo            = '';
        $this->poder_minimo_descrito   = '';
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
        if (!$this->sesion->puede_agregar('adm_modulos')) {
            throw new \Exception('Aviso: No tiene permiso para agregar módulos.');
        }
        // Verificar que no haya sido consultado
        if ($this->consultado == true) {
            throw new \Exception('Error: Ha sido consultado el módulo, no debe estarlo.');
        }
        // Validar
        $this->validar();
        // Insertar registro en la base de datos
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO adm_modulos
                    (orden, clave, nombre, pagina, icono, padre, permiso_maximo, poder_minimo)
                VALUES
                    (%s, %s, %s, %s, %s, %s, %s, %s)",
                \Base2\UtileriasParaSQL::sql_entero($this->orden),
                \Base2\UtileriasParaSQL::sql_texto($this->clave),
                \Base2\UtileriasParaSQL::sql_texto($this->nombre),
                \Base2\UtileriasParaSQL::sql_texto($this->pagina),
                \Base2\UtileriasParaSQL::sql_texto($this->icono),
                \Base2\UtileriasParaSQL::sql_texto($this->padre),
                \Base2\UtileriasParaSQL::sql_entero($this->permiso_maximo),
                \Base2\UtileriasParaSQL::sql_entero($this->poder_minimo)));
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error: Al insertar el módulo. ', $e->getMessage());
        }
        // Obtener el id del registro recién insertado
        try {
            $consulta = $base_datos->comando("
                SELECT
                    last_value AS id
                FROM
                    adm_modulos_id_seq");
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error: Al obtener el ID del módulo. ', $e->getMessage());
        }
        $a        = $consulta->obtener_registro();
        $this->id = intval($a['id']);
        // Despues de insertar se considera como consultado
        $this->consultado = true;
        // Elaborar mensaje
        $msg = "Nuevo módulo {$this->nombre}.";
        // Agregar a la bitacora que hay un nuevo registro
        $bitacora = new \AdmBitacora\Registro($this->sesion);
        $bitacora->agregar_nuevo($this->id, $msg);
        // Entregar
        return $msg;
    } //agregar

    /**
     * Modificar
     *
     * @return string Mensaje
     */
    public function modificar() {
        // Que tenga permiso para modificar
        if (!$this->sesion->puede_modificar('adm_modulos')) {
            throw new \Exception('Aviso: No tiene permiso para modificar módulos.');
        }
        // Verificar que haya sido consultado
        if ($this->consultado == false) {
            throw new \Exception('Error: No ha sido consultado el módulo para modificarlo.');
        }
        // Validar
        $this->validar();
        // Hay que determinar que va cambiar, para armar el mensaje
        $original = new Registro($this->sesion);
        try {
            $original->consultar($this->id);
        } catch (\Exception $e) {
            die('Esto no debería pasar. Error al consultar registro original del módulo.');
        }
        $a = array();
        if ($this->orden != $original->orden) {
            $a[] = "orden {$this->orden}";
        }
        if ($this->clave != $original->clave) {
            $a[] = "clave {$this->clave}";
        }
        if ($this->nombre != $original->nombre) {
            $a[] = "nombre {$this->nombre}";
        }
        if ($this->pagina != $original->pagina) {
            $a[] = "pagina {$this->pagina}";
        }
        if ($this->icono != $original->icono) {
            $a[] = "ícono {$this->icono}";
        }
        if ($this->padre != $original->padre) {
            $a[] = "padre {$this->padre}";
        }
        if ($this->permiso_maximo != $original->permiso_maximo) {
            $a[] = "permiso máximo {$this->permiso_maximo}";
        }
        if ($this->poder_minimo != $original->poder_minimo) {
            $a[] = "poder mínimo {$this->poder_minimo}";
        }
        if ($this->estatus != $original->estatus) {
            $a[] = "estatus {$this->estatus_descrito}";
        }
        // Si no hay cambios, provoca excepcion de validacion
        if (count($a) == 0) {
            throw new \Base2\RegistroExceptionValidacion('Aviso: No hay cambios.');
        } else {
            $msg = "Modificado el módulo {$this->nombre} con ".implode(', ', $a);
        }
        // Actualizar registro en la base de datos
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_modulos
                SET
                    orden = %s, clave = %s, nombre = %s, pagina = %s, icono = %s, padre = %s, permiso_maximo = %s, poder_minimo = %s, estatus = %s
                WHERE
                    id = %d",
                \Base2\UtileriasParaSQL::sql_entero($this->orden),
                \Base2\UtileriasParaSQL::sql_texto($this->clave),
                \Base2\UtileriasParaSQL::sql_texto($this->nombre),
                \Base2\UtileriasParaSQL::sql_texto($this->pagina),
                \Base2\UtileriasParaSQL::sql_texto($this->icono),
                \Base2\UtileriasParaSQL::sql_texto($this->padre),
                \Base2\UtileriasParaSQL::sql_entero($this->permiso_maximo),
                \Base2\UtileriasParaSQL::sql_entero($this->poder_minimo),
                \Base2\UtileriasParaSQL::sql_texto($this->estatus),
                $this->id));
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error: Al actualizar el módulo. ', $e->getMessage());
        }
        // Agregar a la bitacora que se modifico el registro
        $bitacora = new \AdmBitacora\Registro($this->sesion);
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
        if (!$this->sesion->puede_eliminar('adm_modulos')) {
            throw new \Exception('Aviso: No tiene permiso para eliminar el módulo.');
        }
        // Consultar si no lo esta
        if ($this->consultado == false) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'B') {
            throw new \Base2\RegistroExceptionValidacion('Aviso: No puede eliminarse el módulo porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'B';
        $this->modificar();
        // Entregar mensaje
        return "Se ha eliminado el módulo {$this->nombre}";
    } // eliminar

    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
        // Que tenga permiso para recuperar
        if (!$this->sesion->puede_recuperar('adm_modulos')) {
            throw new \Exception('Aviso: No tiene permiso para recuperar el módulo.');
        }
        // Consultar si no lo esta
        if ($this->consultado == false) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'A') {
            throw new \Base2\RegistroExceptionValidacion('Aviso: No puede recuperarse el módulo porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'A';
        $this->modificar();
        // Entregar mensaje
        return "Se ha recuperado el módulo {$this->nombre}";
    } // recuperar

} // Clase Registro

?>
