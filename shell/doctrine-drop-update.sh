#!/bin/bash
 
# Drop an recreate db schema

cd /var/www/muzar.localhost/app && ./console doctrine:schema:drop --force
cd /var/www/muzar.localhost/app && ./console doctrine:schema:update --force
