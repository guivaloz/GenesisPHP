<?php
/**
 * GenesisPHP - EntidadesListado
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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
 */

namespace Pruebas;

/**
 * Clase EntidadesListado
 */
class EntidadesListado extends \Base\Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;

    /**
     * Encabezado
     *
     * @return string Texto del encabezado
     */
    public function encabezado() {
        return 'Entidades Federativas de México';
    } // encabezado

    /**
     * Consultar
     */
    public function consultar() {
        // Definir listado con el resultado de lo que sería una consulta a la base de datos
        $this->listado = array(
            array('nombre' => 'Distrito Federal',                'capital' => '',                          'poblacion' =>  8851080, 'fundacion' => '1824-11-18'),
            array('nombre' => 'Aguascalientes',                  'capital' => 'Aguascalientes',            'poblacion' =>  1184996, 'fundacion' => '1857-02-05'),
            array('nombre' => 'Baja California',                 'capital' => 'Mexicali',                  'poblacion' =>  3155070, 'fundacion' => '1952-01-16'),
            array('nombre' => 'Baja California Sur',             'capital' => 'La Paz',                    'poblacion' =>   637026, 'fundacion' => '1974-10-08'),
            array('nombre' => 'Campeche',                        'capital' => 'San Francisco de Campeche', 'poblacion' =>   822441, 'fundacion' => '1863-04-29'),
            array('nombre' => 'Chiapas',                         'capital' => 'Tuxtla Gutiérrez',          'poblacion' =>  4796580, 'fundacion' => '1824-09-14'),
            array('nombre' => 'Chihuahua',                       'capital' => 'Chihuahua',                 'poblacion' =>  3406465, 'fundacion' => '1824-07-06'),
            array('nombre' => 'Coahuila de Zaragoza',            'capital' => 'Saltillo',                  'poblacion' =>  3055395, 'fundacion' => '1824-05-07'),
            array('nombre' => 'Colima',                          'capital' => 'Colima',                    'poblacion' =>   650555, 'fundacion' => '1856-12-09'),
            array('nombre' => 'Durango',                         'capital' => 'Victoria de Durango',       'poblacion' =>  1632934, 'fundacion' => '1824-05-22'),
            array('nombre' => 'Guanajuato',                      'capital' => 'Guanajuato',                'poblacion' =>  5486372, 'fundacion' => '1823-12-20'),
            array('nombre' => 'Guerrero',                        'capital' => 'Chilpancingo de los Bravo', 'poblacion' =>  3388768, 'fundacion' => '1849-10-27'),
            array('nombre' => 'Hidalgo',                         'capital' => 'Pachuca de Soto',           'poblacion' =>  2665018, 'fundacion' => '1869-01-16'),
            array('nombre' => 'Jalisco',                         'capital' => 'Guadalajara',               'poblacion' =>  7350682, 'fundacion' => '1823-12-23'),
            array('nombre' => 'México',                          'capital' => 'Toluca de Lerdo',           'poblacion' => 15175862, 'fundacion' => '1823-12-20'),
            array('nombre' => 'Michoacán de Ocampo',             'capital' => 'Morelia',                   'poblacion' =>  4351037, 'fundacion' => '1823-12-22'),
            array('nombre' => 'Morelos',                         'capital' => 'Cuernavaca',                'poblacion' =>  1777227, 'fundacion' => '1869-04-17'),
            array('nombre' => 'Nayarit',                         'capital' => 'Tepic',                     'poblacion' =>  1084979, 'fundacion' => '1917-01-26'),
            array('nombre' => 'Nuevo León',                      'capital' => 'Monterrey',                 'poblacion' =>  4653458, 'fundacion' => '1824-05-07'),
            array('nombre' => 'Oaxaca',                          'capital' => 'Oaxaca de Juárez',          'poblacion' =>  3801962, 'fundacion' => '1823-12-21'),
            array('nombre' => 'Puebla',                          'capital' => 'Puebla de Zaragoza',        'poblacion' =>  5779829, 'fundacion' => '1823-12-21'),
            array('nombre' => 'Querétaro de Arteaga',            'capital' => 'Querétaro',                 'poblacion' =>  1827937, 'fundacion' => '1823-12-23'),
            array('nombre' => 'Quintana Roo',                    'capital' => 'Chetumal',                  'poblacion' =>  1325578, 'fundacion' => '1974-10-08'),
            array('nombre' => 'San Luis Potosí',                 'capital' => 'San Luis Potosí',           'poblacion' =>  2585518, 'fundacion' => '1823-12-22'),
            array('nombre' => 'Sinaloa',                         'capital' => 'Culiacán Rosales',          'poblacion' =>  2767761, 'fundacion' => '1830-10-14'),
            array('nombre' => 'Sonora',                          'capital' => 'Hermosillo',                'poblacion' =>  2662480, 'fundacion' => '1824-01-10'),
            array('nombre' => 'Tabasco',                         'capital' => 'Villahermosa',              'poblacion' =>  2238603, 'fundacion' => '1824-02-07'),
            array('nombre' => 'Tamaulipas',                      'capital' => 'Ciudad Victoria',           'poblacion' =>  3268554, 'fundacion' => '1824-02-07'),
            array('nombre' => 'Tlaxcala',                        'capital' => 'Tlaxcala de Xicohténcatl',  'poblacion' =>  1169936, 'fundacion' => '1856-12-09'),
            array('nombre' => 'Veracruz de Ignacio de la Llave', 'capital' => 'Xalapa-Enríquez',           'poblacion' =>  7643194, 'fundacion' => '1823-12-22'),
            array('nombre' => 'Yucatán',                         'capital' => 'Mérida',                    'poblacion' =>  1955577, 'fundacion' => '1823-12-23'),
            array('nombre' => 'Zacatecas',                       'capital' => 'Zacatecas',                 'poblacion' =>  1490668, 'fundacion' => '1823-12-23'));
        // Definir la cantidad de registros
        $this->cantidad_registros = count($this->listado);
        // Ya fue consultado
        $this->consultado = true;
    } // consultar

} // Clase EntidadesListado

?>
