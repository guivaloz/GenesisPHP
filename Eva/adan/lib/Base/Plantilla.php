<?php
/**
 * GenesisPHP - Base Plantilla
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

namespace Base;

/**
 * Clase Plantilla
 */
class Plantilla {

    protected $primary_key;        // Nombre de la columna primary key
    protected $adan;               // Instancia de Adan
    protected $tabla_nombre;       // Texto, nombre de la tabla principal
    protected $tabla;              // Datos de la tabla
    protected $reptil;             // Datos de reptil
    protected $relaciones;         // Datos de las relaciones
    protected $hijos;              // Datos de los hijos
    protected $padre;              // Datos del padre
    protected $sustituciones;      // Arreglo asociativo con las sustituciones
    protected $instancia_singular; // Texto, nombre de la variable para una instancia de este mismo modulo
    protected $imagen;             // Arreglo asociativo, si gestiona imagenes
    protected $mapa;               // Arreglo asociativo, si gestiona mapas
    protected $impresion;          // Arreglo asociativo, si gestiona impresiones
    protected $estatus;            // Arreglo asociativo con datos del estatus
    protected $columnas_caracteres_maximo;
    protected $etiquetas_caracteres_maximo;

    /**
     * Constructor
     *
     * @param mixed Objeto Adan con las propiedades
     */
    public function __construct($in_adan) {
        // Recibir Adan
        $this->adan = $in_adan;
        // El nombre de la columna primary key
        if (is_string($this->adan->primary_key) && ($this->adan->primary_key != '')) {
            $this->primary_key = $this->adan->primary_key;
        } elseif (is_bool($this->adan->primary_key) && ($this->adan->primary_key === false)) {
            $this->primary_key = false;
        } else {
            $this->primary_key = 'id';
        }
        // El nombre de la tabla principal puede ser distinta al nombre del modulo
        if ($this->adan->tabla_nombre != '') {
            $this->tabla_nombre = $this->adan->tabla_nombre;
        } elseif ($this->adan->nombre != '') {
            $this->tabla_nombre = $this->adan->nombre;
        } else {
            die('Error en Plantilla: No se puede determinar el nombre de la tabla principal.');
        }
        // Copiar de adan los datos de la tabla
        if (is_array($this->adan->tabla) && (count($this->adan->tabla) > 0)) {
            $this->tabla = $this->adan->tabla;
        } else {
            die('Error en Plantilla: La propiedad tabla no es un arreglo o está vacío.');
        }
        // Copiar de Adan el reptil
        $clase_nombre = get_class($in_adan);
        if (is_array($clase_nombre::$reptil)) {
            $this->reptil = $clase_nombre::$reptil;
        } else {
            die('Error en Plantilla: No está definido correctamente el reptil en la semilla.');
        }
        // Copiar de adan las relaciones
        if (is_array($this->adan->relaciones) && (count($this->adan->relaciones) > 0)) {
            $this->relaciones = $this->adan->relaciones;
        } else {
            $this->relaciones = array();
        }
        // Copiar de adan los hijos
        if (is_array($this->adan->hijos) && (count($this->adan->hijos) > 0)) {
            $this->hijos = $this->adan->hijos;
        } else {
            $this->hijos = false;
        }
        // Copiar de adan el padre
        if (is_array($this->adan->padre) && (count($this->adan->padre) > 0)) {
            $this->padre = $this->adan->padre;
        } else {
            $this->padre = false;
        }
        // Copiar de Adan las sustituciones
        if (is_array($this->adan->sustituciones) && (count($this->adan->sustituciones) > 0)) {
            $this->sustituciones = $this->adan->sustituciones;
        } else {
            die('Error en Plantilla: La propiedad sustituciones no es un arreglo o está vacío.');
        }
        // Copiar de Adan la instancia singular
        if (is_string($this->adan->instancia_singular) && ($this->adan->instancia_singular != '')) {
            $this->instancia_singular = $this->adan->instancia_singular;
        } else {
            die('Error en Plantilla: La propiedad instancia_singular NO está definida.');
        }
        // Copiar de Adan la imagen
        if (is_array($this->adan->imagen) && (count($this->adan->imagen) > 0)) {
            $this->imagen = $this->adan->imagen;
        } else {
            $this->imagen = false;
        }
        // Copiar de Adan el mapa
        if (is_array($this->adan->mapa) && (count($this->adan->mapa) > 0)) {
            $this->mapa = $this->adan->mapa;
        } else {
            $this->mapa = false;
        }
        // Copiar de Adan la impresion
        if (is_array($this->adan->impresion) && (count($this->adan->impresion) > 0)) {
            $this->impresion = $this->adan->impresion;
        } else {
            $this->impresion = false;
        }
        // Si en la tabla existe el estatus
        if (is_array($this->tabla['estatus']) && (count($this->tabla['estatus']) > 0)) {
            // Si tiene contenido la propiedad estatus
            if (is_array($this->adan->estatus) && (count($this->adan->estatus) > 0)) {
                // Validamos que cada caracter este presente en la tabla
                foreach ($this->adan->estatus as $accion => $caracter) {
                    if (!isset($this->tabla['estatus']['descripciones'][$caracter])) {
                        die("Error en Plantilla: Revise la tabla porque el caracter de estatus $caracter NO está en descripciones.");
                    }
                    if (!isset($this->tabla['estatus']['etiquetas'][$caracter])) {
                        die("Error en Plantilla: Revise la tabla porque el caracter de estatus $caracter NO está en etiquetas.");
                    }
                    if (!isset($this->tabla['estatus']['acciones'][$caracter])) {
                        die("Error en Plantilla: Revise la tabla porque el caracter de estatus $caracter NO está en acciones.");
                    }
                }
                // Copiar estatus
                $this->estatus = $this->adan->estatus;
            } else {
                // Por defecto, el estatus puede ser A o B
                $this->estatus = array(
                    'enuso'     => 'A',
                    'eliminado' => 'B');
            }
        } else {
            // No hay columna estatus, no habra listados separados para en uso y eliminados
            $this->estatus = false;
        }
        // Determinar la cantidad de caracteres de las columnas
        $this->columnas_caracteres_maximo = 0;
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['tipo'] == 'caracter') {
                $columna = "{$columna}_descrito";
            }
            if (strlen($columna) > $this->columnas_caracteres_maximo) {
                $this->columnas_caracteres_maximo = strlen($columna);
            }
        }
        // Determinar la cantidad de caracteres de las etiquetas
        $this->etiquetas_caracteres_maximo = 0;
        foreach ($this->tabla as $columna => $datos) {
            if (mb_strlen($datos['etiqueta']) > $this->etiquetas_caracteres_maximo) {
                $this->etiquetas_caracteres_maximo = mb_strlen($datos['etiqueta']);
            }
        }
    } // Constructor

    /**
     * Columnas VIP
     *
     * @return array Las columnas VIP de la tabla, pero también detecta las que sean relaciones y de tipo caracter
     */
    protected function columnas_vip() {
        // Acumularemos en estos arreglos
        $propiedades_1 = array();
        $propiedades_2 = array();
        $propiedades_3 = array();
        // Juntar columnas vip para el encabezado y mensaje de la bitacora
        foreach ($this->tabla as $columna => $datos) {
            // Si es vip
            if ($datos['vip'] > 0) {
                // Si es una relacion
                if ($datos['tipo'] == 'relacion') {
                    // Se va usar mucho la relacion, asi que para simplificar
                    $nivel_2 = $this->relaciones[$columna];
                    if (!is_array($nivel_2)) {
                        die("Error en Plantilla: Falta obtener datos de Serpiente para la relación $columna.");
                    }
                    foreach ($nivel_2['vip'] as $clave_2 => $datos_2) {
                        if ($datos_2['tipo'] == 'relacion') {
                            // La relacion es otra relacion
                            $nivel_3 = $this->relaciones[$clave_2];
                            if (!is_array($nivel_3)) {
                                die("Error en Plantilla: Falta obtener datos de Serpiente para la relación $clave_2.");
                            }
                            foreach ($nivel_3['vip'] as $clave_3 => $datos_3) {
                                if ($datos_3['tipo'] == 'relacion') {
                                    // $propiedades_3[] = "";
                                } elseif ($datos_3['tipo'] == 'caracter') {
                                    $propiedades_3[] = "{$clave_2}_{$clave_3}_descrito";
                                } else {
                                    $propiedades_3[] = "{$clave_2}_{$clave_3}";
                                }
                            }
                        } elseif ($datos_2['tipo'] == 'caracter') {
                            // Ese vip de la relacion es de tipo caracter
                            $propiedades_2[] = "{$columna}_{$clave_2}_descrito";
                        } else {
                            // Ese vip de la relacion es de otro tipo
                            $propiedades_2[] = "{$columna}_{$clave_2}";
                        }
                    }
                } elseif ($datos['tipo'] == 'caracter') {
                    // Es de tipo caracter
                    $propiedades_1[] = "{$columna}_descrito";
                } else {
                    // Es cualquier otro tipo
                    $propiedades_1[] = $columna;
                }
            }
        }
        // Juntar
        $a = array();
        if (count($propiedades_3) > 0) {
            $a = array_merge($a, $propiedades_3);
        }
        if (count($propiedades_2) > 0) {
            $a = array_merge($a, $propiedades_2);
        }
        if (count($propiedades_1) > 0) {
            $a = array_merge($a, $propiedades_1);
        }
        // Entregar
        return $a;
    } // columnas_vip

    /**
     * Columnas VIP para mensaje
     *
     * @return string Toma de la tabla las columnas VIP para poner en un mensaje
     */
    protected function columnas_vip_para_mensaje() {
        $a = array();
        foreach ($this->columnas_vip() as $v) {
            $a[] = sprintf('{$this->%s}', $v);
        }
        return implode(" / ", $a);
    } // columnas_vip_para_mensaje

    /**
     * Columnas VIP de listado
     *
     * @param  string Nombre de la variable que tenga el listado
     * @return string Toma de la tabla las columnas VIP que vienen en un listado
     */
    protected function columnas_vip_de_listado($variable='a') {
        $a = array();
        foreach ($this->columnas_vip() as $v) {
            $a[] = sprintf("{\$%s['%s']}", $variable, $v);
        }
        return implode(", ", $a);
    } // columnas_vip_para_mensaje

    /**
     * Sustituir SED
     *
     * @param  string Código sin sustituir con las claves SED
     * @return string Código con las sustituciones hechas
     */
    public function sustituir_sed($in_contenido) {
        // Eliminar los dobles avances de linea
        // $contenido = str_replace("\n\n", "\n", $in_contenido);
        // Separar lo que se va a buscar y lo que se va a cambiar
        $voy_a_buscar = array();
        $cambiar_por  = array();
        foreach ($this->sustituciones as $clave => $valor) {
            $voy_a_buscar[] = $clave;
            $cambiar_por[]  = $valor;
        }
        // Sustituir y entregar
        return str_replace($voy_a_buscar, $cambiar_por, $in_contenido);
    } // sustituir

    /**
     * PHP Comentado
     *
     * @return string Código PHP
     */
    public function php_comentado() {
        // Separamos el codigo php a un arreglo
        $anterior = explode("\n", $this->php());
        // Guardaremos el codigo comentado en este arreglo
        $nuevo    = array();
        // Para cada linea
        foreach ($anterior as $a) {
            if ($a === '') {
                continue;
            } elseif (preg_match('/^\s+\/\/.*/', $a) === 1) {
                $nuevo[] = $a;
            } elseif (preg_match('/^(\s+)(\w.*)/', $a, $separado) === 1) {
                $nuevo[] = $separado[1].'// '.$separado[2];
            }
        }
        // Entregamos el codigo php comentado
        return implode("\n", $nuevo);
    } // php_comentado

} // Clase Plantilla

?>
