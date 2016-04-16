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
SOY="[Crear Común]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# Nombres de los directorios
ORIGEN_DIR="Eva"
DESTINO_DIR="Demostracion"

# Cambiarse al directorio de destino
if [ -d ../$DESTINO_DIR ]; then
    echo "$SOY O.K. Estoy en $DESTINO_DIR"
else
    cd ../../
    if [ -d ../$DESTINO_DIR ]; then
        echo "$SOY O.K. Me cambié a $DESTINO_DIR"
    else
        echo "$SOY ERROR: No existe $DESTINO_DIR"
        exit $E_FATAL
    fi
fi

# Validar que exista el directorio de origen
if [ ! -d ../$ORIGEN_DIR ]; then
    echo "$SOY ERROR: No existe $ORIGEN_DIR"
    exit $E_FATAL
fi

#
# Procesos para adan
#

if [ ! -d adan ]; then
    echo "$SOY Creando el directorio adan..."
    mkdir htdocs
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude crear el directorio adan"
        exit $E_FATAL
    fi
fi

if [ ! -d adan/bin ]; then
    echo "$SOY Creando el directorio adan/bin..."
    mkdir adan/bin
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude crear el directorio adan/bin"
        exit $E_FATAL
    fi
fi

cd adan/bin
for SCRIPT in CrearGenesisPHP.php ProbarGenesisPHP.php
do
    if [ -x $SCRIPT ]; then
        echo "$SOY Copiando adan/bin/$SCRIPT..."
        cp ../../../Eva/adan/bin/$SCRIPT .
        if [ "$?" -ne $EXITO ]; then
            echo "$SOY ERROR: No pude copiar $SCRIPT"
            exit $E_FATAL
        fi
    fi
done
cd ../../

if [ ! -d adan/lib ]; then
    echo "$SOY Creando el directorio adan/lib..."
    mkdir adan/lib
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude crear el directorio adan/lib"
        exit $E_FATAL
    fi
fi

cd adan/lib
for DIR in `ls ../../../Eva/adan/lib`
do
    if [ ! -h $DIR ]; then
        echo "$SOY Creando el vínculo adan/lib/$DIR..."
        ln -s ../../../Eva/adan/lib/$DIR
        if [ "$?" -ne $EXITO ]; then
            echo "$SOY ERROR: No pude crear el vínculo para adan/lib/$DIR"
            exit $E_FATAL
        fi
    fi
done
cd ../../

if [ ! -d adan/lib/Semillas ]; then
    echo "$SOY Creando el directorio adan/lib/Semillas..."
    mkdir adan/lib/Semillas
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude crear el directorio adan/lib/Semillas"
        exit $E_FATAL
    fi
fi

#
# Procesos para htdocs
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
    echo "$SOY ERROR: No me pude cambiar a htdocs"
    exit $E_FATAL
fi

# Copiar archivos de la raiz
echo "$SOY Copiando los archivos PHP de la raiz..."
cp ../../$ORIGEN_DIR/htdocs/*.php .
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No pude copiar los archivos de la raiz."
    exit $E_FATAL
fi

# Copiar archivos de la raiz
echo "$SOY Copiando favicon.ico a la raiz..."
cp ../../$ORIGEN_DIR/htdocs/favicon.ico .
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No pude copiar favicon.ico"
    exit $E_FATAL
fi

# Copiar directorios
for DIR in bin css fonts img imagenes js
do
    echo "$SOY Copiando $DIR..."
    cp -r ../../$ORIGEN_DIR/htdocs/$DIR .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar $DIR"
        exit $E_FATAL
    fi
    if [ -d ../htdocs-sobreescribir/$DIR ]; then
        echo "$SOY Copiando $DIR desde htdocs-sobreescribir..."
        cp -r ../htdocs-sobreescribir/$DIR/* $DIR/
        if [ "$?" -ne $EXITO ]; then
            echo "$SOY ERROR: No pude copiar $DIR desde htdocs-sobreescribir"
            exit $E_FATAL
        fi
    fi
done

# Crear el directorio htdocs/lib
echo "$SOY Creando el directorio htdocs/lib..."
mkdir lib
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No pude crear el directorio htdocs/lib"
    exit $E_FATAL
fi

# Cambiarse al directorio htdocs/lib
echo "$SOY Cambiándose a htdocs/lib..."
cd lib
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No me pude cambiar a htdocs/lib"
    exit $E_FATAL
fi

# Copiar directorios de htdocs/lib
for DIR in AdmAutentificaciones AdmBitacora AdmDepartamentos AdmIntegrantes AdmModulos AdmRoles AdmSesiones AdmUsuarios Base Configuracion Inicio Personalizar
do
    echo "$SOY Copiando $DIR..."
    cp -r ../../../$ORIGEN_DIR/htdocs/lib/$DIR .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar $DIR"
        exit $E_FATAL
    fi
done

# Copiar directorios de htdocs-sobreescribir/lib
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

#
# Crear enlaces en bin
#
cd ../bin
echo "$SOY Creando enlace de lib en bin..."
ln -s ../lib .
echo "$SOY Creando enlace de imagenes en bin..."
ln -s ../imagenes .

echo "Script terminado."
exit $EXITO

