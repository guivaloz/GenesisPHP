<?php
/**
 * GenesisPHP - Usuarios BusquedaHTML
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
 * Clase BusquedaHTML
 */
class BusquedaHTML extends \Base\BusquedaHTML {

    // public $hay_resultados;
    // public $entrego_detalle;
    // public $hay_mensaje;
    // public $resultado;
    // protected $sesion;
    // protected $consultado;
    // protected $javascript;
    protected $nombre;                    // FILTRO TEXTO
    protected $clave;                     // FILTRO TEXTO
    protected $permiso_maximo;            // FILTRO ENTERO
    protected $poder_minimo;              // FILTRO ENTERO
    protected $estatus;                   // FILTRO CARACTER
    static public $form_name = 'modulos_busqueda';

    /**
     * Validar
     */
    public function validar() {
        // Validamos las propiedades
        if (($this->nombre != '') && !validar_nombre($this->nombre)) {
            throw new \Base\BusquedaHTMLExceptionValidacion('Aviso: Nombre incorrecto.');
        }
        if (($this->clave != '') && !validar_nombre($this->clave)) {
            throw new \Base\BusquedaHTMLExceptionValidacion('Aviso: Clave incorrecta.');
        }
        if (($this->permiso_maximo != '') && !array_key_exists($this->permiso_maximo, Registro::$permiso_maximo_descripciones)) {
            throw new \Base\BusquedaHTMLExceptionValidacion('Aviso: Permiso máximo incorrecto.');
        }
        if (($this->poder_minimo != '') && !array_key_exists($this->poder_minimo, Registro::$poder_minimo_descripciones)) {
            throw new \Base\BusquedaHTMLExceptionValidacion('Aviso: Poder mínimo incorrecto.');
        }
        if (($this->estatus != '') && !array_key_exists($this->estatus, Registro::$estatus_descripciones)) {
            throw new \Base\BusquedaHTMLExceptionValidacion('Aviso: Estatus incorrecto.');
        }
    } // validar

    /**
     * Formulario
     *
     * @param  string Encabezado opcional
     * @return string HTML con el formulario
     */
    protected function formulario($in_encabezado='') {
        // Formulario
        $f = new \Base\FormularioHTML(self::$form_name);
        $f->texto_nombre('nombre',            'Nombre',         $this->nombre);
        $f->texto_nom_corto('clave',          'Clave',          $this->clave);
        $f->select_con_nulo('permiso_maximo', 'Permiso máximo', Registro::$permiso_maximo_descripciones, $this->permiso_maximo);
        $f->select_con_nulo('poder_minimo',   'Poder mínimo',   Registro::$poder_minimo_descripciones,   $this->poder_minimo);
        if ($this->sesion->puede_recuperar('modulos')) {
            $f->select_con_nulo('estatus', 'Estatus', Registro::$estatus_descripciones, $this->estatus);
        }
        $f->boton_buscar();
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = "Buscar módulos";
        }
        // Entregar
        return $f->html($encabezado, $this->sesion->menu->icono_en('modulos'));
    } // formulario

    /**
     * Recibir Formulario
     *
     * @return boolean Verdadero si se recibió el formulario
     */
    public function recibir_formulario() {
        // Si viene el formulario
        if ($_POST['formulario'] == self::$form_name) {
            // Cargar propiedades
            $this->nombre         = post_texto_mayusculas($_POST['nombre']);
            $this->clave          = post_texto_mayusculas($_POST['clave']);
            $this->permiso_maximo = post_select($_POST['permiso_maximo']);
            $this->poder_minimo   = post_select($_POST['poder_minimo']);
            if ($this->sesion->puede_recuperar('modulos')) {
                $this->estatus = post_select($_POST['estatus']);
            }
            // Entregar verdadero
            return true;
        } else {
            // No viene el formulario, entregar falso
            return false;
        }
    } // recibir_formulario

    /**
     * Consultar
     *
     * @return mixed Objeto con el ListadoHTML, TrenHTML o DetalleHTML, falso si no se encontró nada
     */
    public function consultar() {
        // De inicio, no hay resultados
        $this->hay_resultados = false;
        // Filtros y mensajes
        $f = array();
        $m = array();
        // Elaborar los filtros sql y el mensaje
        if ($this->nombre != '') {
            $f[] = "nombre ILIKE '%{$this->nombre}%'";
            $m[] = "nombre {$this->nombre}";
        }
        if ($this->clave != '') {
            $f[] = "clave ILIKE '%{$this->clave}%'";
            $m[] = "clave {$this->clave}";
        }
        if ($this->permiso_maximo != '') {
            $f[] = "permiso_maximo = '{$this->permiso_maximo}'";
            $m[] = "permiso máximo ".Registro::$permiso_maximo_descripciones[$this->permiso_maximo];
        }
        if ($this->poder_minimo != '') {
            $f[] = "poder_minimo = '{$this->poder_minimo}'";
            $m[] = "poder mínimo ".Registro::$poder_minimo_descripciones[$this->poder_minimo];
        }
        if ($this->sesion->puede_recuperar('modulos')) {
            if ($this->estatus != '') {
                $f[] = "estatus = '{$this->estatus}'";
                $m[] = "estatus ".Registro::$estatus_descripciones[$this->estatus];
            }
        } else {
            // No tiene permiso de recuperar, entonces no encontrara los eliminados
            $f[] = "estatus != 'B'";
        }
        // Siempre debe haber por lo menos un filtro
        if (count($f) == 0) {
            throw new \Base\BusquedaHTMLExceptionValidacion('Aviso: Búsqueda vacía. Debe usar por lo menos un campo.');
        }
        $filtros_sql = implode(' AND ', $f);
        $msg         = 'Buscó módulos con '.implode(', ', $m);
        // Agregar a la bitacora que se busco
        $bitacora = new \Bitacora\Registro($this->sesion);
        $bitacora->agregar_busco($msg);
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("SELECT id FROM modulos WHERE $filtros_sql");
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al buscar módulos.', $e->getMessage());
        }
        // Se considera consultado
        $this->consultado = true;
        // Si la cantidad de registros es mayor a uno
        if ($consulta->cantidad_registros() > 1) {
            // Hay resultados
            $this->hay_resultados = true;
            // Entregar listado
            $listado                 = new ListadoHTML($this->sesion);
            $listado->nombre         = $this->nombre;
            $listado->clave          = $this->clave;
            $listado->permiso_maximo = $this->permiso_maximo;
            $listado->poder_minimo   = $this->poder_minimo;
            $listado->estatus        = $this->estatus;
            return $listado;
        } elseif ($consulta->cantidad_registros() == 1) {
            // Hay resultados
            $this->hay_resultados = true;
            // La cantidad de registros es uno, entregar detalle
            $a           = $consulta->obtener_registro();
            $detalle     = new DetalleHTML($this->sesion);
            $detalle->id = intval($a['id']);
            return $detalle;
        } else {
            // No se encontró nada
            throw new \Base\BusquedaHTMLExceptionVacio('Aviso: La búsqueda no encontró módulos con esos parámetros.');
        }
    } // consultar

} // Clase BusquedaHTML

?>
