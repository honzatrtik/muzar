<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use Muzar\BazaarBundle\Tests\ApiTestCase;


class ItemReplyControllerTest extends ApiTestCase
{

	public function testPost()
    {
		$response = $this->request('POST', '/api/ads/1/replies', array(

		));
		$json = $this->assertJsonResponse($response, 204);

    }


}
