<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Tests\ApiTestCase;


class ItemControllerTest extends ApiTestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->runCommandDropCreateFixtures();
	}


	public function testAll()
    {
		$response = $this->request('GET', '/api/ads');
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('meta', $json);
		$this->assertArrayHasKey('nextLink', $json['meta']);
    }

	public function testAllQuery()
	{
		$response = $this->request('GET', '/api/ads', array(
			'query' => 'kytara',
		));
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('meta', $json);
		$this->assertArrayHasKey('nextLink', $json['meta']);
	}

	public function testGet()
	{
		$id = 1;

		$response = $this->request('GET', '/api/ads/' . $id);
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('id', $json);
		$this->assertArrayHasKey('link', $json);
	}
}
