<?php
/**
 * GenesisPHP - AdmBitacora BusquedaWeb
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
 * Clase BusquedaWeb
 */
class BusquedaWeb extends \Base2\BusquedaWeb implements \Base2\SalidaWeb {

    // public $hay_resultados;
    // public $entrego_detalle;
    // public $hay_mensaje;
    // public $resultado;
    // protected $sesion;
    // protected $consultado;
    // protected $javascript;
    public $usuario;                      // Filtro, entero
    public $usuario_nombre;
    public $tipo;                         // Filtro, caracter
    public $fecha_desde;                  // Filtro, fecha
    public $fecha_hasta;                  // Filtro, fecha
    static public $form_name = 'bitacora_busqueda';

    /**
     * Validar
     */
    public function validar() {
        // Validar usuario
        if ($this->usuario != '') {
            $usuario = new \AdmUsuarios\Registro($this->sesion);
            try {
                $usuario->consultar($this->usuario);
            } catch (\Base2\RegistroExceptionValidacion $e) {
                throw new \Base2\ListadoExceptionValidacion('Aviso: Usuario incorrecto.');
            }
            $this->usuario_nombre = $usuario->nombre;
        } else {
            $this->usuario_nombre = '';
        }
        // Validar tipo
        if (($this->tipo != '') && !array_key_exists($this->tipo, Registro::$tipo_descripciones)) {
            throw new \Base2\BusquedaExceptionValidacion('Aviso: Tipo incorrecto.');
        }
        // Validar fechas
        if (($this->fecha_desde != '') && !\Base2\UtileriasParaValidar::validar_fecha($this->fecha_desde)) {
            throw new \Base2\BusquedaExceptionValidacion('Aviso: Fecha desde incorrecta.');
        }
        if (($this->fecha_hasta != '') && !\Base2\UtileriasParaValidar::validar_fecha($this->fecha_hasta)) {
            throw new \Base2\BusquedaExceptionValidacion('Aviso: Fecha hasta incorrecta.');
        }
    } // validar

    /**
     * Elaborar formulario
     *
     * @param  string Encabezado opcional
     * @return string HTML con el formulario
     */
    protected function elaborar_formulario($in_encabezado='') {
        // Opciones para escoger el usuario
        $usuarios = new \AdmUsuarios\OpcionesSelect($this->sesion);
        // Formulario
        $f = new \Base2\FormularioWeb(self::$form_name);
        $f->select_con_nulo('usuario', 'Usuario', $usuarios->opciones(),         $this->usuario);
        $f->select_con_nulo('tipo',    'Tipo',    Registro::$tipo_descripciones, $this->tipo);
        $f->rango_fechas('fecha',      'Fecha',   $this->fecha_desde, $this->fecha_hasta);
        $f->boton_buscar();
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = "Buscar en bitácora";
        }
        // Agregar javascript
        $this->javascript[] = $f->javascript();
        // Entregar
        return $f->html($encabezado, $this->sesion->menu->icono_en('adm_bitacora'));
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
            $this->usuario     = \Base2\UtileriasParaFormularios::post_select($_POST['usuario']);
            $this->tipo        = \Base2\UtileriasParaFormularios::post_select($_POST['tipo']);
            $this->fecha_desde = $_POST['fecha_desde'];
            $this->fecha_hasta = $_POST['fecha_hasta'];
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
        if ($this->usuario != '') {
            $f[] = "usuario = {$this->usuario}";
            $m[] = "ID de usuario {$this->usuario}";
        }
        if ($this->tipo != '') {
            $f[] = "tipo = '{$this->tipo}'";
            $m[] = "tipo ".Registro::$tipo_descripciones[$this->tipo];
        }
        if ($this->fecha_desde != '') {
            $f[] = "fecha >= '{$this->fecha_desde} 00:00:00'";
            $m[] = "desde {$this->fecha_desde}";
        }
        if ($this->fecha_hasta != '') {
            $f[] = "fecha <= '{$this->fecha_hasta} 23:59:59'";
            $m[] = "hasta {$this->fecha_hasta}";
        }
        // Siempre debe haber por lo menos un filtro
        if (count($f) == 0) {
            throw new \Base2\BusquedaExceptionValidacion('Aviso: Búsqueda vacía. Debe usar por lo menos un campo.');
        }
        $filtros_sql = implode(' AND ', $f);
        $msg         = 'Buscó en bitácora con '.implode(', ', $m);
        // Agregar a la bitacora que se busco
    //  $bitacora = new \AdmBitacora\Registro($this->sesion);
    //  $bitacora->agregar_busco($msg);
        // Consultar
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando("SELECT id FROM adm_bitacora WHERE $filtros_sql");
        } catch (\Exception $e) {
            throw new \AdmBitacora\BaseDatosExceptionSQLError($this->sesion, 'Error SQL: Al buscar en bitácora.', $e->getMessage());
        }
        // Se considera consultado
        $this->consultado = true;
        // Si la cantidad de registros es mayor a uno
        if ($consulta->cantidad_registros() > 1) {
            // Hay resultados
            $this->hay_resultados = true;
            // Entregar listado
            $listado              = new ListadoWeb($this->sesion);
            $listado->usuario     = $this->usuario;
            $listado->tipo        = $this->tipo;
            $listado->fecha_desde = $this->fecha_desde;
            $listado->fecha_hasta = $this->fecha_hasta;
            return $listado;
        } elseif ($consulta->cantidad_registros() == 1) {
            // Hay resultados
            $this->hay_resultados = true;
            // La cantidad de registros es uno, entregar detalle
            $a           = $consulta->obtener_registro();
            $detalle     = new DetalleWeb($this->sesion);
            $detalle->id = intval($a['id']);
            return $detalle;
        } else {
            // No se encontró nada
            throw new \Base2\BusquedaExceptionVacio('Aviso: La búsqueda no encontró registros en bitácora con esos parámetros.');
        }
    } // consultar

} // Clase BusquedaWeb

?>
