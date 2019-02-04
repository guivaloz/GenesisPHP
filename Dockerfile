FROM php:5-apache-jessie
MAINTAINER Guillermo Valdes Lozano <guillermo@movimientolibre.com>

# Actualizar e instalar dependencias
RUN apt-get update && \
  apt-get install \
    libpq-dev \
    postgresql-client \
    mcrypt \
    libmcrypt4 \
    libmcrypt-dev \
    libpng-dev \
    libjpeg62-turbo-dev -qq -y

# Instalar para PHP soporte PostgreSQL y mcrypt
RUN docker-php-source extract && \
  docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ && \
  docker-php-ext-install gd && \
  docker-php-ext-install pgsql && \
  docker-php-ext-install mcrypt && \
  docker-php-source delete

# Copiar nuestra propio php.ini
COPY php.ini /usr/local/etc/php/

# Debe modificar los permisos SELinux
#   $ cd ~/Documentos/Docker/SmdDesarrolloWeb
#   $ sudo chcon -R -t container_file_t .
# Agregar mi usuario y grupo
RUN groupadd -g 1000 guivaloz && \
  useradd -u 1000 -g guivaloz -s /bin/bash -d /genesisphp -M guivaloz && \
  gpasswd -a guivaloz www-data

# Copiar GenesisPHP
RUN mkdir /genesisphp
WORKDIR /genesisphp
COPY Demostracion Demostracion
COPY Eva Eva
COPY Tierra Tierra
