<?php

namespace Muzar\BazaarBundle\Tests;

use JsonSchema\Validator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiTestCase extends WebTestCase
{

	protected function getKernel()
	{
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		return static::$kernel;
	}

	protected function request($method, $uri, $parameters = array(), $files = array(), $server = array())
	{
		$server = array_merge(array(
			'HTTP_CONTENT_TYPE' => 'application/json',
			'HTTP_ACCEPT'       => 'application/json',
		), $server);

		$client = static::createClient();
		$client->request($method, $uri, $parameters, $files, $server);

		return $client->getResponse();
	}

	protected function assertJsonResponse(Response $response, $statusCode = 200)
	{
		$this->assertEquals($statusCode, $response->getStatusCode(), $response->getContent());
		$this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);

		$string = $response->getContent();
		$json = json_decode($string, true);

		return $json;
	}

	protected function runCommand($command, $options = array())
	{

		$application = new \Symfony\Bundle\FrameworkBundle\Console\Application($this->getKernel());
		$application->setAutoExit(FALSE);

		$options['command'] = $command;
		$application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

	}

	protected function runCommandDropCreateFixtures()
	{
		$this->runCommand('doctrine:schema:drop', array(
			'--force' => TRUE
		));
		$this->runCommand('doctrine:schema:create');
		$this->runCommand('doctrine:fixtures:load', array(
			'--no-interaction' => TRUE
		));

		$this->runCommand('fos:elastica:populate', array());
	}

}
