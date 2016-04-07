<?php
/**
 * GenesisPHP - AdmBitacora Registro
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

namespace AdmBitacora;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $id;
    public $usuario;
    public $usuario_nom_corto;
    public $fecha;
    public $pagina;
    public $pagina_id;
    public $tipo;
    public $tipo_descrito;
    public $url;
    public $notas;
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
        // Parametros
        if ($in_id !== false) {
            $this->id = $in_id;
        }
        // Validar
        if (!$this->validar_entero($this->id)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar la bitácora por ID incorrecto.');
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    b.usuario, u.nom_corto AS usuario_nom_corto,
                    to_char(b.fecha, 'YYYY-MM-DD, HH24:MI:SS') as fecha,
                    b.pagina, b.pagina_id, b.tipo, b.url, b.notas
                FROM
                    adm_bitacora AS b,
                    adm_usuarios AS u
                WHERE
                    b.usuario = u.id
                    AND b.id = %d",
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar la bitácora.', $e->getMessage());
        }
        // Si la consulta no entrego registros
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró el registro en la bitácora.');
        }
        // Definir propiedades
        $a = $consulta->obtener_registro();
        $this->usuario           = intval($a['usuario']);
        $this->usuario_nom_corto = $a['usuario_nom_corto'];
        $this->fecha             = $a['fecha'];
        $this->pagina            = $a['pagina'];
        $this->pagina_id         = $a['pagina_id'];
        $this->tipo              = $a['tipo'];
        $this->tipo_descrito     = self::$tipo_descripciones[$this->tipo];
        $this->url               = $a['url'];
        $this->notas             = $a['notas'];
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

    /**
     * Validar
     */
    public function validar() {
        // Tomamos datos de la sesion
        $this->usuario           = $this->sesion->usuario;
        $this->usuario_nom_corto = $this->sesion->nom_corto;
        $this->pagina            = $this->sesion->pagina;
        // Validaciones
        if (($this->pagina_id != null) && !$this->validar_entero($this->pagina_id)) {
            throw new \Base\RegistroExceptionValidacion('Error en bitácora: Número para pagina_id incorrecto.');
        }
        if (!array_key_exists($this->tipo, self::$tipo_descripciones)) {
            throw new \Base\RegistroExceptionValidacion('Error en bitácora: Tipo incorrecto.');
        }
        // Si no esta definido el url, lo hacemos
        if ($this->url == '') {
            $this->url = $_SERVER['PHP_SELF'];
            if ($this->pagina_id != null) {
                $this->url .= '?id='.$this->pagina_id;
            }
        }
        // Validar notas
        if (($this->notas != '') && !$this->validar_notas($this->notas)) {
            // throw new \Base\RegistroExceptionValidacion('Error en bitácora: Notas incorrectas.');
            $this->notas = 'Error en Bitácora: Notas incorrectas.';
        }
    } // validar

    /**
     * Agregar
     *
     * @return string Mensaje
     */
    public function agregar() {
        // Validar
        $this->validar();
        // Saltarse el usuario con id 1, es decir, sistema
        if ($this->usuario == 1) {
            return;
        }
        // Insertamos el registro
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    adm_bitacora (usuario, pagina, pagina_id, tipo, url, notas)
                VALUES
                    (%d, %s, %s, %s, %s, %s)",
                $this->usuario,
                $this->sql_texto($this->pagina),
                $this->sql_entero($this->pagina_id),
                $this->sql_texto($this->tipo),
                $this->sql_texto($this->url),
                $this->sql_texto($this->notas)), true); // Tiene el true para tronar en caso de error
        } catch (\Exception $e) {
            die("Error en bitácora: Al tratar de insertar un registro.");
        }
    } //agregar

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
