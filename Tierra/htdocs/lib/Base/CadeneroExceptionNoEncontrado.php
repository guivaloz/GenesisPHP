<?php
/**
 * Cadenero Exception No Encontrado
 *
 * @package Tierra
 */

// NAMESPACE
namespace Base;

/**
 * Clase CadeneroExceptionNoEncontrado
 */
class CadeneroExceptionNoEncontrado extends \Exception {

	/**
	 * Constructor
	 *
	 * @param mixed  Sesion para saber quien lo provoca
	 * @param string Nombre del formulario para saber en donde ocurre
	 * @param string Mensaje extra
	 */
	public function __construct(\Inicio\Sesion $sesion, $form_name, $mensaje) {
		// MENSAJE
		$mensaje = sprintf('Es ilegal el formulario %s recibido de %s. %s', $form_name, $sesion->nom_corto, $mensaje);
		// INSERTAR
		$base_datos = new BaseDatosMotor();
		try {
			$base_datos->comando(sprintf("INSERT INTO bitacora (usuario, pagina, tipo, url, notas) VALUES (%s, %s, %s, %s, %s)",
				$sesion->usuario,
				sql_texto($sesion->pagina),
				sql_texto('K'),
				sql_texto($_SESION['PHP_SELF']),
				sql_texto($mensaje)), true); // TIENE EL TRUE PARA TRONAR EN CASO DE ERROR
		} catch (\Exception $e) {
			die("Error: Al agregar Excepción de Cadenero a la bitácora.");
		}
		// EJECUTAR CONSTRUCTOR DEL PADRE
		parent::__construct('Error: El formulario se rechazó por haber caducado o por haber sido alterado.');
	} // constructor

} // Clase CadeneroExceptionNoEncontrado

?>
