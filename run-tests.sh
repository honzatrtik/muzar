#!/bin/sh
phpunit -c ./data/app/phpunit.xml.dist && cd ./data/appjs && npm test && npm run apitest
