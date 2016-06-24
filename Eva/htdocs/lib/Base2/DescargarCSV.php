<?php
/**
 * GenesisPHP - DescargarCSV
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

namespace Base2;

/**
 * Clase abstracta DescargarCSV
 */
abstract class DescargarCSV extends PlantillaCSV {

    // protected $cabecera_tipo_contenido;
    // protected $recodificacion;
    // protected $contenido;
    // protected $csv_archivo;
    protected $clave;           // Clave única de la página
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
        // Si la sesion es exitosa, se entrega lo que genere el método csv del padre
        if ($this->sesion_exitosa) {
            return parent::csv();
        } else {
            die('Error al descargar archivo CSV: Falló o caducó la sesión del sistema. Ingrese de nuevo.');
        }
    } // csv

} // Clase abstracta DescargarCSV

?>
