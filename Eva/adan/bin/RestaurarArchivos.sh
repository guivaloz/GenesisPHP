#!/bin/bash

#
# GenesisPHP - Restaurar Archivos
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
SOY="[Restaurar Archivos]"

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

#
# Programe aquí las operaciones para restaurar los archivos impresindibles de htdocs
#

# Cambiarse al directorio htdocs/imagenes
#~ echo "$SOY Cambiándose a htdocs/imagenes..."
#~ cd htdocs/imagenes
#~ if [ "$?" -ne $EXITO ]; then
    #~ echo "$SOY ERROR: No me pude cambiar a htdocs/imagenes"
    #~ exit $E_FATAL
#~ fi

# Restaurar fotos de expedientes personas
#~ if [ -d "../../.exppersonasfotos" ]; then
    #~ echo "$SOY Restaurando imagenes/exppersonasfotos..."
    #~ mv ../../.exppersonasfotos exppersonasfotos
#~ else
    #~ echo "$SOY Creando directorios para las fotos de expedientes..."
    #~ mkdir -p exppersonasfotos/big
    #~ mkdir -p exppersonasfotos/middle
    #~ mkdir -p exppersonasfotos/small
    #~ echo "$SOY Por favor INGRESE SU CONTRASEÑA para ejecutar sudo y cambiar los permisos de los directorios..."
    #~ sudo chgrp -R apache exppersonasfotos
    #~ chmod -R g+w exppersonasfotos
#~ fi

echo "$SOY Script terminado."
exit $EXITO
