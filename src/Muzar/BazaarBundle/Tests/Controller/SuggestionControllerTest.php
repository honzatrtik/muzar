<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Tests\ApiTestCase;


class SuggestionControllerTest extends ApiTestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->runCommandDropCreateFixtures();
	}

	public function testAll()
    {
		$response = $this->request('GET', '/api/suggestions');
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('meta', $json);
		$this->assertArrayHasKey('ads', $json['data']);
		$this->assertArrayHasKey('categories', $json['data']);
    }


}
