#!/bin/bash

#
# GenesisPHP - Copiar Tierra
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
SOY="[Copiar Tierra]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# Este script puede ejecutarse en la base del sistema o en el mismo directorio donde esté.
# Cambiarse al directorio sql
if [ -d ../Demostracion ]; then
    echo "$SOY O.K. Estoy en el directorio Demostracion."
else
    cd ../../
    if [ -d ../Demostracion ]; then
        echo "$SOY O.K. Me cambié al directorio Demostracion."
    else
        echo "ERROR: No existe el directorio Demostracion."
        exit $E_FATAL
    fi
fi

#
# css
#
if [ ! -d htdocs/css ]; then
    echo "$SOY Creando directorio css..."
    mkdir htdocs/css
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio css."
        exit $E_FATAL
    fi
fi
echo "$SOY Copiando css..."
cp -r ../Tierra/htdocs/css/* htdocs/css/
if [ "$?" -ne $EXITO ]; then
    echo "ERROR: Falló al copiar el directorio css."
    exit $E_FATAL
fi

#
# fonts
#
if [ -d htdocs/fonts ]; then
    echo "$SOY Eliminando directorio fonts..."
    rm -rf htdocs/fonts
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio fonts."
        exit $E_FATAL
    fi
fi
echo "$SOY Copiando fonts..."
cp -r ../Tierra/htdocs/fonts htdocs/
if [ "$?" -ne $EXITO ]; then
    echo "ERROR: Falló al copiar el directorio fonts."
    exit $E_FATAL
fi

#
# imagenes
#
if [ ! -d htdocs/imagenes ]; then
    echo "$SOY Creando directorio imagenes..."
    mkdir htdocs/imagenes
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio imagenes."
        exit $E_FATAL
    fi
fi
echo "$SOY Copiando imagenes..."
cp -r ../Tierra/htdocs/imagenes/* htdocs/imagenes/
if [ "$?" -ne $EXITO ]; then
    echo "ERROR: Falló al copiar el directorio imagenes."
    exit $E_FATAL
fi

#
# img
#
if [ -d htdocs/img ]; then
    echo "$SOY Eliminando directorio img..."
    rm -rf htdocs/img
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio img."
        exit $E_FATAL
    fi
fi
echo "$SOY Copiando img..."
cp -r ../Tierra/htdocs/img htdocs/
if [ "$?" -ne $EXITO ]; then
    echo "ERROR: Falló al copiar el directorio img."
    exit $E_FATAL
fi

#
# js
#
if [ -d htdocs/js ]; then
    echo "$SOY Eliminando directorio js..."
    rm -rf htdocs/js
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio js."
        exit $E_FATAL
    fi
fi
echo "$SOY Copiando js..."
cp -r ../Tierra/htdocs/js htdocs/
if [ "$?" -ne $EXITO ]; then
    echo "ERROR: Falló al copiar el directorio js."
    exit $E_FATAL
fi

#
# lib
#
if [ ! -d htdocs/lib ]; then
    echo "$SOY Creando directorio lib..."
    mkdir htdocs/lib
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio lib."
        exit $E_FATAL
    fi
fi

#
# lib/Base
#
if [ ! -d htdocs/lib/Base ]; then
    echo "$SOY Creando directorio lib/Base..."
    mkdir htdocs/lib/Base
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio lib/Base."
        exit $E_FATAL
    fi
fi
echo "$SOY Copiando lib/Base..."
cp -r ../Tierra/htdocs/lib/Base/* htdocs/lib/Base/
if [ "$?" -ne $EXITO ]; then
    echo "ERROR: Falló al copiar el directorio lib/Base."
    exit $E_FATAL
fi

#
# lib/Configuracion
#
if [ ! -d htdocs/lib/Configuracion ]; then
    echo "$SOY Creando directorio lib/Configuracion..."
    mkdir htdocs/lib/Configuracion
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio lib/Configuracion."
        exit $E_FATAL
    fi
fi
#echo "$SOY Copiando lib/Configuracion..."
#cp -r ../Tierra/htdocs/lib/Configuracion/* htdocs/lib/Configuracion/
#if [ "$?" -ne $EXITO ]; then
#    echo "ERROR: Falló al copiar el directorio lib/Configuracion."
#    exit $E_FATAL
#fi

#
# lib/Inicio
#
if [ ! -d htdocs/lib/Inicio ]; then
    echo "$SOY Creando directorio lib/Inicio..."
    mkdir htdocs/lib/Inicio
    if [ "$?" -ne $EXITO ]; then
        echo "ERROR: Falló con el directorio lib/Inicio."
        exit $E_FATAL
    fi
fi
echo "$SOY Copiando lib/Inicio..."
cp -r ../Tierra/htdocs/lib/Inicio/* htdocs/lib/Inicio/
if [ "$?" -ne $EXITO ]; then
    echo "ERROR: Falló al copiar el directorio lib/Inicio."
    exit $E_FATAL
fi

echo "Script terminado."
exit $EXITO

