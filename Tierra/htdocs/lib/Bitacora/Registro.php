<?php
/**
 * GenesisPHP - Bitacora Registro
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

namespace Bitacora;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $id;
    public $usuario;
    public $usuario_nombre;
    public $fecha;
    public $pagina;
    public $pagina_id;
    public $tipo;
    public $tipo_descrito;
    static public $tipo_descripciones = array(
        'A' => 'Agregó',
        'B' => 'Buscó',
        'D' => 'Vio detalle',
        'E' => 'Exportó',
        'F' => 'Sin Fotografía',
        'J' => 'Formulario ya recibido',
        'K' => 'Formulario no válido',
        'L' => 'Eliminó',
        'M' => 'Modificó',
        'P' => 'Cambió su contraseña',
        'R' => 'Recuperó',
        'S' => 'Sistema',
        'X' => 'Error SQL',
        'Y' => 'No encontrado',
        'Z' => 'Dado de baja');
    static public $tipo_colores = array(
        'A' => 'verde',
        'B' => 'oscuro',
        'D' => 'naranja',
        'E' => 'naranja',
        'F' => 'rosa',
        'J' => 'amarillo',
        'K' => 'amarillo',
        'L' => 'rojo',
        'M' => 'azul',
        'P' => 'rosa',
        'R' => 'amarillo',
        'S' => 'gris',
        'X' => 'rojo',
        'Y' => 'amarillo',
        'Z' => 'rojo');

    /**
     * Consultar
     *
     * @param integer ID del registro
     */
    public function consultar($in_id=false) {
        // Que tenga permiso para consultar
        if (!$this->sesion->puede_ver('bitacora')) {
            throw new \Exception('Aviso: No tiene permiso para consultar la bitácora.');
        }
        // Parámetro ID
        if ($in_id !== false) {
            $this->id = $in_id;
        }
        // Validar
        // Consultar
        // Si la consulta no entregó nada
        // Obtener resultado de la consulta
        $a = $consulta->obtener_registro();
        // Si esta eliminado, debe tener permiso para consultarlo
        // Definir propiedades
        // Poner como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

    /**
     * Validar
     */
    public function validar() {
        // Validar las propiedades
        // Definir el estatus descrito
    } // validar

    /**
     * Agregar
     */
    public function agregar() {
        // Que tenga permiso para agregar
        // Verificar que NO haya sido consultado
        // Validar
        $this->validar();
        // Insertar registro en la base de datos
        // Obtener el ID del registro recién insertado
        // Después de insertar se considera como consultado
        $this->consultado = true;
        // Agregar a la bitácora que hay un nuevo registro
        // Entregar mensaje
        return $msg;
    } // agregar

    /**
     * Agregar No Encontrado
     *
     * @param string Notas
     */
    public function agregar_no_encontrado($in_notas) {
        $this->pagina_id = '';
        $this->notas     = $in_notas;
        $this->tipo      = 'Y'; // 'No encontrado'
        $this->agregar();
    } // agregar_no_encontrado

    /**
     * Agregar Dado de Baja
     *
     * @param string Notas
     */
    public function agregar_dado_de_baja($in_notas) {
        $this->pagina_id = '';
        $this->notas     = $in_notas;
        $this->tipo      = 'Z'; // 'Dado de baja'
        $this->agregar();
    } // agregar_dado_de_baja

    /**
     * Agregar Sin Fotografía
     *
     * @param string Notas
     */
    public function agregar_sin_fotografia($in_notas) {
        $this->pagina_id = '';
        $this->notas     = $in_notas;
        $this->tipo      = 'F'; // 'Sin Fotografía'
        $this->agregar();
    } // agregar_sin_fotografia

    /**
     * Agregar nuevo
     *
     * @param integer ID del registro
     * @param string  Notas
     */
    public function agregar_nuevo($in_id, $in_notas) {
        $this->pagina_id = $in_id;
        $this->notas     = $in_notas;
        $this->tipo      = 'A'; // 'Agregó'
        $this->agregar();
    } // agregar_nuevo

    /**
     * Agregar Modificado
     *
     * @param integer ID del registro
     * @param string  Notas
     */
    public function agregar_modificado($in_id, $in_notas) {
        $this->pagina_id = $in_id;
        $this->notas     = $in_notas;
        $this->tipo      = 'M'; // 'Modificó'
        $this->agregar();
    } // agregar_modificado

    /**
     * Agregar Eliminó
     *
     * @param integer ID del registro
     * @param string  Notas
     */
    public function agregar_elimino($in_id, $in_notas) {
        $this->pagina_id = $in_id;
        $this->notas     = $in_notas;
        $this->tipo      = 'L'; // 'Eliminó'
        $this->agregar();
    } // agregar_elimino

    /**
     * Agregar Recuperó
     *
     * @param integer ID del registro
     * @param string  Notas
     */
    public function agregar_recupero($in_id, $in_notas) {
        $this->pagina_id = $in_id;
        $this->notas     = $in_notas;
        $this->tipo      = 'R'; // 'Recuperó'
        $this->agregar();
    } // agregar_recupero

    /**
     * Agregar Buscó
     * @param string  Notas
     */
    public function agregar_busco($in_notas) {
        $this->notas     = $in_notas;
        $this->tipo      = 'B'; // 'Buscó'
        $this->agregar();
    } // agregar_busco

    /**
     * Agregar Vio Detalle
     *
     * @param integer ID del registro
     * @param string  Notas
     */
    public function agregar_vio_detalle($in_id, $in_notas) {
        $this->pagina_id = $in_id;
        $this->notas     = $in_notas;
        $this->tipo      = 'D'; // 'Vio detalle'
        $this->agregar();
    } // agregar_vio_detalle

    /**
     * Agregar Exportó
     *
     * @param integer ID del registro
     * @param string  Notas
     */
    public function agregar_exporto($in_id, $in_notas) {
        $this->pagina_id = $in_id;
        $this->notas     = $in_notas;
        $this->tipo      = 'E'; // 'Exportó'
        $this->agregar();
    } // agregar_exporto

    /**
     * Agregar Cambió Contraseña
     */
    public function agregar_cambio_contrasena() {
        $this->notas = "{$this->sesion->nombre} cambió su contraseña.";
        $this->tipo  = 'P'; // 'Cambió su contraseña'
        $this->agregar();
    } // agregar_cambio_contrasena

    /**
     * Agregar Sistema
     *
     * @param string Notas
     */
    public function agregar_sistema($in_notas) {
        $this->notas = $in_notas;
        $this->tipo  = 'S'; // 'Sistema'
        $this->agregar();
    } // agregar_sistema

    /**
     * Agregar Error SQL
     *
     * @param string Notas
     */
    public function agregar_error_sql($in_notas) {
        $this->notas = $in_notas;
        $this->tipo  = 'X'; // 'Error SQL'
        $this->agregar();
    } // agregar_error_sql

} // Clase Registro

?>
