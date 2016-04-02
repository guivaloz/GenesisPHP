<?php
/**
 * GenesisPHP - AdmBitacora DetalleHTML
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

namespace AdmBitacora;

/**
 * Clase DetalleHTML
 */
class DetalleHTML extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $usuario;
    // public $usuario_nom_corto;
    // public $fecha;
    // public $pagina;
    // public $pagina_id;
    // public $tipo;
    // public $tipo_descrito;
    // public $url;
    // public $notas;
    // static public $tipo_descripciones;
    // static public $tipo_colores;

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
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html($in_encabezado);
            }
        }
        // Detalle
        $detalle = new \Base\DetalleHTML();
        // Seccion general
        $detalle->seccion('General');
        $detalle->dato('Fecha',     $this->fecha);
        $detalle->dato('Usuario',   $this->usuario_nom_corto);
        $detalle->dato('Página',    $this->pagina);
        $detalle->dato('Página ID', $this->pagina_id);
        $detalle->dato('URL',       $this->url);
        $detalle->dato('Tipo',      $this->tipo_descrito);
        $detalle->dato('Notas',     $this->notas);
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = "{$this->fecha} {$this->usuario_nom_corto}";
        }
        // SI HAY ENCABEZADO
        if ($encabezado != '') {
            // CREAR LA BARRA
            $barra             = new \Base\BarraHTML();
            $barra->encabezado = $encabezado;
            $barra->icono      = $this->sesion->menu->icono_en('bitacora');
            // PASAR LA BARRA AL DETALLE HTML
            $detalle->barra = $barra;
        }
        // ENTREGAR HTML
        return $detalle->html();
    } // html

} // Clase DetalleHTML

?>
