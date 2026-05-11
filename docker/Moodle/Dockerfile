FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    libapache2-mod-php \
    php-mysql \
    php-xml \
    php-gd \
    php-curl \
    php-zip \
    php-intl \
    php-mbstring \
    php-soap \
    php-ldap \
    php-bcmath \
    php-cli \
    php-common \
    phpmyadmin \
    vsftpd \
    git \
    nano curl wget unzip \
    iproute2 net-tools \
    sudo \
    && apt-get clean

# Usuario admin
RUN useradd -m -s /bin/bash administrador && \
    echo "administrador:administrador" | chpasswd && \
    usermod -aG sudo administrador

# Habilitar mods url friendly e IPs reales
RUN a2enmod rewrite remoteip

# Ajuste PHP para Moodle
RUN sed -i 's/max_input_vars = 1000/max_input_vars = 5000/' /etc/php/8.3/apache2/php.ini

# Habilitar, configurar y restringir  phpMyAdmin
RUN ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf && \
    a2enconf phpmyadmin
COPY config.inc.php /etc/phpmyadmin/config.inc.php
COPY phpmyadmin.conf /etc/phpmyadmin/conf.d/restriccion.conf

# FTP
RUN mkdir -p /var/run/vsftpd/empty
COPY vsftpd.conf /etc/vsftpd.conf

# Restricción phpMyadmin
COPY phpmyadmin.conf /etc/phpmyadmin/conf.d/restriccion.conf

# Exponer puertos
EXPOSE 80 21 22000-22010

# Script de inicio
COPY inicio.sh /opt/inicio.sh
RUN chmod +x /opt/inicio.sh


ENTRYPOINT ["/opt/inicio.sh"]
