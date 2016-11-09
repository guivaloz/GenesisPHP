#!/bin/bash

#
# GenesisPHP - Crear Nuevo Sistema
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
# Este bash script sirve para crear la estructura básica de un nuevo sistema
# 1. Debe estar en un lugar de sus rutas PATH para ejecutarlo con comodidad
# 2. Cambie GENESISPHP_DIR a la ruta local donde tenga GenesisPHP
# 3. Ejecútelo en un directorio vacío
#

# Yo soy
SOY="[Crear Nuevo Sistema]"

# Constantes que definen los tipos de errores
EXITO=0
E_FATAL=99

# Nombres de los directorios
#GENESISPHP_DIR="$HOME/Documentos/GitHub/guivaloz/GenesisPHP"
EVA_DIR="$GENESISPHP_DIR/Eva"

# Si no se ha configurado este bash script
if [ -z "$GENESISPHP_DIR" ]; then
    echo "$SOY ERROR: No está definido el directorio GenesisPHP. Revise los nombres de los directorios configurados en este script."
    echo
    exit $E_FATAL
fi
if [ ! -d "$GENESISPHP_DIR" ]; then
    echo "$SOY ERROR: No se encuentra el directorio GenesisPHP. Revise los nombres de los directorios configurados en este script."
    echo
    exit $E_FATAL
fi

# Parámetro nombre del sistema
if [ -z "$1" ]; then
    echo "$SOY ERROR: Falta el nombre del sistema como parámetro."
    echo
    echo "Sintaxis:"
    echo "    $ IniciarNuevoSistema.sh <Sistema>"
    echo
    echo "Notas:"
    echo "  Debe ser sin espacios y 'camel case'."
    echo "  Ejecútelo en el directorio donde quiere iniciar el nuevo sistema."
    echo
    exit $E_FATAL
else
    DESTINO_DIR="$1"
fi

# Crear vínculo a Eva
if [ ! -h Eva ]; then
    echo "$SOY Creando vínculo a Eva"
    ln -s "$EVA_DIR"
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: Al tratar de crear el vínculo a Eva."
        exit $E_FATAL
    fi
fi

# Crear enlace a Tierra
if [ ! -h Tierra ]; then
    echo "$SOY Creando vínculo a Tierra"
    ln -s "$GENESISPHP_DIR/Tierra"
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: Al tratar de crear el vínculo a Tierra."
        exit $E_FATAL
    fi
fi

# Crear directorio con el sistema
if [ -d "$DESTINO_DIR" ]; then
    echo "$SOY ERROR: Ya existe el directorio $DESTINO_DIR."
    exit $E_FATAL
else
    echo "$SOY Creando directorio $DESTINO_DIR"
    mkdir "$DESTINO_DIR"
    if [ "$?" -ne $EXITO ]; then
        echo "$SOY ERROR: Al tratar de crear el directorio $DESTINO_DIR."
        exit $E_FATAL
    fi
fi

echo "$SOY Creando rama adan/bin"
mkdir -p $DESTINO_DIR/adan/bin

echo "$SOY Copiando scripts a adan/bin/"
cp -v Eva/adan/bin/CrearBaseDatos.sh                $DESTINO_DIR/adan/bin/
cp -v Eva/adan/bin/CrearComun.sh                    $DESTINO_DIR/adan/bin/
cp -v Eva/adan/bin/CrearExclusivos.sh               $DESTINO_DIR/adan/bin/
cp -v Eva/adan/bin/CrearGenesisPHP.php              $DESTINO_DIR/adan/bin/
cp -v Eva/adan/bin/CrearSobreescribir.sh            $DESTINO_DIR/adan/bin/
cp -v Eva/adan/bin/ProtegerArchivos.sh              $DESTINO_DIR/adan/bin/
cp -v Eva/adan/bin/RestaurarArchivos.sh             $DESTINO_DIR/adan/bin/
cp -v Eva/adan/bin/itrf92-inegi-spatial-ref-sys.sql $DESTINO_DIR/adan/bin/

# Antes cp -v Eva/adan/bin/Dios.sh $DESTINO_DIR/adan/bin/
# Ahora Dios.sh se arma de dos partes
echo "$SOY Armando adan/bin/Dios.sh con la definición SISTEMA_DIR=$DESTINO_DIR"
cat Eva/adan/bin/DiosParte1.txt   >  $DESTINO_DIR/adan/bin/Dios.sh
echo "SISTEMA_DIR=\"$DESTINO_DIR\"" >> $DESTINO_DIR/adan/bin/Dios.sh
cat Eva/adan/bin/DiosParte2.txt   >> $DESTINO_DIR/adan/bin/Dios.sh
chmod +x $DESTINO_DIR/adan/bin/Dios.sh

echo "$SOY Creando rama adan/lib/Semillas"
mkdir -p $DESTINO_DIR/adan/lib/Semillas
echo "$SOY Creando archivo en blanco adan/lib/Semillas/Serpiente.php"
touch $DESTINO_DIR/adan/lib/Semillas/Serpiente.php

echo "$SOY Creando directorio htdocs"
mkdir -p $DESTINO_DIR/htdocs

echo "$SOY Creando rama htdocs-sobreescribir/css"
mkdir -p $DESTINO_DIR/htdocs-sobreescribir/css
echo "$SOY Creando archivo en blanco htdocs-sobreescribir/css/estilos-propios.css"
touch $DESTINO_DIR/htdocs-sobreescribir/css/estilos-propios.css

echo "$SOY Creando rama htdocs-sobreescribir/imagenes"
mkdir -p $DESTINO_DIR/htdocs-sobreescribir/imagenes
echo "$SOY Copiando imágenes genéricas en htdocs-sobreescribir/imagenes"
cp -v Eva/htdocs/imagenes/favicon.png         $DESTINO_DIR/htdocs-sobreescribir/imagenes/
cp -v Eva/htdocs/imagenes/generic_company.png $DESTINO_DIR/htdocs-sobreescribir/imagenes/

echo "$SOY Creando rama htdocs-sobreescribir/lib/Configuracion"
mkdir -p $DESTINO_DIR/htdocs-sobreescribir/lib/Configuracion
echo "$SOY Copiando archivos de configuración a htdocs-sobreescribir/lib/Configuracion"
cp -v Eva/htdocs/lib/Configuracion/* $DESTINO_DIR/htdocs-sobreescribir/lib/Configuracion/

echo "$SOY Creando directorio sql"
mkdir -p $DESTINO_DIR/sql
echo "$SOY Copiando archivos SQL a sql"
cp -v Eva/sql/* $DESTINO_DIR/sql/
echo "$SOY Creando archivo en blanco sql/01.00-modulos-roles-insertar.sql"
touch $DESTINO_DIR/sql/01.00-modulos-roles-insertar.sql

#echo "$SOY Sigue editar Dios.sh. Cambie SISTEMA_DIR. Presione ENTER..."
#read
#nano $DESTINO_DIR/adan/bin/Dios.sh

echo "$SOY Script terminado."
exit $EXITO
