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

		$uniq = uniqid();

		/** @var Client $client */
		$client = $clientManager->createClient();
		$client->setPublic();
		$client->setName($uniq);
		$client->setAllowedGrantTypes(array('password'));
		$clientManager->updateClient($client);


		$user = $uniq;
		$password = $uniq;
		$email = $uniq . '@muzar.cz';


		/** @var UserManipulator $manipulator */
		$manipulator = $this->getKernel()->getContainer()->get('fos_user.util.user_manipulator');
		$user = $manipulator->create($user, $password, $email, TRUE, FALSE);


		$response = $this->request('GET', '/api/oauth/v2/token', array(
			'grant_type' => 'password',
			'username' => $user,
			'password' => $password,
			'client_id' => $client->getPublicId(),
		));

		$json = $this->assertJsonResponse($response, 200);
		$this->assertArrayHasKey('access_token', $json);
		$this->assertArrayHasKey('expires_in', $json);
		$this->assertArrayHasKey('refresh_token', $json);
    }



}
