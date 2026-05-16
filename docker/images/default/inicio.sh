#!/bin/bash

service apache2 start
service vsftpd start

tail -f /dev/null
