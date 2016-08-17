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

# Directorio del sistema
SISTEMA_DIR="Demostracion"

# Cambiarse al directorio
if [ -d ../$SISTEMA_DIR ]; then
    echo "$SOY Ya estaba en $SISTEMA_DIR"
elif [ -d ./$SISTEMA_DIR ]; then
    cd ./$SISTEMA_DIR
    echo "$SOY Me cambié al directorio $SISTEMA_DIR"
else
    cd ../../
    if [ -d ../$SISTEMA_DIR ]; then
        echo "$SOY Me cambié al directorio $SISTEMA_DIR"
    else
        echo "$SOY ERROR: No existe $SISTEMA_DIR. Tal vez esté sin configurar Dios.sh."
        exit $E_FATAL
    fi
fi

# Verificar que existan los scripts
if [ ! -x adan/bin/ProtegerArchivos.sh ]; then
    echo "$SOY ERROR: No existe el ejecutable adan/bin/ProtegerArchivos.sh"
    exit $E_FATAL
fi
if [ ! -x adan/bin/CrearComun.sh ]; then
    echo "$SOY ERROR: No existe el ejecutable adan/bin/CrearComun.sh"
    exit $E_FATAL
fi
if [ ! -x adan/bin/CrearGenesisPHP.php ]; then
    echo "$SOY ERROR: No existe el ejecutable adan/bin/CrearGenesisPHP.php"
    exit $E_FATAL
fi
if [ ! -x adan/bin/CrearSobreescribir.sh ]; then
    echo "$SOY ERROR: No existe el ejecutable adan/bin/CrearSobreescribir.sh"
    exit $E_FATAL
fi
if [ ! -x adan/bin/CrearExclusivos.sh ]; then
    echo "$SOY ERROR: No existe el ejecutable adan/bin/CrearExclusivos.sh"
    exit $E_FATAL
fi
if [ ! -x adan/bin/RestaurarArchivos.sh ]; then
    echo "$SOY ERROR: No existe el ejecutable adan/bin/RestaurarArchivos.sh"
    exit $E_FATAL
fi

# Ejecutar
adan/bin/ProtegerArchivos.sh && \
adan/bin/CrearComun.sh && \
adan/bin/CrearGenesisPHP.php && \
adan/bin/CrearSobreescribir.sh && \
adan/bin/CrearExclusivos.sh && \
adan/bin/RestaurarArchivos.sh

echo "$SOY Script terminado."
exit $EXITO
