<?php
/**
 * Grafica
 *
 * @package Tierra
 */

// NAMESPACE
namespace Base;

/**
 * Clase Grafica
 */
class Grafica {

    public $id;                                 // ID DE LA GRAFICA
    public $caracteres_azar;                    // CARACTERES AL AZAR
    public $titulo;                             // TITULO PRINCIPAL
    public $titulo_eje_x;                       // TITULO PARA EL EJE X
    public $titulo_eje_y;                       // TITULO PARA EL EJE Y
    public $puede_crear = false;                // BANDERA
    protected $almacen_ruta;                    // RUTA ABSOLUTA EN EL DISCO AL ALMACEN
    protected $script_dir = 'scripts';          // DIRECTORIO DONDE VAN LOS SCRIPTS
    protected $script_ruta;                     // RUTA AL ARCHIVO CON EL SCRIPT R
    protected $script_existe;                   // RESULTADO DE LA VALIDACION, VERDADERO SI EXISTE
    protected $script_directorio;               // SOLO EL DIRECTORIO AL SCRIPT
    protected $script_archivo;                  // SOLO EL NOMBRE DEL ARCHIVO SCRIPT R
    protected $grafica_dir = 'graphs';          // DIRECTORIO DONDE VAN LAS GRAFICAS
    protected $grafica_ruta;                    // RUTA AL ARCHIVO CON LA GRAFICA
    protected $grafica_existe;                  // RESULTADO DE LA VALIDACION, VERDADERO SI EXISTE
    protected $grafica_directorio;              // SOLO EL DIRECTORIO A LA GRAFICA PDF
    protected $grafica_archivo;                 // SOLO EL NOMBRE AL ARCHIVO PDF
    protected $vista_previa_dir = 'previews';   // DIRECTORIO DONDE VAN LAS VISTAS PREVIAS
    protected $vista_previa_ruta;               // RUTA AL ARCHIVO DE VISTA PREVIA
    protected $vista_previa_existe;             // RESULTADO DE LA VALIDACION, VERDADERO SI EXISTE
    protected $vista_previa_directorio;         // SOLO EL DIRECTORIO A LA IMAGEN
    protected $vista_previa_archivo;            // SOLO EL NOMBRE DEL ARCHIVO PNG
    static public $vista_previa_tamano_x = 800; // TAMAÑO EN PIXELES
    static public $vista_previa_tamano_y = 600; // TAMAÑO EN PIXELES

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
    protected function validar() {
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
            throw new \Exception('Error: La ruta al almacén de gráficas es incorrecto.');
        } elseif (!is_dir($this->almacen_ruta)) {
            throw new \Exception('Error: La ruta al almacén de gráficas NO existe.');
        }
        // ELABORAR RUTAS PARA EL SCRIPT
        $this->script_directorio = sprintf('%s/%s',   $this->almacen_ruta, $this->script_dir);
        $this->script_archivo    = sprintf('%s-%s.R', $this->id, $this->caracteres_azar);
        $this->script_ruta       = sprintf('%s/%s',   $this->script_directorio, $this->script_archivo);
        // ELABORAR RUTAS PARA LA GRAFICA
        $this->grafica_directorio = sprintf('%s/%s',     $this->almacen_ruta, $this->grafica_dir);
        $this->grafica_archivo    = sprintf('%s-%s.pdf', $this->id, $this->caracteres_azar);
        $this->grafica_ruta       = sprintf('%s/%s',     $this->grafica_directorio, $this->grafica_archivo);
        // ELABORAR RUTAS PARA LA VISTA PREVIA
        $this->vista_previa_directorio = sprintf('%s/%s',     $this->almacen_ruta, $this->vista_previa_dir);
        $this->vista_previa_archivo    = sprintf('%s-%s.png', $this->id, $this->caracteres_azar);
        $this->vista_previa_ruta       = sprintf('%s/%s',     $this->vista_previa_directorio, $this->vista_previa_archivo);
        // SABER SI EXISTEN O NO LOS ARCHIVOS
        $this->script_existe       = file_exists($this->script_ruta);
        $this->grafica_existe      = file_exists($this->grafica_ruta);
        $this->vista_previa_existe = file_exists($this->vista_previa_ruta);
        // NO PUEDE CREAR UNA GRAFICA NUEVA SI EXISTE CUALQUIERA DE LOS ARCHIVOS
        if ($this->script_existe || $this->grafica_existe || $this->vista_previa_existe) {
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
     * Script grafica barras
     *
     * Crea el script R para una gráfica de barras
     *
     * @param  array  Arreglo con enteros, los valores
     * @param  array  Arreglo con textos, las etiquetas del eje x
     * @return string Script de R
     */
    protected function script_grafica_barras($in_datos, $in_etiquetas_eje_x) {
        // VALIDAR
        $this->validar();
        // VALIDAR PARAMETROS
        if (!is_array($in_datos) || (count($in_datos) == 0)) {
            throw new \Exception('Error: Los datos para hacer la gráfica de barras son incorrectos.');
        }
        if (!is_array($in_etiquetas_eje_x) || (count($in_etiquetas_eje_x) == 0)) {
            throw new \Exception('Error: Las etiquetas del eje x para hacer la gráfica de barras son incorrectas.');
        }
        // PARA EL COMANDO BARPLOT VAMOS A REVISAR LOS TITULOS
        $bp = array();
        if (is_string($this->titulo) && ($this->titulo != '')) {
            $bp[] = sprintf('main="%s"', $this->titulo);
        }
        if (is_string($this->titulo_eje_x) && ($this->titulo_eje_x != '')) {
            $bp[] = sprintf('xlab="%s"', $this->titulo_eje_x);
        }
        if (is_string($this->titulo_eje_y) && ($this->titulo_eje_y != '')) {
            $bp[] = sprintf('ylab="%s"', $this->titulo_eje_y);
        }
        if (count($bp) > 0) {
            $barplot = sprintf('barplot(datos, names.arg=etiquetas_eje_x, border="red", %s)', implode(', ', $bp));
        } else {
            $barplot = 'barplot(datos, names.arg=etiquetas_eje_x, border="red")';
        }
        // JUNTAREMOS EL CONTENIDO DEL SCRIPT EN ESTE ARREGLO
        $a   = array();
        $a[] = sprintf('datos <- c(%s)', implode(',', $in_datos));
        $a[] = sprintf('etiquetas_eje_x <- c("%s")', implode('","', $in_etiquetas_eje_x));
        $a[] = sprintf('png(filename="%s", width=%d, height=%d)', $this->vista_previa_archivo, self::$vista_previa_tamano_x, self::$vista_previa_tamano_y);
        $a[] = $barplot;
        $a[] = 'dev.off()';
        $a[] = sprintf('datos <- c(%s)', implode(',', $in_datos));
        $a[] = sprintf('etiquetas_eje_x <- c("%s")', implode('","', $in_etiquetas_eje_x));
        $a[] = sprintf('pdf(file="%s", title="%s", width=11, height=8.5)', $this->grafica_archivo, $this->titulo); // TAMAÑO CARTA LANDSCAPE
        $a[] = $barplot;
        $a[] = 'dev.off()';
        // ENTREGAR
        return implode("\n", $a)."\n";
    } // script_grafica_barras

    /**
     * Guardar script
     *
     * Ejecuta los comandos de PHP para crear un archivo con el script R
     *
     * @param string Contenido del script a guardar
     */
    protected function guardar_script($in_script) {
        // VALIDAR
        $this->validar();
        // SI YA EXISTE EL SCRIPT, ABORTAR
        if ($this->script_existe) {
            throw new \Exception('Error: No puede crearse el script porque ya existe uno o no ha inicializado.');
        }
        // SE DEFINE EL PUNTERO COMO ARCHIVO PARA ESCRITURA
        if (!$puntero = fopen($this->script_ruta, 'w')) {
            throw new \Exception('Error: No fue posible crear el puntero para escribir en el archivo de destino del script.');
        }
        // SE MANDA ESCRIBIR EL CONTENIDO
        if (fwrite($puntero, $in_script) === false) {
            throw new \Exception('Error: No fue posible escribir en el archivo de destino del script.');
        }
        // SE CIERRA EL ARCHIVO
        fclose($puntero);
        // SE CAMBIA LA BANDERA A VERDADERO
        $this->script_existe = true;
    } // guardar_script

    /**
     * Ejecutar R
     *
     * Llama a R para ejecutar el script guardado
     */
    protected function ejecutar_r() {
        // VALIDAR
        $this->validar();
        // SI NO EXISTE EL ARCHIVO R, ABORTAR
        if (!$this->script_existe) {
            throw new \Exception("Error: No ejecuté el graficador porque NO existe el script R.");
        }
        // SI YA EXISTE LA VISTA PREVIA, ABORTAR
        if ($this->vista_previa_existe) {
            throw new \Exception("Error: No ejecuté el graficador porque ya existe la vista previa para el ID dado.");
        }
        // SI YA EXISTE LA GRAFICA, ABORTAR
        if ($this->grafica_existe) {
            throw new \Exception("Error: No ejecuté el graficador porque ya existe la gráfica para el ID dado.");
        }
        // JUNTAREMOS LOS COMANDOS EN ESTE ARREGLO
        $a   = array();
        $a[] = sprintf('cd "%s"', $this->script_directorio); // CAMBIARSE DE DIRECTORIO
        $a[] = sprintf('Rscript --vanilla %s', $this->script_archivo); // COMANDO R
        $a[] = sprintf('mv %s %s/', $this->vista_previa_archivo, $this->vista_previa_directorio); // MOVER LA VISTA PREVIA (PNG)
        $a[] = sprintf('mv %s %s/', $this->grafica_archivo, $this->grafica_directorio); // MOVER LA GRAFICA (PDF)
        // JUNTAMOS
        $comando = sprintf(implode(' && ', $a));
        // EJECUTAR
        exec($comando, $salida);
        // VALIDAR, DE NUEVO PARA SABER SI SE CREARON LOS ARCHIVOS
        $this->validar();
        // SI NO EXISTE LA VISTA PREVIA, ENTONCES FALLÓ
        if (!$this->vista_previa_existe) {
            throw new \Exception("Error: El graficador NO pudo crear la vista previa.\nComando: $comando\nSalida:\n".implode("\n",$salida));
        }
        // SI NO EXISTE LA GRAFICA, ENTONCES FALLÓ
        if (!$this->grafica_existe) {
            throw new \Exception("Error: El graficador NO pudo crear la gráfica.\nComando: $comando\nSalida:\n".implode("\n",$salida));
        }
    } // ejecutar_r

    /**
     * Graficar
     *
     * @param string Script R listo para procesar
     */
    public function graficar($in_script) {
        $this->guardar_script($in_script); // PUEDE PROVOCAR UNA EXCEPCION
        $this->ejecutar_r(); // PUEDE PROVOCAR UNA EXCEPCION
    } // graficar

    /**
     * Graficar barras
     *
     * Ejecuta los tres pasos, elaborar el script, guardarlo y ejecutarlo
     *
     * @param array Arreglo con enteros, los valores
     * @param array Arreglo con textos, las etiquetas del eje x
     */
    public function graficar_barras($in_datos, $in_etiquetas_eje_x) {
        $script_r = $this->script_grafica_barras($in_datos, $in_etiquetas_eje_x); // PUEDE PROVOCAR UNA EXCEPCION
        $this->guardar_script($script_r); // PUEDE PROVOCAR UNA EXCEPCION
        $this->ejecutar_r(); // PUEDE PROVOCAR UNA EXCEPCION
    } // graficar_barras

} // Clase Grafica

?>
