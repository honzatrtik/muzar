<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class GeoControllerTest extends ApiTestCase
{

	public function testAll()
	{
		$response = $this->request('GET', '/api/geo/tree');
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('meta', $json);
	}

}
