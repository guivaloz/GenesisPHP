#!/bin/bash

#
# GenesisPHP - Crear Común
#
# Copyright 2015 Guillermo Valdés Lozano <guivaloz@movimientolibre.com>
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

#### Constantes ####

# Para todos los sistemas creados con Génesis
GENESIS="$HOME/Documentos/GenesisPHP/GitHub/GenesisPHP"
JARDIN="$GENESIS/Tierra/htdocs"
SOBREESCRIBIR="htdocs-sobreescribir"
HTDOCS="htdocs"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

#### Validaciones ####

# Si no está el directorio adán puede que se haya ejecutado este script desde adan/bin
if [ ! -d adan ]; then
    cd ../../
    if [ ! -d adan ]; then
        echo "ERROR: No se encuentra el directorio adan. Este sistema no es para Génesis."
        exit $E_FATAL
    fi
fi

# Validar que exista htdocs-sobreescribir
if [ ! -d "$SOBREESCRIBIR" ]; then
    echo "ERROR: No se encuentra $SOBREESCRIBIR"
    echo "Recuerde que debe ejecutar este script desde el directorio base del sistema o dentro de adan/bin"
    exit $E_FATAL
fi

# Si no existe htdocs será creado
if [ ! -d "$HTDOCS" ]; then
    mkdir $HTDOCS
fi

#### Proceso principal ####

#
# Inicia lo Exclusivo
#
# Fotos de las personas
#PERSONASFOTOS="$HTDOCS/imagenes/exppersonasfotos"
#if [ -d $PERSONASFOTOS ]; then
#    echo "Resguardando $PERSONASFOTOS..."
#    mv $PERSONASFOTOS $HTDOCS/.exppersonasfotos
#fi
#
# Termina lo exclusivo
#

echo "COPIANDO los archivos de la raiz..."
for ARCH in index.php autentificaciones.php bitacora.php departamentos.php integrantes.php modulos.php personalizar.php roles.php sesiones.php sistema.php usuarios.php
do
    cp $JARDIN/$ARCH $HTDOCS/
done
if [ -e $SOBREESCRIBIR/favicon.ico ]; then
    cp $SOBREESCRIBIR/favicon.ico $HTDOCS/
fi
cp $SOBREESCRIBIR/*.php $HTDOCS/

echo "COPIANDO los archivos y directorios del directorio bin..."
mkdir $HTDOCS/bin
cp -r $JARDIN/bin/* $HTDOCS/bin/
cp -r $SOBREESCRIBIR/bin/*.php $HTDOCS/bin/

echo "COPIANDO los archivos y directorios del directorio css..."
mkdir $HTDOCS/css
cp -r $JARDIN/css/* $HTDOCS/css/
cp -r $SOBREESCRIBIR/css/*.css $HTDOCS/css/

echo "COPIANDO los archivos y directorios del directorio fonts..."
mkdir $HTDOCS/fonts
cp -r $JARDIN/fonts/* $HTDOCS/fonts/

echo "COPIANDO los archivos y directorios del directorio img..."
mkdir $HTDOCS/img
cp -r $JARDIN/img/* $HTDOCS/img/

echo "COPIANDO los archivos y directorios del directorio imagenes..."
mkdir $HTDOCS/imagenes
cp -r $JARDIN/imagenes/* $HTDOCS/imagenes/
cp -r $SOBREESCRIBIR/imagenes/* $HTDOCS/imagenes/

echo "COPIANDO los archivos y directorios del directorio js..."
mkdir $HTDOCS/js
cp -r $JARDIN/js/* $HTDOCS/js/

if [ -d "$JARDIN/leaflet" ]; then
    echo "COPIANDO los archivos y directorios del directorio leaflet..."
    mkdir $HTDOCS/leaflet
    cp -r $JARDIN/leaflet/* $HTDOCS/leaflet/
fi

mkdir $HTDOCS/lib
for DIR in `ls $JARDIN/lib/`
do
    echo "COPIANDO los archivos de lib/$DIR..."
    mkdir $HTDOCS/lib/$DIR
    cp -r $JARDIN/lib/$DIR/* $HTDOCS/lib/$DIR/
done
for DIR in `ls $SOBREESCRIBIR/lib/`
do
    echo "COPIANDO los archivos de lib/$DIR..."
    if [ ! -d "$HTDOCS/lib/$DIR" ]; then
        mkdir $HTDOCS/lib/$DIR
    fi
    cp -r $SOBREESCRIBIR/lib/$DIR/* $HTDOCS/lib/$DIR/
done

#
# Inicia lo Exclusivo
#
# Fotos de las personas
#if [ -d $HTDOCS/.exppersonasfotos ]; then
#    echo "Recuperando $PERSONASFOTOS..."
#    mv $HTDOCS/.exppersonasfotos $PERSONASFOTOS
#else
#    echo "CREANDO DIRECTORIO $PERSONASFOTOS..."
#    mkdir -p $PERSONASFOTOS/big
#    mkdir -p $PERSONASFOTOS/middle
#    mkdir -p $PERSONASFOTOS/small
#    chmod -R a+w $PERSONASFOTOS
#fi
#
# Termina lo exclusivo
#

cd $HTDOCS/bin
echo "Creando enlaces de lib en el directorio bin..."
ln -s ../lib .
echo "Creando enlaces de imagenes en el directorio bin..."
ln -s ../imagenes .

echo "Script terminado."
exit $EXITO
