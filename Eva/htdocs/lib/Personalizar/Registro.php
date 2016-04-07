<?php
/**
 * GenesisPHP - Personalizar Registro
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

namespace Personalizar;

/**
 * Clase Registro
 */
class Registro extends \Base\Registro {

    // protected $sesion;
    // protected $consultado;
    public $id;
    public $nom_corto;
    public $nombre;
    public $tipo;
    public $tipo_descrito;
    public $email;
    public $listado_renglones;
    public $contrasena_descrito;                     // Texto que describe la situación de la contraseña
    public $contrasena_alerta = false;
    public $sesiones_maximas;
    public $sesiones_contador;
    public $sesiones_descrito;                       // Texto que describe la situación del contador de sesiones
    public $sesiones_alerta = false;
    public $estatus;
    public $estatus_descrito;
    static public $dias_expira_contrasena_aviso = 7; // Alerta si la fecha de expiracion es igual o menor
    protected $contrasena;                           // Mantener como propiedad interna
    protected $contrasena_encriptada;                // Mantener como propiedad interna

    /**
     * Consultar
     *
     * @param integer ID del usuario, si no se da, se toma de la sesion
     */
    public function consultar($in_id=false) {
        // Si no se da el id del usuario, se toma de la sesion
        if (($in_id == false) && !$this->validar_entero($this->id)) {
            $this->id = $this->sesion->usuario;
        }
        // Validar
        if (!$this->validar_entero($this->id)) {
            throw new \Base\RegistroExceptionValidacion('Error: ID de usuario incorrecto.');
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("
                SELECT
                    nom_corto, nombre, tipo, email, listado_renglones,
                    contrasena, contrasena_encriptada, contrasena_expira, contrasena_fallas,
                    sesiones_maximas, sesiones_contador, estatus
                FROM
                    adm_usuarios
                WHERE
                    id = {$this->id}");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al consultar el usuario.', $e->getMessage());
        }
        // Si la consulta no entrego registros
        if ($consulta->cantidad_registros() < 1) {
            throw new \Base\RegistroExceptionNoEncontrado('Aviso: No se encontró al usuario.');
        }
        // Definir propiedades
        $a = $consulta->obtener_registro();
        $this->nom_corto             = $a['nom_corto'];
        $this->nombre                = $a['nombre'];
        $this->tipo                  = $a['tipo'];
        $this->tipo_descrito         = \AdmUsuarios\Registro::$tipo_descripciones[$this->tipo];
        $this->email                 = $a['email'];
        $this->listado_renglones     = intval($a['listado_renglones']);
        $this->contrasena            = $a['contrasena'];
        $this->contrasena_encriptada = $a['contrasena_encriptada'];
        $this->sesiones_maximas      = $a['sesiones_maximas'];
        $this->sesiones_contador     = $a['sesiones_contador'];
        $this->estatus               = $a['estatus'];
        $this->estatus_descrito      = \AdmUsuarios\Registro::$estatus_descripciones[$this->estatus];
        // Describimos la situacion de las sesiones
        if ($this->sesiones_contador > 0) {
            if ($this->sesiones_contador == $this->sesiones_maximas) {
                $this->sesiones_alerta   = true;
                $this->sesiones_descrito = "AVISO: Ha alcanzado su máximo de sesiones que es de {$this->sesiones_maximas}; si sale ya no podrá ingresar sino hasta mañana.";
            } else {
                $this->sesiones_descrito = "Ha ingresado {$this->sesiones_contador} veces hoy; el máximo es {$this->sesiones_maximas}.";
            }
        }
        // Describimos la situacion de la contraseña
        $s = array();
        if ($a['contrasena_fallas'] >= \Inicio\Autentificar::$fallas_para_bloquear) {
            $this->contrasena_alerta = true;
            $s[] = 'BLOQUEADA';
        }
        if ($a['contrasena_encriptada'] == '') {
            $this->contrasena_alerta = true;
            $s[] = 'Su contraseña es temporal, debe cambiarla para extender su duración';
        }
        $expira = floor(strtotime($a['contrasena_expira'])/(60*60*24));
        $hoy    = floor(strtotime(date('Y-m-d'))/(60*60*24));
        if ($expira - $hoy <= self::$dias_expira_contrasena_aviso) {
            $this->contrasena_alerta = true;
            $s[] = sprintf('Su contraseña expira en %d días', $expira - $hoy);
        } else {
            $s[] = sprintf('Expira el %s', $a['contrasena_expira']);
        }
        $this->contrasena_descrito = implode('. ', $s).'.';
        // Ponemos como verdadero el flag de consultado
        $this->consultado = true;
    } // consultar

    /**
     * Cambiar Contrasena
     *
     * @param string  Contraseña actual
     * @param string  Contraseña nueva
     * @param string  Contraseña para verificar, debe ser igual a la anterior
     * @return string Mensaje de éxito
     */
    protected function cambiar_contrasena($in_contrasena_actual, $in_contrasena_nueva, $in_contrasena_verificar) {
        // Verificar que haya sido consultado
        if ($this->consultado == false) {
            throw new \Exception('Error: No ha sido consultado para cambiar la contraseña.');
        }
        // Validar contraseña actual
        if (!$this->validar_contrasena($in_contrasena_actual)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: La contraseña actual no es válida.');
        }
        // Validar contraseña nueva
        if (!$this->validar_contrasena($in_contrasena_nueva)) {
            throw new \Base\RegistroExceptionValidacion('Aviso: La contraseña nueva no es válida. Debe tener un mínimo de 8 caracteres (letras y números solamente).');
        }
        // Validar que sean iguales
        if ($in_contrasena_nueva !== $in_contrasena_verificar) {
            throw new \Base\RegistroExceptionValidacion('Aviso: Las contraseñas nuevas no son iguales. Intente de nuevo.');
        }
        // Validar que sea la nueva sea diferente a la actual
        if ($in_contrasena_actual === $in_contrasena_nueva) {
            throw new \Base\RegistroExceptionValidacion('Aviso: La contraseña nueva es igual a la actual. Debe ser distinta.');
        }
        // Validar contraseña actual
        if ($this->contrasena_encriptada != '') {
            if (md5($in_contrasena_actual) !== $this->contrasena_encriptada) {
                throw new \Base\RegistroExceptionValidacion('Aviso: La contraseña actual (cifrada) es incorrecta.');
            }
        } else {
            if ($in_contrasena_actual !== $this->contrasena) {
                throw new \Base\RegistroExceptionValidacion('Aviso: La contraseña actual es incorrecta.');
            }
        }
        // Calculamos la fecha de expiracion
        $this->contrasena_expira = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+\AdmUsuarios\Registro::$dias_expira_contrasena, date('Y')));
        // Ciframos la contrasena
        $this->contrasena_encriptada = md5($in_contrasena_nueva);
        // Actualizar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_usuarios
                SET
                    contrasena_encriptada = %s, contrasena_expira = %s
                WHERE
                    id = %d",
                $this->sql_texto($this->contrasena_encriptada),
                $this->sql_tiempo($this->contrasena_expira),
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al cambiar la contraseña. ', $e->getMessage());
        }
        // Agregar a la bitacora que se modifico el registro
        $bitacora = new \AdmBitacora\Registro($this->sesion);
        $bitacora->agregar_cambio_contrasena();
        // Entregar mensaje de exito
        return "Se ha cambiado la contraseña de {$this->nombre}. Expira el {$this->contrasena_expira}.";
    } // cambiar_contrasena

    /**
     * Cambiar cantidad renglones
     *
     * @param integer Nueva cantidad de renglones en los listados
     * @return string Mensaje de éxito
     */
    public function cambiar_cantidad_renglones($in_listado_renglones) {
        // Verificar que haya sido consultado
        if ($this->consultado == false) {
            throw new \Exception('Error: No ha sido consultado para cambiar la cantidad de renglones.');
        }
        // Validar la cantidad para el listado de renglones
        if (!$this->validar_entero($in_listado_renglones) || ($in_listado_renglones < 5)) {
            throw new \Base\RegistroExceptionValidacion("Aviso: La cantidad de reglones en los listados es incorrecta. Debe ser igual o mayor a cinco.");
        }
        // Actualizar usuario
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_usuarios
                SET
                    listado_renglones = %d
                WHERE
                    id = %d",
                $in_listado_renglones,
                $this->id));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al cambiar la cantidad de renglones. ', $e->getMessage());
        }
        // Actualizar sesion
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_sesiones
                SET
                    listado_renglones = %d
                WHERE
                    usuario = %d",
                $in_listado_renglones,
                $this->id));
        } catch (Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError('Error: Al cambiar la cantidad de renglones en la sesión.');
        }
        // Cambiar la propiedad
        $this->listado_renglones = $in_listado_renglones;
        // Entregar mensaje de exito
        return "Se ha cambiado la cantidad de renglones para los listados a {$this->listado_renglones}.";
    } // cambiar_cantidad_renglones

} // Clase Registro

?>
