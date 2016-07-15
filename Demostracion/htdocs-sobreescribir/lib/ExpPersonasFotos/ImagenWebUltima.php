<?php
/**
 * Demostración - Última foto del expediente de una persona
 *
 * @package GenesisPHP
 */

namespace ExpPersonasFotos;

/**
 * Clase ImagenWebUltima
 */
class ImagenWebUltima extends \Base2\ImagenWeb {

    // public $id;
    // public $caracteres_azar;
    // protected $almacen_ruta;
    // protected $almacen_tamanos;
    // protected $tamano_en_uso;
    // protected $imagen;
    // protected $ancho;
    // protected $alto;
    // protected $ruta;
    // public $pie;
    // public $url;
    // public $a_class;
    // public $img_class;
    // public $p_class;

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        $this->sesion = $in_sesion;
        parent::__construct(Registro::$imagen_almacen_ruta, Registro::$imagen_tamanos);
    } // constructor

    /**
     * Consultar
     *
     * @param integer ID de la persona
     */
    public function consultar($in_persona_id) {
        // Consultar persona
        $persona = new \ExpPersonas\Registro($this->sesion);
        $persona->consultar($in_persona_id);
        // Consultar imágenes de la persona
        $base_datos = new \Base2\BaseDatosMotor();
        try {
            $consulta = $base_datos->comando(sprintf("
                SELECT
                    id,
                    caracteres_azar
                FROM
                    exp_personas_fotos
                WHERE
                    persona = %d
                    AND estatus = 'A'
                ORDER BY
                    creado DESC",
                $persona->id));
        } catch (Exception $e) {
            throw new \Base2\BaseDatosExceptionSQLError($this->sesion, 'Error: Al consultar la última foto de la persona. ', $e->getMessage());
        }
        // Provoca excepcion si no hay registros
        if ($consulta->cantidad_registros() == 0) {
            throw new \Base2\ListadoExceptionVacio('Aviso: No se encontraron fotos para la persona.');
        }
        // Obtener sólo la más reciente
        $resultado = $consulta->obtener_registro();
        // Definir los parámetros requeridos
        $this->id              = $resultado['id'];
        $this->caracteres_azar = $resultado['caracteres_azar'];
    } // consultar

} // Clase ImagenWebUltima

?>
