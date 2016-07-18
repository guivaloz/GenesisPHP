#!/bin/bash

#
# GenesisPHP - Destruir
#
# Copyright 2016 Guillermo Valdés Lozano <guivaloz@movimientolibre.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
# MA 02110-1301, USA.
#

# Yo soy
SOY="[Destruir]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# Directorio del sistema
DESTINO_DIR="Demostracion"

# Cambiarse al directorio de destino
if [ -d ../$DESTINO_DIR ]; then
    echo "$SOY O.K. Estoy en $DESTINO_DIR"
elif [ -d ./$DESTINO_DIR ]; then
    cd ./$DESTINO_DIR
    echo "$SOY Me cambié al directorio $DESTINO_DIR"
else
    cd ../../
    if [ -d ../$DESTINO_DIR ]; then
        echo "$SOY O.K. Me cambié a $DESTINO_DIR"
    else
        echo "$SOY ERROR: No existe $DESTINO_DIR"
        exit $E_FATAL
    fi
fi

#
# Inica exclusivo para Demostración
#

# Respaldar fotos de expedientes personas
if [ -d "htdocs/imagenes/exppersonasfotos" ]; then
    echo "$SOY Resguardando imagenes/exppersonasfotos..."
    mv htdocs/imagenes/exppersonasfotos .exppersonasfotos
fi

#
# Termina exclusivo para Demostración
#

# Si existe htdocs será eliminado
if [ -d "htdocs" ]; then
    echo "$SOY ELIMINANDO los directorios y archivos de htdocs..."
    rm -rf htdocs
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude eliminar htdocs"
        exit $E_FATAL
    fi
fi

echo "$SOY Script terminado."
exit $EXITO
