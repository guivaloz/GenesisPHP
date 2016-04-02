<?php
/**
 * GenesisPHP - AdmUsuarios Listado
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

namespace AdmUsuarios;

/**
 * Clase Listado
 */
class Listado extends \Base\Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    public $nom_corto;  // Filtro, fragmento de texto
    public $nombre;     // Filtro, fragmento de texto
    public $tipo;       // Filtro, caracter
    public $estatus;    // Filtro, caracter
    static public $param_nom_corto = 'unc';
    static public $param_nombre    = 'un';
    static public $param_tipo      = 'ut';
    static public $param_estatus   = 'us';
    public $filtros_param;

    /**
     * Validar
     */
    public function validar() {
        // Validar permiso
        if (!$this->sesion->puede_ver('usuarios')) {
            throw new \Exception('Aviso: No tiene permiso para ver los usuarios.');
        }
        // Validar filtros
        if (($this->nom_corto != '') && !validar_nom_corto($this->nom_corto)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Nombre corto incorrecto.');
        }
        if (($this->nombre != '') && !validar_nombre($this->nombre)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Nombre incorrecto.');
        }
        if (($this->tipo != '') && !array_key_exists($this->tipo, Registro::$tipo_descripciones)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Tipo incorrecto.');
        }
        if (($this->estatus != '') && !array_key_exists($this->estatus, Registro::$estatus_descripciones)) {
            throw new \Base\ListadoExceptionValidacion('Aviso: Estatus incorrecto.');
        }
        // Reseteamos el arreglo asociativo
        $this->filtros_param = array();
        // Pasar los filtros como parametros de los botones
        if ($this->nom_corto != '') {
            $this->filtros_param[self::$param_nom_corto] = $this->nom_corto;
        }
        if ($this->nombre != '') {
            $this->filtros_param[self::$param_nombre] = $this->nombre;
        }
        if ($this->tipo != '') {
            $this->filtros_param[self::$param_tipo] = $this->tipo;
        }
        if ($this->estatus != '') {
            $this->filtros_param[self::$param_estatus] = $this->estatus;
        }
        // Ejecutar padre
        parent::validar();
    } // validar

    /**
     * Encabezado
     *
     * @return string Texto del encabezado
     */
    public function encabezado() {
        // En este arreglo juntaremos los elementos del encabezado
        $e = array();
        // Juntar los elementos del encabezado
        if ($this->nom_corto != '') {
            $e[] = "nombre corto {$this->nom_corto}";
        }
        if ($this->nombre != '') {
            $e[] = "nombre {$this->nombre}";
        }
        if ($this->tipo != '') {
            $e[] = "tipo {$this->tipo}";
        }
        if ($this->estatus != '') {
            $e[] = "estatus ".Registro::$estatus_descripciones[$this->estatus];
        }
        // Definimos el encabezado
        if (count($e) > 0) {
            if ($this->cantidad_registros > 0) {
                $encabezado = sprintf('%d Usuarios con %s', $this->cantidad_registros, implode(", ", $e));
            } else {
                $encabezado = sprintf('Usuarios con %s', implode(", ", $e));
            }
        } else {
            $encabezado = 'Usuarios';
        }
        // Entregamos
        return $encabezado;
    } // encabezado

    /**
     * Consultar
     */
    public function consultar() {
        // Validar
        $this->validar();
        // Filtros
        $filtros = array();
        if ($this->nom_corto != '') {
            $filtros[] = "nom_corto ILIKE '%{$this->nom_corto}%'";
        }
        if ($this->nombre != '') {
            $filtros[] = "nombre ILIKE '%{$this->nombre}%'";
        }
        if ($this->tipo != '') {
            $filtros[] = "tipo = '{$this->tipo}'";
        }
        if ($this->estatus != '') {
            $filtros[] = "estatus = '{$this->estatus}'";
        }
        if (count($filtros) > 0) {
            $filtros_sql = 'WHERE '.implode(' AND ', $filtros);
        } else {
            $filtros_sql = '';
        }
        // Consultar
        $base_datos = new \Base\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    id, nom_corto, nombre,
                    contrasena, contrasena_encriptada, contrasena_expira, contrasena_fallas,
                    tipo, sesiones_contador, sesiones_maximas,
                    to_char(sesiones_ultima, 'YYYY-MM-DD, HH24:MI') as sesiones_ultima,
                    estatus
                FROM
                    adm_usuarios
                %s
                ORDER BY
                    nom_corto ASC
                %s",
                $filtros_sql,
                $this->limit_offset_sql()));
        } catch (\Exception $e) {
            throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar usuarios para hacer listado.', $e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base\ListadoExceptionVacio('Aviso: No se encontraron usuarios.');
        }
        // Agregar columnas de contraseña, expira y sesiones
        $terminado = array();
        $hoy       = floor(strtotime(date('Y-m-d'))/(60*60*24));
        foreach ($consulta->obtener_todos_los_registros() as $a) {
            $cd  = array(); // Contraseña descrito
            $cdc = 'A';
            $ee  = array(); // Expira en
            $eec = 'A';
            $se  = array(); // Sesiones
            $sec = 'A';
            if ($a['estatus'] == 'A') {
                if ($a['contrasena_encriptada'] == '') {
                    $cd[] = 'NO CIFRADA';
                    $cdc  = 'N';
                }
                if ($a['contrasena_fallas'] >= \Inicio\Autentificar::$fallas_para_bloquear) {
                    $cd[] = 'BLOQUEADA';
                    $cdc  = 'B';
                }
                if ($a['contrasena_expira'] == '') {
                    $ee[] = 'Nunca';
                    $eec  = 'N';
                } else {
                    $expira = floor(strtotime($a['contrasena_expira'])/(60*60*24));
                    if ($expira > $hoy) {
                        $ee[] = ($expira - $hoy).' días';
                        $eec  = 'A';
                    } else {
                        if ($expira == $hoy) {
                            $ee[] = 'EXPIRÓ HOY';
                        } else {
                            $ee[] = 'EXPIRÓ HACE '.($hoy - $expira).' DÍAS';
                        }
                        $eec = 'E';
                    }
                }
                if ($a['sesiones_contador'] >= $a['sesiones_maximas']) {
                    $se[] = 'BLOQUEADA';
                    $sec  = 'B';
                } else {
                    $se[] = $a['sesiones_contador'].' de '.$a['sesiones_maximas'];
                    $sec  = 'A';
                }
            } else {
                $cd[] = 'INACTIVO';
                $cdc  = 'I';
                $ee[] = 'INACTIVO';
                $eec  = 'I';
                $se[] = 'INACTIVO';
                $sec  = 'I';
            }
            // Nuevas columnas
            $a['contrasena_descrito']       = (count($cd) > 0) ? implode('. ', $cd) : 'Bien.';
            $a['contrasena_descrito_color'] = $cdc;
            $a['expira_en']                 = (count($ee) > 0) ? implode('. ', $ee) : 'Nulo.';
            $a['expira_en_color']           = $eec;
            $a['sesiones_contador']         = (count($se) > 0) ? implode('. ', $se) : $a['sesiones_contador'];
            $a['sesiones_contador_color']   = $sec;
            // Renglon listo
            $terminado[] = $a;
        }
        // Pasamos la consulta a la propiedad listado
        $this->listado = $terminado;
        // Consultar la cantidad de registros
        if (($this->limit > 0) && ($this->cantidad_registros == 0)) {
            try {
                $consulta = $base_datos->comando(sprintf("SELECT COUNT(id) AS cantidad FROM adm_usuarios %s", $filtros_sql));
            } catch (\Exception $e) {
                throw new \Base\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar los usuarios para determinar la cantidad de registros.', $e->getMessage());
            }
            $a = $consulta->obtener_registro();
            $this->cantidad_registros = intval($a['cantidad']);
        }
    } // consultar

} // Clase Listado

?>
