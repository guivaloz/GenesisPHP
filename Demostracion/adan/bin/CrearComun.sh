#!/bin/bash

#
# GenesisPHP - Crear Común
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

# Nombres de los directorios
ORIGEN_DIR="Eva"
DESTINO_DIR="Demostracion"

# Cambiarse al directorio de destino
if [ -d "../$DESTINO_DIR" ]; then
    echo "$SOY O.K. Estoy en el directorio $DESTINO_DIR"
else
    cd ../../
    if [ -d "../$DESTINO_DIR" ]; then
        echo "$SOY O.K. Me cambié al directorio $DESTINO_DIR"
    else
        echo "$SOY ERROR: No existe el directorio $DESTINO_DIR"
        exit $E_FATAL
    fi
fi

# Validar que exista el directorio de origen
if [ ! -d "../$ORIGEN_DIR" ]; then
    echo "$SOY ERROR: No existe el directorio $ORIGEN_DIR"
    exit $E_FATAL
fi

#~ # Validar que exista htdocs-sobreescribir
#~ if [ ! -d "$SOBREESCRIBIR" ]; then
    #~ echo "ERROR: No se encuentra $SOBREESCRIBIR"
    #~ echo "Recuerde que debe ejecutar este script desde el directorio base del sistema o dentro de adan/bin"
    #~ exit $E_FATAL
#~ fi

#
# Proceso principal
#

# Si existe htdocs será eliminado
if [ -d "htdocs" ]; then
    echo "$SOY ELIMINANDO los directorios y archivos de htdocs..."
    rm -rf htdocs
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude eliminar el directorio htdocs"
        exit $E_FATAL
    fi
fi

# Crear el directorio htdocs
echo "$SOY Creando el directorio htdocs..."
mkdir htdocs
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No pude crear el directorio htdocs"
    exit $E_FATAL
fi

# Cambiarse al directorio htdocs
echo "$SOY Cambiándose a htdocs..."
cd htdocs
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No me pude cambiar al directorio htdocs"
    exit $E_FATAL
fi

# Copiar archivos de la raiz
echo "$SOY Copiando los archivos PHP de la raiz..."
cp ../../$ORIGEN_DIR/htdocs/*.php .
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No pude copiar los archivos de la raiz."
    exit $E_FATAL
fi

# Copiar directorios
for DIR in bin css fonts img imagenes js
do
    echo "$SOY Copiando $DIR..."
    cp -r ../../$ORIGEN_DIR/htdocs/$DIR .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar el directorio $DIR"
        exit $E_FATAL
    fi
done

# Crear el directorio htdocs/lib
echo "$SOY Creando el directorio htdocs/lib..."
mkdir lib
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No pude crear el directorio htdocs/lib"
    exit $E_FATAL
fi

# Cambiarse al directorio htdocs
echo "$SOY Cambiándose a htdocs/lib..."
cd lib
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No me pude cambiar al directorio htdocs/lib"
    exit $E_FATAL
fi

# Copiar directorios
for DIR in AdmAutentificaciones AdmBitacora AdmDepartamentos AdmIntegrantes AdmModulos AdmRoles AdmSesiones AdmUsuarios Base Configuracion Inicio Personalizar
do
    echo "$SOY Copiando $DIR..."
    cp -r ../../../$ORIGEN_DIR/htdocs/lib/$DIR .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar el directorio $DIR"
        exit $E_FATAL
    fi
done

#~     cp ../$SOBREESCRIBIR/*.php ./
#~     if [ -d ../$SOBREESCRIBIR/$DIR ]; then
#~         echo "Copiando de $SOBREESCRIBIR a $DIR..."
#~         cp -r ../$SOBREESCRIBIR/$DIR/* ./$DIR/
#~     fi

#~ for DIR in `ls ../$SOBREESCRIBIR/lib/`
#~ do
#~ echo "Copiando de $SOBREESCRIBIR a $DIR..."
#~ if [ ! -d "./lib/$DIR" ]; then
#~ mkdir ./lib/$DIR
#~ fi
#~ cp -r ../$SOBREESCRIBIR/lib/$DIR/* ./lib/$DIR/
#~ done

# Crear enlaces en bin
cd ../bin
echo "$SOY Creando enlace de lib en el directorio bin..."
ln -s ../lib .
echo "$SOY Creando enlace de imagenes en el directorio bin..."
ln -s ../imagenes .

echo "Script terminado."
exit $EXITO

