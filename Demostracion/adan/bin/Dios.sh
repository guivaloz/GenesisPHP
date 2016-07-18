#!/bin/bash

#
# GenesisPHP - Dios
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
SOY="[Dios]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# Si estamos en el directorio base del sistema
if [ -e "adan/bin/Destruir.sh" ] && [ -e "adan/bin/CrearComun.sh" ] && [ -e "adan/bin/CrearGenesisPHP.php" ]; then
    adan/bin/Destruir.sh && adan/bin/CrearComun.sh && adan/bin/CrearGenesisPHP.php
    exit $?
fi

# Si estamos en adan/bin
if [ -e "./Destruir.sh" ] && [ -e "./CrearComun.sh" ] && [ -e "./CrearGenesisPHP.php" ]; then
    ./Destruir.sh && ./CrearComun.sh && ./CrearGenesisPHP.php
    exit $?
fi

echo "$SOY ERROR: No existe Destruir.sh o CrearComun.sh o CrearGenesisPHP.sh"
exit $E_FATAL
