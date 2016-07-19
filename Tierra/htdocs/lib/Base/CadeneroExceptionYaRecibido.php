<?php
/**
 * Cadenero Exception Ya Recibido
 *
 * @package Tierra
 */

// NAMESPACE
namespace Base;

/**
 * Clase CadeneroExceptionYaRecibido
 */
class CadeneroExceptionYaRecibido extends \Exception {

	/**
	 * Constructor
	 *
	 * @param mixed  Sesion para saber quien lo provoca
	 * @param string Nombre del formulario para saber en donde ocurre
	 */
	public function __construct(\Inicio\Sesion $sesion, $form_name) {
		// MENSAJE
		$mensaje = sprintf('Se negó a %s el formulario %s porque ya se había recibido.', $sesion->nom_corto, $form_name);
		// INSERTAR
		$base_datos = new BaseDatosMotor();
		try {
			$base_datos->comando(sprintf("INSERT INTO bitacora (usuario, pagina, tipo, url, notas) VALUES (%s, %s, %s, %s, %s)",
				$sesion->usuario,
				sql_texto($sesion->pagina),
				sql_texto('J'),
				sql_texto($_SESION['PHP_SELF']),
				sql_texto($mensaje)), true); // TIENE EL TRUE PARA TRONAR EN CASO DE ERROR
		} catch (\Exception $e) {
			die("Error: Al agregar a la bitácora.");
		}
		// EJECUTAR CONSTRUCTOR DEL PADRE
		parent::__construct('Aviso: Ya se ha recibido ese formulario. Por favor, no use el botón "atrás" del navegador.');
	} // constructor

} // Clase CadeneroExceptionYaRecibido

?>
