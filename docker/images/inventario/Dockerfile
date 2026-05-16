FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    libapache2-mod-php \
    php-yaml \
    php-mysql \
    php-ldap \
    phpmyadmin \
    vsftpd \
    nano curl wget unzip \
    iproute2 net-tools \
    sudo \
    && apt-get clean

# Usuario admin
RUN useradd -m -s /bin/bash administrador && \
    echo "administrador:administrador" | chpasswd && \
    usermod -aG sudo administrador

# FTP
RUN mkdir -p /var/run/vsftpd/empty
COPY vsftpd.conf /etc/vsftpd.conf

# Habilitar, configurar y restringir phpMyadmin
RUN ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf && \
    a2enconf phpmyadmin
COPY config.inc.php /etc/phpmyadmin/config.inc.php
COPY phpmyadmin-restriccion.conf /etc/apache2/conf-available/phpmyadmin-restriccion.conf
RUN a2enconf phpmyadmin-restriccion

# Habilitar mod ips reales
RUN a2enmod remoteip

COPY remoteip.conf /etc/apache2/conf-available/remoteip.conf
RUN a2enconf remoteip

# Script de inicio
COPY inicio.sh /opt/inicio.sh
RUN chmod +x /opt/inicio.sh

# Exponer puertos
EXPOSE 80 21 23000-23010

ENTRYPOINT ["/opt/inicio.sh"]
