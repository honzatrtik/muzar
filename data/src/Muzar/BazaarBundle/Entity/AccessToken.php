<?php
/**
 * Date: 05/03/15
 * Time: 15:09
 */

namespace Muzar\BazaarBundle\Entity;


use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class AccessToken extends BaseAccessToken
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var Client
	 * @ORM\ManyToOne(targetEntity="Client")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $client;

	/**
	 * @var UserInterface
	 * @ORM\ManyToOne(targetEntity="Muzar\BazaarBundle\Entity\User")
	 */
	protected $user;

}