<?php
/**
 * GenesisPHP - Celebridad DetalleHTML
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

namespace Pruebas;

/**
 * Clase CelebridadDetalleHTML
 */
class CelebridadDetalleHTML extends CelebridadRegistro {

    // protected $sesion;
    // protected $consultado;
    // public $nombre;
    // public $sexo;
    // public $sexo_descrito;
    // public $nacimiento_fecha;
    // public $nacimiento_lugar;
    // public $nacionalidad;
    protected $javascript = array();

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Si no se ha consultado
        if (!$this->consultado) {
            $this->consultar();
        }
        // Definir la barra
        $barra             = new \Base\BarraHTML();
        $barra->encabezado = $this->nombre;
        $barra->icono      = $this->sesion->menu->icono_en('tierra_prueba_detalle_foto');
        // Definir la instacia de DetalleHTML con los datos del registro
        $detalle = new \Base\DetalleHTML();
        $detalle->seccion('Clasificación científica');
        $detalle->dato('Nombre',              $this->nombre);
        $detalle->dato('Sexo',                $this->sexo_descrito);
        $detalle->dato('Fecha de nacimiento', $this->nacimiento_fecha);
        $detalle->dato('Lugar de nacimiento', $this->nacimiento_lugar);
        $detalle->dato('Nacionalidad',        $this->nacionalidad);
        $detalle->barra = $barra;
        // Definir imagen
        $imagen = new \Base\ImagenHTML('imagenes/pruebas', array('small' => 200, 'middle' => 400, 'big' => 1024));
        $imagen->configurar_para_detalle();
        $imagen->cargar(1, 'qwerty', 'middle');
        $imagen->vincular('big');
        $detalle->imagen($imagen);
        // Acumular código Javascript
        $this->javascript[] = $detalle->javascript();
        // Entregar código HTML
        return $detalle->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return implode('', $this->javascript);
    } // javascript

} // Clase CelebridadDetalleHTML

?>
