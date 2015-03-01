#!/bin/sh
"php" "/data/app/console" "fos:elastica:reset"
"php" "/data/app/console" "doctrine:schema:drop" "--force"
"php" "/data/app/console" "doctrine:schema:update" "--force"
"php" "/data/app/console" "doctrine:fixtures:load" "--no-interaction"
"php" "/data/app/console" "fos:elastica:populate"
"php" "/data/app/console" "cache:clear" "--env=docker" "--no-warmup"
