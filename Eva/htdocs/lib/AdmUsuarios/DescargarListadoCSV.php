<?php
/**
 * GenesisPHP - AdmUsuarios DescargarListadoCSV
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
 * Clase DescargarListadoCSV
 */
class DescargarListadoCSV extends \Base2\DescargarCSV {

    // protected $cabecera_tipo_contenido;
    // protected $recodificacion;
    // protected $contenido;
    // protected $csv_archivo;
    // protected $clave;
    // protected $sesion;
    // protected $sesion_exitosa;
    // protected $usuario;
    // protected $usuario_nombre;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('adm_usuarios');
    } // constructor

    /**
     * CSV
     *
     * @return string CSV
     */
    public function csv() {
        // Solo si se carga con éxito la sesión
        if ($this->sesion_exitosa) {
            $listado          = new ListadoCSV($this->sesion);
            $listado->estatus = 'A';
            try {
                $this->contenido = $listado->csv();
            } catch (\Exception $e) {
                $pagina_web = new \Base2\PaginaWeb($this->clave);
                $pagina_web->agregar_mensaje_error('Error al descargar archivo CSV', $e->getMessage());
                return $pagina_web->html();
            }
            $this->csv_archivo = ListadoCSV::RAIZ_CSV_ARCHIVO;
        }
        // Ejecutar el padre y entregar su resultado
        return parent::csv();
    } // csv

} // Clase DescargarListadoCSV

?>
