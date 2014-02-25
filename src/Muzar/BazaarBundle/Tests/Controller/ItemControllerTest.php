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
}
