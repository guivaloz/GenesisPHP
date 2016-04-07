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

# Validar que estemos en el directorio htdocs
HTDOCS="htdocs"
if [ ! -d "$HTDOCS" ]; then
    HTDOCS="../../htdocs"
fi
if [ ! -d "$HTDOCS" ]; then
    echo "$SOY ERROR: No se encuentra el directorio htdocs"
    exit $E_FATAL
fi

echo "$SOY DESTRUYENDO..."
cd $HTDOCS
rm -rf *

echo "$SOY Destruido XD"
exit $EXITO

