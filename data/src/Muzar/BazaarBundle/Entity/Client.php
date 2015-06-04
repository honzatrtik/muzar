<?php
/**
 * Date: 05/03/15
 * Time: 15:09
 */

namespace Muzar\BazaarBundle\Entity;


use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Client extends BaseClient
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function checkSecret($secret)
	{
		return ($this->isPublic() || $secret === $this->secret); // TODO, allow secret column to be nullable and use parent::checkSecret($secret)
	}


	public function isPublic()
	{
		return '' === $this->secret;
	}

	public function setPublic()
	{
		$this->secret = '';
		return $this;
	}
}