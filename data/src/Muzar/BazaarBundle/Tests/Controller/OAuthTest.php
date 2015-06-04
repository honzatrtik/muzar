<?php

namespace Muzar\BazaarBundle\Tests\Controller;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\UserBundle\Util\UserManipulator;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\Client;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class OAuthTest extends ApiTestCase
{

	public function testGetTokenGrantTypePassword()
    {
		/** @var ClientManagerInterface $clientManager */
		$clientManager = $this->getKernel()->getContainer()->get('fos_oauth_server.client_manager.default');

		/** @var Client $client */
		$client = $clientManager->createClient();
		$client->setPublic();
		$client->setName('Test client');
		$client->setAllowedGrantTypes(array('password'));
		$clientManager->updateClient($client);

		/** @var UserManipulator $manipulator */
		$manipulator = $this->getKernel()->getContainer()->get('fos_user.util.user_manipulator');
		$user = $manipulator->create('test', 'testpass', 'test@bandzone.cz', TRUE, FALSE);


		$response = $this->request('GET', '/api/oauth/v2/token', array(
			'grant_type' => 'password',
			'username' => 'test@bandzone.cz',
			'password' => 'testpass',
			'client_id' => $client->getPublicId(),
		));

		$json = $this->assertJsonResponse($response, 200);
		$this->assertArrayHasKey('access_token', $json);
		$this->assertArrayHasKey('expires_in', $json);
		$this->assertArrayHasKey('refresh_token', $json);
    }



}
