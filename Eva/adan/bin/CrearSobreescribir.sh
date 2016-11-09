#!/bin/bash

#
# GenesisPHP - Crear Sobreescribir
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
SOY="[Crear Sobreescribir]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# Validar que exista el directorio Eva
if [ ! -d ../Eva ]; then
    echo "$SOY ERROR: No existe el directorio Eva."
    echo "  Debe ejecutar este script en el directorio del sistema."
    echo "  O mejor con Dios.sh que se encarga de ésto."
    exit $E_FATAL
fi

# Cambiarse al directorio htdocs
echo "$SOY Cambiándose a htdocs..."
cd htdocs

# Sobreescribir archivos en la raiz
if ls ../htdocs-sobreescribir/*.php 1> /dev/null 2>&1; then
    echo "$SOY Copiando *.php desde htdocs-sobreescribir..."
    cp ../htdocs-sobreescribir/*.php .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar los archivos php de la raiz desde htdocs-sobreescribir"
        exit $E_FATAL
    fi
fi

# Sobreescribir favicon.ico
if [ -e ../htdocs-sobreescribir/favicon.ico ]; then
    echo "$SOY Copiando favicon.ico desde htdocs-sobreescribir..."
    cp ../htdocs-sobreescribir/favicon.ico .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar favicon.ico desde htdocs-sobreescribir"
        exit $E_FATAL
    fi
fi

# Sobreescribir directorios
for DIR in bin css fonts img imagenes js
do
    if [ -d ../htdocs-sobreescribir/$DIR ]; then
        echo "$SOY Copiando $DIR desde htdocs-sobreescribir..."
        cp -r ../htdocs-sobreescribir/$DIR/* $DIR/
        if [ "$?" -ne $EXITO ]; then
            echo "$SOY ERROR: No pude copiar $DIR desde htdocs-sobreescribir"
            exit $E_FATAL
        fi
    fi
done

# Cambiarse al directorio htdocs/lib
echo "$SOY Cambiándose a htdocs/lib..."
cd lib

# Sobreescribir directorios de htdocs-sobreescribir/lib
if [ -d ../../htdocs-sobreescribir/lib ]; then
    for DIR in `ls ../../htdocs-sobreescribir/lib`
    do
        echo "$SOY Copiando $DIR desde htdocs-sobreescribir..."
        cp -r ../../htdocs-sobreescribir/lib/$DIR .
        if [ "$?" -ne $EXITO ]; then
            echo "$SOY ERROR: No pude copiar $DIR"
            exit $E_FATAL
        fi
    done
fi

echo "$SOY Script terminado."
exit $EXITO
