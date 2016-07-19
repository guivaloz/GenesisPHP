<?php
/**
 * Impresion
 *
 * @package Tierra
 */

// NAMESPACE
namespace Base;

/**
 * Clase Impresion
 */
class Impresion {

    public $id;                       // ID DE LA IMPRESION
    public $caracteres_azar;          // CARACTERES AL AZAR
    public $titulo;                   // TITULO PRINCIPAL
    public $autor;                    // AUTOR
    protected $almacen_ruta;          // TEXTO, RUTA ABSOLUTA EN EL DISCO AL ALMACEN DE IMPRESIONES
    protected $tex_dir     = 'tex';   // DIRECTORIO DONDE VAN LOS ARCHIVOS TEX
    public    $tex_ruta;              // RUTA AL ARCHIVO TEX
    public    $tex_existe  = false;   // RESULTADO DE LA VALIDACION, VERDADERO SI EXISTE
    public    $tex_directorio;        // SOLO EL DIRECTORIO AL ARCHIVO TEX
    public    $tex_archivo;           // SOLO EL NOMBRE DEL ARCHIVO TEX
    public    $dvi_ruta;              // RUTA AL ARCHIVO DVI
    public    $dvi_directorio;        // SOLO EL DIRECTORIO AL ARCHIVO DVI
    public    $dvi_archivo;           // SOLO EL NOMBRE DEL ARCHIVO DVI
    protected $pdf_dir     = 'pdf';   // DIRECTORIO DONDE VAN LOS PDFS
    public    $pdf_ruta;              // RUTA AL ARCHIVO PDF
    public    $pdf_existe  = false;   // RESULTADO DE LA VALIDACION, VERDADERO SI EXISTE
    public    $pdf_directorio;        // SOLO EL DIRECTORIO AL ARCHIVO PDF
    public    $pdf_archivo;           // SOLO EL NOMBRE DEL ARCHIVO PDF
    protected $puede_crear = false;   // BANDERA

    /**
     * Constructor
     *
     * @param string Ruta al almacén
     */
    public function __construct($in_ruta) {
        $this->almacen_ruta = $in_ruta;
    } // constructor

    /**
     * Validar
     *
     * Valida cada una de las propiedades, provoca una excepción al fallar
     */
    public function validar() {
        // VALIDAR ID
        if (is_string($this->id) && preg_match('/^[0-9]+$/', $this->id)) {
            $this->id = intval($this->id);
        } elseif (!(is_int($this->id) && ($this->id > 0))) {
            throw new \Exception("Error: ID de gráfica incorrecta.");
        }
        // VALIDAR CARACTERES AZAR
        if (!is_string($this->caracteres_azar) || !preg_match('/^[a-zA-Z0-9]{4,64}$/', $this->caracteres_azar)) {
            throw new \Exception("Error: Los caracteres al azar para la imagen son incorrectos.");
        }
        // VALIDAR ALMACEN RUTA
        if (!is_string($this->almacen_ruta) || ($this->almacen_ruta == '')) {
            throw new \Exception('Error: La ruta al almacén es incorrecto.');
        } elseif (!is_dir($this->almacen_ruta)) {
            throw new \Exception('Error: La ruta al almacén NO existe.');
        }
        // PROPIEDADES DEL TEX
        $this->tex_directorio = sprintf('%s/%s', $this->almacen_ruta, $this->tex_dir);
        $this->tex_archivo    = sprintf('%d-%s.tex', $this->id, $this->caracteres_azar);
        $this->tex_ruta       = sprintf('%s/%s', $this->tex_directorio, $this->tex_archivo);
        // PROPIEDADES DEL DVI
        $this->dvi_directorio = $this->tex_directorio;
        $this->dvi_archivo    = sprintf('%d-%s.dvi', $this->id, $this->caracteres_azar);
        $this->dvi_ruta       = sprintf('%s/%s', $this->dvi_directorio, $this->dvi_archivo);
        // PROPIEDADES DEL PDF
        $this->pdf_directorio = sprintf('%s/%s', $this->almacen_ruta, $this->pdf_dir);
        $this->pdf_archivo    = sprintf('%d-%s.pdf', $this->id, $this->caracteres_azar);
        $this->pdf_ruta       = sprintf('%s/%s', $this->pdf_directorio, $this->pdf_archivo);
        // SABER SI EXISTEN O NO LOS ARCHIVOS
        $this->tex_existe = file_exists($this->tex_ruta);
        $this->pdf_existe = file_exists($this->pdf_ruta);
        // NO PUEDE CREAR UNA IMPRESIONES NUEVA SI EXISTE CUALQUIERA DE LOS ARCHIVOS
        if ($this->tex_existe || $this->pdf_existe) {
            $this->puede_crear = false;
        } else {
            $this->puede_crear = true;
        }
    } // validar

    /**
     * Inicializar
     *
     * Carga el ID y los caracteres al azar, pone como textos vacíos a las rutas
     */
    public function inicializar($in_id, $in_caracteres_azar) {
        // PARAMETROS
        $this->id                = $in_id;
        $this->caracteres_azar   = $in_caracteres_azar;
        // VALIDAR, SI NO EXISTE DEBE PONER LA BANDERA puede_crear EN VERDADERO
        $this->validar();
    } // inicializar

    /**
     * Tex Articulo
     *
     * @param array Cuerpo
     */
    protected function tex_articulo($in_cuerpo) {
        // VALIDAR
        $this->validar();
        // VALIDAR PARAMETRO
        if (is_array($in_cuerpo) && (count($in_cuerpo) > 0)) {
            $cuerpo = $in_cuerpo;
        } elseif (is_string($in_cuerpo) && ($in_cuerpo != '')) {
            $cuerpo = $in_cuerpo;
        } else {
            throw new \Exception('Error: El cuerpo para el artículo es incorrecto.');
        }
        // VALIDAR TITULO
        if (is_string($this->titulo) && ($this->titulo != '')) {
            $titulo = $this->titulo;
        } else {
            $titulo = 'Sin título';
        }
        // VALIDAR AUTOR
        if (is_string($this->autor) && ($this->autor != '')) {
            $autor = $this->autor;
        } else {
            $autor = 'Sin autor';
        }
        // JUNTAREMOS EL CONTENIDO DEL TEXT EN ESTE ARREGLO
        $a   = array();
        $a[] = '%';
        $a[] = "% $titulo";
        $a[] = '%';
        $a[] = '';
        $a[] = '\documentclass[letterpaper,12pt,titlepage]{article}';
        $a[] = '\usepackage[spanish]{babel}';
        $a[] = '\usepackage[utf8]{inputenc}';
        $a[] = '\usepackage{fullpage}';
        $a[] = '';
        $a[] = sprintf('\title{%s}', $titulo);
        $a[] = sprintf('\author{%s}', $autor);
        $a[] = '\date{\today}';
        $a[] = '';
        $a[] = '\begin{document}';
        $a[] = '\addtolength{\parskip}{\baselineskip}';
        $a[] = '';
        // SI EL CONTENIDO ES ARREGLO
        if (is_array($cuerpo)) {
            // ES ARREGLO
            foreach ($cuerpo as $encabezado => $rollo) {
                $a[] = sprintf('\section{%s}', $encabezado);
                // SI EL ROLLO ES UN ARREGLO
                if (is_array($rollo) && (count($rollo) > 0)) {
                    // ES ARREGLO
                    foreach ($rollo as $e => $c) {
                        if (is_string($e)) {
                            // SON ITEMS
                            $a[] = sprintf('\item[%s]', $e);
                            $a[] = $c;
                        } else {
                            // SON PARRAFOS
                            $a[] = sprintf('%s\par', $c);
                            $a[] = '';
                        }
                    }
                } elseif (is_string($rollo)) {
                    // ES TEXTO
                    $a[] = $rollo;
                    $a[] = '';
                } else {
                    throw new \Exception('Error: El cuerpo para el artículo es incorrecto.');
                }
            }
        } elseif (is_string($cuerpo)) {
            // ES TEXTO
            $a[] = $cuerpo;
        } else {
            throw new \Exception('Error: El cuerpo para el artículo es incorrecto.');
        }
        // FINAL
        $a[] = '';
        $a[] = '\end{document}';
        // ENTREGAR
        return implode("\n", $a)."\n";
    } // tex_articulo

    /**
     * Guardar TEX
     *
     * @param string Contenido del TEX a guardar
     */
    protected function guardar_tex($in_tex) {
        // VALIDAR
        $this->validar();
        // SI YA EXISTE EL TEX, ABORTAR
        if ($this->tex_existe) {
            throw new \Base\ImpresionExceptionYaExisteTEX('Error: No puede crearse el archivo TEX porque ya existe uno o no ha inicializado.');
        }
        // SE DEFINE EL PUNTERO COMO ARCHIVO PARA ESCRITURA
        if (!$puntero = fopen($this->tex_ruta, 'w')) {
            throw new \Exception('Error: No fue posible crear el puntero para escribir en el archivo de destino TEX.');
        }
        // SE MANDA ESCRIBIR EL CONTENIDO
        if (fwrite($puntero, $in_tex) === false) {
            throw new \Exception('Error: No fue posible escribir en el archivo de destino TEX.');
        }
        // SE CIERRA EL ARCHIVO
        fclose($puntero);
        // SE CAMBIA LA BANDERA A VERDADERO
        $this->tex_existe = true;
    } // guardar_tex

    /**
     * Ejecutar LaTeX
     *
     * Llama a LaTeX para convertir el TEX a PDF
     */
    protected function ejecutar_latex() {
        // VALIDAR
        $this->validar();
        // SI NO EXISTE EL ARCHIVO TEX, ABORTAR
        if (!$this->tex_existe) {
            throw new \Exception("Error: No ejecuté el LaTeX porque NO existe el archivo TEX.");
        }
        // SI YA EXISTE EL ARCHIVO PDF, ABORTAR
        if ($this->pdf_existe) {
            throw new \Base\ImpresionExceptionYaExistePDF("Error: No ejecuté el LaTeX porque ya existe el archivo PDF para el ID dado.");
        }
        // COMANDO. LA SECUENCIA DE COMANDOS SE EJECUTA ENTRE PARENTESIS PARA ASEGURAR QUE EL CONJUNTO SE MANDE AL FONDO. LOS ERRORES SE REDIRIGEN A STDIO
    //  $comando = "(cd {$this->tex_directorio}; latex {$this->tex_archivo}; dvipdf {$this->dvi_archivo}; mv {$this->pdf_archivo} ../{$this->pdf_dir}/) > /dev/null 2>&1 &";
        $comando = "(cd {$this->tex_directorio}; pdflatex {$this->tex_archivo}; mv {$this->pdf_archivo} ../{$this->pdf_dir}/) > /dev/null 2>&1 &";
        // EJECUTAR
        exec($comando);
    } // ejecutar_latex

    /**
     * Imprimir
     *
     * @param string LaTeX listo para procesar
     */
    public function imprimir($in_contenido) {
        $this->guardar_tex($in_contenido); // PUEDE PROVOCAR UNA EXCEPCION
        $this->ejecutar_latex();// PUEDE PROVOCAR UNA EXCEPCION
    } // imprimir

    /**
     * Imprimir Artículo
     *
     * @param string Cuerpo del artículo
     */
    public function imprimir_articulo($in_cuerpo) {
        $latex = $this->tex_articulo($in_cuerpo);// PUEDE PROVOCAR UNA EXCEPCION
        $this->guardar_tex($latex); // PUEDE PROVOCAR UNA EXCEPCION
        $this->ejecutar_latex(); // PUEDE PROVOCAR UNA EXCEPCION
    } // imprimir_articulo

} // Clase Impresion

?>
