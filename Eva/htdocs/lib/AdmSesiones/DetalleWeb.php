<?php
/**
 * GenesisPHP - AdmSesiones DetalleWeb
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

namespace AdmSesiones;

/**
 * Clase DetalleWeb
 */
class DetalleWeb extends Registro implements \Base2\SalidaWeb {

    // protected $sesion;
    // protected $consultado;
    // public $usuario;
    // public $nombre;
    // public $nom_corto;
    // public $tipo;
    // public $ingreso;
    // public $listado_renglones;
    protected $detalle;  // Instancia de \Base2\DetalleWeb
    const RAIZ_PHP_ARCHIVO = 'admsesiones.php';

    /**
     * Barra
     *
     * @param  string Encabezado opcional
     * @return mixed  Instancia de \Base2\BarraWeb
     */
    protected function barra($in_encabezado='') {
        // Si viene el parametro se usa, si no, el encabezado por defecto
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->encabezado();
        }
        // Crear la barra
        $barra             = new \Base2\BarraWeb();
        $barra->encabezado = $encabezado;
        $barra->icono      = $this->sesion->menu->icono_en('adm_sesiones');
        // Entregar
        return $barra;
    } // barra

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
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                return $mensaje->html($in_encabezado);
            }
        }
        // Iniciar detalle
        $this->detalle = new \Base2\DetalleWeb();
        // Seccion departamento
        $this->detalle->seccion('Sesión');
        $this->detalle->dato('Nombre',                $this->nombre);
        $this->detalle->dato('Nombre corto',          $this->nom_corto);
        $this->detalle->dato('Tipo',                  $this->tipo);
        $this->detalle->dato('Ingreso',               $this->ingreso);
        $this->detalle->dato('Renglones en listados', $this->listado_renglones);
        // Pasar la barra
        $this->detalle->barra = $this->barra($in_encabezado);
        // Entregar
        return $this->detalle->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        if ($this->detalle instanceof \Base2\DetalleWeb) {
            return $this->detalle->javascript();
        }
    } // javascript

} // Clase DetalleWeb

?>
