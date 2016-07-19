<?php
/**
 * Gráfica Morris HTML
 *
 * @package Tierra
 */

// NAMESPACE
namespace Base;

/**
 * Clase GraficaMorrisHTML
 */
class GraficaMorrisHTML {

    public $identificador;       // ESTE TEXTO ES UNICO A CADA GRAFICA
    public $tipo     = 'lineas'; // TIPO DE GRAFICA: 'lineas', 'areas' o 'barras'
    protected $clave_x;          // CLAVE DE LO QUE SERA EL EJE X
    protected $claves_y;         // CLAVE O CLAVES DE LO QUE SERA EL VALOR EN EL EJE Y
    protected $etiquetas_y;      // ETIQUETA O ETIQUETAS PARA LOS VALORES
    protected $colores_y;        // COLOR O COLORES DE LA SERIE
    protected $datos = array();  // ARREGLO CON ARREGLOS, DATOS DE LAS SERIES A GRAFICAR
    protected $formato_x;        // PROPIEDAD INTERNA QUE SEÑALA SI TODOS LOS DATOS X SON YYYY-MM-DD O YYYY-MM
    protected $mensaje_error;    // ESTE OBJETO NO CAUSA EXCEPCION. AL HABER UN ERROR SE MUESTRA UN MENSAJE EN LUGAR DE LA GRAFICA

    /**
     * Constructor
     *
     * @param string Opcional, identificador único para el div en la página web, por defecto caracteres al azar
     */
    public function __construct($in_id=false) {
        // DEFINIR IDENTIFICADOR
        if (is_string($in_id) && (trim($in_id) != '')) {
            $this->identificador = $in_id;
        } else {
            $this->identificador = "morris".strtoupper(caracteres_azar());
        }
    } // constructor

    /**
     * Definir Clave X
     *
     * @param string Clave del eje X
     */
    public function definir_clave_x($in_clave) {
        // NO HACER NADA SI HAY ERROR
        if ($this->mensaje_error != '') {
            return;
        }
        // VALIDAR PARAMETRO
        if (is_string($in_clave) && ($in_clave != '')) {
            $this->clave_x = $in_clave;
        } else {
            $this->mensaje_error = "Error en GraficaMorrisHTML: La clave del eje X es incorrecta.";
        }
    } // definir_clave_x

    /**
     * Definir Claves Y
     *
     * @param mixed Claves del eje Y. Puede ser string o un arreglo con strings.
     * @param mixed Etiquetas del eje Y. Puede ser string o un arreglo con strings.
     * @param mixed Opcional. Colores de la serie. Puede ser string o un arreglo con strings.
     */
    public function definir_claves_y($in_claves, $in_etiquetas, $in_colores='') {
        // NO HACER NADA SI HAY ERROR
        if ($this->mensaje_error != '') {
            return;
        }
        // VALIDAR PARAMETRO CLAVES
        if (is_string($in_claves) && ($in_claves != '')) {
            $this->claves_y = array($in_claves);
        } elseif (is_array($in_claves) && (count($in_claves) > 0)) {
            $this->claves_y = $in_claves;
        } else {
            $this->mensaje_error = "Error en GraficaMorrisHTML: Las claves para el eje Y son incorrectas.";
            return;
        }
        // VALIDAR PARAMETRO ETIQUETAS
        if (is_string($in_etiquetas) && ($in_etiquetas != '')) {
            $this->etiquetas_y = array($in_etiquetas);
        } elseif (is_array($in_etiquetas) && (count($in_etiquetas) > 0)) {
            $this->etiquetas_y = $in_etiquetas;
        } else {
            $this->mensaje_error = "Error en GraficaMorrisHTML: Las etiquetas para el eje Y son incorrectas.";
            return;
        }
        // VALIDAR PARAMETRO COLORES
        if (is_string($in_colores) && ($in_colores != '')) {
            $this->colores_y = array($in_colores);
        } elseif (is_array($in_colores) && (count($in_colores) > 0)) {
            $this->colores_y = $in_colores;
        } else {
            $this->colores_y = array();
        }
        // VALIDAR QUE LA CANTIDAD DE ETIQUETAS SEA IGUAL QUE LA CANTIDAD DE CLAVES
        if (count($this->claves_y) != count($this->etiquetas_y)) {
            $this->mensaje_error = "Error en GraficaMorrisHTML: Las cantidades de claves y etiquetas para el eje Y no son iguales.";
        }
    } // definir_claves_y

    /**
     * Agregar Datos
     *
     * @param mixed Valor en X
     * @param mixed Arreglo de valores en Y.
     */
    public function agregar_datos($in_valor_x, $in_valores_y) {
        // NO HACER NADA SI HAY ERROR
        if ($this->mensaje_error != '') {
            return;
        }
        // VALIDAR PARAMETROS
        if (is_string($in_valores_y) && ($in_valores_y ==='')) {
            $this->mensaje_error = "Error en GraficaMorrisHTML: Se ha dado un valor vació para el eje Y.";
            return;
        }
        // DEBEN HABERSE DEFINIDO ANTES QUE AL EJECUTAR ESTE METODO
        if ($this->clave_x == '') {
            $this->mensaje_error = "Error en GraficaMorrisHTML: No se ha definido la clave del eje X.";
            return;
        }
        if (!is_array($this->claves_y)) {
            $this->mensaje_error = "Error en GraficaMorrisHTML: No se han definido las claves para el eje Y.";
            return;
        }
        // VALIDAR VALORES Y
        if (is_array($in_valores_y)) {
            $yes = $in_valores_y;
        } else {
            $yes = array($in_valores_y);
        }
        if (count($yes) != count($this->claves_y)) {
            $this->mensaje_error = "Error en GraficaMorrisHTML: La cantidad de datos en Y no es igual a la cantidad de claves.";
            return;
        }
        // SI EL VALOR NO ES ENTERO O DECIMAL SE GUARDA COMO CERO
        $valores_y = array();
        foreach ($yes as $y) {
            if (is_float($y)) {
                $valores_y[] = $y;
            } elseif (is_int($y)) {
                $valores_y[] = $y;
            } elseif (is_string($y) && preg_match('/^\-?[0-9]*\.?[0-9]+$/', $y)) {
                $valores_y[] = $y;
            } else {
                $valores_y[] = 0;
            }
        }
        // DETECTAR FORMATO DEL VALOR EN X
        if (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $in_valor_x)) {
            $formato_x = 'YYYY-MM-DD';
        } elseif (preg_match('/^\d{4}\-\d{1,2}$/', $in_valor_x)) {
            $formato_x = 'YYYY-MM';
        } else {
            $formato_x = '';
        }
        // SI ES EL PRIMER DATO SE CONSERVA EL FORMATO DETECTADO, SI CAMBIA SE PIERDE
        if (count($this->datos) == 0) {
            $this->formato_x = $formato_x;
        } elseif (($this->formato_x != '') && ($formato_x !== $this->formato_x)) {
            $this->formato_x = '';
        }
        // AGREGAR DATO
        $this->datos[$in_valor_x] = $valores_y;
    } // agregar_datos

    /**
     * Cantidad de datos
     *
     * @return integer Cantidad de datos agregados
     */
    public function cantidad_datos() {
        return count($this->datos);
    } // cantidad_datos

    /**
     * Validar
     */
    protected function validar() {
        // NO HACER NADA SI HAY ERROR
        if ($this->mensaje_error != '') {
            return;
        }
        // DEBEN HABERSE DEFINIDO
        if ($this->clave_x == '') {
            $this->mensaje_error = "Error en GraficaMorrisHTML: No se ha definido la clave del eje X.";
        }
        if (!is_array($this->claves_y) || (count($this->claves_y) == 0)) {
            $this->mensaje_error = "Error en GraficaMorrisHTML: No se han definido las claves para el eje Y.";
        }
        // DEBEN HABERSE AGREGADO DATOS
        if (count($this->datos) == 0) {
            $this->mensaje_error = "Error en GraficaMorrisHTML: No se han agregado datos.";
        }
    } // validar

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // VALIDAR
        $this->validar();
        // SI HAY MENSAJE DE ERROR SE VA A MOSTRAR EN LUGAR DE LA GRAFICA
        if ($this->mensaje_error != '') {
            $mensaje       = new MensajeHTML($this->mensaje_error);
            $mensaje->tipo = 'error';
            return $mensaje->html();
        }
        // PONE EL TAG DIV
        return "<div id=\"{$this->identificador}\" class=\"grafica\"></div>";
    } // html

    /**
     * JavaScript
     *
     * @return string Javascript que controla Leftlet
     */
    public function javascript() {
        // VALIDAR
        $this->validar();
        // SI HAY MENSAJE DE ERROR
        if ($this->mensaje_error != '') {
            return "<!-- SIN JAVASCRIPT PARA GRAFICA POR ERROR -->";
        }
        // OBJETO DE MORRIS A CONSTRUIR
        switch ($this->tipo) {
            case 'areas':
                $objeto = 'Area';
                break;
            case 'barras':
                $objeto = 'Bar';
                break;
            case 'lineas':
            default:
                $objeto = 'Line';
        }
        // ACUMULAREMOS EN ESTE ARREGLO
        $a = array();
        // ELEMENT
        $a[] = "element: '{$this->identificador}'";
        // DATOS
        $d = array();
        foreach ($this->datos as $x => $drreglo_y) {
            $b = array();
            foreach ($drreglo_y as $i => $y) {
                $b[] = "{$this->claves_y[$i]}: $y";
            }
            $d[] = "{ {$this->clave_x}: '$x', ".implode(', ', $b)." }";
        }
        $a[] = "data: [".implode(",", $d)."]";
        // CLAVE DEL EJE X
        $a[] = "xkey: '{$this->clave_x}'";
        // CLAVES DEL EJE Y
        $c = array();
        foreach ($this->claves_y as $clave) {
            $c[] = "'$clave'";
        }
        $a[] = "ykeys: [".implode(', ', $c)."]";
        // ETIQUETAS DEL EJE Y
        $e = array();
        foreach ($this->etiquetas_y as $etiqueta) {
            $e[] = "'$etiqueta'";
        }
        $a[] = "labels: [".implode(', ', $e)."]";
        // COLORES
        if (($objeto == 'Bar') && (count($this->colores_y) > 0)) {
            $c = array();
            foreach ($this->colores_y as $color) {
                $c[] = "'$color'";
            }
            $a[] = "barColors: [".implode(', ', $c)."]";
        }
        if (($objeto == 'Line') && (count($this->colores_y) > 0)) {
            $c = array();
            foreach ($this->colores_y as $color) {
                $c[] = "'$color'";
            }
            $a[] = "lineColors: [".implode(', ', $c)."]";
        }
        // CORREGIR FECHAS EN LA GRÁFICA DE LINEAS
        if (($objeto == 'Line') && ($this->formato_x == 'YYYY-MM-DD')) {
            $a[] = "xLabelFormat: function(d) { return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear(); }";
            $a[] = "dateFormat: function(ts) { var d = new Date(ts); return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear(); }";
        }
        // ENTREGAR
        $interior = implode(",\n      ", $a);
        return <<<FINAL
  // Gráfica
  if (typeof var{$this->identificador} === 'undefined') {
    var{$this->identificador} = Morris.$objeto({
      $interior
    });
  }
FINAL;
    } // javascript

} // Clase GraficaMorrisHTML

?>
