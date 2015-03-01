#!/bin/bash

/bin/sh /initialize.sh

chown www-data:www-data /data -R
source /etc/apache2/envvars
exec apache2 -D FOREGROUND