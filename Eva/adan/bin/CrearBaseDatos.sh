#!/bin/bash

#
# GenesisPHP - Crear Base Datos
#
# Copyright (C) 2016 Guillermo Valdes Lozano guillermo@movimientolibre.com
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#

#
# Para probar que tiene los permisos, haga una prueba de la creación
# con estos comandos...
#
#   $ createuser -d -s genesisphp
#   $ createdb -O genesisphp genesisphp_demostracion
#   $ psql -l
#

# Yo soy
SOY="[Crear Base de Datos]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# CONFIGURE estas constantes para el sistema
BD="genesisphp_demostracion"
PROPIETARIO="genesisphp"

# Debe configurar este script para su sistema
if [ -z "$BD" ] || [ -z "$PROPIETARIO" ]; then
    echo "$SOY ERROR: No pude cambiarme al directorio sql"
    exit $E_FATAL
fi

# PostgreSQL
export PGHOST=localhost
export PGPORT=5432
#export PGUSER=superusuario

# Si estoy en servidor, cancelo la ejecución de este script
#if [ $HOSTNAME == 'servidora' ]; then
#    echo "$SOY DENEGADO: Se ha prohibido la ejecución de este script en el servidor."
#    exit $E_FATAL
#fi

# Cambiarse al directorio sql
if [ -d sql ]; then
    cd sql
    echo "$SOY Me cambié al directorio sql"
else
    cd ../../
    if [ -d sql ]; then
        cd sql
        echo "$SOY Me cambié al directorio sql"
    else
        echo "$SOY ERROR: No se encuentra el directorio sql"
        exit $E_FATAL
    fi
fi

# Eliminar la base de datos
dropdb $BD

# Crear una base de datos nueva
createdb -O $PROPIETARIO $BD

# Extensiones PostGIS para habilitar georreferenciación
#psql -c "CREATE EXTENSION postgis;" $BD
#psql -c "CREATE EXTENSION postgis_topology;" $BD
#psql -f ../adan/bin/itrf92-inegi-spatial-ref-sys.sql $BD

# Ejecutar cada archivo SQL
for ARCH in `ls *.sql`
do
    psql -U $PROPIETARIO -d $BD -f $ARCH
done

echo "$SOY Script terminado."
exit $EXITO
