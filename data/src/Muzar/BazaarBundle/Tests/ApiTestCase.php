<?php

namespace Muzar\BazaarBundle\Tests;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManager;
use JsonSchema\Validator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ApiTestCase extends WebTestCase
{

	/** @var  Application */
	protected $application;

	protected $username;

	protected function getKernel()
	{
		return static::$kernel;
	}

	/**
	 * Proxy for container service getter
	 * @param $service
	 * @return object
	 */
	protected function get($service)
	{
		return $this->getKernel()->getContainer()->get($service);
	}

	protected function setUp()
	{

		parent::setUp();

		static::bootKernel(array());

		/** @var EntityManager $em */
		$em = $this->get('doctrine')->getManager();

		$em->flush();
        $em->clear();
		$em->getConnection()->getConfiguration()->setSQLLogger(NULL);

	}

	protected function tearDown()
	{
		parent::tearDown();
	}

	protected function printMemoryUsage()
	{
		printf('Memory usage (currently) %dKB/ (max) %dKB' . PHP_EOL, round(memory_get_usage(true) / 1024), memory_get_peak_usage(true) / 1024);
	}

	protected function setUsername($username)
	{
		$this->username = $username;
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


	protected function getApplication()
	{
		if ($this->application === NULL)
		{
			$application = new \Symfony\Bundle\FrameworkBundle\Console\Application($this->getKernel());
			$application->setAutoExit(FALSE);
			$this->application = $application;
		}
		return $this->application;

	}

	protected function runCommand($command, $options = array())
	{
		$options['command'] = $command;
		$this->getApplication()->run(new \Symfony\Component\Console\Input\ArrayInput($options));

	}


	protected function runCommandDropCreateFixtures()
	{
		$this->runCommand('fos:elastica:reset', array(
			'--no-debug' => TRUE,
		));
		$this->runCommand('doctrine:schema:drop', array(
			'--force' => TRUE,
			'--no-debug' => TRUE,
		));
		$this->runCommand('doctrine:schema:create', array(
			'--no-debug' => TRUE,
		));
		$this->runCommand('doctrine:fixtures:load', array(
			'--no-interaction' => TRUE,
			'--no-debug' => TRUE,
		));

		$this->runCommand('fos:elastica:populate', array(
			'--no-debug' => TRUE,
		));
	}

}
