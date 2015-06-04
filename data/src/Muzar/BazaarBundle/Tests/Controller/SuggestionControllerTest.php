<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Tests\ApiTestCase;


class SuggestionControllerTest extends ApiTestCase
{

	public function testGetWithoutQuery()
    {
		$response = $this->request('GET', '/api/suggestions');
		$json = $this->assertJsonResponse($response, 400);
    }

	public function testGet()
	{
		$response = $this->request('GET', '/api/suggestions?query=bas');
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('meta', $json);

		$this->assertArrayHasKey('queries', $json['data']);
		$this->assertArrayHasKey('ads', $json['data']);
		$this->assertArrayHasKey('categories', $json['data']);

		$this->assertCount(3, $json['data']['categories']);
	}


}
