<?php
/**
 * GenesisPHP - Autentificaciones Registro
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

namespace Autentificaciones;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $usuario;
    public $usuario_nombre;
    public $fecha;
    public $nom_corto;
    public $tipo;
    public $tipo_descrito;
    public $ip;
    static public $tipo_descripciones = array(
        'I' => 'Datos incorrectos',
        'N' => 'Usuario no encontrado',
        'X' => 'Usuario inactivo',
        'B' => 'Contraseña bloqueda',
        'E' => 'Contraseña equivocada',
        'S' => 'Sesiones máximo',
        'P' => 'No tiene permiso',
        'A' => 'Ingresó',
        'T' => 'Salió');
    static public $tipo_colores = array(
        'I' => 'blanco',
        'N' => 'blanco',
        'X' => 'oscuro',
        'B' => 'naranja',
        'E' => 'rojo',
        'S' => 'amarillo',
        'P' => 'gris',
        'A' => 'verde',
        'T' => 'azul');

    /**
     * Consultar
     *
     * @param integer ID del usuario
     * @param integer Timestamp con la fecha y hora
     */
    public function consultar($in_usuario=false, $in_fecha=false) {
        // Que tenga permiso para consultar
        if (!$this->sesion->puede_ver('autentificaciones')) {
            throw new \Exception('Aviso: No tiene permiso para consultar las autentificaciones.');
        }
        // Parámetro ID
        if ($in_usuario !== false) {
            $this->usuario = $in_usuario;
        }
        if ($in_fecha !== false) {
            $this->fecha = $in_fecha;
        }
        // Validar
        if (is_null($this->usuario) || is_null($this->fecha)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar un registro de la bitácora porque falta el usuario o la fecha y hora.');
        }
        if (!$this->validar_entero($this->usuario)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar un registro de la bitácora porque es incorrecto el usuario.');
        }
        if (!$this->validar_fecha_hora($this->fecha)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar un registro de la bitácora porque es incorrecta la fecha.');
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    nom_corto, tipo, ip
                FROM
                    autentificaciones
                WHERE
                    usuario = %u AND fecha = %s",
                $this->usuario,
                $this->sql_tiempo($this->fecha)));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar la bitácora.', $e->getMessage());
        }
        // Si la consulta no entregó nada
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró el registro en la bitácora.');
        }
        // Obtener resultado de la consulta
        $a = $consulta->obtener_registro();
        // Definir propiedades
        $this->nom_corto     = $a['nom_corto'];
        $this->tipo          = $a['tipo'];
        $this->tipo_descrito = self::$tipo_descripciones[$this->tipo];
        $this->ip            = $a['ip'];
        // Poner como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

} // Clase Registro

?>
