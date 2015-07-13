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

#
# Ruta a la capeta donde está GenesisPHP
#
GENESISPHP="$HOME/Documentos/GenesisPHP/GitHub/GenesisPHP"

#
# Constantes
#
JARDIN="$GENESISPHP/Tierra/htdocs"
SOBREESCRIBIR="htdocs-sobreescribir"
HTDOCS="htdocs"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

#
# Validaciones
#

# Validar GenesisPHP
if [ ! -d "$GENESISPHP" ]; then
    echo "ERROR: No se encuentra $GENESISPHP"
    echo "Revise que en CrearComun.sh la variable GENESISPHP tenga la ruta correcta."
    exit $E_FATAL
fi
if [ ! -d "$JARDIN" ]; then
    echo "ERROR: No se encuentra $JARDIN"
    echo "Revise que en CrearComun.sh la variable JARDIN tenga la ruta correcta."
    exit $E_FATAL
fi

# Debe ejecutarse en el directorio base del sistema o desde adan/bin
if [ ! -d adan ]; then
    cd ../../
    if [ ! -d adan ]; then
        echo "ERROR: No se encuentra el directorio adan."
        exit $E_FATAL
    fi
fi

# Validar que exista htdocs-sobreescribir
if [ ! -d "$SOBREESCRIBIR" ]; then
    echo "ERROR: No se encuentra $SOBREESCRIBIR"
    echo "Recuerde que debe ejecutar este script desde el directorio base del sistema o dentro de adan/bin"
    exit $E_FATAL
fi

#
# Proceso principal
#

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

# Si no existe htdocs será creado
if [ ! -d "$HTDOCS" ]; then
    mkdir $HTDOCS
    cd $HTDOCS
else
    echo "Presione ENTER para DESTRUIR todo el contenido de $HTDOCS"
    read
    cd $HTDOCS
    echo "DESTRUYENDO..."
    rm -rf *
fi

echo "Copiando los archivos PHP de la raiz..."
for ARCH in index.php autentificaciones.php bitacora.php departamentos.php integrantes.php modulos.php personalizar.php roles.php sesiones.php usuarios.php
do
    cp $JARDIN/$ARCH ./
done
cp ../$SOBREESCRIBIR/*.php ./

if [ -e ../$SOBREESCRIBIR/favicon.ico ]; then
    echo "Copiando favicon..."
    cp ../$SOBREESCRIBIR/favicon.ico ./
fi

for DIR in bin css fonts img imagenes js
do
    echo "Copiando $DIR..."
    mkdir ./$DIR
    cp -r $JARDIN/$DIR/* ./$DIR/
    if [ -d ../$SOBREESCRIBIR/$DIR ]; then
        echo "Copiando de $SOBREESCRIBIR a $DIR..."
        cp -r ../$SOBREESCRIBIR/$DIR/* ./$DIR/
    fi
done

mkdir ./lib
for DIR in `ls $JARDIN/lib/`
do
    echo "Copiando $DIR..."
    mkdir ./lib/$DIR
    cp -r $JARDIN/lib/$DIR/* ./lib/$DIR/
done
for DIR in `ls ../$SOBREESCRIBIR/lib/`
do
    echo "Copiando de $SOBREESCRIBIR a $DIR..."
    if [ ! -d "./lib/$DIR" ]; then
        mkdir ./lib/$DIR
    fi
    cp -r ../$SOBREESCRIBIR/lib/$DIR/* ./lib/$DIR/
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

cd ./bin
echo "Creando enlaces de lib en el directorio bin..."
ln -s ../lib .
echo "Creando enlaces de imagenes en el directorio bin..."
ln -s ../imagenes .

echo "Script terminado."
exit $EXITO
