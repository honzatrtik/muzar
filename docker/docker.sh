#!/usr/bin/env sh

docker stop cache
docker stop mysql
docker stop redis
docker stop elasticsearch
docker stop apache-php

docker rm cache
docker rm mysql
docker rm redis
docker rm elasticsearch
docker rm apache-php

docker build -t elasticsearch ./elasticsearch
docker build -t apache-php ./apache-php

docker run -v /data-cache --name cache busybox
docker run -d -p 9200:9200 -v /data --name elasticsearch elasticsearch
docker run -d -e MYSQL_PASS=muzar -e MYSQL_USER=muzar -e STARTUP_SQL=/docker/startup.sql -p 3306:3306 -v "$(pwd)/mysql:/docker" -v /var/lib/mysql --name mysql tutum/mysql
docker run -d -e "REDIS_PASS=**None**" -p 6379:6379  --name redis tutum/redis
docker run -d --volumes-from=cache -e "SYMFONY_ENV=docker" -v "$(cd ..;pwd)/data:/data" --link "elasticsearch:elasticsearch" --link "mysql:mysql" --link "redis:redis" -p 8080:80 --name apache-php apache-php
docker exec apache-php chmod a+rw /data-cache
docker exec apache-php php /data/app/console muzar:rebuild-data


