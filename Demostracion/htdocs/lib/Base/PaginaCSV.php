<?php
/**
 * GenesisPHP - Base PaginaCSV
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

namespace Base;

/**
 * Clase PaginaCSV
 */
class PaginaCSV extends PlantillaCSV {

    // public $contenido;
    // public $csv_archivo;
    protected $sesion;          // Instancia de la sesion
    protected $sesion_exitosa;  // Boleano, verdadero si se cargo con exito la sesion
    protected $usuario;         // Entero, id del usuario
    protected $usuario_nombre;  // Texto, nombre del usuario

    /**
     * Constructor
     *
     * @param string Clave de la página
     */
    public function __construct($in_clave) {
        // Parametro clave de la pagina
        $this->clave = $in_clave;
        // Cargar la sesion
        $this->sesion = new \Inicio\Sesion();
        try {
            // Cargar la sesion
            $this->sesion->cargar($this->clave);
            // La sesion se ha cargado con exito
            $this->sesion_exitosa = true;
            // Pasar datos del usuario
            $this->usuario        = $this->sesion->usuario;
            $this->usuario_nombre = $this->sesion->nombre;
        } catch (\Exception $e) {
            // Ha fallado la sesion, se mostrara el mensaje en la pantalla de ingreso
            $this->sesion_exitosa = false;
            $this->contenido      = $e->getMessage();
            $this->modelo         = 'ingreso';
        }
    } // constructor

    /**
     * CSV
     *
     * @return string CSV
     */
    public function csv() {
        // Si la sesion es exitosa
        if ($this->sesion_exitosa) {
            // Se ejecuta el padre y se entrega su resultado
            return parent::csv();
        } else {
            // De lo contrario se muestra un html con el error en la sesion
            $error_fatal_html            = new \Base\PlantillaErrorFatalHTML();
            $error_fatal_html->titulo    = 'Falló la sesión';
            $error_fatal_html->contenido = <<<FINAL
    <p>Entre las posibles causas de este error están:</p>
    <ul>
      <li>No tiene permiso para usar este módulo.</li>
      <li>La sesión caducó.</li>
      <li>No existe la cookie requerida en el navegador.</li>
    </ul>
FINAL;
            // ENTREGAR
            return $error_fatal_html->html();
        }
    } // csv

} // Clase PaginaCSV

?>
