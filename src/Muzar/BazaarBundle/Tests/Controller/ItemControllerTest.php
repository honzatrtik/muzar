<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Entity\CategoryService;
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

	public function testPost()
	{
		/** @var CategoryService $cs */
		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');
		$categories = $cs->getSelectableCategories();

		$key = array_rand($categories);


		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => 200,
			'category' => $categories[$key]->getId(),
		));
		$json = $this->assertJsonResponse($response, 201);

		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'negotiablePrice' => TRUE,
			'category' => $categories[$key]->getId(),
		));
		$json = $this->assertJsonResponse($response, 201);
	}

	public function testPostInvalid()
	{
		/** @var CategoryService $cs */
		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');
		$categories = $cs->getSelectableCategories();
		$key = array_rand($categories);


		// Empty name
		$response = $this->request('POST', '/api/ads', array(
			'description' => '',
			'price' => 200,
			'category' => $categories[$key]->getId(),
		));
		$json = $this->assertJsonResponse($response, 400);

		// Invalid price
		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => -2,
			'category' => $categories[$key]->getId(),
		));
		$json = $this->assertJsonResponse($response, 400);

		// Invalid price
		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => 'cena',
			'category' => $categories[$key]->getId(),
		));
		$json = $this->assertJsonResponse($response, 400);

		// Empty cat.
		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => 'cena',
		));
		$json = $this->assertJsonResponse($response, 400);
	}


	public function testPut()
	{
		/** @var CategoryService $cs */
		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');
		$categories = $cs->getSelectableCategories();

		$key = array_rand($categories);

		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => 200,
			'category' => $categories[$key]->getId(),
		));
		$json = $this->assertJsonResponse($response, 201);
		$this->assertArrayHasKey('id', $json);

		$url = '/api/ads/' . $json['id'];


		$response = $this->request('PUT', $url,  array(
			'name' => 'XXX',
			'description' => '',
			'price' => 200,
			'category' => $categories[$key]->getId(),
		));
		$json = $this->assertJsonResponse($response, 200);


		$response = $this->request('GET', $url);
		$json = $this->assertJsonResponse($response, 200);
		$this->assertArrayHasKey('name', $json);
		$this->assertEquals('XXX', $json['name']);

	}


}
