<?php
/**
 * GenesisPHP - Usuarios Registro
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

namespace AdmUsuarios;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $id;
    public $nom_corto;
    public $nombre;
    public $puesto;
    public $tipo;
    public $tipo_descrito;
    public $email;
    public $contrasena_fallas;
    public $contrasena_expira;
    public $contrasena_descrito;
    public $sesiones_maximas;
    public $sesiones_contador;
    public $sesiones_ultima;
    public $sesiones_descrito;
    public $listado_renglones;
    public $notas;
    public $estatus;
    public $estatus_descrito;
    public $contrasena_no_cifrada     = false;
    public $esta_bloqueada            = false;
    public $bloqueada_porque_fallas   = false;
    public $bloqueada_porque_expiro   = false;
    public $bloqueada_porque_sesiones = false;
    public $contrasena_no_cifrada_descrito;
    public $bloqueada_porque_fallas_descrito;
    public $bloqueada_porque_expiro_descrito;
    public $bloqueada_porque_sesiones_descrito;
    protected $contrasena; // PARA RECIBIR LA CONTRASEÑA NO CIFRADA DEL FORMULARIO
    static public $contrasena_colores = array(
        'A' => 'blanco',
        'I' => 'gris',
        'N' => 'amarillo',
        'B' => 'rojo');
    static public $expira_en_colores = array(
        'A' => 'blanco',
        'I' => 'gris',
        'E' => 'rojo');
    static public $sesiones_contador_colores = array(
        'A' => 'blanco',
        'I' => 'gris',
        'B' => 'rojo');
    static public $tipo_descripciones = array(
        'A' => 'Administrador',
        'U' => 'Usuario');
    static public $tipo_colores = array(
        'A' => 'azul',
        'U' => 'amarillo');
    static public $estatus_descripciones = array(
        'A' => 'EN USO',
        'B' => 'ELIMINADO');
    static public $estatus_colores = array(
        'A' => 'blanco',
        'B' => 'gris');
    static public $dias_expira_contrasena = 30;

    /**
     * Consultar
     *
     * @param integer ID del registro
     */
    public function consultar($in_id=false) {
        // Que tenga permiso para consultar
        if (!$this->sesion->puede_ver('usuarios')) {
            throw new \Exception('Aviso: No tiene permiso para consultar usuarios.');
        }
        // Parametros
        if ($in_id !== false) {
            $this->id = $in_id;
        }
        // Validar
        if (!validar_entero($this->id)) {
            throw new \Base\RegistroExceptionValidacion('Error: Al consultar el usuario por ID incorrecto.');
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    nom_corto, nombre, puesto, tipo, email,
                    contrasena, contrasena_encriptada, contrasena_fallas, contrasena_expira,
                    sesiones_maximas, sesiones_contador,
                    to_char(sesiones_ultima, 'YYYY-MM-DD, HH24:MI') as sesiones_ultima,
                    listado_renglones,
                    notas,
                    estatus
                FROM
                    usuarios
                WHERE
                    id = %d",
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar el usuario.', $e->getMessage());
        }
        // Si la consulta no entrego registros
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró al usuario.');
        }
        // Resultado de la consulta
        $a = $consulta->obtener_registro();
        // Validar que si esta eliminado tenga permiso para consultarlo
        if (($a['estatus'] == 'B') && !$this->sesion->puede_recuperar('usuarios')) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No tiene permiso de consultar un registro eliminado.');
        }
        // Definir propiedades
        $this->nombre            = $a['nombre'];
        $this->nom_corto         = $a['nom_corto'];
        $this->puesto            = $a['puesto'];
        $this->tipo              = $a['tipo'];
        $this->tipo_descrito     = self::$tipo_descripciones[$this->tipo];
        $this->email             = $a['email'];
        $this->contrasena_fallas = $a['contrasena_fallas'];
        $this->contrasena_expira = $a['contrasena_expira'];
        $this->sesiones_maximas  = $a['sesiones_maximas'];
        $this->sesiones_contador = intval($a['sesiones_contador']);
        $this->sesiones_ultima   = $a['sesiones_ultima'];
        $this->listado_renglones = intval($a['listado_renglones']);
        $this->notas             = $a['notas'];
        $this->estatus           = $a['estatus'];
        $this->estatus_descrito  = self::$estatus_descripciones[$this->estatus];
        // Fechas
        $hoy    = floor(strtotime(date('Y-m-d'))/(60*60*24));
        $expira = floor(strtotime($this->contrasena_expira)/(60*60*24));
        // Banderas de bloqueos
        $this->contrasena_no_cifrada     = ($a['contrasena_encriptada'] == '');
        $this->bloqueada_porque_fallas   = ($this->contrasena_fallas >= \Inicio\Autentificar::$fallas_para_bloquear);
        $this->bloqueada_porque_expiro   = ($hoy >= $expira);
        $this->bloqueada_porque_sesiones = ($this->sesiones_contador >= $this->sesiones_maximas);
        if ($this->estatus == 'A') {
            $this->esta_bloqueada = ($this->bloqueada_porque_fallas || $this->bloqueada_porque_expiro || $this->bloqueada_porque_sesiones);
        } else {
            $this->esta_bloqueada = true;
        }
        // Descrito contraseña no cifrada
        if ($this->contrasena_no_cifrada) {
            $this->contrasena_no_cifrada_descrito = 'NO CIFRADA. Solicite al usuario que la cambie.';
        }
        if ($this->bloqueada_porque_expiro) {
            $this->bloqueada_porque_expiro_descrito = 'BLOQUEADA porque expiró.';
        }
        if ($this->bloqueada_porque_fallas) {
            $this->bloqueada_porque_fallas_descrito = 'BLOQUEADA porque se equivocó muchas veces o porque expiró.';
        }
        if ($this->bloqueada_porque_sesiones) {
            $this->bloqueada_porque_sesiones_descrito = 'BLOQUEADA porque llegó al máximo de sesiones por hoy.';
        }
        // Sesiones descrito
        if ($this->estatus == 'A') {
            if ($this->sesiones_contador == 0) {
                $this->sesiones_descrito = 'Hoy no ha ingresado';
            } elseif ($this->bloqueada_porque_sesiones) {
                $this->sesiones_descrito = 'BLOQUEADA';
            } elseif ($this->sesiones_contador == 1) {
                $this->sesiones_descrito = "Ha ingresado una vez hoy; el máximo es {$this->sesiones_maximas}.";
            } else {
                $this->sesiones_descrito = "Ha ingresado {$this->sesiones_contador} veces hoy; el máximo es {$this->sesiones_maximas}.";
            }
        } else {
            $this->sesiones_descrito = 'INACTIVO';
        }
        // Contraseña descrito
        if ($this->estatus == 'A') {
            if ($this->bloqueada_porque_expiro) {
                if ($expira < $hoy) {
                    $this->contrasena_descrito = 'EXPIRÓ HACE '.($hoy - $expira).' DÍAS';
                } elseif ($expira == $hoy) {
                    $this->contrasena_descrito = 'EXPIRÓ HOY';
                }
            } else {
                $s[] = 'Faltan '.($expira - $hoy).' días para que expire.';
            }
        } else {
            $this->contrasena_descrito = 'INACTIVO';
        }
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

    /**
     * Validar
     */
    public function validar() {
    } // validar

    /**
     * Nuevo
     */
    public function nuevo() {
    } // nuevo

    /**
     * Agregar
     *
     * @return string Mensaje
     */
    public function agregar() {
    } //agregar

    /**
     * Modificar
     *
     * @return string Mensaje
     */
    public function modificar() {
    } // modificar

    /**
     * Eliminar
     *
     * @return string Mensaje
     */
    public function eliminar() {
    } // eliminar

    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
    } // recuperar

    /**
     * Desbloquear
     *
     * @return string Mensaje
     */
    public function desbloquear() {
    } // desbloquear

} // Clase Registro

?>
