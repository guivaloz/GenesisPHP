<?php
/**
 * GenesisPHP - Cookie
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

namespace Inicio;

/**
 * Clase Cookie
 */
class Cookie extends \Configuracion\CookieConfig {

    // protected $nom_cookie;
    // protected $version_actual;
    // protected $tiempo_expirar;
    // protected $tiempo_renovar;
    // protected $key;
    public  $usuario = false; // ID del usuario
    public  $ingreso = false; // Timestamp de cuando ingreso
    private $version = false; // Version de la cookie
    private $cypher;          // Nombre del metodo para cifrar, constante
    private $mode;            // Modo del cifrado, constante
    private $td;              // Variable para manipular el cifrado, interna

    /**
     * Constructor
     */
    public function __construct() {
        // Criptografia
        $this->cypher = MCRYPT_BLOWFISH;
        $this->mode   = 'cfb';
        $this->td     = mcrypt_module_open($this->cypher, '', $this->mode, '');
        // Si existe la cookie
        if (array_key_exists($this->nom_cookie, $_COOKIE)) {
            $this->desempacar($_COOKIE[$this->nom_cookie]);
        }
    } // constructor

    /**
     * Eliminar
     */
    public function eliminar() {
        setcookie($this->nom_cookie, '', 0);
        $this->usuario = false;
        $this->ingreso = false;
        $this->version = false;
    } // eliminar

    /**
     * Encriptar
     *
     * @param  string Valor a encriptar
     * @return string Valor encriptado
     */
    private function encriptar($desencriptado) {
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->td), MCRYPT_RAND);
        mcrypt_generic_init($this->td, $this->key, $iv);
        $encriptado = mcrypt_generic($this->td, $desencriptado);
        mcrypt_generic_deinit($this->td);
        $b64 = base64_encode($iv.$encriptado);
        return str_replace('=', '', $b64);
    } // encriptar

    /**
     * Empacar
     *
     * @return string Valor encriptado
     */
    private function empacar() {
        $this->ingreso = time();
        $this->version = $this->version_actual;
        $arr           = array($this->version, $this->ingreso, $this->usuario);
        $mezcla        = implode(',', $arr);
        return $this->encriptar($mezcla);
    } // empacar

    /**
     * Crear
     *
     * @param integer ID del usuario
     */
    public function crear($in_id) {
        if (intval($in_id)) {
            $this->usuario = intval($in_id);
            setcookie($this->nom_cookie, $this->empacar(), time() + $this->tiempo_expirar, '/');
        } else {
            throw new \Exception("No se puede crear la cookie. El ID no es entero mayor a cero.");
        }
    } // crear

    /**
     * Validar
     */
    protected function validar() {
        if (!$this->usuario || !$this->ingreso || !$this->version) {
            throw new \Exception('Escriba su nombre de usuario y contraseña para entrar.');
        } elseif ($this->version != $this->version_actual) {
            throw new \Exception('La versión de cookie es incorrecta.');
        } elseif (time() - $this->ingreso > $this->tiempo_expirar) {
            throw new \Exception('La cookie ha expirado, ingrese de nuevo.');
        } elseif (time() - $this->ingreso > $this->tiempo_renovar) {
            $this->crear($this->usuario);
        }
    } // validar

    /**
     * Desencriptar
     *
     * @param  string Valor encriptado en hexadecimal
     * @return string Valor desencriptado
     */
    private function desencriptar($hexadecimal) {
        $encriptado = base64_decode($hexadecimal);
        $ivsize     = mcrypt_get_iv_size($this->cypher, $this->mode);
        $iv         = substr($encriptado, 0, $ivsize);
        $encriptado = substr($encriptado, $ivsize);
        mcrypt_generic_init($this->td, $this->key, $iv);
        $desencriptado = mdecrypt_generic($this->td, $encriptado);
        mcrypt_generic_deinit($this->td);
        return $desencriptado;
    } // desencriptar

    /**
     * Desempacar
     *
     * @param string Valor desencriptado
     */
    private function desempacar($contenido) {
        $desencriptado = $this->desencriptar($contenido);
        list($this->version, $ingreso, $id) = explode(',', $desencriptado);
        $this->ingreso = intval($ingreso);
        $this->usuario = intval($id);
    } // desempacar

} // Clase Cookie

?>
