<?php
/**
 * GenesisPHP - CookieConfig
 *
 * Copyright 2015 Guillermo Valdés Lozano <guivaloz@movimientolibre.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @package GenesisPHP
 */

namespace Configuracion;

/**
 * Clase CookieConfig
 */
class CookieConfig {

    protected $nom_cookie     = 'genesisphp_demo';  // Nombre con el que se guardara la cookie en el navegador.
    protected $version_actual = 1;                  // Número entero que sirve para obligar a renover las cookies anteriores
    protected $tiempo_expirar = 86400;              // Tiempo en segundos para que expire la cookie, 60 x 60 x 60 x 24 = 86400 seg = 1 dia
    protected $tiempo_renovar = 3600;               // Tiempo en segundos para que se renueve la cookie, 60 x 60 = 3600 seg = 1 hora
    protected $key            = 'Jcc5CqzF3TMzIqy7'; // 16 caracteres o más que sean muy difíciles de adivinar para llave de cifrado

} // Clase CookieConfig

?>
