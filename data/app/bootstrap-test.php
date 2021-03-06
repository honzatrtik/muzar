<?php

use Symfony\Component\Console\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Debug\Debug;

// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
umask(0000);

set_time_limit(0);

require_once __DIR__.'/bootstrap.php.cache';
require_once __DIR__.'/AppKernel.php';

$skipRebuildData = getenv('TEST_SKIP_REBUILD_DATA') ?: FALSE;
if ($skipRebuildData)
{
	return;
}

$input = new Input\StringInput('muzar:rebuild-data');
$env = $input->getParameterOption(array('--env', '-e'), getenv('SYMFONY_ENV') ?: 'test');
$debug = FALSE;

$kernel = new AppKernel($env, $debug);
$application = new Application($kernel);
$application->setAutoExit(FALSE);
$application->run($input);
