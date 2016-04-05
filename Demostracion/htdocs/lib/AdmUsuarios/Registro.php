<?php
/**
 * GenesisPHP - AdmUsuarios Registro
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
        if (!$this->validar_entero($this->id)) {
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
                    adm_usuarios
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
            $this->esta_bloqueada = false;
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
        // Validamos las propiedades
        if (!$this->validar_nom_corto($this->nom_corto)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Nombre corto incorrecto.');
        }
        $this->nom_corto = strtolower($this->nom_corto);
        if (!$this->validar_nombre($this->nombre)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Nombre incorrecto.');
        }
        if (($this->puesto != '') && !$this->validar_nombre($this->puesto)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Puesto incorrecto.');
        }
        if (!array_key_exists($this->tipo, self::$tipo_descripciones)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Tipo incorrecto.');
        }
        if (!$this->validar_email($this->email)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Correo electrónico incorrecto.');
        }
        if (($this->contrasena != '') && !$this->validar_contrasena($this->contrasena)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Contraseña incorrecta.');
        }
        if (!$this->validar_entero($this->sesiones_maximas)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: La cantidad de ingresos por día es incorrecta.');
        }
        if (!$this->validar_entero($this->listado_renglones)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: La cantidad de renglones en los listados es incorrecta.');
        }
        if (($this->notas != '') && !$this->validar_nombre($this->notas)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: La nota es incorrecta.');
        }
        if (!array_key_exists($this->estatus, self::$estatus_descripciones)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Estatus incorrecto.');
        }
        // Definimos los descritos
        $this->tipo_descrito    = self::$tipo_descripciones[$this->tipo];
        $this->estatus_descrito = self::$estatus_descripciones[$this->estatus];
    } // validar

    /**
     * Nuevo
     */
    public function nuevo() {
        // Que tenga permiso para agregar
        if (!$this->sesion->puede_agregar('usuarios')) {
            throw new \Exception('Aviso: No tiene permiso para agregar usuarios.');
        }
        // Definir propiedades
        $this->id = 'agregar';
        // General
        $this->nombre        = '';
        $this->nom_corto     = '';
        $this->puesto        = '';
        $this->tipo          = 'O';
        $this->tipo_descrito = self::$tipo_descripciones[$this->tipo];
        $this->email         = '';
        // Sesion
        $this->sesiones_maximas  = 10;
        $this->listado_renglones = 10;
        $this->notas             = '';
        $this->estatus           = 'A';
        $this->estatus_descrito  = self::$estatus_descripciones[$this->estatus];
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
        if (!$this->sesion->puede_agregar('usuarios')) {
            throw new \Exception('Aviso: No tiene permiso para agregar usuarios.');
        }
        // Verificar que no haya sido consultado
        if ($this->consultado == true) {
            throw new \Exception('Error: Ha sido consultado el usuario, no debe estarlo.');
        }
        // Validar
        $this->validar();
        // Validar contraseña, no debe estar vacía
        if (($this->contrasena == '') || !$this->validar_contrasena($this->contrasena)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Contraseña incorrecta.');
        }
        // Insertar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                INSERT INTO
                    adm_usuarios
                    (nom_corto, nombre, puesto, tipo, email, contrasena, sesiones_maximas, listado_renglones, notas, estatus)
                VALUES
                    (%s, %s, %s, %s, %s, %s, %d, %d, %s, %s)",
                $this->sql_texto($this->nom_corto),
                $this->sql_texto($this->nombre),
                $this->sql_texto($this->puesto),
                $this->sql_texto($this->tipo),
                $this->sql_texto($this->email),
                $this->sql_texto($this->contrasena),
                $this->sesiones_maximas,
                $this->listado_renglones,
                $this->sql_texto($this->notas),
                $this->sql_texto($this->estatus)));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al insertar el usuario. ', $e->getMessage());
        }
        // Obtener el id del registro recién insertado
        try {
            $consulta = $base_datos->comando("
                SELECT
                    last_value AS id
                FROM
                    adm_usuarios_id_seq");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al obtener el ID del usuario. ', $e->getMessage());
        }
        $a        = $consulta->obtener_registro();
        $this->id = intval($a['id']);
        // Despues de insertar se considera como consultado
        $this->consultado = true;
        // Elborar mensaje
        $msg = "Nuevo usuario {$this->nombre}.";
        // Agregar a la bitacora que hay un nuevo registro
        $bitacora = new \AdmBitacora\Registro($this->sesion);
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
        if (!$this->sesion->puede_modificar('usuarios')) {
            throw new \Exception('Aviso: No tiene permiso para modificar usuarios.');
        }
        // Verificar que haya sido consultado
        if ($this->consultado == false) {
            throw new \Exception('Error: No ha sido consultado el usuario para modificarlo.');
        }
        // Validar
        $this->validar();
        // Hay que determinar que va cambiar, para armar el mensaje
        $original = new Registro($this->sesion);
        try {
            $original->consultar($this->id);
        } catch (\Exception $e) {
            die('Esto no debería pasar. Error al consultar registro original del usuario.');
        }
        $a = array();
        if ($this->nom_corto != $original->nom_corto) {
            $a[] = "nombre corto {$this->nom_corto}";
        }
        if ($this->nombre != $original->nombre) {
            $a[] = "nombre {$this->nombre}";
        }
        if ($this->puesto != $original->puesto) {
            $a[] = "puesto {$this->puesto}";
        }
        if ($this->tipo != $original->tipo) {
            $a[] = "tipo {$this->tipo_descrito}";
        }
        if ($this->email != $original->email) {
            $a[] = "e-mail {$this->email}";
        }
        if ($this->contrasena != '') {
            $a[] = "nueva contraseña";
        }
        if ($this->sesiones_maximas != $original->sesiones_maximas) {
            $a[] = "sesiones máximas {$this->sesiones_maximas}";
        }
        if ($this->listado_renglones != $original->listado_renglones) {
            $a[] = "listados renglones {$this->listado_renglones}";
        }
        if ($this->notas != $original->notas) {
            $a[] = "notas {$this->notas}";
        }
        if ($this->estatus != $original->estatus) {
            $a[] = "estatus {$this->estatus_descrito}";
        }
        // Si no hay cambios, provoca excepcion de validacion
        if (count($a) == 0) {
            throw new \Base\RegistroExceptionValidacion('Aviso: No hay cambios.');
        } else {
            $msg = "Modificado el usuario {$this->nom_corto} con ".implode(', ', $a);
        }
        // Actualizar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_usuarios
                SET
                    nom_corto = %s, nombre = %s, puesto = %s, tipo = %s, email = %s,
                    sesiones_maximas = %d, listado_renglones = %d,
                    notas = %s, estatus = %s
                WHERE
                    id = %d",
                $this->sql_texto($this->nom_corto),
                $this->sql_texto($this->nombre),
                $this->sql_texto($this->puesto),
                $this->sql_texto($this->tipo),
                $this->sql_texto($this->email),
                $this->sesiones_maximas,
                $this->listado_renglones,
                $this->sql_texto($this->notas),
                $this->sql_texto($this->estatus),
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al actualizar el usuario. ', $e->getMessage());
        }
        // Actualizar contraseña, si la define
        if ($this->contrasena != '') {
            try {
                $base_datos->comando(sprintf("
                    UPDATE
                        usuarios
                    SET
                        contrasena=%s,
                        contrasena_encriptada=NULL,
                        contrasena_fallas=0,
                        contrasena_expira=((('now'::text)::date + '30 days'::interval))::date
                    WHERE
                        id = %d",
                    $this->sql_texto($this->contrasena),
                    $this->id));
            } catch (\Exception $e) {
                throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al actualizar el usuario. ', $e->getMessage());
            }
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
        if (!$this->sesion->puede_eliminar('usuarios')) {
            throw new \Exception('Aviso: No tiene permiso para eliminar usuarios.');
        }
        // Consultar si no lo esta
        if ($this->consultado == false) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'B') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede eliminarse el usuario porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'B';
        $this->modificar();
        // Entregar mensaje
        return "Se ha eliminado el usuario {$this->nombre}";
    } // eliminar

    /**
     * Recuperar
     *
     * @return string Mensaje
     */
    public function recuperar() {
        // Que tenga permiso para recuperar
        if (!$this->sesion->puede_recuperar('usuarios')) {
            throw new \Exception('Aviso: No tiene permiso para recuperar usuarios.');
        }
        // Consultar si no lo esta
        if ($this->consultado == false) {
            $this->consultar();
        }
        // Validar el estatus
        if ($this->estatus == 'A') {
            throw new \Base\RegistroExceptionValidacion('Aviso: No puede recuperarse el usuario porque ya lo está.');
        }
        // Cambiar el estatus
        $this->estatus = 'A';
        $this->modificar();
        // Entregar mensaje
        return "Se ha recuperado el usuario {$this->nombre}";
    } // recuperar

    /**
     * Desbloquear
     *
     * @return string Mensaje
     */
    public function desbloquear() {
        // Que tenga permiso para desbloquear
        if (!$this->sesion->puede_modificar('usuarios')) {
            throw new \Exception('Aviso: No tiene permiso para desbloquear usuarios.');
        }
        // Debe estar consultado el registro
        if (!$this->consultado) {
            $this->consultar();
        }
        // Debe tener el estatus activo
        if ($this->estatus != 'A') {
            throw new \Base\RegistroExceptionValidacion('ERROR: El usuario NO está activo.');
        }
        // Que este bloqueado
        if (!$this->esta_bloqueada) {
            throw new \Base\RegistroExceptionValidacion('ERROR: El usuario NO está bloqueado.');
        }
        // Determinar el comando sql y armar el mensaje
        if ($this->bloqueada_porque_fallas) {
            // Vamos a poner la cantidad de fallas en cero
            $comando_sql = sprintf("
                UPDATE
                    adm_usuarios
                SET
                    contrasena_fallas = 0
                WHERE
                    id = %d",
                $this->id);
        }
        if ($this->bloqueada_porque_expiro) {
            // Cuando una contraseña esta expirada, viene con el numero de fallas al tope para que se bloquee
            // vamos a poner la cantidad de fallas en cero
            // y agregarle tres dias a la fecha de expiracion
            // debe aparecerle el mensaje al usuario para que cambie su contraseña
            list($y, $m, $d) = explode('-', date('Y-m-d'));
            $comando_sql = sprintf("
                UPDATE
                    adm_usuarios
                SET
                    contrasena_expira = %s, contrasena_fallas = 0
                WHERE
                    id = %d",
                $this->sql_tiempo(mktime(0, 0, 0, $m, $d+3, $y)),
                $this->id);
        }
        if ($this->bloqueada_porque_sesiones) {
            // Vamos a poner el contador de sesiones en cero
            $comando_sql = sprintf("
                UPDATE
                    adm_usuarios
                SET
                    sesiones_contador = 0
                WHERE
                    id = %d",
                $this->id);
        }
        // Actualizar registro en la base de datos
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando($comando_sql);
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al tratar de desbloquear el usuario. ', $e->getMessage());
        }
        // Bajar banderas y elaborar mensaje
        if ($this->bloqueada_porque_fallas) {
            $this->bloqueada_porque_fallas = false;
            $msg = "Desbloqueado el usuario {$this->nombre}, porque se había equivocado en su contraseña muchas veces.";
        }
        if ($this->bloqueada_porque_expiro) {
            $this->bloqueada_porque_expiro = false;
            $msg = "Desbloqueado el usuario {$this->nombre}, porque su contraseña había expirado.";
        }
        if ($this->bloqueada_porque_sesiones) {
            $this->bloqueada_porque_sesiones = false;
            $msg = "Desbloqueado el usuario {$this->nombre}, porque alcanzó su máximo de sesiones por día.";
        }
        $this->esta_bloqueada = ($this->bloqueada_porque_fallas || $this->bloqueada_porque_expiro || $this->bloqueada_porque_sesiones);
        // Agregar a la bitacora que se modifico el registro
        $bitacora = new \AdmBitacora\Registro($this->sesion);
        $bitacora->agregar_modificado($this->id, $msg);
        // Entregar mensaje
        return $msg;
    } // desbloquear

} // Clase Registro

?>
