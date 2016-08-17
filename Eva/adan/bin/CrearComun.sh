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

# Validar que exista el directorio Eva
if [ ! -d ../Eva ]; then
    echo "$SOY ERROR: No existe el directorio Eva."
    echo "  Debe ejecutar este script en el directorio del sistema."
    echo "  O mejor con Dios.sh que se encarga de ésto."
    exit $E_FATAL
fi

# Validar que exista el directorio adan/lib
if [ ! -d adan/lib ]; then
    echo "$SOY ERROR: No existe el directorio adan/lib. El script IniciarNuevoSistema.sh debería haberlo hecho."
    exit $E_FATAL
fi

# Validar que exista el directorio adan/lib/Semillas
if [ ! -d adan/lib/Semillas ]; then
    echo "$SOY ERROR: No existe el directorio adan/lib/Semillas. El script IniciarNuevoSistema.sh debería haberlo hecho."
    exit $E_FATAL
fi

# Crear vínculos de adan/lib/*
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

# Copiar archivos de la raiz
echo "$SOY Copiando los archivos PHP de la raiz..."
cp ../../Eva/htdocs/*.php .
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No pude copiar los archivos de la raiz."
    exit $E_FATAL
fi

# Copiar favicon
if [ -e ../../Eva/htdocs/favicon.ico ]; then
    echo "$SOY Copiando favicon.ico..."
    cp ../../Eva/htdocs/favicon.ico .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar favicon.ico"
        exit $E_FATAL
    fi
fi

# Copiar directorios
for DIR in bin css fonts img imagenes js
do
    echo "$SOY Copiando $DIR..."
    cp -r ../../Eva/htdocs/$DIR .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar $DIR"
        exit $E_FATAL
    fi
done
if [ -d imagenes/pruebas ]; then
    rm -rf imagenes/pruebas
fi

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
for DIR in AdmAutentificaciones AdmBitacora AdmCadenero AdmDepartamentos AdmIntegrantes AdmModulos AdmRoles AdmSesiones AdmUsuarios Base2 Inicio Michelf Personalizar
do
    echo "$SOY Copiando $DIR..."
    cp -r ../../../Eva/htdocs/lib/$DIR .
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: No pude copiar $DIR"
        exit $E_FATAL
    fi
done

# Cambiarse al directorio htdocs/bin
echo "$SOY Cambiándose a htdocs/bin..."
cd ../bin
if [ "$?" -ne $EXITO ]; then
    echo "$SOY ERROR: No me pude cambiar a htdocs/bin"
    exit $E_FATAL
fi

# Crear vínculos en bin
echo "$SOY Creando vínculo de lib en bin..."
ln -s ../lib .
echo "$SOY Creando vínculo de imagenes en bin..."
ln -s ../imagenes .

echo "$SOY Script terminado."
exit $EXITO
