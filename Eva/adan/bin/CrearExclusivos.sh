#!/bin/bash

#
# GenesisPHP - Crear Exclusivos
#
# Copyright (C) 2016 Guillermo Valdes Lozano guillermo@movimientolibre.com
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#

# Yo soy
SOY="[Crear Exclusivos]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# Nombres de los directorios
DESTINO_DIR="Demostracion"

# Validar que exista el directorio Eva
if [ ! -d ../Eva ]; then
    echo "$SOY ERROR: No existe el directorio Eva."
    echo "  Debe ejecutar este script en el directorio del sistema."
    echo "  O mejor con Dios.sh que se encarga de ésto."
    exit $E_FATAL
fi

#
# Escriba aquí los comandos exclusivos para este sistema
#

echo "$SOY Script terminado."
exit $EXITO
