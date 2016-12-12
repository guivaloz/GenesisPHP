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
# Previamiente debe crear el usuario sin privilegios ser superusuario
#
#   $ createuser -D -S genesisphp
#
# Luego crear la base de datos con su propiedad
#
#   $ createdb -O genesisphp genesisphp_demostracion
#   $ psql -l
#
# Si tiene éxito en los comandos anteriores, ejecute...
#
#   $ ./CrearBaseDatos.sh
#

# Yo soy
SOY="[Crear Base de Datos]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# CONFIGURE estas constantes
#BD="genesisphp_demostracion"
#PROPIETARIO="genesisphp"

#
# Debe configurar este script para su sistema
# Si no lo ha hecho, mostrará este mensaje
#
if [ -z "$BD" ] || [ -z "$PROPIETARIO" ]; then
    echo "GenesisPHP"
    echo "  AVISO: No ha configurado este script."
    echo
    echo "Pasos a seguir:"
    echo "1 Verifique que PostgreSQL esté funcionando y"
    echo "  y que su cuenta de usuario tenga permisos para"
    echo "  crear y eliminar bases de datos"
    echo "    $ createuser -D -S genesisphp"
    echo "    $ createdb -O genesisphp genesisphp_demostracion"
    echo "    $ psql -l"
    echo
    echo "2 Cree archivos sql en el directorio sql con los"
    echo "  CREATE TABLE e INSERT necesarios para la"
    echo "  base de datos inicial de su sistema"
    echo
    echo "3 Edite CrearBaseDatos.sh"
    echo "  Cambie las constantes BD y PROPIETARIO"
    echo
    echo "4 Ejecute este script y verifique que haya trabajado bien"
    echo
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
