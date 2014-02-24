<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Tests\ApiTestCase;


class CategoryControllerTest extends ApiTestCase
{

    public function testAll()
    {
		$response = $this->request('GET', '/api/categories');
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('meta', $json);
    }
}