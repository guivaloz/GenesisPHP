<?php
/**
 * GenesisPHP - Imagen
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
 * Clase Imagen
 */
class Imagen {

    public $id;                 // ID de la imagen, forma parte del nombre del archivo
    public $caracteres_azar;    // Caracteres al azar, forma parte del nombre del archivo
    protected $almacen_ruta;    // Texto, ruta absoluta en el disco al almacen de imágenes
    protected $almacen_tamanos; // Arreglo asociativo tamaño => pixeles
    protected $tamano_en_uso;   // Texto, tamaño en uso
    protected $imagen;          // Recurso de imagen, es la imagen original que sera escalada
    protected $ancho;           // Entero
    protected $alto;            // Entero
    protected $ruta;            // Texto

    /**
     * Constructor
     *
     * Recibe la ruta al almacen y el arreglo asociativo con los tamaños
     *
     * @param string Ruta al almacén
     * @param array  Arreglo asociativo con nombre del directorio => tamaño en pixeles
     */
    public function __construct($in_ruta, $in_tamanos) {
        $this->almacen_ruta    = $in_ruta;
        $this->almacen_tamanos = $in_tamanos;
    } // constructor

    /**
     * Cargar
     *
     * Recibe el ID, los caracteres al azar y el tamaño a usar. No valida ni causa excepción.
     *
     * @param integer ID de la imagen
     * @param string  Caracteres al azar usados en el nombre del archivo
     * @param string  Opcional, texto con el tamaño a usar
     */
    public function cargar($in_id, $in_caracteres_azar, $in_tamano='') {
        $this->id              = $in_id;
        $this->caracteres_azar = $in_caracteres_azar;
        if ($in_tamano != '') {
            $this->tamano_en_uso = $in_tamano;
        }
    } // cargar

    /**
     * Usar Tamaño
     *
     * Cambia el tamaño en uso, debe ser uno del arreglo almacen_tamanos.
     *
     * @param string Tamaño
     */
    public function usar_tamano($in_tamano='') {
        // Validar que almacen_tamanos esté definido
        if (!is_array($this->almacen_tamanos) || (count($this->almacen_tamanos) == 0)) {
            throw new \Exception('Error: Falta definir los tamaños para las imágenes.');
        }
        // Si está vacío
        if ($in_tamano === '') {
            // Si la propiedad ya está definida
            if ($this->tamano_en_uso != '' && array_key_exists($this->tamano_en_uso, $this->almacen_tamanos)) {
                return;
            } else {
                // De lo contrario tomar la ultima
                reset($this->almacen_tamanos);
                $ultimo = each($this->almacen_tamanos);
                $this->tamano_en_uso = $ultimo['key'];
            }
        } elseif (is_string($in_tamano) && array_key_exists($in_tamano, $this->almacen_tamanos)) {
            // Cambiar el tamaño en uso al solicitado
            $this->tamano_en_uso = $in_tamano;
        } else {
            // Causa excepción si no están definido almacen_tamanos o si no es uno de ellos.
            throw new \Exception("Error: No se usa el tamaño de imagen '$in_tamano'.");
        }
    } // usar_tamano

    /**
     * Validar
     *
     * Valida ruta, tamaños, tamaño en uso, id y caracteres azar. Truena con excepción.
     */
    protected function validar() {
        // Validar ruta
        if (!is_string($this->almacen_ruta) || ($this->almacen_ruta == '')) {
            throw new \Exception('Error: Falta definir el directorio de almacén de imágenes.');
        } elseif (!is_dir($this->almacen_ruta)) {
            throw new \Exception('Error: El directorio al almacén de imágenes NO existe.');
        }
        // Validar tamaños
        if (!is_array($this->almacen_tamanos) || (count($this->almacen_tamanos) == 0)) {
            throw new \Exception('Error: Falta definir los tamaños para las imágenes.');
        }
        foreach ($this->almacen_tamanos as $directorio => $pixeles) {
            if (!is_dir("{$this->almacen_ruta}/$directorio")) {
                throw new \Exception("Error: El directorio '$directorio' para las imágenes NO existe.");
            }
        }
        // Validar tamaño en uso
        if (!is_string($this->tamano_en_uso) || ($this->tamano_en_uso == '')) {
            $this->usar_tamano();
        } elseif (!array_key_exists($this->tamano_en_uso, $this->almacen_tamanos)) {
            throw new \Exception("Error: El tamaño de imagen '{$this->tamano_en_uso}' es incorrecto.");
        }
        // Validar ID
        if (is_string($this->id) && preg_match('/^[0-9]+$/', $this->id)) {
            $this->id = intval($this->id);
        } elseif (!(is_int($this->id) && ($this->id > 0))) {
            throw new \Exception("Error: ID de imagen incorrecto.");
        }
        // Validar caracteres azar
        if (!is_string($this->caracteres_azar) || !preg_match('/^[a-zA-Z0-9]{4,64}$/', $this->caracteres_azar)) {
            throw new \Exception("Error: Los caracteres al azar para la imagen son incorrectos.");
        }
    } // validar

    /**
     * Obtener la ruta a la imagen
     *
     * @param  integer Opcional, ID de la imagen
     * @param  string  Opcional, Caracteres al azar usados en el nombre del archivo
     * @return string  Ruta y nombre al archivo de imagen
     */
    public function obtener_ruta($in_id='', $in_caracteres_azar='') {
        // Parametros
        if ($in_id != '') {
            $this->id = $in_id;
        }
        if ($in_caracteres_azar != '') {
            $this->caracteres_azar = $in_caracteres_azar;
        }
        // Validar
        $this->validar();
        // Elaborar ruta
        $this->ruta = sprintf('%s/%s/%s%s.jpg', $this->almacen_ruta, $this->tamano_en_uso, $this->id, $this->caracteres_azar);
        // Entregar
        return $this->ruta;
    } // obtener_ruta

    /**
     * Obtener la ruta a la imagen y verificar que exista el archivo
     *
     * @param  integer Opcional, ID de la imagen
     * @param  string  Opcional, Caracteres al azar usados en el nombre del archivo
     * @return string  Ruta y nombre al archivo de imagen
     */
    public function obtener_verificar_ruta($in_id='', $in_caracteres_azar='') {
        // Obtener ruta
        $ruta = $this->obtener_ruta($in_id, $in_caracteres_azar);
        // Verificar que exista el archivo y entregar ruta
        if (file_exists($ruta)) {
            return $ruta;
        } else {
            throw new ImagenExceptionNoEncontrada("Error: Imagen no encontrada.");
        }
    } // obtener_verificar_ruta

    /**
     * Obtener URL a la imagen
     *
     * @param  integer Opcional, ID de la imagen
     * @param  string  Opcional, Caracteres al azar usados en el nombre del archivo
     * @param  string  Opcional, Tamaño
     * @return string  URL al archivo de imagen
     */
    public function obtener_url($in_id='', $in_caracteres_azar='', $in_tamano='') {
        // Parametro tamaño
        $this->usar_tamano($in_tamano);
        // Ejecutamos obtener ruta, eso validara que exista el archivo
        $this->obtener_verificar_ruta($in_id, $in_caracteres_azar);
        // Elaborar URL
        $imagen_url = sprintf('%s/%s/%s%s.jpg', $this->almacen_ruta, $this->tamano_en_uso, $this->id, $this->caracteres_azar);
        // Entregar
        return $imagen_url;
    } // obtener_url

    /**
     * Escalar la imagen a un ancho y alto
     *
     * @param  integer Ancho
     * @param  integer Alto
     * @return mixed   Recurso con la imagen nueva
     */
    protected function escalar_a($in_ancho, $in_alto) {
        $nueva_imagen = imagecreatetruecolor($in_ancho, $in_alto);
        if (imagecopyresampled($nueva_imagen, $this->imagen, 0, 0, 0, 0, $in_ancho, $in_alto, $this->ancho, $this->alto) === true) {
            return $nueva_imagen;
        } else {
            throw new \Exception("Error al tratar de escalar la imagen: Falló.");
        }
    } // escalar_a

    /**
     * Escalar la imagen a alto
     *
     * @param  integer Alto
     * @return mixed   Recurso con la imagen nueva
     */
    public function escalar_a_alto($in_alto) {
        $relacion    = $in_alto / $this->alto;
        $nuevo_ancho = $this->ancho * $relacion;
        return $this->escalar_a($nuevo_ancho, $in_alto);
    } // cambiar_tamano_alto

    /**
     * Escalar la imagen a ancho
     *
     * @param  integer Ancho
     * @return mixed   Recurso con la imagen nueva
     */
    public function escalar_a_ancho($in_ancho) {
        $relacion   = $in_ancho / $this->ancho;
        $nuevo_alto = $this->alto * $relacion;
        return $this->escalar_a($in_ancho, $nuevo_alto);
    } // cambiar_tamano_ancho

    /**
     * Escalar a porcentaje
     *
     * @param  integer Porcentaje de 1 en adelante, donde 100 es sin cambio
     * @return mixed   Recurso con la imagen nueva
     */
    public function escalar_porcentaje($in_porcentaje) {
        $nuevo_ancho = $this->ancho * $in_porcentaje/100;
        $nuevo_alto  = $this->alto * $in_porcentaje/100;
        return $this->escalar_a($nuevo_ancho, $nuevo_alto);
    } // escalar_porcentaje

    /**
     * Almacenar
     *
     * Guarda una imagen subida por un formulario en todos los tamaños
     *
     * @param  string  Ruta al archivo de imagen subido
     * @param  integer ID de la imagen
     * @param  string  Caracteres al azar usados en el nombre del archivo
     */
    public function almacenar($in_archivo_temporal, $in_id, $in_caracteres_azar) {
        // Verificar que exista el archivo temporal
        if (!file_exists($in_archivo_temporal)) {
            throw new \Exception("Error al almacenar imagen: No existe el archivo temporal.");
        }
        // La funcion getimagesize entrega un arreglo con 7 elementos
        $informacion = getimagesize($in_archivo_temporal);
        if (!is_array($informacion)) {
            throw new \Exception("Error al almacenar imagen: No se pudo obtener información del archivo temporal.");
        }
        $this->ancho = $informacion[0];
        $this->alto  = $informacion[1];
        if ($informacion[2] != IMAGETYPE_JPEG) {
            throw new \Exception("Error al almacenar imagen: El archivo de imagen a subir NO es jpeg.");
        }
        // Definimos la propiedad imagen que sera usada por los metodos que cambian el tamaño
        $this->imagen = imagecreatefromjpeg($in_archivo_temporal);
        // Bucle por tamaños
        foreach ($this->almacen_tamanos as $tamano => $medida) {
            // Tamaño y ruta
            $this->usar_tamano($tamano);
            $destino_ruta = $this->obtener_ruta($in_id, $in_caracteres_azar);
            // No sobreescribir
            if (file_exists($destino_ruta)) {
                continue;
            }
            // Cambiar tamaño
            if (is_int($medida) && ($medida > 0)) {
                $imagen_nueva = $this->escalar_a_alto($medida);
            } else {
                throw new \Exception("Error al almacenar imagen: La medida es incorrecta.");
            }
            // Guardar imagen
            if (imagejpeg($imagen_nueva, $destino_ruta, 75) === false) {
                throw new \Exception("Error al almacenar imagen: No se pudo almacenar la imagen de tamaño '$tamano'.");
            }
        }
    } // almacenar

} // Clase Imagen

?>
