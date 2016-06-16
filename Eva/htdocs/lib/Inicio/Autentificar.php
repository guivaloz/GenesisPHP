<?php
/**
 * GenesisPHP - Inicio Autentificar
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

namespace Inicio;

/**
 * Clase Autentificar
 */
class Autentificar {

    static public $fallas_para_bloquear = 5;

    /**
     * Usuario contraseña
     *
     * @param  string  Nombre corto
     * @param  string  Contraseña
     * @return integer ID del usuario
     */
    public function usuario_contrasena($in_nom_corto, $in_contrasena) {
        // Ponemos en minusculas el nombre corto
        $this->nom_corto = strtolower($in_nom_corto);
        // Validar parámetros
        if (!\Base2\UtileriasParaValidar::validar_nom_corto($this->nom_corto)) {
            throw new AutentificarException(false, $this->nom_corto, 'datos incorrectos', 'Aviso: Nombre corto incorrecto.');
        }
        if (!\Base2\UtileriasParaValidar::validar_contrasena($in_contrasena)) {
            throw new AutentificarException(false, $this->nom_corto, 'datos incorrectos', 'Aviso: Contraseña incorrecta.');
        }
        // Consultar usuario
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    id, tipo,
                    contrasena, contrasena_encriptada, contrasena_fallas, contrasena_expira,
                    sesiones_maximas, sesiones_contador, estatus
                FROM
                    adm_usuarios
                WHERE
                    nom_corto = %s",
                $this->sql_texto($this->nom_corto)));
        } catch (\Exception $e) {
            throw new \Exception('Error: Al ejecutar comando SQL para consultar usuario.');
        }
        // Si la consulta no entrego registros, no se encontro
        if ($consulta->cantidad_registros() == 0) {
            throw new AutentificarException(false, $this->nom_corto, 'usuario no encontrado', 'Aviso: No existe el usuario.');
        }
        // Esta variable tendra los datos de la consulta
        $u = $consulta->obtener_registro();
        // Temporalmente ponemos el id del usuario en esta variable
        $id = intval($u['id']);
        // No entra si el usuario no es activo
        if ($u['estatus'] != 'A') {
            throw new AutentificarException($id, $this->nom_corto, 'usuario inactivo', 'Aviso: Cuenta dada de baja. Llame al administrador para solicitar la renovación de su cuenta.');
        }
        // No entra si excede la cantidad de contraseñas fallidas
        if ($u['contrasena_fallas'] >= self::$fallas_para_bloquear) {
            throw new AutentificarException($id, $this->nom_corto, 'contrasena bloqueada', 'Aviso: Contraseña bloqueada. Por favor llame a un administrador del sistema para solicitar su desbloqueo.');
        }
        // No entra si excede la cantidad maxima de sesiones
        if ($u['sesiones_contador'] >= $u['sesiones_maximas']) {
            throw new AutentificarException($id, $this->nom_corto, 'sesiones maximo', 'Aviso: Ha excedido la cantidad de sesiones por el día de hoy.');
        }
        // Comparar las contraseñas
        if ($u['contrasena_encriptada'] == '') {
            $es_correcta = ($in_contrasena === $u['contrasena']);
        } else {
            $es_correcta = (md5($in_contrasena) == $u['contrasena_encriptada']);
        }
        // Si no es correcta la contraseña
        if (!$es_correcta) {
            // Se incrementa contrasena_fallas
            try {
                $base_datos->comando(sprintf("
                    UPDATE
                        adm_usuarios
                    SET
                        contrasena_fallas = %d
                    WHERE
                        id = %d",
                    $u['contrasena_fallas'] + 1,
                    $id));
            } catch (\Exception $e) {
                throw new \Exception('Error: Al tratar de incrementar el contador de contraseñas fallidas.');
            }
            throw new AutentificarException($id, $this->nom_corto, 'contrasena equivocada', 'Aviso: Contraseña equivocada.');
        }
        // Si llego la fecha de expiracion de la contraseña
        if ($u['contrasena_expira'] == '') {
            throw new AutentificarException($id, $this->nom_corto, 'contrasena bloqueada', 'Aviso: La contraseña no tiene fecha de expiración.');
        } elseif (date('Y-m-d') >= $u['contrasena_expira']) {
            // Cambiamos la cantidad de fallas al maximo para que se bloquee
            try {
                $base_datos->comando(sprintf("
                    UPDATE
                        adm_usuarios
                    SET
                        contrasena_fallas = %d
                    WHERE
                        id = %d",
                    self::$fallas_para_bloquear,
                    $id));
            } catch (\Exception $e) {
                throw new \Exception('Error: Al tratar bloquear la contraseña.');
            }
            throw new AutentificarException($id, $this->nom_corto, 'contrasena bloqueada', 'Aviso: Contraseña bloqueada.');
        }
        // Se acepta la autentificacion, por lo que
        // - Se incrementa sesiones_contador en uno
        // - Se pone sesiones_ultima al momento
        // - Y contrasena_fallas se pone a cero
        try {
            $base_datos->comando(sprintf("
                UPDATE
                    adm_usuarios
                SET
                    sesiones_contador = %s, sesiones_ultima = NOW(), contrasena_fallas = 0
                WHERE
                    id = %s",
                $u['sesiones_contador'] + 1,
                $id));
        } catch (\Exception $e) {
            throw new \Exception('Error: Al tratar de actualizar el usuario.');
        }
        // Entregamos el id del usuario
        return $id;
    } // usuario_contrasena

} // Clase Autentificar

?>
