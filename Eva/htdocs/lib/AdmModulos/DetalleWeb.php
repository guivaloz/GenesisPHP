<?php
/**
 * GenesisPHP - AdmModulos DetalleWeb
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

namespace AdmModulos;

/**
 * Clase DetalleWeb
 */
class DetalleWeb extends Registro implements \Base2\SalidaWeb {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $orden;
    // public $clave;
    // public $nombre;
    // public $pagina;
    // public $icono;
    // public $padre;
    // public $padre_nombre;
    // public $permiso_maximo;
    // public $permiso_maximo_descrito;
    // public $poder_minimo;
    // public $poder_minimo_descrito;
    // public $estatus;
    // public $estatus_descrito;
    // static public $permiso_maximo_descripciones;
    // static public $permiso_maximo_colores;
    // static public $poder_minimo_descripciones;
    // static public $poder_minimo_colores;
    // static public $estatus_descripciones;
    // static public $estatus_colores;
    protected $detalle;  // Instancia de \Base2\DetalleWeb
    static public $accion_modificar = 'moduloModificar';
    static public $accion_eliminar  = 'moduloEliminar';
    static public $accion_recuperar = 'moduloRecuperar';
    const RAIZ_PHP_ARCHIVO          = 'admmodulos.php';

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
        $barra->icono      = $this->sesion->menu->icono_en('adm_modulos');
        // Definir botones
        if (($this->estatus != 'B') && $this->sesion->puede_modificar('adm_modulos')) {
            $barra->boton_modificar(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_modificar));
        }
        if (($this->estatus != 'B') && $this->sesion->puede_eliminar('adm_modulos')) {
            $barra->boton_eliminar_confirmacion(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_eliminar),
                "¿Está seguro de querer <strong>eliminar</strong> al módulo {$this->nombre}?");
        }
        if (($this->estatus == 'B') && $this->sesion->puede_recuperar('adm_departamentos')) {
            $barra->boton_recuperar_confirmacion(sprintf('%s?id=%d&accion=%s', self::RAIZ_PHP_ARCHIVO, $this->id, self::$accion_recuperar),
                "¿Está seguro de querer <strong>recuperar</strong> al módulo {$this->nombre}?");
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
        // Seccion modulo
        $this->detalle->seccion('Módulo');
        $this->detalle->dato('Ícono',          sprintf('<img src="imagenes/32x32/%s" />', $this->icono));
        $this->detalle->dato('Nombre',         $this->nombre);
        $this->detalle->dato('Orden',          $this->orden);
        $this->detalle->dato('Clave',          $this->clave);
        $this->detalle->dato('Página',         sprintf('<a href="%s">%s</a>', $this->pagina, $this->pagina));
        $this->detalle->dato('Padre',          $this->padre_nombre);
        $this->detalle->dato('Permiso máximo', $this->permiso_maximo_descrito, parent::$permiso_maximo_colores[$this->permiso_maximo]);
        $this->detalle->dato('Poder mínimo',   $this->poder_minimo_descrito, parent::$poder_minimo_colores[$this->poder_minimo]);
        // Seccion registro
        if ($this->sesion->puede_eliminar('adm_modulos')) {
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
