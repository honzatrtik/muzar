<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class ItemEditControllerTest extends ApiTestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->runCommandDropCreateFixtures();
	}


	public function testDefault()
    {
		$response = $this->request('GET', '/ads/edit');
		$this->assertEquals(200, $response->getStatusCode());
    }


}
