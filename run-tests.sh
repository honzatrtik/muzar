#!/bin/sh
cd ./data/appjs && npm test && npm run apitest
./data/bin/phpunit -c ./data/app/phpunit.xml.dist