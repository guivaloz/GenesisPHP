<?php
/**
 * GenesisPHP - Base Raiz
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
 * Clase Raiz
 */
class Raiz extends Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        // TODO: Programar ListadosCSV
        if (false) { // $this->adan->si_hay_que_crear('listado_csv')
            // Pagina con descarga de archivo csv
            $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - Página SED_TITULO_PLURAL
 *
 * @package SED_PAQUETE
 */

require_once('lib/Base2/AutocargadorClases.php');

// Si se solicita la descarga de archivo CSV, de lo contrario la página web
if (\$_GET['csv'] == 'descargar') {
    \$descargar_listado_csv = new \\SED_CLASE_PLURAL\\DescargarListadoCSV();
    echo \$descargar_listado_csv->csv();
} else {
    \$pagina_web = new \\SED_CLASE_PLURAL\\PaginaWeb();
    echo \$pagina_web->html();
}

?>

FINAL;
        } else {
            // Entregar pagina con descarga de archivo csv
            $contenido = <<<FINAL
<?php
/**
 * SED_SISTEMA - Página SED_TITULO_PLURAL
 *
 * @package SED_PAQUETE
 */

require_once('lib/Base2/AutocargadorClases.php');

// Mostrar página web
\$pagina_web = new \\SED_CLASE_PLURAL\\PaginaWeb();
echo \$pagina_web->html();

?>

FINAL;
        }
        // Realizar sustituciones y entregar
        return $this->sustituir_sed($contenido);
    } // php

} // Clase Raiz

?>
