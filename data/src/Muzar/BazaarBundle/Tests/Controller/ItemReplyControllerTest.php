<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Tests\ApiTestCase;


class ItemReplyControllerTest extends ApiTestCase
{

	public function testPostEmpty()
    {
		$response = $this->request('POST', '/api/ads/1/replies', array(

		));
		$json = $this->assertJsonResponse($response, 400);
		$this->assertArrayHasKey('errors', $json);
    }

	public function testPostInvalid()
	{
		$response = $this->request('POST', '/api/ads/1/replies', array(
			'email' => '_invalid',
			'message' => 'zpravicka',
			'phone' => '1234567',
			'name' => 'pepa',
		));
		$json = $this->assertJsonResponse($response, 400);
		$this->assertArrayHasKey('errors', $json);
	}


	public function testPost()
    {
		$response = $this->request('POST', '/api/ads/1/replies', array(
			'email' => 'pepa@novak.cz',
			'message' => 'Mel bych zajem!',
			'phone' => '1234567',
			'name' => 'Pepa Novak',
		));

		$this->assertEquals(204, $response->getStatusCode());
    }


}
