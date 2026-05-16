FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    libapache2-mod-php \
    vsftpd \
    nano \
    curl \
    wget \
    unzip \
    iproute2 \
    net-tools \
    sudo \
    && apt-get clean

# Usuario administrador
RUN useradd -m -s /bin/bash administrador && \
    echo "administrador:administrador" | chpasswd && \
    usermod -aG sudo administrador

# FTP
RUN mkdir -p /var/run/vsftpd/empty

COPY inicio.sh /opt/inicio.sh
RUN chmod +x /opt/inicio.sh

EXPOSE 80 21

ENTRYPOINT ["/opt/inicio.sh"]
