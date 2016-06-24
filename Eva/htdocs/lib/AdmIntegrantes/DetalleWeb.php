<?php
/**
 * GenesisPHP - AdmIntegrantes DetalleWeb
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

namespace AdmIntegrantes;

/**
 * Clase DetalleWeb
 */
class DetalleWeb extends Registro {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $usuario;
    // public $usuario_nombre;
    // public $usuario_nom_corto;
    // public $departamento;
    // public $departamento_nombre;
    // public $poder;
    // public $poder_descrito;
    // public $estatus;
    // public $estatus_descrito;
    // static public $poder_descripciones;
    // static public $poder_colores;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    protected $detalle;  // Instancia de \Base2\DetalleWeb
    static public $accion_modificar = 'integranteModificar';
    static public $accion_eliminar  = 'integranteEliminar';
    static public $accion_recuperar = 'integranteRecuperar';
    const RAIZ_PHP_ARCHIVO          = 'admintegrantes.php';

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
        $barra->icono      = $this->sesion->menu->icono_en('adm_integrantes');
        // Definir botones
        if (($this->estatus != 'B') && $this->sesion->puede_modificar('adm_integrantes')) {
            $barra->boton_modificar(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_modificar));
        }
        if (($this->estatus != 'B') && $this->sesion->puede_eliminar('adm_integrantes')) {
            $barra->boton_eliminar_confirmacion(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_eliminar),
                "¿Está seguro de querer <strong>eliminar</strong> a el integrante {$this->usuario_nombre} del departamento {$this->departamento_nombre}?");
        }
        if (($this->estatus == 'B') && $this->sesion->puede_recuperar('adm_integrantes')) {
            $barra->boton_recuperar_confirmacion(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_recuperar),
                "¿Está seguro de querer <strong>recuperar</strong> a el integrante {$this->usuario_nombre} del departamento {$this->departamento_nombre}?");
        }
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
        // Seccion integrante
        $this->detalle->seccion('Integrante');
        $this->detalle->dato('Usuario',      sprintf('<a href="%s?%s=%d">%s (%s)</a>', self::RAIZ_PHP_ARCHIVO, Listado::$param_usuario, $this->usuario, $this->usuario_nombre, $this->usuario_nom_corto));
        $this->detalle->dato('Departamento', sprintf('<a href="%s?%s=%d">%s</a>', self::RAIZ_PHP_ARCHIVO, Listado::$param_departamento, $this->departamento, $this->departamento_nombre));
        $this->detalle->dato('Poder',        $this->poder_descrito, parent::$poder_colores[$this->poder]);
        // Seccion registro
        if ($this->sesion->puede_eliminar('adm_integrantes')) {
            $this->detalle->seccion('Registro');
            $this->detalle->dato('Estatus', $this->estatus_descrito, parent::$estatus_colores[$this->estatus]);
        }
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
