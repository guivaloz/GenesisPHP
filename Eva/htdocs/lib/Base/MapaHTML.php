<?php
/**
 * MapaHTML
 *
 * @package Tierra
 */

// NAMESPACE
namespace Base;

/**
 * Clase MapaHTML
 */
class MapaHTML extends \Configuracion\MapaConfig {

    // public $centro_longitud;
    // public $centro_latitud;
    // public $zoom;
    // public $zoom_minimo;
    // public $zoom_maximo;
    // protected $servidor_mapas;
    protected $identificador;              // ESTE TEXTO ES UNICO A CADA MAPA
    protected $div_id;                     // ID DEL DIV QUE CONTIENE EL MAPA
    protected $variable_mapa;              // NOMBRE DE LA VARIABLE JS CON EL OBJETO DEL MAPA
    protected $funcion_iniciar_mapa;       // NOMBRE DE LA FUNCION QUE INICIA EL MAPA
    protected $categorias     = array();   // ARREGLO CATEGORIAS QUE AFECTAN COMO SE VAN A MOSTRAR, DE LOS PUNTOS LOS COLORES
    protected $geodatos       = array();   // ARREGLO DATOS GEOREFENCIADOS, CADA PUNTO TENDRA LONGITUD, LATITUD Y CATEGORIA
    protected $modo           = 'listado'; // EL MODO detalle CENTRA EL ULTIMO PUNTO Y USA MAYOR ZOOM
    protected $id_ultimo;                  // EL ID DEL ÚLTIMO GEODATO AGREGADO
    static public $colores    = array(
        'blanco'      => '#FFFFFF',
        'gris'        => '#909090',
        'oscuro'      => '#404040',
        'rosa'        => '#FF50A8',
        'rosafuerte'  => '#FF0080',
        'rojo'        => '#FF3030',
        'naranja'     => '#FF9A22',
        'amarillo'    => '#FFFF31',
        'verde'       => '#2BFF2B',
        'verdeoscuro' => '#04AF04',
        'azul'        => '#3535FF'); // LOS COLORES DE LOS PUNTOS DEBEN SER MAS FUERTES QUE EN LOS LISTADOS

    /**
     * Constructor
     *
     * @param string Opcional, el modo del mapa puede ser 'detalle' o 'listado'
     */
    public function __construct($in_modo='') {
        // PARAMETRO
        if (is_string($in_modo) && ($in_modo != '')) {
            $this->modo = $in_modo;
        }
        // POR DEFECTO, TENER EN EL IDENTIFICADOR UNA CADENA DE TEXTO AL AZAR
        $this->identificador = caracteres_azar();
        // LO QUE DEBE SER DISTINTO EN CADA MAPA
        $this->div_id               = "LeafLet{$this->identificador}";
        $this->variable_mapa        = "map{$this->identificador}";
        $this->funcion_iniciar_mapa = "initmap{$this->identificador}";
        // SI EL MODO ES detalle EL ZOOM INICIAL ES MAYOR
        if ($this->modo == 'detalle') {
            $this->zoom = 16;
        }
    } // constructor

    /**
     * Agregar Categoría
     *
     * @param string Categoría
     * @param string Color
     */
    public function agregar_categoria($categoria, $color) {
        $this->categorias[$categoria] = array('relleno_color' => $color);
    } // agregar_categoria

    /**
     * Agregar GeoPunto
     *
     * @param  integer ID del registro
     * @param  string  Cadena de texto GeoJSON
     * @param  string  Categoría
     * @param  string  Descripción
     * @return boolean Verdadero si es válido y se agrega
     */
    public function agregar_geopunto($id, $geojson, $categoria, $in_descripcion='') {
        // VALIDAR CATEGORIA
        if (!isset($this->categorias[$categoria])) {
            return false;
        }
        // VALIDAR DESCRIPCION
        if ($in_descripcion == '') {
            $descripcion = 'Sin descripción';
        } else {
            $descripcion = strval($in_descripcion);
        }
        // VALIDAR GEOJSON, NO PUEDO USAR validar_geopunto($longitud, $latitud)
        if ($geojson == '') {
            return false;
        }
        // AGREGAR
        $this->geodatos[$id] = array(
            'tipo'        => 'geopunto',
            'geojson'     => $geojson,
            'categoria'   => $categoria,
            'descripcion' => $descripcion);
        $this->id_ultimo = $id;
        // ENTREGAR VERDADERO
        return true;
    } // agregar_geopunto

    /**
     * Centrar en el último GeoDato
     */
    protected function centrar() {
        // SI SE HA CARGADO UN GEODATO
        if ($this->id_ultimo > 0) {
            // SI ESE ULTIMO ES UN GEOPUNTO
            if ($this->geodatos[$this->id_ultimo]['tipo'] == 'geopunto') {
                // TOMAR LA LATITUD Y LONGITUD DEL GEOJSON "type":"Point","coordinates":[-103.4154513,25.530039299]
                if (preg_match('/\[(\-*\d+\.\d*),(\-*\d+\.\d*)\]/', $this->geodatos[$this->id_ultimo]['geojson'], $resultados) == 1) {
                    $this->centro_longitud = $resultados[1];
                    $this->centro_latitud  = $resultados[2];
                }
            }
        }
    } // centrar

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // DEBE HABER CATEGORIAS Y DATOS GEOREFERENCIADOS
        if (count($this->categorias) == 0) {
            $mensaje = new \Base\MensajeHTML('Aviso: No hay categorías para mostrar el mapa.');
            return $mensaje->html();
        } elseif (count($this->geodatos) == 0) {
            $mensaje = new \Base\MensajeHTML('Aviso: No hay datos georeferenciados para mostrar el mapa.');
            return $mensaje->html();
        } else {
            return "        <div id=\"{$this->div_id}\" class=\"mapa\"></div>";
        }
        // ENTREGAR CODIGO HTML
        return implode("\n", $a);
    } // html

    /**
     * JavaScript Círculos de Colores
     *
     * @return string Javascript
     */
    protected function javascript_circulos_colores() {
        $a   = array();
        $a[] = '  // DECLARAR LOS CIRCULOS DE COLORES PARA GEOPUNTOS';
        foreach ($this->categorias as $categoria => $datos) {
            $a[] = "  var circulo$categoria = {";
            $a[] = '    "radius": 8,';
            $a[] = '    "fillColor": "'.self::$colores[$datos['relleno_color']].'",';
            $a[] = '    "color": "#000",';
            $a[] = '    "weight": 1,';
            $a[] = '    "opacity": 1,';
            $a[] = '    "fillOpacity": 0.7';
            $a[] = '  };';
        }
        return implode("\n", $a);
    } // javascript_circulos_colores

    /**
     * JavaScript Arreglo con los Geopuntos
     *
     * @return string Javascript
     */
    protected function javascript_arreglo_geopuntos() {
        $a   = array();
        $a[] = '    // ARREGLO CON LOS GEOPUNTOS';
        $a[] = '    var geoPuntos = {';
        $a[] = '      "type": "FeatureCollection",';
        $a[] = '      "features": [';
        $c   = array();
        foreach ($this->geodatos as $id => $datos) {
            $b   = array();
            $b[] = '        {';
            $b[] = '          "type": "Feature",';
            $b[] = '          "properties": { "name": "'.$datos['categoria'].'", "popupContent": "'.$datos['descripcion'].'" },';
            $b[] = '          "geometry": '.$datos['geojson'].',';
            $b[] = '          "id": '.$id;
            $b[] = '        }';
            $c[] = implode("\n", $b);
        }
        $a[] = implode(",\n", $c); // NOTE QUE AGREGA LAS COMAS QUE SEPARAN CADA DATO DEL ARREGLO
        $a[] = '      ]';
        $a[] = '    };';
        return implode("\n", $a);
    } // javascript_arreglo_geopuntos

    /**
     * Javascript Conmutador Categorías
     */
    protected function javascript_conmutador_categorias() {
        $a   = array();
        $a[] = '    // CONMUTAR LOS GEOPUNTOS POR SUS CIRCULOS DE COLORES';
        $a[] = '    L.geoJson(geoPuntos, {';
        $a[] = '      onEachFeature: onEachFeature,';
        $a[] = '      pointToLayer: function (feature, latlng) {';
        $a[] = '        switch (feature.properties.name) {';
        foreach ($this->categorias as $categoria => $datos) {
            $a[] = "          case '$categoria': return L.circleMarker(latlng, circulo$categoria);";
        }
        $a[] = '        }';
        $a[] = '      }';
        $a[] = "    }).addTo({$this->variable_mapa});";
        return implode("\n", $a);
    } // javascript_conmutador_categorias

    /**
     * JavaScript
     *
     * @return string Javascript que controla Leftlet
     */
    public function javascript() {
        // DEBE HABER CATEGORIAS Y DATOS GEOREFERENCIADOS
        if (count($this->categorias) == 0) {
            return '  <!-- NO HAY CATEGORIAS PARA MOSTRAR EL MAPA -->';
        } elseif (count($this->geodatos) == 0) {
            return '  <!-- NO HAY DATOS GEOREFERENCIADOS -->';
        }
        // SI EL MODO ES DETALLE VAMOS A CENTRAR
        if ($this->modo == 'detalle') {
            $this->centrar();
        }
        // ENTREGAR JAVASCRIPT
        return <<<FINAL
  // Mapa
  var {$this->variable_mapa};
{$this->javascript_circulos_colores()}
  // Función para Pop-Ups
  function onEachFeature(feature, layer) {
    if (feature.properties && feature.properties.popupContent) {
      layer.bindPopup(feature.properties.popupContent);
    }
  };
  // Función para el mapa
  function {$this->funcion_iniciar_mapa}() {
    // Nuevo Mapa
    {$this->variable_mapa} = new L.Map('{$this->div_id}');
    // Capa con el mapa
    var osmUrl='{$this->servidor_mapas}';
    var osmAttrib='Ayuntamiento de Torreón. Map data © OpenStreetMap contributors';
    var osm = new L.TileLayer(osmUrl, {minZoom: {$this->zoom_minimo}, maxZoom: {$this->zoom_maximo}, attribution: osmAttrib});
    // Definir coordenadas del centro del mapa y el nivel de zoom
    {$this->variable_mapa}.setView(new L.LatLng({$this->centro_latitud}, {$this->centro_longitud}), {$this->zoom});
    // Agregar capa con el mapa
    {$this->variable_mapa}.addLayer(osm);
{$this->javascript_arreglo_geopuntos()}
{$this->javascript_conmutador_categorias()}
    // Entregar
    return true;
  };
  // Ejecutar el mapa
  if (typeof var{$this->funcion_iniciar_mapa} === 'undefined') {
    var{$this->funcion_iniciar_mapa} = {$this->funcion_iniciar_mapa}();
  };
FINAL;
    } // javascript

} // Clase MapaHTML

?>
