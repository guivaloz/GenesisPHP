<?php
/**
 * GenesisPHP - Personalizar SituacionWeb
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
 * Clase SituacionWeb
 */
class SituacionWeb extends Registro {

    protected $mensaje; // Instancia de \Base2\MensajeWeb

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar detalle
        $this->mensaje = new \Base2\MensajeWeb();
        // Ejecutar el constructor del padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function html($in_encabezado='') {
        // Debe estar consultado, de lo contrario se consulta y si falla se muestra mensaje
        if (!$this->consultado) {
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $this->mensaje->tipo      = 'error';
                $this->mensaje->contenido = $e->getMessage();
            }
        }
        // Elaborar y entregar mensaje
        if ($this->contrasena_alerta) {
            $this->mensaje->tipo      = 'aviso';
            $this->mensaje->contenido = 'CAMBIE SU CONTRASEÑA';
        } else {
            $this->mensaje->info      = 'info';
            $this->mensaje->contenido = 'Su cuenta está bien';
        }
        // Entregar
        return $this->mensaje->html($in_encabezado);
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->mensaje->javascript();
    } // javascript

} // Clase SituacionWeb

?>
