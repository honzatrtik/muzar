<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class ItemControllerTest extends ApiTestCase
{

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


		$this->assertEquals(count($json['data']), $json['meta']['total']);
		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('meta', $json);
		$this->assertArrayHasKey('nextLink', $json['meta']);
		$this->assertArrayHasKey('query', $json['meta']);
		$this->assertArrayHasKey('total', $json['meta']);
		$this->assertArrayHasKey('query', $json['meta']['query']);
		$this->assertEquals('kytara', $json['meta']['query']['query']);
	}

	public function testAllPriceFilter()
	{
		$response = $this->request('GET', '/api/ads', array(
			'priceFrom' => 10000,
			'priceTo' => 20000,
		));
		$json = $this->assertJsonResponse($response, 200);

		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('meta', $json);
		$this->assertArrayHasKey('nextLink', $json['meta']);

		foreach($json['data'] as $item)
		{
			$this->assertGreaterThanOrEqual(10000, $item['price']);
			$this->assertLessThanOrEqual(20000, $item['price']);
		}

	}

	public function testGet()
	{
		$id = 1;

		$response = $this->request('GET', '/api/ads/' . $id);
		$json = $this->assertJsonResponse($response, 200);


		$this->assertArrayHasKey('data', $json);
		$this->assertArrayHasKey('id', $json['data']);
		$this->assertArrayHasKey('slug', $json['data']);
		$this->assertArrayHasKey('link', $json['data']);
	}

	public function testPost()
	{
		$this->setUsername('jan.novak@mailinator.com');

		/** @var CategoryService $cs */
		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');
		$categories = $cs->getSelectableCategories();

		$key = array_rand($categories);


		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => 200,
			'category' => $categories[$key]->getId(),
			'contact' => array(
				'name' => 'Jan Trtik',
				'place' => array (
					'lat' => 23,
					'lng' => 22,
					'place_id' => 'sadasd',
					'address_components' => array (
						'locality' => 'Pribram',
						'country' => 'CR',
						'administrative_area_level_1' => 'Stredocesky kraj',
						'administrative_area_level_2' => 'Pribram',
					),

				),
				'email' => 'pepik@novak.cz',
			),
		));
		$json = $this->assertJsonResponse($response, 201);

		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'negotiablePrice' => TRUE,
			'category' => $categories[$key]->getId(),
			'contact' => array(
				'name' => 'Jan Trtik',
				'place' => array (
					'lat' => 23,
					'lng' => 22,
					'place_id' => 'sadasd',
					'address_components' => array (
						'locality' => 'Pribram',
						'country' => 'CR',
						'administrative_area_level_1' => 'Stredocesky kraj',
						'administrative_area_level_2' => 'Pribram',
					),

				),
				'email' => 'pepik@novak.cz',
			),
		));
		$json = $this->assertJsonResponse($response, 201);
	}

	public function testEmptyPost()
	{
		$this->setUsername('jan.novak@mailinator.com');
		$response = $this->request('POST', '/api/ads', array());
		$json = $this->assertJsonResponse($response, 400);
	}

	public function testPostInvalid()
	{
		$this->setUsername('jan.novak@mailinator.com');

		/** @var CategoryService $cs */
		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');
		$categories = $cs->getSelectableCategories();
		$key = array_rand($categories);


		// Empty name
		$response = $this->request('POST', '/api/ads', array(
			'description' => '',
			'price' => 200,
			'category' => $categories[$key]->getId(),
			'contact' => array(
				'name' => 'Jan Trtik',
				'place' => array (
					'lat' => 23,
					'lng' => 22,
					'place_id' => 'sadasd',
					'address_components' => array (
						'locality' => 'Pribram',
						'country' => 'CR',
						'administrative_area_level_1' => 'Stredocesky kraj',
						'administrative_area_level_2' => 'Pribram',
					),

				),
				'email' => 'pepik@novak.cz',
			),
		));
		$json = $this->assertJsonResponse($response, 400);

		// Invalid price
		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => -2,
			'category' => $categories[$key]->getId(),
			'contact' => array(
				'name' => 'Jan Trtik',
				'place' => array (
					'lat' => 23,
					'lng' => 22,
					'place_id' => 'sadasd',
					'address_components' => array (
						'locality' => 'Pribram',
						'country' => 'CR',
						'administrative_area_level_1' => 'Stredocesky kraj',
						'administrative_area_level_2' => 'Pribram',
					),

				),
				'email' => 'pepik@novak.cz',
			),
		));
		$json = $this->assertJsonResponse($response, 400);

		// Invalid price
		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => 'cena',
			'category' => $categories[$key]->getId(),
			'contact' => array(
				'email' => 'pepik@novak.cz',
			)
		));
		$json = $this->assertJsonResponse($response, 400);

		// Empty cat.
		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => 'cena',
			'contact' => array(
				'name' => 'Jan Trtik',
				'place' => array (
					'lat' => 23,
					'lng' => 22,
					'place_id' => 'sadasd',
					'address_components' => array (
						'locality' => 'Pribram',
						'country' => 'CR',
						'administrative_area_level_1' => 'Stredocesky kraj',
						'administrative_area_level_2' => 'Pribram',
					),

				),
				'email' => 'pepik@novak.cz',
			),
		));
		$json = $this->assertJsonResponse($response, 400);

		// Empty contact.email.
		$response = $this->request('POST', '/api/ads', array(
			'name' => 'Kytara',
			'description' => '',
			'price' => 'cena',
			'contact' => array(
				'name' => 'Jan Trtik',
				'place' => array (
					'lat' => 23,
					'lng' => 22,
					'place_id' => 'sadasd',
					'address_components' => array (
						'locality' => 'Pribram',
						'country' => 'CR',
						'administrative_area_level_1' => 'Stredocesky kraj',
						'administrative_area_level_2' => 'Pribram',
					),

				),
			),
		));
		$json = $this->assertJsonResponse($response, 400);
	}

//	public function testPostUnauthentificated()
//	{
//		/** @var CategoryService $cs */
//		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');
//		$categories = $cs->getSelectableCategories();
//
//		$key = array_rand($categories);
//
//
//		$response = $this->request('POST', '/api/ads', array(
//			'name' => 'Kytara',
//			'description' => '',
//			'price' => 200,
//			'category' => $categories[$key]->getId(),
//			'contact' => array(
//				'email' => 'pepik@novak.cz',
//			)
//		));
//		$json = $this->assertJsonResponse($response, 401);
//	}


//	public function testPut()
//	{
//		$this->setUsername('jan.novak@mailinator.com');
//
//		/** @var CategoryService $cs */
//		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');
//		$categories = $cs->getSelectableCategories();
//
//		$key = array_rand($categories);
//
//		$response = $this->request('POST', '/api/ads', array(
//			'name' => 'Kytara',
//			'description' => '',
//			'price' => 200,
//			'category' => $categories[$key]->getId(),
//			'contact' => array(
//				'name' => 'Jan Trtik',
//				'place' => array (
//					'lat' => 23,
//					'lng' => 22,
//					'place_id' => 'sadasd',
//					'address_components' => array (
//						'locality' => 'Pribram',
//						'country' => 'CR',
//						'administrative_area_level_1' => 'Stredocesky kraj',
//						'administrative_area_level_2' => 'Pribram',
//					),
//
//				),
//				'email' => 'pepik@novak.cz',
//			),
//		));
//		$json = $this->assertJsonResponse($response, 201);
//		$this->assertArrayHasKey('data', $json);
//		$this->assertArrayHasKey('id', $json['data']);
//
//		$url = '/api/ads/' . $json['data']['id'];
//
//
//		$response = $this->request('PUT', $url,  array(
//			'name' => 'XXX',
//			'description' => '',
//			'price' => 200,
//			'category' => $categories[$key]->getId(),
//			'contact' => array(
//				'name' => 'Jan Trtik',
//				'place' => array (
//					'lat' => 23,
//					'lng' => 22,
//					'place_id' => 'sadasd',
//					'address_components' => array (
//						'locality' => 'Pribram',
//						'country' => 'CR',
//						'administrative_area_level_1' => 'Stredocesky kraj',
//						'administrative_area_level_2' => 'Pribram',
//					),
//
//				),
//				'email' => 'pepik@novak.cz',
//			),
//		));
//		$json = $this->assertJsonResponse($response, 200);
//
//
//		$response = $this->request('GET', $url);
//		$json = $this->assertJsonResponse($response, 200);
//
//
//		$this->assertArrayHasKey('data', $json);
//		$this->assertArrayHasKey('name', $json['data']);
//		$this->assertEquals('XXX', $json['data']['name']);
//
//	}

//	public function testPutDifferentUser()
//	{
//		$this->setUsername('jan.novak@mailinator.com');
//
//		/** @var CategoryService $cs */
//		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');
//		$categories = $cs->getSelectableCategories();
//
//		$key = array_rand($categories);
//
//		$response = $this->request('POST', '/api/ads', array(
//			'name' => 'Kytara',
//			'description' => '',
//			'price' => 200,
//			'category' => $categories[$key]->getId(),
//			'contact' => array(
//				'email' => 'pepik@novak.cz',
//			)
//		));
//		$json = $this->assertJsonResponse($response, 201);
//		$this->assertArrayHasKey('id', $json);
//
//		$url = '/api/ads/' . $json['id'];
//
//		$this->setUsername('josef.svoboda@mailinator.com');
//		$response = $this->request('PUT', $url,  array(
//			'name' => 'XXX',
//			'description' => '',
//			'price' => 200,
//			'category' => $categories[$key]->getId(),
//			'contact' => array(
//				'email' => 'pepik@novak.cz',
//			)
//		));
//		$json = $this->assertJsonResponse($response, 401);
//	}


}
