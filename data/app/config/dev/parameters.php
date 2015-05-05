<?php

$container->setParameter('docker_ip', getenv('DOCKER_IP') ?: '192.168.99.100');