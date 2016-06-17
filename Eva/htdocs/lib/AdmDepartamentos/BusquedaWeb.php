<?php
/**
 * GenesisPHP - AdmDepartamentos BusquedaWeb
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

namespace AdmDepartamentos;

/**
 * Clase BusquedaWeb
 */
class BusquedaWeb extends \Base2\BusquedaWeb {

    // public $hay_resultados;
    // public $entrego_detalle;
    // public $hay_mensaje;
    // public $resultado;
    // protected $sesion;
    // protected $consultado;
    // protected $javascript;
    protected $nombre;  // Filtro texto
    protected $estatus; // Filtro caracter
    static public $form_name = 'departamentos_busqueda';

    /**
     * Validar
     */
    public function validar() {
        // Validamos las propiedades
        if (($this->nombre != '') && !\Base2\UtileriasParaValidar::validar_nombre($this->nombre)) {
            throw new \Base2\BusquedaExceptionValidacion('Aviso: Nombre incorrecto.');
        }
        if (($this->estatus != '') && !array_key_exists($this->estatus, Registro::$estatus_descripciones)) {
            throw new \Base2\BusquedaExceptionValidacion('Aviso: Estatus incorrecto.');
        }
    } // validar

    /**
     * Elaborar formulario
     *
     * @param  string Encabezado opcional
     * @return string HTML con el formulario
     */
    protected function elaborar_formulario($in_encabezado='') {
        // Formulario
        $f = new \Base2\FormularioWeb(self::$form_name);
        $f->texto_nombre('nombre', 'Nombre', $this->nombre, 48);
        if ($this->sesion->puede_recuperar('adm_departamentos')) {
            $f->select_con_nulo('estatus', 'Estatus', Registro::$estatus_descripciones, $this->estatus);
        }
        $f->boton_buscar();
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = "Buscar departamentos";
        }
        // Entregar
        return $f->html($encabezado, $this->sesion->menu->icono_en('adm_departamentos'));
    } // elaborar_formulario

    /**
     * Recibir Formulario
     *
     * @return boolean Verdadero si se recibió el formulario
     */
    public function recibir_formulario() {
        // Si viene el formulario
        if ($_POST['formulario'] == self::$form_name) {
            // Cargar propiedades
            $this->nombre = \Base2\UtileriasParaFormularios::post_texto($_POST['nombre']);
            if ($this->sesion->puede_recuperar('adm_departamentos')) {
                $this->estatus = \Base2\UtileriasParaFormularios::post_select($_POST['estatus']);
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
     * @return mixed Instancia con lo encontrado, falso si no se encontró nada
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
        if ($this->sesion->puede_recuperar('adm_departamentos')) {
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
            throw new \Base2\BusquedaExceptionValidacion('Aviso: Búsqueda vacía. Debe usar por lo menos un campo.');
        }
        $filtros_sql = implode(' AND ', $f);
        $msg         = 'Buscó departamentos con '.implode(', ', $m);
        // Agregar a la bitacora que se busco
        $bitacora = new \AdmBitacora\Registro($this->sesion);
        $bitacora->agregar_busco($msg);
        // Consultar
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("SELECT id FROM adm_departamentos WHERE $filtros_sql");
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al buscar departamentos.', $e->getMessage());
        }
        // Se considera consultado
        $this->consultado = true;
        // Si la cantidad de registros es mayor a uno
        if ($consulta->cantidad_registros() > 1) {
            // Hay resultados
            $this->hay_resultados  = true;
            $this->entrego_detalle = false;
            // Entregar listado
            $listado          = new ListadoWeb($this->sesion);
            $listado->nombre  = $this->nombre;
            $listado->estatus = $this->estatus;
            return $listado;
        } elseif ($consulta->cantidad_registros() == 1) {
            // Hay resultados
            $this->hay_resultados  = true;
            $this->entrego_detalle = true;
            // La cantidad de registros es uno, entregar detalle
            $a           = $consulta->obtener_registro();
            $detalle     = new DetalleWeb($this->sesion);
            $detalle->id = intval($a['id']);
            return $detalle;
        } else {
            // No se encontró nada
            throw new \Base2\BusquedaExceptionVacio('Aviso: La búsqueda no encontró departamentos con esos parámetros.');
        }
    } // consultar

} // Clase BusquedaWeb

?>
