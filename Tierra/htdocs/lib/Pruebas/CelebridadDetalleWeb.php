<?php
/**
 * GenesisPHP - Pruebas CelebridadDetalleWeb
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

namespace Pruebas;

/**
 * Clase CelebridadDetalleWeb
 */
class CelebridadDetalleWeb extends CelebridadRegistro {

    // protected $sesion;
    // protected $consultado;
    // public $nombre;
    // public $sexo;
    // public $sexo_descrito;
    // public $nacimiento_fecha;
    // public $nacimiento_lugar;
    // public $nacionalidad;
    protected $detalle;

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar DetalleHTML
        $this->detalle = new \Base2\DetalleWeb();
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

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
        // Elaborar Imagen
        $imagen = new \Base2\ImagenWeb('imagenes/pruebas', array('small' => 200, 'middle' => 400, 'big' => 1024));
        $imagen->configurar_para_detalle();
        $imagen->cargar(1, 'qwerty', 'middle');
        $imagen->vincular('big');
        // Cargar Detalle
        $this->detalle->encabezado = $this->nombre;
        $this->detalle->icono      = $this->sesion->menu->icono_en('tierra_prueba_detalle_foto');
        $this->detalle->seccion('Clasificación científica');
        $this->detalle->dato('Nombre',              $this->nombre);
        $this->detalle->dato('Sexo',                $this->sexo_descrito);
        $this->detalle->dato('Fecha de nacimiento', $this->nacimiento_fecha);
        $this->detalle->dato('Lugar de nacimiento', $this->nacimiento_lugar);
        $this->detalle->dato('Nacionalidad',        $this->nacionalidad);
        $this->detalle->imagen($imagen);
        // Entregar
        return $this->detalle->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->detalle->javascript();
    } // javascript

} // Clase CelebridadDetalleWeb

?>
