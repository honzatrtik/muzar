<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ImageControllerTest extends ApiTestCase
{
	protected function setUp()
	{
		parent::setUp();
		copy(__DIR__ . '/test.jpg', __DIR__ . '/test-copy.jpg');
		chmod(__DIR__ . '/test-copy.jpg', 0666);
	}

	protected function tearDown()
	{
		@unlink(__DIR__ . '/test-copy.jpg');
	}

	public function testPostGet()
	{

		$file = new UploadedFile(
			__DIR__ . '/test-copy.jpg',
			'test-copy.jpg',
			NULL,
			NULL,
			NULL,
			TRUE
		);

		$parameters = [];
		$files = [
			'file' => $file,
		];
		$server = [
			'HTTP_ACCEPT'       => 'application/json',
		];

		$client = static::createClient();
		$client->request('POST', '/api/images', $parameters, $files, $server);

		$response = $client->getResponse();
		$json = $this->assertJsonResponse($response);


		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('id', $json['data']);
		$this->assertArrayHasKey('image_url', $json['data']);


		$client->request('GET', '/api/images/' . $json['data']['id']);
		$response = $client->getResponse();
		$json = $this->assertJsonResponse($response);

		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('id', $json['data']);
		$this->assertArrayHasKey('image_url', $json['data']);


	}

}
