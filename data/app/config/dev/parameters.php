<?php

$container->setParameter('docker_ip', getenv('DOCKER_IP') ?: '127.0.0.1');