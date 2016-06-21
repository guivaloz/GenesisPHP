<?php
/**
 * GenesisPHP - Página Inicial
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

require_once('lib/Base/AutocargadorClases.php');

/**
 * Clase PaginaInicial
 */
class PaginaInicial extends \Base2\PlantillaWeb {

    // protected $sistema;
    // protected $titulo;
    // protected $descripcion;
    // protected $autor;
    // protected $css;
    // protected $favicon;
    // protected $modelo;
    // protected $menu_principal_logo;
    // protected $modelo_ingreso_logos;
    // protected $modelo_fluido_logos;
    // protected $pie;
    // public $clave;
    // public $menu;
    // public $contenido;
    // public $javascript;
    protected $sesion;

    /**
     * Constructor
     */
    public function __construct() {
        $this->clave = 'inicio';
    } // constructor

    /**
     * Contenido de Inicio
     *
     * @return string HTML
     */
    protected function contenido_inicio_html() {
        // Titulo
        $this->titulo = 'Bienvenido';
        // Usuario
        $this->usuario        = $this->sesion->usuario;
        $this->usuario_nombre = $this->sesion->nombre;
        // Cargar el menu
        $this->menu        = new \Inicio\Menu($this->sesion);
        $this->menu->clave = $this->clave;
        try {
            $this->menu->consultar();
        } catch (\Exception $e) {
            $mensaje           = new \Base2\MensajeWeb($e->getMessage());
            $this->contenido[] = $mensaje->html('Error en menú');
        }
        // Si viene el formulario del cambio de contraseña
        $contrasena_form = new \Personalizar\ContrasenaFormularioWeb($this->sesion);
        if ($_POST['formulario'] == \Personalizar\ContrasenaFormularioWeb::$form_name) {
            // Mostrar el resultado de recibirlo
            $this->contenido[] = $contrasena_form->html();
        } else {
            // Mostrar la situacion de la cuenta
            $situacion         = new \Personalizar\SituacionWeb($this->sesion);
            $this->contenido[] = $situacion->html(); // Definirá contrasena_alerta
            // Si se necesita el cambio de contraseña
            if ($situacion->contrasena_alerta) {
                // Pone el mensaje y el formulario para cambiarla
                $this->contenido[] = $contrasena_form->html();
            } else {
                // Mensaje de bienvenida
                $mensaje           = new \Base2\MensajeWeb(array(
                    'Use el menú para ir a los módulos de este sistema.',
                    'De clic en su nombre de usuario para personalizar su cuenta.',
                    'Para salir del sistema use el botón de apagado. Debe usarlo antes de cerrar la ventana del navegador.'));
                $mensaje->tipo     = 'tip';
                $this->contenido[] = $mensaje->html('Bienvenido');
            }
        }
    } // contenido_inicio_html

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Si quiere salir
        if ($_GET['accion'] == 'salir') {
            try {
                // Salir de la sesion
                $this->sesion = new \Inicio\SesionSalir();
                $this->sesion->cargar('inicio');
                $this->sesion->salir();
                // Mostramos el ingreso
                $this->contenido[] = "Ha cerrado su sesión.";
                $this->modelo      = 'ingreso';
                return parent::html();
            } catch (\Exception $e) {
                // Error, mostramos el ingreso y el mensaje
                $mensaje           = new \Base2\MensajeWeb($e->getMessage());
                $this->contenido[] = $mensaje->html('Error al salir');
                $this->modelo      = 'ingreso';
                return parent::html();
            }
        } elseif (($_POST['nom_corto'] != '') && ($_POST['contrasena'] != '')) {
            // Viene el formulario
            try {
                // Entonces trata de ingresar
                $autentificar = new \Inicio\Autentificar();
                $usuario_id   = $autentificar->usuario_contrasena($_POST['nom_corto'], $_POST['contrasena']);
                // Conservar el nombre corto en una cookie
                $hoy = getdate();
                setcookie('nom_corto', $autentificar->nom_corto, mktime(0, 0, 0, $hoy['mon'], $hoy['mday']+30, $hoy['year']));
                // Nueva sesion
                $this->sesion = new \Inicio\SesionNueva();
                $this->sesion->crear($usuario_id);  // Crear la cookie de la sesion
                $this->sesion->nueva();
                // Entregamos el contenido de la pagina de inicio
                $this->contenido_inicio_html();
                return parent::html();
            } catch (\Exception $e) {
                // Error, mostramos el ingreso y el mensaje
                $mensaje           = new \Base2\MensajeWeb($e->getMessage());
                $this->contenido[] = $mensaje->html('Error al iniciar sesión');
                $this->modelo      = 'ingreso';
                return parent::html();
            }
        } else {
            // No viene el formulario
            try {
                // Tratamos de cargar la sesion
                $this->sesion = new \Inicio\Sesion();
                $this->sesion->cargar('inicio');
                // Entregamos el contenido de la pagina de inicio
                $this->contenido_inicio_html();
                return parent::html();
            } catch (\Exception $e) {
                // Error o no hay sesion, mostramos el ingreso y el mensaje
                $this->contenido[] = $e->getMessage();
                $this->modelo      = 'ingreso';
                return parent::html();
            }
        }
    } // html

} // Clase PaginaInicial

// Ejecutar y mostrar
$pagina = new PaginaInicial();
echo $pagina->html();

?>
