#!/bin/bash

echo "Iniciando Apache..."
service apache2 start

echo "Iniciando vsftpd..."
service vsftpd start

echo "Contenedor listo"

tail -f /dev/null
