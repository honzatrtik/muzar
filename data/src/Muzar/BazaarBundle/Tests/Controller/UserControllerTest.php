<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class UserControllerTest extends ApiTestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->runCommandDropCreateFixtures();
	}


	public function testGetNotLoggedIn()
    {
		$response = $this->request('GET', '/api/users/current');
		$json = $this->assertJsonResponse($response, 401);
    }

	public function testGet()
	{
		$this->setUsername('jan.novak@mailinator.com');
		$response = $this->request('GET', '/api/users/current');
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('id', $json);
		$this->assertArrayHasKey('username', $json);
		$this->assertArrayHasKey('email', $json);
		$this->assertArrayNotHasKey('password', $json);
	}

	public function testGetItemSearchQueriesNotLoggedIn()
	{
		$response = $this->request('GET', '/api/users/current/watchdog');
		$json = $this->assertJsonResponse($response, 401);
	}


	public function testGetItemSearchQueries()
	{
		$this->setUsername('jan.novak@mailinator.com');
		$response = $this->request('GET', '/api/users/current/watchdog');
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('meta', $json);
		$this->assertArrayHasKey('data', $json);
	}

}
