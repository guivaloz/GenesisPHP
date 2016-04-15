<?php
/**
 * GenesisPHP - BusquedaHTML Consultar
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

namespace BusquedaHTML;

/**
 * Clase Consultar
 */
class Consultar extends \Base\Plantilla {


    /**
     * Elaborar Consultar Filtros Campo
     *
     * Subrutina para elaborar_consultar_filtros
     *
     * @param  string Nombre de la tabla SQL a agregar a la columna. Si es vacio no se agrega.
     * @param  string Columna de la tabla SQL
     * @param  string Propiedad que es el filtro
     * @param  array  Datos declarados para esa columna en la semilla
     * @param  mixed  Opcional. Si este campo es de una relación, se debe dar la misma
     * @param  string Opcional. Columna de la tabla relacionada
     * @return string Código PHP
     */
    protected function elaborar_consultar_filtros_campo($tabla, $columna, $filtro, $datos, $relacion=null, $relacion_columna=null) {
        // Si hay tabla
        if ($tabla != '') {
            $tc = "{$tabla}.{$columna}";
        } else {
            $tc = $columna;
        }
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // De acuerdo al tipo agregar filtro sql y mensaje
        switch ($datos['tipo']) {
            case 'clave':
            case 'curp':
            case 'email':
            case 'frase':
            case 'mayusculas':
            case 'nombre':
            case 'nom_corto':
            case 'notas':
            case 'rfc':
            case 'telefono':
            case 'variable':
                $a[] = "        if (\$this->$filtro != '') {";
                $a[] = "            \$f[] = \"$tc ILIKE '%{\$this->$filtro}%'\";";
                $a[] = "            \$m[] = \"{$datos['etiqueta']} {\$this->$filtro}\";";
                $a[] = "        }";
                break;
            case 'caracter':
                $a[] = "        if (\$this->$filtro != '') {";
                $a[] = "            \$f[] = \"$tc = '{\$this->$filtro}'\";";
                if (is_array($relacion)) {
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} \".\\{$relacion['clase_plural']}\\Registro::\${$relacion_columna}_descripciones[\$this->$filtro];";
                } else {
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} \".Registro::\${$columna}_descripciones[\$this->$filtro];";
                }
                $a[] = "        }";
                break;
            case 'fecha':
            case 'fecha_hora':
                if ($datos['filtro'] > 1) {
                    $a[] = "        if (\$this->{$filtro}_desde != '') {";
                    $a[] = "            \$f[] = \"$tc >= '{\$this->{$filtro}_desde}'\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} desde {\$this->{$filtro}_desde}\";";
                    $a[] = "        }";
                    $a[] = "        if (\$this->{$filtro}_hasta != '') {";
                    $a[] = "            \$f[] = \"$tc <= '{\$this->{$filtro}_hasta}'\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} hasta {\$this->{$filtro}_hasta}\";";
                    $a[] = "        }";
                } else {
                    $a[] = "        if (\$this->$filtro != '') {";
                    $a[] = "            \$f[] = \"$tc = '{\$this->$filtro}'\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} {\$this->$filtro}\";";
                    $a[] = "        }";
                }
                break;
            case 'entero':
            case 'serial':
                if ($datos['filtro'] > 1) {
                    $a[] = "        if (\$this->{$filtro}_desde != '') {";
                    $a[] = "            \$f[] = \"$tc >= {\$this->{$filtro}_desde}\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} desde {\$this->{$filtro}_desde}\";";
                    $a[] = "        }";
                    $a[] = "        if (\$this->{$filtro}_hasta != '') {";
                    $a[] = "            \$f[] = \"$tc <= {\$this->{$filtro}_hasta}\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} hasta {\$this->{$filtro}_hasta}\";";
                    $a[] = "        }";
                } else {
                    $a[] = "        if (\$this->$filtro != '') {";
                    $a[] = "            \$f[] = \"$tc = {\$this->$filtro}\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} {\$this->$filtro}\";";
                    $a[] = "        }";
                }
                break;
            case 'flotante':
            case 'dinero':
            case 'porcentaje':
            case 'peso':
            case 'estatura':
                if ($datos['filtro'] > 1) {
                    $a[] = "        if (\$this->{$filtro}_desde != '') {";
                    $a[] = "            \$f[] = \"$tc >= '{\$this->{$filtro}_desde}'\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} desde {\$this->{$filtro}_desde}\";";
                    $a[] = "        }";
                    $a[] = "        if (\$this->{$filtro}_hasta != '') {";
                    $a[] = "            \$f[] = \"$tc <= '{\$this->{$filtro}_hasta}'\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} hasta {\$this->{$filtro}_hasta}\";";
                    $a[] = "        }";
                } else {
                    $a[] = "        if (\$this->$filtro != '') {";
                    $a[] = "            \$f[] = \"$tc = '{\$this->$filtro}'\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} {\$this->$filtro}\";";
                    $a[] = "        }";
                }
                break;
            case 'relacion':
                $a = false;
                break;
            default:
                die("Error en BusquedaHTML, Consultar, elaborar_consultar_filtros_campo: Tipo {$datos['tipo']} no programado al elaborar consultar en $columna.");
        }
        // Entregar
        if ($a === false) {
            return "        // Se omite la columna $columna porque su tipo es {$datos['tipo']}";
        } else {
            return implode("\n", $a);
        }
    } // elaborar_consultar_filtros_campo

    /**
     * Elaborar Consultar Filtros Relacion
     *
     * Subrutina para elaborar_consultar_filtros
     *
     * @param  string Nombre de la tabla SQL a agregar a la columna. Si es vacio no se agrega.
     * @param  string Columna de la tabla SQL
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_consultar_filtros_relacion($tabla, $columna, $datos) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Se va usar mucho la relacion, asi que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion = $this->relaciones[$columna];
        } else {
            die("Error en BusquedaHTML, Consultar, elaborar_consultar_filtros_relacion: Falta obtener datos de Serpiente para la relación $columna.");
        }
        //
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = "        if (\$this->{$columna} != '') {";
            $a[] = "            \$f[] = \"{$relacion['tabla']}.{$columna} = {\$this->{$columna}}\";";
            $a[] = "            \$m[] = \"{$datos['etiqueta']} {\$this->{$columna}}\";";
            $a[] = "        }";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = $this->elaborar_consultar_filtros_campo($this->relaciones[$vip]['tabla'], $v, "{$vip}_{$v}", $vd, $this->relaciones[$vip], $v);
                                }
                            } else {
                                $a[] = "        if (\$this->{$vip} != '') {";
                                $a[] = "            \$f[] = \"{$vip_datos['tabla']}.{$vip} = {\$this->{$vip}}\";";
                                $a[] = "            \$m[] = \"{$this->relaciones[$vip]['etiqueta_singular']} {\$this->{$vip}}\";";
                                $a[] = "        }";
                            }
                        } else {
                            die("Error en BusquedaHTML, Consultar, elaborar_consultar_filtros_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } else {
                        $a[] = $this->elaborar_consultar_filtros_campo($relacion['tabla'], $vip, "{$columna}_{$vip}", $vip_datos, $relacion, $vip);
                    }
                } else {
                    $a[] = "        if (\$this->{$columna}_{$vip_datos} != '') {";
                    $a[] = "            \$f[] = \"{$relacion['tabla']}.{$vip_datos} = {\$this->{$columna}_{$vip_datos}}\";";
                    $a[] = "            \$m[] = \"{$datos['etiqueta']} {\$this->{$columna}_{$vip_datos}}\";";
                    $a[] = "        }";
                }
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_filtros_relacion

    /**
     * Elaborar Consultar Filtros Estatus
     *
     * Subrutina para elaborar_consultar_filtros
     *
     * @param  string Nombre de la tabla SQL a agregar a la columna. Si es vacio no se agrega.
     * @return string Código PHP
     */
    protected function elaborar_consultar_filtros_estatus($tabla) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Para buscar por estatus, se requiere tener permiso
        $a[] = "        if (\$this->sesion->puede_recuperar('SED_CLAVE')) {";
        $a[] = "            if (\$this->estatus != '') {";
        if ($tabla != '') {
            $a[] = "                \$f[] = \"{$this->tabla_nombre}.estatus = '{\$this->estatus}'\";";
        } else {
            $a[] = "                \$f[] = \"estatus = '{\$this->estatus}'\";";
        }
        $a[] = "                \$m[] = \"estatus \".Registro::\$estatus_descripciones[\$this->estatus];";
        $a[] = "            }";
        $a[] = "        } else {";
        $a[] = "            // No tiene permiso de recuperar, entonces no encontrara los eliminados";
        if ($tabla != '') {
            $a[] = "            \$f[] = \"{$this->tabla_nombre}.estatus != '{$this->estatus['eliminado']}'\";";
        } else {
            $a[] = "            \$f[] = \"estatus != '{$this->estatus['eliminado']}'\";";
        }
        $a[] = "        }";
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_filtros_estatus

    /**
     * Elaborar Consultar Filtros
     *
     * Subrutina para elaborar_consultar
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_filtros() {
        // Determinar si hay o no relaciones
        $hay_relaciones = false;
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['tipo'] == 'relacion') {
                $hay_relaciones = true;
            }
        }
        // Si hay relaciones, hay que usar el nombre de la tabla
        if ($hay_relaciones) {
            $tabla = $this->tabla_nombre;
        } else {
            $tabla = '';
        }
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Bucle a traves todas las columnas de tabla
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no aparece en el formulario
            } elseif ($columna == 'estatus') {
                $a[] = $this->elaborar_consultar_filtros_estatus($tabla);
            } elseif ($datos['tipo'] == 'relacion') {
                $a[] = $this->elaborar_consultar_filtros_relacion($tabla, $columna, $datos);
            } else {
                $a[] = $this->elaborar_consultar_filtros_campo($tabla, $columna, $columna, $datos);
            }
        }
        // Tronar en caso de no haber filtros
        if (count($a) > 0) {
            return implode("\n", $a);
        } else {
            die('Error en BusquedaHTML, Consultar, elaborar_consultar_filtros: No hay columnas con filtro para crear el método recibir formulario.');
        }
    } // elaborar_consultar_filtros

    /**
     * Elaborar Consultar Tablas
     *
     * Subrutina para elaborar_consultar
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_tablas() {
        // Determinar si hay o no relaciones
        $hay_relaciones = false;
        foreach ($this->tabla as $columna => $datos) {
            if ($datos['tipo'] == 'relacion') {
                $hay_relaciones = true;
            }
        }
        // Juntaremos las tablas en estos arreglos
        $tablas  = array();
        $ganchos = array();
        // De inicio, agregar la tabla de este mudulo
        $tablas[] = $this->tabla_nombre;
        // Si hay relaciones
        if ($hay_relaciones) {
            // Mas las tablas de las relaciones
            foreach ($this->tabla as $columna => $datos) {
                if ($datos['tipo'] == 'relacion') {
                    // Primeras relaciones
                    $relacion  = $this->relaciones[$columna];
                    $tablas[]  = $relacion['tabla'];
                    $ganchos[] = "{$this->tabla_nombre}.$columna = {$relacion['tabla']}.id";
                    // Segundas relaciones
                    if (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                        foreach ($relacion['vip'] as $vip => $vip_datos) {
                            if (is_array($vip_datos) && ($vip_datos['tipo'] == 'relacion')) {
                                $tablas[]  = $this->relaciones[$vip]['tabla'];
                                $ganchos[] = "{$relacion['tabla']}.$vip = {$this->relaciones[$vip]['tabla']}.id";
                            }
                        }
                    }
                }
            }
        }
        // En la semilla se puede definir un fragmento de sql, que es una o varias tablas adicionales
        if (is_string($this->adan->tabla_impuesto_sql) && ($this->adan->tabla_impuesto_sql != '')) {
            // Para evitar que se duplique el nombre de la tabla, se agrega si no ha sido añadida
            if (!in_array($this->adan->tabla_impuesto_sql, $tablas)) {
                $tablas[] = $this->adan->tabla_impuesto_sql;
            }
        // Si es un arreglo
        } elseif (is_array($this->adan->tabla_impuesto_sql) && (count($this->adan->tabla_impuesto_sql) > 0)) {
            // Para asegurar que cada tabla aparezca solo una vez
            foreach ($this->adan->tabla_impuesto_sql as $t) {
                if (!in_array($t, $tablas)) {
                    $tablas[] = $tabla;
                }
            }
        }
        // En la semilla se puede definir un fragmento de sql, que es un filtro para el where
        if (is_string($this->adan->filtro_impuesto_sql) && ($this->adan->filtro_impuesto_sql != '')) {
            $ganchos[] = $this->adan->filtro_impuesto_sql;
        } elseif (is_array($this->adan->filtro_impuesto_sql) && (count($this->adan->filtro_impuesto_sql) > 0)) {
            foreach ($this->adan->filtro_impuesto_sql as $f) {
                $ganchos[] = $f;
            }
        }
        // Asegurarse que no haya filtros repetidos
        $filtros = array();
        foreach ($ganchos as $g) {
            if (!in_array($g, $filtros)) {
                $filtros[] = $g;
            }
        }
        // Lo que se va a entregar se juntara en este arreglo
        $a   = array();
        $a[] = "                SELECT";
        if ($hay_relaciones) {
            $a[] = "                    {$this->tabla_nombre}.id";
        } else {
            $a[] = "                    id";
        }
        $a[] = "                FROM";
        // Agregaremos los nombres de las tablas separados por comas
        $c   = 0;
        foreach ($tablas as $t) {
            $c++;
            if ($c < count($tablas)) {
                $a[] = "                    $t,";
            } else {
                $a[] = "                    $t";
            }
        }
        $a[] = "                WHERE";
        // Filtros puestos por génesis
        $es_primero = true;
        foreach ($filtros as $f) {
            if ($es_primero) {
                $a[] = "                    $f";
                $es_primero = false;
            } else {
                $a[] = "                    AND $f";
            }
        }
        // Filtros que pondrá el método
        if ($es_primero) {
            $a[] = "                    \$filtros_sql";
        } else {
            $a[] = "                    AND \$filtros_sql";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_tablas

    /**
     * Elaborar Consultar Resultados Declaración
     *
     * @param  string Instancia.
     * @param  string Columna de la tabla
     * @param  array  Opcional. Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_consultar_resultados_declaracion($instancia, $columna, $datos=false) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Si no vienen los datos, por defecto el filtro es uno
        if ($datos === false) {
            $datos = array('filtro' => 1);
        }
        // Si filtro es mayor a uno es un rango desde-hasta
        if ($datos['filtro'] > 1) {
            // Si el tipo es geopunto
            if ($datos['tipo'] == 'geopunto') {
                // El filtro para geopunto son dos puntos
                $a[] = "            {$instancia}->{$columna}_desde_longitud = \$this->{$columna}_desde_longitud;";
                $a[] = "            {$instancia}->{$columna}_hasta_longitud = \$this->{$columna}_hasta_longitud;";
                $a[] = "            {$instancia}->{$columna}_desde_latitud = \$this->{$columna}_desde_latitud;";
                $a[] = "            {$instancia}->{$columna}_hasta_latitud= \$this->{$columna}_hasta_latitud;";
            } else {
                // Cualquier otro tipo de rango desde-hasta
                $a[] = "            {$instancia}->{$columna}_desde = \$this->{$columna}_desde;";
                $a[] = "            {$instancia}->{$columna}_hasta = \$this->{$columna}_hasta;";
            }
        // Si hay filtro
        } elseif ($datos['filtro'] > 0) {
            // Si el tipo es geopunto
            if ($datos['tipo'] == 'geopunto') {
                // Es geopunto
                $a[] = "            {$instancia}->{$columna}_longitud = \$this->{$columna}_longitud;";
                $a[] = "            {$instancia}->{$columna}_latitud  = \$this->{$columna}_latitud;";
            } elseif ($datos['tipo'] == 'relacion') {
                // Es relacion
                $a[] = "            // {$columna} No se usa por ser relación (id entero)";
            } else {
                // Cualquier otro tipo
                $a[] = "            {$instancia}->{$columna} = \$this->{$columna};";
            }
        } else {
            die("Error en BusquedaHTML, Consultar, elaborar_consultar_resultados_declaracion: No hay valor en filtro para $columna.");
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_resultados_declaracion

    /**
     * Elaborar Consultar Resultados Relación
     *
     * Subrutina para elaborar_consultar_resultados
     *
     * @param  string Nombre de la varible que va ser la instancia. Puede ser $listado o $tren
     * @param  string Columna de la tabla
     * @param  array  Datos declarados para esa columna en la semilla
     * @return string Código PHP
     */
    protected function elaborar_consultar_resultados_relacion($instancia, $columna, $datos) {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Se va usar mucho la relacion, asi que para simplificar
        if (is_array($this->relaciones[$columna])) {
            $relacion = $this->relaciones[$columna];
        } else {
            die("Error en BusquedaHTML, Consultar, elaborar_consultar_resultados_relacion: Falta obtener datos de Serpiente para la relación $columna.");
        }
        // Inicia
        $a[] = "            // Relacion $columna, inicia";
        //
        if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
            $a[] = $this->elaborar_consultar_resultados_declaracion($instancia, $columna);
            //$a[] = "            $instancia->$columna = \$this->$columna;";
        } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
            foreach ($relacion['vip'] as $vip => $vip_datos) {
                if (is_array($vip_datos)) {
                    if ($vip_datos['tipo'] == 'relacion') {
                        if (is_array($this->relaciones[$vip])) {
                            if (is_array($this->relaciones[$vip]['vip'])) {
                                foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                    $a[] = $this->elaborar_consultar_resultados_declaracion($instancia, "{$vip}_{$v}", $vd);
                                    //$a[] = "            $instancia->{$vip}_{$v} = \$this->{$vip}_{$v};";
                                }
                            } else {
                                $a[] = $this->elaborar_consultar_resultados_declaracion($instancia, $vip);
                                //$a[] = "            $instancia->{$vip} = \$this->{$vip};";
                            }
                        } else {
                            die("Error en BusquedaHTML, Consultar, elaborar_consultar_resultados_relacion: No está definido el VIP en Serpiente para $vip.");
                        }
                    } else {
                        $a[] = $this->elaborar_consultar_resultados_declaracion($instancia, "{$columna}_{$vip}", $vip_datos);
                        //$a[] = "            $instancia->{$columna}_{$vip} = \$this->{$columna}_{$vip};";
                    }
                } else {
                    $a[] = $this->elaborar_consultar_resultados_declaracion($instancia, "{$columna}_{$vip_datos}");
                    //$a[] = "            $instancia->{$columna}_{$vip_datos} = \$this->{$columna}_{$vip_datos};";
                }
            }
        }
        // Termina
        $a[] = "            // Relacion $columna, termina";
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_resultados_relacion

    /**
     * Elaborar Consultar Resultados
     *
     * Subrutina para elaborar_consultar
     *
     * @return string Código PHP
     */
    protected function elaborar_consultar_resultados() {
        // Lo que se va a entregar se juntara en este arreglo
        $a = array();
        // Decidir entre listado o tren
        if ($this->adan->si_hay_que_crear('tren')) {
            // Entregar un tren cuando el resultado sea mas de uno va
            $instancia = '$tren';
            $a[] = '            // Entregar tren';
            $a[] = "            $instancia = new TrenHTML(\$this->sesion);";
        } elseif ($this->adan->si_hay_que_crear('listado')) {
            // Entregar un listado cuando el resultado sea mas de uno va
            $instancia = '$listado';
            $a[] = '            // Entregar listado';
            $a[] = "            $instancia = new ListadoHTML(\$this->sesion);";
        } else {
            die('Error en BusquedaHTML, Consultar, elaborar_consultar_resultados: Sin listado y sin tren no se puede mostrar el resultado de la búsqueda.');
        }
        // Para cada columna
        foreach ($this->tabla as $columna => $datos) {
            if (($datos['etiqueta'] == '') || ($datos['filtro'] == 0)) {
                continue; // Si no hay etiqueta o valor en filtro, no aparece en el formulario
            } elseif ($datos['tipo'] == 'relacion') {
                $a[] = $this->elaborar_consultar_resultados_relacion($instancia, $columna, $datos);
        //  } elseif ($datos['filtro'] > 1) {
        //      $a[] = "            $instancia->{$columna}_desde = \$this->{$columna}_desde;";
        //      $a[] = "            $instancia->{$columna}_hasta = \$this->{$columna}_hasta;";
        //  } else {
        //      $a[] = "            $instancia->{$columna} = \$this->$columna;";
            } else {
                $a[] = $this->elaborar_consultar_resultados_declaracion($instancia, $columna, $datos);
            }
        }
        // Cuando la busqueda encuentra dos o mas resultados entrega un listado o un tren
        $a[] = "            return $instancia;";
        // Entregar
        return implode("\n", $a);
    } // elaborar_consultar_resultados

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Consultar
     *
     * @return mixed Objeto con el ListadoHTML, TrenHTML o DetalleHTML, falso si no se encontró nada
     */
    public function consultar() {
        // Definir la bandera de resultados con falso
        \$this->hay_resultados = false;
        // Un arreglo para los filtros y otro para los mensajes
        \$f = array();
        \$m = array();
        // Elaborar los filtros sql y el mensaje
{$this->elaborar_consultar_filtros()}
        // Siempre debe haber por lo menos un filtro
        if (count(\$m) == 0) {
            throw new \\Base\\BusquedaHTMLExceptionValidacion('Aviso: Búsqueda vacía. Debe usar por lo menos un campo.');
        }
        \$filtros_sql = implode(' AND ', \$f);
        \$msg         = 'Buscó SED_SUBTITULO_PLURAL con '.implode(', ', \$m);
        // Agregar a la bitacora que se busco
        \$bitacora = new \\AdmBitacora\\Registro(\$this->sesion);
        \$bitacora->agregar_busco(\$msg);
        // Consultar
        \$base_datos = new \\Base\\BaseDatosMotor();
        try {
            \$consulta = \$base_datos->comando("
{$this->elaborar_consultar_tablas()}");
        } catch (\\Exception \$e) {
            throw new \\Base\\BaseDatosExceptionSQLError(\$this->sesion, 'Error SQL: Al buscar SED_SUBTITULO_PLURAL.', \$e->getMessage());
        }
        // Se considera consultado
        \$this->consultado = true;
        // Si la cantidad de registros es mayor a uno
        if (\$consulta->cantidad_registros() > 1) {
            // Levantar la bandera, hay resultados
            \$this->hay_resultados = true;
{$this->elaborar_consultar_resultados()}
        } elseif (\$consulta->cantidad_registros() == 1) {
            // Levantar la bandera, hay resultados
            \$this->hay_resultados = true;
            // Entregar detalle porque la cantidad de registros es uno
            \$a           = \$consulta->obtener_registro();
            \$detalle     = new DetalleHTML(\$this->sesion);
            \$detalle->id = intval(\$a['id']);
            // Entregar detalle
            return \$detalle;
        } else {
            // No se encontró nada
            throw new \\Base\\BusquedaHTMLExceptionVacio('Aviso: La búsqueda no encontró módulos con esos parámetros.');
        }
    } // consultar

FINAL;
    } // php

} // Clase Consultar

?>
