<?php
/**
 * GenesisPHP - FormularioHTML ElaborarFormulario
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

namespace FormularioHTML;

/**
 * Clase ElaborarFormulario
 */
class ElaborarFormulario extends \Base\Plantilla {

    /**
     * Elaborar Formulario Campos Modificables Relacionados
     *
     * @param  string Columna
     * @param  array  Datos
     * @param  string Etiqueta
     * @return string Código PHP
     */
    protected function elaborar_formulario_campos_modificables_relacionados($columna, $datos, $etiqueta) {
        // En este arreglo juntaremos el codigo php
        $a = array();
        // Si esta relacion es padre
        if (is_array($this->padre) && (count($this->padre[$columna]) > 0)) {
            // Esta relacion es padre
            $padre      = $this->padre[$columna];
            $directorio = $padre['clase_plural'];
            $instancia  = "\${$padre['instancia_plural']}";
            // Si vip es texto
            if (is_string($padre['vip']) && ($padre['vip'] != '')) {
                // Solo un vip
                $valor = "\$this->{$columna}_{$padre['vip']}";
            } elseif (is_array($padre['vip'])) {
                // Vip es un arreglo
                $z = array();
                foreach ($padre['vip'] as $vip => $vip_datos) {
                    // Si es un arreglo
                    if (is_array($vip_datos)) {
                        // Si es una relacion
                        if ($vip_datos['tipo'] == 'relacion') {
                            // Es una relacion y debe de existir en reptil
                            if (is_array($this->relaciones[$vip])) {
                                // Si el vip es un arreglo
                                if (is_array($this->relaciones[$vip]['vip'])) {
                                    // Ese vip es un arreglo
                                    foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                        if ($vd['tipo'] == 'relacion') {
                                            // Ese vip de la relacion es otra relacion, se omite
                                        } elseif ($vd['tipo'] == 'caracter') {
                                            // Ese vip de la relacion es caracter, se usa el descrito
                                            $z[] = "\$this->{$vip}_{$v}_descrito";
                                        } else {
                                            // Ese vip de la relacion es de cualquier otro tipo
                                            $z[] = "\$this->{$vip}_{$v}";
                                        }
                                    }
                                } else {
                                    // Ese vip es texto
                                    $z[] = "\$this->{$vip}_{$this->relaciones[$vip]['vip']}";
                                }
                            } else {
                                die("Error en FormularioHTML: No está definido el VIP en Serpiente para $vip.");
                            }
                        } elseif ($vip_datos['tipo'] == 'caracter') {
                            // Es caracter, usara su descrito
                            $z[] = "\$this->{$columna}_{$vip}_descrito";
                        } else {
                            // Es cualquier otro tipo
                            $z[] = "\$this->{$columna}_{$vip}";
                        }
                    } else {
                        // Vip datos es un texto
                        $z[] = "\$this->{$columna}_{$vip_datos}";
                    }
                }
                $valor = "implode(', ', array(".implode(', ', $z)."))";
            } else {
                die("Error en FormularioHTML: No está definido vip en el padre $columna.");
            }
            // Si viene el dato sera fijo, si no viene sera select
            $a[] = "        if (\$this->es_nuevo && (\$this->$columna != '')) {";
            $a[] = "            \$form->fijo('{$columna}_fijo', '$etiqueta', $valor);";
            $a[] = "            \$form->oculto('$columna', \$this->$columna);";
            $a[] = "        } else {";
            $a[] = "            $instancia = new \\$directorio\\OpcionesSelect(\$this->sesion);";
            $a[] = "            try {";
            if ($datos['validacion'] > 1) {
                $a[] = "                \$form->select('$columna', '$etiqueta', {$instancia}->opciones(), \$this->$columna);";
            } else {
                $a[] = "                \$form->select_con_nulo('$columna', '$etiqueta', {$instancia}->opciones(), \$this->$columna);";
            }
            $a[] = "            } catch (\Base\ListadoExceptionVacio \$e) {";
            $a[] = "                throw new \Exception('Aviso: No se elaboró el formulario porque la consulta a $columna no entregó registros.');";
            $a[] = "            } catch (\Exception \$e) {";
            $a[] = "                throw \$e;";
            $a[] = "            }";
            $a[] = "        }";
        } else {
            // La relacion no es padre. entonces sera un select.
            if ($this->relaciones[$columna]['clase_plural'] == '') {
                die('Error en FormularioHTML: No está definida la clase_plural en el padre.');
            }
            if ($this->relaciones[$columna]['instancia_plural'] == '') {
                die('Error en FormularioHTML: No está definida la instancia_plural en el padre.');
            }
            $directorio = $this->relaciones[$columna]['clase_plural'];
            $instancia  = "\${$this->relaciones[$columna]['instancia_plural']}";
            $a[] = "        $instancia = new \\$directorio\\OpcionesSelect(\$this->sesion);";
            $a[] = "        try {";
            if ($datos['validacion'] > 1) {
                $a[] = "            \$form->select('$columna', '$etiqueta', {$instancia}->opciones(), \$this->$columna);";
            } else {
                $a[] = "            \$form->select_con_nulo('$columna', '$etiqueta', {$instancia}->opciones(), \$this->$columna);";
            }
            $a[] = "        } catch (\Exception \$e) {";
            $a[] = "            \$form->fijo('$columna', '$etiqueta', \$e->getMessage());";
            $a[] = "        }";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_formulario_campos_modificables_relacionados

    /**
     * Elaborar Fromulario Campos Modificables
     *
     * @param  string Columna
     * @param  array  Datos
     * @return string Código PHP
     */
    protected function elaborar_formulario_campos_modificables($columna, $datos) {
        // Si validacion es dos, es obligatorio
        if (is_int($datos['validacion']) && ($datos['validacion'] > 1)) {
            $etiqueta = $datos['etiqueta'].' *'; // Agregamos un asterisco a la etiqueta para dar a entender que es obligatorio
        } else {
            $etiqueta = $datos['etiqueta'];
        }
        // Si la columna se agrega o se modifica
        switch ($datos['tipo']) {
            case 'caracter':
                if ($datos['validacion'] > 1) {
                    $campo = "\$form->select('$columna', '$etiqueta', parent::\${$columna}_descripciones, \$this->$columna);";
                } else {
                    $campo = "\$form->select_con_nulo('$columna', '$etiqueta', parent::\${$columna}_descripciones, \$this->$columna);";
                }
                break;
            case 'clave':
                $campo = "\$form->texto('$columna', '$etiqueta', \$this->$columna, 24);";
                break;
            case 'contraseña':
                $campo = "\$form->password('$columna', '$etiqueta', \$this->$columna, 16);";
                break;
            case 'cuip':
                $campo = "\$form->texto('$columna', '$etiqueta', \$this->$columna, 20);";
                break;
            case 'curp':
                $campo = "\$form->texto('$columna', '$etiqueta', \$this->$columna, 18);";
                break;
            case 'email':
            case 'nombre':
            case 'mayusculas':
            case 'telefono':
            case 'frase':
            case 'variable':
                $campo = "\$form->texto_nombre('$columna', '$etiqueta', \$this->$columna, 128);";
                break;
            case 'nom_corto':
                $campo = "\$form->texto_nom_corto('$columna', '$etiqueta', \$this->$columna, 24);";
                break;
            case 'notas':
                $campo = "\$form->area_texto('$columna', '$etiqueta', \$this->$columna, 64, 5);";
                break;
            case 'rfc':
                $campo = "\$form->texto('$columna', '$etiqueta', \$this->$columna, 13);";
                break;
            case 'fecha':
                $campo = "\$form->fecha('$columna', '$etiqueta', \$this->$columna);";
                break;
            case 'fecha_hora':
                $campo = "\$form->fecha_hora('$columna', '$etiqueta', \$this->$columna);";
                break;
            case 'entero':
            case 'porcentaje':
                $campo = "\$form->texto_entero('$columna', '$etiqueta', \$this->$columna, 8);";
                break;
            case 'flotante':
            case 'dinero':
            case 'peso':
            case 'estatura':
                $campo = "\$form->texto_flotante('$columna', '$etiqueta', \$this->$columna, 8);";
                break;
            case 'geopunto':
                $campo = "\$form->geopunto('$columna', '$etiqueta', \$this->{$columna}_longitud, \$this->{$columna}_latitud);";
                break;
            case 'relacion':
                return $this->elaborar_formulario_campos_modificables_relacionados($columna, $datos, $etiqueta);
            default:
                die("Error en FormularioHTML: Tipo {$datos['tipo']} no programado al elaborar formulario.");
        }
        // Entregar campo (codigo php)
        return "        $campo";
    } // elaborar_formulario_campos_modificables

    /**
     * Elaborar Formulario Campos Fijos
     *
     * @param  string Columna
     * @param  array  Datos
     * @return string Código PHP
     */
    protected function elaborar_formulario_campos_fijos($columna, $datos) {
        // No se va a agregar ni modificar, pero tiene etiqueta, asi que mostramos el dato fijo
        if ($datos['tipo'] == 'caracter') {
            // Es un caracter
            if ($datos['vip'] > 1) {
                // Si el vip es mayor a uno, elaborar un vinculo al detalle
                $valor = sprintf('"<a href=\"SED_ARCHIVO_PLURAL.php?id={%s}\">{%s}</a>"', '$this->id', "\$this->{$columna}_descrito");
            } else {
                // Contenido sin vinculo
                $valor = "\$this->{$columna}_descrito";
            }
            // Entregar
            return "        \$form->fijo('$columna', '{$datos['etiqueta']}', $valor);";
        } elseif ($datos['tipo'] == 'relacion') {
            // Es una relacion
            $relacion = $this->relaciones[$columna];
            // Si vip es texto
            if (is_string($relacion['vip']) && ($relacion['vip'] != '')) {
                // Solo un solo vip
                $eses  = '%s';
                $param = "\$this->{$columna}_{$relacion['vip']}";
            } elseif (is_array($relacion['vip']) && (count($relacion['vip']) > 0)) {
                // Vip es un arreglo
                $s = array();
                $p = array();
                foreach ($relacion['vip'] as $vip => $vip_datos) {
                    // Si es un arreglo
                    if (is_array($vip_datos)) {
                        // Si es una relacion
                        if ($vip_datos['tipo'] == 'relacion') {
                            // Es una relacion y debe de existir en reptil
                            if (is_array($this->relaciones[$vip])) {
                                // Si el vip es un arreglo
                                if (is_array($this->relaciones[$vip]['vip'])) {
                                    // Ese vip es un arreglo
                                    foreach ($this->relaciones[$vip]['vip'] as $v => $vd) {
                                        if ($vd['tipo'] == 'caracter') {
                                            // Ese vip de la relacion es de tipo caracter
                                            $s[] = "{$vd['etiqueta']} %s";
                                            $p[] = "\$this->{$vip}_{$v}_descrito";
                                        } else {
                                            // Ese vip de la relacion es de otro tipo
                                            $s[] = "{$vd['etiqueta']} %s";
                                            $p[] = "\$this->{$vip}_{$v}";
                                        }
                                    }
                                } else {
                                    // Ese vip es texto
                                    $s[] = "%s";
                                    $p[] = "\$this->{$vip}_{$this->relaciones[$vip]['vip']}";
                                }
                            } else {
                                die("Error en FormularioHTML: No está definido el VIP en Serpiente para $vip.");
                            }
                        } elseif ($vip_datos['tipo'] == 'caracter') {
                            // Es caracter, usaremos su descrito
                            $s[] = '%s';
                            $p[] = "\$this->{$columna}_{$vip}_descrito";
                        } else {
                            // Es cualquier otro tipo
                            $s[] = '%s';
                            $p[] = "\$this->{$columna}_{$vip}";
                        }
                    } else {
                        // Vip datos es un texto
                        $s[] = '%s';
                        $p[] = "\$this->{$columna}_{$vip_datos}";
                    }
                }
                $eses  = implode(', ', $s);
                $param = implode(', ', $p);
            }
            // Armamos una operacion que pruebe que tenga valor la relacion
            $valor = "(\$this->{$columna} != '') ? sprintf('<a href=\"{$this->relaciones[$columna]['archivo_plural']}.php?id=%s\">{$eses}</a>', \$this->{$columna}, {$param}) : ''";
            // Entregar
            return "        \$form->fijo('$columna', '{$datos['etiqueta']}', $valor);";
        } else {
            // No es ni caracter ni relacion, asi que se mostrara el valor directamente
            return "        \$form->fijo('$columna', '{$datos['etiqueta']}', \$this->$columna);";
        }
    } // elaborar_formulario_campos_fijos

    /**
     * Elaborar Formulario Campos
     *
     * @return string Código PHP
     */
    protected function elaborar_formulario_campos() {
        // En este arreglo juntaremos los campos
        $a   = array();
        $a[] = "        // Campos del formulario";
        // Bucle para cada columna de la tabla
        foreach ($this->tabla as $columna => $datos) {
            if (($columna == 'id') || ($columna == 'estatus')) {
                continue; // Las columnas id y estatus nunca aparecen en los formularios
            } elseif ((is_int($datos['agregar']) && ($datos['agregar'] > 0)) || (is_int($datos['modificar']) && ($datos['modificar'] > 0))) {
                $a[] = $this->elaborar_formulario_campos_modificables($columna, $datos);
            } elseif ($datos['etiqueta'] != '') {
                $a[] = $this->elaborar_formulario_campos_fijos($columna, $datos);
            } else {
                continue; // No tiene agregar o modificar, ni tiene etiqueta, asi que no aparece en el formulario
            }
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_formulario_campos

    /**
     * Elaborar Formulario Imagen
     *
     * @return string Código PHP
     */
    protected function elaborar_formulario_imagen() {
        if (is_array($this->imagen)) {
            if (is_string($this->imagen['etiqueta']) && ($this->imagen['etiqueta'] != '')) {
                $etiqueta = $this->imagen['etiqueta'];
            } else {
                $etiqueta = 'Imagen';
            }
            if (is_string($this->imagen['variable']) && ($this->imagen['variable'] != '')) {
                $variable = $this->imagen['variable'];
            } else {
                $variable = 'imagen';
            }
            return <<<FINAL
        // Campo del formulario para subir la imagen
        if (\$this->es_nuevo) {
            \$form->adjuntar_archivo('$variable', '$etiqueta');
        }
FINAL;
        } else {
            return '';
        }
    } // elaborar_formulario_imagen

    /**
     * Elaborar formulario botones
     *
     * @return string Código PHP
     */
    protected function elaborar_formulario_botones() {
        // En este arreglo juntaremos los campos
        $a   = array();
        $a[] = "        // Botones";
        $a[] = "        \$form->boton_guardar();";
        if (is_array($this->padre) && (count($this->padre) > 0)) {
            // Cuando tiene un padre (o varios) el boton cancelar lo regresa al detalle del padre
            $a[] = "        if (\$this->es_nuevo) {";
            foreach ($this->padre as $columna => $datos) {
                $a[] = "            if (\$this->{$columna} != '') {";
                $a[] = "                \$form->boton_cancelar(sprintf('{$datos['archivo_plural']}.php?id=%d', \$this->{$columna}));";
                $a[] = "            }";
            }
            $a[] = "        } else {";
            $a[] = "            \$form->boton_cancelar(sprintf('SED_ARCHIVO_PLURAL.php?id=%d', \$this->id));";
            $a[] = "        }";
        } else {
            // Sin relacion, el boton cancelar solo aparece al modificar
            $a[] = "        if (!\$this->es_nuevo) {";
            $a[] = "            \$form->boton_cancelar(sprintf('SED_ARCHIVO_PLURAL.php?id=%d', \$this->id));";
            $a[] = "        }";
        }
        // Entregar
        return implode("\n", $a);
    } // elaborar_formulario_botones

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    /**
     * Elaborar formulario
     *
     * @param  string  Encabezado opcional
     * @return string  HTML del Formulario
     */
    protected function elaborar_formulario(\$in_encabezado='') {
        // Formulario
        \$form = new \\Base\\FormularioHTML(self::\$form_name);
        \$form->mensaje = '(*) Campos obligatorios.';
        // Campos ocultos
        \$cadenero = new \\Base\\Cadenero(\$this->sesion);
        \$form->oculto('cadenero', \$cadenero->crear_clave(self::\$form_name));
        if (\$this->es_nuevo) {
            \$form->oculto('accion', 'agregar');
        } else {
            \$form->oculto('id', \$this->id);
        }
{$this->elaborar_formulario_campos()}{$this->elaborar_formulario_imagen()}
{$this->elaborar_formulario_botones()}
        // Encabezado
        if (\$in_encabezado !== '') {
            \$encabezado = \$in_encabezado;
        } elseif (\$this->es_nuevo) {
            \$encabezado = "Nuevo(a) SED_SUBTITULO_SINGULAR";
        } else {
            \$encabezado = "{$this->columnas_vip_para_mensaje()}";
        }
        // Agregar javascript
        \$this->javascript[] = \$form->javascript();
        // Entregar
        return \$form->html(\$encabezado, \$this->sesion->menu->icono_en('SED_CLAVE'));
    } // elaborar_formulario

FINAL;
    } // php

} // Clase ElaborarFormulario

?>
