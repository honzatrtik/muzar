#!/bin/bash
 
# Drop an recreate db schema

cd /var/www/muzar.localhost/app && php ./console doctrine:schema:drop --force
cd /var/www/muzar.localhost/app && php ./console doctrine:schema:update --force
cd /var/www/muzar.localhost/app && php ./console doctrine:fixtures:load --no-interaction
cd /var/www/muzar.localhost/app && php ./console fos:elastica:populate
cd /var/www/muzar.localhost/app && sudo php ./console cache:clear --env=dev  --no-warmup
cd /var/www/muzar.localhost/app && sudo php ./console cache:clear --env=prod  --no-warmup
